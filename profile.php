<?php
/**
 * Module: Lexikon - glossary module
 * changes: Yerres
 * Licence: GNU
 */

include __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'lx_profile.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
global $xoopsModule, $xoopsUser;
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/class/Utility.php';
$myts = \MyTextSanitizer::getInstance();

if (empty($xoopsUser) && !$xoopsModuleConfig['authorprofile']) {
    redirect_header(XOOPS_URL . '/user.php', 3, _MD_LEXIKON_MUSTREGFIRST);
}

// User & Perm validation
$uid = isset($_GET['uid']) ? (int)$_GET['uid'] : 0;
if (empty($uid)) {
    redirect_header('index.php', 2, _ERRORS);
}
$data = $utility::getUserData($uid);
if (!$data) {
    redirect_header('index.php', 2, _MD_LEXIKON_UNKNOWNERROR);
}
//permissions
$gpermHandler = xoops_getHandler('groupperm');
$groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname('lexikon');
$module_id     = $module->getVar('mid');
$allowed_cats  = $gpermHandler->getItemIds('lexikon_view', $groups, $module_id);
$catids        = implode(',', $allowed_cats);
$catperms      = " AND categoryID IN ($catids) ";

// basic functions_navi and get user data
$thisuser = new \XoopsUser($uid);
$authname = $thisuser->getVar('uname');

// get usertotals
list($num) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*)
                                FROM ' . $xoopsDB->prefix('lxentries') . "
                                WHERE uid='" . $uid . "' " . $catperms . "
                                AND submit = '0' AND request = '0'
                                AND offline = '0'
                                "));

// total results
$authortermstotal = $num;

if ($authortermstotal >= $xoopsModuleConfig['indexperpage']) {
    $xoopsTpl->assign('navi', true);
} else {
    $xoopsTpl->assign('navi', false);
}
if (0 == $authortermstotal) {
    $xoopsTpl->assign('nothing', sprintf(_MD_LEXIKON_AUTHORPROFILENOTERM, $authname));
} else {
    $xoopsTpl->assign('nothing', false);
}
// get infotext
$result2 = $xoopsDB->query('SELECT COUNT(*)
                              FROM ' . $xoopsDB->prefix('lxentries') . "
                              WHERE uid='" . $uid . "' " . $catperms . "
                              AND offline = '1' ");
list($totalwaiting) = $xoopsDB->fetchRow($result2);
if (!$totalwaiting) {
    $xoopsTpl->assign('waiting', constant('_MD_LEXIKON_NOWAITINGTERMS'));
} else {
    $xoopsTpl->assign('waiting', sprintf(_MD_LEXIKON_AUTHORWAITING, $totalwaiting));
}

// Get all terms of this author
$utility::getAuthorProfile($uid);

// various strings
$xoopsTpl->assign('lang_modulename', $xoopsModule->name());
$xoopsTpl->assign('lang_moduledirname', $xoopsModule->getVar('dirname'));
$xoopsTpl->assign('submitted', sprintf(_MD_LEXIKON_AUTHORTERMS, $authname, $authortermstotal));
$xoopsTpl->assign('author_id', $uid);
$xoopsTpl->assign('author_name', $authname);
$xoopsTpl->assign('user_avatarurl', XOOPS_URL . '/uploads/' . $thisuser->getVar('user_avatar'));
$xoopsTpl->assign('lang_authorprofile', _MD_LEXIKON_AUTHORPROFILE);
$xoopsTpl->assign('author_name_with_link', sprintf("<a href='%s'>%s</a>", XOOPS_URL . '/userinfo.php?uid=' . $uid, $authname));

$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" href="assets/css/style.css" >');
$xoopsTpl->assign('xoops_pagetitle', _MD_LEXIKON_AUTHORPROFILE . ' - ' . $authname . ' - ' . $myts->htmlSpecialChars($xoopsModule->name()));

// Meta data
$meta_description = _MD_LEXIKON_AUTHORPROFILE . ' - ' . $authname . ' - ' . $myts->htmlSpecialChars($xoopsModule->name());
if (isset($xoTheme) && is_object($xoTheme)) {
    $xoTheme->addMeta('meta', 'description', $meta_description);
} else {
    $xoopsTpl->assign('xoops_meta_description', $meta_description);
}

require_once XOOPS_ROOT_PATH . '/footer.php';
