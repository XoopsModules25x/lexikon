<?php
/**
 *
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */

use XoopsModules\Lexikon;

include __DIR__ . '/header.php';

$GLOBALS['xoopsOption']['template_main'] = 'lx_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once XOOPS_ROOT_PATH . '/modules/lexikon/include/common.inc.php';
global $xoTheme, $xoopsUser;
$myts = \MyTextSanitizer::getInstance();

// Disable cache since content differs for each user
//$xoopsConfig["module_cache"][$xoopsModule->getVar("mid")] = 0;

$utility::calculateTotals();
$xoopsTpl->assign('multicats', (int)$xoopsModuleConfig['multicats']);
$rndlength = !empty($xoopsModuleConfig['rndlength']) ? (int)$xoopsModuleConfig['rndlength'] : 150;

//permissions
$gpermHandler = xoops_getHandler('groupperm');
$groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$module_id    = $xoopsModule->getVar('mid');
$perm_itemid  = isset($categoryID) ? $categoryID : 0;
if (!$gpermHandler->checkRight('lexikon_view', $perm_itemid, $groups, $module_id)) {
    redirect_header('javascript:history.go(-1)', 2, _NOPERM);
}
$allowed_cats = $gpermHandler->getItemIds('lexikon_view', $groups, $module_id);
if (count($allowed_cats) > 0) {
    $catids   = implode(',', $allowed_cats);
    $catperms = " AND categoryID IN ($catids) ";
} else {
    return '0';
}

if (!function_exists('mb_ucfirst') && function_exists('mb_substr')) {
    /**
     * @param $string
     * @return false|string
     */
    function mb_ucfirst($string)
    {
        $string = mb_ereg_replace("^[\ ]+", '', $string);
        $string = mb_strtoupper(mb_substr($string, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($string, 1, mb_strlen($string), 'UTF-8');
        return $string;
    }
}

// Counts
$xoopsTpl->assign('multicats', (int)$xoopsModuleConfig['multicats']);
if (1 == $xoopsModuleConfig['multicats']) {
    $xoopsTpl->assign('totalcats', (int)$utility::countCats());
}
$publishedwords = $utility::countWords();
$xoopsTpl->assign('publishedwords', $publishedwords);

// If there's no entries yet in the system...
if (0 == $publishedwords) {
    $xoopsTpl->assign('empty', '1');
}

// To display the search form
$xoopsTpl->assign('searchform', $utility::showSearchForm());

//--------------------------------------------------------------
// To display the linked letter list
$alpha = $utility::getAlphaArray();
$xoopsTpl->assign('alpha', $alpha);
$alphaCount = count($alpha);

list($howmanyother) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('lxentries') . " WHERE init = '#' AND offline ='0' " . $catperms . ' '));
$xoopsTpl->assign('totalother', $howmanyother);

//-----------------------------------------



// Letter Choice Start ---------------------------------------

$moduleDirName = basename(__DIR__);
$moduleDirNameUpper = strtoupper($moduleDirName);

Lexikon\Helper::getInstance()->loadLanguage('common');
$xoopsTpl->assign('letterChoiceTitle', constant('CO_' . $moduleDirNameUpper . '_' . 'BROWSETOTOPIC'));
/** @var \XoopsDatabase $db */
$db           = \XoopsDatabaseFactory::getDatabase();
$objHandler =  Lexikon\Helper::getInstance()->getHandler('Entries');
$choicebyletter = new Lexikon\Common\LetterChoice($objHandler, null, null, range('a', 'z'), 'init', LEXIKON_URL . '/letter.php');
$catarray['letters']  = $choicebyletter->render($alphaCount, $howmanyother);
$xoopsTpl->assign('catarray', $catarray);

// Letter Choice End ------------------------------------




//---------------------------------------------
// To display the tree of categories
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
}
// To display the recent entries block
$block1   = [];
$result05 = $xoopsDB->query('SELECT entryID, categoryID, term, datesub FROM ' . $xoopsDB->prefix('lxentries') . ' WHERE datesub < ' . time() . " AND datesub > 0 AND submit = '0' AND offline = '0' AND request = '0' " . $catperms . ' ORDER BY datesub DESC', (int)$xoopsModuleConfig['blocksperpage'],
                            0);
