<?php
//
//  ------------------------------------------------------------------------ //

defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

/**
 * @param $category
 * @param $item_id
 * @return mixed
 */
function lexikon_notify_iteminfo($category, $item_id)
{
    /*global $xoopsModule, $xoopsModuleConfig, $xoopsConfig;

    if (empty($xoopsModule) || $xoopsModule->getVar('dirname') != 'lexikon') {
        $moduleHandler = xoops_getHandler('module');
        $module = $moduleHandler->getByDirname('lexikon');
        $configHandler = xoops_getHandler('config');
        $config = $configHandler->getConfigsByCat(0,$module->getVar('mid'));
    } else {
        $module = $xoopsModule;
        $config = $xoopsModuleConfig;
    }*/
    if (strpos(__DIR__, '/') > 0) {
        $pathparts = explode('/', __DIR__);
    } else {
        $pathparts = explode("\\", __DIR__);
    }
    $moduleDirName = $pathparts[array_search('modules', $pathparts) + 1];// checken

    if ('global' === $category) {
        $item['name'] = '';
        $item['url']  = '';

        return $item;
    }
    $item_id = (int)$item_id;

    global $xoopsDB;
    if ('category' === $category) {
        // Assume we have a valid category id
        $sql = 'SELECT name FROM ' . $xoopsDB->prefix('lxcategories') . ' WHERE categoryID = ' . $item_id;
        if (!$result = $xoopsDB->query($sql)) {
            redirect_header('index.php', 2, _ERRORS);
        }
        $result       = $xoopsDB->query($sql);
        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['name'];
        $item['url']  = XOOPS_URL . '/modules/lexikon/category.php?categoryID=' . $item_id;

        return $item;
    }

    if ('term' === $category) {
        // Assume we have a valid entry id
        $sql = 'SELECT entryID,term FROM ' . $xoopsDB->prefix('lxentries') . ' WHERE entryID = ' . $item_id;
        if (!$result = $xoopsDB->query($sql)) {
            redirect_header('index.php', 2, _ERRORS);
        }
        $result       = $xoopsDB->query($sql);
        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['term'];
        $item['url']  = XOOPS_URL . '/modules/lexikon/entry.php?entryID=' . $item_id;

        return $item;
    }
}
