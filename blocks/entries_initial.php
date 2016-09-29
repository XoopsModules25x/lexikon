<?php
/**
 *
 * Module: Lexikon
 * Version: v 1.00
 * Release Date: 25 May 2011
 * Author: Yerres
 * adapted from xwords
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * @param $options
 * @return array
 */
function b_lxentries_alpha_show($options)
{
    global $xoopsDB, $xoopsUser;
    $myts = MyTextSanitizer::getInstance();

    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $lexikon       = $moduleHandler->getByDirname('lexikon');
    if (!isset($lxConfig)) {
        $configHandler = xoops_getHandler('config');
        $lxConfig       =& $configHandler->getConfigsByCat(0, $lexikon->getVar('mid'));
    }
    $groups        = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gpermHandler = xoops_getHandler('groupperm');
    $module_id     = $lexikon->getVar('mid');
    $allowed_cats  = $gpermHandler->getItemIds('lexikon_view', $groups, $module_id);
    $catids        = implode(',', $allowed_cats);
    $catperms      = " AND categoryID IN ($catids) ";

    $block = array();
    // To handle options in the template
    if ($options[0] == 1) {
        $block['layout'] = 1;
    } else {
        $block['layout'] = 0;
    }
    if ($options[1]) {
        $block['number'] = $options[1];
    } else {
        $block['number'] = 8;
    }
    $block['title']         = _MB_LEXIKON_TERMINITIAL;
    $block['moduledirname'] = $lexikon->dirname();
    $count                  = 0;

    foreach (range('A', 'Z') as $chr) {
        $letterlinks = array();
        $initial     = $chr;
        ++$count;
        $sql                     = $xoopsDB->query('SELECT init FROM ' . $xoopsDB->prefix('lxentries') . " WHERE init = '$initial' AND datesub < '" . time() . "' AND datesub > '0' AND offline= '0' AND submit='0' AND request='0' " . $catperms . ' ');
        $howmany                 = $xoopsDB->getRowsNum($sql);
        $letterlinks['total']    = $howmany;
        $letterlinks['id']       = $chr;
        $letterlinks['linktext'] = $chr;
        $letterlinks['count']    = (int)$count;

        $block['initstuff'][] = $letterlinks;
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_lxentries_alpha_edit($options)
{
    $form = _ALIGN;
    $form .= "<input type='radio' name='options[0]' value='1'" . (($options[0] == 1) ? ' checked' : '') . ' />' . _YES . '&nbsp;';
    $form .= "<input type='radio' name='options[0]' value='0'" . (($options[0] == 0) ? ' checked' : '') . ' />' . _NO . '<br>';

    $form .= '' . _MB_LEXIKON_LETTERS . " <input type='text' name='options[]' value='" . $options[1] . "' />&nbsp; <br>";

    //------------
    return $form;
}
