<?php
/**
 * Module: Lexikon - glossary module
 * Author: hsalazar
 * Licence: GNU
 */

use Xmf\Request;
use XoopsModules\Lexikon\{
    Helper,
    Utility
};
/** @var Helper $helper */

$GLOBALS['xoopsOption']['template_main'] = 'lx_entry.tpl';

require __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/header.php';
global $xoTheme, $xoopsUser, $lexikon_module_header;
$myts = \MyTextSanitizer::getInstance();
xoops_load('XoopsUserUtility');

$helper = Helper::getInstance();

require_once XOOPS_ROOT_PATH . '/modules/lexikon/include/common.inc.php';
$highlight = $utility::getModuleOption('config_highlighter');
if ($highlight) {
    require_once XOOPS_ROOT_PATH . '/modules/lexikon/class/keyhighlighter.class.php';
}

$entryID = Request::getInt('entryID', 0, 'GET');
if (empty($entryID)) {
    redirect_header('index.php', 3, _MD_LEXIKON_UNKNOWNERROR);
}
$entrytype = 1;
// permissions
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');
$groups           = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$module_id        = $xoopsModule->getVar('mid');
$allowed_cats     = $grouppermHandler->getItemIds('lexikon_view', $groups, $module_id);
$catids           = implode(',', $allowed_cats);
$catperms         = " AND categoryID IN ($catids) ";

// If there's no entries yet in the system...
$publishedwords = $utility::countWords();
$xoopsTpl->assign('publishedwords', $publishedwords);
if (0 == $publishedwords) {
    $xoopsTpl->assign('empty', '1');
    $xoopsTpl->assign('stillnothing', _MD_LEXIKON_STILLNOTHINGHERE);
}

// To display the linked letter list
$alpha = $utility::getAlphaArray();
$xoopsTpl->assign('alpha', $alpha);

[$howmanyother] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(entryID) FROM ' . $xoopsDB->prefix('lxentries') . " WHERE init = '#' AND offline ='0' " . $catperms . ' '));
$xoopsTpl->assign('totalother', $howmanyother);

$xoopsTpl->assign('multicats', (int)$helper->getConfig('multicats'));
// To display the list of categories
if (1 == $helper->getConfig('multicats')) {
    $xoopsTpl->assign('block0', $utility::getCategoryArray());
    $xoopsTpl->assign('layout', CONFIG_CATEGORY_LAYOUT_PLAIN);
    if (1 == $helper->getConfig('useshots')) {
        $xoopsTpl->assign('show_screenshot', true);
        $xoopsTpl->assign('logo_maximgwidth', $helper->getConfig('logo_maximgwidth'));
        $xoopsTpl->assign('lang_noscreenshot', _MD_LEXIKON_NOSHOTS);
    } else {
        $xoopsTpl->assign('show_screenshot', false);
    }
}

if (!$entryID) {
    redirect_header('<script>javascript:history.go(-1)</script>', 2, _MD_LEXIKON_UNKNOWNERROR);
} else {
    if ($entryID <= 0) {
        redirect_header('<script>javascript:history.go(-1)</script>', 2, _MD_LEXIKON_UNKNOWNERROR);
    }
    if (!$xoopsUser || ($xoopsUser->isAdmin($xoopsModule->mid()) && 1 == $helper->getConfig('adminhits'))
        || ($xoopsUser
            && !$xoopsUser->isAdmin($xoopsModule->mid()))) {
        $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('lxentries') . " SET counter = counter+1 WHERE entryID = $entryID ");
    }

    $result = $xoopsDB->query(
        'SELECT entryID, categoryID, term, init, definition, ref, url, uid, submit, datesub, counter, html, smiley, xcodes, breaks, block, offline, notifypub
                                 FROM ' . $xoopsDB->prefix('lxentries') . "
                                 WHERE entryID = $entryID"
    );
    // verify result
    if ($xoopsDB->getRowsNum($result) <= 0) {
        redirect_header('index.php', 2, _MD_LEXIKON_UNKNOWNERROR);
    }
}

