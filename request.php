<?php
/**
 * Module: Lexikon - glossary module
 * Author: hsalazar
 * Licence: GNU
 */

use Xmf\Request;

include __DIR__ . '/header.php';

global $xoTheme, $xoopsUser, $xoopsModuleConfig, $xoopsModule;

// permissions
$gpermHandler = xoops_getHandler('groupperm');
$groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$module_id    = $xoopsModule->getVar('mid');
$perm_itemid  = Request::getInt('categoryID', 0, 'POST');
if (!$gpermHandler->checkRight('lexikon_request', $perm_itemid, $groups, $module_id)) {
    redirect_header('history.go(-1)', 3, _ERRORS);
}
if (!Request::hasVar('submit', 'POST')) {
    $GLOBALS['xoopsOption']['template_main'] = 'lx_request.tpl';
    include XOOPS_ROOT_PATH . '/header.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $username_v = !empty($xoopsUser) ? $xoopsUser->getVar('uname', 'E') : '';
    $usermail_v = !empty($xoopsUser) ? $xoopsUser->getVar('email', 'E') : '';
    $notifypub  = '1';
    include __DIR__ . '/include/requestform.php';
    $xoopsTpl->assign('modulename', $xoopsModule->dirname());

    $rform->assign($xoopsTpl);

    $xoopsTpl->assign('lang_modulename', $xoopsModule->name());
    $xoopsTpl->assign('lang_moduledirname', $xoopsModule->getVar('dirname'));

    $xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($xoopsModule->name()) . ' - ' . _MD_LEXIKON_ASKFORDEF);
    $xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" href="assets/css/style.css">');
    // Meta data
    $meta_description = _MD_LEXIKON_ASKFORDEF . ' - ' . $myts->htmlSpecialChars($xoopsModule->name());
    if (isset($xoTheme) && is_object($xoTheme)) {
        $xoTheme->addMeta('meta', 'description', $meta_description);
    } else {
        $xoopsTpl->assign('xoops_meta_description', $meta_description);
    }
    include XOOPS_ROOT_PATH . '/footer.php';
} else {
    //    extract($_POST);

    $display   = 'D';
    $myts      = \MyTextSanitizer::getInstance();
    $usermail  = Request::getEmail('usermail', '', 'POST');
    $username  = Request::getString('username', '', 'POST');
    $reqterm   = Request::getString('reqterm', '', 'POST');
    $notifypub = Request::getInt('notifypub', 1, 'POST');
    $html      = Request::getInt('html', 1, 'POST');
    $smiley    = Request::getInt('smiley', 1, 'POST');
    $xcodes    = Request::getInt('xcodes', 1, 'POST');
    if ($xoopsUser) {
        $user = $xoopsUser->getVar('uid');
    } else {
        $user = _MD_LEXIKON_ANONYMOUS;
    }
    $submit  = 1;
    $date    = time();
    $offline = 1;
    $request = 1;
    $ref     = '';
    $url     = '';
    $init    = substr($reqterm, 0, 1);

    $xoopsDB->query('INSERT INTO '
                    . $xoopsDB->prefix('lxentries')
                    . " (entryID, term, init, ref, url, uid, submit, datesub, html, smiley, xcodes, offline, notifypub, request ) VALUES ('', '$reqterm', '$init', '$ref', '$url', '$user', '$submit', '$date', '$html', '$smiley', '$xcodes', '$offline', '$notifypub', '$request' )");
    $newid = $xoopsDB->getInsertId();
    // Increment author's posts count
    if (is_object($xoopsUser) && !empty($user)) {
        $memberHandler = xoops_getHandler('member');
        $submitter     = $memberHandler->getUser($user);
        if (is_object($submitter)) {
            $submitter->setVar('posts', $submitter->getVar('posts') + 1);
            $res = $memberHandler->insertUser($submitter, true);
            unset($submitter);
        }
    }
    // trigger Notification
    if (!empty($xoopsModuleConfig['notification_enabled'])) {
        global $xoopsModule;
        if (0 == $newid) {
            $newid = $xoopsDB->getInsertId();
        }
        $notificationHandler = xoops_getHandler('notification');
        $tags                = [];
        $tags['ITEM_NAME']   = $reqterm;
        $tags['DATESUB']     = formatTimestamp($date, 'd M Y');
        $tags['ITEM_URL']    = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/submit.php?suggest=' . $newid;
        $notificationHandler->triggerEvent('global', 0, 'term_request', $tags);
    }
    $adminmail = $xoopsConfig['adminmail'];

    if ($xoopsUser) {
        $logname = $xoopsUser->getVar('uname', 'E');
    } else {
        $logname = $xoopsConfig['anonymous'];
    }

    if ($xoopsUser) {
        $result = $xoopsDB->query('select email from ' . $xoopsDB->prefix('users') . " where uname='$logname'");
        list($address) = $xoopsDB->fetchRow($result);
    } else {
        $address = $xoopsConfig['adminmail'];
    }

    if (1 == $xoopsModuleConfig['mailtoadmin']) {
        $adminMessage = sprintf(_MD_LEXIKON_WHOASKED, $logname);
        $adminMessage .= '' . $reqterm . "\n";
        $adminMessage .= '' . _MD_LEXIKON_EMAILLEFT . " $address\n";
        $adminMessage .= "\n";
        if ('1' == $notifypub) {
            $adminMessage .= _MD_LEXIKON_NOTIFYONPUB;
        }
        $adminMessage .= "\n" . Request::getString('HTTP_USER_AGENT', '', 'SERVER') . "\n";
        $subject      = $xoopsConfig['sitename'] . ' - ' . _MD_LEXIKON_DEFINITIONREQ;
        $xoopsMailer  = xoops_getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setToEmails($xoopsConfig['adminmail']);
        $xoopsMailer->setFromEmail($address);
        $xoopsMailer->setFromName($xoopsConfig['sitename']);
        $xoopsMailer->setSubject($subject);
        $xoopsMailer->setBody($adminMessage);
        $xoopsMailer->send();
    }
    //send 'received!' mail
    if (1 == $xoopsModuleConfig['mailtosender'] && $address) {
        $conf_subject = _MD_LEXIKON_THANKS2;
        $userMessage  = sprintf(_MD_LEXIKON_GOODDAY2, $logname);
        $userMessage  .= "\n\n";
        $userMessage  .= sprintf(_MD_LEXIKON_THANKYOU, $xoopsConfig['sitename']);
        $userMessage  .= "\n";
        $userMessage  .= sprintf(_MD_LEXIKON_REQUESTSENT, $xoopsConfig['sitename']);
        $userMessage  .= "\n";
        $userMessage  .= "--------------\n";
        $userMessage  .= '' . $xoopsConfig['sitename'] . ' ' . _MD_LEXIKON_WEBMASTER . "\n";
        $userMessage  .= '' . $xoopsConfig['adminmail'] . '';
        $xoopsMailer  = xoops_getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setToEmails($address);
        $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
        $xoopsMailer->setFromName($xoopsConfig['sitename']);
        $xoopsMailer->setSubject($conf_subject);
        $xoopsMailer->setBody($userMessage);
        $xoopsMailer->send();

        $messagesent = sprintf(_MD_LEXIKON_MESSAGESENT, $xoopsConfig['sitename']) . '<br>' . _MD_LEXIKON_THANKS1 . '';
        $messagesent .= sprintf(_MD_LEXIKON_SENTCONFIRMMAIL, $address);
    } else {
        $messagesent = sprintf(_MD_LEXIKON_MESSAGESENT, $xoopsConfig['sitename']) . '<br>' . _MD_LEXIKON_THANKS1 . '';
    }
    redirect_header('index.php', 2, $messagesent);
}
