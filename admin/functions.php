<?php
/**
 *
 * Module: lexikon
 * Version: v 1.00
 * Release Date: 18 Dec 2011
 * Author: Yerres
 * Licence: GNU
 */

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
 * @package          lexikon
 * @orig             author: hsalazar, The smartfactory
 * @copyright    (c) XOOPS Project (https://xoops.org)
 * @param int    $currentoption
 * @param string $breadcrumb
 */

function lx_adminMenu($currentoption = 0, $breadcrumb = '')
{
    require_once XOOPS_ROOT_PATH . '/class/template.php';

    global $xoopsDB, $xoopsModule, $xoopsConfig;
    if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/language/' . $xoopsConfig['language'] . '/modinfo.php')) {
        require_once XOOPS_ROOT_PATH . '/modules/lexikon/language/' . $xoopsConfig['language'] . '/modinfo.php';
    } else {
        require_once XOOPS_ROOT_PATH . '/modules/lexikon/language/english/modinfo.php';
    }
    if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/language/' . $xoopsConfig['language'] . '/admin.php')) {
        require_once XOOPS_ROOT_PATH . '/modules/lexikon/language/' . $xoopsConfig['language'] . '/admin.php';
    } else {
        require_once XOOPS_ROOT_PATH . '/modules/lexikon/language/english/admin.php';
    }

    include __DIR__ . '/menu.php';

    $tpl = new XoopsTpl();
    $tpl->assign(array(
                     'headermenu'      => $headermenu,
                     'adminmenu'       => $adminmenu,
                     'current'         => $currentoption,
                     'breadcrumb'      => $breadcrumb,
                     'headermenucount' => count($headermenu)
                 ));
    $tpl->display('db:lx_adminmenu.tpl');
    echo "<br>\n";
}

/**
 * Verify that a field exists inside a mysql table
 *
 * @package       Lexikon
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 * @param $fieldname
 * @param $table
 * @return bool
 */
function lx_FieldExists($fieldname, $table)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("SHOW COLUMNS FROM   $table LIKE '$fieldname'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

/**
 * Add a field to a mysql table
 *
 * @package       Lexikon
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 * @param $field
 * @param $table
 * @return
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
 * @package       Lexikon
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 * @param $field
 * @param $table
 * @return bool
 */

function lx_alterTable($field, $table)
{
    global $xoopsDB;
    $sql    = 'SHOW COLUMNS FROM ' . $table . " LIKE '" . $field . '\'';
    $result = $xoopsDB->queryF($sql);
    //if ($result) {
    if ($xoopsDB->getRowsNum($result) == 0) {
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix($table) . ' ADD `' . $field . '`';

        return $xoopsDB->query($sql);
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
    echo '<style type="text/css">
    br {clear: left;}
    img {border:0;}
    #menu {width:400px; position:relative;  height:80px; margin:1em auto auto 2em;}
    #menu a:visited, #menu a {text-decoration:none; color:#d00; font-weight:bold;}
    #menu a:visited img, #menu a img{filter: alpha(opacity=40);
    filter: progid:DXImageTransform.Microsoft.Alpha(opacity=40);
    -moz-opacity: 0.40; opacity:0.4;
}
    #menu a:hover {background-color:trans; color:#06a;}
    #menu a span {display:none;}
    #menu a:hover span {display:block; position:absolute; top:50px; left:0; font-size:12px; height:18px; padding:4px; font-weight:normal; color:#a40;}

    #menu a:hover img { filter: alpha(opacity=100);
    filter: progid:DXImageTransform.Microsoft.Alpha(opacity=100);
    -moz-opacity: 1.00; opacity:1;
}
    </style>';
    echo " <TABLE BORDER=0 CELLPADDING=1 CELLSPACING=2 width='98%'>
    <tr><td width='200' VALIGN='top'>
    <h3>Import Menu</h3><span style='font-size:1'>";
    if ($cf < 5) {
        echo '' . _AM_LEXIKON_OTHERMODS . '';
    } else {
        echo '' . _AM_LEXIKON_NOOTHERMODS . '';
    }

    echo "</FONT></td><td VALIGN='top'>
    <div id=\"menu\">";
    // show only modules located on the system
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler  = xoops_getHandler('module');
    $wordbookModule = $moduleHandler->getByDirname('wordbook');
    $got_options    = false;
    $cf             = 0;
    if (is_object($wordbookModule)) {
        $wb_imgurl = XOOPS_URL . '/modules/wordbook/images';
        ++$cf;
        echo '<a href="importwordbook.php">
        <img src="' . $wb_imgurl . '/wb_slogo.png" alt="wb_slogo.png" title="Wordbook" height="39" width="69"><span>Import Wordbook</span></a>';
    } //else { echo "". 'wordbook' ."";}
    $dictionaryModule = $moduleHandler->getByDirname('dictionary');
    $got_options      = false;
    if (is_object($dictionaryModule)) {
        $dic_imgurl = XOOPS_URL . '/modules/dictionary/images';
        ++$cf;
        echo '<a href="importdictionary.php">
        <img src="' . $dic_imgurl . '/dictionary_logo.png" alt="Dictionary" title="Dictionary" height="39" width="69"><span>Import Dictionary</span></a>';
    } //else { echo "<B>&middot;</B>". 'dictionary' ."";}
    $glossaireModule = $moduleHandler->getByDirname('glossaire');
    $got_options     = false;
    if (is_object($glossaireModule)) {
        $glo_imgurl = XOOPS_URL . '/modules/glossaire.';
        ++$cf;
        echo '<a href="importglossaire.php">
        <img src="' . $glo_imgurl . '/glossaire_logo.jpg" alt="Glossaire" title="Glossaire" height="31" width="88"><span>Import Glossaire</span></a>';
    } //else { echo "<B>&middot;</B>". 'glossaire' ."";}
    $wiwimodModule = $moduleHandler->getByDirname('wiwimod');
    $got_options   = false;
    if (is_object($wiwimodModule)) {
        $wiwi_imgurl = XOOPS_URL . '/modules/wiwimod/images';
        ++$cf;
        echo '<a href="importwiwimod.php"><img src="' . $wiwi_imgurl . '/wiwilogo.gif" alt="Wiwimod" title="Wiwimod" height="39" width="69"><span>Import Wiwimod</span></a>';
    } //else { echo "<B>&middot;</B>". 'wiwimod' ."";}
    $xwordsModule = $moduleHandler->getByDirname('xwords');
    $got_options  = false;
    if (is_object($xwordsModule)) {
        $xwd_imgurl = XOOPS_URL . '/modules/xwords/images';
        ++$cf;
        echo '<a href="importxwords.php"><img src="' . $xwd_imgurl . '/xwords_slogo.png" alt="Xwords" title="Xwords" height="39" width="69"><span>Import Xwords</span></a>';
    }// else { echo "<B>&middot;</B>". 'xwords' ."";}
    echo '</div></td><tr></TABLE>';
}

