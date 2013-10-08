<?php
/**
 * Lexikon XOOPS glossary Module
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Yerres
 * @since	    0.5
 * @version	    $Id$
 * @package     module::lexikon
 * @credits     hsalazar, catzwolf, Dario Garcia and many others
 */

if( ! defined( 'XOOPS_ROOT_PATH' ) ) die( 'XOOPS root path not defined' ) ;

$modversion['name'] = _MI_LEXIKON_MD_NAME;
$modversion['version'] = "1.51";
$modversion['description'] = _MI_LEXIKON_MD_DESC;
$modversion['author'] = "Yerres";
$modversion['credits'] = "hsalazar, Mondarse, Catzwolf, and many more";
$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0 or later';
$modversion['license_url'] = "www.gnu.org/licenses/gpl-2.0.html";
$modversion['official'] = 0;
$modversion['image'] = "images/lx_slogo.png";
$modversion['dirname'] = "lexikon";
$modversion['onInstall'] = 'include/install_function.php';

$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
$modversion['icons16']        = '../../Frameworks/moduleclasses/icons/16';
$modversion['icons32']        = '../../Frameworks/moduleclasses/icons/32';

$modversion["license_file"] = XOOPS_URL."/modules/lexikon/gpl.txt";
$modversion['license_url'] = "www.gnu.org/licenses/gpl-2.0.html";
$modversion['status_version'] = '1.5';
$modversion["module_status"] = "beta";
$modversion["release"] = "2012-05-10";
$modversion['last_update'] = '2012/05/10'; 

$modversion['release_date']        = '2013/02/27';
$modversion["module_website_url"]  = "www.xoops.org";
$modversion["module_website_name"] = "XOOPS";
$modversion["module_status"]       = "Beta 2";
$modversion['min_php']             = '5.2';
$modversion['min_xoops']           = "2.5.6";
$modversion['min_admin']           = '1.1';
$modversion['min_db']              = array(
    'mysql'  => '5.0.7',
    'mysqli' => '5.0.7'
);


$modversion["author_word"] = "-";
$modversion["module_website_url"] = "http://www.xoops.org/";
$modversion["module_website_name"] = "XOOPS";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Sql
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion["tables"] = array(
	"lxcategories",
	"lxentries",
);

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "lx_search";

// Menu
$modversion['hasMain'] = 1;
$modversion['system_menu'] = 1;

// Use smarty
$modversion["use_smarty"] = 1;

