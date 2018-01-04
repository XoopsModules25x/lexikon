<?php
/**
 *
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */

use Xmf\Request;

include __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'lx_category.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
global $xoTheme, $xoopsUser;
$myts = \MyTextSanitizer::getInstance();
require_once XOOPS_ROOT_PATH . '/modules/lexikon/include/common.inc.php';
$limit      = $xoopsModuleConfig['indexperpage'];
$categoryID = isset($_GET['categoryID']) ? (int)$_GET['categoryID'] : 0;
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
$start = Request::getInt('start', 0, 'GET');
$xoopsTpl->assign('multicats', (int)$xoopsModuleConfig['multicats']);

// Permission
$gpermHandler = xoops_getHandler('groupperm');
$groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$module_id    = $xoopsModule->getVar('mid');
$allowed_cats = $gpermHandler->getItemIds('lexikon_view', $groups, $module_id);
$catids       = implode(',', $allowed_cats);
$catperms     = " AND categoryID IN ($catids) ";
if (!$gpermHandler->checkRight('lexikon_view', $categoryID, $groups, $xoopsModule->getVar('mid'))) {
    redirect_header('index.php', 3, _NOPERM);
}
// If there's no entries yet in the system...
$publishedwords = $utility::countWords();
if (0 == $publishedwords) {
    redirect_header(XOOPS_URL, 1, _MD_LEXIKON_STILLNOTHINGHERE);
}
$xoopsTpl->assign('publishedwords', $publishedwords);

// To display the list of linked initials
$alpha = $utility::getAlphaArray();
$xoopsTpl->assign('alpha', $alpha);

list($howmanyother) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('lxentries') . " WHERE init = '#' AND offline ='0' " . $catperms . ' '));
$xoopsTpl->assign('totalother', $howmanyother);

// get the list of Maincategories :: or return to mainpage
if (1 == $xoopsModuleConfig['multicats']) {
    $xoopsTpl->assign('block0', $utility::getCategoryArray());
    $xoopsTpl->assign('layout', CONFIG_CATEGORY_LAYOUT_PLAIN);
    if (1 == $xoopsModuleConfig['useshots']) {
        $xoopsTpl->assign('show_screenshot', true);
        $xoopsTpl->assign('logo_maximgwidth', $xoopsModuleConfig['logo_maximgwidth']);
        $xoopsTpl->assign('lang_noscreenshot', _MD_LEXIKON_NOSHOTS);
    } else {
        $xoopsTpl->assign('show_screenshot', false);
    }
} else {  // if glossaries are disabled in module options
    redirect_header('index.php', 3, _MD_LEXIKON_SINGLECAT);
}

