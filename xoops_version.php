<?php
/**
 * Lexikon XOOPS glossary Module
 *
 * @copyright      XOOPS Project (https://xoops.org)
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author         Yerres
 * @since          0.5
 * @package        module::lexikon
 * @credits        hsalazar, catzwolf, Dario Garcia and many others
 */

$moduleDirName = basename(__DIR__);
$modversion    = [
        'version'               => 1.52,
        'module_status'         => 'Beta 2',
        'release_date'          => '2017/02/05',
        'name'                  => _MI_LEXIKON_MD_NAME,
        'description'           => _MI_LEXIKON_MD_DESC,
        'author'                => 'Yerres',
        'credits'               => 'hsalazar, Mondarse, Catzwolf, and many more',
        'help'                  => 'page=help',
        'license'               => 'GNU GPL 2.0 or later',
        'license_url'           => 'www.gnu.org/licenses/gpl-2.0.html',
        'official'              => 0, //1 indicates supported by XOOPS Dev Team, 0 means 3rd party supported
        'image'                 => 'assets/images/logoModule.png',
        'dirname'               => basename(__DIR__),
        'onInstall'             => 'include/install_function.php',
        'onUpdate'              => 'include/update_function.php',

        'modicons16'            => 'assets/images/icons/16',
        'modicons32'            => 'assets/images/icons/32',

        'license_file'          => XOOPS_URL . '/modules/lexikon/gpl.txt',
        'status_version'        => 1.52,
        'release'               => '2012-05-10',
        'last_update'           => '2015/01/12',
        'module_website_url'    => 'www.xoops.org',
        'module_website_name'   => 'XOOPS',
        'min_php'               => '5.5',
        'min_xoops'             => '2.5.8',
        'min_admin'             => '1.2',
        'min_db'                => ['mysql' => '5.1'],

        'author_word'           => '-',
        'module_website_url'    => 'https://xoops.org/',
        'module_website_name'   => 'XOOPS',
        // Admin things
        'hasAdmin'              => 1,
        'adminindex'            => 'admin/index.php',
        'adminmenu'             => 'admin/menu.php',
        // Sql
        'sqlfile'               => ['mysql' => 'sql/mysql.sql'],
        'tables'                => [
                                    'lxcategories',
                                    'lxentries'
         ],
        // Search
        'hasSearch'             => 1,
        'search'                => [
                        'file'  => 'include/search.inc.php',
                        'func'  => 'lx_search',
         ],
        // Menu
        'hasMain'               => 1,
        'system_menu'           => 1,
        // Use smarty
        'use_smarty'            => 1,
];
global $xoopsUser, $xoopsDB, $xoopsModuleConfig;
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$lexikon       = $moduleHandler->getByDirname($modversion['dirname']);
if ($lexikon) {
    if (!isset($lxConfig)) {
        $configHandler = xoops_getHandler('config');
        $lxConfig      = $configHandler->getConfigsByCat(0, $lexikon->getVar('mid'));
    }
}
$i = 0;
if (is_object($xoopsUser)) {
    if ($xoopsUser->isAdmin()) {
        $modversion['sub'][$i]['name'] = constant('_MI_LEXIKON_SUB_SMNAME0');
        $modversion['sub'][$i]['url']  = 'admin/index.php';
        ++$i;
        $modversion['sub'][$i]['name'] = constant('_MI_LEXIKON_SUB_SMNAME4');
        $modversion['sub'][$i]['url']  = 'admin/entry.php?op=add';
        ++$i;
    }
}
if (isset($lxConfig['authorprofile']) && $lxConfig['authorprofile'] == 1) {
    $modversion['sub'][$i]['name'] = _MI_LEXIKON_SUB_SMNAME6;
    $modversion['sub'][$i]['url']  = 'authorlist.php';
    ++$i;
}
if (isset($lxConfig['contentsyndication']) && $lxConfig['contentsyndication'] == 1) {
    $modversion['sub'][$i]['name'] = _MI_LEXIKON_SUB_SMNAME7;
    $modversion['sub'][$i]['url']  = 'content.php';
    ++$i;
}
if ($lexikon) {
    $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gpermHandler = xoops_getHandler('groupperm');
    if ($gpermHandler->checkRight('lexikon_submit', 0, $groups, $lexikon->getVar('mid'))) {
        $modversion['sub'][$i]['name'] = _MI_LEXIKON_SUB_SMNAME1;
        $modversion['sub'][$i]['url']  = 'submit.php';
        ++$i;
    }
    $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gpermHandler = xoops_getHandler('groupperm');
    if ($gpermHandler->checkRight('lexikon_request', 0, $groups, $lexikon->getVar('mid'))) {
        $modversion['sub'][$i]['name'] = constant('_MI_LEXIKON_SUB_SMNAME2');
        $modversion['sub'][$i]['url']  = 'request.php';
        ++$i;
    }
}
$modversion['sub'][$i]['name'] = constant('_MI_LEXIKON_SUB_SMNAME3');
$modversion['sub'][$i]['url']  = 'search.php';
++$i;
if (isset($lxConfig['catsinmenu']) && $lxConfig['catsinmenu'] == 1 && isset($lxConfig['multicats'])
    && $lxConfig['multicats'] == 1
) {
    $myts = MyTextSanitizer::getInstance();
    $sql  = $xoopsDB->query('SELECT categoryID, name FROM ' . $xoopsDB->prefix('lxcategories') . ' ORDER BY weight ASC');
    while (list($categoryID, $name) = $xoopsDB->fetchRow($sql)) {
        if ($gpermHandler->checkRight('lexikon_view', $categoryID, $groups, $lexikon->getVar('mid'))) {
            $name                          = $myts->htmlSpecialChars($name);
            $categoryID                    = (int)$categoryID;
            $modversion['sub'][$i]['name'] = $name;
            $modversion['sub'][$i]['url']  = 'category.php?categoryID=' . $categoryID;
            ++$i;
        }
    }
}

