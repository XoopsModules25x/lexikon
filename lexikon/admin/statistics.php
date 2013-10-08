<?php
/**
 * $Id: statistics.php v 1.0 8 JUN 2011 Yerres Exp $
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 18 Dec 2011
 * Author: Yerres
 * adapted from news
 * Licence: GNU
 */

include( "admin_header.php" );
$myts =& MyTextSanitizer::getInstance();
if (!isset($op)) {
    $op = '';
}

/**
 * Statistics about the Glossary: Definitions, Categories, views and authors
 *
 * You can reach the statistics from the admin part of the news module by clicking on the "Statistics" tabs
 * The number of visible elements in each table is equal to the module's option called "perpage"
 * There are three kind of different statistics :
 * - Categories statistics
 *   For each Category you can see its number of definitions, the number of time each Definition was viewed, 
 *   the number of unused i.e. offline or submitted Definitions and the number of unique authors.
 * - Definitions statistics
 *   This part consists of 2 tables :
 *   a) Most read definitions
 *      This table resumes, for all the terms in your database, the most read Definitions.
 *      The table contains, for each term, its Category, name, author and the number of views.
 *   b) Less read Definitions
 *      That's the opposite action of the previous table and its content is the same
 * - Authors statistics
 *   This part also consists of 2 tables
 *   a) Most read authors
 *		To create this table, the program computes the total number of reads per author and displays the most read author and the number of views
 *   b) Biggest contributors
 *      The goal of this table is to know who is creating the biggest number of terms.
 **/

