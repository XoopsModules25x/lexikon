<?php
/**
 *
 * Module: lexikon
 * Licence: GNU
 */

use XoopsModules\Lexikon;

require_once __DIR__ . '/../class/Helper.php';
//require_once __DIR__ . '/../include/common.php';
$helper = Lexikon\Helper::getInstance();

$pathIcon32    = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

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
    'title' => _MI_LEXIKON_IMPORT,
    'link'  => 'admin/importwordbook.php',
    'icon'  => $pathIcon32 . '/compfile.png',

];

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
