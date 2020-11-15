<?php
/**
 * Module: Lexikon - glossary module
 * Author: hsalazar
 * Changes: Yerres
 * Licence: GNU
 */

use Xmf\Request;
use XoopsModules\Lexikon\{
    Helper,
    Utility
};
/** @var Helper $helper */

require __DIR__ . '/header.php';


$helper = Helper::getInstance();

//foreach ($_POST as $k => $v) {
//    ${$k} = $v;
//}
//
//foreach ($_GET as $k => $v) {
//    ${$k} = $v;
//}

$entryID = Request::getInt('entryID', '', 'GET');

if (empty($entryID)) {
    redirect_header('index.php');
}

/**
 * @param $entryID
 */
function printPage($entryID)
{
    global $xoopsConfig, $xoopsDB, $xoopsModule, $myts;

    $helper  = Helper::getInstance();
    $result1 = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('lxentries') . " WHERE entryID = '$entryID' and submit = '0' order by datesub");
    $Ok      = $xoopsDB->getRowsNum($result1);
    if ($Ok <= 0) {
        redirect_header('<script>javascript:history.go(-1)</script>', 3, _ERRORS);
    }
    [$entryID, $categoryID, $term, $init, $definition, $ref, $url, $uid, $submit, $datesub, $counter, $html, $smiley, $xcodes, $breaks, $block, $offline, $notifypub] = $xoopsDB->fetchRow($result1);

    $result2 = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('lxcategories') . " WHERE categoryID = '$categoryID'");
    [$name] = $xoopsDB->fetchRow($result2);

    $result3 = $xoopsDB->query('SELECT name, uname FROM ' . $xoopsDB->prefix('users') . " WHERE uid = '$uid'");
    [$authorname, $username] = $xoopsDB->fetchRow($result3);

    $datetime     = formatTimestamp($datesub, 'D, d-M-Y, H:i');
    $categoryname = htmlspecialchars($name);
    $term         = htmlspecialchars($term);
    $definition   = str_replace('[pagebreak]', '<br style="page-break-after:always;">', $definition);
    $definition   = &$myts->displayTarea($definition, $html, $smiley, $xcodes, '', $breaks);
    if ('' == $authorname) {
        $authorname = htmlspecialchars($username);
    } else {
        $authorname = htmlspecialchars($authorname);
    }
    echo "<!DOCTYPE HTML>\n";
    echo "<html>\n<head>\n";
    echo '<title>' . $xoopsConfig['sitename'] . ' ' . $term . ' ' . _MD_LEXIKON_PRINTTERM . "</title>\n";
    echo "<meta http-equiv='Content-Type' content='text/html; charset=" . _CHARSET . "'>\n";
    echo "<meta name='keywords' content= $term >\n";
    echo "<meta name='AUTHOR' content='" . $xoopsConfig['sitename'] . "'>\n";
    echo "<meta name='COPYRIGHT' content='Copyright (c) 2004 by " . $xoopsConfig['sitename'] . "'>\n";
    echo "<meta name='DESCRIPTION' content='" . $xoopsConfig['slogan'] . "'>\n";
    echo "<meta name='GENERATOR' content='" . XOOPS_VERSION . "'>\n\n\n";

    echo "<body bgcolor='#ffffff' text='#000000'>
    <div style='width: 650px; border: 1px solid #000; padding: 20px;'>
    <div style='text-align: center; display: block; padding-bottom: 12px; margin: 0 0 6px 0; border-bottom: 2px solid #ccc;'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/lx_slogo.png' border='0' alt=''><h2 style='margin: 0;'>" . $term . '</h2></div>
    <div></div>';
    if (1 == $helper->getConfig('multicats')) {
        echo '<div>' . _MD_LEXIKON_ENTRYCATEGORY . '<b>' . $categoryname . '</b></div>';
    }
    echo "<div style='padding-bottom: 6px; border-bottom: 1px solid #ccc;'>" . _MD_LEXIKON_SUBMITTER . '<b>' . $authorname . "</b></div>
    <h3 style='margin: 0;'>" . $term . '</h3>
    <p>' . $definition . "</p>
    <div style='padding-top: 12px; border-top: 2px solid #ccc;'><b>" . _MD_LEXIKON_SENT . '</b>&nbsp;' . $datetime . '<br></div>
    </div>
    <br>
    </body>
    </html>';
}

printPage($entryID);
