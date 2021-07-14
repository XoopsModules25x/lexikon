<?php
/**
 * Module: lexikon
 * Author: Yerres
 * Licence: GNU
 */

use XoopsModules\Lexikon\{
    Helper
};
/** @var Helper $helper */

global $xoopsUser;

if (is_object($xoopsUser)) {
    $xoopsModule = XoopsModule::getByDirname('lexikon');
    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . '/', 1, _NOPERM);
    }
} else {
    redirect_header(XOOPS_URL . '/', 1, _NOPERM);
}
/**
 * Function used to display an horizontal menu inside the admin panel
 * Enable webmasters to navigate thru the module's features.
 * Each time you select an option in the admin panel of the news module, this option is highlighted in this menu
 * @param int    $currentoption
 * @param string $breadcrumb
 * @package          lexikon
 * @orig             author: hsalazar, The smartfactory
 * @copyright    (c) XOOPS Project (https://xoops.org)
 */
function lx_adminMenu($currentoption = 0, $breadcrumb = '')
{
    require_once XOOPS_ROOT_PATH . '/class/template.php';

    global $xoopsDB, $xoopsModule, $xoopsConfig;

    $helper = Helper::getInstance();
    $helper->loadLanguage('admin');
    $helper->loadLanguage('modinfo');

    require __DIR__ . '/menu.php';

    $tpl = new \XoopsTpl();
    $tpl->assign(
        [
            'headermenu'      => $headermenu,
            'adminmenu'       => $adminmenu,
            'current'         => $currentoption,
            'breadcrumb'      => $breadcrumb,
            'headermenucount' => count($headermenu),
        ]
    );
    $tpl->display('db:lx_adminmenu.tpl');
    echo "<br>\n";
}

/**
 * Add a field to a mysql table
 *
 * @param $field
 * @param $table
 * @return bool|\mysqli_result
 * @package       Lexikon
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */
function lx_AddField($field, $table)
{
    global $xoopsDB;
    //naja !
    $result = $xoopsDB->queryF('ALTER TABLE ' . $table . ' ADD ' . $field . ' ');

    return $result;
}

/**
 * Change a field to a mysql table
 * desuet
 * @param $field
 * @param $table
 * @return bool
 * @package       Lexikon
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */
function lx_alterTable($field, $table)
{
    global $xoopsDB;
    $sql    = 'SHOW COLUMNS FROM ' . $table . " LIKE '" . $field . "'";
    $result = $xoopsDB->queryF($sql);
    if (0 == $xoopsDB->getRowsNum($result)) {
        $sql    = 'ALTER TABLE ' . $xoopsDB->prefix($table) . ' ADD `' . $field . '`';
        $result = $xoopsDB->query($sql);
        return $result;
        //   }
    }

    return true;
}

/*
 * Sub-Menu for Importscripts
 * @package lexikon
 * @copyright (c) XOOPS Project (https://xoops.org)
*/

/**
 * @param int    $currentoption
 * @param string $breadcrumb
 */
