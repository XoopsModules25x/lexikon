<?php
/**
 * $Id: main.php v 1.0 8 May 2011 yerres Exp $
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 18 Dec 2011
 * Author: yerres
 * Licence: GNU
 */

include( "admin_header.php" );
xoops_cp_header();

$myts = MyTextSanitizer::getInstance();
global $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $xoopsModule, $entryID;
xoops_load('XoopsUserUtility');
$indexAdmin = new ModuleAdmin();
echo $indexAdmin->addNavigation('main.php');
$indexAdmin->addItemButton(_AM_LEXIKON_CREATECAT, 'category.php?op=addcat', 'add');
$indexAdmin->addItemButton(_AM_LEXIKON_CREATEENTRY, 'entry.php?op=add', 'add');
echo $indexAdmin->renderButton('left');
//include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

$startentry = isset( $_GET['startentry'] ) ? intval( $_GET['startentry'] ) : 0;
$entryID = isset($_POST['entryID']) ? intval($_POST['entryID']) : 0;
$pick = isset($_GET['pick']) ? intval($_GET['pick']) : 0;
$pick = isset($_POST['pick']) ? intval($_POST['pick']) : $pick;

$statussel = isset($_GET['statussel']) ? intval($_GET['statussel']) : 0;
$statussel = isset($_POST['statussel']) ? intval($_POST['statussel']) : $statussel;

$sortsel = isset($_GET['sortsel']) ? $_GET['sortsel'] : 'entryID';
$sortsel = isset($_POST['sortsel']) ? $_POST['sortsel'] : $sortsel;

$ordersel = isset($_GET['ordersel']) ? $_GET['ordersel'] : 'DESC';
$ordersel = isset($_POST['ordersel']) ? $_POST['ordersel'] : $ordersel;

