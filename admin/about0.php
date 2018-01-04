<?php
/**
 *
 * Module: Lexikon - glossary module
 * Version: v 1.5
 * Release Date:
 * Author: hsalazar
 * License: GNU
 */

use Xmf\Request;

require_once __DIR__ . '/admin_header.php';
$myts = \MyTextSanitizer::getInstance();

xoops_cp_header();

/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$versioninfo   = $moduleHandler->get($xoopsModule->getVar('mid'));
echo '
    <style type="text/css">
    label,text {
    display: block;
    float: left;
    margin-bottom: 2px;
    margin-top: 2px;
    }
    label {
    text-align: right;
    width: 150px;
    padding-right: 20px;
    }
    br {
    clear: left;
    }
</style>';
/**
 * display module info
 */

function about()
{
    $op = 'default';
    global $xoopsConfig, $xoopsModuleConfig, $xoopsModule, $versioninfo;

    echo '<br clear="all">';
    echo "<img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/' . $versioninfo->getInfo('image') . '\' alt=\'\' hspace=\'0\' vspace=\'0\' align=\'left\' style=\'margin-right: 10px; \'>';
    echo "<div style='margin-top: 10px; color: #33538e; margin-bottom: 4px; font-size: 18px; line-height: 18px; font-weight: bold; display: block;'>" . $versioninfo->getInfo('name') . ' v. ' . $versioninfo->getInfo('version') . '</div>';
    if ('' != $versioninfo->getInfo('author_realname')) {
        $author_name = $versioninfo->getInfo('author') . ' (' . $versioninfo->getInfo('author_realname') . ')';
    } else {
        $author_name = $versioninfo->getInfo('author');
    }
    echo '<br clear="all"><b>';

    echo "<div style='padding: 8px;'>";
    echo "<table width='100%' cellspacing='2' cellpadding='2' border='0' class='outer'>";
    echo '<tr>';
    echo "<td colspan='2' class='even' align='left'><b>" . $versioninfo->getInfo('name') . ' ' . $versioninfo->getInfo('version') . '</b></td>';
    echo '</tr></table>';
    echo '<br clear="all">';

    echo '<label>' . _AM_LEXIKON_ABOUT_RELEASEDATE . ':</label><text>' . $versioninfo->getInfo('release') . '</text><br>';
    echo '<label>' . _AM_LEXIKON_ABOUT_AUTHOR . ':</label><text>' . $versioninfo->getInfo('author') . '</text><br>';
    //echo "<label>" . _AM_LEXIKON_ABOUT_LICENSE . ":</label><text><a href=\"".$versioninfo->getInfo( 'license_file' )."\" target=\"_blank\" >" . $versioninfo->getInfo( 'license' ) . "</a></text>\n";
    echo '<label>' . _AM_LEXIKON_ABOUT_LICENSE . ':</label><text><a href="' . $versioninfo->getInfo('license_file') . '" target="_blank" >' . $versioninfo->getInfo('license') . "</a></text>\n";
    echo '</div>';
    echo '<br clear="all">';

    // information
    echo "<div style='padding: 8px;'>";
    echo "<table width='100%' cellspacing='2' cellpadding='2' border='0' class='outer'>";
    echo '<tr>';
    echo "<td colspan='2' class='even' align='left'><b>" . _AM_LEXIKON_ABOUT_MODULE_INFO . '</b></td>';
    echo '</tr></table>';

    echo '<label>' . _AM_LEXIKON_ABOUT_MODULE_STATUS . ':</label><text>' . $versioninfo->getInfo('module_status') . '</text><br>';
    echo '<label>' . _AM_LEXIKON_ABOUT_WEBSITE . ':</label><text>' . "<a href='" . $versioninfo->getInfo('module_website_url') . '\' target=\'_blank\'>' . $versioninfo->getInfo('module_website_name') . '</a>' . '</text><br>';

    if ('' != $versioninfo->getInfo('submit_bug')) {
        echo '<label>' . _AM_LEXIKON_ABOUT_SUBMIT_BUG . "</label><text><a href='" . $versioninfo->getInfo('submit_bug') . '\' target=\'blank\'>' . _AM_LEXIKON_ABOUT_SUBMIT_BUG_TEXT . '</a></text><b>';
    }
    if ('' != $versioninfo->getInfo('submit_feature')) {
        echo '<label>' . _AM_LEXIKON_ABOUT_SUBMIT_FEATURE . "</label><text'><a href='" . $versioninfo->getInfo('submit_feature') . '\' target=\'blank\'>' . _AM_LEXIKON_ABOUT_SUBMIT_FEATURE_TEXT . '</a></text><b>';
    }
    echo '</div>';
    echo '<br clear="all">';

    echo "<div style='padding: 8px;'>";
    echo "<table width='100%' cellspacing='2' cellpadding='2' border='0' class='outer'>";
    echo '<tr>';
    echo "<td colspan='2' class='even' align='left'><b>" . _AM_LEXIKON_ABOUT_DISCLAIMER . '</b></td>';
    echo '</tr></table>';
    echo '<div>' . _AM_LEXIKON_ABOUT_DISCLAIMER_TEXT . '</div>';
    echo '</div>';
    echo '<br clear="all">';
    $file = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/changelog.txt';
    if (is_readable($file)) {
        echo "<div style='padding: 8px;'>";
        echo "<table width='100%' cellspacing='2' cellpadding='2' border='0' class='outer'>";
        echo '<tr>';
        echo "<td colspan='2' class='even' align='left'><b>" . _AM_LEXIKON_ABOUT_CHANGELOG . '</b></td>';
        echo '</tr></table>';

        echo '' . implode('<br>', file($file)) . '';
        echo '</div>';

        echo '<br clear="all">';
    }
}

/**
 * readme file
 */

function readme()
{
    global $xoopsConfig, $xoopsModuleConfig, $xoopsModule, $versioninfo;

    echo '<br clear="all">';
    echo "<img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/' . $versioninfo->getInfo('image') . '\' alt=\'\' hspace=\'0\' vspace=\'0\' align=\'left\' style=\'margin-right: 10px; \'></a>';
    echo "<div style='margin-top: 10px; color: #33538e; margin-bottom: 4px; font-size: 18px; line-height: 18px; font-weight: bold; display: block;'>" . $versioninfo->getInfo('name') . ' v. ' . $versioninfo->getInfo('version') . '</div>';
    if ('' != $versioninfo->getInfo('author_realname')) {
        $author_name = $versioninfo->getInfo('author') . ' (' . $versioninfo->getInfo('author_realname') . ')';
    } else {
        $author_name = $versioninfo->getInfo('author');
    }
    echo '<br clear="all"><b>';

    $file = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/readme.txt';
    if (is_readable($file)) {
        echo "<div style='padding: 8px;'>";
        echo "<table width='100%' cellspacing='2' cellpadding='2' border='0' class='outer'>";
        echo '<tr>';
        echo "<td colspan='2' class='even' align='left'><b>" . _AM_LEXIKON_ABOUT_README . '</b></td>';
        echo '</tr></table>';

        echo '' . implode('<br>', file($file)) . '';
        echo '</div>';
        echo '<br clear="all">';
    }
}

/* -- Available operations -- */
$op = Request::getCmd('op', '');
switch ($op) {
    case 'readme':
        lx_adminMenu(47);
        readme();
        break;
    case 'about':
    default:
        lx_adminMenu(11);
        about();
        break;
}
xoops_cp_footer();
