<?php
/**
 *
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */

include __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'lx_letter.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
include_once XOOPS_ROOT_PATH . '/modules/lexikon/include/common.inc.php';

global $xoTheme, $xoopsUser;
$myts = MyTextSanitizer::getInstance();

$init = isset($_GET['init']) ? $_GET['init'] : 0;
$xoopsTpl->assign('firstletter', $init);
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
$start = isset($_GET['start']) ? (int)$_GET['start'] : 0;

$publishedwords = LexikonUtility::countWords();
$xoopsTpl->assign('publishedwords', $publishedwords);

//permissions
$gpermHandler = xoops_getHandler('groupperm');
$groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$module_id    = $xoopsModule->getVar('mid');
$allowed_cats = $gpermHandler->getItemIds('lexikon_view', $groups, $module_id);
$catids       = implode(',', $allowed_cats);
$catperms     = " AND categoryID IN ($catids) ";

$xoopsTpl->assign('multicats', (int)$xoopsModuleConfig['multicats']);

if (!function_exists('mb_ucfirst') && function_exists('mb_substr')) {
   function mb_ucfirst($string) {  
   $string = mb_ereg_replace("^[\ ]+","", $string);  
   $string = mb_strtoupper(mb_substr($string, 0, 1, "UTF-8"), "UTF-8").mb_substr($string, 1, mb_strlen($string), "UTF-8" );  
   return $string;  
   }  
}
// To display the linked letter list
$alpha = LexikonUtility::getAlphaArray();
$xoopsTpl->assign('alpha', $alpha);

list($howmanyother) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('lxentries') . " WHERE init = '#' AND offline ='0' " . $catperms . ''));
$xoopsTpl->assign('totalother', $howmanyother);

// To display the list of categories
if ($xoopsModuleConfig['multicats'] == 1) {
    $xoopsTpl->assign('block0', LexikonUtility::getCategoryArray());
    $xoopsTpl->assign('layout', CONFIG_CATEGORY_LAYOUT_PLAIN);
    if (LexikonUtility::getModuleOption('useshots')) {
        $xoopsTpl->assign('show_screenshot', true);
        $xoopsTpl->assign('logo_maximgwidth', $xoopsModuleConfig['logo_maximgwidth']);
        $xoopsTpl->assign('lang_noscreenshot', _MD_LEXIKON_NOSHOTS);
    } else {
        $xoopsTpl->assign('show_screenshot', false);
    }
}

