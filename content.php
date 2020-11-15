<?php
/**
 * Module: Lexikon
 * Author: Yerres
 * Licence: GNU
 */

use XoopsModules\Lexikon\{
    Helper,
    Utility
};
/** @var Helper $helper */

$GLOBALS['xoopsOption']['template_main'] = 'lx_content.tpl';
require __DIR__ . '/header.php';
require XOOPS_ROOT_PATH . '/header.php';


$helper = Helper::getInstance();

global $xoTheme, $xoopsUser;
$myts = \MyTextSanitizer::getInstance();
if (!is_object($xoopsUser) && 0 == $helper->getConfig('contentsyndication')) {
    redirect_header(XOOPS_URL . '/user.php?xoops_redirect=' . parse_url($_SERVER['SCRIPT_NAME']), 5, _NOPERM);
}

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

require __DIR__ . '/include/syndication.inc.php';
$yform->assign($xoopsTpl);

// Various strings
$xoopsTpl->assign('introcontentsyn', sprintf(_MD_LEXIKON_INTROCONTENTSYN, $xoopsConfig['sitename']));
$xoopsTpl->assign('modulename', $xoopsModule->dirname());
$xoopsTpl->assign('lang_modulename', $xoopsModule->name());
$xoopsTpl->assign('lang_moduledirname', $xoopsModule->getVar('dirname'));

$xoopsTpl->assign('xoops_pagetitle', _MD_LEXIKON_SYNDICATION . ' - ' . htmlspecialchars($xoopsModule->name()));
$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" href="assets/css/style.css" >');

// Meta data
$meta_description = _MD_LEXIKON_SYNDICATION . ' - ' . htmlspecialchars($xoopsModule->name());
if (isset($xoTheme) && is_object($xoTheme)) {
    $xoTheme->addMeta('meta', 'description', $meta_description);
} else {
    $xoopsTpl->assign('xoops_meta_description', $meta_description);
}

require XOOPS_ROOT_PATH . '/footer.php';
