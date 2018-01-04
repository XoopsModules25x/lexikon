<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

use XoopsModules\Lexikon;

$moduleDirName = basename(dirname(__DIR__));

require_once __DIR__ . '/../class/Helper.php';
require_once __DIR__ . '/../class/Utility.php';
require_once __DIR__ . '/../class/EntriesHandler.php';
require_once __DIR__ . '/../class/CategoriesHandler.php';

if (!defined('LEXIKON_MODULE_PATH')) {
    define('LEXIKON_DIRNAME', basename(dirname(__DIR__)));
    define('LEXIKON_URL', XOOPS_URL . '/modules/' . LEXIKON_DIRNAME);
    define('LEXIKON_IMAGE_URL', LEXIKON_URL . '/assets/images/');
    define('LEXIKON_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . LEXIKON_DIRNAME);
    define('LEXIKON_IMAGE_PATH', LEXIKON_ROOT_PATH . '/assets/images');
    define('LEXIKON_ADMIN_URL', LEXIKON_URL . '/admin/');
    define('LEXIKON_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . LEXIKON_DIRNAME);
    define('LEXIKON_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . LEXIKON_DIRNAME);
}

/** @var \XoopsDatabase $db */
/** @var Lexikon\Helper $helper */
/** @var Lexikon\Utility $utility */
$db           = \XoopsDatabaseFactory::getDatabase();
$helper       = Lexikon\Helper::getInstance();
$utility      = new Lexikon\Utility();
$configurator = new Lexikon\Configurator();

$helper->loadLanguage('common');

//handlers
$entriesHandler     = new Lexikon\EntriesHandler($db);
$categoriesHandler     = new Lexikon\CategoriesHandler($db);

$pathIcon16    = Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon16 = $helper->getModule()->getInfo('modicons16');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

$icons = [
    'edit'    => "<img src='" . $pathIcon16 . "/edit.png'  alt=" . _EDIT . "' align='middle'>",
    'delete'  => "<img src='" . $pathIcon16 . "/delete.png' alt='" . _DELETE . "' align='middle'>",
    'clone'   => "<img src='" . $pathIcon16 . "/editcopy.png' alt='" . _CLONE . "' align='middle'>",
    'preview' => "<img src='" . $pathIcon16 . "/view.png' alt='" . _PREVIEW . "' align='middle'>",
    'print'   => "<img src='" . $pathIcon16 . "/printer.png' alt='" . _CLONE . "' align='middle'>",
    'pdf'     => "<img src='" . $pathIcon16 . "/pdf.png' alt='" . _CLONE . "' align='middle'>",
    'add'     => "<img src='" . $pathIcon16 . "/add.png' alt='" . _ADD . "' align='middle'>",
    '0'       => "<img src='" . $pathIcon16 . "/0.png' alt='" . _ADD . "' align='middle'>",
    '1'       => "<img src='" . $pathIcon16 . "/1.png' alt='" . _ADD . "' align='middle'>",
];

// MyTextSanitizer object
$myts = \MyTextSanitizer::getInstance();

$debug = false;
