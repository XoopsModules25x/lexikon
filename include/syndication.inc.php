<?php
/**
 *
 * Module: Lexikon
 * Author: Yerres
 * Licence: GNU
 */

global $xoopsUser, $xoopsModule, $xoopsModuleConfig;

include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$yform = new \XoopsThemeForm(_MD_LEXIKON_SUB_SYNNAME, 'yform', '');

$myts       = \MyTextSanitizer::getInstance();
$syncode    = sprintf($myts->htmlSpecialChars(_MD_LEXIKON_SYNCODE), XOOPS_URL, $xoopsModule->dirname());
$sync_block = new \XoopsformTextArea(_MD_LEXIKON_SYNDICATION, 'txt', $syncode, 5, 60);
$sync_block->setExtra('readonly wrap=virtual style="font-size: 8pt; font-family: verdana,arial; border: 1px solid #ccc" ');
$yform->addElement($sync_block);