function lx_Statistics(){
    global $xoopsModule, $xoopsConfig, $xoopsModuleConfig;
    xoops_cp_header();
    $myts =& MyTextSanitizer::getInstance();
   
//    lx_adminMenu(3, _INFO);
    
    $stats = array();
    //$stats=lx_GetStatistics(10);
     $stats=lx_GetStatistics($xoopsModuleConfig['perpage']);
	$totals=array(0,0,0,0);

//   printf("<h1>%s</h1>\n",_AM_LEXIKON_STATS);
    $indexAdmin = new ModuleAdmin();
    echo $indexAdmin->addNavigation('statistics.php');
   // First part of the stats, everything about categories
	$termspercategory=$stats['termspercategory'];
	$readspercategory=$stats['readspercategory'];
	$offlinepercategory=$stats['offlinepercategory'];
	$authorspercategory=$stats['authorspercategory'];
	$class='';

	echo "<div style='text-align: center;'><b>" . _AM_LEXIKON_STATS0 . "</b><br />\n";
	echo "<table width='100%' style=\"margin-top: 6px; clear:both;\" cellspacing='2' cellpadding='3' border='0' >";
	echo "<tr class='bg3'><td align='center'>"._AM_LEXIKON_ENTRYCATNAME."</td><td align='center'>" . _AM_LEXIKON_TOTALENTRIES . "</td><td>" . _READS . "</td><td>" . _AM_LEXIKON_STATS6 ."</td><td>" ._AM_LEXIKON_STATS1 ."</td></tr>";
	
	foreach ( $termspercategory as $categoryID => $data ) {
		$url=XOOPS_URL . '/modules/' . $xoopsModule -> dirname() . '/category.php?categoryID=' . $categoryID;
		$views=0;
		if(array_key_exists($categoryID,$readspercategory)) {
			$views=$readspercategory[$categoryID];
		}
		$offline=0;
		if(array_key_exists($categoryID,$offlinepercategory)) {
			$offline=$offlinepercategory[$categoryID];
		}
		$authors=0;
		if(array_key_exists($categoryID,$authorspercategory)) {
			$authors=$authorspercategory[$categoryID];
		}
		$terms=$data['cpt'];

        $totals[0]+=$terms;
        $totals[1]+=$views;
        $totals[2]+=$offline;
        $class = ($class == 'even') ? 'odd' : 'even';
		printf("<tr class='".$class."'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='right'>%u</td><td align='right'>%u</td><td align='right'>%u</td><td align='right'>%u</td></tr>\n",$url,$myts->displayTarea($data['name']),$terms,$views,$offline,$authors);
	}
	$class = ($class == 'even') ? 'odd' : 'even';
	printf("<tr class='".$class."'><td align='center'><b>%s</b></td><td align='right'><b>%u</b></td><td align='right'><b>%u</b></td><td align='right'><b>%u</b></td><td>&nbsp;</td>\n",_AM_LEXIKON_STATS2,$totals[0],$totals[1],$totals[2]);
	echo '</table></div><br /><br /><br />';

	// Second part of the stats, everything about reads
	// a) Most read definitions
	$mostreadterms=$stats['mostreadterms'];
	
	echo "<div style='text-align: center;'><b>" . _AM_LEXIKON_STATS3 . '</b><br /><br />' . _AM_LEXIKON_STATS4 . "<br />\n";
	echo "<table width='100%' style=\"margin-top: 6px; clear:both;\" cellspacing='2' cellpadding='3' border='0' >
	<tr class='bg3'><td align='center'>"._AM_LEXIKON_ENTRYCATNAME."</td><td align='center'>" . _AM_LEXIKON_ENTRYTERM . "</td><td>" . _AM_LEXIKON_AUTHOR . "</td><td>" . _READS . "</td></tr>\n";
	foreach ( $mostreadterms as $entryID => $data ) {
		$url1=XOOPS_URL . '/modules/' . $xoopsModule -> dirname() . '/category.php?categoryID=' . $data['categoryID'];
		$url2=XOOPS_URL . '/modules/' . $xoopsModule -> dirname() . '/entry.php?entryID=' . $entryID;
		$sentby = XoopsUserUtility::getUnameFromId($data['uid']);
		$class = ($class == 'even') ? 'odd' : 'even';
		printf("<tr class='".$class."'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='left'><a href='%s' target='_blank'>%s</a></td><td>%s</td><td align='right'>%u</td></tr>\n",$url1,$myts->displayTarea($data['name']),$url2,$myts->displayTarea($data['term']),$sentby,$data['counter']);
	}
	echo '</table>';

	// b) Less read definitions
	$lessreadnews=$stats['lessreadterms'];
	echo '<br /><br />'._AM_LEXIKON_STATS5;
	echo "<table width='100%' style=\"margin-top: 6px; clear:both;\" cellspacing='2' cellpadding='3' border='0' >
	<tr class='bg3'><td align='center'>"._AM_LEXIKON_ENTRYCATNAME."</td><td align='center'>" . _AM_LEXIKON_ENTRYTERM . "</td><td>" . _AM_LEXIKON_AUTHOR . "</td><td>" . _READS . "</td></tr>\n";
	foreach ( $lessreadnews as $entryID => $data ) {
		$url1=XOOPS_URL . '/modules/' . $xoopsModule -> dirname() . '/category.php?categoryID=' . $data['categoryID'];
		$url2=XOOPS_URL . '/modules/' . $xoopsModule -> dirname() . '/entry.php?entryID=' . $entryID;
		$sentby = XoopsUserUtility::getUnameFromId($data['uid']);
		$class = ($class == 'even') ? 'odd' : 'even';
		printf("<tr class='".$class."'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='left'><a href='%s' target='_blank'>%s</a></td><td>%s</td><td align='right'>%u</td></tr>\n",$url1,$myts->displayTarea($data['name']),$url2,$myts->displayTarea($data['term']),$sentby,$data['counter']);
	}
	echo '</table>';
	echo '<br /><br /><br />';

	// Last part of the stats, everything about authors
	// a) Most read authors
	$mostreadauthors=$stats['mostreadauthors'];
	echo "<div style='text-align: center;'><b>" . _AM_LEXIKON_STATS10 . '</b><br /><br />' . _AM_LEXIKON_STATS7 . "<br />\n";
	echo "<table width='100%' style=\"margin-top: 6px; clear:both;\" cellspacing='2' cellpadding='3' border='0' >
	<tr class='bg3'><td>"._AM_LEXIKON_AUTHOR.'</td><td>' . _READS . "</td></tr>\n";
	foreach ( $mostreadauthors as $uid => $reads) {
		$sentby = XoopsUserUtility::getUnameFromId($uid);
		$class = ($class == 'even') ? 'odd' : 'even';
		printf("<tr class='".$class."'><td align='left'>%s</td><td align='right'>%u</td></tr>\n",$sentby,$reads);
	}
	echo '</table>';


	// c) Biggest contributors
	$biggestcontributors=$stats['biggestcontributors'];
	echo '<br /><br />'._AM_LEXIKON_STATS9;
	echo "<table width='100%' style=\"margin-top: 6px; clear:both;\" cellspacing='2' cellpadding='3' border='0' >
	<tr class='bg3'><td>"._AM_LEXIKON_AUTHOR."</td><td>" . _AM_LEXIKON_STATS11 . "</td></tr>\n";
	foreach ( $biggestcontributors as $uid => $count) {
		$url=XOOPS_URL . '/userinfo.php?uid=' . $uid;
		$sentby = XoopsUserUtility::getUnameFromId($uid);
		$class = ($class == 'even') ? 'odd' : 'even';
		printf("<tr class='".$class."'><td align='left'>%s</td><td align='right'>%u</td></tr>\n",$sentby,$count);
	}
	echo '</table></div><br />';
}


/* -- Available operations -- */
$op = isset($_GET['op']) ? $_GET['op'] : (isset($_POST['op']) ? $_POST['op'] : '');
switch ( $op ) {

default:
    lx_Statistics();
    break;
}

xoops_cp_footer();

?>