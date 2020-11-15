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
// import script wiwimod  -> Lexikon                                        //
// ------------------------------------------------------------------------ //
//////////////////////////////////////////////////////////////////////////////

use Xmf\Request;

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
        $myts = \MyTextSanitizer::getInstance();
}

/****
 * Start Import
 ***
 * @param $msg
 */
function showerror($msg)
{
    global $xoopsDB;
    if ('' != $xoopsDB->error()) {
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
    global $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsModule, $myts;
    $myts = \MyTextSanitizer::getInstance();

    $sqlQuery = $xoopsDB->query('SELECT count(id) AS count FROM ' . $xoopsDB->prefix('wiwimod'));
    [$count] = $xoopsDB->fetchRow($sqlQuery);
    if ($count < 1) {
        redirect_header('import.php', 1, _AM_LEXIKON_MODULEIMPORTEMPTY10);
    }

    $delete       = 0;
    $wiwicounter  = 0;
    $errorcounter = 0;

    if (isset($delete)) {
        $delete = \Xmf\Request::getInt('delete', 0, 'POST');
    } else {
        if (isset($delete)) {
            $delete = \Xmf\Request::getInt('delete', 0, 'POST');
        }
    }

    /****
     * delete all entries and categories + comments
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

    $sqlQuery = $xoopsDB->query(
        '
                              SELECT id, title, body, u_id, lastmodified datetime, visible
                              FROM ' . $xoopsDB->prefix('wiwimod')
    );

    $fecha = time() - 1;
    while (false !== ($sqlfetch = $xoopsDB->fetchArray($sqlQuery))) {
        $wiwi                 = [];
        $wiwi['id']           = $sqlfetch['id'];
        $wiwi['title']        = $sqlfetch['title'];
        $wiwi['body']         = $myts->addSlashes(import2db($sqlfetch['body']));
        $wiwi['u_id']         = import2db($sqlfetch['u_id']);
        $wiwi['lastmodified'] = ++$fecha;
        $wiwi['visible']      = $sqlfetch['visible'];
        ++$wiwicounter;

        if ($delete) {
            $insert = $xoopsDB->queryF(
                '
                                       INSERT INTO ' . $xoopsDB->prefix('lxentries') . " (entryID, term, definition, uid, datesub, offline, html)
                                       VALUES ('" . $wiwi['id'] . "','" . $wiwi['title'] . "','" . $wiwi['body'] . "','" . $wiwi['u_id'] . "','" . $wiwi['lastmodified'] . "','" . $wiwi['visible'] . "','1')"
            );
        } else {
            $insert = $xoopsDB->queryF(
                '
                                       INSERT INTO ' . $xoopsDB->prefix('lxentries') . " (entryID, term, definition, uid, datesub, offline, html)
                                       VALUES ('','" . $wiwi['title'] . "','" . $wiwi['body'] . "','" . $wiwi['u_id'] . "','" . $wiwi['lastmodified'] . "','" . $wiwi['visible'] . "','1')"
            );
        }
        if (!$insert) {
            ++$errorcounter;
            showerror('<br>' . _AM_LEXIKON_IMPORT_ERROR_IMPORT_TERM . ': <span style="color:red">ID: ' . $wiwi['id'] . '</span>: ' . $wiwi['title'] . ' ...');
        }
        // update user posts count
        if ($ret1) {
            if ($uid) {
                /** @var \XoopsMemberHandler $memberHandler */
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

    $sqlQuery = $xoopsDB->query(
        'SELECT mid
                              FROM ' . $xoopsDB->prefix('modules') . "
                              WHERE dirname = 'wiwimod'"
    );
    [$wiwiID] = $xoopsDB->fetchRow($sqlQuery);
    echo '<p>' . _AM_LEXIKON_IMPORT_MODULE_ID . ': ' . $wiwiID . '</p>';
    echo '<p>' . _AM_LEXIKON_IMPORT_MODULE_LEX_ID . ': ' . $xoopsModule->getVar('mid') . '</p>';
    echo '<p>' . _AM_LEXIKON_IMPORT_UPDATE_COUNT . '</p>';
    echo "<p><span style='color:red'>" . _AM_LEXIKON_IMPORT_INCORRECTLY . ': ' . $errorcounter . '</span></p>';
    echo '<p>' . _AM_LEXIKON_IMPORT_PROCESSED . ': ' . $glocounter . '</p>';
    echo '<h3>' . _AM_LEXIKON_IMPORT_FINISH . '</h3>';
    echo "<br><b><a href='import.php'>" . _AM_LEXIKON_IMPORT_TO_ADMIN . '</a></b><p>';
    require_once __DIR__ . '/admin_footer.php';
}

/****
 * IMPORT FORM PLAIN HTML
 ****/

function FormImport()
{
    global $xoopsConfig, $xoopsDB, $xoopsModule;
    //lx_importMenu(9);
    echo "<strong style='color: #2F5376; margin-top:6px; font-size:medium'>" . _AM_LEXIKON_IMPORT_WIWIMOD . '</strong><br><br>';
    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $wiwimodModule = $moduleHandler->getByDirname('wiwimod');
    $got_options   = false;
    if (is_object($wiwimodModule)) {
        echo "<table style='width:100%; border:0;' class='outer'>";
        echo '<tr>';
        echo "<td colspan='2' class='bg3' style='text-align:left;'><span style='font-size: x-small; '><b>" . _AM_LEXIKON_MODULEHEADIMPORTWW . '</b></span></td>';
        echo '</tr>';

        echo '<tr>';
        echo "<td class='head' style='width:200px; text-align:center;'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/assets/images/dialog-important.png' . "' alt='' style='margin-right:10px;  margin-top:20px; text-align:middle;'></td>";
        echo "<td class='even' style='text-align:center;'><br><b><span style='font-size:x-small; color:red;'>" . _AM_LEXIKON_IMPORTWARN . '</span></b></td>';
        echo '</tr>';

        echo '<tr>';
        echo "<td class='head' style='width:200px; text-align:left'><span style='font-size:x-small;'>" . _AM_LEXIKON_IMPORTDELWB . '</span></td>';
        echo "<td class='even' style='text-align:center;'><form action='importdictionary.php?op=import' method=POST>
        <input type='radio' name='delete' value='1'>&nbsp;" . _YES . "&nbsp;&nbsp;
        <input type='radio' name='delete' value='0' checked>&nbsp;" . _NO . '</td>';
        echo "</tr><tr><td class='head' style='width:200px; text-align:center;'>&nbsp;</td>";
        echo "<td class='even' style='text-align:center;'>
        <input type='submit' name='button' id='import' value='" . _AM_LEXIKON_IMPORT . "'>&nbsp;
        <input type='button' name='cancel' value='" . _CANCEL . "' onclick='history.go(-1);'></td>";
        echo "</tr></table><br>\n";
    } else {
        echo "<br><b><span style='color:red'>" . _AM_LEXIKON_IMPORT_ERROR_MODULE . "</span></b><br><br><a href='import.php'><button>" . _AM_LEXIKON_BACK . '</button></a>';
    }
    require_once __DIR__ . '/admin_footer.php';
}

$op = Request::getCmd('op', '');

switch ($op) {
    case 'import':
        $delete = \Xmf\Request::getInt('delete', \Xmf\Request::getInt('delete', 0, 'POST'), 'GET');
        DefinitionImport($delete);
        break;
    case 'main':
    default:
        FormImport();
        break;
}
