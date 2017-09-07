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
// import script glossaire  -> Lexikon                                      //
// ------------------------------------------------------------------------ //
//////////////////////////////////////////////////////////////////////////////

use Xmf\Request;

require_once __DIR__ . '/admin_header.php';
$myts = MyTextSanitizer::getInstance();
$op   = '';

/****
 * Available operations
 ****/
switch ($op) {
    case 'default':
    default:
        xoops_cp_header();
        global $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsModule, $entryID;
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
    return preg_replace(["/'/i"], ["\'"], $text);
}

/**
 * @param $delete
 */
function DefinitionImport($delete)
{
    global $xoopsConfig, $xoopsDB, $xoopsModule;
    $sqlQuery = $xoopsDB->query('SELECT count(id) AS count FROM ' . $xoopsDB->prefix('glossaire'));
    list($count) = $xoopsDB->fetchRow($sqlQuery);
    if ($count < 1) {
        redirect_header('importwordbook.php', 1, _AM_LEXIKON_MODULEIMPORTEMPTY10);
    }

    //    $delete       = 0;
    $glocounter   = 0;
    $errorcounter = 0;

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
        $result3 = $xoopsDB->query('SELECT entryID FROM ' . $xoopsDB->prefix('lxentries') . ' ');
        //delete comments for each entry
        while (list($entryID) = $xoopsDB->fetchRow($result3)) {
            xoops_comment_delete($xoopsModule->getVar('mid'), $entryID);
        }
        $resultC = $xoopsDB->query('SELECT categoryID FROM ' . $xoopsDB->prefix('lxcategories') . ' ');
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
    $sqlQuery = $xoopsDB->query('SELECT id, lettre, nom, definition, affiche
                              FROM ' . $xoopsDB->prefix('glossaire'));
    $fecha    = time() - 1;
    while ($sqlfetch = $xoopsDB->fetchArray($sqlQuery)) {
        $glo               = [];
        $glo['id']         = $sqlfetch['id'];
        $glo['lettre']     = $sqlfetch['lettre'];
        $glo['nom']        = import2db($sqlfetch['nom']);
        $glo['definition'] = import2db($sqlfetch['definition']);
        $glo['affiche']    = ++$fecha;

        ++$glocounter;

        if ($delete) {
            $insert = $xoopsDB->queryF('
                                       INSERT INTO ' . $xoopsDB->prefix('lxentries') . "
                                       (entryID, init, term, definition, url, submit, datesub, offline, comments)
                                       VALUES ('" . $glo['id'] . '\',\'' . $glo['lettre'] . '\',\'' . $glo['nom'] . '\',\'' . $glo['definition'] . '\',\'\',\'\',\'' . $glo['affiche'] . '\',\'\',\'\')');
        } else {
            $insert = $xoopsDB->queryF('
                                       INSERT INTO ' . $xoopsDB->prefix('lxentries') . "
                                       (entryID, init, term, definition, url, submit, datesub, offline, comments)
                                       VALUES ('','" . $glo['lettre'] . '\',\'' . $glo['nom'] . '\',\'' . $glo['definition'] . '\',\'\',\'\',\'' . $glo['affiche'] . '\',\'\',\'\')');
        }
        if (!$insert) {
            ++$errorcounter;
            showerror('<br>Import term failed: <span style="color:red">entryID: ' . $glo['id'] . '</span>: ' . $glo['nom'] . ' ...');
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

    $sqlQuery = $xoopsDB->query('
                              SELECT mid
                              FROM ' . $xoopsDB->prefix('modules') . "
                              WHERE dirname = 'glossaire'");
    list($gloID) = $xoopsDB->fetchRow($sqlQuery);
    echo '<p>Glossaire Module ID: ' . $gloID . '</p>';
    echo '<p>Lexikon Module ID: ' . $xoopsModule->getVar('mid') . '<br>';

    $comentario = $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('xoopscomments') . "
                                   SET com_modid = '" . $xoopsModule->getVar('mid') . '\'
                                   WHERE  com_modid = \'' . $gloID . '\'');
    if (!$comentario) {
        showerror('Import comments failed:  ...');
    } else {
        showerror('Import comments :  ');
    }
    echo '<p>Update User Post count: O.K.!</p>';
    echo "<p><span style='color:red'>Incorrectly: " . $errorcounter . '</span></p>';
    echo '<p>Processed: ' . $glocounter . '<br>';
    echo '<H3>Import finished!</H3>';
    echo "<br><B><a href='index.php'>Back to Admin</a></B><p>";
    xoops_cp_footer();
}

/****
 * IMPORT FORM
 ****/
function FormImport()
{
    global $xoopsConfig, $xoopsDB, $xoopsModule;
    lx_importMenu(9);
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler   = xoops_getHandler('module');
    $glossaireModule = $moduleHandler->getByDirname('glossaire');
    $got_options     = false;
    if (is_object($glossaireModule)) {
        echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
        echo '<tr>';
        echo "<td colspan='2' class='bg3' align='left'><span style='font-size: x-small; '><b>" . _AM_LEXIKON_MODULEHEADIMPORTGLO . '</b></span></td>';
        echo '</tr>';

        echo '<tr>';
        echo "<td class='head' width = '200' align='center'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/assets/images/dialog-important.png' . '\' alt=\'\' hspace=\'0\' vspace=\'0\' align=\'middle\' style=\'margin-right: 10px; margin-top: 20px; \'></td>';
        echo "<td class='even' align='center'><br><B><span style='font-size: x-small; color: red'>" . _AM_LEXIKON_IMPORTWARN . '</span></B><P></td>';
        echo '</tr>';

        echo '<tr>';
        echo "<td class='head' width = '200' align='left'><span style='font-size: x-small; '>" . _AM_LEXIKON_IMPORTDELWB . '</span></td>';
        echo "<td class='even' align='center'><FORM ACTION='importglossaire.php?op=import' METHOD=POST>
        <input type='radio' name='delete' value='1'>&nbsp;" . _YES . "&nbsp;&nbsp;
        <input type='radio' name='delete' value='0' checked>&nbsp;" . _NO . '<b>
        </td>';
        echo "</tr><tr><td width = '200' class='head' align='center'>&nbsp;</td>";
        echo "<td class='even' align='center'>
        <input type='submit' name='button' id='import' value='" . _AM_LEXIKON_IMPORT . '\'>&nbsp;
        <input type=\'button\' name=\'cancel\' value=\'' . _CANCEL . '\' onclick=\'history.go(-1);\'></td>';
        echo "</tr></table><br>\n";
    } else {
        echo "<br><B><span style='color:red'>Module Glossaire not found on this site.</span></B><br><A HREF='index.php'>Back</A><P>";
    }
    xoops_cp_footer();
}

global $op;
$op = Request::getCmd('op', '');
switch ($op) {
    case 'import':
        $delete = isset($_GET['delete']) ? (int)$_GET['delete'] : (int)$_POST['delete'];
        DefinitionImport($delete);
        break;
    default:
        FormImport();
        break;
}