// blocks
$modversion['blocks'][1] = [
    'file'        => 'entries_new.php',
    'name'        => _MI_LEXIKON_ENTRIESNEW,
    'description' => 'Shows latest terms',
    'show_func'   => 'b_lxentries_new_show',
    'edit_func'   => 'b_lxentries_new_edit',
    'options'     => 'datesub|5|0|0|1|up||',
    'template'    => 'entries_new.tpl',
    'can_clone'   => true
];
$modversion['blocks'][]  = [
    'file'        => 'entries_top.php',
    'name'        => _MI_LEXIKON_ENTRIESTOP,
    'description' => 'Shows popular terms',
    'show_func'   => 'b_lxentries_top_show',
    'edit_func'   => 'b_lxentries_top_edit',
    'options'     => 'counter|5|0|0|1|up||',
    'template'    => 'entries_top.tpl',
    'can_clone'   => true
];
$modversion['blocks'][]  = [
    'file'        => 'random_term.php',
    'name'        => _MI_LEXIKON_RANDOMTERM,
    'description' => 'Shows a random term',
    'show_func'   => 'b_lxentries_random_show',
    'template'    => 'entries_random.tpl',
    'can_clone'   => true
];
$modversion['blocks'][]  = [
    'file'        => 'entries_initial.php',
    'name'        => _MI_LEXIKON_TERMINITIAL,
    'description' => 'Shows alphabet',
    'show_func'   => 'b_lxentries_alpha_show',
    'edit_func'   => 'b_lxentries_alpha_edit',
    'options'     => '1|8',
    'template'    => 'entries_initial.tpl',
    'can_clone'   => true
];
$modversion['blocks'][]  = [
    'file'        => 'categories_block.php',
    'name'        => _MI_LEXIKON_CATS,
    'description' => 'Shows categories',
    'show_func'   => 'b_lxcategories_show',
    'edit_func'   => 'b_lxcategories_edit',
    'options'     => 'weight|5',
    'template'    => 'categories_block.tpl',
    'can_clone'   => true
];
$modversion['blocks'][]  = [
    'file'        => 'entries_spot.php',
    'name'        => _MI_LEXIKON_SPOT,
    'description' => 'Shows spotlight terms in a category',
    'show_func'   => 'b_lxspot_show',
    'edit_func'   => 'b_lxspot_edit',
    'options'     => '1|5|0|0|0|ver|0|datesub|65|25',
    'template'    => 'entries_spot.tpl',
    'can_clone'   => true
];
$modversion['blocks'][]  = [
    'file'        => 'entries_authors.php',
    'name'        => _MI_LEXIKON_BNAME8,
    'description' => 'Shows top authors',
    'show_func'   => 'b_lx_author_show',
    'edit_func'   => 'b_lx_author_edit',
    'options'     => 'count|5|uname|total',
    'template'    => 'entries_authors.tpl',
    'can_clone'   => true
];
$modversion['blocks'][]  = [
    'file'        => 'entries_scrolling.php',
    'name'        => _MI_LEXIKON_BNAME9,
    'description' => 'Shows scrolling definitions',
    'show_func'   => 'b_scrolling_term_show',
    'edit_func'   => 'b_scrolling_term_edit',
    'options'     => '5|2||up|0|200|1|1|RAND()|DESC|1',
    'template'    => 'entries_scrolling.tpl',
    'can_clone'   => true
];
/*
 * $options:
 *                  $options[0] - number of tags to display
 *                  $options[1] - time duration, in days, 0 for all the time
 *                  $options[2] - max font size (px or %)
 *                  $options[3] - min font size (px or %)
 */

