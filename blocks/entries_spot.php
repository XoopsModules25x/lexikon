<?php
/**
 * Module: Lexikon
 * credits: hsalazar, Smartfactory, Eric Juden & ackbarr ->Project XHelp
 * Licence: GNU
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

/* This function spotlights a category, with a spotlight definition and links to others */
/**
 * @param $options
 * @return array
 */
function b_lxspot_show($options)
{
    global $xoopsDB, $xoopsUser;
    $myts = MyTextSanitizer:: getInstance();
    xoops_load('XoopsUserUtility');

    $module_name = 'lexikon';
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $lexikon       = $moduleHandler->getByDirname('lexikon');
    if (!isset($lxConfig)) {
        $configHandler = xoops_getHandler('config');
        $lxConfig      = $configHandler->getConfigsByCat(0, $lexikon->getVar('mid'));
    }

    $gpermHandler = xoops_getHandler('groupperm');
    $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $module_id    = $lexikon->getVar('mid');

    $block = [];

    // To handle options in the template
    if (1 == $options[2]) {
        $block['showdateask'] = 1;
    } else {
        $block['showdateask'] = 0;
    }
    if (1 == $options[3]) {
        $block['showbylineask'] = 1;
    } else {
        $block['showbylineask'] = 0;
    }
    if (1 == $options[4]) {
        $block['showstatsask'] = 1;
    } else {
        $block['showstatsask'] = 0;
    }
    if ('ver' === $options[5]) {
        $block['verticaltemplate'] = 1;
    } else {
        $block['verticaltemplate'] = 0;
    }
    if (1 == $options[6]) {
        $block['showpicask'] = 1;
    } else {
        $block['showpicask'] = 0;
    }

    // Retrieve the latest terms in the selected category
    $resultA = $xoopsDB->query('SELECT entryID, categoryID, term, definition, uid, datesub, counter, html, smiley, xcodes, breaks, comments
                                 FROM ' . $xoopsDB->prefix('lxentries') . '
                                 WHERE categoryID = ' . $options[0] . " AND submit = '0' AND offline = 0 AND block= 1
                                 ORDER BY datesub DESC", //ORDER BY " . $options[7] . " DESC ",
                               1, 0);

    list($entryID, $categoryID, $term, $definition, $authorID, $datesub, $counter, $html, $smiley, $xcodes, $breaks, $comments) = $xoopsDB->fetchRow($resultA);
    $eID = (int)$entryID;
    // If there's no result - which means there's no definition yet...
    if (0 == $eID) {
        $block['display'] = 0;
    } else {
        $block['display'] = 1;
    }

    // Retrieve the category name
    $resultB = $xoopsDB->query('SELECT name, logourl FROM ' . $xoopsDB->prefix('lxcategories') . ' WHERE categoryID = ' . $options[0] . ' ');
    list($name, $logourl) = $xoopsDB->fetchRow($resultB);
    if ($lexikon = $moduleHandler->getByDirname('lexikon')) {
        if ($gpermHandler->checkRight('lexikon_view', $options[0], $groups, $module_id)) {
            // get the items
            $block['userID']     = ((int)$authorID);
            $block['authorname'] = XoopsUserUtility::getUnameFromId((int)$authorID);
            $block['name']       = xoops_substr($name, 0, (int)$options[9]);
            $block['catID']      = (int)$options[0];
            $block['catimage']   = stripslashes($logourl);
            $block['termID']     = (int)$entryID;
            $block['title']      = $myts->htmlSpecialChars($term);
            $block['introtext']  = xoops_substr($myts->displayTarea($definition, $html, 1, $xcodes, 1, $breaks), 0, (int)$options[8]);

            $block['moduledir'] = $lexikon->dirname();
            $block['date']      = formatTimestamp($datesub, 'd M Y');
            //$block['date'] = formatTimestamp( $datesub, $lxConfig['dateformat'] );
            $block['hits'] = (int)$counter;
            if ((0 != $lxConfig['com_rule']) || ((0 != $lxConfig['com_rule']) && is_object($xoopsUser))) {
                if (0 != $comments) {
                    $block['comments'] = "<a href='" . XOOPS_URL . '/modules/' . $lexikon->dirname() . '/entry.php?entryID=' . $block['termID'] . "'>" . _COMMENTS . '&nbsp;:&nbsp; ' . $comments . '</a>';
                } else {
                    $block['comments'] = "<a href='" . XOOPS_URL . '/modules/' . $lexikon->dirname() . '/entry.php?entryID=' . $block['termID'] . "'>" . _COMMENTS . '?</a>';
                }
            }

            // get the other terms
            $resultC = $xoopsDB->query('SELECT entryID, term, datesub FROM ' . $xoopsDB->prefix('lxentries') . ' WHERE categoryID = ' . $options[0] . ' AND entryID != ' . $block['termID'] . ' AND submit = 0 AND offline = 0 AND block= 1 ORDER BY ' . $options[7] . ' DESC ', $options[1], 0);

            $i = 0;
            while ($myrow = $xoopsDB->fetchArray($resultC)) {
                if ($i < $options[1]) {
                    $morelinks         = [];
                    $morelinks['id']   = $myrow['entryID'];
                    $morelinks['head'] = xoops_substr($myts->htmlSpecialChars($myrow['term']), 0, (int)$options[9]);

                    $morelinks['subdate'] = formatTimestamp($datesub, 'd M Y');
                    ++$i;
                    $block['links'][] = $morelinks;
                }
            }
        } else { // if permissions are not granted
            $block['display'] = 0;
        }
    }
    //------------
    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_lxspot_edit($options)
{
    global $xoopsDB;
    $myts      = MyTextSanitizer:: getInstance();
    $resultcat = $xoopsDB->query('SELECT categoryID, name FROM ' . $xoopsDB->prefix('lxcategories') . ' ORDER BY categoryID');
    $form      = "<table border='0'>";
    $form      .= '<tr><td>' . _MB_LEXIKON_SELECTCAT . '</td><td><select name="options[]">';
    while (list($categoryID, $name) = $xoopsDB->fetchRow($resultcat)) {
        $form .= '<option value=' . $categoryID . ' ' . (($options[0] == $categoryID) ? ' selected' : '') . ">$categoryID : $name</option>\n";
    }
    $form .= "</select><br></td></tr>\n";

    $form .= '<tr><td>' . _MB_LEXIKON_TERMSTOSHOW . "</td><td><input type='text' name='options[]' value='" . $options[1] . "' >&nbsp; " . _MB_LEXIKON_TERMS . '.<br></td></tr>';

    $form .= '<tr><td>' . _MB_LEXIKON_SHOWDATE . '</td><td>';
    $form .= "<input type='radio' name='options[2]' value='1'" . ((1 == $options[2]) ? ' checked' : '') . ' >' . _YES . '&nbsp;';
    $form .= "<input type='radio' name='options[2]' value='0'" . ((0 == $options[2]) ? ' checked' : '') . ' >' . _NO . '<br></td></tr>';

    $form .= '<tr><td>' . _MB_LEXIKON_SHOWBYLINE . '</td><td>';
    $form .= "<input type='radio' name='options[3]' value='1'" . ((1 == $options[3]) ? ' checked' : '') . ' >' . _YES . '&nbsp;';
    $form .= "<input type='radio' name='options[3]' value='0'" . ((0 == $options[3]) ? ' checked' : '') . ' >' . _NO . '<br></td></tr>';

    $form .= '<tr><td>' . _MB_LEXIKON_SHOWSTATS . '</td><td>';
    $form .= "<input type='radio' name='options[4]' value='1'" . ((1 == $options[4]) ? ' checked' : '') . ' >' . _YES . '&nbsp;';
    $form .= "<input type='radio' name='options[4]' value='0'" . ((0 == $options[4]) ? ' checked' : '') . ' >' . _NO . '<br></td></tr>';

    $form .= '<tr><td>' . _MB_LEXIKON_TEMPLATE . "</td><td><select name='options[]'>";
    $form .= "<option value='ver' " . (('ver' === $options[5]) ? ' selected' : '') . '>' . _MB_LEXIKON_VERTICAL . "</option>\n";
    $form .= "<option value='hor' " . (('hor' === $options[5]) ? ' selected' : '') . '>' . _MB_LEXIKON_HORIZONTAL . "</option>\n";
    $form .= '</select><br></td></tr>';

    $form .= '<tr><td>' . _MB_LEXIKON_SHOWPIC . '</td><td>';
    $form .= "<input type='radio' name='options[6]' value='1'" . ((1 == $options[6]) ? ' checked' : '') . ' >' . _YES . '&nbsp;';
    $form .= "<input type='radio' name='options[6]' value='0'" . ((0 == $options[6]) ? ' checked' : '') . ' >' . _NO . '<br></td></tr>';

    $form .= '<tr><td>' . _MB_LEXIKON_ORDER . "</td><td>&nbsp;<select name='options[7]'>";
    $form .= "<option value='datesub' " . (('datesub' === $options[7]) ? ' selected' : '') . '>' . _MB_LEXIKON_DATE . "</option>\n";
    $form .= "<option value='counter' " . (('counter' === $options[7]) ? ' selected' : '') . '>' . _MB_LEXIKON_HITS . "</option>\n";
    $form .= "<option value='term' " . (('term' === $options[7]) ? ' selected' : '') . '>' . _MB_LEXIKON_NAME . "</option>\n";
    $form .= "</select>\n";

    $form .= "&nbsp;<tr><td style='vertical-align: top;'>" . _MB_LEXIKON_CHARS . "</td><td>&nbsp;<input type='text' name='options[8]' value='" . $myts->htmlSpecialChars($options[8]) . "' >&nbsp;" . _MB_LEXIKON_LENGTH . '';
    $form .= "&nbsp;<tr><td style='vertical-align: top;'>" . _MB_LEXIKON_CHARSTERM . "</td><td>&nbsp;<input type='text' name='options[9]' value='" . $myts->htmlSpecialChars($options[9]) . "' >&nbsp;" . _MB_LEXIKON_LENGTH . '';

    $form .= '</td></tr>';
    $form .= '</table>';

    //------------
    return $form;
}
