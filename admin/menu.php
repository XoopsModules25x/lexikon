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
// Load language files
$moduleHelper->loadLanguage('admin');
$moduleHelper->loadLanguage('modinfo');
$moduleHelper->loadLanguage('main');
//Menu
$adminmenu[] = [
     'title' => _AM_MODULEADMIN_HOME,
     'link'  => 'admin/index.php',
     'icon'  => $pathIcon32 . 'home.png'
];
$adminmenu[] = [
     'title' => _MI_LEXIKON_ADMENU1,
     'link'  => 'admin/main.php',
     'icon'  => $pathIcon32 . '/manage.png'
];
$adminmenu[] = [
     'title' => _MI_LEXIKON_ADMENU2,
     'link'  => 'admin/category.php',
     'icon'  => $pathIcon32 . '/category.png'
];
$adminmenu[] = [
     'title' => _MI_LEXIKON_ADMENU3,
     'link'  => 'admin/entry.php',
     'icon'  => $pathIcon32 . '/add.png'
];
$adminmenu[] = [
     'title' => _MI_LEXIKON_ADMENU12,
     'link'  => 'admin/statistics.php',
     'icon'  => $pathIcon32 . '/stats.png'
];
$adminmenu[] = [
     'title' => _AM_LEXIKON_SHOWSUBMISSIONS,
     'link'  => 'admin/submissions.php',
     'icon'  => $pathIcon32 . '/event.png'
];
$adminmenu[] = [
     'title' => _MI_LEXIKON_ADMENU9,
     'link'  => 'admin/permissions.php',
     'icon'  => $pathIcon32 . '/permissions.png'
];
$adminmenu[] = [
     'title' => _MI_LEXIKON_IMPORT,
     'link'  => 'admin/import.php',
     'icon'  => $pathIcon32 . '/compfile.png'
];
$adminmenu[] = [
     'title' => _AM_MODULEADMIN_ABOUT,
     'link'  => 'admin/about.php',
     'icon'  => $pathIcon32 . '/about.png'
];