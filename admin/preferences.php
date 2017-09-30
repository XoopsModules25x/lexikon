<?php
/**
 *
 * Module: Lexikon
 * Author: Xavier JIMENEZ
 * Licence: GNU
 */

include_once __DIR__ . '/../../../mainfile.php';

include_once XOOPS_ROOT_PATH . '/kernel/module.php';
include_once XOOPS_ROOT_PATH . '/modules/lexikon/class/lexikontree.php'; // -- LionHell
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

if (file_exists(__DIR__ . '/../language/' . $xoopsConfig['language'] . '/main.php')) {
    include __DIR__ . '/../language/' . $xoopsConfig['language'] . '/main.php';
} else {
    include __DIR__ . '/../language/english/main.php';
}
include_once XOOPS_ROOT_PATH . '/modules/lexikon/class/Utility.php';
include_once XOOPS_ROOT_PATH . '/modules/lexikon/admin/functions.php';
include_once XOOPS_ROOT_PATH . '/kernel/module.php';
$xoopsModule = XoopsModule::getByDirname('lexikon');

ob_start();
//lx_adminmenu(0, _PREFERENCES);
$btnsbar = ob_get_contents();
ob_end_clean();

/**
 * @param $buf
 * @return mixed
 */
function addAdminMenu($buf)
{
    global $btnsbar;

    $pattern = array(
        '#admin.php?#',
        "#(<div class='content'>)#"
    );
    $replace = array(
        'preferences.php?',
        " $1 <br>" . $btnsbar . "<div style='clear: both;' class='content'>"
    );
    $html    = preg_replace($pattern, $replace, $buf);

    return $html;
}

/*
* Display and capture preferences screen
*/

if (!isset($_POST['fct'])) {
    $_GET['fct'] = $_GET['fct'] = 'preferences';
}
if (!isset($_POST['op'])) {
    $_GET['op'] = $_GET['op'] = 'showmod';
}
if (!isset($_POST['mod'])) {
    $_GET['mod'] = $_GET['mod'] = $xoopsModule->getVar('mid');
}
chdir(XOOPS_ROOT_PATH . '/modules/system/');
ob_start('addAdminMenu');
include XOOPS_ROOT_PATH . '/modules/system/admin.php';
ob_end_flush();
