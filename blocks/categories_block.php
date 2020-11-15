<?php

/**
 * Module: Lexikon -  glossary module
 * Author: Yerres
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * @param $options
 * @return array
 */
function b_lxcategories_show($options)
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
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    /** @var \XoopsGroupPermHandler $grouppermHandler */
    $grouppermHandler = xoops_getHandler('groupperm');
    $module_id        = $lexikon->getVar('mid');
    $allowed_cats     = $grouppermHandler->getItemIds('lexikon_view', $groups, $module_id);
    $catids           = implode(',', $allowed_cats);
    $catperms         = " categoryID IN ($catids) ";

    $cats      = $grouppermHandler->getItemIds('lexikon_view', $groups, $module_id);
    $totalcats = count($cats);

    $block  = [];
    $sql    = 'SELECT categoryID, name, total FROM ' . $xoopsDB->prefix('lxcategories') . ' WHERE ' . $catperms . ' ORDER BY ' . $options[0] . ' DESC';
    $result = $xoopsDB->query($sql, $options[1], 0);

    if ($totalcats > 0) { // If there are categories
        while (list($categoryID, $name, $total) = $xoopsDB->fetchRow($result)) {
            $catlist             = [];
            $linktext            = htmlspecialchars($name);
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
    $form .= "<option value='weight' " . (('weight' === $options[0]) ? ' selected' : '') . '>' . _MB_LEXIKON_WEIGHT . "</option>\n";
    $form .= "<option value='name' " . (('name' === $options[0]) ? ' selected' : '') . '>' . _MB_LEXIKON_NAME . "</option>\n";
    $form .= "<option value='total' " . (('total' === $options[0]) ? ' selected' : '') . '>' . _MB_LEXIKON_TOTAL . "</option>\n";
    $form .= "</select>\n<br>";
    $form .= '&nbsp;' . _MB_LEXIKON_DISP . "&nbsp;<input type='text' name='options[]' value='" . $options[1] . "' >&nbsp;" . _MB_LEXIKON_CATS . '';

    return $form;
}
