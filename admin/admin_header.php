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
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

use Xmf\Module\Admin;
use XoopsModules\Lexikon\{
    Helper,
    Utility
};
/** @var Helper $helper */

$path = dirname(dirname(dirname(__DIR__)));
require_once $path . '/include/cp_header.php';

require_once dirname(__DIR__) . '/include/common.php';

//if functions.php file exist
// require_once  dirname(__DIR__) . '/class/Utility.php';
require_once dirname(__DIR__) . '/admin/functions.php';
// require_once  dirname(__DIR__) . '/class/LexikonTree.php'; // -- LionHell
require_once $path . '/class/xoopslists.php';
require_once $path . '/class/xoopsformloader.php';

$moduleDirName = basename(dirname(__DIR__));

$helper = Helper::getInstance();

/** @var Xmf\Module\Admin $adminObject */
$adminObject = Admin::getInstance();

$pathIcon16    = Admin::iconUrl('', 16);
$pathIcon32    = Admin::iconUrl('', 32);
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');

$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new \XoopsTpl();
}