$modversion['blocks'][] = [
    'file'        => 'lexikon_block_tag.php',
    'name'        => 'Lexikon Tag Cloud',
    'description' => 'Show tag cloud',
    'show_func'   => 'lexikon_tag_block_cloud_show',
    'edit_func'   => 'lexikon_tag_block_cloud_edit',
    'options'     => '100|0|150|80',
    'template'    => 'lexikon_tag_block_cloud.tpl',
    'can_clone'   => true
];
/*
 * $options:
 *                  $options[0] - number of tags to display
 *                  $options[1] - time duration, in days, 0 for all the time
 *                  $options[2] - sort: a - alphabet; c - count; t - time
 */
$modversion['blocks'][] = [
    'file'        => 'lexikon_block_tag.php',
    'name'        => 'Lexikon Top Tags',
    'description' => 'Show top tags',
    'show_func'   => 'lexikon_tag_block_top_show',
    'edit_func'   => 'lexikon_tag_block_top_edit',
    'options'     => '50|30|t',
    'template'    => 'lexikon_tag_block_top.tpl',
    'can_clone'   => true
];

// Templates
$modversion['templates'][1] = [
    'file'        => 'lx_category.tpl',
    'description' => 'Display categories'
];
$modversion['templates'][]  = [
    'file'        => 'lx_index.tpl',
    'description' => 'Display index'
];
$modversion['templates'][]  = [
    'file'        => 'lx_entry.tpl',
    'description' => 'Display term'
];
$modversion['templates'][]  = [
    'file'        => 'lx_letter.tpl',
    'description' => 'Display letter'
];
$modversion['templates'][]  = [
    'file'        => 'lx_search.tpl',
    'description' => 'search glossary'
];
$modversion['templates'][]  = [
    'file'        => 'lx_request.tpl',
    'description' => 'Request a definition'
];
$modversion['templates'][]  = [
    'file'        => 'lx_submit.tpl',
    'description' => 'Submit a definition'
];
$modversion['templates'][]  = [
    'file'        => 'lx_adminmenu.tpl',
    'description' => '(Admin) Tabs bar for administration pages'
];
$modversion['templates'][]  = [
    'file'        => 'lx_bookmark.tpl',
    'description' => 'Social Bookmarking tags'
];
$modversion['templates'][]  = [
    'file'        => 'lexikon_rss.tpl',
    'description' => 'Display Lexikon rss feed'
];
$modversion['templates'][]  = [
    'file'        => 'lx_profile.tpl',
    'description' => 'Glossary-profile of authors'
];
$modversion['templates'][]  = [
    'file'        => 'lx_authorlist.tpl',
    'description' => 'Glossary author List'
];
$modversion['templates'][]  = [
    'file'        => 'lx_content.tpl',
    'description' => 'content syndication dispatcher'
];
$modversion['templates'][]  = [
    'file'        => 'lx_syndication.tpl',
    'description' => 'Webmaster content syndication'
];
$modversion['templates'][]  = [
    'file'        => 'lx_topten.tpl',
    'description' => 'TOP10 Definitions'
];
$modversion['templates'][]  = [
    'file'        => 'lx_tag_bar.tpl',
    'description' => 'Lexikon Definition Tagbar'
];

