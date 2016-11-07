<?php
/**
 * $Id: popup.php v 1.0 27 May 2011 Yerres Exp $
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 18 Dec 2011
 * orig. Author: nagl@dictionary
 * Licence: GNU
 */

include("header.php");
include(XOOPS_ROOT_PATH."/header.php");

xoops_header(false);

$entryID = isset($_GET['entryID']) ? intval((int)$_GET['entryID']) : 0;
if (!$entryID){exit();}
//global $xoopsModuleConfig;
$myts = MyTextSanitizer::getInstance();

$sqlquery=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("lxentries")." WHERE entryID=$entryID");
$sqlfetch=$xoopsDB->fetchArray($sqlquery);
if ($xoopsModuleConfig['multicats'] == 1) {
    $cID = $sqlfetch['categoryID'];
    $sqlquery2=$xoopsDB->query("SELECT name FROM ".$xoopsDB->prefix("lxcategories")." WHERE categoryID = $cID");
    $sqlfetch2=$xoopsDB->fetchArray($sqlquery2);
    $catname = $myts->htmlSpecialChars($sqlfetch2['name']);
    }
$term = $myts->htmlSpecialChars($sqlfetch['term']);
$definition = $myts -> displayTarea( $sqlfetch['definition'], $sqlfetch['html'], $sqlfetch['smiley'], 1, 1, 1);

echo '</head><body>
	<table width="100%" class="outer">
	  <tr>
	     <th class="head">'.$term.'</th>
	  </tr>
	</table>';
if ($xoopsModuleConfig['multicats'] == 1) {
    echo '<div class="itemBody">'._MD_LEXIKON_ENTRYCATEGORY.''.$catname.'</div>';
    }
echo '<div class="itemBody"><p class="itemText">'.$definition.'</p></div>
	<div style="text-align:center;"><p><input value="'._CLOSE.'" type="button" onclick="window.close();"></p></div>';