global $xoopsUser, $xoopsDB,  $xoopsModuleConfig;
$module_handler = &xoops_gethandler('module');
$lexikon =& $module_handler->getByDirname($modversion['dirname']);
if ($lexikon) {
	if (!isset($lxConfig)) {
			$config_handler = &xoops_gethandler('config');
			$lxConfig = &$config_handler->getConfigsByCat(0, $lexikon->getVar('mid'));
	}
}
$i = 0;
if ( is_object($xoopsUser) ) {
    if ( $xoopsUser->isAdmin() ) {
        $modversion['sub'][$i]['name'] = constant("_MI_LEXIKON_SUB_SMNAME0");
        $modversion['sub'][$i]['url'] = "admin/index.php";
        $i++;
        $modversion['sub'][$i]['name'] = constant("_MI_LEXIKON_SUB_SMNAME4");
        $modversion['sub'][$i]['url'] = "admin/entry.php?op=add";
        $i++;
    }
}
#if ( isset($xoopsModuleConfig['authorprofile']) && $xoopsModuleConfig['authorprofile'] == 1 ) {
if ( isset($lxConfig['authorprofile']) && $lxConfig['authorprofile'] == 1 ) {
    $modversion['sub'][$i]['name'] = _MI_LEXIKON_SUB_SMNAME6;
    $modversion['sub'][$i]['url'] = "authorlist.php";
    $i++;
}
//if ( $xoopsUser || isset($xoopsModuleConfig['contentsyndication']) && $xoopsModuleConfig['contentsyndication'] == 1 ) {
if (isset($lxConfig['contentsyndication']) && $lxConfig["contentsyndication"] == 1) {
    $modversion['sub'][$i]['name'] = _MI_LEXIKON_SUB_SMNAME7;
    $modversion['sub'][$i]['url'] = "content.php";
    $i++;
}
if ($lexikon) {
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gperm_handler =& xoops_gethandler('groupperm');
    if ($gperm_handler->checkRight("lexikon_submit", 0, $groups, $lexikon->getVar('mid'))) {
		$modversion['sub'][$i]['name'] = _MI_LEXIKON_SUB_SMNAME1;
		$modversion['sub'][$i]['url'] = "submit.php";
		$i++;
	}
	
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gperm_handler =& xoops_gethandler('groupperm');
    if ($gperm_handler->checkRight("lexikon_request", 0, $groups, $lexikon->getVar('mid'))) {
		$modversion['sub'][$i]['name'] = constant("_MI_LEXIKON_SUB_SMNAME2");
		$modversion['sub'][$i]['url'] = "request.php";
		$i++;
	}
}
$modversion['sub'][$i]['name'] = constant("_MI_LEXIKON_SUB_SMNAME3");
$modversion['sub'][$i]['url'] = "search.php";
$i++;
//if ( isset($xoopsModuleConfig['catsinmenu']) && $xoopsModuleConfig['catsinmenu'] == 1 && $xoopsModuleConfig['multicats']) {
#if (isset($xoopsModuleConfig['catsinmenu']) && $xoopsModuleConfig['catsinmenu'] == 1 && isset($xoopsModuleConfig['multicats']) && $xoopsModuleConfig["multicats"] == 1) {
if (isset($lxConfig['catsinmenu']) && $lxConfig['catsinmenu'] == 1 && isset($lxConfig['multicats']) && $lxConfig["multicats"] == 1) {
    $myts = & MyTextSanitizer ::getInstance();
    $sql = $xoopsDB->query( "SELECT categoryID, name FROM " . $xoopsDB->prefix( "lxcategories" ) . " ORDER BY weight ASC" );
    while ( list( $categoryID, $name ) = $xoopsDB->fetchRow( $sql ) ) {
		if ($gperm_handler->checkRight('lexikon_view', $categoryID, $groups, $lexikon->getVar('mid'))) {
			$name = $myts->htmlSpecialChars($name);
			$categoryID = intval($categoryID);
			$modversion['sub'][$i]['name'] = $name;
			$modversion['sub'][$i]['url'] = "category.php?categoryID=" . $categoryID;
			$i++;
		}
    }
}

// blocks
$modversion['blocks']	= array();
$modversion["blocks"][1]	= array(
	"file"			=> "entries_new.php",
	"name"			=> _MI_LEXIKON_ENTRIESNEW,
	"description"	=> "Shows latest terms",
	"show_func"		=> "b_lxentries_new_show",
	"edit_func"		=> "b_lxentries_new_edit",
	"options"		=> "datesub|5|0|0|1|up||",
	"template"		=> "entries_new.html",
	"can_clone"		=> true,
	);
$modversion["blocks"][]	= array(
	"file"			=> "entries_top.php",
	"name"			=> _MI_LEXIKON_ENTRIESTOP,
	"description"	=> "Shows popular terms",
	"show_func"		=> "b_lxentries_top_show",
	"edit_func"		=> "b_lxentries_top_edit",
	"options"		=> "counter|5|0|0|1|up||",
	"template"		=> "entries_top.html",
	"can_clone"		=> true,
	);
$modversion["blocks"][]	= array(
	"file"			=> "random_term.php",
	"name"			=>  _MI_LEXIKON_RANDOMTERM,
	"description"	=> "Shows a random term",
	"show_func"		=> "b_lxentries_random_show",
	"template"		=> "entries_random.html",
	"can_clone"		=> true,
	);
$modversion["blocks"][]	= array(
	"file"			=> "entries_initial.php",
	"name"			=> _MI_LEXIKON_TERMINITIAL,
	"description"	=> "Shows alphabet",
	"show_func"		=> "b_lxentries_alpha_show",
	"edit_func"		=> "b_lxentries_alpha_edit",
	"options"		=> "1|8",
	"template"		=> "entries_initial.html",
	"can_clone"		=> true,
	);