/**
 * collapsable bar for items lists
 * @package       lexikon
 * @copyright (c) XOOPS Project (https://xoops.org)
 * @param string $tablename
 * @param string $iconname
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
    echo "<div style=\"color: #2F5376; margin: 6px 0 0 0; \"><a href=\"#\" onClick=\"toggle('" . $tablename . '\'); toggleIcon(\'' . $iconname . '\');">';
}

/**
 * Returns statistics about the Glossary
 * adapted from news module 1.0
 * @param $limit
 * @return array
 */
function lx_GetStatistics($limit)
{
    $ret  = array();
    $db   = XoopsDatabaseFactory::getDatabaseConnection();
    $tbls = $db->prefix('lxentries');
    $tblt = $db->prefix('lxcategories');

    $db = XoopsDatabaseFactory::getDatabaseConnection();
    // Number of Definitions per Category, including offline and submitted terms
    $ret2   = array();
    $sql    = "SELECT count(s.entryID) as cpt, s.categoryID, t.name FROM $tbls s, $tblt t WHERE s.categoryID=t.categoryID GROUP BY s.categoryID ORDER BY t.name";
    $result = $db->query($sql);
    while ($myrow = $db->fetchArray($result)) {
        $ret2[$myrow['categoryID']] = $myrow;
    }
    $ret['termspercategory'] = $ret2;
    unset($ret2);

    // Total reads per category
    $ret2   = array();
    $sql    = "SELECT Sum(counter) as cpt, categoryID FROM $tbls GROUP BY categoryID ORDER BY categoryID";
    $result = $db->query($sql);
    while ($myrow = $db->fetchArray($result)) {
        $ret2[$myrow['categoryID']] = $myrow['cpt'];
    }
    $ret['readspercategory'] = $ret2;

    // unused terms per category i.e. offline or submitted
    $ret2   = array();
    $sql    = "SELECT Count(entryID) as cpt, categoryID FROM $tbls WHERE offline > 0 OR submit > 0 GROUP BY categoryID ORDER BY categoryID";
    $result = $db->query($sql);
    while ($myrow = $db->fetchArray($result)) {
        $ret2[$myrow['categoryID']] = $myrow['cpt'];
    }
    $ret['offlinepercategory'] = $ret2;
    unset($ret2);

    // Number of unique authors per category
    $ret2   = array();
    $sql    = "SELECT Count(Distinct(uid)) as cpt, categoryID FROM $tbls GROUP BY categoryID ORDER BY categoryID";
    $result = $db->query($sql);
    while ($myrow = $db->fetchArray($result)) {
        $ret2[$myrow['categoryID']] = $myrow['cpt'];
    }
    $ret['authorspercategory'] = $ret2;
    unset($ret2);

    // Most read terms
    $ret2   = array();
    $sql    = "SELECT s.entryID, s.uid, s.term, s.counter, s.categoryID, t.name  FROM $tbls s, $tblt t WHERE s.categoryID=t.categoryID ORDER BY s.counter DESC";
    $result = $db->query($sql, (int)$limit);
    while ($myrow = $db->fetchArray($result)) {
        $ret2[$myrow['entryID']] = $myrow;
    }
    $ret['mostreadterms'] = $ret2;
    unset($ret2);

    // Less read terms
    $ret2   = array();
    $sql    = "SELECT s.entryID, s.uid, s.term, s.counter, s.categoryID, t.name  FROM $tbls s, $tblt t WHERE s.categoryID=t.categoryID ORDER BY s.counter";
    $result = $db->query($sql, (int)$limit);
    while ($myrow = $db->fetchArray($result)) {
        $ret2[$myrow['entryID']] = $myrow;
    }
    $ret['lessreadterms'] = $ret2;
    unset($ret2);

    // Most read authors
    $ret2   = array();
    $sql    = "SELECT Sum(counter) as cpt, uid FROM $tbls GROUP BY uid ORDER BY cpt DESC";
    $result = $db->query($sql, (int)$limit);
    while ($myrow = $db->fetchArray($result)) {
        $ret2[$myrow['uid']] = $myrow['cpt'];
    }
    $ret['mostreadauthors'] = $ret2;
    unset($ret2);

    // Biggest contributors
    $ret2   = array();
    $sql    = "SELECT Count(*) as cpt, uid FROM $tbls GROUP BY uid ORDER BY cpt DESC";
    $result = $db->query($sql, (int)$limit);
    while ($myrow = $db->fetchArray($result)) {
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
    echo "<table width='100%' cellspacing='2' cellpadding='3' border='0' class='outer'>";
    //echo "<tr><td colspan='7' class='odd'>";
    //echo "<strong>". _AM_LEXIKON_INVENTORY . "</strong></td></tr>";
    echo '<tr >';
    echo "<th width='40px'  align='center'><b>" . _AM_LEXIKON_ENTRYID . '</b></td>';
    echo "<th width='100px'  align='center'><b>" . _AM_LEXIKON_ENTRYCATNAME . '</b></td>';
    echo "<th align='center'><b>" . _AM_LEXIKON_TERM . '</b></td>';
    echo "<th width='90px'  align='center'><b>" . _AM_LEXIKON_AUTHOR . '</b></td>';
    echo "<th width='90px'  align='center'><b>" . _AM_LEXIKON_ENTRYCREATED . '</b></td>';
    echo "<th width='40px'  align='center'><b>" . _AM_LEXIKON_STATUS . '</b></td>';
    echo "<th width='60px'  align='center'><b>" . _AM_LEXIKON_ACTION . '</b></td>';
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
    $result   = true;
    $hModule  = xoops_getHandler('module');
    $lxModule = $hModule->getByDirname('lexikon');

    $module_id    = $lxModule->getVar('mid');
    $gpermHandler = xoops_getHandler('groupperm');

    /*
    * First, if the permissions are already there, delete them
    */
    $gpermHandler->deleteByModule($module_id, $perm_name, $id);
    /*
    *  Save the new permissions
    */
    if (is_array($groups)) {
        foreach ($groups as $group_id) {
            $gpermHandler->addRight($perm_name, $id, $group_id, $module_id);
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
    echo "<select name='init'>";
    echo "<option value='#'>&nbsp; # &nbsp;</option>";
    for ($a = 65; $a < (65 + 26); ++$a) {
        if (chr($a) == $init) {
            $opt_selected = 'selected';
        } else {
            $opt_selected = '';
        }
        echo "<option value='" . chr($a) . "' $opt_selected>&nbsp; " . chr($a) . ' &nbsp;</option>';
    }
    echo '</select></div>';
}
