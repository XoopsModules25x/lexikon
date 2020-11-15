<?php

/** entries_scrolling.php v.1
 * XOOPS - PHP Content Management System
 * Copyright (c) 2017 <https://xoops.org>
 *
 * Module: lexikon 1.5 beta
 * Author : Yerres
 * Licence : GPL
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * @param $options
 * @return array
 */
function b_scrolling_term_show($options)
{
    global $xoopsDB, $xoopsUser;
    $myts = MyTextSanitizer:: getInstance();

    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $lexikon       = $moduleHandler->getByDirname('lexikon');
    if (!isset($lxConfig)) {
        /** @var \XoopsConfigHandler $configHandler */
        $configHandler = xoops_getHandler('config');
        $lxConfig      = $configHandler->getConfigsByCat(0, $lexikon->getVar('mid'));
    }
    require_once XOOPS_ROOT_PATH . '/modules/lexikon/class/Utility.php';

    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    /** @var \XoopsGroupPermHandler $grouppermHandler */
    $grouppermHandler = xoops_getHandler('groupperm');
    $module_id        = $lexikon->getVar('mid');
    $allowed_cats     = $grouppermHandler->getItemIds('lexikon_view', $groups, $module_id);

    $block                = [];
    $block['speed']       = isset($options[1]) && '' != $options[1] ? $options[1] : '';
    $block['bgcolor']     = isset($options[2]) && '' != $options[2] ? $options[2] : '#FFFFFF';
    $block['direction']   = $options[3];
    $block['alternate']   = isset($options[4]) ? 1 : 0;
    $block['includedate'] = isset($options[6]) ? 1 : 0;
    $block['style']       = $options[7];

    if (!empty($options[10])) {
        $categories = array_filter(array_slice($options, 10));
    } else {
        $categories = $allowed_cats;
    }
    $categories = array_intersect($categories, $allowed_cats);
    $categories = implode(',', $categories);
    if (0 == count($categories)) {
        return $block;
    }

    $sql    = $xoopsDB->query(
        '
      SELECT entryID, term, definition, datesub, html
      FROM ' . $xoopsDB->prefix('lxentries') . '
      WHERE datesub < ' . time() . " AND datesub > 0 AND offline = '0' AND submit = '0' AND request = '0' AND  categoryID IN (" . $categories . ')
      ORDER BY ' . $options[8] . ' ' . $options[9] . '
      LIMIT 0, ' . $options[0] . ' '
    );
    $totals = $xoopsDB->getRowsNum($sql);

    if ($totals > 1) {
        while (list($entryID, $term, $definition, $datesub, $html) = $xoopsDB->fetchRow($sql)) {
            $items         = [];
            $userlink      = '<a style="cursor:help;background-color: transparent;" href=\"' . XOOPS_URL . '/modules/' . $lexikon->dirname() . '/entry.php?entryID=' . (int)$entryID . '\">';
            $items['id']   = (int)$entryID;
            $items['term'] = htmlspecialchars($term);
            if ($options[5] > 0) {
                $html                = 1 == $html ? 1 : 0;
                $definition          = preg_replace("/'/", '’', $definition);
                $items['definition'] = $utility::truncateTagSafe($myts->displayTarea($definition, $html), $options[5] + 3);
            } else {
                $items['definition'] = '';
            }
            if ('1' == $options[6]) {
                $items['date'] = formatTimestamp($datesub, $lxConfig['dateformat']);
            }
            $items['url']           = $userlink;
            $block['scrollitems'][] = $items;
        }
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_scrolling_term_edit($options)
{
    global $xoopsDB;
    $myts = MyTextSanitizer:: getInstance();
    $form = "<table style='width:100%' class='bg2'>";
    $form .= "<tr><th style='width:50%'>" . _OPTIONS . "</th><th style='width:50%'>" . _MB_LEXIKON_SETTINGS . '</th></tr>';
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_BLIMIT . "</td><td class='odd'><input type='text' name='options[0]' size='16' maxlength=3 value='" . $options[0] . "' ></td></tr>";
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_BSPEED . "</td><td class='odd'><input type='text' name='options[1]' size='16' maxlength=2 value='" . $options[1] . "' ></td></tr>";
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_BACKGROUNDCOLOR . "</td><td class='odd'><input type='text' name='options[2]' size='16'  value='" . $options[2] . "' ></td></tr>";
    //---
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_DIRECTION . "</td><td class='odd'><select name='options[3]'>";
    $form .= "<option value='up' " . (('up' === $options[3]) ? ' selected' : '') . '>' . _MB_LEXIKON_UP . "</option>\n";
    $form .= "<option value='down' " . (('down' === $options[3]) ? ' selected' : '') . '>' . _MB_LEXIKON_DOWN . "</option>\n";
    $form .= '</select></td></tr>';
    //---
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_ALTERNATE . "</td><td class='odd'>";
    $form .= "<input type='radio' name='options[4]' value='1'" . ((1 == $options[4]) ? ' checked' : '') . ' >' . _YES . '&nbsp;';
    $form .= "<input type='radio' name='options[4]' value='0'" . ((0 == $options[4]) ? ' checked' : '') . ' >' . _NO . '<br></td></tr>';
    //---
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_CHARS . " </td><td class='odd'><input type='text' name='options[5]' value='" . $options[5] . "' ></td></tr>";
    //---
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_TERMSTOSHOW . ' ' . _MB_LEXIKON_SHOWDATE . "</td><td class='odd'>";
    $form .= "<input type='radio' name='options[6]' value='1'" . ((1 == $options[6]) ? ' checked' : '') . ' >' . _YES . '&nbsp;';
    $form .= "<input type='radio' name='options[6]' value='0'" . ((0 == $options[6]) ? ' checked' : '') . ' >' . _NO . '<br></td></tr>';
    //---
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_DISP . "</td><td class='odd'><select name='options[7]'>";
    $form .= "<option value='0' " . (('0' == $options[7]) ? ' selected' : '') . '>' . _MB_LEXIKON_MARQUEE . "</option>\n";
    $form .= "<option value='1' " . (('1' == $options[7]) ? ' selected' : '') . '>' . _MB_LEXIKON_PAUSESCROLLER . "</option>\n";
    $form .= "<option value='2' " . (('2' == $options[7]) ? ' selected' : '') . '>' . _MB_LEXIKON_DOMTICKER . "</option>\n";
    $form .= '</select></td></tr>';
    //---
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_SORT . "</td><td class='odd'><select name='options[8]'>";
    $form .= "<option value='RAND()' " . (('RAND()' === $options[8]) ? ' selected' : '') . '>' . _MB_LEXIKON_RANDOM . "</option>\n";
    $form .= "<option value='datesub' " . (('datesub' === $options[8]) ? ' selected' : '') . '>' . _MB_LEXIKON_DATE . "</option>\n";
    $form .= "<option value='counter' " . (('counter' === $options[8]) ? ' selected' : '') . '>' . _MB_LEXIKON_HITS . "</option>\n";
    $form .= "<option value='term' " . (('term' === $options[8]) ? ' selected' : '') . '>' . _MB_LEXIKON_NAME . "</option>\n";
    $form .= '</select></td></tr>';
    //---
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_ORDER . "</td><td class='odd'><select name='options[9]'>";
    $form .= "<option value='ASC' " . (('ASC' === $options[9]) ? ' selected' : '') . '>' . _ASCENDING . "</option>\n";
    $form .= "<option value='DESC' " . (('DESC' === $options[9]) ? ' selected' : '') . '>' . _DESCENDING . "</option>\n";
    $form .= '</select></td></tr>';
    //--- get allowed categories
    $isAll       = empty($options[10]) ? true : false;
    $options_cat = array_slice($options, 10);
    $form        .= "<tr><td class='even'>" . _MB_LEXIKON_CATEGORY . "</td><td class='odd'><select name=\"options[]\" multiple=\"multiple\">";
    $form        .= '<option value="0" ';
    if ($isAll) {
        $form .= ' selected="selected"';
    }
    $form      .= '>' . _ALL . '</option>';
    $resultcat = $xoopsDB->query('SELECT categoryID, name FROM ' . $xoopsDB->prefix('lxcategories') . ' ORDER BY categoryID ASC');
    while (list($categoryID, $name) = $xoopsDB->fetchRow($resultcat)) {
        $sel  = ($isAll || in_array($categoryID, $options_cat)) ? ' selected' : '';
        $form .= '<option value=' . $categoryID . " $sel>$categoryID : $name</option>\n";
    }
    $form .= '</select></td></tr>';
    $form .= '</table>';
    //--------
    return $form;
}