$modversion["blocks"][]	= array(
	"file"			=> "categories_block.php",
	"name"			=> _MI_LEXIKON_CATS,
	"description"	=> "Shows categories",
	"show_func"		=> "b_lxcategories_show",
	"edit_func"		=> "b_lxcategories_edit",
	"options"		=> "weight|5",
	"template"		=> "categories_block.html",
	"can_clone"		=> true,
	);
$modversion["blocks"][]	= array(
	"file"			=> "entries_spot.php",
	"name"			=> _MI_LEXIKON_SPOT,
	"description"	=> "Shows spotlight terms in a category",
	"show_func"		=> "b_lxspot_show",
	"edit_func"		=> "b_lxspot_edit",
	"options"		=> "1|5|0|0|0|ver|0|datesub|65|25",
	"template"		=> "entries_spot.html",
	"can_clone"		=> true,
	);
$modversion["blocks"][]	= array(
	"file"			=> "entries_authors.php",
	"name"			=> _MI_LEXIKON_BNAME8,
	"description"	=> "Shows top authors",
	"show_func"		=> "b_lx_author_show",
	"edit_func"		=> "b_lx_author_edit",
	"options"		=> "count|5|uname|total",
	"template"		=> "entries_authors.html",
	"can_clone"		=> true,
	);
$modversion["blocks"][]	= array(
	"file"			=> "entries_scrolling.php",
	"name"			=> _MI_LEXIKON_BNAME9,
	"description"	=> "Shows scrolling definitions",
	"show_func"		=> "b_scrolling_term_show",
	"edit_func"		=> "b_scrolling_term_edit",
	"options"		=> "5|2||up|0|200|1|1|RAND()|DESC|1",
	"template"		=> "entries_scrolling.html",
	"can_clone"		=> true,
	);
/*
 * $options:  
 *					$options[0] - number of tags to display
 *					$options[1] - time duration, in days, 0 for all the time
 *					$options[2] - max font size (px or %)
 *					$options[3] - min font size (px or %)
 */

$modversion["blocks"][]	= array(
	"file"			=> "lexikon_block_tag.php",
	"name"			=> "Lexikon Tag Cloud",
	"description"	=> "Show tag cloud",
	"show_func"		=> "lexikon_tag_block_cloud_show",
	"edit_func"		=> "lexikon_tag_block_cloud_edit",
	"options"		=> "100|0|150|80",
	"template"		=> "lexikon_tag_block_cloud.html",
	"can_clone"		=> true,
	);
/*
 * $options:  
 *					$options[0] - number of tags to display
 *					$options[1] - time duration, in days, 0 for all the time
 *					$options[2] - sort: a - alphabet; c - count; t - time
 */
$modversion["blocks"][]	= array(
	"file"			=> "lexikon_block_tag.php",
	"name"			=> "Lexikon Top Tags",
	"description"	=> "Show top tags",
	"show_func"		=> "lexikon_tag_block_top_show",
	"edit_func"		=> "lexikon_tag_block_top_edit",
	"options"		=> "50|30|t",
	"template"		=> "lexikon_tag_block_top.html",
	"can_clone"		=> true,
	);
	
// Templates
$modversion['templates']	= array();
$modversion['templates'][1]	= array(
	'file'			=> 'lx_category.html',
	'description'	=> 'Display categories'
	);
$modversion['templates'][]	= array(
	'file'			=> 'lx_index.html',
	'description'	=> 'Display index'
	);	
$modversion['templates'][]	= array(
	'file'			=> 'lx_entry.html',
	'description'	=> 'Display term'
	);
$modversion['templates'][]	= array(
	'file'			=> 'lx_letter.html',
	'description'	=> 'Display letter'
	);
$modversion['templates'][]	= array(
	'file'			=> 'lx_search.html',
	'description'	=> 'search glossary'
	);
$modversion['templates'][]	= array(
	'file'			=> 'lx_request.html',
	'description'	=> 'Request a definition'
	);