function lx_importMenu($currentoption = 0, $breadcrumb = '')
{
    global $cf;
    echo "<table style='border:0; width:99%;'>
              <tr><td style='vertical-align:top;'>
              <strong style='color: #2F5376; margin-top:6px; font-size:medium'>" . _AM_LEXIKON_IMPORT_MENU . '</strong><br>';
    if ($cf > 0) {
        echo '<span style="font-size:x-small">' . _AM_LEXIKON_OTHERMODS . '</span><br><br>';
    } else {
        echo '<span style="font-size:x-small; color:red;">' . _AM_LEXIKON_NOOTHERMODS . '</span><br><br>';
    }

    echo "</td><td style='vertical-align:top;'>
              <div id='menu'>";
    // show only modules located on the system
    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler  = xoops_getHandler('module');
    $wordbookModule = $moduleHandler->getByDirname('wordbook');
    $got_options    = false;
    $cf             = 0;
    if (is_object($wordbookModule)) {
        $wb_imgurl = XOOPS_URL . '/modules/wordbook/images';
        ++$cf;
        echo "<a href='importwordbook.php'>
                  <img src='" . $wb_imgurl . "/wb_slogo.png' alt='wb_slogo.png' title='Wordbook' style='height:39px; width:69px;'><span>" . _AM_LEXIKON_IMPORT_WORDBOOK . '</span></a>';
    } //else { echo "". 'wordbook' ."";}
    $dictionaryModule = $moduleHandler->getByDirname('dictionary');
    $got_options      = false;
    if (is_object($dictionaryModule)) {
        $dic_imgurl = XOOPS_URL . '/modules/dictionary/images';
        ++$cf;
        echo "<a href='importdictionary.php'>
                  <img src='" . $dic_imgurl . "/dictionary_logo.png' alt='Dictionary' title='Dictionary' style='height:39px; width:69px;'><span>" . _AM_LEXIKON_IMPORT_DICTIONARY . '</span></a>';
    } //else { echo "<B>&middot;</B>". 'dictionary' ."";}
    $glossaireModule = $moduleHandler->getByDirname('glossaire');
    $got_options     = false;
    if (is_object($glossaireModule)) {
        $glo_imgurl = XOOPS_URL . '/modules/glossaire.';
        ++$cf;
        echo "<a href='importglossaire.php'>
                  <img src='" . $glo_imgurl . "/glossaire_logo.jpg' alt='Glossaire' title='Glossaire' style='height:31px; width:88px;'><span>" . _AM_LEXIKON_IMPORT_GLOSSAIRE . '</span></a>';
    } //else { echo "<B>&middot;</B>". 'glossaire' ."";}
    $wiwimodModule = $moduleHandler->getByDirname('wiwimod');
    $got_options   = false;
    if (is_object($wiwimodModule)) {
        $wiwi_imgurl = XOOPS_URL . '/modules/wiwimod/images';
        ++$cf;
        echo "<a href='importwiwimod.php'>
                  <img src='" . $wiwi_imgurl . "/wiwilogo.gif' alt='Wiwimod' title='Wiwimod' style='height:39px; width:69px;'><span>" . _AM_LEXIKON_IMPORT_WIWIMOD . '</span></a>';
    } //else { echo "<B>&middot;</B>". 'wiwimod' ."";}
    $xwordsModule = $moduleHandler->getByDirname('xwords');
    $got_options  = false;
    if (is_object($xwordsModule)) {
        $xwd_imgurl = XOOPS_URL . '/modules/xwords/images';
        ++$cf;
        echo "<a href='importxwords.php'>
                  <img src='" . $xwd_imgurl . "/xwords_slogo.png' alt='Xwords' title='Xwords' style='height:39px; width:69px;'><span>" . _AM_LEXIKON_IMPORT_XWORDS . '</span></a>';
    }// else { echo "<B>&middot;</B>". 'xwords' ."";}
    echo '</div></td><tr></table>';
}

/**
 * collapsable bar for items lists
 * @param string $tablename
 * @param string $iconname
 * @package       lexikon
 * @copyright (c) XOOPS Project (https://xoops.org)
 */
function lx_collapsableBar($tablename = '', $iconname = '')
{
    ?>
    <script type="text/javascript"><!--
        function goto_URL(object) {
            window.location.href = object.options[object.selectedIndex].value;
        }

        function toggle(id) {
            if (document.getElementById) {
                obj = document.getElementById(id);
            }
            if (document.all) {
                obj = document.all[id];
            }
            if (document.layers) {
                obj = document.layers[id];
            }
            if (obj) {
                if (obj.style.display === "none") {
                    obj.style.display = "";
                } else {
                    obj.style.display = "none";
                }
            }

            return false;
        }

        var iconClose = new Image();
        iconClose.src = '../assets/images/close12.gif';
        var iconOpen = new Image();
        iconOpen.src = '../assets/images/open12.gif';

        function toggleIcon(iconName) {
            if (document.images[iconName].src == window.iconOpen.src) {
                document.images[iconName].src = window.iconClose.src;
            }
            elseif(document.images[iconName].src == window.iconClose.src)
            {
                document.images[iconName].src = window.iconOpen.src;
            }
        }

        //-->
    </script>
    <?php
    // HTML Error Fixed by 5Vision
    echo "<div style='color:#2F5376; margin:6px 0 0 0;'><a href='#' onClick=\"toggle('" . $tablename . "'); toggleIcon('" . $iconname . "');\">";
}