// No ID of category: we need to see all categories descriptions
if (!$categoryID) {
    // How many categories are there?
    $catperms2  = " WHERE categoryID IN ($catids) ";
    $resultcats = $xoopsDB->query('SELECT categoryID FROM ' . $xoopsDB->prefix('lxcategories') . ' ORDER BY weight DESC');
    $totalcats  = $xoopsDB->getRowsNum($resultcats);
    if (0 == $totalcats) {
        redirect_header('javascript:history.go(-1)', 1, _MD_LEXIKON_NOCATSINSYSTEM);
    }
    // If there's no $categoryID, we want to show just the categories with their description
    $catsarray = [];

    // How many categories will we show in this page?
    $queryA  = 'SELECT * FROM ' . $xoopsDB->prefix('lxcategories') . ' ' . $catperms2 . ' ORDER BY weight ASC';
    $resultA = $xoopsDB->query($queryA, $xoopsModuleConfig['indexperpage'], $start);
    while (list($categoryID, $name, $description, $total, $weight, $logourl) = $xoopsDB->fetchRow($resultA)) {
        if ($logourl && 'http://' !== $logourl) {
            $logourl = $myts->htmlSpecialChars($logourl);
        } else {
            $logourl = '';
        }
        $eachcat                = [];
        $xoopsModule            = XoopsModule::getByDirname('lexikon');
        $eachcat['dir']         = $xoopsModule->dirname();
        $eachcat['id']          = (int)$categoryID;
        $eachcat['name']        = $myts->htmlSpecialChars($name);
        $eachcat['description'] = $myts->displayTarea($description, 1, 1, 1, 1, 1);
        $eachcat['image']       = $logourl;

        // Total entries in this category
        $entriesincat     = (int)$total;
        $eachcat['total'] = $entriesincat;

        $catsarray['single'][] = $eachcat;
    }

    $pagenav             = new \XoopsPageNav($totalcats, $xoopsModuleConfig['indexperpage'], $start, 'start');
    $catsarray['navbar'] = '<div style="text-align:right;">' . $pagenav->renderNav(6) . '</div>';

    $xoopsTpl->assign('catsarray', $catsarray);
    $xoopsTpl->assign('pagetype', '0');

    $utility::createPageTitle($myts->htmlSpecialChars(_MD_LEXIKON_ALLCATS));
    // Meta data
    $meta_description = xoops_substr(strip_tags($eachcat['description']), 0, 150);
    $utility::extractKeywords($myts->htmlSpecialChars($xoopsModule->name()) . ', ' . $eachcat['name'] . ', ' . $meta_description);
    $utility::getMetaDescription($myts->htmlSpecialChars($xoopsModule->name()) . ' ' . $eachcat['name'] . ' ' . $meta_description);
} else {
    // There IS a $categoryID, thus we show only that category's description

    // get the list of Subcategories
    $catdata = $xoopsDB->query('SELECT categoryID, name, description, total, logourl FROM ' . $xoopsDB->prefix('lxcategories') . " WHERE categoryID = '$categoryID' ");
    // verify ID
    if ($xoopsDB->getRowsNum($catdata) <= 0) {
        redirect_header('index.php', 2, _MD_LEXIKON_UNKNOWNERROR);
    }
    while (list($categoryID, $name, $description, $total, $logourl) = $xoopsDB->fetchRow($catdata)) {
        if ($gpermHandler->checkRight('lexikon_view', $categoryID, $groups, $xoopsModule->getVar('mid'))) {
            if (0 == $total) {
                redirect_header('javascript:history.go(-1)', 1, _MD_LEXIKON_NOENTRIESINCAT);
            }
            $singlecat                = [];
            $singlecat['dir']         = $xoopsModule->dirname();
            $singlecat['id']          = $categoryID;
            $singlecat['name']        = $myts->htmlSpecialChars($name);
            $singlecat['description'] = html_entity_decode($myts->displayTarea($description, 1, 1, 1, 1, 1)); // LionHell ajout html_entity ...
            $singlecat['image']       = $myts->htmlSpecialChars($logourl);

            // Total entries in this category
            //$entriesincat = $utility::countByCategory($categoryID);
            $entriesincat       = (int)$total;
            $singlecat['total'] = $entriesincat;
            $xoopsTpl->assign('singlecat', $singlecat);

            // Entries to show in current page
            $entriesarray = [];

            // Now we retrieve a specific number of entries according to start variable
            $queryB  = 'SELECT entryID, term, definition, html, smiley, xcodes, breaks, comments FROM ' . $xoopsDB->prefix('lxentries') . " WHERE categoryID = '$categoryID' AND submit ='0' AND offline = '0' ORDER BY term ASC";
            $resultB = $xoopsDB->query($queryB, $xoopsModuleConfig['indexperpage'], $start);

            //while (list( $entryID, $term, $definition ) = $xoopsDB->fetchRow($resultB))
            while (list($entryID, $term, $definition, $html, $smiley, $xcodes, $breaks, $comments) = $xoopsDB->fetchRow($resultB)) {
                $eachentry         = [];
                $xoopsModule       = XoopsModule::getByDirname('lexikon');
                $eachentry['dir']  = $xoopsModule->dirname();
                $eachentry['id']   = $entryID;
                $eachentry['term'] = ucfirst($myts->htmlSpecialChars($term));
                if (!XOOPS_USE_MULTIBYTES) {
                    $eachentry['definition'] = $myts->displayTarea($definition, $html, $smiley, $xcodes, 1, $breaks);
                }
                if ((0 != $xoopsModuleConfig['com_rule'])
                    || ((0 != $xoopsModuleConfig['com_rule'])
                        && is_object($xoopsUser))) {
                    if (0 != $comments) {
                        $eachentry['comments'] = "<a href='entry.php?entryID=" . $eachentry['id'] . "'>" . $comments . '&nbsp;' . _COMMENTS . '</a>';
                    } else {
                        $eachentry['comments'] = '';
                    }
                }

                // Functional links
                $microlinks               = $utility::getServiceLinks($eachentry);
                $eachentry['microlinks']  = $microlinks;
                $entriesarray['single'][] = $eachentry;
            }
        }
    }
    $navstring = 'categoryID=' . $singlecat['id'] . '&start';
    $pagenav   = new \XoopsPageNav($entriesincat, $xoopsModuleConfig['indexperpage'], $start, $navstring);

    $entriesarray['navbar'] = '<div style="text-align:right;">' . $pagenav->renderNav(6) . '</div>';

    $xoopsTpl->assign('entriesarray', $entriesarray);
    $xoopsTpl->assign('pagetype', '1');
    $xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars(_MD_LEXIKON_ENTRYCATEGORY . ' ' . $singlecat['name']) . ' - ' . $myts->htmlSpecialChars($xoopsModule->name()));
    // Meta data
    if ($entriesincat > 0) {
        $meta_description = xoops_substr(strip_tags($singlecat['description']), 0, 150);
        $utility::extractKeywords($myts->htmlSpecialChars($xoopsModule->name()) . ', ' . $singlecat['name'] . ', ' . $eachentry['term'] . ', ' . $meta_description);
        $utility::getMetaDescription($myts->htmlSpecialChars($xoopsModule->name()) . ' ' . $singlecat['name'] . '  ' . $eachentry['term'] . ' ' . $meta_description);
    }
}

$xoopsTpl->assign('lang_modulename', $xoopsModule->name());
$xoopsTpl->assign('lang_moduledirname', $xoopsModule->getVar('dirname'));
if (1 == $xoopsModuleConfig['syndication']) {
    $xoopsTpl->assign('syndication', true);
}
if ($xoopsUser) {
    $xoopsTpl->assign('syndication', true);
}
$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" href="assets/css/style.css">');

include XOOPS_ROOT_PATH . '/footer.php';
