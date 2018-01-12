<?php
/**
 *
 * Module: Lexikon - glossary module
 * adapted from News 1.50 (c) instant-zero.com
 * changes: Yerres
 * Licence: GNU
 */

use XoopsModules\Lexikon;

include __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'lx_authorlist.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
global $xoopsUser, $xoTheme, $xoopsTpl, $authortermstotal, $xoopsModule;
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/Utility.php';
require_once XOOPS_ROOT_PATH . '/modules/lexikon/include/common.inc.php';
$authorlistext = false;
$myts          = \MyTextSanitizer::getInstance();
$utility      = new Lexikon\Utility();

if (empty($xoopsUser) && !$xoopsModuleConfig['authorprofile']) {
    redirect_header(XOOPS_URL . '/user.php', 3, _MD_LEXIKON_MUSTREGFIRST);
}
$result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('lxcategories') . ' ');
if ('0' == $xoopsDB->getRowsNum($result) && '1' == $xoopsModuleConfig['multicats']) {
    redirect_header('index.php', 3, _AM_LEXIKON_NOCOLEXISTS);
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

// --- display a list of the authors of the site ---

$uid_ids = [];
$uid_ids = $utility::getAuthors();
if (count($uid_ids) > 0) {
    $lst_uid       = implode(',', $uid_ids);
    $memberHandler = xoops_getHandler('member');
    $criteria      = new \Criteria('uid', '(' . $lst_uid . ')', 'IN');
    $tbl_users     = $memberHandler->getUsers($criteria);
    $iu            = 0;

    foreach ($tbl_users as $one_user) {
        $uname = '';
        $uname = $one_user->getVar('uname');
        if (CONFIG_EXTENDED_AUTHORLIST) {
            $xoopsTpl->assign('authorlistext', true);
            if (is_object($xoopsUser)) {
                $user_pmlink = "<a href='javascript:openWithSelfMain(\""
                               . XOOPS_URL
                               . '/pmlite.php?send2=1&amp;to_userid='
                               . $one_user->getVar('uid')
                               . "\",\"pmlite\",450,370);'><img src='"
                               . XOOPS_URL
                               . "/images/icons/pm.gif' border='0' alt=\""
                               . sprintf(_SENDPMTO, $one_user->getVar('uname'))
                               . '"></a>';
            } else {
                $user_pmlink = '';
            }
            if (is_object($xoopsUser)) {
                if ($xoopsUserIsAdmin
                    || (1 == $one_user->getVar('user_viewemail')
                        && '' != $one_user->getVar('email'))) {
                    $user_maillink = "<a href='mailto:" . $one_user->getVar('email') . "'><img src='" . XOOPS_URL . "/images/icons/email.gif' border='0' alt=\"" . sprintf(_SENDEMAILTO, $one_user->getVar('uname')) . '"></a>';
                } else {
                    $user_maillink = '';
                }
            } else {
                $user_maillink = '';
            }
            if ('' != $one_user->getVar('url')) {
                $url          = $one_user->getVar('url');
                $user_wwwlink = "<a href='" . $one_user->getVar('url') . "'><img src='" . XOOPS_URL . "/images/icons/www.gif' border='0' alt='" . _VISITWEBSITE . "' ></a>";
            } else {
                $user_wwwlink = '';
            }
            // authortotals
            list($num) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*)
                                            FROM ' . $xoopsDB->prefix('lxentries') . "
                                            WHERE uid='" . $one_user->getVar('uid') . "' " . $catperms . ' '));
            $authortotal = $num;
            // location
            if ('' != $one_user->getVar('user_from')) {
                $userfrom = $one_user->getVar('user_from');
            } else {
                $userfrom = '';
            }
            ++$iu;
            $xoopsTpl->append('authors', [
                'id'             => $iu,
                'uid'            => $one_user->getVar('uid'),
                'name'           => $uname,
                'user_avatarurl' => XOOPS_URL . '/uploads/' . $one_user->getVar('user_avatar'),
                'email'          => $user_maillink,
                'pm'             => $user_pmlink,
                'url'            => $user_wwwlink,
                'total'          => $authortotal,
                'location'       => $userfrom
            ]);
        } else {
            $xoopsTpl->assign('authorlistext', false);
            // authortotals
            list($num) = $xoopsDB->fetchRow($xoopsDB->query('
                                            SELECT COUNT(*)
                                            FROM ' . $xoopsDB->prefix('lxentries') . "
                                            WHERE uid='" . $one_user->getVar('uid') . "' " . $catperms . ' '));

            $authortotal = $num;
            ++$iu;
            $user_pmlink   = '';
            $user_maillink = '';
            $user_wwwlink  = '';
            $userfrom      = '';
            $xoopsTpl->append('authors', [
                'id'    => $iu,
                'uid'   => $one_user->getVar('uid'),
                'name'  => $uname,
                'total' => $authortotal
            ]);
        }
    }
}
// todo: pagenav
$xoopsTpl->assign('lang_modulename', $xoopsModule->name());
$xoopsTpl->assign('lang_moduledirname', $xoopsModule->dirname());

$xoopsTpl->assign('xoops_pagetitle', _MD_LEXIKON_CONTRIBUTORS . ' - ' . $myts->htmlSpecialChars($xoopsModule->name()));
$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" href="assets/css/style.css">');

// Meta data
$meta_description = _MD_LEXIKON_CONTRIBUTORS . ' - ' . $myts->htmlSpecialChars($xoopsModule->name());
if (isset($xoTheme) && is_object($xoTheme)) {
    $xoTheme->addMeta('meta', 'description', $meta_description);
} else {
    $xoopsTpl->assign('xoops_meta_description', $meta_description);
}

require_once XOOPS_ROOT_PATH . '/footer.php';
