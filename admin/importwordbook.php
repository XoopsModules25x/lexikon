<?php
//////////////////////////////////////////////////////////////////////////////
//
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
//                                                                          //
// This program is distributed in the hope that it will be useful, but      //
// WITHOUT ANY WARRANTY; without even the implied warranty of               //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU         //
// General Public License for more details.                                 //
//                                                                          //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the                            //
// Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,      //
// MA 02111-1307 USA                                                        //
// ------------------------------------------------------------------------ //
// code partially from Aiba and rmdp                                        //
// ------------------------------------------------------------------------ //
// import script Wordbook -> Lexikon                                        //
// ------------------------------------------------------------------------ //
//////////////////////////////////////////////////////////////////////////////

require_once __DIR__ . '/admin_header.php';

$op = '';

/****
 * Available operations
 ****/ 
switch ($op) {
    case 'default':
    default:
        xoops_cp_header();
        global $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsModule;
        $myts = MyTextSanitizer::getInstance();
}

/****
 * Start Import
 ***
 * @param $msg
 */

function showerror($msg)
{
    global $xoopsDB;
    if ($xoopsDB->error() != '') {
        echo '<br>' . $msg . ' <br><span style="font-size: xx-small; "> - ' . _AM_LEXIKON_IMPORT_ERROR . ': ' . $xoopsDB->error() . '</span>.';
    } else {
        echo '<br>' . $msg . '' . _AM_LEXIKON_IMPORT_OK;
    }
}

/**
 * @param $text
 * @return mixed
 */
function import2db($text)
{
    return preg_replace(["/'/i"], ["\'"], $text);
}

/**
 * @param $delete
 */
