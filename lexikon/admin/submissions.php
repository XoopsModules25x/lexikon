<?php
/**
 * $Id: submissions.php v 1.0 18 May 2006 hsalazar Exp $
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Changes: Yerres
 * Licence: GNU
 */
//file obsolete . remains for compatibility reasons

include( "admin_header.php" );
$myts =& MyTextSanitizer::getInstance();
$op = '';

if ( isset( $_GET['op'] ) ) $op = $_GET['op'];
if ( isset( $_POST['op'] ) ) $op = $_POST['op'];

/* -- Available operations -- */
switch ( $op ) {
case "default":
    default:
    include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

    $startentry = isset( $_GET['startentry'] ) ? intval( $_GET['startentry'] ) : 0;
    $startcat = isset( $_GET['startcat'] ) ? intval( $_GET['startcat'] ) : 0;
    $startsub = isset( $_GET['startsub'] ) ? intval( $_GET['startsub'] ) : 0;
    $datesub = isset( $_GET['datesub'] ) ? intval( $_GET['datesub'] ) : 0;
    xoops_cp_header();
    global $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsModule, $entryID;

    $myts =& MyTextSanitizer::getInstance();
//    lx_adminMenu(3, _AM_LEXIKON_SUBMITS);
    
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

    $result05 = $xoopsDB -> query( "SELECT COUNT(*)
                                   FROM " . $xoopsDB -> prefix( "lxentries" ) . "
                                   WHERE offline = '1'  " );
    list( $totaloffline ) = $xoopsDB -> fetchRow( $result05 );

    echo "<table width='100%' class='outer' style=\"margin-top: 6px; clear:both;\" cellspacing='2' cellpadding='3' border='0' ><tr>";
    echo "<td class='odd'>" . _AM_LEXIKON_TOTALENTRIES . "</td><td align='center' class='even'>" . $totalpublished . "</td>";
    if ($xoopsModuleConfig['multicats'] == 1) {
        echo "<td class='odd'>" . _AM_LEXIKON_TOTALCATS . "</td><td align='center' class='even'>" . $totalcategories . "</td>";
    }
    echo "<td class='odd'>" . _AM_LEXIKON_TOTALSUBM . "</td><td align='center' class='even'>" . $totalsubmitted . "</td>
    <td class='odd'>" . _AM_LEXIKON_TOTALREQ . "</td><td align='center' class='even'>" . $totalrequested . "</td>
    </tr></table>
    <br /><br />";

    /**
     * Code to show submitted entries
     **/

    lx_collapsableBar('lexikonsub', 'lexikonsubicon');
    echo "  <img  onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='lexikonsubicon' name='lexikonsubicon' src='" . XOOPS_URL . "/modules/lexikon/images/close12.gif' alt='' /></a>&nbsp;". _AM_LEXIKON_SHOWSUBMISSIONS . ' (' . $totalsubmitted . ')'. "<br />";
    echo "	<div id='lexikonsub' style='float:left; width:100%;'><table class='outer' width='100%' border='0'>";
    /*		<tr>
    		<td colspan='7' class='odd'>
    		<strong>". _AM_LEXIKON_SHOWSUBMISSIONS . ' (' . $totalsubmitted . ')'. "</strong></td></TR>";
    		echo "<tr>";
    */
    $resultS1 = $xoopsDB -> query( "SELECT COUNT(*)
                                   FROM " . $xoopsDB -> prefix( "lxentries" ) . "
                                   WHERE submit = '1' AND request = '0' " );
    list( $numrows ) = $xoopsDB -> fetchRow( $resultS1 );

    $sql = "SELECT entryID, categoryID, term, uid, datesub
           FROM " . $xoopsDB -> prefix( "lxentries" ) . "
           WHERE submit = '1' AND request = '0'
           ORDER BY datesub DESC";
    $resultS2 = $xoopsDB -> query( $sql, $xoopsModuleConfig['perpage'], $startsub );

    echo " <td width='40' class='odd' align='center'><b>" . _AM_LEXIKON_ENTRYID . "</b></td>";
    if ($xoopsModuleConfig['multicats'] == 1) {
        echo "<td width='20%' class='odd' align='center'><b>" . _AM_LEXIKON_ENTRYCATNAME . "</b></td>";
    }
    echo "<td class='odd' align='center'><b>" . _AM_LEXIKON_ENTRYTERM . "</b></td>
    <td width='90' class='odd' align='center'><b>" . _AM_LEXIKON_SUBMITTER . "</b></td>
    <td width='90' class='odd' align='center'><b>" . _AM_LEXIKON_ENTRYCREATED . "</b></td>
    <td width='60' class='odd' align='center'><b>" . _AM_LEXIKON_ACTION . "</b></td>
    </tr>";

    if ( $numrows > 0 ) // That is, if there ARE submitted entries in the system
    {
        while ( list( $entryID, $categoryID, $term, $uid, $created) = $xoopsDB -> fetchrow( $resultS2 ) ) {
            $resultS3 = $xoopsDB -> query( "SELECT name
                                           FROM " . $xoopsDB -> prefix( "lxcategories" ) . "
                                           WHERE categoryID = '$categoryID'" );
            list( $name ) = $xoopsDB -> fetchrow( $resultS3 );

            $sentby = XoopsUserUtility::getUnameFromId($uid);

            $catname = $myts -> htmlSpecialChars( $name );
            $term = $myts -> htmlSpecialChars( $term );
            $created = formatTimestamp( $created, 's' );
            $modify = "<a href='entry.php?op=mod&entryID=" . $entryID . "'><img src=" . $pathIcon16."/edit.png width='16' height='16' ALT='"._AM_LEXIKON_EDITSUBM."'></a>";
            $delete = "<a href='entry.php?op=del&entryID=" . $entryID . "'><img src=" . $pathIcon16."/delete.png width='16' height='16' ALT='"._AM_LEXIKON_DELETESUBM."'></a>";
            //$approve = "<a href='entry.php?op=add&entryID=" . $entryID . "'><img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/approve.gif  width='20' height='20' ALT='"._AM_LEXIKON_APPROVESUBM."'></a>";

            echo "<tr>
            <td class='even' align='center'>" . $entryID . "</td>";
            if ($xoopsModuleConfig['multicats'] == 1) {
                echo "<td class='odd' align='left'>" . $catname . "</td>";
            }
            echo "<td class='odd' align='left'>" . $term . "</td>
            <td class='odd' align='center'>" . $sentby . "</td>
            <td class='odd' align='center'>" . $created . "</td>
            <td class='even' align='center'> $modify $delete </td>
            </tr></DIV>";
        }
    }
    else // that is, $numrows = 0, there's no columns yet
    {
        echo "<tr>
        <td class='odd' align='center' colspan= '7'>"._AM_LEXIKON_NOSUBMISSYET."</td>
        </tr></DIV>";
    }
    echo "</table>\n";
    $pagenav = new XoopsPageNav( $numrows, $xoopsModuleConfig['perpage'], $startsub, 'startsub');
    echo '<div style="text-align:right;">' . $pagenav -> renderNav(8) . '</div>';
    echo" <br /><br /></DIV>\n";
    echo "</div>";

    /**
     * Code to show requested entries
     **/

    lx_collapsableBar('lexikonreq', 'lexikonreqicon');
    echo "  <img  onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='lexikonreqicon' name='lexikonreqicon' src='" . XOOPS_URL . "/modules/lexikon/images/close12.gif' alt='' /></a>&nbsp;". _AM_LEXIKON_SHOWREQUESTS . ' (' . $totalrequested . ')'. "<br />";
    echo "	<div id='lexikonreq' style='float:left; width:100%;'><table class='outer' width='100%' border='0'>";
    /*		<tr>
    		<td colspan='7' class='odd'>
    		<strong>". _AM_LEXIKON_SHOWREQUESTS . ' (' . $totalrequested . ')'. "</strong></td></TR>";
    		echo "<tr>";
    */
    $resultS2 = $xoopsDB -> query( "SELECT COUNT(*)
                                   FROM " . $xoopsDB -> prefix( "lxentries" ) . "
                                   WHERE submit = '1' and request = '1'" );
    list( $numrowsX ) = $xoopsDB -> fetchRow( $resultS2 );

    $sql4 = "SELECT entryID, categoryID, term, uid, datesub
            FROM " . $xoopsDB -> prefix( "lxentries" ) . "
            WHERE submit = '1' AND request = '1'
            ORDER BY datesub DESC";
    $resultS4 = $xoopsDB -> query( $sql4, $xoopsModuleConfig['perpage'], $startsub );

    echo "<td width='40' class='odd' align='center'><b>" . _AM_LEXIKON_ENTRYID . "</b></td>";
    if ($xoopsModuleConfig['multicats'] == 1) {
        echo "<td width='20%' class='odd' align='center'><b>" . _AM_LEXIKON_ENTRYCATNAME . "</b></td>";
    }
    echo "<td class='odd' align='center'><b>" . _AM_LEXIKON_ENTRYTERM . "</b></td>";
    echo "<td width='90' class='odd' align='center'><b>" . _AM_LEXIKON_SUBMITTER . "</b></td>";
    echo "<td width='90' class='odd' align='center'><b>" . _AM_LEXIKON_ENTRYCREATED . "</b></td>";
    echo "<td width='60' class='odd' align='center'><b>" . _AM_LEXIKON_ACTION . "</b></td>";
    echo "</tr>";

    if ( $numrowsX > 0 ) // That is, if there ARE unauthorized articles in the system
    {
        while ( list( $entryID, $categoryID, $term, $uid, $created) = $xoopsDB -> fetchrow( $resultS4 ) ) {
            $resultS3 = $xoopsDB -> query( "SELECT name
                                           FROM " . $xoopsDB -> prefix( "lxcategories" ) . "
                                           WHERE categoryID = '$categoryID'" );
            list( $name ) = $xoopsDB -> fetchrow( $resultS3 );

            $sentby = XoopsUserUtility::getUnameFromId($uid);

            $catname = $myts -> htmlSpecialChars( $name );
            $term = $myts -> htmlSpecialChars( $term );
            $created = formatTimestamp( $created, 's' );
            $modify = "<a href='entry.php?op=mod&entryID=" . $entryID . "'><img src=" . $pathIcon16."/edit.png width='16' height='16' ALT='"._AM_LEXIKON_EDITSUBM."'></a>";
            $delete = "<a href='entry.php?op=del&entryID=" . $entryID . "'><img src=" . $pathIcon16."/delete.png width='16' height='16' ALT='"._AM_LEXIKON_DELETESUBM."'></a>";

            echo "<tr>";
            echo "<td class='even' align='center'>" . $entryID . "</td>";
            if ($xoopsModuleConfig['multicats'] == 1) {
                echo "<td class='odd' align='left'>" . $catname . "</td>";
            }
            echo "<td class='odd' align='left'>" . $term . "</td>";
            echo "<td class='odd' align='center'>" . $sentby . "</td>";
            echo "<td class='odd' align='center'>" . $created . "</td>";
            echo "<td class='even' align='center'> $modify $delete </td>";
            echo "</tr></DIV>";
        }
    }
    else // that is, $numrows = 0, there's no columns yet
    {
        echo "<tr>
        <td class='odd' align='center' colspan= '7'>"._AM_LEXIKON_NOREQSYET."</td>
        </tr></DIV>";
    }
    echo "</table>\n";
    $pagenav = new XoopsPageNav( $numrowsX, $xoopsModuleConfig['perpage'], $startsub, 'startsub');
    echo '<div style="text-align:right;">' . $pagenav -> renderNav(8) . '</div>';
    echo "<br /></DIV>\n";
    echo "</div>";

    /**
     * Code to show offline entries
     **/
    lx_collapsableBar('lexikonoff', 'lexikonofficon');
    echo "  <img  onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='lexikonofficon' name='lexikonofficon' src='" . XOOPS_URL . "/modules/lexikon/images/close12.gif' alt='' /></a>&nbsp;". _AM_LEXIKON_SHOWOFFLINE . ' (' . $totaloffline . ')'. "</legend><br />";
    echo "	<div id='lexikonoff' style='float:left; width:100%;'><table class='outer' width='100%' border='0'>";
    /*		<tr>
    		<td colspan='7' class='odd'>
    		<strong>". _AM_LEXIKON_SHOWOFFLINE . ' (' . $totaloffline . ')'. "</strong></td></TR>";
    		echo "<tr>";
    */
    $resultS2 = $xoopsDB -> query( "SELECT COUNT(*)
                                   FROM " . $xoopsDB -> prefix( "lxentries" ) . "
                                   WHERE offline = '1'" );
    list( $numrowsX ) = $xoopsDB -> fetchRow( $resultS2 );

    $sql4 = "SELECT entryID, categoryID, term, uid, datesub
            FROM " . $xoopsDB -> prefix( "lxentries" ) . "
            WHERE offline = '1'
            ORDER BY datesub DESC";
    $resultS4 = $xoopsDB -> query( $sql4, $xoopsModuleConfig['perpage'], $startsub );

    echo "<td width='40' class='odd' align='center'><b>" . _AM_LEXIKON_ENTRYID . "</b></td>";
    if ($xoopsModuleConfig['multicats'] == 1) {
        echo "<td width='20%' class='odd' align='center'><b>" . _AM_LEXIKON_ENTRYCATNAME . "</b></td>";
    }
    echo "<td class='odd' align='center'><b>" . _AM_LEXIKON_ENTRYTERM . "</b></td>";
    echo "<td width='90' class='odd' align='center'><b>" . _AM_LEXIKON_SUBMITTER . "</b></td>";
    echo "<td width='90' class='odd' align='center'><b>" . _AM_LEXIKON_ENTRYCREATED . "</b></td>";
    echo "<td width='60' class='odd' align='center'><b>" . _AM_LEXIKON_ACTION . "</b></td>";
    echo "</tr>";

    if ( $numrowsX > 0 ) // That is, if there ARE unauthorized articles in the system
    {
        while ( list( $entryID, $categoryID, $term, $uid, $created) = $xoopsDB -> fetchrow( $resultS4 ) ) {
            $resultS3 = $xoopsDB -> query( "SELECT name
                                           FROM " . $xoopsDB -> prefix( "lxcategories" ) . "
                                           WHERE categoryID = '$categoryID'" );
            list( $name ) = $xoopsDB -> fetchrow( $resultS3 );

            $sentby = XoopsUserUtility::getUnameFromId($uid);

            $catname = $myts -> htmlSpecialChars( $name );
            $term = $myts -> htmlSpecialChars( $term );
            $created = formatTimestamp( $created, 's' );
            $modify = "<a href='entry.php?op=mod&entryID=" . $entryID . "'><img src=" . $pathIcon16."/edit.png width='16' height='16' ALT='"._AM_LEXIKON_EDITSUBM."'></a>";
            $delete = "<a href='entry.php?op=del&entryID=" . $entryID . "'><img src=" . $pathIcon16."/delete.png width='16' height='16' ALT='"._AM_LEXIKON_DELETESUBM."'></a>";

            echo "<tr>";
            echo "<td class='even' align='center'>" . $entryID . "</td>";
            if ($xoopsModuleConfig['multicats'] == 1) {
                echo "<td class='odd' align='left'>" . $catname . "</td>";
            }
            echo "<td class='odd' align='left'>" . $term . "</td>";
            echo "<td class='odd' align='center'>" . $sentby . "</td>";
            echo "<td class='odd' align='center'>" . $created . "</td>";
            echo "<td class='even' align='center'> $modify $delete </td>";
            echo "</tr></DIV>";
        }
    }
    else // that is, $numrows = 0, there's no columns yet
    {
        echo "<tr>
        <td class='odd' align='center' colspan= '7'>"._AM_LEXIKON_NOREQSYET."</td>
        </tr></DIV>";
    }
    echo "</table>\n";
    $pagenav = new XoopsPageNav( $numrowsX, $xoopsModuleConfig['perpage'], $startsub, 'startsub' );
    echo '<div style="text-align:right;">' . $pagenav -> renderNav(8) . '</div>';
    echo "<br /></DIV>\n";
    echo "</div>";
}
xoops_cp_footer();
