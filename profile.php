<?php
/**
 * $Id: profile.php v 1.0 8 Apr 2011 Yerres Exp $
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 18 Dec 2011
 * adapted from News 1.50 (c) instant-zero.com
 * changes: Yerres
 * Licence: GNU
 */

include( "header.php" );
$xoopsOption['template_main'] = 'lx_profile.html';
include_once XOOPS_ROOT_PATH.'/header.php';
global $xoopsModule, $xoopsUser;
include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/include/functions.php';
$myts = MyTextSanitizer::getInstance();

if (empty($xoopsUser) && !$xoopsModuleConfig['authorprofile']) {
    redirect_header(XOOPS_URL."/user.php", 3, _MD_LEXIKON_MUSTREGFIRST);
    exit();
}

// User & Perm validation
$uid= (isset($_GET['uid'])) ? intval($_GET['uid']) : 0;
if (empty($uid)) {
    redirect_header('index.php',2,_ERRORS);
    exit();
}
$data = lx_val_user_data($uid);
if (!$data ) {
    redirect_header('index.php', 2, _MD_LEXIKON_UNKNOWNERROR);
    exit();
}
//permissions
$gperm_handler = xoops_gethandler('groupperm');
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$module_handler = xoops_gethandler('module');
$module = $module_handler->getByDirname('lexikon');
$module_id = $module->getVar('mid');
$allowed_cats = $gperm_handler->getItemIds("lexikon_view", $groups, $module_id);
$catids = implode(',', $allowed_cats);
$catperms = " AND categoryID IN ($catids) ";

// basic functions_navi and get user data
$thisuser = new XoopsUser($uid);
$authname = $thisuser->getVar('uname');

// get usertotals
list($num) = $xoopsDB->fetchRow($xoopsDB->query("SELECT COUNT(*)
                                FROM ".$xoopsDB->prefix('lxentries')."
                                WHERE uid='".$uid."' ".$catperms."
                                AND submit = '0' AND request = '0'
                                AND offline = '0'
                                "));

// total results
$authortermstotal = $num;

if ($authortermstotal >=$xoopsModuleConfig['indexperpage']) {
    $xoopsTpl->assign('navi', true);
} else {
    $xoopsTpl->assign('navi', false);
}
if ($authortermstotal == 0) {
    $xoopsTpl -> assign ( 'nothing', sprintf(_MD_LEXIKON_AUTHORPROFILENOTERM, $authname) );
} else {
    $xoopsTpl -> assign ( 'nothing', false );
}
// get infotext
$result2 = $xoopsDB -> query( "SELECT COUNT(*)
                              FROM " . $xoopsDB -> prefix( "lxentries" ) . "
                              WHERE uid='".$uid."' ".$catperms."
                              AND offline = '1' " );
list( $totalwaiting ) = $xoopsDB -> fetchRow( $result2 );
if (!$totalwaiting ) {
    $xoopsTpl -> assign ( 'waiting', constant("_MD_LEXIKON_NOWAITINGTERMS") );
} else {
    $xoopsTpl -> assign ( 'waiting', sprintf(_MD_LEXIKON_AUTHORWAITING, $totalwaiting) );
}

// Get all terms of this author
lx_AuthorProfile($uid);

// various strings
$xoopsTpl -> assign ( 'lang_modulename', $xoopsModule->name() );
$xoopsTpl -> assign ( 'lang_moduledirname', $xoopsModule->getVar('dirname') );
$xoopsTpl -> assign ( 'submitted', sprintf( _MD_LEXIKON_AUTHORTERMS, $authname, $authortermstotal) );
$xoopsTpl->assign('author_id',$uid);
$xoopsTpl->assign('author_name',$authname);
$xoopsTpl->assign('user_avatarurl', XOOPS_URL.'/uploads/'.$thisuser->getVar('user_avatar'));
$xoopsTpl->assign('lang_authorprofile',_MD_LEXIKON_AUTHORPROFILE);
$xoopsTpl->assign('author_name_with_link',sprintf("<a href='%s'>%s</a>",XOOPS_URL.'/userinfo.php?uid='.$uid,$authname));

$xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="style.css" />');
$xoopsTpl->assign('xoops_pagetitle', _MD_LEXIKON_AUTHORPROFILE . ' - ' .$authname . ' - ' . $myts->htmlSpecialChars($xoopsModule->name()) );

// Meta data
$meta_description = _MD_LEXIKON_AUTHORPROFILE . ' - ' .$authname . ' - ' . $myts->htmlSpecialChars($xoopsModule->name());
if (isset($xoTheme) && is_object($xoTheme)) {
    $xoTheme->addMeta( 'meta', 'description', $meta_description);
} else {
    $xoopsTpl->assign('xoops_meta_description', $meta_description);
}

include_once XOOPS_ROOT_PATH.'/footer.php';