/**
 * Returns statistics about the Glossary
 * adapted from news module 1.0
 * @param $limit
 * @return array
 */
function lx_GetStatistics($limit)
{
    $ret  = [];
    $db   = \XoopsDatabaseFactory::getDatabaseConnection();
    $tbls = $db->prefix('lxentries');
    $tblt = $db->prefix('lxcategories');

    $db = \XoopsDatabaseFactory::getDatabaseConnection();
    // Number of Definitions per Category, including offline and submitted terms
    $ret2   = [];
    $sql    = "SELECT count(s.entryID) as cpt, s.categoryID, t.name FROM $tbls s, $tblt t WHERE s.categoryID=t.categoryID GROUP BY s.categoryID ORDER BY t.name";
    $result = $db->query($sql);
    while (false !== ($myrow = $db->fetchArray($result))) {
        $ret2[$myrow['categoryID']] = $myrow;
    }
    $ret['termspercategory'] = $ret2;
    unset($ret2);

    // Total reads per category
    $ret2   = [];
    $sql    = "SELECT Sum(counter) as cpt, categoryID FROM $tbls GROUP BY categoryID ORDER BY categoryID";
    $result = $db->query($sql);
    while (false !== ($myrow = $db->fetchArray($result))) {
        $ret2[$myrow['categoryID']] = $myrow['cpt'];
    }
    $ret['readspercategory'] = $ret2;

    // unused terms per category i.e. offline or submitted
    $ret2   = [];
    $sql    = "SELECT Count(entryID) as cpt, categoryID FROM $tbls WHERE offline > 0 OR submit > 0 GROUP BY categoryID ORDER BY categoryID";
    $result = $db->query($sql);
    while (false !== ($myrow = $db->fetchArray($result))) {
        $ret2[$myrow['categoryID']] = $myrow['cpt'];
    }
    $ret['offlinepercategory'] = $ret2;
    unset($ret2);

    // Number of unique authors per category
    $ret2   = [];
    $sql    = "SELECT Count(Distinct(uid)) as cpt, categoryID FROM $tbls GROUP BY categoryID ORDER BY categoryID";
    $result = $db->query($sql);
    while (false !== ($myrow = $db->fetchArray($result))) {
        $ret2[$myrow['categoryID']] = $myrow['cpt'];
    }
    $ret['authorspercategory'] = $ret2;
    unset($ret2);

    // Most read terms
    $ret2   = [];
    $sql    = "SELECT s.entryID, s.uid, s.term, s.counter, s.categoryID, t.name  FROM $tbls s, $tblt t WHERE s.categoryID=t.categoryID ORDER BY s.counter DESC";
    $result = $db->query($sql, (int)$limit);
    while (false !== ($myrow = $db->fetchArray($result))) {
        $ret2[$myrow['entryID']] = $myrow;
    }
    $ret['mostreadterms'] = $ret2;
    unset($ret2);

    // Less read terms
    $ret2   = [];
    $sql    = "SELECT s.entryID, s.uid, s.term, s.counter, s.categoryID, t.name  FROM $tbls s, $tblt t WHERE s.categoryID=t.categoryID ORDER BY s.counter";
    $result = $db->query($sql, (int)$limit);
    while (false !== ($myrow = $db->fetchArray($result))) {
        $ret2[$myrow['entryID']] = $myrow;
    }
    $ret['lessreadterms'] = $ret2;
    unset($ret2);

    // Most read authors
    $ret2   = [];
    $sql    = "SELECT Sum(counter) as cpt, uid FROM $tbls GROUP BY uid ORDER BY cpt DESC";
    $result = $db->query($sql, (int)$limit);
    while (false !== ($myrow = $db->fetchArray($result))) {
        $ret2[$myrow['uid']] = $myrow['cpt'];
    }
    $ret['mostreadauthors'] = $ret2;
    unset($ret2);

    // Biggest contributors
    $ret2   = [];
    $sql    = "SELECT Count(*) as cpt, uid FROM $tbls GROUP BY uid ORDER BY cpt DESC";
    $result = $db->query($sql, (int)$limit);
    while (false !== ($myrow = $db->fetchArray($result))) {
        $ret2[$myrow['uid']] = $myrow['cpt'];
    }
    $ret['biggestcontributors'] = $ret2;
    unset($ret2);

    return $ret;
}

