<?php
/**
 * Module: Lexikon - glossary module
 * Author: hsalazar
 * Licence: GNU
 */

use Xmf\Request;

include __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'lx_submit.tpl';
include XOOPS_ROOT_PATH . '/header.php';

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

global $xoTheme, $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $xoopsModule;

$result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('lxcategories') . '');
if ('0' == $xoopsDB->getRowsNum($result) && '1' == $xoopsModuleConfig['multicats']) {
    redirect_header('index.php', 1, _AM_LEXIKON_NOCOLEXISTS);
}

$op = 'form';

//if (isset($_POST['post'])) {
//    $op = trim('post');
//} elseif (isset($_POST['edit'])) {
//    $op = trim('edit');
//}

$op = Request::hasVar('post', 'POST') ? 'post' : Request::hasVar('edit', 'POST') ? 'edit' : $op;

//$suggest = isset($_GET['suggest']) ? $_GET['suggest'] : (isset($_POST['suggest']) ? $_POST['suggest'] : '');

if (!function_exists('mb_ucfirst') && function_exists('mb_substr')) {
    /**
     * @param $string
     * @return false|string
     */
    function mb_ucfirst($string)
    {
        $string = mb_ereg_replace("^[\ ]+", '', $string);
        $string = mb_strtoupper(mb_substr($string, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($string, 1, mb_strlen($string), 'UTF-8');
        return $string;
    }
}

$suggest = Request::getInt('suggest', 0, 'GET'); //isset($_GET['suggest']) ? (int)$_GET['suggest'] : 0;

if ($suggest > 0) {
    $terminosql = $xoopsDB->query('SELECT term FROM ' . $xoopsDB->prefix('lxentries') . ' WHERE datesub < ' . time() . " AND datesub > 0 AND request = '1' AND entryID = '" . $suggest . "'");
    list($termino) = $xoopsDB->fetchRow($terminosql);
} else {
    $termino = '';
}
//--- Permissions ---
$gpermHandler = xoops_getHandler('groupperm');
$groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$module_id    = $xoopsModule->getVar('mid');
$perm_itemid  = Request::getInt('categoryID', 0, 'POST');
if (!$gpermHandler->checkRight('lexikon_submit', $perm_itemid, $groups, $module_id)) {
    redirect_header('index.php', 3, _MD_LEXIKON_MUSTREGFIRST);
}
$totalcats    = $gpermHandler->getItemIds('lexikon_submit', $groups, $module_id);
$permitsubmit = count($totalcats);
if (0 == $permitsubmit && '1' == $xoopsModuleConfig['multicats']) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM);
}
switch ($op) {
    case 'post':
        //--- Captcha
        if (0 != $xoopsModuleConfig['captcha']) {
            xoops_load('XoopsCaptcha');
            if (@require_once XOOPS_ROOT_PATH . '/class/captcha/xoopscaptcha.php') {
                $xoopsCaptcha = XoopsCaptcha::getInstance();
                if (!$xoopsCaptcha->verify()) {
                    echo $xoopsCaptcha->getMessage();
                    redirect_header('javascript:history.go(-1)', 2, _CAPTCHA_INVALID_CODE);
                }
            }
        }
        //-------

        global $xoTheme, $xoopsUser, $xoopsModule, $xoopsModuleConfig;
        require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/class/Utility.php';
        $myts = MyTextSanitizer:: getInstance();
        //permissions
        $gpermHandler = xoops_getHandler('groupperm');
        $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $module_id    = $xoopsModule->getVar('mid');
        $perm_itemid  = Request::getInt('categoryID', 0, 'POST');

        $html = 1;
        if ($xoopsUser) {
            $uid = $xoopsUser->getVar('uid');
            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                $html = empty($html) ? 0 : 1;
            }
        } else {
            if (!is_object($xoopsUser)
                && $gpermHandler->checkRight('lexikon_submit', $perm_itemid, $groups, $module_id)) {
                $uid = 0;
            } else {
                redirect_header('index.php', 3, _NOPERM);
            }
        }

        $block     = isset($block) ? (int)$block : 1;
        $smiley    = isset($smiley) ? $smiley : 1;
        $xcodes    = isset($xcodes) ? $xcodes : 1;
        $breaks    = isset($breaks) ? $breaks : 1;
        $notifypub = !empty($_POST['notifypub']) ? 1 : 0;

        if (1 == $xoopsModuleConfig['multicats']) {
            $categoryID = (int)$_POST['categoryID'];
        } else {
            $categoryID = 1;
        }
        $term       = $myts->addSlashes($myts->censorString($_POST['term']));
        $definition = $myts->addSlashes($myts->censorString($_POST['definition']));
        $ref        = $myts->addSlashes($myts->censorString($_POST['ref']));
        $url        = $myts->addSlashes($_POST['url']);
        if (empty($url)) {
            $url = '';
        }
        // this is for terms with umlaut or accented initials
        $term4sql = $utility::sanitizeFieldName($myts->htmlspecialchars($_POST['term']));
        $init     = mb_substr($term4sql, 0, 1);
        $init     = preg_match('/[a-zA-Zа-яА-Я0-9]/u', $init) ? mb_strtoupper($init) : '#';

        $datesub = time();

        $submit      = 1;
        $offline     = 1;
        $request     = 0;
        $block       = 1;
        $autoapprove = 0;

        if ($gpermHandler->checkRight('lexikon_approve', $perm_itemid, $groups, $module_id)) {
            $submit      = 0;
            $offline     = 0;
            $autoapprove = 1;
        }
        // verify that the term not exists
        if ($utility::isTermPresent($term, $xoopsDB->prefix('lxentries'))) {
            redirect_header('javascript:history.go(-1)', 2, _MD_LEXIKON_ITEMEXISTS . '<br>' . $term);
        }
        $result = $xoopsDB->query('INSERT INTO '
                                  . $xoopsDB->prefix('lxentries')
                                  . " (entryID, categoryID, term, init, definition, ref, url, uid, submit, datesub, html, smiley, xcodes, breaks, block, offline, notifypub ) VALUES ('', '$categoryID', '$term', '$init', '$definition', '$ref', '$url', '$uid', '$submit', '$datesub', '$html', '$smiley', '$xcodes', '$breaks','$block', '$offline', '$notifypub')");
        $newid  = $xoopsDB->getInsertId();
        // Increment author's posts count
        if (is_object($xoopsUser) && empty($entryID) && $autoapprove) {
            $memberHandler = xoops_getHandler('member');
            $submitter     = $memberHandler->getUser($uid);
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
            $notificationHandler   = xoops_getHandler('notification');
            $tags                  = [];
            $shortdefinition       = $myts->htmlSpecialChars(xoops_substr(strip_tags($definition), 0, 45));
            $tags['ITEM_NAME']     = $term;
            $tags['ITEM_BODY']     = $shortdefinition;
            $tags['DATESUB']       = formatTimestamp($datesub, 'd M Y');
            $tags['ITEM_URL']      = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/entry.php?op=mod&entryID=' . $newid;
            $sql                   = 'SELECT name FROM ' . $xoopsDB->prefix('lxcategories') . ' WHERE categoryID=' . $categoryID;
            $result                = $xoopsDB->query($sql);
            $row                   = $xoopsDB->fetchArray($result);
            $tags['CATEGORY_NAME'] = $row['name'];
            $tags['CATEGORY_URL']  = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/category.php?categoryID=' . $categoryID;
            if (1 == $xoopsModuleConfig['autoapprove']) {
                $notificationHandler->triggerEvent('category', $categoryID, 'new_post', $tags);
                $notificationHandler->triggerEvent('global', 0, 'new_post', $tags);
                //sample: $notificationHandler->triggerEvent($category, $item_id, $events, $tags, $user_list=array(), $module_id=null, $omit_user_id=null)
            } else {
                $notificationHandler->triggerEvent('global', 0, 'term_submit', $tags);
                $notificationHandler->triggerEvent('category', 0, 'term_submit', $tags);
                if ($notifypub) {
                    require_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                    $notificationHandler->subscribe('term', $newid, 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
                }
            }
        }
        if ($result) {
            if (!is_object($xoopsUser)) {
                $username = _MD_LEXIKON_GUEST;
                $usermail = '';
            } else {
                $username = $xoopsUser->getVar('uname', 'E');
                $result   = $xoopsDB->query('select email from ' . $xoopsDB->prefix('users') . " WHERE uname='$username'");
                list($usermail) = $xoopsDB->fetchRow($result);
            }

            if (1 == $xoopsModuleConfig['mailtoadmin']) {
                $adminMessage = sprintf(_MD_LEXIKON_WHOSUBMITTED, $username);
                $adminMessage .= '<b>' . $term . "</b>\n";
                $adminMessage .= '' . _MD_LEXIKON_EMAILLEFT . " $usermail\n";
                $adminMessage .= "\n";
                if ('1' == $notifypub) {
                    $adminMessage .= _MD_LEXIKON_NOTIFYONPUB;
                }
                $adminMessage .= "\n" . $_SERVER['HTTP_USER_AGENT'] . "\n";
                $subject      = $xoopsConfig['sitename'] . ' - ' . _MD_LEXIKON_DEFINITIONSUB;
                $xoopsMailer  = xoops_getMailer();
                $xoopsMailer->useMail();
                $xoopsMailer->multimailer->isHTML(true);
                $xoopsMailer->setToEmails($xoopsConfig['adminmail']);
                $xoopsMailer->setFromEmail($usermail);
                $xoopsMailer->setFromName($xoopsConfig['sitename']);
                $xoopsMailer->setSubject($subject);
                $xoopsMailer->setBody($adminMessage);
                $xoopsMailer->send();
                $messagesent = sprintf(_MD_LEXIKON_MESSAGESENT, $xoopsConfig['sitename']) . '<br>' . _MD_LEXIKON_THANKS1 . '';
            }

            //if ($xoopsModuleConfig['autoapprove'] == 1) {
            if (1 == $autoapprove) {
                redirect_header('index.php', 2, _MD_LEXIKON_RECEIVEDANDAPPROVED);
            } else {
                //send received mail
                if (1 == $xoopsModuleConfig['mailtosender'] && $usermail) {
                    $conf_subject = _MD_LEXIKON_THANKS3;
                    $userMessage  = sprintf(_MD_LEXIKON_GOODDAY2, $username);
                    $userMessage  .= "\n\n";
                    $userMessage  .= sprintf(_MD_LEXIKON_THANKYOU3, $xoopsConfig['sitename']);
                    $userMessage  .= "\n";
                    $userMessage  .= sprintf(_MD_LEXIKON_SUBMISSIONSENT, $xoopsConfig['sitename']);
                    $userMessage  .= "\n";
                    $userMessage  .= "--------------\n";
                    $userMessage  .= '' . $xoopsConfig['sitename'] . ' ' . _MD_LEXIKON_WEBMASTER . "\n";
                    $userMessage  .= '' . $xoopsConfig['adminmail'] . '';

                    $xoopsMailer = xoops_getMailer();
                    $xoopsMailer->useMail();
                    $xoopsMailer->multimailer->isHTML(true);
                    $xoopsMailer->setToEmails($usermail);
                    $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
                    $xoopsMailer->setFromName($xoopsConfig['sitename']);
                    $xoopsMailer->setSubject($conf_subject);
                    $xoopsMailer->setBody($userMessage);
                    $xoopsMailer->send();
                    $messagesent = _MD_LEXIKON_RECEIVED . '<br>' . _MD_LEXIKON_THANKS1 . '';
                    $messagesent .= sprintf(_MD_LEXIKON_SENTCONFIRMMAIL, $usermail);
                } else {
                    $messagesent = sprintf(_MD_LEXIKON_RECEIVED) . '<br>' . _MD_LEXIKON_THANKS1 . '';
                }
                redirect_header('index.php', 2, $messagesent);
            }
        } else {
            redirect_header('submit.php', 2, _MD_LEXIKON_ERRORSAVINGDB);
        }
        exit();
        break;

    case 'form':
    default:
        global $xoopsUser, $_SERVER;
        require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/Utility.php';// to create pagetitle
        $myts = MyTextSanitizer:: getInstance();
        if (!is_object($xoopsUser)) {
            $name = _MD_LEXIKON_GUEST;
        } else {
            $name = mb_ucfirst($xoopsUser->getVar('uname'));
        }

        $xoopsTpl->assign('send_def_to', sprintf(_MD_LEXIKON_SUB_SNEWNAME, mb_ucfirst($xoopsModule->name())));
        $xoopsTpl->assign('send_def_g', sprintf(_MD_LEXIKON_SUB_SNEWNAME, mb_ucfirst($xoopsModule->name())));
        $xoopsTpl->assign('lx_user_name', $name);

        $block      = 1;
        $html       = 1;
        $smiley     = 1;
        $xcodes     = 1;
        $breaks     = 1;
        $categoryID = 0;
        $notifypub  = 1;
        $term       = $termino;
        $definition = '';
        $ref        = '';
        $url        = '';

        require_once __DIR__ . '/include/storyform.inc.php';

        $xoopsTpl->assign('modulename', $xoopsModule->dirname());

        $sform->assign($xoopsTpl);

        $xoopsTpl->assign('lang_modulename', $xoopsModule->name());
        $xoopsTpl->assign('lang_moduledirname', $xoopsModule->getVar('dirname'));
        $xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($xoopsModule->name()) . ' - ' . _MD_LEXIKON_SUBMITART);
        $xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" href="assets/css/style.css" >');
        // Meta data
        $meta_description = _MD_LEXIKON_SUBMITART . ' - ' . $myts->htmlSpecialChars($xoopsModule->name());
        if (isset($xoTheme) && is_object($xoTheme)) {
            $xoTheme->addMeta('meta', 'description', $meta_description);
        } else {
            $xoopsTpl->assign('xoops_meta_description', $meta_description);
        }

        include XOOPS_ROOT_PATH . '/footer.php';
        break;
}