//--- inventory
$result = $xoopsDB -> query( "SELECT COUNT(*)
							   FROM " . $xoopsDB -> prefix( "lxcategories" ) . " " );
list( $totalcategories ) = $xoopsDB -> fetchRow( $result );

$result01 = $xoopsDB -> query( "SELECT COUNT(*)
							   FROM " . $xoopsDB -> prefix( "lxentries" ) . "
							   " );
list( $totalterms ) = $xoopsDB -> fetchRow( $result01 );

$result02 = $xoopsDB -> query( "SELECT COUNT(*)
							   FROM " . $xoopsDB -> prefix( "lxentries" ) . "
							   WHERE offline = 0 AND submit = 0 AND request = 0 " );
list( $totalpublished ) = $xoopsDB -> fetchRow( $result02 );

$result03 = $xoopsDB -> query( "SELECT COUNT(*)
							   FROM " . $xoopsDB -> prefix( "lxentries" ) . "
							   WHERE submit = '1' AND request = '0' " );
list( $totalsubmitted ) = $xoopsDB -> fetchRow( $result03 );

$result04 = $xoopsDB -> query( "SELECT COUNT(*)
							   FROM " . $xoopsDB -> prefix( "lxentries" ) . "
							   WHERE submit = '1' AND request = '1' " );
list( $totalrequested ) = $xoopsDB -> fetchRow( $result04 );

$result05 = $xoopsDB -> query( "SELECT COUNT(*)
							   FROM " . $xoopsDB -> prefix( "lxentries" ) . "
							   WHERE offline = '1'  " );
list( $totaloffline ) = $xoopsDB -> fetchRow( $result05 );

// Adapted from Smartsection
//echo "<table width='100%' class='outer' style=\"margin-top: 6px; clear:both;\" ; cellspacing='2' cellpadding='3' border='0' ><tr>";
//echo "<td class='odd'>" . _AM_LEXIKON_TOTALENTRIES . "</td><td align='center' class='even'>" . $totalpublished . "</td>";
//if ($xoopsModuleConfig['multicats'] == 1) {
//	echo "<td class='odd'>" . _AM_LEXIKON_TOTALCATS . "</td><td align='center' class='even'>" . $totalcategories . "</td>";
//}
//echo "<td class='odd'>" . _AM_LEXIKON_TOTALSUBM . "</td><td align='center' class='even'>" . $totalsubmitted . "</td>";
//echo "<td class='odd'>" . _AM_LEXIKON_TOTALREQ . "</td><td align='center' class='even'>" . $totalrequested . "</td>";
//echo "</tr></table>";
//echo "<br /><br />";
//--- category dropdown
if ($xoopsModuleConfig['multicats'] == 1) {
    $cattree = new XoopsTree( $xoopsDB->prefix("lxcategories"), "categoryID", "0" );
    echo "<table class='outer' width='100%'><tr ><td colspan=\"2\" class='even'><strong>" . _AM_LEXIKON_INVENTORY . "</strong></td></tr>";
    echo "<tr class=\"odd\" ><td width=\"30%\" align=\"left\">";
    echo '<form method=get action=\"category.php\">';
    $cattree -> makeMySelBox("name", "weight DESC",0,1,"",'window.location="category.php?op=mod&amp;categoryID="+this.value');
    echo "</form>";
    echo "</td><td align=\"left\">";
    echo "<input type='button' name='button' onClick=\"location='category.php?op=addcat'\" value=' " . _AM_LEXIKON_CREATECAT . "' >&nbsp;&nbsp;";
    echo "<input type='button' name='button' onclick=\"location='entry.php?op=add'\" value='" . _AM_LEXIKON_CREATEENTRY . "'>&nbsp;&nbsp;";
    echo "</td></tr>";
    echo "</table><P><br/>";
} else {
    //--- create button
    echo "<form><div style=\"margin-bottom: 12px;\">";
    echo "<input type='button' name='button' onclick=\"location='entry.php?op=add'\" value='" . _AM_LEXIKON_CREATEENTRY . "'>&nbsp;&nbsp;";
    echo "</div></form>";
    echo "<br/><span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \"><b>" . _AM_LEXIKON_ALLITEMSMSG . "</b></span>";
}
// database update
if (!lx_FieldExists('logourl',$xoopsDB->prefix('lxcategories')) || (lx_FieldExists('parent',$xoopsDB->prefix('lxcategories')))) {
    $i++;
    echo "<table><tr><td  style='border-bottom: 1px dotted #cfcfcf; line-height: 16px;'><img src='".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/dialog-important.png"."' alt='' hspace='0' vspace='0' align='left' style='margin-right: 10px; '><A href='upgrade.php'>" . _AM_LEXIKON_PLEASE_UPGRADE. "</A></td></tr></table></P>";
}
    
//--- navigation bar
$showingtxt = '';
$selectedtxt = '';
$cond = "";
$selectedtxt0 = '';
$selectedtxt1 = '';
$selectedtxt2 = '';
$selectedtxt3 = '';
$selectedtxt4 = '';

$sorttxtterm = "";
$sorttxtcreated = "";
$sorttxtauthor = "";
$sorttxtentryID = "";
$sorttxtcats = "";

$ordertxtasc='';
$ordertxtdesc='';

switch ($sortsel) {
    case 'term':
    $sorttxtterm = "selected='selected'";
    break;
    
    case 'datesub':
    $sorttxtcreated = "selected='selected'";
    break;
    
    case 'uid':
    $sorttxtauthor = "selected='selected'";
    break;

    case 'categoryID':
    $sorttxtcats = "selected='selected'";
    break;
        
    default :
    $sorttxtentryID = "selected='selected'";
    break;
}

switch ($ordersel) {
    case 'ASC':
    $ordertxtasc = "selected='selected'";
    break;
    
    default :
    $ordertxtdesc = "selected='selected'";
    break;
}

switch ($statussel) {
    case '0' :
    //default:
    $selectedtxt0 = "selected='selected'";
    $caption = _ALL;
    $cond = "";
    $status_explanation = _AM_LEXIKON_ALL_EXP;
    break;
    
    case '1' :
    $selectedtxt1 = "selected='selected'";
    $caption = _AM_LEXIKON_SUBMITTED;
    $cond = " WHERE submit = 1 AND request = 0 ";
    $status_explanation = _AM_LEXIKON_SUBMITTED_EXP;
    break;
    
    case '2' :
    $selectedtxt2 = "selected='selected'";
    $caption = _AM_LEXIKON_PUBLISHED;
    $cond = " WHERE offline = 0 ";
    $status_explanation = _AM_LEXIKON_PUBLISHED_EXP;
    break;
    
    case '3' :
    $selectedtxt3 = "selected='selected'";
    $caption = _AM_LEXIKON_SHOWOFFLINE;
    $cond = " WHERE offline = 1 ";
    $status_explanation = _AM_LEXIKON_OFFLINE_EXP;
    break;
    
    case '4' :
    $selectedtxt4 = "selected='selected'";
    $caption = _AM_LEXIKON_SHOWREQUESTS;
    $cond = " WHERE submit= 1 AND request = 1 ";
    $status_explanation = _AM_LEXIKON_REQ_ITEM_EXP;
    break;
}
// -- Code to show selected terms
echo "<form name='pick' id='pick' action='" . $_SERVER['PHP_SELF'] . "' method='POST' style='margin: 0;'>";

echo "
	<table width='100%' cellspacing='1' cellpadding='2' border='0' style='border-left: 1px solid silver; border-top: 1px solid silver; border-right: 1px solid silver;'>
		<tr>
			<td><span style='font-weight: bold; font-variant: small-caps;'>" . _AM_LEXIKON_SHOWING . " " . $caption . "</span></td>
			<td align='right'>" . _AM_LEXIKON_SELECT_SORT . "
				<select name='sortsel' onchange='submit()'>
					<option value='entryID' $sorttxtentryID>" . _AM_LEXIKON_ENTRYID . "</option>
					<option value='term' $sorttxtterm>" . _AM_LEXIKON_TERM . "</option>
					<option value='uid' $sorttxtauthor>" . _AM_LEXIKON_AUTHOR . "</option>
					<option value='datesub' $sorttxtcreated>" . _DATE . "</option>
					<option value='categoryID' $sorttxtcats>" . _AM_LEXIKON_CATEGORY . "</option>
				</select>
				<select name='ordersel' onchange='submit()'>
					<option value='ASC' $ordertxtasc>" . _ASCENDING . "</option>
					<option value='DESC' $ordertxtdesc>" . _DESCENDING . "</option>
				</select>
			" . _AM_LEXIKON_STATUS . " :
				<select name='statussel' onchange='submit()'>
					<option value='0' $selectedtxt0>" . _ALL . " [$totalterms]</option>
					<option value='1' $selectedtxt1>" . _AM_LEXIKON_SUBMITS . " [$totalsubmitted]</option>
					<option value='2' $selectedtxt2>" . _AM_LEXIKON_PUBLISHED . " [$totalpublished]</option>
					<option value='3' $selectedtxt3>" . _AM_LEXIKON_SHOWOFFLINE . " [$totaloffline]</option>
					<option value='4' $selectedtxt4>" . _AM_LEXIKON_SHOWREQUESTS . " [$totalrequested]</option>
				</select>
			</td>
		</tr>
	</table>
	</form>";

// Get number of entries in the selected state
$statusSelected = ($statussel == 0) ? -1 : $statussel;
$results = $xoopsDB -> query( "SELECT COUNT(*)
                                FROM " . $xoopsDB -> prefix( "lxentries" ) . "
                                " . $cond . "
                                ORDER BY " . $sortsel . " " . $ordersel . "
                                " );
list( $numrows ) = $xoopsDB -> fetchRow( $results );
// creating the content
$sql = "SELECT entryID, categoryID, term, uid, datesub, offline
	   FROM ".$xoopsDB->prefix('lxentries')."
	   ".$cond."
	   ORDER BY ".$sortsel." ".$ordersel." ";

$items = $xoopsDB -> query( $sql, $xoopsModuleConfig['perpage'], $startentry );//missing nav. extras
$totalItemsOnPage = count($numrows);

lx_buildTable();

if ($numrows > 0) {
    $class   = "odd";
    while ( list( $entryID, $categoryID, $term, $uid, $created, $offline) = $xoopsDB -> fetchrow( $items ) ) {
        // Creating the items
        $resultcn = $xoopsDB -> query( "SELECT name
									   FROM " . $xoopsDB -> prefix( "lxcategories" ) . "
									   WHERE categoryID = '$categoryID'" );
        list( $name ) = $xoopsDB -> fetchrow( $resultcn );
        $catname = $myts -> htmlSpecialChars( $name );
        $sentby = XoopsUserUtility::getUnameFromId($uid);
        $term = $myts -> htmlSpecialChars( $term );
        $created= formatTimestamp( $created, 's' );
        $modify = "<a href='entry.php?op=mod&entryID=" . $entryID . "'><img src=" . $pathIcon16."/edit.png width='16' height='16' ALT='"._AM_LEXIKON_EDITENTRY."'></a>&nbsp;";
        $delete = "<a href='entry.php?op=del&entryID=" . $entryID . "'><img src=" . $pathIcon16."/delete.png width='16' height='16' ALT='"._AM_LEXIKON_DELETEENTRY."'></a>";

        for ( $i = 0; $i < $totalItemsOnPage; $i++ ) {
            $approve = '';
            switch ($items) {
                //case _LEXIKON_STATUS_SUBMITTED :
                case '1' :
                $statustxt = "<img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/off.gif alt='"._AM_LEXIKON_ENTRYISOFF."'>";
                break;
                
                //case _LEXIKON_STATUS_PUBLISHED :
                case '2' :
                $statustxt = "<img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/on.gif alt='"._AM_LEXIKON_ENTRYISON."'>";
                break;
                
                //case _LEXIKON_STATUS_OFFLINE :
                case '3' :
                $statustxt = "<img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/off.gif alt='"._AM_LEXIKON_ENTRYISOFF."'>";
                break;
                
                //case _LEXIKON_STATUS_REQ :
                case '4' :
                $statustxt = "<img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/off.gif alt='"._AM_LEXIKON_ENTRYISOFF."'>";
                break;
                
                case "default" :
                default :
                if ( $offline == 0 ) {
                    $statustxt = "<img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/on.gif alt='"._AM_LEXIKON_ENTRYISON."'>";
                } else {
                    $statustxt = "<img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/off.gif alt='"._AM_LEXIKON_ENTRYISOFF."'>";
                }
                $approve = "";
                break;
            }

            echo "<tr class='" . $class . "'>";
            $class = ($class == "even") ? "odd" : "even";

            echo "<td  align='center'>".$entryID."</td>";
            echo "<td  align='left'>" . $catname . "</td>";
            echo "<td  align='left'><a href='../entry.php?entryID=" . $entryID . "'>" . $term . "</td>";
            echo "<td  align='center'>" . $sentby . "</td>";
            echo "<td  align='center'>" . $created . "</td>";
            echo "<td  align='center'>" . $statustxt . "</td>";
            echo "<td  align='center'> ". $approve . $modify . $delete . "</td>";
            echo "</tr>";
        }
    }
} else {
    // that is no item corresponding the status
    echo "<tr>";
    echo "<td class='head' align='center' colspan= '7'>" . _AM_LEXIKON_NOITEMSSEL . "</td>";
    echo "</tr>";
}
echo "</table>\n";

echo "<span style=\"color: #567; margin: 3px 0 18px 0; font-size: small; display: block; \">$status_explanation</span>";
$pagenav = new XoopsPageNav($numrows, $xoopsModuleConfig['perpage'], $startentry, 'startentry', "statussel=$statussel&amp;sortsel=$sortsel&amp;ordersel=$ordersel");
echo '<div style="text-align:right;">' . $pagenav -> renderNav(12) . '</div>';
echo "<br /><BR>\n";
echo "</div>";

//----
xoops_cp_footer();
