<?php
/**
 *
 * Module: lexikon
 * Licence: GNU
 */



if (!isset($moduleDirName)) {
    $moduleDirName = basename(dirname(__DIR__));
}

if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Xmf\Module\Helper::getHelper('system');
}
$adminObject = \Xmf\Module\Admin::getInstance();

$pathIcon32    = \Xmf\Module\Admin::menuIconPath('');
//$pathModIcon32 = $moduleHelper->getModule()->getInfo('modicons32');

$moduleHelper->loadLanguage('modinfo');

$adminObject              = array();
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
