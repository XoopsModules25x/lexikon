<?php
/**
 * $Id: lexikon.php,v 1.0 2015/04/07 09:23:42 Yerres Exp $
 * sitemap-plugin
 * version 1.5
 */

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

function b_sitemap_lexikon() {

    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $myts = MyTextSanitizer::getInstance();

    // Permission
    global $xoopsUser;
    $gperm_handler = xoops_gethandler('groupperm');
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
      $module_handler = xoops_gethandler('module');
    $module = $module_handler->getByDirname('lexikon');
    $module_id = $module->getVar('mid');
      $allowed_cats = $gperm_handler->getItemIds("lexikon_view", $groups, $module_id);
      $catids = implode(',', $allowed_cats);
      $catperms = " WHERE categoryID IN ($catids) ";
    $result = $db->query("SELECT categoryID, name FROM ".$db->prefix("lxcategories")." ".$catperms." ORDER BY weight");

    $ret = array() ;
    while (list($id, $name) = $db->fetchRow($result)) {
        $ret["parent"][] = array(
                               "id" => $id,
                               "title" => $myts->htmlSpecialChars($name),
                               "url" => "category.php?categoryID=$id"
                           );
    }

    return $ret;
}
