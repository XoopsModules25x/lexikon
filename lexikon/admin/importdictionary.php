<?php
//////////////////////////////////////////////////////////////////////////////
// $Id: importdictionary.php,v 1.2 18/03/2011 17:21:00 Yerres Exp $         //
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
// import script dictionary  -> Lexikon                                     //
// ------------------------------------------------------------------------ //
//////////////////////////////////////////////////////////////////////////////

include("admin_header.php");
$op = '';

/****
 * Available operations
 ****/
switch ( $op ) {
case "default":
    default:
    xoops_cp_header();
    global $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsModule;
    $myts =& MyTextSanitizer::getInstance();
//    lx_adminMenu(9, _AM_LEXIKON_IMPORT);
}
/****
 * Start Import
 ****/
function showerror($msg) {
    global $xoopsDB;
    if ($xoopsDB->error()!='') {
        echo "<br>".$msg . " <BR><font size=1> -  ERROR: ".$xoopsDB->error()."</font>.";
    } else {
        echo "<br>".$msg .' O.K.!';
    }
}
function import2db($text) {
    return preg_replace(array("/'/i"), array("\'"), $text);
}

function DefinitionImport($delete) {
    global $xoopsConfig, $xoopsDB, $xoopsModule, $myts;
    $sqlquery = $xoopsDB->query("SELECT count(id) as count FROM ".$xoopsDB->prefix("dictionary"));
    list( $count ) = $xoopsDB->fetchRow( $sqlquery ) ;
    if ( $count < 1 ) {
        redirect_header("index.php",1,"Database for import missing or empty!");
        exit();
    }

    $delete = 0 ;
    $glocounter = 0;
    $errorcounter = 0;

    if (isset($delete)) {
        $delete=intval($_POST['delete']);
    } else {
        if (isset($delete)) {
            $delete=intval($_POST['delete']);
        }
    }

    /****
     * delete all entries and categories
     ****/
    if ($delete) {
        // delete notifications
        xoops_notification_deletebymodule($xoopsModule->getVar('mid'));
        //get all entries
        $result3=$xoopsDB->query("select entryID from ".$xoopsDB->prefix("lxentries")."");
        //now for each entry, delete the coments
        while ( list($entryID)=$xoopsDB->fetchRow($result3) ) {
            xoops_comment_delete( $xoopsModule->getVar('mid'), $entryID);
        }
        $resultC=$xoopsDB->query("SELECT categoryID FROM ".$xoopsDB->prefix("lxcategories")."");
        while ( list($categoryID)=$xoopsDB->fetchRow($resultC) ) {
            // delete permissions
            xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'lexikon_view', $categoryID);
            xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'lexikon_submit', $categoryID);
            xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'lexikon_approve', $categoryID);
            xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'lexikon_request', $categoryID);
        }
        // delete everything
        $sqlquery1=$xoopsDB->queryF("TRUNCATE TABLE ".$xoopsDB->prefix("lxentries"));
        $sqlquery2=$xoopsDB->queryF("TRUNCATE TABLE ".$xoopsDB->prefix("lxcategories"));
    }

    /****
     * Import ENTRIES
     ****/

    $sql1 = $xoopsDB -> query("
                              SELECT *
                              FROM ".$xoopsDB->prefix("dictionary")." ");

    $result1 = $xoopsDB -> getRowsNum( $sql1 );
    if ($result1) {
        $fecha = time()-1;
        while ($row2 = $xoopsDB->fetchArray( $sql1 )) {
            $entryID     = intval($row2['id']);
            $init        = $myts -> addSlashes($row2['letter']);
            $term        = $myts -> addSlashes(import2db($row2['name']));
            $definition  = $myts -> addSlashes(import2db($row2['definition']));
            $datesub     = $fecha++;
            $estado      = import2db($row2['state']);
            if ($estado  == 'O') {
                $row2['state']  = 0;
            } else {
                $row2['state'] = 1;
            }
            if ($estado  == 'D') {
                $row2['submit'] = 1 && $row2['state'] = 1;
            } else {
                $row2['submit'] = 0;
            }
            $comments    = intval($row2['comments']);
            $glocounter  = $glocounter + 1;

            if ( $delete ) {
                $ret1 = $xoopsDB->queryF("
                                         INSERT INTO ".$xoopsDB->prefix("lxentries")."
                                         (entryID, init, term, definition, url,  submit, datesub, offline, comments)
                                         VALUES
                                         ('$entryID', '$init', '$term', '$definition', '', '".$row2['submit']."', '$datesub', '".$row2['state']."', '$comments' )");
            } else {
                $ret1 = $xoopsDB->queryF("
                                         INSERT INTO ".$xoopsDB->prefix("lxentries")."
                                         (entryID, init, term, definition, url, submit, datesub, offline, comments)
                                         VALUES
                                         ('', '$init', '$term', '$definition', '', '".$row2['submit']."', '$datesub', '".$row2['state']."',  '$comments' )");
            }
            if (!$ret1) {
                $errorcounter = $errorcounter + 1;
                showerror('<BR>Import term failed: <font color=red>entryID: '.$entryID.'</font>: '.$term.' ...');
            }
            // update user posts count
            if ($ret1) {
                if ($uid) {
                    $member_handler = &xoops_gethandler('member');
                    $submitter =& $member_handler -> getUser($uid);
                    if (is_object($submitter) ) {
                        $submitter -> setVar('posts',$submitter -> getVar('posts') + 1);
                        $res=$member_handler -> insertUser($submitter, true);
                        unset($submitter);
                    }
                }
            }
        }
    }
    /****
     * FINISH
     ****/

    $sqlquery=$xoopsDB->query("
                              SELECT mid from ".$xoopsDB->prefix("modules")."
                              WHERE dirname = 'dictionary'");
    list( $dicID ) = $xoopsDB->fetchRow( $sqlquery ) ;
    echo "<p>Dictionary Module ID: ".$dicID."</p>";
    echo "<p>Lexikon Module ID: ".$xoopsModule->getVar('mid')."<br/>";
    //echo "<p>delete is on/off: ".$delete."</p>";

    $commentaire = $xoopsDB->queryF("
                                    UPDATE ".$xoopsDB->prefix("xoopscomments")."
                                    SET com_modid = '".$xoopsModule->getVar('mid')."'
                                    WHERE  com_modid = '".$dicID."'");
    if (!$commentaire) {
        showerror('Import comments failed:  ...');
    } else {
        showerror('Import comments :  ');
    }
    echo "<p>Update User Post count: O.K.!</p>";
    echo "<p><font color='red'>Incorrectly: ".$errorcounter."</font></p>";
    echo "<p>Processed: ".$glocounter."</p>";
    echo "<H3>Import finished!</H3>";
    echo "<br /><B><a href='index.php'>Back to Admin</a></B><p>";
    xoops_cp_footer();
}

/****
 * IMPORT FORM PLAIN HTML
 ****/

function FormImport() {
    global $xoopsConfig, $xoopsDB, $xoopsModule;
    lx_importMenu(9);
    $module_handler = xoops_gethandler('module');
    $dictionaryModule = $module_handler->getByDirname("dictionary");
    $got_options = false;
    if (is_object($dictionaryModule)) {
        echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
        echo "<tr>";
        echo "<td colspan='2' class='bg3' align='left'><FONT size='2'><b>"._AM_LEXIKON_MODULEHEADIMPORT."</b></FONT></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td class='head' width = '200' align='center'><img src='".XOOPS_URL."/modules/".$xoopsModule->dirname(). "/images/dialog-important.png"."' alt='' hspace='0' vspace='0' align='middle' style='margin-right: 10px;  margin-top: 20px;'></td>";
        echo "<td class='even' align='center'><BR><B><font size=2 color='red'>"._AM_LEXIKON_IMPORTWARN."</font></B><P></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td class='head' width = '200' align='left'><font size=2>"._AM_LEXIKON_IMPORTDELWB."</FONT></td>";
        echo "<td class='even' align='center'><FORM ACTION='importdictionary.php?op=import' METHOD=POST>
        <input type='radio' name='delete' value='1'>&nbsp;"._YES."&nbsp;&nbsp;
        <input type='radio' name='delete' value='0' checked='checked'>&nbsp;"._NO."<BR />
        </td>";
        echo "</tr><tr><td width = '200' class='head' align='center'>&nbsp;</TD>";
        echo "<td class='even' align='center'>
        <input type='submit' name='button' id='import' value='"._AM_LEXIKON_IMPORT."'>&nbsp;
        <input type='button' name='cancel' value='"._CANCEL."' onclick='javascript:history.go(-1);'></td>";
        echo "</TR></table><br />\n";
    } else {
        echo "<BR><B><font color='red'>Module Dictionary not found on this site.</font></B><BR><A HREF='index.php'>Back</A><P>";
    }
    xoops_cp_footer();
}

$op = isset($_GET['op']) ? $_GET['op'] : (isset($_POST['op']) ? $_POST['op'] : '');
switch ($op) {
case "import":
    $delete = ( isset( $_GET['delete'] ) ) ? intval($_GET['delete']) : intval($_POST['delete']);
    DefinitionImport($delete);
    break;
case 'main':
default:
    FormImport();
    break;
}
