<?php
/**
 * $Id: lexikon_authors.php v 1.0 8 May 2004 Yerres Exp $
 * Module: Lexikon -  glossary module
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: adapted from AMS
 * Licence: GNU
 */
if( ! defined( 'XOOPS_ROOT_PATH' ) ) die( 'XOOPS root path not defined' ) ;

function b_lx_author_show($options) {
    $myts =& MyTextSanitizer::getInstance();
    $module_handler = &xoops_gethandler('module');
    $lexikon = &$module_handler->getByDirname('lexikon');
    if (!isset($lxConfig)) {
        $config_handler = &xoops_gethandler('config');
        $lxConfig = &$config_handler->getConfigsByCat(0, $lexikon->getVar('mid'));
    }    
    include_once XOOPS_ROOT_PATH.'/modules/lexikon/include/functions.php';
    
    $block = array();
    if (!isset($options[3])) $options[3] = "average";
    $authors = lexikon_block_getAuthors($options[1], $options[0], $options[2], $options[3]);
    if (is_array($authors) && count($authors) > 0) $block['authors'] = $authors;
    $block['profile'] = ($lxConfig['authorprofile'] == 1) ? 1:0;
    
    return $block;
}

function b_lx_author_edit($options) {
    include_once (XOOPS_ROOT_PATH."/class/xoopsformloader.php");
    $form = new XoopsFormElementTray('', '<br/>');
    
    $sort_select = new XoopsFormSelect(_MB_LEXIKON_ORDER, 'options[0]', $options[0]);
    $sort_select->addOption('count', _MB_LEXIKON_TERMSCOUNT);
    $sort_select->addOption('read', _MB_LEXIKON_HITS);
    $form->addElement($sort_select);
    
    $form->addElement(new XoopsFormText(_MB_LEXIKON_DISP, 'options[1]', 20, 15, $options[1]));
    
    $name_select = new XoopsFormSelect(_MB_LEXIKON_DISPLAYNAME, 'options[2]', $options[2]);
    $name_select->addOption('uname', _MB_LEXIKON_USERNAME);
    $name_select->addOption('name', _MB_LEXIKON_REALNAME);
    $form->addElement($name_select);
    
    $average_select = new XoopsFormSelect(_MB_LEXIKON_COMPUTING, 'options[3]', $options[3]);
    $average_select->addOption('average', _MB_LEXIKON_AVERAGE);
    $average_select->addOption('total', _MB_LEXIKON_TOTALS);
    $form->addElement($average_select);
    return $form->render();
}
?>