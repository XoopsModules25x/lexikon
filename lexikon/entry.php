<?php
/**
 * $Id: entry.php v 1.0 8 May 2004 hsalazar Exp $
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */

include( "header.php" );
$xoopsOption['template_main'] = 'lx_entry.html';
include_once( XOOPS_ROOT_PATH . "/header.php" );
global $xoTheme, $xoopsUser,$lexikon_module_header;
$myts =& MyTextSanitizer::getInstance();
xoops_load('XoopsUserUtility');

include_once XOOPS_ROOT_PATH.'/modules/lexikon/include/common.inc.php';
$highlight = lx_getmoduleoption('config_highlighter');
if ( $highlight ) include_once XOOPS_ROOT_PATH.'/modules/lexikon/class/keyhighlighter.class.php';

$entryID = isset($_GET['entryID']) ? intval($_GET['entryID']) : 0;
if (empty($entryID)) {
    redirect_header(XOOPS_URL.'/modules/lexikon/index.php',3,_MD_LEXIKON_UNKNOWNERROR);
    exit();
}
$entrytype = 1;
// permissions
$gperm_handler =& xoops_gethandler('groupperm');
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$module_id = $xoopsModule->getVar('mid');
$allowed_cats = $gperm_handler->getItemIds("lexikon_view", $groups, $module_id);
$catids = implode(',', $allowed_cats);
$catperms = " AND categoryID IN ($catids) ";

// If there's no entries yet in the system...
$publishedwords = lx_countWords();
$xoopsTpl->assign('publishedwords', $publishedwords);
if ( $publishedwords == 0 ) {
    $xoopsTpl -> assign ('empty', '1');
    $xoopsTpl -> assign ('stillnothing', _MD_LEXIKON_STILLNOTHINGHERE);
}

// To display the linked letter list
$alpha = lx_alphaArray();
$xoopsTpl->assign('alpha', $alpha);

// list($howmanyother) = $xoopsDB -> fetchRow($xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB -> prefix ( "lxentries") . " WHERE init = '#' AND offline ='0' ".$catperms."" ));
list($howmanyother) = $xoopsDB -> fetchRow($xoopsDB->query("SELECT COUNT(entryID) FROM " . $xoopsDB -> prefix ( "lxentries") . " WHERE init = '#' AND offline ='0' ".$catperms." " ));
$xoopsTpl->assign('totalother', $howmanyother);

$xoopsTpl->assign('multicats', intval($xoopsModuleConfig['multicats']));
// To display the list of categories
if ( $xoopsModuleConfig['multicats'] == 1 ) {
	$xoopsTpl -> assign ( 'block0', lx_CatsArray() );
	$xoopsTpl->assign('layout', CONFIG_CATEGORY_LAYOUT_PLAIN);
	if ( $xoopsModuleConfig['useshots'] == 1 ) {
		$xoopsTpl->assign('show_screenshot', true);
		$xoopsTpl->assign('logo_maximgwidth', $xoopsModuleConfig['logo_maximgwidth']);
		$xoopsTpl->assign('lang_noscreenshot', _MD_LEXIKON_NOSHOTS);
	} else {
		$xoopsTpl->assign('show_screenshot', false);
	}
}


