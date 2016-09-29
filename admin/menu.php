<?php
/**
 *
 * Module: lexikon
 * Licence: GNU
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

$path = dirname(dirname(dirname(__DIR__)));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(__DIR__));
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module          = $moduleHandler->getByDirname($dirname);
$pathIcon32      =& $module->getInfo('icons32');
$pathModuleAdmin =& $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;

if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu              = array();
$i                      = 0;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]['link']  = 'admin/index.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/home.png';
++$i;
$adminmenu[$i]['title'] = _MI_LEXIKON_ADMENU1;
$adminmenu[$i]['link']  = 'admin/main.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/manage.png';
++$i;
$adminmenu[$i]['title'] = _MI_LEXIKON_ADMENU2;
$adminmenu[$i]['link']  = 'admin/category.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/category.png';
++$i;
$adminmenu[$i]['title'] = _MI_LEXIKON_ADMENU3;
$adminmenu[$i]['link']  = 'admin/entry.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/add.png';
++$i;
$adminmenu[$i]['title'] = _MI_LEXIKON_ADMENU12;
$adminmenu[$i]['link']  = 'admin/statistics.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/stats.png';
++$i;
$adminmenu[$i]['title'] = _MI_LEXIKON_ADMENU9;
$adminmenu[$i]['link']  = 'admin/permissions.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/permissions.png';

++$i;
$adminmenu[$i]['title'] = _MI_LEXIKON_IMPORT;
$adminmenu[$i]['link']  = 'admin/importwordbook.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/compfile.png';

++$i;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]['link']  = 'admin/about.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/about.png';