// No initial: we need to see all letters
if (!$init) {
    $entriesarray = [];
    $pagetype     = 0;

    // How many entries will we show in this page?
    //$queryA = "SELECT w. * , c.name AS catname FROM ".$xoopsDB -> prefix( 'lxentries' )." w LEFT JOIN ".$xoopsDB -> prefix( 'lxcategories' )." c ON w.categoryID = c.categoryID WHERE w.submit = '0' AND w.offline = '0' ORDER BY w.term ASC";
    //$resultA = $xoopsDB -> query ($queryA, $xoopsModuleConfig['indexperpage'], $start );
    $queryA  = 'SELECT * FROM '
                . $xoopsDB->prefix('lxentries')
                . " WHERE offline = '0' AND submit = '0' "
                . $catperms
                . ' ORDER BY term ASC';
    $resultA = $xoopsDB->query($queryA, $xoopsModuleConfig['indexperpage'], $start);

    $allentries   = $xoopsDB->query('SELECT entryID FROM '
                                    . $xoopsDB->prefix('lxentries')
                                    . " WHERE submit ='0' AND offline = '0' "
                                    . $catperms
                                    . ' ORDER BY term ASC ');
    $totalentries = $xoopsDB->getRowsNum($allentries);
    $xoopsTpl->assign('totalentries', $totalentries);

    while (list($entryID, $categoryID, $term, $init, $definition, $ref, $url, $uid, $submit, $datesub, $counter, $html, $smiley, $xcodes, $breaks, $block, $offline, $comments) = $xoopsDB->fetchRow($resultA)) {
        $eachentry        = [];
        $xoopsModule      = XoopsModule::getByDirname('lexikon');
        $eachentry['dir'] = $xoopsModule->dirname();

        if ($xoopsModuleConfig['multicats'] == 1) {
            $eachentry['catid'] = (int)$categoryID;
            $resultF            = $xoopsDB->query('SELECT name FROM '
                                                  . $xoopsDB->prefix('lxcategories')
                                                  . " WHERE categoryID = $categoryID ORDER BY name ASC");
            while (list($name)  = $xoopsDB->fetchRow($resultF)) {
                $eachentry['catname'] = $myts->htmlSpecialChars($name);
            }
        }

        $eachentry['id']   = (int)$entryID;
        $eachentry['term'] = mb_ucfirst($myts->htmlSpecialChars($term));

        if (($xoopsModuleConfig['com_rule'] != 0) || (($xoopsModuleConfig['com_rule'] != 0) && is_object($xoopsUser))) {
            if ($comments != 0) {
                $eachentry['comments'] = "<a href='entry.php?entryID="
                                          . $eachentry['id']
                                          . "'>"
                                          . $comments
                                          . '&nbsp;'
                                          . _COMMENTS
                                          . '</a>';
            } else {
                $eachentry['comments'] = '';
            }
        }

        if (!XOOPS_USE_MULTIBYTES) {
            $eachentry['definition'] = $myts->displayTarea($definition, $html, $smiley, $xcodes, 1, $breaks);
        }

        // Functional links
        $microlinks              = LexikonUtility::getServiceLinks($eachentry);
        $eachentry['microlinks'] = $microlinks;

        $entriesarray['single'][] = $eachentry;
    }
    $pagenav                = new XoopsPageNav($totalentries, $xoopsModuleConfig['indexperpage'], $start, 'start');
    $entriesarray['navbar'] = '<div style="text-align:right;">'
                              . $pagenav->renderNav(6)
                              . '</div>';

    $xoopsTpl->assign('entriesarray', $entriesarray);
    $xoopsTpl->assign('pagetype', '0');
    $xoopsTpl->assign('pageinitial', _MD_LEXIKON_ALL);

    LexikonUtility::createPageTitle($myts->htmlSpecialChars(_MD_LEXIKON_BROWSELETTER . ' - ' . _MD_LEXIKON_ALL));
} else {    // $init does exist
    $pagetype = 1;
    // There IS an initial letter, so we want to show just that letter's terms
    $entriesarray2 = array();

    // How many entries will we show in this page?
    if ($init == _MD_LEXIKON_OTHER) {
        $queryB  = 'SELECT entryID, categoryID, term, definition, uid, html, smiley, xcodes, breaks, comments FROM '
                   . $xoopsDB->prefix('lxentries')
                   . " WHERE submit ='0' AND offline = '0' AND init = '#' "
                   . $catperms
                   . '  ORDER BY term ASC';
        $resultB = $xoopsDB->query($queryB, $xoopsModuleConfig['indexperpage'], $start);
    } else {
        $queryB  = 'SELECT entryID, categoryID, term, definition, uid, html, smiley, xcodes, breaks, comments FROM '
                   . $xoopsDB->prefix('lxentries')
                   . " WHERE submit ='0' AND offline = '0' AND init = '$init' AND init != '#' "
                   . $catperms
                   . '  ORDER BY term ASC';
        $resultB = $xoopsDB->query($queryB, $xoopsModuleConfig['indexperpage'], $start);
    }

    $entrieshere = $xoopsDB->getRowsNum($resultB);
    if ($entrieshere == 0) {
        redirect_header('javascript:history.go(-1)', 1, _MD_LEXIKON_NOTERMSINLETTER);
    }

    if ($init == _MD_LEXIKON_OTHER) {
        $allentries = $xoopsDB->query('SELECT entryID FROM '
                                      . $xoopsDB->prefix('lxentries')
                                      . " WHERE init = '#' AND submit ='0' AND offline = '0' "
                                      . $catperms
                                      . '  ORDER BY term ASC ');
    } else {
        $allentries = $xoopsDB->query('SELECT entryID FROM '
                                      . $xoopsDB->prefix('lxentries')
                                      . " WHERE init = '$init' AND init != '#' AND submit ='0' AND offline = '0' "
                                      . $catperms
                                      . '  ORDER BY term ASC ');
    }
    $totalentries = $xoopsDB->getRowsNum($allentries);
    $xoopsTpl->assign('totalentries', $totalentries);
    LexikonUtility::createPageTitle($myts->htmlSpecialChars(_MD_LEXIKON_BROWSELETTER . (isset($init['init']) ? (' - ' . $init['init']) : '')));

    while (list($entryID, $categoryID, $term, $definition, $uid, $html, $smiley, $xcodes, $breaks, $comments) = $xoopsDB->fetchRow($resultB)) {
        $eachentry        = array();
        $xoopsModule      = XoopsModule::getByDirname('lexikon');
        $eachentry['dir'] = $xoopsModule->dirname();

        if ($xoopsModuleConfig['multicats'] == 1) {
            $eachentry['catid'] = (int)$categoryID;
            $resultF            = $xoopsDB->query('SELECT name FROM '
                                                  . $xoopsDB->prefix('lxcategories')
                                                  . " WHERE categoryID = $categoryID ORDER BY name ASC");
            while (list($name) = $xoopsDB->fetchRow($resultF)) {
                $eachentry['catname'] = $myts->htmlSpecialChars($name);
            }
        }
        $eachentry['id']   = (int)$entryID;
        $eachentry['term'] = mb_ucfirst($myts->htmlSpecialChars($term));
        if ($init === '#') {
            $eachentry['init'] = _MD_LEXIKON_OTHER;
        } else {
            $eachentry['init'] = $init;
        }

        if (($xoopsModuleConfig['com_rule'] != 0) || (($xoopsModuleConfig['com_rule'] != 0) && is_object($xoopsUser))) {
            if ($comments != 0) {
                $eachentry['comments'] = "<a href='entry.php?entryID="
                                          . $eachentry['id']
                                          . "'>"
                                          . $comments
                                          . '&nbsp;'
                                          . _COMMENTS
                                          . '</a>';
            } else {
                $eachentry['comments'] = '';
            }
        }
        if (!XOOPS_USE_MULTIBYTES) {
            $eachentry['definition'] = $myts->displayTarea($definition, $html, $smiley, $xcodes, 1, $breaks);
        }

        // Functional links
        $microlinks              = LexikonUtility::getServiceLinks($eachentry);
        $eachentry['microlinks'] = $microlinks;

        $entriesarray2['single'][] = $eachentry;
    }
    $pagenav                 = new XoopsPageNav($totalentries, $xoopsModuleConfig['indexperpage'], $start, 'init=' . $eachentry['init'] . '&start');
    $entriesarray2['navbar'] = '<div style="text-align:right;">'
                                . $pagenav->renderNav(6)
                                . '</div>';

    $xoopsTpl->assign('entriesarray2', $entriesarray2);
    $xoopsTpl->assign('pagetype', '1');
    if ($eachentry['init'] === '#') {
        $xoopsTpl->assign('pageinitial', _MD_LEXIKON_OTHER);
        LexikonUtility::createPageTitle($myts->htmlSpecialChars(_MD_LEXIKON_BROWSELETTER . ' - ' . _MD_LEXIKON_OTHER));
    } else {
        $xoopsTpl->assign('pageinitial', mb_ucfirst($eachentry['init']));
    }
}