// Config Settings
$modversion['config'][1] = [
    'name'        => 'multicats',
    'title'       => '_MI_LEXIKON_MULTICATS',
    'description' => '_MI_LEXIKON_MULTICATSDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
];
$modversion['config'][] = [
    'name'        => 'catsinmenu',
    'title'       => '_MI_LEXIKON_CATSINMENU',
    'description' => '_MI_LEXIKON_CATSINMENUDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
];
$modversion['config'][] = [
    'name'        => 'dateformat',
    'title'       => '_MI_LEXIKON_DATEFORMAT',
    'description' => '_MI_LEXIKON_DATEFORMATDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'd.m.Y H:i'
];
$modversion['config'][] = [
    'name'        => 'perpage',
    'title'       => '_MI_LEXIKON_PERPAGE',
    'description' => '_MI_LEXIKON_PERPAGEDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 20,
    'options'     => [
                      '5' => 5,
                      '10' => 10,
                      '15' => 15,
                      '20' => 20,
                      '25' => 25,
                      '30' => 30,
                      '50' => 50
                      ]
];
$modversion['config'][] = [
    'name'        => 'indexperpage',
    'title'       => '_MI_LEXIKON_PERPAGEINDEX',
    'description' => '_MI_LEXIKON_PERPAGEINDEXDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 10,
    'options'     => [
                      '5' => 5,
                      '10' => 10,
                      '15' => 15,
                      '20' => 20,
                      '25' => 25,
                      '30' => 30,
                      '50' => 50
                      ]
];
$modversion['config'][] = [
    'name'        => 'blocksperpage',
    'title'       => '_MI_LEXIKON_BLOCKSPERPAGE',
    'description' => '_MI_LEXIKON_BLOCKSPERPAGEDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 5,
    'options'     => [
                      '5' => 5,
                      '10' => 10,
                      '15' => 15,
                      '20' => 20,
                      '25' => 25,
                      '30' => 30,
                      '50' => 50
                      ]
];
$modversion['config'][] = [
    'name'        => 'autoapprove',
    'title'       => '_MI_LEXIKON_AUTOAPPROVE',
    'description' => '_MI_LEXIKON_AUTOAPPROVEDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
];
$modversion['config'][] = [
    'name'        => 'adminhits',
    'title'       => '_MI_LEXIKON_ALLOWADMINHITS',
    'description' => '_MI_LEXIKON_ALLOWADMINHITSDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
];
$modversion['config'][] = [
    'name'        => 'mailtoadmin',
    'title'       => '_MI_LEXIKON_MAILTOADMIN',
    'description' => '_MI_LEXIKON_MAILTOADMINDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
];
$modversion['config'][] = [
    'name'        => 'mailtosender',
    'title'       => '_MI_LEXIKON_MAILTOSENDER',
    'description' => '_MI_LEXIKON_MAILTOSENDERDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
];
$modversion['config'][] = [
    'name'        => 'rndlength',
    'title'       => '_MI_LEXIKON_RANDOMLENGTH',
    'description' => '_MI_LEXIKON_RANDOMLENGTHDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 150
];
$modversion['config'][] = [
    'name'        => 'linkterms',
    'title'       => '_MI_LEXIKON_LINKTERMS',
    'description' => '_MI_LEXIKON_LINKTERMSDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => [
        '_NO'                    => 1,
        '_YES'                   => 2,
        '_MI_LEXIKON_TOOLTIP'    => 3,
        '_MI_LEXIKON_POPUP'      => 4,
        '_MI_LEXIKON_BUBBLETIPS' => 5,
        '_MI_LEXIKON_SHADOWTIPS' => 6
    ],
    'default'     => 2
];

