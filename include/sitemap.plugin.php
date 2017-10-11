<?php
/**
 *
 * sitemap-plugin
 * version 1.5
 */

defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * @return array
 */
function b_sitemap_lexikon()
{
    $db   = XoopsDatabaseFactory::getDatabaseConnection();
    $myts = MyTextSanitizer::getInstance();

    // Permission
    global $xoopsUser;
    $gpermHandler = xoops_getHandler('groupperm');
    $groups        = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname('lexikon');
    $module_id     = $module->getVar('mid');
    $allowed_cats  = $gpermHandler->getItemIds('lexikon_view', $groups, $module_id);
    $catids        = implode(',', $allowed_cats);
    $catperms      = " WHERE categoryID IN ($catids) ";
    $result        = $db->query('SELECT categoryID, name FROM ' . $db->prefix('lxcategories') . ' ' . $catperms . ' ORDER BY weight');

    $ret = [];
    while (list($id, $name) = $db->fetchRow($result)) {
        $ret['parent'][] = [
                            'id'    => $id,
                            'title' => $myts->htmlSpecialChars($name),
                            'url'   => "category.php?categoryID=$id"
                           ];
    }

    return $ret;
}
