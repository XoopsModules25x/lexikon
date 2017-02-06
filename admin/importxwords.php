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
// import script XWords  -> Lexikon                                         //
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
    //    lx_adminMenu(9, _AM_LEXIKON_IMPORT);
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
        echo '<br>' . $msg . ' <br><span style="font-size: xx-small; "> -  ERROR: ' . $xoopsDB->error() . '</span>.';
    } else {
        echo '<br>' . $msg . ' O.K.!';
    }
}

/**
 * @param $text
 * @return mixed
 */
function import2db($text)
{
    return preg_replace(array("/'/i"), array("\'"), $text);
}

/**
 * @param $delete
 */
function DefinitionImport($delete)
{
    global $xoopsConfig, $xoopsDB, $xoopsModule;
    $myts     = MyTextSanitizer::getInstance();
    $sqlQuery = $xoopsDB->query('SELECT count(entryID) AS count FROM ' . $xoopsDB->prefix('xwords_ent'));
    list($count) = $xoopsDB->fetchRow($sqlQuery);
    if ($count < 1) {
        redirect_header('index.php', 1, _AM_LEXIKON_MODULEIMPORTEMPTY10);
    }

    $delete       = 0;
    $wxocounter   = 0;
    $errorcounter = 0;
    $wxocounter1  = 0;
    //$errorcounter1 = 0;

    if (isset($delete)) {
        $delete = (int)$_POST['delete'];
    } else {
        if (isset($delete)) {
            $delete = (int)$_POST['delete'];
        }
    }

    /****
     * delete all entries and categories. Xwords has no comments.
     ****/
    if ($delete) {
        // delete notifications
        xoops_notification_deletebymodule($xoopsModule->getVar('mid'));
        //get all entries
        $result3 = $xoopsDB->query('SELECT entryID FROM ' . $xoopsDB->prefix('lxentries') . '');
        //delete comments for each entry
        while (list($entryID) = $xoopsDB->fetchRow($result3)) {
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

    $sql1 = $xoopsDB->query('
                              SELECT *
                              FROM ' . $xoopsDB->prefix('xwords_ent') . ' ');

    $result1 = $xoopsDB->getRowsNum($sql1);
    if ($result1) {
        while ($row2 = $xoopsDB->fetchArray($sql1)) {
            $entryID    = (int)$row2['entryID'];
            $categoryID = (int)$row2['categoryID'];
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

            ++$wxocounter;

            if ($delete) {
                $ret1 = $xoopsDB->queryF('
                                         INSERT INTO ' . $xoopsDB->prefix('lxentries') . "
                                         (entryID, categoryID, term, init, definition, ref, url, uid, submit, datesub, counter, html, smiley, xcodes, breaks, block, offline, notifypub, request)
                                         VALUES
                                         ('$entryID', '$categoryID', '$term', '$init', '$definition', '$ref', '$url', '$uid', '$submit', '$datesub', '$counter', '$html', '$smiley', '$xcodes', '$breaks', '$block', '$offline', '$notifypub', '$request' )");
            } else {
                $ret1 = $xoopsDB->queryF('
                                         INSERT INTO ' . $xoopsDB->prefix('lxentries') . "
                                         (entryID, categoryID, term, init, definition, ref, url, uid, submit, datesub, counter, html, smiley, xcodes, breaks, block, offline, notifypub, request)
                                         VALUES
                                         ('', '$categoryID', '$term', '$init', '$definition', '$ref', '$url', '$uid', '$submit', '$datesub', '$counter', '$html', '$smiley', '$xcodes', '$breaks', '$block', '$offline', '$notifypub', '$request' )");
            }
            if (!$ret1) {
                ++$errorcounter;
                showerror('<br>Import term failed: <span style="color:red">entryID: ' . $entryID . '</span>: ' . $term . ' ...');
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
    $sql2 = $xoopsDB->query('SELECT *
                              FROM ' . $xoopsDB->prefix('xwords_cat') . '
                              ORDER BY categoryID');

    $result2 = $xoopsDB->getRowsNum($sql2);
    if ($result2) {
        while ($row1 = $xoopsDB->fetchArray($sql2)) {
            $categoryID  = (int)$row1['categoryID'];
            $name        = $myts->addSlashes($row1['name']);
            $description = $myts->addSlashes(import2db($row1['description']));
            $total       = (int)$row1['total'];
            $weight      = (int)$row1['weight'];
            ++$wxocounter1;

            if ($delete) {
                $ret2 = $xoopsDB->queryF('
                                         INSERT INTO ' . $xoopsDB->prefix('lxcategories') . "
                                         (categoryID, name, description, total, weight)
                                         VALUES ('$categoryID', '$name', '$description', '$total', '$weight')");
            } else {
                $ret2 = $xoopsDB->queryF('
                                         INSERT INTO ' . $xoopsDB->prefix('lxcategories') . "
                                         (categoryID, name, description, total, weight)
                                         VALUES ('', '$name', '$description', '$total', '$weight')");
            }
            if (!$ret2) {
                ++$errorcounter;
                showerror('<br>Import category failed: <span style="color:red">categoryID: ' . $categoryID . '</span>: ' . $name . ' ...');
            }
        }
    }

    /****
     * FINISH
     ****/

    $sqlQuery = $xoopsDB->query('SELECT mid
                              FROM ' . $xoopsDB->prefix('modules') . "
                              WHERE dirname = 'xwords'");
    list($xwdID) = $xoopsDB->fetchRow($sqlQuery);
    echo '<p>XWords Module ID: ' . $xwdID . '</p>';
    echo '<p>Lexikon Module ID: ' . $xoopsModule->getVar('mid') . '</p>';
    //echo "<p>delete is on/off: ".$delete."</p>";

    echo '<p>Update User Post count: O.K.!</p>';
    echo "<p><span style='color:red'>Incorrectly: " . $errorcounter . '</span></p>';
    echo '<p>Processed Entries: ' . $wxocounter . '</p>';
    //echo "<p><span style='color:red'>Categories Incorrectly: ".$errorcounter1."</span></p>";
    echo '<p>Processed Categories: ' . $wxocounter1 . '<br>';
    echo '<H3>Import finished!</H3>';
    echo "<br><B><a href='index.php'>Back to Admin</a></B><p>";
    xoops_cp_footer();
}

/****
 * IMPORT FORM PLAIN HTML
 ****/

function FormImport()
{
    global $xoopsConfig, $xoopsDB, $xoopsModule;
    lx_importMenu(9);
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $xwordsModule  = $moduleHandler->getByDirname('xwords');
    $got_options   = false;
    if (is_object($xwordsModule)) {
        echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
        echo '<tr>';
        echo "<td colspan='2' class='bg3' align='left'><span style='font-size: x-small; '><b>" . _AM_LEXIKON_MODULEHEADIMPORTXWO . '</b></span></td>';
        echo '</tr>';

        echo '<tr>';
        echo "<td class='head' width = '200' align='center'><img src='"
             . XOOPS_URL
             . '/modules/'
             . $xoopsModule->dirname()
             . '/assets/images/dialog-important.png'
             . "' alt='' hspace='0' vspace='0' align='middle' style='margin-right: 10px; margin-top: 20px;'></td>";
        echo "<td class='even' align='center'><br><B><span style='font-size: x-small; color: red'>" . _AM_LEXIKON_IMPORTWARN . '</span></B><P></td>';
        echo '</tr>';

        echo '<tr>';
        echo "<td class='head' width = '200' align='left'><span style='font-size: x-small; '>" . _AM_LEXIKON_IMPORTDELWB . '</span></td>';
        echo "<td class='even' align='center'><FORM ACTION='importxwords.php?op=import' METHOD=POST>
        <input type='radio' name='delete' value='1'>&nbsp;" . _YES . "&nbsp;&nbsp;
        <input type='radio' name='delete' value='0' checked>&nbsp;" . _NO . '<b>
        </td>';
        echo "</tr><tr><td width = '200' class='head' align='center'>&nbsp;</td>";
        echo "<td class='even' align='center'>
        <input type='submit' name='button' id='import' value='" . _AM_LEXIKON_IMPORT . "'>&nbsp;
        <input type='button' name='cancel' value='" . _CANCEL . "' onclick='history.go(-1);'></td>";
        echo "</tr></table><br>\n";
    } else {
        echo "<br><B><span style='color:red'>Module Xwords not found on this site.</span></B><br><A HREF='index.php'>Back</A><P>";
    }

    xoops_cp_footer();
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