// WYSIWYG - Form-Option for X2.0.18ff
/*xoops_load('xoopseditorhandler');
$editorHandler = XoopsEditorHandler::getInstance();
$modversion['config'][] = array(
    'name'          => 'form_options',
    'title'         => '_MI_LEXIKON_FORM_OPTIONS',
    'description'   => '_MI_LEXIKON_FORM_OPTIONSDSC',
    'formtype'      => 'select',
    'valuetype'     => 'text',
    'options'       => array_flip($editorHandler->getList()),
    'default' => 'dhtml');
*/
// WYSIWYG - Form-Options for XOOPS
xoops_load('XoopsEditorHandler');
$editorHandler = XoopsEditorHandler::getInstance();
$editorList    = array_flip($editorHandler->getList());

$modversion['config'][] = [
    'name'        => 'form_options',
    'title'       => '_MI_LEXIKON_FORM_OPTIONS',
    'description' => '_MI_LEXIKON_FORM_OPTIONSDSC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'options'     => $editorList,
    'default'     => 'dhtmltextarea'
];
$modversion['config'][] = [
    'name'        => 'wysiwyg_guests',
    'title'       => '_MI_LEXIKON_EDIGUEST',
    'description' => '_MI_LEXIKON_EDIGUESTDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
];

// add a textbox
$modversion['config'][] = [
    'name'        => 'teaser',
    'title'       => '_MI_LEXIKON_HEADER',
    'description' => '_MI_LEXIKON_HEADERDSC',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => ''
];
$modversion['config'][] = [
    'name'        => 'showsubmitter',
    'title'       => '_MI_LEXIKON_DISPPROL',
    'description' => '_MI_LEXIKON_DISPPROLDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
];

//show a link to this authors glossary profile
$modversion['config'][] = [
    'name'        => 'authorprofile',
    'title'       => '_MI_LEXIKON_AUTHORPROFILE',
    'description' => '_MI_LEXIKON_AUTHORPROFILEDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
];
$modversion['config'][] = [
    'name'        => 'showdate',
    'title'       => '_MI_LEXIKON_SHOWDAT',
    'description' => '_MI_LEXIKON_SHOWDATDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
];
$modversion['config'][] = [
    'name'        => 'showcount',
    'title'       => '_MI_LEXIKON_SHOWCTR',
    'description' => '_MI_LEXIKON_SHOWCTRDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
];
$modversion['config'][] = [
    'name'        => 'captcha',
    'title'       => '_MI_LEXIKON_CAPTCHA',
    'description' => '_MI_LEXIKON_CAPTCHADSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => [
        '_NONE'   => 0,
        '_GUESTS' => 1,
        '_ALL'    => 2
    ],
    'default'     => 0
];

//  highlight search keywords in the definitions
$modversion['config'][] = [
    'name'        => 'config_highlighter',
    'title'       => '_MI_LEXIKON_KEYWORDS_HIGH',
    'description' => '_MI_LEXIKON_KEYWORDS_HIGHDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0
];

// Display social Bookmark icons ?
$modversion['config'][] = [
    'name'        => 'bookmarkme',
    'title'       => '_MI_LEXIKON_BOOKMARK_ME',
    'description' => '_MI_LEXIKON_BOOKMARK_MEDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => [
        '_NO'                  => 1,
        '_YES'                 => 2,
        '_MI_LEXIKON_ADDTHIS1' => 3,
        '_MI_LEXIKON_ADDTHIS2' => 4
    ],
    'default'     => 2
];

