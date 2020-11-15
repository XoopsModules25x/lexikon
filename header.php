<?php
/**
 * Module: Lexikon - glossary module
 * Author: hsalazar
 * Licence: GNU
 */


use XoopsModules\Lexikon\{
    Helper
};
/** @var Helper $helper
 * {@internal $helper defined in ./include/common.php }}
 */

require dirname(__DIR__, 2) . '/mainfile.php';
require XOOPS_ROOT_PATH . '/header.php';

require __DIR__ . '/preloads/autoloader.php';
require_once __DIR__ . '/include/common.php';

$moduleDirName = basename(__DIR__);

$helper = Helper::getInstance();
// Load language files
$helper->loadLanguage('main');

$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
    require $GLOBALS['xoops']->path('class/theme.php');
    $GLOBALS['xoTheme'] = new \xos_opal_Theme();
}

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new XoopsTpl();
}
