<?php
/**
 * $Id: submit.php v 1.0 8 May 2004 hsalazar Exp $
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */

include( "header.php" );
$xoopsOption['template_main'] = 'lx_submit.html';
include( XOOPS_ROOT_PATH . "/header.php" );

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

Global $xoTheme, $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $xoopsModule;

$result = $xoopsDB -> query( "SELECT * FROM " . $xoopsDB -> prefix( "lxcategories" ) . "" );
if ( $xoopsDB -> getRowsNum( $result ) == '0' && $xoopsModuleConfig['multicats'] == '1') {
    redirect_header( "index.php", 1, _AM_LEXIKON_NOCOLEXISTS );
    exit();
}

/*if ( !is_object( $xoopsUser ) && $xoopsModuleConfig['anonpost'] == 0 ) {
    redirect_header( "index.php", 1, _NOPERM );
    exit();
}
if ( is_object( $xoopsUser ) && $xoopsModuleConfig['allowsubmit'] == 0 ) {
    redirect_header( "index.php", 1, _NOPERM );
    exit();
}*/

$op = 'form';

if ( isset( $_POST['post'] ) ) {
    $op = trim( 'post' );
}
elseif ( isset( $_POST['edit'] ) ) {
    $op = trim( 'edit' );
}

//$suggest = isset($_GET['suggest']) ? $_GET['suggest'] : (isset($_POST['suggest']) ? $_POST['suggest'] : '');
$suggest = isset($_GET['suggest']) ? intval((int)$_GET['suggest']):0;