//option to set number of keywords
$modversion['config'][] = [
    'name'        => 'metakeywordsnum',
    'title'       => '_MI_LEXIKON_METANUM',
    'description' => '_MI_LEXIKON_METANUMDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => [
        _MI_LEXIKON_METANUM_0  => 0,
        _MI_LEXIKON_METANUM_5  => 5,
        _MI_LEXIKON_METANUM_10 => 10,
        _MI_LEXIKON_METANUM_20 => 20,
        _MI_LEXIKON_METANUM_30 => 30,
        _MI_LEXIKON_METANUM_40 => 40,
        _MI_LEXIKON_METANUM_50 => 50,
        _MI_LEXIKON_METANUM_60 => 60,
        _MI_LEXIKON_METANUM_70 => 70,
        _MI_LEXIKON_METANUM_80 => 80
    ],
    'default'     => '40'
];

//category image
$modversion['config'][] = [
    'name'        => 'useshots',
    'title'       => '_MI_LEXIKON_USESHOTS',
    'description' => '_MI_LEXIKON_USESHOTSDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
];
$modversion['config'][] = [
    'name'        => 'logo_maximgwidth',
    'title'       => '_MI_LEXIKON_LOGOWIDTH',
    'description' => '_MI_LEXIKON_LOGOWIDTHDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 20
];

// width in category view mode
$modversion['config'][] = [
    'name'        => 'imgcatwd',
    'title'       => '_MI_LEXIKON_IMCATWD',
    'description' => '_MI_LEXIKON_IMCATWDDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 50
];

//Uploads : max width/height for image upload
$modversion['config'][] = [
    'name'            => 'imguploadwd',
    'title'        => "_MI_LEXIKON_IMGUPLOADWD",
    'description'    => "_MI_LEXIKON_IMGUPLOADWD_DESC",
    'formtype'        => 'textbox',
    'valuetype'    => 'text',
    'default'        => 200
];

//Uploads : max size for image upload
$modversion['config'][] = [
    'name'            => 'imguploadsize',
    'title'        => "_MI_LEXIKON_IMGUPLOADSIZE",
    'description'    => "_MI_LEXIKON_IMGUPLOADSIZE_DESC",
    'formtype'        => 'textbox',
    'valuetype'    => 'text',
    'default'        => 10485760
]; // 1 MB

//to activate RSS Syndication for users / guests
$modversion['config'][] = [
    'name'        => 'syndication',
    'title'       => '_MI_LEXIKON_RSS',
    'description' => '_MI_LEXIKON_RSSDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
];

//to activate webmastercontent for users / guests
$modversion['config'][] = [
    'name'        => 'contentsyndication',
    'title'       => '_MI_LEXIKON_SYNDICATION',
    'description' => '_MI_LEXIKON_SYNDICATIONDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1
];

//Comments (Mondarse)
$modversion['hasComments']          = 1;
$modversion['comments']['itemName'] = 'entryID';
$modversion['comments']['pageName'] = 'entry.php';

$modversion['comments']['callbackFile']        = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = 'lexikon_com_approve';
$modversion['comments']['callback']['update']  = 'lexikon_com_update';
//Comments (Mondarse)

//Notification
$modversion['notification']                = [];
$modversion['hasNotification']             = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'lexikon_notify_iteminfo';

