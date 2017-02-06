<?php
/**
 *
 * Module: Lexikon -  glossary module
 * Version: v 1.00
 * Release Date: 29 May 2011
 * Author: Yerres
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * @param $options
 * @return array
 */
function b_lxcategories_show($options)
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
    $catperms     = " categoryID IN ($catids) ";

    $cats      = $gpermHandler->getItemIds('lexikon_view', $groups, $module_id);
    $totalcats = count($cats);

    $block = array();
    $sql   = 'SELECT categoryID, name, total FROM ' . $xoopsDB->prefix('lxcategories') . ' WHERE ' . $catperms . ' ORDER BY ' . $options[0] . ' DESC';
    //xoops 2.0.13
    //$sql = "SELECT a.categoryID, a.name, a.total, b.* FROM " . $xoopsDB->prefix("lxcategories") . " a, ".$xoopsDB->prefix('group_permission')." b WHERE a.categoryID = b.gperm_itemid AND b.gperm_modid = $module_id AND b.gperm_name = \"lexikon_view\" AND b.gperm_groupid = $groups[0]  ORDER BY " . $options[0] . " DESC ";
    $result = $xoopsDB->query($sql, $options[1], 0);

    if ($totalcats > 0) { // If there are categories
        while (list($categoryID, $name, $total) = $xoopsDB->fetchRow($result)) {
            $catlist             = array();
            $linktext            = $myts->htmlSpecialChars($name);
            $catlist['dir']      = $lexikon->dirname();
            $catlist['linktext'] = $linktext;
            $catlist['id']       = (int)$categoryID;
            $catlist['total']    = (int)$total;

            $block['catstuff'][] = $catlist;
        }
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_lxcategories_edit($options)
{
    $form = '' . _MB_LEXIKON_ORDER . "&nbsp;<select name='options[]'>";
    $form .= "<option value='weight' " . (($options[0] === 'weight') ? ' selected' : '') . '>' . _MB_LEXIKON_WEIGHT . "</option>\n";
    $form .= "<option value='name' " . (($options[0] === 'name') ? ' selected' : '') . '>' . _MB_LEXIKON_NAME . "</option>\n";
    $form .= "<option value='total' " . (($options[0] === 'total') ? ' selected' : '') . '>' . _MB_LEXIKON_TOTAL . "</option>\n";
    $form .= "</select>\n<br>";

    $form .= '&nbsp;' . _MB_LEXIKON_DISP . "&nbsp;<input type='text' name='options[]' value='" . $options[1] . "' />&nbsp;" . _MB_LEXIKON_CATS . '';

    return $form;
}