if ($publishedwords > 0) { // If there are definitions
    //while (list( $entryID, $term, $datesub ) = $xoopsDB->fetchRow($result05)) {
    while (list($entryID, $categoryID, $term, $datesub) = $xoopsDB->fetchRow($result05)) {
        $newentries             = [];
        $xoopsModule            = XoopsModule::getByDirname('lexikon');
        $linktext               = mb_ucfirst($myts->htmlSpecialChars($term));
        $newentries['linktext'] = $linktext;
        $newentries['id']       = $entryID;
        $newentries['date']     = formatTimestamp($datesub, 's');

        $block1['newstuff'][] = $newentries;
    }
    $xoopsTpl->assign('block', $block1);
}

// To display the most read entries block
$block2   = [];
$result06 = $xoopsDB->query('SELECT entryID, term, counter FROM ' . $xoopsDB->prefix('lxentries') . ' WHERE datesub < ' . time() . " AND datesub > 0 AND submit = '0' AND offline = '0' AND request = '0' " . $catperms . ' ORDER BY counter DESC', (int)$xoopsModuleConfig['blocksperpage'], 0);
// If there are definitions
if ($publishedwords > 0) {
    while (list($entryID, $term, $counter) = $xoopsDB->fetchRow($result06)) {
        $popentries             = [];
        $xoopsModule            = XoopsModule::getByDirname('lexikon');
        $linktext               = mb_ucfirst($myts->htmlSpecialChars($term));
        $popentries['linktext'] = $linktext;
        $popentries['id']       = $entryID;
        $popentries['counter']  = (int)$counter;

        $block2['popstuff'][] = $popentries;
    }
    $xoopsTpl->assign('block2', $block2);
}

// To display the random term block
list($numrows) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('lxentries') . " WHERE submit = 'O' AND offline = '0' " . $catperms . ' '));
if ($numrows > 1) {
    --$numrows;
    mt_srand((double)microtime() * 1000000);
    $entrynumber = mt_rand(0, $numrows);
} else {
    $entrynumber = 0;
}

$resultZ = $xoopsDB->query('SELECT entryID, categoryID, term, definition, html, smiley, xcodes, breaks FROM ' . $xoopsDB->prefix('lxentries') . " WHERE submit = 'O' AND offline = '0' " . $catperms . " LIMIT $entrynumber, 1");

$zerotest = $xoopsDB->getRowsNum($resultZ);
if (0 != $zerotest) {
    while ($myrow = $xoopsDB->fetchArray($resultZ)) {
        $random         = [];
        $random['id']   = $myrow['entryID'];
        $random['term'] = mb_ucfirst($myrow['term']);

        if (!XOOPS_USE_MULTIBYTES) {
            $random['definition'] = $myts->displayTarea(xoops_substr($myrow['definition'], 0, $rndlength - 1), $myrow['html'], $myrow['smiley'], $myrow['xcodes'], 1, $myrow['breaks']);
        }

        if (1 == $xoopsModuleConfig['multicats']) {
            $random['categoryID'] = $myrow['categoryID'];

            $resultY = $xoopsDB->query('SELECT categoryID, name FROM ' . $xoopsDB->prefix('lxcategories') . ' WHERE categoryID = ' . $myrow['categoryID'] . ' ');
            list($categoryID, $name) = $xoopsDB->fetchRow($resultY);
            $random['categoryname'] = $myts->displayTarea($name);
        }
    }
    $microlinks = $utility::getServiceLinks($random);
    $xoopsTpl->assign('random', $random);
}

