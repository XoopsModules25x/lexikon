<?PHP
/**
 * Module: Lexikon -  glossary module
 * Author: hsalazar
 * Licence: GNU
 */
#$xoopsOption['pagetype'] = "search";

include __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'lx_search.tpl';
include XOOPS_ROOT_PATH . '/header.php';

global $xoTheme, $xoopsDB, $xoopsModule, $xoopsModuleConfig, $searchtype;
$myts = MyTextSanitizer::getInstance();
// -- options
include_once XOOPS_ROOT_PATH . '/modules/lexikon/include/common.inc.php';
$highlight = false;
$highlight = ($xoopsModuleConfig['config_highlighter'] = 1) ? 1 : 0;
$hightlight_key = '';

include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

// Check if search is enabled site-wide
$configHandler     = xoops_getHandler('config');
$xoopsConfigSearch = $configHandler->getConfigsByCat(XOOPS_CONF_SEARCH);
if ($xoopsConfigSearch['enable_search'] != 1) {
    header('location: ' . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/index.php');
    exit();
}

// permissions
$gpermHandler = xoops_getHandler('groupperm');
$groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$module_id    = $xoopsModule->getVar('mid');
$allowed_cats = $gpermHandler->getItemIds('lexikon_view', $groups, $module_id);
$catids       = implode(',', $allowed_cats);

extract($_GET);
extract($_POST, EXTR_OVERWRITE);

$action     = isset($action) ? trim($action) : 'search';
$query      = isset($term) ? trim($term) : '';
$start      = isset($start) ? (int)$start : 0;
$categoryID = isset($categoryID) ? (int)$categoryID : 0;
$type       = isset($type) ? (int)$type : 3;
$queries    = array();

if ($xoopsModuleConfig['multicats'] == 1) {
    $xoopsTpl->assign('multicats', 1);
    $totalcats = LexikonUtility::countCats();
    $xoopsTpl->assign('totalcats', $totalcats);
} else {
    $xoopsTpl->assign('multicats', 0);
}

// Configure search parameters according to selector
$query = stripslashes($query);
if ($type == '1') {
    $searchtype = "( w.term LIKE '%$query%' )";
}
if ($type == '2') {
    $searchtype = "( definition LIKE '%$query%' )";
}
if ($type == '3') {
    $searchtype = "(( term LIKE '%$query%' OR definition LIKE '%$query%' OR ref LIKE '%$query%' ))";
}

if ($xoopsModuleConfig['multicats'] == 1) {
    // If the search is in a particular category
    if ($categoryID > 0) {
        $andcatid = "AND categoryID = '$categoryID' ";
    } else {
        $andcatid = '';
    }
} else {
    $andcatid = '';
}

// Counter
$publishedwords = LexikonUtility::countWords();
$xoopsTpl->assign('publishedwords', $publishedwords);

// If there's no term here (calling directly search page)
if (!$query) {
    // Display message saying there's no term and explaining how to search
    $xoopsTpl->assign('intro', _MD_LEXIKON_NOSEARCHTERM);
    // Display search form
    $searchform = LexikonUtility::showSearchForm();
    $xoopsTpl->assign('searchform', $searchform);
} else {
    // IF results, count number
    $catrestrict = " categoryID IN ($catids) ";
    $searchquery = $xoopsDB->query('SELECT COUNT(*) as nrows FROM '
                                   . $xoopsDB->prefix('lxentries')
                                   . " w WHERE offline='0' AND "
                                   . $catrestrict
                                   . ' '
                                   . $andcatid
                                   . " AND $searchtype   ORDER BY term DESC");
    list($results) = $xoopsDB->fetchRow($searchquery);

    if ($results == 0) {
        // There's been no correspondences with the searched terms
        $xoopsTpl->assign('intro', _MD_LEXIKON_NORESULTS);

        // Display search form
        $searchform = LexikonUtility::showSearchForm();
        $xoopsTpl->assign('searchform', $searchform);
        // $results > 0 -> there were search results
    } else {
        // Show paginated list of results
        // We'll put the results in an array
        $resultset = [];

        // -- highlighter
        if (is_array($resultset)) {
            if ($highlight) {
                $xoopsTpl->assign('highlight', true);
                $hightlight_key = '&amp;keywords=' . urlencode(trim($query));
            } else {
                $xoopsTpl->assign('highlight', false);
            }
        }

        // How many results will we show in this page?
        if ($xoopsModuleConfig['multicats'] == 1) {
            // If the search is in a particular category
            if ($categoryID > 0) {
                $andcatid2 = "AND w.categoryID = '$categoryID' ";
            } else {
                $andcatid2 = '';
            }
        } else {
            $andcatid2 = '';
        }
        $catsallow = " w.categoryID IN ($catids) ";
        $queryA    = 'SELECT w.entryID, w.categoryID, w.term, w.init, w.definition, w.datesub, w.ref, c.name AS catname FROM '
                     . $xoopsDB->prefix('lxentries')
                     . ' w LEFT JOIN '
                     . $xoopsDB->prefix('lxcategories')
                     . " c ON w.categoryID = c.categoryID WHERE w.offline = '0' AND "
                     . $catsallow
                     . ' '
                     . $andcatid2
                     . ' AND '
                     . $searchtype
                     . ' ';
        $queryA    .= '  ORDER BY w.term ASC';
        $resultA   = $xoopsDB->query($queryA, $xoopsModuleConfig['indexperpage'], $start);

        while (list($entryID, $categoryID, $term, $init, $definition, $datesub, $ref, $catname) = $xoopsDB->fetchRow($resultA)) {
            $eachresult               = [];
            $xoopsModule              = XoopsModule::getByDirname('lexikon');
            $eachresult['dir']        = $xoopsModule->dirname();
            $eachresult['id']         = $entryID;
            $eachresult['categoryID'] = $categoryID;
            $eachresult['term']       = ucfirst($myts->htmlSpecialChars($term));
            $eachresult['date']       = formatTimestamp($datesub, $xoopsModuleConfig['dateformat']);
            $eachresult['ref']        = LexikonUtility::getHTMLHighlight($query, $myts->htmlSpecialChars($ref), '<b style="background-color: #FFFF80; ">', '</b>');
            $eachresult['catname']    = $myts->htmlSpecialChars($catname);
            $tempdef                  = $myts->displayTarea($definition, 1, 1, 1, 1, 1);
            $eachresult['definition'] = LexikonUtility::getHTMLHighlight($query, $tempdef, '<b style="background-color: #FFFF80; ">', '</b>');
            if ($highlight) {
                $eachresult['keywords'] = $hightlight_key;
            }
            // Functional links
            $microlinks               = LexikonUtility::getServiceLinks($eachresult);
            $eachresult['microlinks'] = $microlinks;
            $resultset['match'][]     = $eachresult;
        }

        // Msg: there's # results
        $xoopsTpl->assign('intro', sprintf(_MD_LEXIKON_THEREWERE, $results, $query));

        $linkstring          = 'term=' . $query . '&start';
        $pagenav             = new XoopsPageNav($results, $xoopsModuleConfig['indexperpage'], $start, $linkstring);
        $resultset['navbar'] = '<div style="text-align:right;">' . $pagenav->renderNav(6) . '</div>';

        $xoopsTpl->assign('resultset', $resultset);

        // Display search form
        $searchform = LexikonUtility::showSearchForm();
        $xoopsTpl->assign('searchform', $searchform);
    }
}
// Assign variables and close
$xoopsTpl->assign('lang_modulename', $xoopsModule->name());
$xoopsTpl->assign('lang_moduledirname', $xoopsModule->getVar('dirname'));

$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" href="assets/css/style.css" />');
$xoopsTpl->assign('xoops_pagetitle', _MD_LEXIKON_SEARCHENTRY . ' - ' . $myts->htmlSpecialChars($xoopsModule->name()));

// Meta data
$meta_description = _MD_LEXIKON_SEARCHENTRY . ' - ' . $myts->htmlSpecialChars($xoopsModule->name());
if (isset($xoTheme) && is_object($xoTheme)) {
    $xoTheme->addMeta('meta', 'description', $meta_description);
} else {
    $xoopsTpl->assign('xoops_meta_description', $meta_description);
}

include XOOPS_ROOT_PATH . '/footer.php';
