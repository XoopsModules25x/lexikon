<?php
/**
 * Module: Lexikon - glossary module
 * Author: hsalazar
 * Licence: GNU
 */

global $xoopsModule;
include __DIR__ . '/../../mainfile.php';
xoops_load('XoopsRequest');

require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/Utility.php';
$myts = MyTextSanitizer:: getInstance();
