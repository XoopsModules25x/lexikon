<?php
/**
 * Module: Lexikon - glossary module
 * Author: hsalazar
 * Licence: GNU
 */

defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * @param $options
 * @return array
 */
function b_lxentries_new_show($options)
{
    global $xoopsDB, $xoopsUser;

    $myts = MyTextSanitizer:: getInstance();

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

    $words      = $xoopsDB->query('SELECT entryID FROM ' . $xoopsDB->prefix('lxentries') . " WHERE offline = '0' AND submit='0' AND request='0' AND block = '1' ");
    $totalwords = $xoopsDB->getRowsNum($words);

    $block              = [];
    $block['marquee']   = ($options[2] == 1) ? 1 : 0;
    $block['alternate'] = ($options[3] == 1) ? 1 : 0;
    $block['showdate']  = ($options[4] == 1) ? 1 : 0;
    $block['direction'] = $options[5];
    $block['speed']     = isset($options[6]) && $options[6] != '' ? $options[6] : '2';
    $block['bgcolor']   = isset($options[7]) && $options[7] != '' ? $options[7] : '#FFFFFF';

    $sql    = 'SELECT entryID, categoryID, term, datesub FROM '
              . $xoopsDB->prefix('lxentries')
              . ' WHERE datesub < '
              . time()
              . " AND datesub > 0 AND offline = '0' "
              . $catperms
              . ' ORDER BY '
              . $options[0]
              . ' DESC';
    $result = $xoopsDB->query($sql, $options[1], 0);

    if ($totalwords > 0) { // If there are definitions
        while (list($entryID, $categoryID, $term, $datesub) = $xoopsDB->fetchRow($result)) {
            $newentries             = [];
            $linktext               = ucfirst($myts->htmlSpecialChars($term));
            $newentries['dir']      = $lexikon->dirname();
            $newentries['linktext'] = $linktext;
            $newentries['id']       = (int)$entryID;
            $newentries['date']     = formatTimestamp($datesub, $lxConfig['dateformat']);

            $block['newstuff'][] = $newentries;
        }
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_lxentries_new_edit($options)
{
    $form = "<table style='width:100%;' class='bg2'>";
    $form .= "<tr><th style='width:50%'>" . _OPTIONS . "</th><th style='width:50%'>" . _MB_LEXIKON_SETTINGS . '</th></tr>';
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_ORDER . "</td><td class='odd'>";
    $form .= "&nbsp;<select name='options[0]'>";
    $form .= "<option value='datesub' " . (($options[0] === 'datesub') ? ' selected' : '') . '>' . _MB_LEXIKON_DATE . "</option>\n";
    $form .= "<option value='counter' " . (($options[0] === 'counter') ? ' selected' : '') . '>' . _MB_LEXIKON_HITS . "</option>\n";
    $form .= "<option value='term' " . (($options[0] === 'term') ? ' selected' : '') . '>' . _MB_LEXIKON_NAME . "</option>\n";
    $form .= "</select><br></td></tr>";
    //---
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_DISP . "</td><td class='odd'><input type='text' name='options[]' value='" . $options[1] . "' />&nbsp;" . _MB_LEXIKON_TERMS . '<br></td></tr>';
    //---
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_MARQUEE . "</td><td class='odd'>";
    $form .= "<input type='radio' name='options[2]' value='1'" . (($options[2] == 1) ? ' checked' : '') . ' />' . _YES . '&nbsp;';
    $form .= "<input type='radio' name='options[2]' value='0'" . (($options[2] == 0) ? ' checked' : '') . ' />' . _NO . '<br></td></tr>';
    //---
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_ALTERNATE . "</td><td class='odd'>";
    $form .= "<input type='radio' name='options[3]' value='1'" . (($options[3] == 1) ? ' checked' : '') . ' />' . _YES . '&nbsp;';
    $form .= "<input type='radio' name='options[3]' value='0'" . (($options[3] == 0) ? ' checked' : '') . ' />' . _NO . '<br></td></tr>';
    $form .= '</td></tr>';
    //---
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_SHOWDATE . "</td><td class='odd'>";
    $form .= "<input type='radio' name='options[4]' value='1'" . (($options[4] == 1) ? ' checked' : '') . ' />' . _YES . '&nbsp;';
    $form .= "<input type='radio' name='options[4]' value='0'" . (($options[4] == 0) ? ' checked' : '') . ' />' . _NO . '<br></td></tr>';
    //---
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_DIRECTION . "</td><td class='odd'><select name='options[5]'>";
    $form .= "<option value='up' " . (($options[5] === 'up') ? ' selected' : '') . '>' . _MB_LEXIKON_UP . "</option>\n";
    $form .= "<option value='down' " . (($options[5] === 'down') ? ' selected' : '') . '>' . _MB_LEXIKON_DOWN . "</option>\n";
    $form .= "</select></td></tr>";
    //---
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_BSPEED . "</td><td class='odd'><input type='text' name='options[6]' size='16' maxlength=2 value='" . $options[6] . "' /></td></tr>";
    $form .= "<tr><td class='even'>" . _MB_LEXIKON_BACKGROUNDCOLOR . "</td><td class='odd'><input type='text' name='options[7]' size='16'  value='" . $options[7] . "' /></td></tr>";
    $form .= '</table>';
    //----
    return $form;
}
