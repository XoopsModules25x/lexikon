<?php
/**
 * Module: lexikon
 * Licence: GNU
 */

use Xmf\Module\Admin;
use XoopsModules\Lexikon\{
    Helper
};
/** @var Admin $adminObject */
/** @var Helper $helper */


include dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);
$helper = Helper::getInstance();
$helper->loadLanguage('common');
$helper->loadLanguage('feedback');

$pathIcon32 = Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    //    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
    $pathModIcon32 = $helper->url($helper->getModule()->getInfo('modicons32'));
}


$adminmenu[] = [
    'title' => _MI_LEXIKON_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png',
];

$adminmenu[] = [
    'title' => _MI_LEXIKON_ADMENU1,
    'link'  => 'admin/main.php',
    'icon'  => $pathIcon32 . '/manage.png',
];

$adminmenu[] = [
    'title' => _MI_LEXIKON_ADMENU2,
    'link'  => 'admin/category.php',
    'icon'  => $pathIcon32 . '/category.png',
];

$adminmenu[] = [
    'title' => _MI_LEXIKON_ADMENU3,
    'link'  => 'admin/entry.php',
    'icon'  => $pathIcon32 . '/add.png',
];

$adminmenu[] = [
    'title' => _MI_LEXIKON_ADMENU12,
    'link'  => 'admin/statistics.php',
    'icon'  => $pathIcon32 . '/stats.png',
];

$adminmenu[] = [
    'title' => _MI_LEXIKON_SHOWSUBMISSIONS,
    'link'  => 'admin/submissions.php',
    'icon'  => $pathIcon32 . '/event.png',
];

$adminmenu[] = [
    'title' => _MI_LEXIKON_ADMENU9,
    'link'  => 'admin/permissions.php',
    'icon'  => $pathIcon32 . '/permissions.png',
];

$adminmenu[] = [
    'title' => _MI_LEXIKON_BLOCKADMIN,
    'link'  => 'admin/myblocksadmin.php',
    'icon'  => $pathIcon32 . '/block.png',
];

$adminmenu[] = [
    'title' => _MI_LEXIKON_IMPORT,
    'link'  => 'admin/importwordbook.php',
    'icon'  => $pathIcon32 . '/compfile.png',
];

if (is_object($helper->getModule()) && $helper->getConfig('displayDeveloperTools')) {
    $adminmenu[] = [
        'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_MIGRATE'),
        'link' => 'admin/migrate.php',
        'icon' => $pathIcon32 . '/database_go.png',
    ];
}

$adminmenu[] = [
    'title' => _MI_LEXIKON_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png',
];

$adminmenu[] = [
    'title' => _MI_LEXIKON_ADMENU2,
    'link'  => 'admin/categories.php',
    'icon'  => $pathIcon32 . '/category.png',
];

$adminmenu[] = [
    'title' => _MI_LEXIKON_ADMENU3,
    'link'  => 'admin/entries.php',
    'icon'  => $pathIcon32 . '/add.png',
];
