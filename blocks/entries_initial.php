<?php
/**
 * $Id: entries_initial.php v 1.0 25 May 2011 Yerres Exp $
 * Module: Lexikon
 * Version: v 1.00
 * Release Date: 25 May 2011
 * Author: Yerres
 * adapted from xwords
 * Licence: GNU
 */
if( ! defined( 'XOOPS_ROOT_PATH' ) ) die( 'XOOPS root path not defined' ) ;

function b_lxentries_alpha_show($options) {
    global $xoopsDB, $xoopsUser;
    $myts = MyTextSanitizer::getInstance();

    $module_handler = xoops_gethandler('module');
    $lexikon = $module_handler->getByDirname('lexikon');
    if (!isset($lxConfig)) {
        $config_handler = xoops_gethandler('config');
        $lxConfig = $config_handler->getConfigsByCat(0, $lexikon->getVar('mid'));
    }
  $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gperm_handler = xoops_gethandler('groupperm');
    $module_id = $lexikon->getVar('mid');
    $allowed_cats = $gperm_handler->getItemIds("lexikon_view", $groups, $module_id);
    $catids = implode(',', $allowed_cats);
    $catperms = " AND categoryID IN ($catids) ";
        
    $block = array();
    // To handle options in the template
    if ( $options[0] == 1 ) {
        $block['layout'] = 1;
    }
    else {
        $block['layout'] = 0;
    }
    if ( $options[1]) {
        $block['number'] = $options[1];
    }
    else {
        $block['number'] = 8;
    }
    $block['title'] = _MB_LEXIKON_TERMINITIAL;
    $block['moduledirname'] = $lexikon->dirname();
    $count = 0;

    foreach(range("A","Z") as $chr) {
        $letterlinks = array();
        $initial = $chr;
        $count++;
        $sql = $xoopsDB -> query ( "SELECT init FROM " . $xoopsDB -> prefix ( "lxentries") . " WHERE init = '$initial' AND datesub < '".time()."' AND datesub > '0' AND offline= '0' AND submit='0' AND request='0' ".$catperms." " );
        $howmany = $xoopsDB -> getRowsNum( $sql );
        $letterlinks['total'] = $howmany;
        $letterlinks['id'] = $chr;
        $letterlinks['linktext'] = $chr;
        $letterlinks['count'] = intval($count);

        $block['initstuff'][] = $letterlinks;
        
    }

    return $block;
}

function b_lxentries_alpha_edit( $options ) {
    
    $form = _ALIGN;
    $form .= "<input type='radio' name='options[0]' value='1'".(($options[0]==1)?" checked='checked'":"")." />"._YES."&nbsp;";
    $form .= "<input type='radio' name='options[0]' value='0'".(($options[0]==0)?" checked='checked'":"")." />"._NO."<br/>";

    $form .= ""._MB_LEXIKON_LETTERS." <input type='text' name='options[]' value='" . $options[1] . "' />&nbsp; <br />";

    //------------
    
    return $form;
}