while (list($entryID, $categoryID, $term, $init, $definition, $ref, $url, $uid, $submit, $datesub, $counter, $html, $smiley, $xcodes, $breaks, $block, $offline) = $xoopsDB->fetchRow($result)) {
    $catID = (int)$categoryID;
    if (!$grouppermHandler->checkRight('lexikon_view', (int)$categoryID, $groups, $module_id)) {
        redirect_header('index.php', 3, _NOPERM);
    }

    $thisterm            = [];
    $xoopsModule         = XoopsModule::getByDirname('lexikon');
    $thisterm['id']      = (int)$entryID;
    $thisterm['offline'] = (int)$offline;
    // exit if offline - except admin
    if (1 == $thisterm['offline'] && !$xoopsUserIsAdmin) {
        redirect_header('<script>javascript:history.go(-1)</script>', 3, _MD_LEXIKON_ENTRYISOFF);
    }
    if (1 == $helper->getConfig('multicats')) {
        $thisterm['categoryID'] = (int)$categoryID;
        $catname                = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('lxcategories') . " WHERE categoryID = $categoryID ");
        while (list($name) = $xoopsDB->fetchRow($catname)) {
            $thisterm['catname'] = htmlspecialchars($name);
        }
    }

    $glossaryterm     = htmlspecialchars($term);
    $thisterm['term'] = ucfirst(htmlspecialchars($term));
    if ('#' === $init) {
        $thisterm['init'] = _MD_LEXIKON_OTHER;
    } else {
        $thisterm['init'] = ucfirst($init);
    }
    $thisterm['offline'] = (int)$offline;

    if (1 != $helper->getConfig('linkterms') && 2 != $helper->getConfig('linkterms')) {
        $utility::getModuleHeader();
        $xoopsTpl->assign('xoops_module_header', $lexikon_module_header);
    } else {
        $xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" href="assets/css/style.css">');
    }

    if (1 != $helper->getConfig('linkterms')) {
        // Code to make links out of glossary terms
        $parts = explode('>', $definition);

        // First, retrieve all terms from the glossary...
        $allterms = $xoopsDB->query('SELECT entryID, term, definition FROM ' . $xoopsDB->prefix('lxentries') . " WHERE offline ='0' " . $catperms . ' ');

        while (list($entryID, $term, $definition) = $xoopsDB->fetchRow($allterms)) {
            foreach ($parts as $key => $part) {
                if ($term != $glossaryterm) {
                    $term_q      = preg_quote($term, '/');
                    $search_term = "/\b$term_q\b/SsUi";
                    //static link
                    $staticURL = '' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/entry.php?entryID=' . ucfirst($entryID) . '';
                    switch ($helper->getConfig('linkterms')) {
                        default:
                            $replace_term = '<span><b><a style="cursor:help;border-bottom: 1px dotted #000;color: #2F5376;" href="' . $staticURL . '" >' . $term . '</a></b></span>';
                            break;
                        case 3: //tooltip
                            $tooltipdef   = htmlspecialchars(xoops_substr(strip_tags($definition), 0, 150));
                            $replace_term = '<a class="parser" href="' . $staticURL . '" onMouseover="ddrivetip(\'' . $tooltipdef . '\', 300)"; onMouseout=\'hideddrivetip()\'>' . $term . '</a>';
                            break;
                        case 4://simple popup
                            $replace_term = '<a style="cursor:help;border-bottom: 1px dotted #000;color: #2F5376;" href="#" onClick=\'popup("popup.php?entryID=' . $entryID . '","details", 420, 350); return false\'>' . $term . '</a>';
                            break;
                        case 5:// balloon tooltip
                            $tooltipdef   = htmlspecialchars(xoops_substr(strip_tags($definition), 0, 150));
                            $replace_term = '<a class="parser" href="' . $staticURL . '" onMouseover="showToolTip(event,\'' . $tooltipdef . '\');return false"; onMouseout=\'hideToolTip()\'>' . $term . '</a>';
                            break;
                        case 6:// shadow tooltip
                            $tooltipdef   = htmlspecialchars(xoops_substr(strip_tags($definition), 0, 150));
                            $replace_term = '<a class="parser" href="' . $staticURL . '" onmouseout="hideTooltip()" onmouseover="showTooltip(event,\'' . $tooltipdef . '\')"; >' . $term . '</a>';
                            break;
                    }
                    $parts[$key] = preg_replace($search_term, $replace_term, $parts[$key]);
                }
            }
        }
        $definition = implode('>', $parts);
    }
    $thisterm['definition'] = $myts->displayTarea($definition, $html, $smiley, $xcodes, 1, $breaks);
    $thisterm['ref']        = $myts->displayTarea($ref, $html, $smiley, $xcodes, 1, $breaks);
    $thisterm['url']        = $myts->makeClickable($url, $allowimage = 0);
    //$thisterm['submitter'] = XoopsUserUtility::getUnameFromId ( $uid );
    if (1 == $helper->getConfig('showsubmitter')) {
        $xoopsTpl->assign('showsubmitter', true);
        if (1 == $helper->getConfig('authorprofile')) {
            $thisterm['submitter'] = $utility::getLinkedProfileFromId($uid);
        } else {
            $thisterm['submitter'] = \XoopsUserUtility::getUnameFromId($uid);
        }
    } else {
        $xoopsTpl->assign('showsubmitter', false);
    }
    $thisterm['submit']  = (int)$submit;
    $thisterm['datesub'] = formatTimestamp($datesub, $helper->getConfig('dateformat'));
    $thisterm['counter'] = (int)$counter;
    $thisterm['block']   = (int)$block;
    $thisterm['dir']     = $xoopsModule->dirname();
    if ($highlight && isset($_GET['keywords'])) {
        $keywords               = htmlspecialchars(trim(urldecode($_GET['keywords'])));
        $h                      = new Lexikon\Keyhighlighter($keywords, true, 'lx_myhighlighter');
        $thisterm['definition'] = $h->highlight($thisterm['definition']);
        $thisterm['ref']        = $h->highlight($thisterm['ref']);
    }
}
//smartry strings
$xoopsTpl->assign('thisterm', $thisterm);
$microlinks    = $utility::getServiceLinks($thisterm);
$microlinksnew = $utility::getServiceLinksNew($thisterm);
$xoopsTpl->assign('microlinks', $microlinks);
$xoopsTpl->assign('microlinksnew', $microlinksnew);
$xoopsTpl->assign('lang_modulename', $xoopsModule->name());
$xoopsTpl->assign('lang_moduledirname', $xoopsModule->getVar('dirname'));
$xoopsTpl->assign('entryID', $entryID);
$xoopsTpl->assign('submittedon', sprintf(_MD_LEXIKON_SUBMITTEDON, $thisterm['datesub']));
if (1 == $helper->getConfig('showsubmitter')) {
    $xoopsTpl->assign('submitter', sprintf(_MD_LEXIKON_SUBMITTEDBY, $thisterm['submitter']));
}
$xoopsTpl->assign('counter', sprintf(_MD_LEXIKON_COUNT, $thisterm['counter']));
$xoopsTpl->assign('entrytype', '1');

