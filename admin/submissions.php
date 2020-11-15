<?php
/**
 * Module: Lexikon - glossary module
 * Author: hsalazar
 * Changes: Yerres
 * Licence: GNU
 */

//file obsolete . remains for compatibility reasons

use Xmf\Module\Admin;
use Xmf\Request;
use XoopsModules\Lexikon\{
    Helper,
    Utility
};
/** @var Helper $helper */

require_once __DIR__ . '/admin_header.php';


$helper = Helper::getInstance();
$myts   = \MyTextSanitizer::getInstance();
xoops_load('XoopsUserUtility');

$op = '';

if (Request::hasVar('op', 'GET')) {
    $op = $_GET['op'];
}
if (Request::hasVar('op', 'POST')) {
    $op = $_POST['op'];
}

/* -- Available operations -- */
switch ($op) {
    case 'default':
    default:
        require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

        $startentry = Request::getInt('startentry', 0, 'GET');
        $startcat   = Request::getInt('startcat', 0, 'GET');
        $startsub   = Request::getInt('startsub', 0, 'GET');
        $datesub    = Request::getInt('datesub', 0, 'GET');
        xoops_cp_header();
        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        global $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModule, $entryID;

        $myts = \MyTextSanitizer::getInstance();

        $result01 = $xoopsDB->query(
            'SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxcategories') . ' '
        );
        [$totalcategories] = $xoopsDB->fetchRow($result01);

        $result02 = $xoopsDB->query(
            'SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxentries') . '
                                   WHERE submit = 0'
        );
        [$totalpublished] = $xoopsDB->fetchRow($result02);

        $result03 = $xoopsDB->query(
            'SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxentries') . "
                                   WHERE submit = '1' AND request = '0' "
        );
        [$totalsubmitted] = $xoopsDB->fetchRow($result03);

        $result04 = $xoopsDB->query(
            'SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxentries') . "
                                   WHERE submit = '1' AND request = '1' "
        );
        [$totalrequested] = $xoopsDB->fetchRow($result04);

        $result05 = $xoopsDB->query(
            'SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxentries') . "
                                   WHERE offline = '1'  "
        );
        [$totaloffline] = $xoopsDB->fetchRow($result05);

        echo "<table class='outer' style='margin-top:6px; clear:both; width:99%;'><tr>";
        echo "<th style='text-align:right;'>" . _AM_LEXIKON_TOTALENTRIES . " </th><td style='text-align:center;' class='even'>" . $totalpublished . '</td>';
        if (1 == $helper->getConfig('multicats')) {
            echo "<th style='text-align:right;'>" . _AM_LEXIKON_TOTALCATS . "</th><td style='text-align:center;' class='even'>" . $totalcategories . '</td>';
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
        echo "  <img  onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='lexikonsubicon' name='lexikonsubicon' src='" . XOOPS_URL . "/modules/lexikon/assets/images/close12.gif' alt=''></a>&nbsp;<strong>" . _AM_LEXIKON_SHOWSUBMISSIONS . ' (' . $totalsubmitted . ')' . '</strong><br>';
        echo "<div id='lexikonsub' style='float:left; width:100%;'><table class='outer' style='width:99%;'>";
        $resultS1 = $xoopsDB->query(
            'SELECT COUNT(*)
                                     FROM ' . $xoopsDB->prefix('lxentries') . "
                                     WHERE submit = '1' AND request = '0' "
        );
        [$numrows] = $xoopsDB->fetchRow($resultS1);

        $sql      = 'SELECT entryID, categoryID, term, uid, datesub
                     FROM ' . $xoopsDB->prefix('lxentries') . "
                     WHERE submit = '1' AND request = '0'
                     ORDER BY datesub DESC";
        $resultS2 = $xoopsDB->query($sql, $helper->getConfig('perpage'), $startsub);

        echo "<th style='text-align:center; width:40px;'>" . _AM_LEXIKON_ENTRYID . '</th>';
        if (1 == $helper->getConfig('multicats')) {
            echo "<th style='text-align:center; width:20%;'>" . _AM_LEXIKON_ENTRYCATNAME . '</th>';
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
            while (list($entryID, $categoryID, $term, $uid, $created) = $xoopsDB->fetchRow($resultS2)) {
                $resultS3 = $xoopsDB->query(
                    'SELECT name
                                           FROM ' . $xoopsDB->prefix('lxcategories') . "
                                           WHERE categoryID = '$categoryID'"
                );
                [$name] = $xoopsDB->fetchRow($resultS3);

                $sentby = \XoopsUserUtility::getUnameFromId($uid);

                $catname = htmlspecialchars($name);
                $term    = htmlspecialchars($term);
                $created = formatTimestamp($created, 's');
                $modify  = "<a href='entry.php?op=mod&entryID=" . $entryID . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _AM_LEXIKON_EDITSUBM . "'></a>";
                $delete  = "<a href='entry.php?op=del&entryID=" . $entryID . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _AM_LEXIKON_DELETESUBM . "'></a>";

                echo "<tr><td class='even' style='text-align:center;'>" . $entryID . '</td>';
                if (1 == $helper->getConfig('multicats')) {
                    echo "<td class='odd' style='text-align:left;'>" . $catname . '</td>';
                }
                echo "<td class='odd' style='text-align:left;'>"
                     . $term
                     . "</td><td class='odd' style='text-align:center;'>"
                     . $sentby
                     . "</td><td class='odd' style='text-align:center;'>"
                     . $created
                     . "</td><td class='even' style='text-align:center;'>"
                     . $modify
                     . '-'
                     . $delete
                     . '</td></tr></div>';
            }
        } else { // that is, $numrows = 0, there's no columns yet
            echo "<tr><td class='odd' style='text-align:center;' colspan= '7'>" . _AM_LEXIKON_NOSUBMISSYET . '</td></tr></div>';
        }
        echo '</table>';
        $pagenav = new \XoopsPageNav($numrows, $helper->getConfig('perpage'), $startsub, 'startsub');
        echo '<div style="text-align:right;">' . $pagenav->renderNav(8) . '</div>';
        echo ' <br></div>';
        echo '</div>';

        /**
         * Code to show requested entries
         **/
        lx_collapsableBar('lexikonreq', 'lexikonreqicon');
        echo "  <img  onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='lexikonreqicon' name='lexikonreqicon' src='" . XOOPS_URL . "/modules/lexikon/assets/images/close12.gif' alt=''></a>&nbsp;<strong>" . _AM_LEXIKON_SHOWREQUESTS . ' (' . $totalrequested . ')' . '</strong><br>';
        echo "<div id='lexikonreq' style='float:left; width:100%;'><table class='outer' style='width:99%;'>";
        $resultS2 = $xoopsDB->query(
            'SELECT COUNT(*)
                                     FROM ' . $xoopsDB->prefix('lxentries') . "
                                     WHERE submit = '1' AND request = '1'"
        );
        [$numrowsX] = $xoopsDB->fetchRow($resultS2);

        $sql4     = 'SELECT entryID, categoryID, term, uid, datesub
                    FROM ' . $xoopsDB->prefix('lxentries') . "
                    WHERE submit = '1' AND request = '1'
                    ORDER BY datesub DESC";
        $resultS4 = $xoopsDB->query($sql4, $helper->getConfig('perpage'), $startsub);

        echo "<th style='text-align:center; width:40px;'>" . _AM_LEXIKON_ENTRYID . '</th>';
        if (1 == $helper->getConfig('multicats')) {
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
             . '</th></tr>';

        if ($numrowsX > 0) { // That is, if there ARE unauthorized articles in the system
            while (list($entryID, $categoryID, $term, $uid, $created) = $xoopsDB->fetchRow($resultS4)) {
                $resultS3 = $xoopsDB->query(
                    'SELECT name
                                             FROM ' . $xoopsDB->prefix('lxcategories') . "
                                             WHERE categoryID = '$categoryID'"
                );
                [$name] = $xoopsDB->fetchRow($resultS3);

                $sentby = \XoopsUserUtility::getUnameFromId($uid);

                $catname = htmlspecialchars($name);
                $term    = htmlspecialchars($term);
                $created = formatTimestamp($created, 's');
                $modify  = "<a href='entry.php?op=mod&entryID=" . $entryID . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _AM_LEXIKON_EDITSUBM . "'></a>";
                $delete  = "<a href='entry.php?op=del&entryID=" . $entryID . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _AM_LEXIKON_DELETESUBM . "'></a>";
                echo '<tr>';
                echo "<td class='even' style='text-align:center;'>" . $entryID . '</td>';
                if (1 == $helper->getConfig('multicats')) {
                    echo "<td class='odd' align='left'>" . $catname . '</td>';
                }
                echo "<td class='odd' style='text-align:left;'>"
                     . $term
                     . "</td><td class='odd' style='text-align:center;'>"
                     . $sentby
                     . "</td><td class='odd' style='text-align:center;'>"
                     . $created
                     . "</td><td class='even' style='text-align:center;'>"
                     . $modify
                     . '-'
                     . $delete
                     . '</td></tr></div>';
            }
        } else { // that is, $numrows = 0, there's no columns yet
            echo "<tr><td class='odd' style='text-align:center;' colspan= '7'>" . _AM_LEXIKON_NOREQSYET . '</td></tr></div>';
        }
        echo '</table>';
        $pagenav = new \XoopsPageNav($numrowsX, $helper->getConfig('perpage'), $startsub, 'startsub');
        echo '<div style="text-align:right;">' . $pagenav->renderNav(8) . '</div>';
        echo '<br></div></div>';

        /**
         * Code to show offline entries
         **/
        lx_collapsableBar('lexikonoff', 'lexikonofficon');
        echo "  <img  onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='lexikonofficon' name='lexikonofficon' src='" . XOOPS_URL . "/modules/lexikon/assets/images/close12.gif' alt='' ></a>&nbsp;<strong>" . _AM_LEXIKON_SHOWOFFLINE . ' (' . $totaloffline . ')' . '</strong><br>';
        echo "  <div id='lexikonoff' style='float:left; width:100%;'><table class='outer' style='width:99%;'>";
        $resultS2 = $xoopsDB->query(
            'SELECT COUNT(*)
                                     FROM ' . $xoopsDB->prefix('lxentries') . "
                                     WHERE offline = '1'"
        );
        [$numrowsX] = $xoopsDB->fetchRow($resultS2);

        $sql4     = 'SELECT entryID, categoryID, term, uid, datesub
                    FROM ' . $xoopsDB->prefix('lxentries') . "
                    WHERE offline = '1'
                    ORDER BY datesub DESC";
        $resultS4 = $xoopsDB->query($sql4, $helper->getConfig('perpage'), $startsub);

        echo "<th style='text-align:center; width:40px;'>" . _AM_LEXIKON_ENTRYID . '</th>';
        if (1 == $helper->getConfig('multicats')) {
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
             . '</th></tr>';

        if ($numrowsX > 0) { // That is, if there ARE unauthorized articles in the system
            while (list($entryID, $categoryID, $term, $uid, $created) = $xoopsDB->fetchRow($resultS4)) {
                $resultS3 = $xoopsDB->query(
                    'SELECT name
                                             FROM ' . $xoopsDB->prefix('lxcategories') . "
                                             WHERE categoryID = '$categoryID'"
                );
                [$name] = $xoopsDB->fetchRow($resultS3);

                $sentby = \XoopsUserUtility::getUnameFromId($uid);

                $catname = htmlspecialchars($name);
                $term    = htmlspecialchars($term);
                $created = formatTimestamp($created, 's');
                $modify  = "<a href='entry.php?op=mod&entryID=" . $entryID . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _AM_LEXIKON_EDITSUBM . "'></a>";
                $delete  = "<a href='entry.php?op=del&entryID=" . $entryID . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _AM_LEXIKON_DELETESUBM . "'></a>";
                echo "<tr><td class='even' style='text-align:center;'>" . $entryID . '</td>';
                if (1 == $helper->getConfig('multicats')) {
                    echo "<td class='odd' style='text-align:left;'>" . $catname . '</td>';
                }
                echo "<td class='odd' style='text-align:left;'>"
                     . $term
                     . "</td><td class='odd' style='text-align:center;'>"
                     . $sentby
                     . "</td><td class='odd' style='text-align:center;'>"
                     . $created
                     . "</td><td class='even' style='text-align:center;'>"
                     . $modify
                     . '-'
                     . $delete
                     . '</td></tr></div>';
            }
        } else { // that is, $numrows = 0, there's no columns yet
            echo "<tr><td class='odd' style='text-align:center;' colspan= '7'>" . _AM_LEXIKON_NOREQSYET . '</td></tr></div>';
        }
        echo '</table>';
        $pagenav = new \XoopsPageNav($numrowsX, $helper->getConfig('perpage'), $startsub, 'startsub');
        echo '<div style="text-align:right;">' . $pagenav->renderNav(8) . '</div>';
        echo '<br></div></div>';
}
require_once __DIR__ . '/admin_footer.php';
