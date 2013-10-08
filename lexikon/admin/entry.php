<?php
/**
 * $Id: entry.php v 1.0 8 May 2004 hsalazar Exp $
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Modifs: Yerres
 * Licence: GNU
 */


include( "admin_header.php" );
$myts =& MyTextSanitizer::getInstance();
xoops_cp_header();
$indexAdmin = new ModuleAdmin();
echo $indexAdmin->addNavigation('entry.php');
$indexAdmin->addItemButton(_AM_LEXIKON_CREATEENTRY, 'entry.php?op=add', 'add');
echo $indexAdmin->renderButton('left');

$op = '';
#if ( isset( $_GET['op'] ) ) $op = $_GET['op'];
#if ( isset( $_POST['op'] ) ) $op = $_POST['op'];
error_reporting(E_ALL);
 error_reporting(E_ERROR | E_WARNING | E_PARSE); 
/* -- Available operations -- */
function entryDefault() {
    global $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsModule, $entryID, $pathIcon16;
    include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
    xoops_load('XoopsUserUtility');
//    lx_adminMenu(2, _AM_LEXIKON_ENTRIES);

    $startentry = isset( $_GET['startentry'] ) ? intval( $_GET['startentry'] ) : 0;
    $startcat = isset( $_GET['startcat'] ) ? intval( $_GET['startcat'] ) : 0;
    $startsub = isset( $_GET['startsub'] ) ? intval( $_GET['startsub'] ) : 0;
    $datesub = isset( $_GET['datesub'] ) ? intval( $_GET['datesub'] ) : 0;

    $myts =& MyTextSanitizer::getInstance();

    $result01 = $xoopsDB -> query( "SELECT COUNT(*)
                                   FROM " . $xoopsDB -> prefix( "lxcategories" ) . " " );
    list( $totalcategories ) = $xoopsDB -> fetchRow( $result01 );

    $result02 = $xoopsDB -> query( "SELECT COUNT(*)
                                   FROM " . $xoopsDB -> prefix( "lxentries" ) . "
                                   WHERE submit = 0" );
    list( $totalpublished ) = $xoopsDB -> fetchRow( $result02 );

    $result03 = $xoopsDB -> query( "SELECT COUNT(*)
                                   FROM " . $xoopsDB -> prefix( "lxentries" ) . "
                                   WHERE submit = '1' AND request = '0' " );
    list( $totalsubmitted ) = $xoopsDB -> fetchRow( $result03 );

    $result04 = $xoopsDB -> query( "SELECT COUNT(*)
                                   FROM " . $xoopsDB -> prefix( "lxentries" ) . "
                                   WHERE submit = '1' AND request = '1' " );
    list( $totalrequested ) = $xoopsDB -> fetchRow( $result04 );

//    echo "<table width='100%' class='outer' style=\"margin-top: 6px; clear:both;\" cellspacing='2' cellpadding='3' border='0' ><tr>";
//    echo "<td class='odd'>" . _AM_LEXIKON_TOTALENTRIES . "</td><td align='center' class='even'>" . $totalpublished . "</td>";
//    if ($xoopsModuleConfig['multicats'] == 1) {
//        echo "<td class='odd'>" . _AM_LEXIKON_TOTALCATS . "</td><td align='center' class='even'>" . $totalcategories . "</td>";
//    }
//    echo "<td class='odd'>" . _AM_LEXIKON_TOTALSUBM . "</td><td align='center' class='even'>" . $totalsubmitted . "</td>
//    <td class='odd'>" . _AM_LEXIKON_TOTALREQ . "</td><td align='center' class='even'>" . $totalrequested . "</td>
//    </tr></table>
//    <br /><br />";
//
    /**
    * Code to show existing terms
    **/

    // create existing terms table
    $resultA1 = $xoopsDB -> query( "SELECT COUNT(*)
                                   FROM " . $xoopsDB -> prefix( "lxentries" ) . "
                                   WHERE submit = 0" );
    list( $numrows ) = $xoopsDB -> fetchRow( $resultA1 );

    $sql = "SELECT entryID, categoryID, term, uid, datesub, offline
           FROM ".$xoopsDB->prefix('lxentries')."
           WHERE submit = 0
           ORDER BY entryID DESC";
    $resultA2 = $xoopsDB -> query( $sql, $xoopsModuleConfig['perpage'], $startentry );
    $result = $xoopsDB->query($sql, $xoopsModuleConfig['perpage']);

    echo "	<table class='outer' width='100%' border='0'>
    <tr>
    <td colspan='7' class='odd'>
    <strong>". _AM_LEXIKON_SHOWENTRIES . ' (' . $totalpublished . ')'. "</strong></td></TR>";
    echo "<tr>";

    echo "<th width='40' align='center'><b>" . _AM_LEXIKON_ENTRYID . "</A></b></td>";
    if ($xoopsModuleConfig['multicats'] == 1) {
        echo "<th width='20%'  align='center'><b>" . _AM_LEXIKON_ENTRYCATNAME . "</b></td>";
    }
    echo "<th width='*' align='center'><b>" . _AM_LEXIKON_ENTRYTERM . "</b></td>
    <th width='90'  align='center'><b>" . _AM_LEXIKON_SUBMITTER . "</b></td>
    <th width='90'  align='center'><b>" . _AM_LEXIKON_ENTRYCREATED . "</b></td>
    <th width='30'  align='center'><b>" . _AM_LEXIKON_STATUS . "</b></td>
    <th width='60'  align='center'><b>" . _AM_LEXIKON_ACTION . "</b></td>
    </tr>";
    $class   = "odd";
    if ( $numrows > 0 ) // That is, if there ARE entries in the system

    {
        while ( list( $entryID, $categoryID, $term, $uid, $created, $offline ) = $xoopsDB -> fetchrow( $resultA2 ) ) {
            $resultA3 = $xoopsDB -> query( "SELECT name
                                           FROM " . $xoopsDB -> prefix( "lxcategories" ) . "
                                           WHERE categoryID = '$categoryID'" );
            list( $name ) = $xoopsDB -> fetchrow( $resultA3 );

            $sentby = XoopsUserUtility::getUnameFromId($uid);
            $catname = $myts -> htmlSpecialChars( $name );
            $term = $myts -> htmlSpecialChars( $term );
            $created= formatTimestamp( $created, 's' );
            $modify = "<a href='entry.php?op=mod&entryID=" . $entryID . "'><img src=" . $pathIcon16."/edit.png width='16' height='16' ALT='"._AM_LEXIKON_EDITENTRY."'></a>";
            $delete = "<a href='entry.php?op=del&entryID=" . $entryID . "'><img src=" . $pathIcon16."/delete.png width='16' height='16' ALT='"._AM_LEXIKON_DELETEENTRY."'></a>";

            if ( $offline == 0 ) {
                $status = "<img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/on.gif alt='"._AM_LEXIKON_ENTRYISON."'>";
            } else {
                $status = "<img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/off.gif alt='"._AM_LEXIKON_ENTRYISOFF."'>";
            }
            echo "<tr class='" . $class . "'>";
            $class = ($class == "even") ? "odd" : "even";

            echo "<td align='center'>" . $entryID . "</td>";

            if ($xoopsModuleConfig['multicats'] == 1) {
                echo "<td class='odd' align='left'>" . $catname . "</td>";
            }
            //echo "<td class='$class'align='left'>" . $term . "</td>";
            echo "<td class='odd'align='left'><a href='../entry.php?entryID=" . $entryID . "'>" . $term . "</td>
            <td class='odd' align='center'>" . $sentby . "</td>
            <td class='odd' align='center'>" . $created . "</td>
            <td class='odd' align='center'>" . $status . "</td>
            <td class='even' align='center'> $modify $delete </td>
            </tr></DIV>";
        }
    }
    else // that is, $numrows = 0, there's no entries yet
    {
        echo "<tr>";
        echo "<td class='odd' align='center' colspan= '7'>"._AM_LEXIKON_NOTERMS."</td>";
        echo "</tr></DIV>";
    }
    echo "</table>\n";
    $pagenav = new XoopsPageNav( $numrows, $xoopsModuleConfig['perpage'], $startentry, 'startentry');
    echo '<div style="text-align:right;">' . $pagenav -> renderNav(8) . '</div>';
    echo "<br /><BR>\n";
    echo "</div>";
}

// -- Edit function --
function entryEdit( $entryID = '' ) {
  global $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsModule, $init;
  $myts =& MyTextSanitizer::getInstance();
  /**
   * Clear all variables before we start
   */
	if(!isset($block)) { $block = 1; }
	if(!isset($html)) { $html = 1; }
	if(!isset($smiley)) { $smiley = 1; }
	if(!isset($xcodes)) { $xcodes = 1; }
	if(!isset($breaks)) { $breaks = 1; }
	if(!isset($offline)) { $offline = 0; }
	if(!isset($submit)) { $submit = 0; }
	if(!isset($request)) { $request = 0; }
	if(!isset($notifypub)) { $notifypub = 1; }
	if(!isset($categoryID)) { $categoryID = 1; }
	if(!isset($term)) { $term = ""; }
  if(!isset($init)) { $init = ""; }
  if (!isset($definition)) {
      $definition = _AM_LEXIKON_WRITEHERE;
  }
	if(!isset($ref)) { $ref = ""; }
	if(!isset($url)) { $url = ""; }
	if(!isset($datesub)) { $datesub = 0; }

    // If there is a parameter, and the id exists, retrieve data: we're editing an entry
    if ( $entryID ) {
        $result = $xoopsDB -> query( "
                                     SELECT categoryID, term, init, definition, ref, url, uid, submit, datesub, html, smiley, xcodes, breaks, block, offline, notifypub, request
                                     FROM " . $xoopsDB -> prefix( "lxentries" ) . "
                                     WHERE entryID = '$entryID'" );
        list( $categoryID, $term, $init, $definition, $ref, $url, $uid, $submit, $datesub, $html, $smiley, $xcodes, $breaks, $block, $offline, $notifypub, $request ) = $xoopsDB -> fetchrow( $result );

        if ( !$xoopsDB -> getRowsNum( $result ) ) {
            redirect_header( "index.php", 1, _AM_LEXIKON_NOENTRYTOEDIT );
            exit();
        }
        $term = $myts->stripSlashesGPC($myts->htmlSpecialChars($term));

//        lx_adminMenu(2, _AM_LEXIKON_ADMINENTRYMNGMT);

        echo "<h3 style=\"color: #2F5376; margin-top: 6px; \">" . _AM_LEXIKON_ADMINENTRYMNGMT . "</h3>";
        $sform = new XoopsThemeForm( _AM_LEXIKON_MODENTRY . ": $term" , "op", xoops_getenv( 'PHP_SELF' ) );
    } else // there's no parameter, so we're adding an entry
    {
        $result01 = $xoopsDB -> query( "SELECT COUNT(*) FROM " . $xoopsDB -> prefix( "lxcategories" ) . " " );
        list( $totalcats ) = $xoopsDB -> fetchRow( $result01 );
        if ( $totalcats == 0 && $xoopsModuleConfig['multicats'] == 1 ) {
            redirect_header( "index.php", 1, _AM_LEXIKON_NEEDONECOLUMN );
            exit();
        }
//        lx_adminMenu(2, _AM_LEXIKON_ADMINENTRYMNGMT);
        $uid = $xoopsUser->getVar('uid');
        echo "<h3 style=\"color: #2F5376; margin-top: 6px; \">" . _AM_LEXIKON_ADMINENTRYMNGMT . "</h3>";
        $sform = new XoopsThemeForm( _AM_LEXIKON_NEWENTRY, "op", xoops_getenv( 'PHP_SELF' ) );
    }

    $sform -> setExtra( 'enctype="multipart/form-data"' );
    // Category selector
    if ($xoopsModuleConfig['multicats'] == 1) {
        $mytree = new XoopsTree( $xoopsDB->prefix( "lxcategories" ), "categoryID" , "0" );
        $categoryselect = new XoopsFormSelect(_AM_LEXIKON_CATNAME, 'categoryID', $categoryID);
        $tbl = array();
        $tbl = $mytree->getChildTreeArray(0,'name');
        foreach($tbl as $oneline) {
            if ($oneline['prefix']=='.') {
                $oneline['prefix']='';
            }
            $oneline['prefix'] = str_replace('.','-',$oneline['prefix']);
            $categoryselect->addOption($oneline['categoryID'], $oneline['prefix'].' '.$oneline['name']);
        }
        $sform->addElement($categoryselect,true);
    }

    // Author selector
    ob_start();
    lx_getuserForm( intval($uid) );
    $sform -> addElement( new XoopsFormLabel( _AM_LEXIKON_AUTHOR, ob_get_contents() ) );
    ob_end_clean();

    // Initial selector
    ob_start();
    lx_getinit( intval($init) );
    $sform -> addElement( new XoopsFormLabel( _AM_LEXIKON_INIT, ob_get_contents() ) );
    ob_end_clean();

    // Term, definition, reference and related URL
    $sform -> addElement( new XoopsFormText( _AM_LEXIKON_ENTRYTERM, 'term', 50, 80, $term ), true );

    // set editor according to the module's option "form_options"
    $editor = lx_getWysiwygForm( _AM_LEXIKON_ENTRYDEF, 'definition', $definition, 15, 60 );
    if ($definition == _MD_LEXIKON_WRITEHERE) {
        $editor -> setExtra( 'onfocus="this.select()"' );
    }
    $sform -> addElement( $editor,true );
    unset($editor);

    $sform -> addElement( new XoopsFormTextArea( _AM_LEXIKON_ENTRYREFERENCE, 'ref', $ref, 5, 60 ), false );
    $sform -> addElement( new XoopsFormText( _AM_LEXIKON_ENTRYURL, 'url', 50, 80, $url ), false );

	// tags of this term - for module 'Tag'
    $module_handler = xoops_gethandler('module');
    $tagsModule = $module_handler->getByDirname("tag");
    if (is_object($tagsModule)) {
        include_once XOOPS_ROOT_PATH."/modules/tag/include/formtag.php";
        $sform->addElement(new XoopsFormTag("item_tag", 60, 255, $entryID, $catid = 0));
	}
    // Code to take entry offline, for maintenance purposes
    $offline_radio = new XoopsFormRadioYN(_AM_LEXIKON_SWITCHOFFLINE, 'offline', $offline, ' '._AM_LEXIKON_YES.'', ' '._AM_LEXIKON_NO.'');
    $sform -> addElement($offline_radio);

    // Code to put entry in block
    $block_radio = new XoopsFormRadioYN( _AM_LEXIKON_BLOCK, 'block', $block , ' ' . _AM_LEXIKON_YES . '', ' ' . _AM_LEXIKON_NO . '' );
    $sform -> addElement( $block_radio );

    // VARIOUS OPTIONS
    $options_tray = new XoopsFormElementTray(_AM_LEXIKON_OPTIONS,'<br />');
	if ($submit) {
		$notify_checkbox = new XoopsFormCheckBox('', 'notifypub', $notifypub);
		$notify_checkbox->addOption(1, _AM_LEXIKON_NOTIFYPUBLISH);
		$options_tray->addElement($notify_checkbox);
	}else{ 
		$notifypub=0;
	}
    $html_checkbox = new XoopsFormCheckBox( '', 'html', $html );
    $html_checkbox -> addOption( 1, _AM_LEXIKON_DOHTML );
    $options_tray -> addElement( $html_checkbox );

    $smiley_checkbox = new XoopsFormCheckBox( '', 'smiley', $smiley );
    $smiley_checkbox -> addOption( 1, _AM_LEXIKON_DOSMILEY );
    $options_tray -> addElement( $smiley_checkbox );

    $xcodes_checkbox = new XoopsFormCheckBox( '', 'xcodes', $xcodes );
    $xcodes_checkbox -> addOption( 1, _AM_LEXIKON_DOXCODE );
    $options_tray -> addElement( $xcodes_checkbox );

    $breaks_checkbox = new XoopsFormCheckBox( '', 'breaks', $breaks );
    $breaks_checkbox -> addOption( 1, _AM_LEXIKON_BREAKS );
    $options_tray -> addElement( $breaks_checkbox );

    $sform -> addElement( $options_tray );

    $sform -> addElement( new XoopsFormHidden( 'entryID', $entryID ) );

    $button_tray = new XoopsFormElementTray( '', '' );
    $hidden = new XoopsFormHidden( 'op', 'addentry' );
    $button_tray -> addElement( $hidden );


    if ( !$entryID ) // there's no entryID? Then it's a new entry
    {

        $butt_create = new XoopsFormButton( '', '', _AM_LEXIKON_CREATE, 'submit' );
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addentry\'"');
        $button_tray->addElement( $butt_create );

        $butt_clear = new XoopsFormButton( '', '', _AM_LEXIKON_CLEAR, 'reset' );
        $button_tray->addElement( $butt_clear );

        $butt_cancel = new XoopsFormButton( '', '', _AM_LEXIKON_CANCEL, 'button' );
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement( $butt_cancel );
    } else // else, we're editing an existing entry
    {
        $butt_create = new XoopsFormButton( '', '', _AM_LEXIKON_MODIFY, 'submit' );
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addentry\'"');
        $button_tray->addElement( $butt_create );

        $butt_cancel = new XoopsFormButton( '', '', _AM_LEXIKON_CANCEL, 'button' );
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement( $butt_cancel );
    }

    $sform -> addElement( $button_tray );
    $sform -> display();
    unset( $hidden );
}


/* Save */
function entrySave ($entryID = '') {
    Global $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $xoopsModule, $xoopsDB;
    $myts =& MyTextSanitizer::getInstance();
    $entryID = isset($_POST['entryID']) ? intval($_POST['entryID']) : intval($_GET['entryID']);
    if ($xoopsModuleConfig['multicats'] == 1) {
        $categoryID = isset($_POST['categoryID']) ? intval($_POST['categoryID']) : intval($_GET['categoryID']);
    } else {
        $categoryID = 1;
    }
    $block = isset($_POST['block']) ? intval($_POST['block']) : intval($_GET['block']);
    $breaks = isset($_POST['breaks']) ? intval($_POST['breaks']) : intval($_GET['breaks']);

    $html = isset($_POST['html']) ? intval($_POST['html']) : intval($_GET['html']);
    $smiley = isset($_POST['smiley']) ? intval($_POST['smiley']) : intval($_GET['smiley']);
    $xcodes = isset($_POST['xcodes']) ? intval($_POST['xcodes']) : intval($_GET['xcodes']);
    $offline = isset($_POST['offline']) ? intval($_POST['offline']) : intval($_GET['offline']);
    $init= $myts->addslashes($_POST['init']);
    $term = $myts->addSlashes(xoops_trim($_POST['term']));
    //$definition = $myts -> xoopsCodeDecode($_POST['definition'], $allowimage = 1);
    //$ref = isset($_POST['ref']) ? $myts->addSlashes($_POST['ref']) : '';
    $definition = $myts -> xoopsCodeDecode($myts->censorString($_POST['definition']), $allowimage = 1);
    $ref = isset($_POST['ref']) ? $myts->addSlashes($myts->censorString($_POST['ref'])) : '';
    $url = isset($_POST['url']) ? $myts->addSlashes($_POST['url']) : '';

    $date = time();
    $submit = 0;
    //$notifypub = 0;
    $notifypub = isset($_POST['notifypub']) ? intval($_POST['notifypub']) : intval($_GET['notifypub']);
    $request = 0;
    $uid = isset($_POST['author']) ? intval($_POST['author']) : $xoopsUser->uid();

	//-- module Tag
    $module_handler = xoops_gethandler('module');
    $tagsModule = $module_handler->getByDirname("tag");
    if (is_object($tagsModule)) {
        $tag_handler = xoops_getmodulehandler('tag', 'tag');
        $tag_handler->updateByItem($_POST["item_tag"], $entryID, $xoopsModule->getVar("dirname"), $catid =0);
    }
// Save to database
    if ( !$entryID ) {
        // verify that the term does not exists
        if (lx_TermExists($term,$xoopsDB->prefix('lxentries')))  redirect_header("javascript:history.go(-1)", 2,  _AM_LEXIKON_ITEMEXISTS . "<br />" . $term );
        if ( $xoopsDB -> query( "INSERT INTO " . $xoopsDB -> prefix( "lxentries" ) . " (entryID, categoryID, term, init, definition, ref, url, uid, submit, datesub, html, smiley, xcodes, breaks, block, offline, notifypub, request ) VALUES ('', '$categoryID', '$term', '$init', '$definition', '$ref', '$url', '$uid', '$submit', '$date', '$html', '$smiley', '$xcodes', '$breaks', '$block', '$offline', '$notifypub', '$request' )" ) ) {
			  $newid = $xoopsDB->getInsertId();
            // Increment author's posts count (only if it's a new definition)
            if (is_object($xoopsUser) && empty($entryID)) {
                $member_handler = &xoops_gethandler('member');
                $submitter =& $member_handler -> getUser($uid);
                if (is_object($submitter) ) {
                    $submitter -> setVar('posts',$submitter -> getVar('posts') + 1);
                    $res=$member_handler -> insertUser($submitter, true);
                    unset($submitter);
                }
            }
			// trigger Notification only if its a new definition
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
				$tags['DATESUB'] = formatTimestamp( $date, 'd M Y' );
				$tags['ITEM_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/entry.php?entryID='. $newid;
				$sql = "SELECT name FROM " . $xoopsDB->prefix("lxcategories") . " WHERE categoryID=" . $categoryID;
				$result = $xoopsDB->query($sql);
				$row = $xoopsDB->fetchArray($result);
				$tags['CATEGORY_NAME'] = $row['name'];
				$tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/category.php?categoryID=' . $categoryID;
				$notification_handler->triggerEvent('global', 0, 'new_post', $tags);
				$notification_handler->triggerEvent('category', $categoryID, 'new_post', $tags);
				//$notification_handler->triggerEvent('term', $newid, 'approve', $tags);
			}
            lx_calculateTotals();
            redirect_header( "entry.php", 1, _AM_LEXIKON_ENTRYCREATEDOK );
        } else {
            redirect_header( "index.php", 1, _AM_LEXIKON_ENTRYNOTCREATED );
        }
    } else { // That is, $entryID exists, thus we're editing an entry
        if ( $xoopsDB -> query( "UPDATE " . $xoopsDB -> prefix( "lxentries" ) . " SET term = '$term', categoryID = '$categoryID', init = '$init', definition = '$definition', ref = '$ref', url = '$url', uid = '$uid', submit = '$submit', datesub = '$date', html = '$html', smiley = '$smiley', xcodes = '$xcodes', breaks = '$breaks', block = '$block', offline = '$offline', notifypub = '$notifypub', request = '$request' WHERE entryID = '$entryID'" ) ) {
			// trigger Notification only if its a new submission
			if(!empty($xoopsModuleConfig['notification_enabled']) ){
				global $xoopsModule;
				$notification_handler =& xoops_gethandler('notification');
				$tags = array();
				$shortdefinition = $myts -> htmlSpecialChars(xoops_substr( strip_tags( $definition ),0,45));
				$tags['ITEM_NAME'] = $term;
				$tags['ITEM_BODY'] = $shortdefinition;
				$tags['DATESUB'] = formatTimestamp( $date, 'd M Y' );
				$tags['ITEM_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/entry.php?entryID='. $entryID;
				$sql = "SELECT name FROM " . $xoopsDB->prefix("lxcategories") . " WHERE categoryID=" . $categoryID;
				$result = $xoopsDB->query($sql);
				$row = $xoopsDB->fetchArray($result);
				$tags['CATEGORY_NAME'] = $row['name'];
				$tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/category.php?categoryID=' . $categoryID;
				$notification_handler->triggerEvent('global', 0, 'new_post', $tags);
				$notification_handler->triggerEvent('category', $categoryID, 'new_post', $tags);
				$notification_handler->triggerEvent('term', $entryID, 'approve', $tags);
			}

         lx_calculateTotals();
			if ($notifypub == '0'){
				redirect_header( "entry.php", 1, _AM_LEXIKON_ENTRYMODIFIED );
				exit();
			} else {
				$user = new XoopsUser($uid);
				$userMessage = sprintf(_MD_LEXIKON_GOODDAY2, $user->getVar('uname'));
				$userMessage .= "\n\n";
				if ($request == '1'){$userMessage .= sprintf(_MD_LEXIKON_CONFREQ,$xoopsConfig['sitename']);
				} else { $userMessage .= sprintf(_MD_LEXIKON_CONFSUB);}
				$userMessage .= "\n";
				$userMessage .= sprintf(_MD_LEXIKON_APPROVED,$xoopsConfig['sitename']);
				$userMessage .= "\n\n";
				$userMessage .= sprintf(_MD_LEXIKON_REGARDS);
				$userMessage .= "\n";
				$userMessage .= "__________________\n";
				$userMessage .= "".$xoopsConfig['sitename']." "._MD_LEXIKON_WEBMASTER."\n";
				$userMessage .= "".$xoopsConfig['adminmail']."";
				$xoopsMailer =& getMailer();
				$xoopsMailer->useMail();
				$xoopsMailer->setToEmails($user->getVar('email'));
				$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
				//$xoopsMailer->setFromName($xoopsConfig['sitename']." - "._MI_LEXIKON_MD_NAME);
				$xoopsMailer->setFromName($xoopsConfig['sitename']." - ".$xoopsModule->name());
				if ($request == '1'){ $conf_subject = sprintf(_MD_LEXIKON_SUBJECTREQ,$xoopsConfig['sitename']);
						} else { $conf_subject = sprintf(_MD_LEXIKON_SUBJECTSUB,$xoopsConfig['sitename']);}
				$xoopsMailer->setSubject($conf_subject);			
				$xoopsMailer->setBody($userMessage);
				$xoopsMailer->send();
				$messagesent = sprintf(_AM_LEXIKON_SENTCONFIRMMAIL,$user->getVar('uname'));

				redirect_header( "entry.php", 1, $messagesent );
				exit();
				}
            redirect_header( "entry.php", 1, _AM_LEXIKON_ENTRYMODIFIED );
        } else {
            redirect_header( "index.php", 1, _AM_LEXIKON_ENTRYNOTUPDATED );
        }
    }
}

function entryDelete($entryID = '') {
    global $xoopsDB, $xoopsModule;
    $entryID = isset($_POST['entryID']) ? intval($_POST['entryID']) : intval($_GET['entryID']);
    $ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;
    $result = $xoopsDB -> query( "SELECT entryID, term, uid FROM " . $xoopsDB -> prefix( "lxentries" ) . " WHERE entryID = $entryID" );
    list( $entryID, $term, $uid  ) = $xoopsDB -> fetchrow( $result );

    // confirmed, so delete
    if ( $ok == 1 ) {
        $result = $xoopsDB -> query( "DELETE FROM " .$xoopsDB -> prefix("lxentries")." WHERE entryID = $entryID");
        xoops_comment_delete( $xoopsModule->getVar('mid'), $entryID );
        // delete notifications
        xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'term', $entryID);
        // update user posts
		if (!empty($uid)) {
			$submitter = new xoopsUser($uid);
			$member_handler =& xoops_gethandler('member');
			$member_handler->updateUserByField($submitter, 'posts', $submitter->getVar('posts') - 1);
		}
        redirect_header("entry.php",1,sprintf( _AM_LEXIKON_ENTRYISDELETED, $term ) );
        exit();
    } else {
        //xoops_cp_header();
        xoops_confirm(array('op' => 'del', 'entryID' => $entryID, 'ok' => 1, 'term' => $term ), 'entry.php', _AM_LEXIKON_DELETETHISENTRY . "<br /><br>" . $term, _AM_LEXIKON_DELETE );
        xoops_cp_footer();
    }
//	break;
    exit();
}

/* -- Available operations -- */
$op = 'default';
if (isset($_POST['op'])) {
    $op=$_POST['op'];
} else {
    if (isset($_GET['op'])) {
        $op=$_GET['op'];
    }
}
switch ( $op ) {
	case "mod":
		$entryID = ( isset( $_GET['entryID'] ) ) ? intval($_GET['entryID']) : intval($_POST['entryID']);
		entryEdit($entryID);
		break;
	
	case "add":
		entryEdit();
		break;
	
	case "addentry":
		entrySave();
		break;
	
	case "del":
		entryDelete();
		break;//
	
	case "default":
	default:
		entryDefault();
		break;
	}
xoops_cp_footer();
?>