$modversion['templates'][]	= array(
	'file'			=> 'lx_submit.html',
	'description'	=> 'Submit a definition'
	);
$modversion['templates'][]	= array(
	'file'			=> 'lx_adminmenu.html',
	'description'	=> '(Admin) Tabs bar for administration pages'
	);
$modversion['templates'][]	= array(
	'file'			=> 'lx_bookmark.html',
	'description'	=> 'Social Bookmarking tags'
	);
$modversion['templates'][]	= array(
	'file'			=> 'lexikon_rss.html',
	'description'	=> 'Display Lexikon rss feed'
	);
$modversion['templates'][]	= array(
	'file'			=> 'lx_profile.html',
	'description'	=> 'Glossary-profile of authors'
	);
$modversion['templates'][]	= array(
	'file'			=> 'lx_authorlist.html',
	'description'	=> 'Glossary author List'
	);
$modversion['templates'][]	= array(
	'file'			=> 'lx_content.html',
	'description'	=> 'content syndication dispatcher'
	);
$modversion['templates'][]	= array(
	'file'			=> 'lx_syndication.html',
	'description'	=> 'Webmaster content syndication'
	);
$modversion['templates'][]	= array(
	'file'			=> 'lx_topten.html',
	'description'	=> 'TOP10 Definitions'
	);
$modversion['templates'][]	= array(
	'file'			=> 'lx_tag_bar.html',
	'description'	=> 'Lexikon Definition Tagbar'
	);

// Config Settings
$modversion["config"] = array();

$modversion['config'][1] = array(
	'name' 			=> 'multicats',
	'title' 		=> '_MI_LEXIKON_MULTICATS',
	'description' 	=> '_MI_LEXIKON_MULTICATSDSC',
	'formtype' 		=> 'yesno',
	'valuetype' 	=> 'int',
	'default' 		=> 1);

$modversion['config'][] = array(
	'name' 			=> 'catsinmenu',
	'title' 		=> '_MI_LEXIKON_CATSINMENU',
	'description' 	=> '_MI_LEXIKON_CATSINMENUDSC',
	'formtype' 		=> 'yesno',
	'valuetype' 	=> 'int',
	'default' 		=> 0);
	
$modversion['config'][] = array(
	'name' 			=> 'dateformat',
	'title' 		=> '_MI_LEXIKON_DATEFORMAT',
	'description' 	=> '_MI_LEXIKON_DATEFORMATDSC',
	'formtype' 		=> 'textbox',
	'valuetype' 	=> 'text',
	'default' 		=> 'd.m.Y H:i');

