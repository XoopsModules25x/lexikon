<?php
/**
 * Module: Lexikon -  glossary module
 * Author: adapted from AMS
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * @param $options
 * @return array
 */
function b_lx_author_show($options)
{
    $myts = MyTextSanitizer::getInstance();
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $lexikon       = $moduleHandler->getByDirname('lexikon');
    if (!isset($lxConfig)) {
        $configHandler = xoops_getHandler('config');
        $lxConfig      = $configHandler->getConfigsByCat(0, $lexikon->getVar('mid'));
    }
    include_once XOOPS_ROOT_PATH . '/modules/lexikon/class/Utility.php';

    $block = array();
    if (!isset($options[3])) {
        $options[3] = 'average';
    }
    $authors = LexikonUtility::getBlockAuthors($options[1], $options[0], $options[2], $options[3]);
    if (is_array($authors) && count($authors) > 0) {
        $block['authors'] = $authors;
    }
    $block['profile'] = ($lxConfig['authorprofile'] == 1) ? 1 : 0;

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_lx_author_edit($options)
{
    include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $form = new XoopsFormElementTray('', '<br>');

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
