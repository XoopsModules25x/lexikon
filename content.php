<?php
/**
 * $Id: content.php v 1.0 18 Dec 2011 Yerres Exp $
 * Module: Lexikon
 * Version: v 1.00
 * Release Date: 18 Dec 2011
 * Author: Yerres
 * Licence: GNU
 */

include( "header.php" );
$xoopsOption['template_main'] = 'lx_content.html';
include XOOPS_ROOT_PATH."/header.php";
global $xoTheme, $xoopsUser, $xoopsModuleConfig;
$myts = MyTextSanitizer::getInstance();
if ( !is_object( $xoopsUser ) && $xoopsModuleConfig['contentsyndication'] == 0 ) {
    redirect_header(XOOPS_URL."/user.php?xoops_redirect=".parse_url($_SERVER['PHP_SELF']), 5, _NOPERM);
    exit();
}

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

include "include/syndication.inc.php";
$yform->assign($xoopsTpl);

// Various strings
$xoopsTpl -> assign ( 'introcontentsyn', sprintf(_MD_LEXIKON_INTROCONTENTSYN,  $xoopsConfig['sitename']) );
$xoopsTpl -> assign ( 'modulename', $xoopsModule->dirname());
$xoopsTpl -> assign ( 'lang_modulename', $xoopsModule->name() );
$xoopsTpl -> assign ( 'lang_moduledirname', $xoopsModule->getVar('dirname') );

$xoopsTpl->assign('xoops_pagetitle', _MD_LEXIKON_SYNDICATION . ' - ' . $myts->htmlSpecialChars($xoopsModule->name()));
$xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="style.css" />');

// Meta data
$meta_description = _MD_LEXIKON_SYNDICATION . ' - '.$myts->htmlSpecialChars($xoopsModule->name());
if (isset($xoTheme) && is_object($xoTheme)) {
    $xoTheme->addMeta( 'meta', 'description', $meta_description);
} else {
    $xoopsTpl->assign('xoops_meta_description', $meta_description);
}

include XOOPS_ROOT_PATH."/footer.php";
