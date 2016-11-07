<?php
/**
 *
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */

global $xoopsModule;
include __DIR__ . '/../../mainfile.php';
xoops_load('XoopsRequest');

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/functions.php';
$myts = MyTextSanitizer:: getInstance();