if ( !$entryID ) {
    redirect_header("javascript:history.go(-1)" , 2, _MD_LEXIKON_UNKNOWNERROR ) ;
    exit();
} else {
    if ($entryID <= 0) {
        redirect_header('javascript:history.go(-1)', 2, _MD_LEXIKON_UNKNOWNERROR);
        exit();
    }
    if ( !$xoopsUser || ( $xoopsUser->isAdmin($xoopsModule->mid()) && $xoopsModuleConfig['adminhits'] == 1 ) || ( $xoopsUser && !$xoopsUser -> isAdmin( $xoopsModule -> mid() ) ) ) {
        $xoopsDB -> queryF( "UPDATE " . $xoopsDB -> prefix( "lxentries" ) . " SET counter = counter+1 WHERE entryID = $entryID " );
    }

    $result = $xoopsDB -> query( "SELECT entryID, categoryID, term, init, definition, ref, url, uid, submit, datesub, counter, html, smiley, xcodes, breaks, block, offline, notifypub
                                 FROM " . $xoopsDB -> prefix( "lxentries" ) . "
                                 WHERE entryID = $entryID" );
    // verify result
    if ($xoopsDB->getRowsNum($result) <= 0) {
        redirect_header('javascript:history.go(-1)', 2, _MD_LEXIKON_UNKNOWNERROR);
        exit();
    }
}

while (list( $entryID, $categoryID, $term, $init, $definition, $ref, $url, $uid, $submit, $datesub, $counter, $html, $smiley, $xcodes, $breaks, $block, $offline) = $xoopsDB->fetchRow($result)) {
	$catID = intval($categoryID);
    if (!$gperm_handler->checkRight('lexikon_view', intval($categoryID), $groups, $module_id)) {
        redirect_header('index.php', 3, _NOPERM);
        exit();
    }

    $thisterm = array();
    $xoopsModule = XoopsModule::getByDirname("lexikon");
    $thisterm['id'] = intval($entryID);
    $thisterm['offline'] = intval($offline);
    // exit if offline - except admin
    if ($thisterm['offline'] == 1 && !$xoopsUserIsAdmin) {
        redirect_header('javascript:history.go(-1)' , 3, _MD_LEXIKON_ENTRYISOFF ) ;
        exit();
    }
    if ($xoopsModuleConfig['multicats'] == 1) {
        $thisterm['categoryID'] = intval($categoryID);
        $catname = $xoopsDB -> query ( "SELECT name FROM " . $xoopsDB -> prefix ( "lxcategories" ) . " WHERE categoryID = $categoryID ");
        while (list ($name) = $xoopsDB -> fetchRow ( $catname )) {
            $thisterm['catname'] = $myts -> htmlSpecialChars( $name );
        }
    }

    $glossaryterm = $myts -> htmlSpecialChars( $term );
    $thisterm['term'] = ucfirst($myts -> htmlSpecialChars( $term ));
    if ($init == '#') {
        $thisterm['init'] = _MD_LEXIKON_OTHER;
    } else {
        $thisterm['init'] = ucfirst($init);
    }
    $thisterm['offline'] = intval($offline);

	if ($xoopsModuleConfig['linkterms'] !=1 && $xoopsModuleConfig['linkterms'] != 2 ) {
		lx_module_header();
		$xoopsTpl->assign('xoops_module_header', $lexikon_module_header);
	 } else {
		$xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="style.css" />');
	 }

    if ($xoopsModuleConfig['linkterms'] != 1) {
        // Code to make links out of glossary terms
        $parts = explode(">", $definition);

        // First, retrieve all terms from the glossary...
        $allterms = $xoopsDB -> query( "SELECT entryID, term, definition FROM " . $xoopsDB -> prefix( "lxentries" ) . " WHERE offline ='0' ".$catperms." " );

        while ( list( $entryID, $term, $definition ) = $xoopsDB -> fetchrow( $allterms )) {
            foreach($parts as $key=>$part) {
                if ( $term != $glossaryterm) {
                    $term_q = preg_quote($term, '/');
                    $search_term = "/\b$term_q\b/SsUi";
					//static link
					$staticURL = "".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/entry.php?entryID=".ucfirst($entryID)."";
          switch ($xoopsModuleConfig['linkterms']) {
						default:
							$replace_term = '<span><b><a style="cursor:help;border-bottom: 1px dotted #000;color: #2F5376;" href="'.$staticURL.'" >'.$term.'</a></b></span>';
							break;
						case 3: //tooltip
							$tooltipdef = $myts->htmlSpecialChars(xoops_substr(strip_tags($definition), 0, 150));
							$replace_term = '<a class="parser" href="'.$staticURL.'" onMouseover="ddrivetip(\''.$tooltipdef.'\', 300)"; onMouseout=\'hideddrivetip()\'>'.$term.'</a>';
							break;
						case 4://simple popup
							$replace_term = '<a style="cursor:help;border-bottom: 1px dotted #000;color: #2F5376;" href="#" onClick=\'popup("popup.php?entryID='.$entryID.'","details", 420, 350); return false\'>'.$term.'</a>';
							break;
						case 5:// balloon tooltip
							$tooltipdef = $myts->htmlSpecialChars(xoops_substr(strip_tags($definition),0,150));
							$replace_term = '<a class="parser" href="'.$staticURL.'" onMouseover="showToolTip(event,\''.$tooltipdef.'\');return false"; onMouseout=\'hideToolTip()\'>'.$term.'</a>';
							break;
						case 6:// shadow tooltip
							$tooltipdef = $myts->htmlSpecialChars(xoops_substr(strip_tags($definition),0,150));
							$replace_term = '<a class="parser" href="'.$staticURL.'" onmouseout="hideTooltip()" onmouseover="showTooltip(event,\''.$tooltipdef.'\')"; >'.$term.'</a>';
							break;
					}
                    $parts[$key] = preg_replace($search_term, $replace_term, $parts[$key]);
                }
            }
        }
        $definition = implode(">", $parts);
    }
    $thisterm['definition'] = $myts -> displayTarea( $definition, $html, $smiley, $xcodes, 1, $breaks );
    $thisterm['ref'] = $myts -> displayTarea( $ref, $html, $smiley, $xcodes, 1, $breaks );
    $thisterm['url'] = $myts -> makeClickable($url, $allowimage = 0);
    //$thisterm['submitter'] = XoopsUserUtility::getUnameFromId ( $uid );
    if ($xoopsModuleConfig['showsubmitter'] == 1 ) {
        $xoopsTpl -> assign('showsubmitter', true);
        if ($xoopsModuleConfig['authorprofile'] == 1 ) {
            $thisterm['submitter'] = lx_getLinkedProfileFromId ( $uid );
        } else {
            $thisterm['submitter'] = XoopsUserUtility::getUnameFromId ( $uid );
        }
    } else {
        $xoopsTpl -> assign('showsubmitter', false);
    }
    $thisterm['submit'] = intval($submit);
    $thisterm['datesub'] = formatTimestamp($datesub,$xoopsModuleConfig['dateformat']);
    $thisterm['counter'] = intval($counter);
    $thisterm['block'] = intval($block);
    $thisterm['dir'] = $xoopsModule->dirname();
    if ($highlight && isset($_GET['keywords'])) {
        $keywords = $myts -> htmlSpecialChars(trim(urldecode($_GET['keywords'])));
        $h= new lx_keyhighlighter ($keywords, true , 'lx_myhighlighter');
        $thisterm['definition'] = $h -> highlight($thisterm['definition']);
        $thisterm['ref'] = $h -> highlight( $thisterm['ref'] );
    }
}
//smartry strings
$xoopsTpl -> assign( 'thisterm', $thisterm );
$microlinks = lx_serviceLinks ( $thisterm );
$microlinksnew = lx_serviceLinksnew ( $thisterm );
$xoopsTpl -> assign ( 'microlinks', $microlinks );
$xoopsTpl -> assign ( 'microlinksnew', $microlinksnew );
$xoopsTpl -> assign ( 'lang_modulename', $xoopsModule->name() );
$xoopsTpl -> assign ( 'lang_moduledirname', $xoopsModule->getVar('dirname') );
$xoopsTpl -> assign ( 'entryID', $entryID);
$xoopsTpl -> assign ( 'submittedon', sprintf(_MD_LEXIKON_SUBMITTEDON,  $thisterm['datesub']) );
if ($xoopsModuleConfig['showsubmitter'] == 1 ) {
    $xoopsTpl -> assign ( 'submitter', sprintf(_MD_LEXIKON_SUBMITTEDBY, $thisterm['submitter']) );
}
$xoopsTpl -> assign ( 'counter', sprintf(_MD_LEXIKON_COUNT, $thisterm['counter']) );
$xoopsTpl -> assign ( 'entrytype', '1' );

// --- keywordshighligher ---
function lx_myhighlighter ($matches) {
    return '<span style="font-weight: bolder; background-color: #FFFF80;">' . $matches[0] . '</span>';
}

//--- Display tags of this term
#$itemid = $entryID;
$module_handler = xoops_gethandler('module');
$tagsModule = $module_handler->getByDirname("tag");
if (is_object($tagsModule)) {
    include_once XOOPS_ROOT_PATH."/modules/tag/include/tagbar.php";

    $itemid = isset($_GET['entryID']) ? intval($_GET['entryID']) : 0;
    $catid = 0; 
    //$xoopsTpl->assign('tagbar', tagBar($itemid, $catid = 0));
    $tagbar = tagBar($itemid, $catid);
    if($tagbar) {
        $xoopsTpl->assign('tagbar',$tagbar);
        $tagsmeta = implode(' ',$tagbar["tags"]);
    } else {
        $tagsmeta = '';
    }
}   else { 
    $xoopsTpl->assign('tagbar', false);
    $tagsmeta = '';
}

//--- linkterms assigns
// Balloontips
if ($xoopsModuleConfig['linkterms'] == 5 ) {
	$xoopsTpl->assign("balloontips", true);
} else {
	$xoopsTpl->assign("balloontips", false);
}

// Show Bookmark icons ?
switch ($xoopsModuleConfig['bookmarkme']) {
  case "0":
  default:
  $xoopsTpl->assign('bookmarkme', false);
  break;
  case "1":
    $xoopsTpl->assign('bookmarkme', 1);
    $xoopsTpl->assign('encoded_title', rawurlencode( $thisterm['term']));
  break;
  case "2":
  $xoopsTpl->assign('bookmarkme', 2);
  break;
  case "3":
  $xoopsTpl->assign('bookmarkme', 3);
  break;
}
// Meta data
$meta_description = xoops_substr( lx_html2text($thisterm['definition']), 0, 150 );
if ($xoopsModuleConfig['multicats'] == 1 ) {
    lx_create_pagetitle($thisterm['term']. ' - ' .$thisterm['catname']);
    lx_extract_keywords($myts->htmlSpecialChars($xoopsModule->name()).' ,'.$thisterm['term']. ' ,'.$thisterm['catname'].', '.$meta_description .', '.$tagsmeta);
    lx_get_metadescription($myts->htmlSpecialChars($xoopsModule->name()).' '.$thisterm['catname'].' '.$thisterm['term'].' '. $meta_description);
} else {
    lx_create_pagetitle($thisterm['term']);
    lx_extract_keywords($myts->htmlSpecialChars($xoopsModule->name()).' ,'.$thisterm['term'].', '.$meta_description .', '.$tagsmeta);
    lx_get_metadescription($myts->htmlSpecialChars($xoopsModule->name()).' '.$thisterm['term'].' '.$meta_description);
}
//Mondarse
include XOOPS_ROOT_PATH.'/include/comment_view.php';
//Mondarse
include_once XOOPS_ROOT_PATH.'/footer.php';
?>