$modversion['notification']['category'][] = [
                        'name'            => 'global',
                        'title'           => _MI_LEXIKON_NOTIFY,
                        'description'     => _MI_LEXIKON_NOTIFYDSC,
                        'subscribe_from'  => [
                                              'index.php',
                                              'category.php',
                                              'entry.php'
                                              ],
                        'allow_bookmark'  => 0
];
$modversion['notification']['category'][] = [
                        'name'            => 'category',
                        'title'           => _MI_LEXIKON_NOTIFY_CAT,
                        'description'     => _MI_LEXIKON_NOTIFY_CATDSC,
                        'subscribe_from'  => [
                                              'category.php',
                                              'entry.php'
                                              ],
                        'item_name'       => 'categoryID',
                        'allow_bookmark'  => 1
];
$modversion['notification']['category'][] = [
                        'name'            => 'term',
                        'title'           => _MI_LEXIKON_NOTIFY_TERM,
                        'description'     => _MI_LEXIKON_NOTIFY_TERMDSC,
                        'subscribe_from'  => 'entry.php',
                        'item_name'       => 'entryID',
                        'allow_bookmark'  => 1
];
$modversion['notification']['event'][]    = [
                        'name'            => 'new_post',
                        'category'        => 'global',
                        'title'           => _MI_LEXIKON_NEWPOST_NOTIFY,
                        'caption'         => _MI_LEXIKON_NEWPOST_NOTIFYCAP,
                        'description'     => _MI_LEXIKON_NEWPOST_NOTIFYDSC,
                        'mail_template'   => 'lexikon_newpost_notify',
                        'mail_subject'    => _MI_LEXIKON_NEWPOST_NOTIFYSBJ
];
$modversion['notification']['event'][]    = [
                        'name'            => 'new_category',
                        'category'        => 'global',
                        'title'           => _MI_LEXIKON_NEWCAT_NOTIFY,
                        'caption'         => _MI_LEXIKON_NEWCAT_NOTIFYCAP,
                        'description'     => _MI_LEXIKON_NEWCAT_NOTIFYDSC,
                        'mail_template'   => 'lexikon_newcat_notify',
                        'mail_subject'    => _MI_LEXIKON_NEWCAT_NOTIFYSBJ
];
$modversion['notification']['event'][]    = [
                        'name'            => 'term_request',
                        'category'        => 'global',
                        'title'           => _MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFY,
                        'caption'         => _MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFYCAP,
                        'description'     => _MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFYDSC,
                        'mail_template'   => 'global_termrequest_notify',
                        'mail_subject'    => _MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFYSBJ
];
$modversion['notification']['event'][]    = [
                        'name'            => 'term_submit',
                        'category'        => 'global',
                        'admin_only'      => 1,
                        'title'           => _MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFY,
                        'caption'         => _MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFYCAP,
                        'description'     => _MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFYDSC,
                        'mail_template'   => 'global_termsubmit_notify',
                        'mail_subject'    => _MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFYSBJ
];
$modversion['notification']['event'][]    = [
                        'name'            => 'new_post',
                        'category'        => 'category',
                        'title'           => _MI_LEXIKON_CATEGORY_NEWTERM_NOTIFY,
                        'caption'         => _MI_LEXIKON_CATEGORY_NEWTERM_NOTIFYCAP,
                        'description'     => _MI_LEXIKON_CATEGORY_NEWTERM_NOTIFYDSC,
                        'mail_template'   => 'category_newterm_notify',
                        'mail_subject'    => _MI_LEXIKON_CATEGORY_NEWTERM_NOTIFYSBJ
];
$modversion['notification']['event'][]    = [
                        'name'            => 'term_submit',
                        'category'        => 'category',
                        'admin_only'      => 1,
                        'title'           => _MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFY,
                        'caption'         => _MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFYCAP,
                        'description'     => _MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFYDSC,
                        'mail_template'   => 'category_termsubmit_notify',
                        'mail_subject'    => _MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFYSBJ
];
$modversion['notification']['event'][]    = [
                        'name'            => 'approve',
                        'category'        => 'term',
                        'invisible'       => 1,
                        'title'           => _MI_LEXIKON_TERM_APPROVE_NOTIFY,
                        'caption'         => _MI_LEXIKON_TERM_APPROVE_NOTIFYCAP,
                        'description'     => _MI_LEXIKON_TERM_APPROVE_NOTIFYDSC,
                        'mail_template'   => 'term_approve_notify',
                        'mail_subject'    => _MI_LEXIKON_TERM_APPROVE_NOTIFYSBJ
];