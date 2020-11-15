<?php
/**
 * Module: Lexikon - glossary module
 * orig. Author: nagl@dictionary
 * Licence: GNU
 */

use Xmf\Request;
use XoopsModules\Lexikon\{
    Helper,
    Utility
};
/** @var Helper $helper */

require __DIR__ . '/header.php';
require XOOPS_ROOT_PATH . '/header.php';

xoops_header(false);


$helper = Helper::getInstance();

$entryID = isset($_GET['entryID']) ? Request::getInt('entryID', 0, 'GET') : 0;
if (!$entryID) {
    exit();
}

$myts = \MyTextSanitizer::getInstance();

$sqlQuery = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('lxentries') . " WHERE entryID=$entryID");
$sqlfetch = $xoopsDB->fetchArray($sqlQuery);
if (1 == $helper->getConfig('multicats')) {
    $cID       = $sqlfetch['categoryID'];
    $sqlquery2 = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('lxcategories') . " WHERE categoryID = $cID");
    $sqlfetch2 = $xoopsDB->fetchArray($sqlquery2);
    $catname   = htmlspecialchars($sqlfetch2['name']);
}
$term       = htmlspecialchars($sqlfetch['term']);
$definition = $myts->displayTarea($sqlfetch['definition'], $sqlfetch['html'], $sqlfetch['smiley'], 1, 1, 1);

echo '</head><body>
    <table width="100%" class="outer">
      <tr>
         <th class="head">' . $term . '</th>
      </tr>
    </table>';
if (1 == $helper->getConfig('multicats')) {
    echo '<div class="itemBody">' . _MD_LEXIKON_ENTRYCATEGORY . '' . $catname . '</div>';
}
echo '<div class="itemBody"><p class="itemText">' . $definition . '</p></div>
    <div style="text-align:center;"><p><input value="' . _CLOSE . '" type="button" onclick="window.close();"></p></div>';