$modversion['config'][] = array(
	'name' 			=> 'perpage',
	'title' 		=> '_MI_LEXIKON_PERPAGE',
	'description' 	=> '_MI_LEXIKON_PERPAGEDSC',
	'formtype' 		=> 'select',
	'valuetype' 	=> 'int',
	'default' 		=> 20,
	'options' => array( '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30, '50' => 50 ));

$modversion['config'][] = array(
	'name' 			=> 'indexperpage',
	'title' 		=> '_MI_LEXIKON_PERPAGEINDEX',
	'description' 	=> '_MI_LEXIKON_PERPAGEINDEXDSC',
	'formtype' 		=> 'select',
	'valuetype' 	=> 'int',
	'default' 		=> 10,
	'options' 		=> array( '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30, '50' => 50 ));

$modversion['config'][] = array(
	'name' 			=> 'blocksperpage',
	'title' 		=> "_MI_LEXIKON_BLOCKSPERPAGE",
	'description' 	=> "_MI_LEXIKON_BLOCKSPERPAGEDSC",
	'formtype' 		=> 'select',
	'valuetype' 	=> 'int',
	'default' 		=> 5,
	'options' 		=> array( '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30, '50' => 50 )
	);

$modversion['config'][] = array(
	'name' 			=> 'autoapprove',
	'title' 		=> '_MI_LEXIKON_AUTOAPPROVE',
	'description'	=> '_MI_LEXIKON_AUTOAPPROVEDSC',
	'formtype' 		=> 'yesno',
	'valuetype' 	=> 'int',
	'default' 		=> 0);

$modversion['config'][] = array(
	'name' 			=> 'adminhits',
	'title' 		=> '_MI_LEXIKON_ALLOWADMINHITS',
	'description' 	=> '_MI_LEXIKON_ALLOWADMINHITSDSC',
	'formtype' 		=> 'yesno',
	'valuetype' 	=> 'int',
	'default' 		=> 0);

$modversion['config'][] = array(
	'name' 			=> 'mailtoadmin',
	'title' 		=> '_MI_LEXIKON_MAILTOADMIN',
	'description' 	=> '_MI_LEXIKON_MAILTOADMINDSC',
	'formtype' 		=> 'yesno',
	'valuetype' 	=> 'int',
	'default' 		=> 1);

$modversion['config'][] = array(
	'name' 			=> 'mailtosender',
	'title' 		=> '_MI_LEXIKON_MAILTOSENDER',
	'description' 	=> '_MI_LEXIKON_MAILTOSENDERDSC',
	'formtype' 		=> 'yesno',
	'valuetype' 	=> 'int',
	'default' 		=> 0);


$modversion['config'][] = array(
	'name' 			=> 'rndlength',
	'title' 		=> '_MI_LEXIKON_RANDOMLENGTH',
	'description' 	=> '_MI_LEXIKON_RANDOMLENGTHDSC',
	'formtype' 		=> 'textbox',
	'valuetype' 	=> 'int',
	'default' 		=> 150);

$modversion['config'][] = array(
	'name' 			=> 'linkterms',
	'title' 		=> '_MI_LEXIKON_LINKTERMS',
	'description' 	=> '_MI_LEXIKON_LINKTERMSDSC',
	'formtype' 		=> 'select',
	'valuetype' 	=> 'int',
	'options'	 	=> array('_NO' => 1, 
							'_YES' => 2,
							'_MI_LEXIKON_TOOLTIP' => 3,
							'_MI_LEXIKON_POPUP' => 4,
							'_MI_LEXIKON_BUBBLETIPS' => 5,
							'_MI_LEXIKON_SHADOWTIPS' => 6 ),
	'default' 		=> 2);

// WYSIWYG - Form-Option for X2.0.18ff
/*xoops_load('xoopseditorhandler');
$editor_handler = XoopsEditorHandler::getInstance();
$modversion['config'][] = array(
	'name' 			=> 'form_options',
	'title' 		=> '_MI_LEXIKON_FORM_OPTIONS',
	'description' 	=> '_MI_LEXIKON_FORM_OPTIONSDSC',
	'formtype' 		=> 'select',
	'valuetype' 	=> 'text',
	'options' 		=> array_flip($editor_handler->getList()),
	'default' => 'dhtml');
*/
// WYSIWYG - Form-Options for XOOPS
xoops_load('XoopsEditorHandler');
$editor_handler = XoopsEditorHandler::getInstance();
$editorList = array_flip($editor_handler->getList());

$modversion['config'][] = array('name'        => 'form_options',
                                'title'       => '_MI_LEXIKON_FORM_OPTIONS',
                                'description' => '_MI_LEXIKON_FORM_OPTIONSDSC',
                                'formtype'    => 'select',
                                'valuetype'   => 'text',
                                'options'     => $editorList,
                                'default'     => 'dhtmltextarea');


$modversion['config'][] = array(
	'name' 			=> 'wysiwyg_guests',
	'title' 		=> '_MI_LEXIKON_EDIGUEST',
	'description' 	=> '_MI_LEXIKON_EDIGUESTDSC',
	'formtype' 		=> 'yesno',
	'valuetype' 	=> 'int',
	'default' 		=> 0);
		
// add a textbox
$modversion['config'][] = array(
	'name' 			=> 'teaser',
	'title' 		=> "_MI_LEXIKON_HEADER",
	'description'	=> "_MI_LEXIKON_HEADERDSC",
	'formtype' 		=> 'textarea',
	'valuetype' 	=> 'text',
	'default' 		=> '');

$modversion['config'][] = array(
	'name' 			=> 'showsubmitter',
	'title' 		=> '_MI_LEXIKON_DISPPROL',
	'description' 	=> '_MI_LEXIKON_DISPPROLDSC',
	'formtype' 		=> 'yesno',
	'valuetype' 	=> 'int',
	'default' 		=> 1);
	
//show a link to this authors glossary profile 
$modversion['config'][] = array(
	'name' 			=> 'authorprofile',
	'title' 		=> "_MI_LEXIKON_AUTHORPROFILE",
	'description' 	=> "_MI_LEXIKON_AUTHORPROFILEDSC",
	'formtype' 		=> 'yesno',
	'valuetype' 	=> 'int',
	'default' 		=>  0);

$modversion['config'][] = array(
	'name' 			=> 'showdate',
	'title' 		=> '_MI_LEXIKON_SHOWDAT',
	'description' 	=> '_MI_LEXIKON_SHOWDATDSC',
	'formtype' 		=> 'yesno',
	'valuetype' 	=> 'int',
	'default' 		=> 1);

$modversion['config'][] = array(
	'name' 			=> 'showcount',
	'title' 		=> '_MI_LEXIKON_SHOWCTR',
	'description' 	=> '_MI_LEXIKON_SHOWCTRDSC',
	'formtype' 		=> 'yesno',
	'valuetype' 	=> 'int',
	'default' 		=> 1);

$modversion['config'][] = array(
	'name' 			=> 'captcha',
	'title' 		=> '_MI_LEXIKON_CAPTCHA',
	'description' 	=> '_MI_LEXIKON_CAPTCHADSC',
	'formtype' 		=> 'select',
	'valuetype' 	=> 'int',
	'options' 		=> array(
                    '_NONE' => 0,
                    '_GUESTS' => 1,
                    '_ALL' => 2 ),
	'default' 		=> 0);

//  highlight search keywords in the definitions
$modversion['config'][] = array(
	'name' 			=> 'config_highlighter',
	'title' 		=> '_MI_LEXIKON_KEYWORDS_HIGH',
	'description' 	=> '_MI_LEXIKON_KEYWORDS_HIGHDSC',
	'formtype' 		=> 'yesno',
	'valuetype' 	=> 'int',
	'default' 		=> 0);

// Display social Bookmark icons ?
$modversion['config'][] = array(
	'name' 			=> 'bookmarkme',
	'title' 		=> "_MI_LEXIKON_BOOKMARK_ME",
	'description' 	=> "_MI_LEXIKON_BOOKMARK_MEDSC",
	'formtype' 		=> 'select',
	'valuetype' 	=> 'int',
	'options'	 	=> array('_NO' => 1, 
				          '_YES' => 2,
				          '_MI_LEXIKON_ADDTHIS1' => 3,
				          '_MI_LEXIKON_ADDTHIS2' => 4 ),
	'default' 		=> 2);

//option to set number of keywords
$modversion['config'][] = array(
	'name' 			=> 'metakeywordsnum',
	'title' 		=> "_MI_LEXIKON_METANUM",
	'description' 	=> "_MI_LEXIKON_METANUMDSC",
	'formtype' 		=> 'select',
	'valuetype'		=> 'int',
    'options' 		=> array(
			_MI_LEXIKON_METANUM_0=>0,
			_MI_LEXIKON_METANUM_5=>5,
			_MI_LEXIKON_METANUM_10=>10,
			_MI_LEXIKON_METANUM_20=>20,
			_MI_LEXIKON_METANUM_30=>30,
			_MI_LEXIKON_METANUM_40=>40,
			_MI_LEXIKON_METANUM_50=>50,
			_MI_LEXIKON_METANUM_60=>60,
			_MI_LEXIKON_METANUM_70=>70,
			_MI_LEXIKON_METANUM_80=>80 ),
    'default' 		=> '40');

//category image
$modversion['config'][] = array(
	'name' 			=> 'useshots',
	'title' 		=> "_MI_LEXIKON_USESHOTS",
	'description' 	=> "_MI_LEXIKON_USESHOTSDSC",
	'formtype' 		=> 'yesno',
	'valuetype'		=> 'int',
	'default' 		=> 1);

$modversion['config'][] = array(
	'name' 			=> 'logo_maximgwidth',
	'title' 		=> "_MI_LEXIKON_LOGOWIDTH",
	'description' 	=> "_MI_LEXIKON_LOGOWIDTHDSC",
	'formtype' 		=> 'textbox',
	'valuetype' 	=> 'text',
	'default' 		=> 20);

// width in category view mode
$modversion['config'][] = array(
	'name' 			=> 'imgcatwd',
	'title' 		=> "_MI_LEXIKON_IMCATWD",
	'description' 	=> "_MI_LEXIKON_IMCATWDDSC",
	'formtype' 		=> 'textbox',
	'valuetype' 	=> 'text',
	'default' 		=> 50);

//to activate RSS Syndication for users / guests
$modversion['config'][] = array(
	'name' 			=> 'syndication',
	'title' 		=> "_MI_LEXIKON_RSS",
	'description'	=> "_MI_LEXIKON_RSSDSC",
	'formtype' 		=> 'yesno',
	'valuetype' 	=> 'int',
	'default' 		=> 1);

//to activate webmastercontent for users / guests
$modversion['config'][] = array(
	'name' 			=> 'contentsyndication',
	'title' 		=> "_MI_LEXIKON_SYNDICATION",
	'description'	=> "_MI_LEXIKON_SYNDICATIONDSC",
	'formtype' 		=> 'yesno',
	'valuetype' 	=> 'int',
	'default' 		=> 1);

//Comments (Mondarse)
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] = 'entryID';
$modversion['comments']['pageName'] = 'entry.php';

$modversion['comments']['callbackFile'] = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = 'lexikon_com_approve';
$modversion['comments']['callback']['update'] = 'lexikon_com_update';
//Comments (Mondarse)

//Notification
$modversion["notification"] = array();
$modversion['hasNotification'] = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'lexikon_notify_iteminfo';

$modversion['notification']['category'][1]['name'] = 'global';
$modversion['notification']['category'][1]['title'] = _MI_LEXIKON_NOTIFY;
$modversion['notification']['category'][1]['description'] = _MI_LEXIKON_NOTIFYDSC;
$modversion['notification']['category'][1]['subscribe_from'] = array('index.php','category.php','entry.php');
$modversion['notification']['category'][1]['allow_bookmark'] = 0;

$modversion['notification']['category'][2]['name'] = 'category';
$modversion['notification']['category'][2]['title'] = _MI_LEXIKON_NOTIFY_CAT;
$modversion['notification']['category'][2]['description'] = _MI_LEXIKON_NOTIFY_CATDSC;
$modversion['notification']['category'][2]['subscribe_from'] = array('category.php', 'entry.php');
$modversion['notification']['category'][2]['item_name'] = 'categoryID';
$modversion['notification']['category'][2]['allow_bookmark'] = 1;

$modversion['notification']['category'][3]['name'] = 'term';
$modversion['notification']['category'][3]['title'] = _MI_LEXIKON_NOTIFY_TERM;
$modversion['notification']['category'][3]['description'] = _MI_LEXIKON_NOTIFY_TERMDSC;
$modversion['notification']['category'][3]['subscribe_from'] = 'entry.php';
$modversion['notification']['category'][3]['item_name'] = 'entryID';
$modversion['notification']['category'][3]['allow_bookmark'] = 1;

$modversion['notification']['event'][1]['name'] = 'new_post';
$modversion['notification']['event'][1]['category'] = 'global';
#$modversion['notification']['event'][1]['category'] = 'term';
$modversion['notification']['event'][1]['title'] = _MI_LEXIKON_NEWPOST_NOTIFY;
$modversion['notification']['event'][1]['caption'] = _MI_LEXIKON_NEWPOST_NOTIFYCAP;
$modversion['notification']['event'][1]['description'] = _MI_LEXIKON_NEWPOST_NOTIFYDSC;
$modversion['notification']['event'][1]['mail_template'] = 'lexikon_newpost_notify';
$modversion['notification']['event'][1]['mail_subject'] = _MI_LEXIKON_NEWPOST_NOTIFYSBJ;

$modversion['notification']['event'][2]['name'] = 'new_category';
$modversion['notification']['event'][2]['category'] = 'global';
$modversion['notification']['event'][2]['title'] = _MI_LEXIKON_NEWCAT_NOTIFY;
$modversion['notification']['event'][2]['caption'] = _MI_LEXIKON_NEWCAT_NOTIFYCAP;
$modversion['notification']['event'][2]['description'] = _MI_LEXIKON_NEWCAT_NOTIFYDSC;
$modversion['notification']['event'][2]['mail_template'] = 'lexikon_newcat_notify';
$modversion['notification']['event'][2]['mail_subject'] = _MI_LEXIKON_NEWCAT_NOTIFYSBJ;

$modversion['notification']['event'][3]['name'] = 'term_request';
$modversion['notification']['event'][3]['category'] = 'global';
$modversion['notification']['event'][3]['title'] = _MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFY;
$modversion['notification']['event'][3]['caption'] = _MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFYCAP;
$modversion['notification']['event'][3]['description'] = _MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFYDSC;
$modversion['notification']['event'][3]['mail_template'] = 'global_termrequest_notify';
$modversion['notification']['event'][3]['mail_subject'] = _MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFYSBJ;

$modversion['notification']['event'][4]['name'] = 'term_submit';
$modversion['notification']['event'][4]['category'] = 'global';
$modversion['notification']['event'][4]['admin_only'] = 1;
$modversion['notification']['event'][4]['title'] = _MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFY;
$modversion['notification']['event'][4]['caption'] = _MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFYCAP;
$modversion['notification']['event'][4]['description'] = _MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFYDSC;
$modversion['notification']['event'][4]['mail_template'] = 'global_termsubmit_notify';
$modversion['notification']['event'][4]['mail_subject'] = _MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFYSBJ;

$modversion['notification']['event'][5]['name'] = 'new_post';
$modversion['notification']['event'][5]['category'] = 'category';
$modversion['notification']['event'][5]['title'] = _MI_LEXIKON_CATEGORY_NEWTERM_NOTIFY;
$modversion['notification']['event'][5]['caption'] = _MI_LEXIKON_CATEGORY_NEWTERM_NOTIFYCAP;
$modversion['notification']['event'][5]['description'] = _MI_LEXIKON_CATEGORY_NEWTERM_NOTIFYDSC;
$modversion['notification']['event'][5]['mail_template'] = 'category_newterm_notify';
$modversion['notification']['event'][5]['mail_subject'] = _MI_LEXIKON_CATEGORY_NEWTERM_NOTIFYSBJ;

$modversion['notification']['event'][6]['name'] = 'term_submit';
$modversion['notification']['event'][6]['category'] = 'category';
$modversion['notification']['event'][6]['admin_only'] = 1;
$modversion['notification']['event'][6]['title'] = _MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFY;
$modversion['notification']['event'][6]['caption'] = _MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFYCAP;
$modversion['notification']['event'][6]['description'] = _MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFYDSC;
$modversion['notification']['event'][6]['mail_template'] = 'category_termsubmit_notify';
$modversion['notification']['event'][6]['mail_subject'] = _MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFYSBJ;

$modversion['notification']['event'][7]['name'] = 'approve';
$modversion['notification']['event'][7]['category'] = 'term';
$modversion['notification']['event'][7]['invisible'] = 1;
$modversion['notification']['event'][7]['title'] = _MI_LEXIKON_TERM_APPROVE_NOTIFY;
$modversion['notification']['event'][7]['caption'] = _MI_LEXIKON_TERM_APPROVE_NOTIFYCAP;
$modversion['notification']['event'][7]['description'] = _MI_LEXIKON_TERM_APPROVE_NOTIFYDSC;
$modversion['notification']['event'][7]['mail_template'] = 'term_approve_notify';
$modversion['notification']['event'][7]['mail_subject'] = _MI_LEXIKON_TERM_APPROVE_NOTIFYSBJ;
?>