// --- keywordshighligher ---
/**
 * @param $matches
 * @return string
 */
function lx_myhighlighter($matches)
{
    return '<span style="font-weight: bolder; background-color: #FFFF80;">' . $matches[0] . '</span>';
}


//--- Display tags of this term
$tagsmeta = '';
#$itemid = $entryID;
/** @var \XoopsModuleHandler $moduleHandler */
//$moduleHandler = xoops_getHandler('module');
//$tagsModule    = $moduleHandler->getByDirname('tag');
//if (is_object($tagsModule)) {
//    require_once XOOPS_ROOT_PATH . '/modules/tag/include/tagbar.php';
//
//    $itemid = Request::getInt('entryID', 0, 'GET');
//    $catid  = 0;
//    //$xoopsTpl->assign('tagbar', tagBar($itemid, $catid = 0));
//    $tagbar = tagBar($itemid, $catid);
//    if ($tagbar) {
//        $xoopsTpl->assign('tagbar', $tagbar);
//        $tagsmeta = implode(' ', $tagbar['tags']);
//    } else {
//        $tagsmeta = '';
//    }
//} else {
//    $xoopsTpl->assign('tagbar', false);
//    $tagsmeta = '';
//}

//--- linkterms assigns
// Balloontips
if (5 == $helper->getConfig('linkterms')) {
    $xoopsTpl->assign('balloontips', true);
} else {
    $xoopsTpl->assign('balloontips', false);
}

// Show Bookmark icons ?
switch ($helper->getConfig('bookmarkme')) {
    case '0':
    default:
        $xoopsTpl->assign('bookmarkme', false);
        break;
    case '1':
        $xoopsTpl->assign('bookmarkme', 1);
        $xoopsTpl->assign('encoded_title', rawurlencode($thisterm['term']));
        break;
    case '2':
        $xoopsTpl->assign('bookmarkme', 2);
        break;
    case '3':
        $xoopsTpl->assign('bookmarkme', 3);
        break;
}
// Meta data
$meta_description = xoops_substr($utility::convertHtml2text($thisterm['definition']), 0, 150);
if (1 == $helper->getConfig('multicats')) {
    $utility::createPageTitle($thisterm['term'] . ' - ' . $thisterm['catname']);
    $utility::extractKeywords(htmlspecialchars($xoopsModule->name()) . ' ,' . $thisterm['term'] . ' ,' . $thisterm['catname'] . ', ' . $meta_description . ', ' . $tagsmeta);
    $utility::getMetaDescription(htmlspecialchars($xoopsModule->name()) . ' ' . $thisterm['catname'] . ' ' . $thisterm['term'] . ' ' . $meta_description);
} else {
    $utility::createPageTitle($thisterm['term']);
    $utility::extractKeywords(htmlspecialchars($xoopsModule->name()) . ' ,' . $thisterm['term'] . ', ' . $meta_description . ', ' . $tagsmeta);
    $utility::getMetaDescription(htmlspecialchars($xoopsModule->name()) . ' ' . $thisterm['term'] . ' ' . $meta_description);
}
//Mondarse
require XOOPS_ROOT_PATH . '/include/comment_view.php';
//Mondarse
require_once XOOPS_ROOT_PATH . '/footer.php';