if ($xoopsUser && $xoopsUser->isAdmin()) {
    // To display the submitted and requested terms box
    $xoopsTpl->assign('userisadmin', 1);

    $blockS      = [];
    $resultS     = $xoopsDB->query('SELECT entryID, term FROM ' . $xoopsDB->prefix('lxentries') . ' WHERE datesub < ' . time() . " AND datesub > 0 AND submit = '1' AND offline = '1' AND request = '0' ORDER BY term");
    $totalSwords = $xoopsDB->getRowsNum($resultS);

    if ($totalSwords > 0) { // If there are definitions
        while (list($entryID, $term) = $xoopsDB->fetchRow($resultS)) {
            $subentries             = [];
            $xoopsModule            = XoopsModule::getByDirname('lexikon');
            $linktext               = mb_ucfirst($myts->htmlSpecialChars($term));
            $subentries['linktext'] = $linktext;
            $subentries['id']       = $entryID;

            $blockS['substuff'][] = $subentries;
        }
        $xoopsTpl->assign('blockS', $blockS);
        $xoopsTpl->assign('wehavesubs', 1);
    } else {
        $xoopsTpl->assign('wehavesubs', 0);
    }

    $blockR      = [];
    $resultR     = $xoopsDB->query('SELECT entryID, term FROM ' . $xoopsDB->prefix('lxentries') . ' WHERE datesub < ' . time() . " AND datesub > 0 AND request = '1' ORDER BY term");
    $totalRwords = $xoopsDB->getRowsNum($resultR);

    if ($totalRwords > 0) { // If there are definitions
        while (list($entryID, $term) = $xoopsDB->fetchRow($resultR)) {
            $reqentries             = [];
            $xoopsModule            = XoopsModule::getByDirname('lexikon');
            $linktext               = mb_ucfirst($myts->htmlSpecialChars($term));
            $reqentries['linktext'] = $linktext;
            $reqentries['id']       = $entryID;

            $blockR['reqstuff'][] = $reqentries;
        }
        $xoopsTpl->assign('blockR', $blockR);
        $xoopsTpl->assign('wehavereqs', 1);
    } else {
        $xoopsTpl->assign('wehavereqs', 0);
    }
} else {
    $xoopsTpl->assign('userisadmin', 0);
    $blockR      = [];
    $resultR     = $xoopsDB->query('SELECT entryID, term FROM ' . $xoopsDB->prefix('lxentries') . ' WHERE datesub < ' . time() . " AND datesub > 0 AND request = '1' " . $catperms . ' ORDER BY term');
    $totalRwords = $xoopsDB->getRowsNum($resultR);

    if ($totalRwords > 0) { // If there are definitions
        while (list($entryID, $term) = $xoopsDB->fetchRow($resultR)) {
            $reqentries             = [];
            $xoopsModule            = XoopsModule::getByDirname('lexikon');
            $linktext               = mb_ucfirst($myts->htmlSpecialChars($term));
            $reqentries['linktext'] = $linktext;
            $reqentries['id']       = $entryID;

            $blockR['reqstuff'][] = $reqentries;
        }
        $xoopsTpl->assign('blockR', $blockR);
        $xoopsTpl->assign('wehavereqs', 1);
    } else {
        $xoopsTpl->assign('wehavereqs', 0);
    }
}
// Various strings
$xoopsTpl->assign('lang_modulename', $xoopsModule->name());
$xoopsTpl->assign('lang_moduledirname', $xoopsModule->getVar('dirname'));
if (0 != $publishedwords) {
    $xoopsTpl->assign('microlinks', $microlinks);
    $xoopsTpl->assign('showdate', (int)$xoopsModuleConfig['showdate']);
    $xoopsTpl->assign('showcount', (int)$xoopsModuleConfig['showcount']);
}
$xoopsTpl->assign('alpha', $alpha);
$xoopsTpl->assign('teaser', $utility::getModuleOption('teaser'));
if (1 == $xoopsModuleConfig['syndication']) {
    $xoopsTpl->assign('syndication', true);
}
if ($xoopsUser) {
    $xoopsTpl->assign('syndication', true);
}
$xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($xoopsModule->name()));

// Meta data
$meta_description = $myts->htmlSpecialChars($xoopsModule->name());
if (isset($xoTheme) && is_object($xoTheme)) {
    $xoTheme->addMeta('meta', 'description', $meta_description);
} else {
    $xoopsTpl->assign('xoops_meta_description', $meta_description);
}
$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" href="assets/css/style.css">');

include XOOPS_ROOT_PATH . '/footer.php';
