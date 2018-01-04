<?php
/**
 * Module: Lexikon - glossary module
 * Author: hsalazar
 * Licence: GNU
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

/**
 * @return array
 */
function b_lxentries_random_show()
{
    global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsModule;
    $myts = \MyTextSanitizer::getInstance();

    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $lexikon       = $moduleHandler->getByDirname('lexikon');

    if (!isset($lxConfig)) {
        $configHandler = xoops_getHandler('config');
        $lxConfig      = $configHandler->getConfigsByCat(0, $lexikon->getVar('mid'));
    }
    $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gpermHandler = xoops_getHandler('groupperm');
    $module_id    = $lexikon->getVar('mid');
    $allowed_cats = $gpermHandler->getItemIds('lexikon_view', $groups, $module_id);
    $catids       = implode(',', $allowed_cats);
    $catperms     = " AND categoryID IN ($catids) ";

    $adminlinks     = '';
    $block          = [];
    $block['title'] = _MB_LEXIKON_RANDOMTITLE;

    list($numrows) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(entryID) FROM ' . $xoopsDB->prefix('lxentries') . " WHERE offline= '0' AND block = '1' " . $catperms . ' '));

    if ($numrows > 1) {
        --$numrows;
        mt_srand((double)microtime() * 1000000);
        $entrynumber = mt_rand(0, $numrows);
    } else {
        $entrynumber = 0;
    }

    $result = $xoopsDB->query('SELECT entryID, categoryID, term, definition FROM ' . $xoopsDB->prefix('lxentries') . " WHERE offline = '0' AND block = '1' " . $catperms . " LIMIT $entrynumber, 1");

    while ($myrow = $xoopsDB->fetchArray($result)) {
        //$entryID = (int)($entryID);
        $entryID = (int)$myrow['entryID'];
        $term    = ucfirst($myts->displayTarea($myrow['term']));

        if (XOOPS_USE_MULTIBYTES) {
            $deftemp    = xoops_substr($myrow['definition'], 0, $lxConfig['rndlength'] - 1);
            $definition = $myts->displayTarea($deftemp, 1, 1, 1, 1, 1);
        }

        $categoryID = $myrow['categoryID'];
        $result_cat = $xoopsDB->query('SELECT categoryID, name FROM ' . $xoopsDB->prefix('lxcategories') . " WHERE categoryID = $categoryID");
        list($categoryID, $name) = $xoopsDB->fetchRow($result_cat);
        $categoryname = $myts->displayTarea($name);

        //TODO switch to central icons repository

        if ($xoopsUser) {
            if ($xoopsUser->isAdmin()) {
                $adminlinks = '<a href="'
                              . XOOPS_URL
                              . '/modules/'
                              . $lexikon->dirname()
                              . '/admin/entry.php?op=mod&entryID='
                              . $entryID
                              . '" target="_blank"><img src="'
                              . XOOPS_URL
                              . '/modules/'
                              . $lexikon->dirname()
                              . '/assets/images/edit.gif" alt="'
                              . _MB_LEXIKON_EDITTERM
                              . '" width="16" height="16" ></a>&nbsp;<a href="'
                              . XOOPS_URL
                              . '/modules/'
                              . $lexikon->dirname()
                              . '/admin/entry.php?op=del&entryID='
                              . $entryID
                              . '" target="_self"><img src="'
                              . XOOPS_URL
                              . '/modules/'
                              . $lexikon->dirname()
                              . '/assets/images/delete.gif" alt="'
                              . _MB_LEXIKON_DELTERM
                              . '" width="16" height="16" ></a>&nbsp;';
            }
        }
        $userlinks = '<a href="'
                     . XOOPS_URL
                     . '/modules/'
                     . $lexikon->dirname()
                     . '/print.php?entryID='
                     . $entryID
                     . '" target="_blank"><img src="'
                     . XOOPS_URL
                     . '/modules/'
                     . $lexikon->dirname()
                     . '/assets/images/print.gif" alt="'
                     . _MB_LEXIKON_PRINTTERM
                     . '" width="16" height="16" ></a>&nbsp;<a href="mailto:?subject='
                     . sprintf(_MB_LEXIKON_INTENTRY, $xoopsConfig['sitename'])
                     . '&amp;body='
                     . sprintf(_MB_LEXIKON_INTENTRYFOUND, $xoopsConfig['sitename'])
                     . ':&nbsp;'
                     . XOOPS_URL
                     . '/modules/'
                     . $lexikon->dirname()
                     . '/entry.php?entryID='
                     . $entryID
                     . '" target="_blank"><img src="'
                     . XOOPS_URL
                     . '/modules/'
                     . $lexikon->dirname()
                     . '/assets/images/friend.gif" alt="'
                     . _MB_LEXIKON_SENDTOFRIEND
                     . '" width="16" height="16" ></a>&nbsp;';

        if (1 == $lxConfig['multicats']) {
            $block['content'] = '<div style="font-size: 12px; font-weight: bold; background-color: #ccc; padding: 4px; margin: 0;"><a href="' . XOOPS_URL . '/modules/' . $lexikon->dirname() . "/category.php?categoryID=$categoryID\">$categoryname</a></div>";
            $block['content'] .= "<div style=\"padding: 4px 0 0 0; color: #456;\"><h5 style=\"margin: 0;\">$adminlinks $userlinks <a href=\"" . XOOPS_URL . '/modules/' . $lexikon->dirname() . "/entry.php?entryID=$entryID\">$term</a></h5>$definition</div>";
        } else {
            $block['content'] = "<div style=\"padding: 4px; color: #456;\"><h5 style=\"margin: 0;\">$adminlinks $userlinks <a style=\"margin: 0;\" href=\"" . XOOPS_URL . '/modules/' . $lexikon->dirname() . "/entry.php?entryID=$entryID\">$term</a></h5>$definition</div>";
        }
    }

    $block['content'] .= '<div style="text-align: right; font-size: x-small;"><a href="' . XOOPS_URL . '/modules/' . $lexikon->dirname() . '/index.php">' . _MB_LEXIKON_SEEMORE . '</a></div>';

    return $block;
}