//-- build a table header
function lx_buildTable()
{
    global $xoopsConfig, $xoopsModuleConfig, $xoopsModule;
    echo "<div style='color: #2F5376; margin: 6px 0 0 0; '>";
    echo "<table class='outer' style='width:100%;'>";
    echo '<tr >';
    echo "<th style='width:40px; text-align:center;'>" . _AM_LEXIKON_ENTRYID . '</td>';
    echo "<th style='width:100px; text-align:center;'>" . _AM_LEXIKON_ENTRYCATNAME . '</td>';
    echo "<th style='text-align:center;'>" . _AM_LEXIKON_TERM . '</td>';
    echo "<th style='width:90px; text-align:center;'>" . _AM_LEXIKON_AUTHOR . '</td>';
    echo "<th style='width:90px; text-align:center;'>" . _AM_LEXIKON_ENTRYCREATED . '</td>';
    echo "<th style='width:40px; text-align:center;'>" . _AM_LEXIKON_STATUS . '</td>';
    echo "<th style='width:60px; text-align:center;'>" . _AM_LEXIKON_ACTION . '</td>';
    echo '</tr>';
}

/**
 * save_permissions()
 * adapted from WF-Downloads
 * @param $groups
 * @param $id
 * @param $perm_name
 * @return bool
 */
function lx_save_Permissions($groups, $id, $perm_name)
{
    $result = true;
    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $lxModule      = $moduleHandler->getByDirname('lexikon');

    $module_id = $lxModule->getVar('mid');
    /** @var \XoopsGroupPermHandler $grouppermHandler */
    $grouppermHandler = xoops_getHandler('groupperm');

    /*
    * First, if the permissions are already there, delete them
    */
    $grouppermHandler->deleteByModule($module_id, $perm_name, $id);
    /*
    *  Save the new permissions
    */
    if (is_array($groups)) {
        foreach ($groups as $group_id) {
            $grouppermHandler->addRight($perm_name, $id, $group_id, $module_id);
        }
    }

    return $result;
}

//-- Initial Selector
/**
 * @param $init
 */
function lx_getinit($init)
{
    global $init;
    echo "<div><select name='init'>";
    echo "<option value='#'>&nbsp; # &nbsp;</option>";
    for ($a = 48; $a < (48 + 10); ++$a) {
        if (uchr($a) == $init) {
            $opt_selected = 'selected';
        } else {
            $opt_selected = '';
        }
        echo "<option value='" . uchr($a) . "' $opt_selected>&nbsp;" . uchr($a) . '&nbsp;</option>';
    }
    for ($a = 65; $a < (65 + 26); ++$a) {
        if (uchr($a) == $init) {
            $opt_selected = 'selected';
        } else {
            $opt_selected = '';
        }
        echo "<option value='" . uchr($a) . "' $opt_selected>&nbsp;" . uchr($a) . '&nbsp;</option>';
    }
    /*for ($a = 1040; $a < (1040 + 32); ++$a) {
        if (uchr($a) == $init) {
            $opt_selected = 'selected';
        } else {
            $opt_selected = '';
        }
        echo "<option value='" . uchr($a) . "' $opt_selected>&nbsp;" . uchr($a) . "&nbsp;</option>";
    }*/
    echo '</select></div>';
}

/**
 * @param $a
 * @return string
 */
function uchr($a)
{
    if (is_scalar($a)) {
        $a = func_get_args();
    }
    $str = '';
    foreach ($a as $code) {
        $str .= html_entity_decode('&#' . $code . ';', ENT_NOQUOTES, 'UTF-8');
    }

    return $str;
}
