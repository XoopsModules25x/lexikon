<?php
/**
 * Module: Lexikon - glossary module
 * Author: Yerres
 * Licence: GNU
 */
if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
include __DIR__ . '/../../mainfile.php';
include_once XOOPS_ROOT_PATH . '/header.php';
include_once XOOPS_ROOT_PATH . '/class/template.php';
$tpl = new XoopsTpl();
$tpl->xoops_setCaching(0);

global $xoopsUser, $xoopsDB, $xoopsConfig, $xoopsModuleConfig;
$myts = MyTextSanitizer:: getInstance();

//if ( !is_object( $xoopsUser ) && $xoopsModuleConfig['contentsyndication'] == 0 ) {
if ($xoopsModuleConfig['contentsyndication'] == 0) {
    echo ' ' . _NOPERM . ' ';
    exit();
}
//permissions
$groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gpermHandler = xoops_getHandler('groupperm');
$module_id    = $xoopsModule->getVar('mid');

$tpl->assign('multicats', (int)$xoopsModuleConfig['multicats']);

// To display the syndicated definition
//list($numrows) = $xoopsDB -> fetchRow($xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB -> prefix("lxentries")." WHERE offline = '0' AND submit = '0' AND request = '0'"));
$resultX = $xoopsDB->query('
    SELECT a.entryID, a.categoryID, a.term, a.offline, b.*
    FROM ' . $xoopsDB->prefix('lxentries') . ' a, ' . $xoopsDB->prefix('group_permission') . " b
    WHERE a.categoryID = b.gperm_itemid AND b.gperm_modid = $module_id AND b.gperm_name = \"lexikon_view\" AND b.gperm_groupid = $groups[0]  AND offline = '0' AND submit = '0' ");
$numrows = $xoopsDB->getRowsNum($resultX);

if ($numrows > 1) {
    --$numrows;
    mt_srand((double)microtime() * 1000000);
    $entrynumber = mt_rand(0, $numrows);
} else {
    $entrynumber = 0;
}
$resultZ = $xoopsDB->query('SELECT a.entryID, a.categoryID, a.term, a.definition, a.offline, b.* FROM '
                           . $xoopsDB->prefix('lxentries')
                           . ' a, '
                           . $xoopsDB->prefix('group_permission')
                           . " b WHERE a.categoryID = b.gperm_itemid AND b.gperm_modid = $module_id AND b.gperm_name = \"lexikon_view\" AND b.gperm_groupid = $groups[0]  AND a.datesub < "
                           . time()
                           . " AND a.offline = '0' AND a.submit = '0' LIMIT $entrynumber, 1");

$zerotest = $xoopsDB->getRowsNum($resultZ);
if ($zerotest != 0) {
    while ($myrow = $xoopsDB->fetchArray($resultZ)) {
        $syndication         = array();
        $syndication['id']   = $myrow['entryID'];
        $syndication['term'] = ucfirst($myrow['term']);
        if (!XOOPS_USE_MULTIBYTES) {
            $syndication['definition'] = $myts->displayTarea(xoops_substr($myrow['definition'], 0, $xoopsModuleConfig['rndlength'] - 3), 1, 1, 1, 1, 1);
            // note: if the definitions are too long try : $xoopsModuleConfig['rndlength'] -20 ) and decrease font-size:x-small below ...
        }
        if ($xoopsModuleConfig['multicats'] == 1) {
            $syndication['catID'] = $myrow['categoryID'];
            $resultY              = $xoopsDB->query('SELECT categoryID, name FROM '
                                                    . $xoopsDB->prefix('lxcategories')
                                                    . ' WHERE categoryID = '
                                                    . $myrow['categoryID']
                                                    . ' ');
            list($categoryID, $name) = $xoopsDB->fetchRow($resultY);
            $syndication['categoryname'] = $myts->displayTarea($name);
        }
    }
    $tpl->assign('syndication', $syndication);
}
$tpl->assign('lang_modulename', $xoopsModule->name());
$tpl->assign('lang_moduledirname', $xoopsModule->getVar('dirname'));
$tpl->assign('lang_sitename', $xoopsConfig['sitename']);

$tpl->display('db:lx_syndication.tpl');