if ($suggest > 0) {
    $terminosql = $xoopsDB -> query( "SELECT term FROM " . $xoopsDB -> prefix( "lxentries" ) . " WHERE datesub < ".time()." AND datesub > 0 AND request = '1' AND entryID = '".$suggest."'" );
    list($termino) = $xoopsDB->fetchRow($terminosql);
} else {
    $termino = '';
}
//--- Permissions ---
$gperm_handler =& xoops_gethandler('groupperm');
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$module_id = $xoopsModule->getVar('mid');
$perm_itemid = isset($_POST['categoryID']) ? intval($_POST['categoryID']) :  0;
if (!$gperm_handler->checkRight('lexikon_submit', $perm_itemid, $groups, $module_id)) {
	redirect_header('javascript:history.go(-1)', 3, _MD_LEXIKON_MUSTREGFIRST);
	exit();
}
$totalcats = $gperm_handler->getItemIds("lexikon_submit", $groups, $module_id);
$permitsubmit =count($totalcats);
if ( $permitsubmit == 0 && $xoopsModuleConfig['multicats'] == '1') {
	redirect_header( "index.php", 3, _NOPERM );
	exit();
}
switch ( $op ) {
case 'post':
  //--- Captcha 
  if ($xoopsModuleConfig['captcha'] != 0) {
       xoops_load('XoopsCaptcha');
       if(@include_once XOOPS_ROOT_PATH."/class/captcha/xoopscaptcha.php") {
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        //if(! $xoopsCaptcha->verify($_POST["skipmember"]) ) {
          if (!$xoopsCaptcha->verify()) {
              echo  $xoopsCaptcha->getMessage();
              redirect_header("javascript:history.go(-1)", 2,  _CAPTCHA_INVALID_CODE );
          }
      //}
      }
  }
  //-------

	Global $xoTheme, $xoopsUser,$xoopsModule,$xoopsModuleConfig;
	include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar('dirname')."/include/functions.php";
	$myts = & MyTextSanitizer :: getInstance();
	//permissions
	$gperm_handler =& xoops_gethandler('groupperm');
	$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
	$module_id = $xoopsModule->getVar('mid');
	$perm_itemid = isset($_POST['categoryID']) ? intval($_POST['categoryID']) :  0;

    $html = 1;
    if ( $xoopsUser ) {
        $uid = $xoopsUser -> getVar( 'uid' );
        if ( $xoopsUser -> isAdmin( $xoopsModule -> mid() ) ) {
            $html = empty( $html ) ? 0 : 1;
        }
    } else {
        if (!is_object($xoopsUser) && ($gperm_handler->checkRight('lexikon_submit', $perm_itemid, $groups, $module_id))) {
            $uid = 0;
        } else {
            redirect_header( "index.php", 3, _NOPERM );
            exit();
        }
    }

    $block = isset( $block ) ? intval( $block ) : 1;
    $smiley = isset( $smiley ) ? intval( $smiley ) : 1;
    $xcodes = isset( $xcodes ) ? intval( $xcodes ) : 1;
    $breaks = isset( $breaks ) ? intval( $breaks ) : 1;
    //$notifypub = isset( $notifypub ) ? intval( $notifypub ) : 0;
    //$notifypub = (isset($_POST['notifypub'])) ? intval($_POST['notifypub']) : '';
    $notifypub = !empty($_POST['notifypub']) ? 1 : 0;

    if ( $xoopsModuleConfig['multicats'] == 1 ) {
        $categoryID = intval( $_POST['categoryID'] );
    } else {
        $categoryID = 1;
    }
    //$term = $myts->htmlspecialchars($_POST['term']);
    //$init = substr($term, 0, 1);
    //$definition = $myts -> addSlashes( $_POST['definition'] );
    //$ref = $myts -> addSlashes( $_POST['ref'] );
    //$term = $myts->htmlSpecialChars($myts->censorString($_POST['term'] ));
    $term = $myts->addSlashes($myts->censorString($_POST['term'] ));
    $definition = $myts -> addSlashes($myts->censorString( $_POST['definition']));
    $ref = $myts -> addSlashes($myts->censorString($_POST['ref'] ));
    $url = $myts -> addSlashes( $_POST['url'] );
    if (empty($url)) {
        $url = "";
    }
    // this is for terms with umlaut or accented initials 
    $term4sql = lx_sanitizeFieldName($myts->htmlspecialchars($_POST['term']));
 	  $init = substr($term4sql, 0, 1);
    $init = preg_match("/[a-zA-Z]/", $init)  ?  strtoupper($init) : '#';
    
    $datesub = time();

    $submit = 1;
    $offline = 1;
    $request = 0;
    $block = 1;
    $autoapprove = 0;

    /*if ( $xoopsModuleConfig['autoapprove'] == 1 ) {
        $submit = 0;
        $offline = 0;
    }*/
    if (!$gperm_handler->checkRight('lexikon_approve', $perm_itemid, $groups, $module_id)) {
        $submit = 0;
        $offline = 0;
        $autoapprove = 1;
     }
     // verify that the term not exists
    if (lx_TermExists($term,$xoopsDB->prefix('lxentries')))  redirect_header("javascript:history.go(-1)", 2,  _MD_LEXIKON_ITEMEXISTS . "<br />" . $term );
    $result = $xoopsDB -> query( "INSERT INTO " . $xoopsDB -> prefix( "lxentries" ) . " (entryID, categoryID, term, init, definition, ref, url, uid, submit, datesub, html, smiley, xcodes, breaks, block, offline, notifypub ) VALUES ('', '$categoryID', '$term', '$init', '$definition', '$ref', '$url', '$uid', '$submit', '$datesub', '$html', '$smiley', '$xcodes', '$breaks','$block', '$offline', '$notifypub')" );
	  $newid = $xoopsDB -> getInsertId();
    // Increment author's posts count
    //if ( $xoopsModuleConfig['autoapprove'] == 1 ) {
		//if (is_object($xoopsUser) && empty($entryID)) {
		if (is_object($xoopsUser) && empty($entryID) && $autoapprove) {
		  $member_handler = &xoops_gethandler('member');
			$submitter =& $member_handler -> getUser($uid);
			if (is_object($submitter) ) {
				$submitter -> setVar('posts',$submitter -> getVar('posts') + 1);
				$res=$member_handler -> insertUser($submitter, true);
				unset($submitter);
			}
		}
	//}
	// trigger Notification
	if(!empty($xoopsModuleConfig['notification_enabled']) ){
		global $xoopsModule;
		if ($newid == 0) {
			$newid = $xoopsDB->getInsertId();
		}
		$notification_handler =& xoops_gethandler('notification');
		$tags = array();
		$shortdefinition = $myts -> htmlSpecialChars(xoops_substr( strip_tags( $definition ),0,45));
		$tags['ITEM_NAME'] = $term;
		$tags['ITEM_BODY'] = $shortdefinition;
		$tags['DATESUB'] = formatTimestamp( $datesub, 'd M Y' );
		$tags['ITEM_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/entry.php?op=mod&entryID='. $newid;
		$sql = "SELECT name FROM " . $xoopsDB->prefix("lxcategories") . " WHERE categoryID=" . $categoryID;
		$result = $xoopsDB->query($sql);
		$row = $xoopsDB->fetchArray($result);
		$tags['CATEGORY_NAME'] = $row['name'];
		$tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/category.php?categoryID=' . $categoryID;
		if ( $xoopsModuleConfig['autoapprove'] == 1 ) {
			$notification_handler->triggerEvent('category', $categoryID, 'new_post', $tags);
			$notification_handler->triggerEvent('global', 0, 'new_post', $tags);
			//sample: $notification_handler->triggerEvent($category, $item_id, $events, $tags, $user_list=array(), $module_id=null, $omit_user_id=null)			
		} else {
			$notification_handler->triggerEvent('global', 0, 'term_submit', $tags);
			$notification_handler->triggerEvent('category', 0, 'term_submit', $tags);
			if ($notifypub) {
				include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
				$notification_handler->subscribe('term', $newid, 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
			}
		}
	}
    if ( $result ) {
        if (!is_object($xoopsUser)) {
            $username = _MD_LEXIKON_GUEST;
            $usermail = '';
        } else {
            $username = $xoopsUser->getVar("uname", "E");
            $result = $xoopsDB->query("select email from ".$xoopsDB->prefix("users")." WHERE uname='$username'");
            list($usermail) = $xoopsDB->fetchRow($result);
        }

        if ($xoopsModuleConfig['mailtoadmin'] == 1) {
            $adminMessage = sprintf( _MD_LEXIKON_WHOSUBMITTED, $username );
            $adminMessage .= "<b>".$term."</b>\n";
            $adminMessage .= ""._MD_LEXIKON_EMAILLEFT." $usermail\n";
            $adminMessage .= "\n";
            if ($notifypub == '1') {
                $adminMessage .= _MD_LEXIKON_NOTIFYONPUB;
            }
            $adminMessage .= "\n".$_SERVER['HTTP_USER_AGENT']."\n";
            $subject = $xoopsConfig['sitename']." - "._MD_LEXIKON_DEFINITIONSUB;
            $xoopsMailer =& getMailer();
            $xoopsMailer->useMail();
            $xoopsMailer->multimailer->IsHTML(true);
            $xoopsMailer->setToEmails($xoopsConfig['adminmail']);
            $xoopsMailer->setFromEmail($usermail);
            $xoopsMailer->setFromName($xoopsConfig['sitename']);
            $xoopsMailer->setSubject($subject);
            $xoopsMailer->setBody($adminMessage);
            $xoopsMailer->send();
            $messagesent = sprintf(_MD_LEXIKON_MESSAGESENT,$xoopsConfig['sitename'])."<br />"._MD_LEXIKON_THANKS1."";
        }

        //if ( $xoopsModuleConfig['autoapprove'] == 1 ) {
        if ( $autoapprove == 1 ) {
            redirect_header( "index.php", 2, _MD_LEXIKON_RECEIVEDANDAPPROVED );
		} else {
			//send received mail
			//if (lx_getmoduleoption('mailtosender') && $usermail) {
			if ( $xoopsModuleConfig['mailtosender'] == 1 && $usermail) {
				$conf_subject = _MD_LEXIKON_THANKS3;
				$userMessage = sprintf(_MD_LEXIKON_GOODDAY2, $username);
				$userMessage .= "\n\n";
				$userMessage .= sprintf(_MD_LEXIKON_THANKYOU3,$xoopsConfig['sitename']);
				$userMessage .= "\n";
				$userMessage .= sprintf(_MD_LEXIKON_SUBMISSIONSENT,$xoopsConfig['sitename']);
				$userMessage .= "\n";
				$userMessage .= "--------------\n";
				$userMessage .= "".$xoopsConfig['sitename']." "._MD_LEXIKON_WEBMASTER."\n";
				$userMessage .= "".$xoopsConfig['adminmail']."";

				$xoopsMailer =& getMailer();
				$xoopsMailer->useMail();
				$xoopsMailer->multimailer->IsHTML(true);
				$xoopsMailer->setToEmails($usermail);
				$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
				$xoopsMailer->setFromName($xoopsConfig['sitename']);
				$xoopsMailer->setSubject($conf_subject);
				$xoopsMailer->setBody($userMessage);
				$xoopsMailer->send();
				$messagesent = _MD_LEXIKON_RECEIVED."<br />"._MD_LEXIKON_THANKS1."";
				$messagesent .= sprintf(_MD_LEXIKON_SENTCONFIRMMAIL,$usermail);
			} else {
				$messagesent = sprintf(_MD_LEXIKON_RECEIVED)."<br />"._MD_LEXIKON_THANKS1."";
			}
			redirect_header("index.php", 2, $messagesent );
		   }
		} else {
			redirect_header( "submit.php", 2, _MD_LEXIKON_ERRORSAVINGDB );
		}
		exit();
		break;

case 'form':
default:
    global $xoopsUser, $_SERVER;
    include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/include/functions.php";// to create pagetitle
    $myts = & MyTextSanitizer :: getInstance();
    if (!is_object($xoopsUser)) {
        $name = _MD_LEXIKON_GUEST;
    } else {
        $name = ucfirst($xoopsUser->getVar("uname"));
    }

    $xoopsTpl -> assign ( 'send_def_to', sprintf(_MD_LEXIKON_SUB_SNEWNAME,ucfirst($xoopsModule->name())) );
    $xoopsTpl -> assign ( 'send_def_g', sprintf(_MD_LEXIKON_SUB_SNEWNAME,ucfirst($xoopsModule->name())) );
    $xoopsTpl -> assign ( 'lx_user_name', $name );

    $block = 1;
    $html = 1;
    $smiley = 1;
    $xcodes = 1;
    $breaks = 1;
    $categoryID = 0;
    $notifypub = 1;
    $term = $termino;
    $definition = '';
    $ref = '';
    $url = '';

    include_once 'include/storyform.inc.php';

    $xoopsTpl -> assign ( 'modulename', $xoopsModule->dirname());

    $sform->assign($xoopsTpl);

    $xoopsTpl -> assign ( 'lang_modulename', $xoopsModule->name() );
    $xoopsTpl -> assign ( 'lang_moduledirname', $xoopsModule->getVar('dirname') );
    $xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($xoopsModule->name()). ' - ' ._MD_LEXIKON_SUBMITART);
    $xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="style.css" />');
    // Meta data
    $meta_description = _MD_LEXIKON_SUBMITART. ' - ' .$myts->htmlSpecialChars($xoopsModule->name());
    if (isset($xoTheme) && is_object($xoTheme)) {
      $xoTheme->addMeta( 'meta', 'description', $meta_description);
    } else {
      $xoopsTpl->assign('xoops_meta_description', $meta_description);
    }

    include XOOPS_ROOT_PATH . '/footer.php';
    break;
}
?>