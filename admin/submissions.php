<?php
/**
 *
 * Module: Lexikon - glossary module
 * Author: hsalazar
 * Changes: Yerres
 * Licence: GNU
 */
//file obsolete . remains for compatibility reasons

require_once __DIR__ . '/admin_header.php';
$myts = MyTextSanitizer::getInstance();
xoops_load('XoopsUserUtility');

$op   = '';

if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

/* -- Available operations -- */
switch ($op) {
    case 'default':
    default:
        include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
        include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

        $startentry = isset($_GET['startentry']) ? (int)$_GET['startentry'] : 0;
        $startcat   = isset($_GET['startcat']) ? (int)$_GET['startcat'] : 0;
        $startsub   = isset($_GET['startsub']) ? (int)$_GET['startsub'] : 0;
        $datesub    = isset($_GET['datesub']) ? (int)$_GET['datesub'] : 0;
        xoops_cp_header();
        $adminObject  = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        global $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsModule, $entryID;

        $myts = MyTextSanitizer::getInstance();

        $result01 = $xoopsDB->query('SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxcategories') . ' ');
        list($totalcategories) = $xoopsDB->fetchRow($result01);

        $result02 = $xoopsDB->query('SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxentries') . '
                                   WHERE submit = 0');
        list($totalpublished) = $xoopsDB->fetchRow($result02);

        $result03 = $xoopsDB->query('SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxentries') . "
                                   WHERE submit = '1' AND request = '0' ");
        list($totalsubmitted) = $xoopsDB->fetchRow($result03);

        $result04 = $xoopsDB->query('SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxentries') . "
                                   WHERE submit = '1' AND request = '1' ");
        list($totalrequested) = $xoopsDB->fetchRow($result04);

        $result05 = $xoopsDB->query('SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxentries') . "
                                   WHERE offline = '1'  ");
        list($totaloffline) = $xoopsDB->fetchRow($result05);

        echo "<table class='outer' style='margin-top:6px; clear:both; width:99%;'><tr>";
        echo "<th style='text-align:right;'>"
              . _AM_LEXIKON_TOTALENTRIES
              . " </th><td style='text-align:center;' class='even'>"
              . $totalpublished
              . '</td>';
        if ($xoopsModuleConfig['multicats'] == 1) {
            echo "<th style='text-align:right;'>"
                  . _AM_LEXIKON_TOTALCATS
                  . "</th><td style='text-align:center;' class='even'>"
                  . $totalcategories
                  . '</td>';
            }
        echo "<th style='text-align:right;'>"
              . _AM_LEXIKON_TOTALSUBM
              . "</th><td style='text-align:center;' class='even'>"
              . $totalsubmitted
              . "</td><th style='text-align:right;'>"
              . _AM_LEXIKON_TOTALREQ
              . "</th><td style='text-align:center;' class='even'>"
              . $totalrequested
              . '</td></tr></table><br>';

        /**
         * Code to show submitted entries
         **/

        lx_collapsableBar('lexikonsub', 'lexikonsubicon');
        echo "  <img  onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='lexikonsubicon' name='lexikonsubicon' src='"
             . XOOPS_URL
             . "/modules/lexikon/assets/images/close12.gif' alt=''></a>&nbsp;<strong>"
             . _AM_LEXIKON_SHOWSUBMISSIONS
             . ' ('
             . $totalsubmitted
             . ')'
             . '</strong><br>';
        echo "<div id='lexikonsub' style='float:left; width:100%;'><table class='outer' style='width:99%;'>";
        $resultS1 = $xoopsDB->query('SELECT COUNT(*)
                                     FROM ' . $xoopsDB->prefix('lxentries') . "
                                     WHERE submit = '1' AND request = '0' ");
        list($numrows) = $xoopsDB->fetchRow($resultS1);

        $sql      = 'SELECT entryID, categoryID, term, uid, datesub
                     FROM ' . $xoopsDB->prefix('lxentries') . "
                     WHERE submit = '1' AND request = '0'
                     ORDER BY datesub DESC";
        $resultS2 = $xoopsDB->query($sql, $xoopsModuleConfig['perpage'], $startsub);

        echo "<th style='text-align:center; width:40px;'>"
              . _AM_LEXIKON_ENTRYID
              . '</th>';
        if ($xoopsModuleConfig['multicats'] == 1) {
            echo "<th style='text-align:center; width:20%;'>"
                  . _AM_LEXIKON_ENTRYCATNAME
                  . '</th>';
        }
        echo "<th style='text-align:center;'>"
        . _AM_LEXIKON_ENTRYTERM
        . "</th><th style='text-align:center; width:90;'>"
        . _AM_LEXIKON_SUBMITTER
        . "</th><th style='text-align:center; width:90;'>"
        . _AM_LEXIKON_ENTRYCREATED
        . "</th><th style='text-align:center; width:60;'>"
        . _AM_LEXIKON_ACTION
        . '</th></tr>';

        if ($numrows > 0) { // That is, if there ARE submitted entries in the system
            while (list($entryID, $categoryID, $term, $uid, $created) = $xoopsDB->fetchrow($resultS2)) {
                $resultS3 = $xoopsDB->query('SELECT name
                                           FROM ' . $xoopsDB->prefix('lxcategories') . "
                                           WHERE categoryID = '$categoryID'");
                list($name) = $xoopsDB->fetchrow($resultS3);

                $sentby = XoopsUserUtility::getUnameFromId($uid);

                $catname = $myts->htmlSpecialChars($name);
                $term    = $myts->htmlSpecialChars($term);
                $created = formatTimestamp($created, 's');
                $modify  = "<a href='entry.php?op=mod&entryID="
                            . $entryID
                            . "'><img src="
                            . $pathIcon16
                            . "/edit.png alt='"
                            . _AM_LEXIKON_EDITSUBM
                            . "'></a>";
                $delete  = "<a href='entry.php?op=del&entryID="
                            . $entryID
                            . "'><img src="
                            . $pathIcon16
                            . "/delete.png alt='"
                            . _AM_LEXIKON_DELETESUBM
                            . "'></a>";

                echo "<tr><td class='even' style='text-align:center;'>"
                      . $entryID
                      . '</td>';
                if ($xoopsModuleConfig['multicats'] == 1) {
                    echo "<td class='odd' style='text-align:left;'>"
                          . $catname
                          . '</td>';
                }
                echo "<td class='odd' style='text-align:left;'>"
                      . $term
                      . "</td><td class='odd' style='text-align:center;'>"
                      . $sentby
                      . "</td><td class='odd' style='text-align:center;'>"
                      . $created
                      . "</td><td class='even' style='text-align:center;'>"
                      . $modify
                      . "-"
                      . $delete
                      . "</td></tr></div>";
            }
        } else { // that is, $numrows = 0, there's no columns yet
            echo "<tr><td class='odd' style='text-align:center;' colspan= '7'>"
                  . _AM_LEXIKON_NOSUBMISSYET
                  . '</td></tr></div>';
        }
        echo "</table>";
        $pagenav = new XoopsPageNav($numrows, $xoopsModuleConfig['perpage'], $startsub, 'startsub');
        echo '<div style="text-align:right;">'
              . $pagenav->renderNav(8)
              . '</div>';
        echo " <br></div>";
        echo '</div>';

        /**
         * Code to show requested entries
         **/

        lx_collapsableBar('lexikonreq', 'lexikonreqicon');
        echo "  <img  onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='lexikonreqicon' name='lexikonreqicon' src='"
             . XOOPS_URL
             . "/modules/lexikon/assets/images/close12.gif' alt=''></a>&nbsp;<strong>"
             . _AM_LEXIKON_SHOWREQUESTS
             . ' ('
             . $totalrequested
             . ')'
             . '</strong><br>';
        echo "<div id='lexikonreq' style='float:left; width:100%;'><table class='outer' style='width:99%;'>";
        $resultS2 = $xoopsDB->query('SELECT COUNT(*)
                                     FROM ' . $xoopsDB->prefix('lxentries') . "
                                     WHERE submit = '1' AND request = '1'");
        list($numrowsX) = $xoopsDB->fetchRow($resultS2);

        $sql4     = 'SELECT entryID, categoryID, term, uid, datesub
                    FROM ' . $xoopsDB->prefix('lxentries') . "
                    WHERE submit = '1' AND request = '1'
                    ORDER BY datesub DESC";
        $resultS4 = $xoopsDB->query($sql4, $xoopsModuleConfig['perpage'], $startsub);

        echo "<th style='text-align:center; width:40px;'>"
              . _AM_LEXIKON_ENTRYID
              . '</th>';
        if ($xoopsModuleConfig['multicats'] == 1) {
            echo "<th style='text-align:center; width:20%;'>"
                  . _AM_LEXIKON_ENTRYCATNAME
                  . '</th>';
        }
        echo "<th style='text-align:center;'>"
              . _AM_LEXIKON_ENTRYTERM
              . "</th><th style='text-align:center; width:90px;'>"
              . _AM_LEXIKON_SUBMITTER
              . "</th><th style='text-align:center; width:90px;'>"
              . _AM_LEXIKON_ENTRYCREATED
              . "</th><th style='text-align:center; width:60px;'>"
              . _AM_LEXIKON_ACTION
              . '</th></tr>';

        if ($numrowsX > 0) { // That is, if there ARE unauthorized articles in the system
            while (list($entryID, $categoryID, $term, $uid, $created) = $xoopsDB->fetchrow($resultS4)) {
                $resultS3 = $xoopsDB->query('SELECT name
                                             FROM ' . $xoopsDB->prefix('lxcategories') . "
                                             WHERE categoryID = '$categoryID'");
                list($name) = $xoopsDB->fetchrow($resultS3);

                $sentby = XoopsUserUtility::getUnameFromId($uid);

                $catname = $myts->htmlSpecialChars($name);
                $term    = $myts->htmlSpecialChars($term);
                $created = formatTimestamp($created, 's');
                $modify  = "<a href='entry.php?op=mod&entryID="
                            . $entryID
                            . "'><img src="
                            . $pathIcon16
                            . "/edit.png alt='"
                            . _AM_LEXIKON_EDITSUBM
                            . "'></a>";
                $delete  = "<a href='entry.php?op=del&entryID="
                            . $entryID
                            . "'><img src="
                            . $pathIcon16
                            . "/delete.png alt='"
                            . _AM_LEXIKON_DELETESUBM
                            . "'></a>";
                echo '<tr>';
                echo "<td class='even' style='text-align:center;'>"
                      . $entryID
                      . '</td>';
                if ($xoopsModuleConfig['multicats'] == 1) {
                    echo "<td class='odd' align='left'>"
                          . $catname
                          . '</td>';
                }
                echo "<td class='odd' style='text-align:left;'>"
                      . $term
                      . "</td><td class='odd' style='text-align:center;'>"
                      . $sentby
                      . "</td><td class='odd' style='text-align:center;'>"
                      . $created
                      . "</td><td class='even' style='text-align:center;'>"
                      . $modify
                      . "-"
                      . $delete
                      . "</td></tr></div>";
            }
        } else { // that is, $numrows = 0, there's no columns yet
            echo "<tr><td class='odd' style='text-align:center;' colspan= '7'>"
                  . _AM_LEXIKON_NOREQSYET
                  . '</td></tr></div>';
        }
        echo "</table>";
        $pagenav = new XoopsPageNav($numrowsX, $xoopsModuleConfig['perpage'], $startsub, 'startsub');
        echo '<div style="text-align:right;">'
              . $pagenav->renderNav(8)
              . '</div>';
        echo "<br></div></div>";

        /**
         * Code to show offline entries
         **/
        lx_collapsableBar('lexikonoff', 'lexikonofficon');
        echo "  <img  onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='lexikonofficon' name='lexikonofficon' src='"
             . XOOPS_URL
             . "/modules/lexikon/assets/images/close12.gif' alt='' /></a>&nbsp;<strong>"
             . _AM_LEXIKON_SHOWOFFLINE
             . ' ('
             . $totaloffline
             . ')'
             . '</strong><br>';
        echo "  <div id='lexikonoff' style='float:left; width:100%;'><table class='outer' style='width:99%;'>";
        $resultS2 = $xoopsDB->query('SELECT COUNT(*)
                                     FROM ' . $xoopsDB->prefix('lxentries') . "
                                     WHERE offline = '1'");
        list($numrowsX) = $xoopsDB->fetchRow($resultS2);

        $sql4     = 'SELECT entryID, categoryID, term, uid, datesub
                    FROM ' . $xoopsDB->prefix('lxentries') . "
                    WHERE offline = '1'
                    ORDER BY datesub DESC";
        $resultS4 = $xoopsDB->query($sql4, $xoopsModuleConfig['perpage'], $startsub);

        echo "<th style='text-align:center; width:40px;'>" . _AM_LEXIKON_ENTRYID . '</th>';
        if ($xoopsModuleConfig['multicats'] == 1) {
            echo "<th style='text-align:center; width:20%;'>" . _AM_LEXIKON_ENTRYCATNAME . '</th>';
        }
        echo "<th style='text-align:center;'>"
              . _AM_LEXIKON_ENTRYTERM
              . "</th><th style='text-align:center; width:90px;'>"
              . _AM_LEXIKON_SUBMITTER
              . "</th><th style='text-align:center; width:90px;'>"
              . _AM_LEXIKON_ENTRYCREATED
              . "</th><th style='text-align:center; width:60px;'>"
              . _AM_LEXIKON_ACTION
              . "</th></tr>";

        if ($numrowsX > 0) { // That is, if there ARE unauthorized articles in the system
            while (list($entryID, $categoryID, $term, $uid, $created) = $xoopsDB->fetchrow($resultS4)) {
                $resultS3 = $xoopsDB->query('SELECT name
                                             FROM ' . $xoopsDB->prefix('lxcategories') . "
                                             WHERE categoryID = '$categoryID'");
                list($name) = $xoopsDB->fetchrow($resultS3);

                $sentby = XoopsUserUtility::getUnameFromId($uid);

                $catname = $myts->htmlSpecialChars($name);
                $term    = $myts->htmlSpecialChars($term);
                $created = formatTimestamp($created, 's');
                $modify  = "<a href='entry.php?op=mod&entryID="
                            . $entryID
                            . "'><img src="
                            . $pathIcon16
                            . "/edit.png alt='"
                            . _AM_LEXIKON_EDITSUBM
                            . "'></a>";
                $delete  = "<a href='entry.php?op=del&entryID="
                            . $entryID
                            . "'><img src="
                            . $pathIcon16
                            . "/delete.png alt='"
                            . _AM_LEXIKON_DELETESUBM
                            . "'></a>";
                echo "<tr><td class='even' style='text-align:center;'>"
                      . $entryID
                      . '</td>';
                if ($xoopsModuleConfig['multicats'] == 1) {
                    echo "<td class='odd' style='text-align:left;'>"
                          . $catname
                          . '</td>';
                }
                echo "<td class='odd' style='text-align:left;'>"
                      . $term
                      . "</td><td class='odd' style='text-align:center;'>"
                      . $sentby
                      . "</td><td class='odd' style='text-align:center;'>"
                      . $created
                      . "</td><td class='even' style='text-align:center;'>"
                      . $modify
                      . "-"
                      . $delete
                      . "</td></tr></div>";
            }
        } else { // that is, $numrows = 0, there's no columns yet
            echo "<tr><td class='odd' style='text-align:center;' colspan= '7'>"
                  . _AM_LEXIKON_NOREQSYET
                  . '</td></tr></div>';
        }
        echo "</table>";
        $pagenav = new XoopsPageNav($numrowsX, $xoopsModuleConfig['perpage'], $startsub, 'startsub');
        echo '<div style="text-align:right;">'
              . $pagenav->renderNav(8)
              . '</div>';
        echo "<br></div></div>";
}
require_once __DIR__ . '/admin_footer.php';