$xoopsTpl->assign('lang_modulename', $xoopsModule->name());
$xoopsTpl->assign('lang_moduledirname', $xoopsModule->getVar('dirname'));
$xoopsTpl->assign('alpha', $alpha);
if ($xoopsModuleConfig['syndication'] == 1) {
    $xoopsTpl->assign('syndication', true);
}
if ($xoopsUser) {
    $xoopsTpl->assign('syndication', true);
}
// Meta data
if ($publishedwords = 0) {
    $meta_description = xoops_substr(LexikonUtility::convertHtml2text($eachentry['definition']), 0, 150);
    if ($xoopsModuleConfig['multicats'] == 1) {
        LexikonUtility::extractKeywords($xoopsModule->name() . ' ,' . $eachentry['term'] . ', ' . $meta_description);
        LexikonUtility::getMetaDescription($myts->htmlSpecialChars($xoopsModule->name()) . ' ' . $eachentry['catname'] . ' ' . $eachentry['term']);
    } else {
        LexikonUtility::extractKeywords($myts->htmlSpecialChars($xoopsModule->name()) . ', ' . $eachentry['term'] . ', ' . $meta_description);
        LexikonUtility::getMetaDescription($myts->htmlSpecialChars($xoopsModule->name()) . ' ' . $eachentry['term'] . ' ' . $meta_description);
    }
}

$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" href="assets/css/style.css" />');

include XOOPS_ROOT_PATH . '/footer.php';
