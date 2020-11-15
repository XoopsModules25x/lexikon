<?php
/**
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */

require dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

xoops_loadLanguage('main', basename(dirname(__DIR__)));

require_once XOOPS_ROOT_PATH . '/modules/lexikon/class/Utility.php';
require_once XOOPS_ROOT_PATH . '/modules/lexikon/admin/functions.php';
require_once XOOPS_ROOT_PATH . '/kernel/module.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
$myts = \MyTextSanitizer::getInstance();

if (is_object($xoopsUser)) {
    $xoopsModule = XoopsModule::getByDirname('lexikon');
    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . '/', 1, _NOPERM);
    }
} else {
    redirect_header(XOOPS_URL . '/', 1, _NOPERM);
}