function DefinitionImport($delete)
{
    global $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModule, $entryID, $myts;
    $sqlQuery = $xoopsDB->query('SELECT count(entryID) AS count FROM ' . $xoopsDB->prefix('wbentries'));
    list($count) = $xoopsDB->fetchRow($sqlQuery);
    if ($count < 1) {
        redirect_header('import.php', 1, _AM_LEXIKON_MODULEIMPORTEMPTY10);
    }
    $delete       = 0;
    $wbkcounter   = 0;
    $errorcounter = 0;
    $wbkcounter1  = 0;

    if (isset($delete)) {
        $delete = (int)$_POST['delete'];
    } else {
        if (isset($delete)) {
            $delete = (int)$_POST['delete'];
        }
    }

    /****
     * delete all entries and categories without comments
     ****/
    if ($delete) {
        // delete notifications
        xoops_notification_deletebymodule($xoopsModule->getVar('mid'));
        //get all entries
        $resultE = $xoopsDB->query('SELECT entryID FROM ' . $xoopsDB->prefix('lxentries') . '');
        while (list($entryID) = $xoopsDB->fetchRow($resultE)) {
            //delete comments for each entry
            xoops_comment_delete($xoopsModule->getVar('mid'), $entryID);
        }
        $resultC = $xoopsDB->query('SELECT categoryID FROM ' . $xoopsDB->prefix('lxcategories') . '');
        while (list($categoryID) = $xoopsDB->fetchRow($resultC)) {
            // delete permissions
            xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'lexikon_view', $categoryID);
            xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'lexikon_submit', $categoryID);
            xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'lexikon_approve', $categoryID);
            xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'lexikon_request', $categoryID);
        }
        // delete everything
        $sqlquery1 = $xoopsDB->queryF('TRUNCATE TABLE ' . $xoopsDB->prefix('lxentries'));
        $sqlquery2 = $xoopsDB->queryF('TRUNCATE TABLE ' . $xoopsDB->prefix('lxcategories'));
    }

    /****
     * Import ENTRIES
     ****/

    $sql1    = $xoopsDB->query('
                                SELECT *
                                FROM ' . $xoopsDB->prefix('wbentries') . '
                              ');
    $result1 = $xoopsDB->getRowsNum($sql1);
    if ($result1) {
        while ($row2 = $xoopsDB->fetchArray($sql1)) {
            $entryID    = (int)$row2['entryID'];
            $categoryID = (int)$row2['categoryID'];
            #$term        = $myts -> addSlashes($row2['term']);
            $term       = $myts->addSlashes(import2db($row2['term']));
            $init       = $myts->addSlashes($row2['init']);
            $definition = $myts->addSlashes(import2db($row2['definition']));
            $ref        = $myts->addSlashes($row2['ref']);
            $url        = $myts->addSlashes($row2['url']);
            $uid        = (int)$row2['uid'];
            $submit     = (int)$row2['submit'];
            $datesub    = (int)$row2['datesub'];
            $counter    = (int)$row2['counter'];
            $html       = (int)$row2['html'];
            $smiley     = (int)$row2['smiley'];
            $xcodes     = (int)$row2['xcodes'];
            $breaks     = (int)$row2['breaks'];
            $block      = (int)$row2['block'];
            $offline    = (int)$row2['offline'];
            $notifypub  = (int)$row2['notifypub'];
            $request    = (int)$row2['request'];
            $comments   = (int)$row2['comments'];
            ++$wbkcounter;

            if ($delete) {
                $ret1 = $xoopsDB->queryF('
                                         INSERT INTO ' . $xoopsDB->prefix('lxentries') . "
                                         (entryID, categoryID, term, init, definition, ref, url, uid, submit, datesub, counter, html, smiley, xcodes, breaks, block, offline, notifypub, request, comments)
                                         VALUES
                                         ('$entryID', '$categoryID', '$term', '$init', '$definition', '$ref', '$url', '$uid', '$submit', '$datesub', '$counter', '$html', '$smiley', '$xcodes', '$breaks', '$block', '$offline', '$notifypub', '$request', '$comments' )");
            } else {
                $ret1 = $xoopsDB->queryF('
                                         INSERT INTO ' . $xoopsDB->prefix('lxentries') . "
                                         (entryID, categoryID, term, init, definition, ref, url, uid, submit, datesub, counter, html, smiley, xcodes, breaks, block, offline, notifypub, request, comments)
                                         VALUES
                                         ('', '$categoryID', '$term', '$init', '$definition', '$ref', '$url', '$uid', '$submit', '$datesub', '$counter', '$html', '$smiley', '$xcodes', '$breaks', '$block', '$offline', '$notifypub', '$request', '$comments' )");
            }
            if (!$ret1) {
                ++$errorcounter;
                showerror('<br>' . _AM_LEXIKON_IMPORT_ERROR_IMPORT_TERM . ': <span style="color:red">entryID: ' . $entryID . '</span>: ' . $term . ' ...');
            }
            // update user posts count
            if ($ret1) {
                if ($uid) {
                    $memberHandler = xoops_getHandler('member');
                    $submitter     = $memberHandler->getUser($uid);
                    if (is_object($submitter)) {
                        $submitter->setVar('posts', $submitter->getVar('posts') + 1);
                        $res = $memberHandler->insertUser($submitter, true);
                        unset($submitter);
                    }
                }
            }
        }
    }

    /****
     * Import CATEGORIES
     ****/

    $sql3 = $xoopsDB->query('SELECT *
                              FROM ' . $xoopsDB->prefix('wbcategories') . '
                              ORDER BY categoryID');

    $result3 = $xoopsDB->getRowsNum($sql3);
    if ($result3) {
        while ($row1 = $xoopsDB->fetchArray($sql3)) {
            $categoryID  = (int)$row1['categoryID'];
            $name        = $myts->addSlashes($row1['name']);
            $description = $myts->addSlashes(import2db($row1['description']));
            $total       = (int)$row1['total'];
            $weight      = (int)$row1['weight'];
            ++$wbkcounter1;

            // insert new field ``
            if ($delete) {
                $ret3 = $xoopsDB->queryF('
                                         INSERT INTO ' . $xoopsDB->prefix('lxcategories') . "
                                         (categoryID, name, description, total, weight)
                                         VALUES ('$categoryID','$name', '$description', '$total', '$weight')");
            } else {
                $ret3 = $xoopsDB->queryF('
                                         INSERT INTO ' . $xoopsDB->prefix('lxcategories') . "
                                         (categoryID, name, description, total, weight)
                                         VALUES ('', '$name', '$description', '$total', '$weight')");
            }
            if (!$ret3) {
                ++$errorcounter;
                showerror('<br>' . _AM_LEXIKON_IMPORT_ERROR_IMPORT_CAT . ': <span style="color:red">categoryID: ' . $categoryID . '</span>: ' . $name . ' ...');
            }
        }
    }

    /****
     * FINISH
     ****/

    $sqlquery4 = $xoopsDB->query('
                               SELECT mid
                               FROM ' . $xoopsDB->prefix('modules') . "
                               WHERE dirname = 'wordbook'");
    list($wbkID) = $xoopsDB->fetchRow($sqlquery4);

    echo '<p>' . _AM_LEXIKON_IMPORT_MODULE_ID . ': ' . $wbkID . '</p>';
    echo '<p>' . _AM_LEXIKON_IMPORT_MODULE_LEX_ID . ': ' . $xoopsModule->getVar('mid') . '<br>';

    $commentaire = $xoopsDB->queryF('
                                    UPDATE ' . $xoopsDB->prefix('xoopscomments') . "
                                    SET com_modid = '" . $xoopsModule->getVar('mid') . "'
                                    WHERE com_modid = '" . $wbkID . "'");

    if (!$commentaire) {
        showerror(_AM_LEXIKON_IMPORT_ERROR_IMPORT_COMMENT . ':  ...');
    } else {
        showerror(_AM_LEXIKON_IMPORT_COMMENT . ':  ');
    }
    echo "<p>" . _AM_LEXIKON_IMPORT_UPDATE_COUNT . "</p>";
    echo "<p><span style='color:red'>" . _AM_LEXIKON_IMPORT_INCORRECTLY . ": " . $errorcounter . '</span></p>';
    echo "<p>" . _AM_LEXIKON_IMPORT_PROCESSED . " Entries: " . $wbkcounter . "</p>";
    echo "<p>" . _AM_LEXIKON_IMPORT_PROCESSED . " Categories: " . $wbkcounter1 . "</p>";
    echo "<h3>" . _AM_LEXIKON_IMPORT_FINISH . "</h3>";
    echo "<br><b><a href='import.php'>" . _AM_LEXIKON_IMPORT_TO_ADMIN . "</a></b><p>";
    require_once __DIR__ . '/admin_footer.php';
}

/****
 * IMPORT FORM PLAIN HTML
 ****/

function FormImport()
{
    global $xoopsConfig, $xoopsDB, $xoopsModule;
    //lx_importMenu(9);
        echo "<strong style='color: #2F5376; margin-top:6px; font-size:medium'>"
        . _AM_LEXIKON_IMPORT_WORDBOOK
        . "</strong><br><br>";
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler  = xoops_getHandler('module');
    $wordbookModule = $moduleHandler->getByDirname('wordbook');
    $got_options    = false;
    if (is_object($wordbookModule)) {
        echo "<table style='width:100%; border:0;' class='outer'>";
        echo '<tr>';
        echo "<td colspan='2' class='bg3' style='text-align:left;'><span style='font-size: x-small; '><b>"
        . _AM_LEXIKON_MODULEHEADIMPORTWB
        . '</b></span></td>';
        echo '</tr>';

        echo '<tr>';
        echo "<td class='head' style='width:200px; text-align:center;'><img src='"
             . XOOPS_URL
             . '/modules/'
             . $xoopsModule->dirname()
             . '/assets/images/dialog-important.png'
             . "' alt='' style='margin-right:10px;  margin-top:20px; text-align:middle;'></td>";
        echo "<td class='even' style='text-align:center;'><br><b><span style='font-size:x-small; color:red;'>"
        . _AM_LEXIKON_IMPORTWARN
        . '</span></b></td>';
        echo '</tr>';

        echo '<tr>';
        echo "<td class='head' style='width:200px; text-align:left'><span style='font-size:x-small;'>"
        . _AM_LEXIKON_IMPORTDELWB
        . '</span></td>';
        echo "<td class='even' style='text-align:center;'><form action='importdictionary.php?op=import' method=POST>
        <input type='radio' name='delete' value='1'>&nbsp;"
        . _YES
        . "&nbsp;&nbsp;
        <input type='radio' name='delete' value='0' checked>&nbsp;"
        . _NO
        . '</td>';
        echo "</tr><tr><td class='head' style='width:200px; text-align:center;'>&nbsp;</td>";
        echo "<td class='even' style='text-align:center;'>
        <input type='submit' name='button' id='import' value='"
        . _AM_LEXIKON_IMPORT
        . "'>&nbsp;
        <input type='button' name='cancel' value='"
        . _CANCEL
        . "' onclick='history.go(-1);'></td>";
        echo "</tr></table><br>\n";
    } else {
        echo "<br><b><span style='color:red'>"
        . _AM_LEXIKON_IMPORT_ERROR_MODULE
        . "</span></b><br><br><a href='import.php'><button>"
        . _AM_LEXIKON_BACK
        ."</button></a>";
    }
    require_once __DIR__ . '/admin_footer.php';
}

global $op;
$op = isset($_GET['op']) ? $_GET['op'] : (isset($_POST['op']) ? $_POST['op'] : '');
switch ($op) {
    case 'import':
        $delete = isset($_GET['delete']) ? (int)$_GET['delete'] : (int)$_POST['delete'];
        DefinitionImport($delete);
        break;
    default:
        FormImport();
        break;
}
