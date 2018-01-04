<?php
/**
 *
 * Module: Lexikon
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

/**
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 * @return array
 */
function lx_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB, $xoopsUser;
    // -- search comments + highlighter
    $highlight        = false;
    $searchincomments = false;
    require_once XOOPS_ROOT_PATH . '/modules/lexikon/include/common.inc.php';
    require_once XOOPS_ROOT_PATH . '/modules/lexikon/class/Utility.php';
    $hightlight_key   = '';
    $highlight        = $utility::getModuleOption('config_highlighter');
    $searchincomments = CONFIG_SEARCH_COMMENTS;
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname('lexikon');
    $module_id     = $module->getVar('mid');
    // Permissions
    $gpermHandler = xoops_getHandler('groupperm');
    $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $allowed_cats = $gpermHandler->getItemIds('lexikon_view', $groups, $module_id);
    $catids       = implode(',', $allowed_cats);

    $sql = 'SELECT entryID, categoryID, term, definition, ref, uid, datesub FROM ' . $xoopsDB->prefix('lxentries') . ' WHERE submit = 0 AND offline = 0 ';
    $sql .= " AND categoryID IN ($catids) ";

    if (0 != $userid) {
        $sql .= ' AND uid=' . $userid . ' ';
    }
    if ($highlight) {
        if ('' == $queryarray) {
            $keywords       = '';
            $hightlight_key = '';
        } else {
            $keywords       = implode('+', $queryarray);
            $hightlight_key = '&amp;keywords=' . $keywords;
        }
    }
    // because count() returns 1 even if a supplied variable
    // is not an array, we must check if $querryarray is really an array
    $count = count($queryarray);
    if ($count > 0 && is_array($queryarray)) {
        $sql .= "AND ((term LIKE '%$queryarray[0]%' OR definition LIKE '%$queryarray[0]%' OR ref LIKE '%$queryarray[0]%')";
        for ($i = 1; $i < $count; ++$i) {
            $sql .= " $andor ";
            $sql .= "(term LIKE '%$queryarray[$i]%' OR definition LIKE '%$queryarray[$i]%' OR ref LIKE '%$queryarray[$i]%')";
        }
        $sql .= ') ';
    }
    $sql    .= 'ORDER BY entryID DESC';
    $result = $xoopsDB->query($sql, $limit, $offset);
    $ret    = [];
    $i      = 0;

    while ($myrow = $xoopsDB->fetchArray($result)) {
        $display = true;
        if ($module_id && $gpermHandler) {
            if (!$gpermHandler->checkRight('lexikon_view', $myrow['categoryID'], $groups, $module_id)) {
                //if (!$gpermHandler->checkRight("lexikon_view", $categoryID, $groups, $module_id)) {
                $display = false;
            }
        }
        if ($display) {
            $ret[$i]['image'] = 'assets/images/lx.png';
            $ret[$i]['link']  = 'entry.php?entryID=' . $myrow['entryID'] . $hightlight_key;
            $ret[$i]['title'] = $myrow['term'];
            $ret[$i]['time']  = $myrow['datesub'];
            $ret[$i]['uid']   = $myrow['uid'];
            ++$i;
        }
    }
    //return $ret;
    //}
    // --- comments search ---
    if ($searchincomments && (isset($limit) && $i <= $limit)) {
        require_once XOOPS_ROOT_PATH . '/include/comment_constants.php';
        $ind = $i;
        $sql = 'SELECT com_id, com_modid, com_itemid, com_created, com_uid, com_title, com_text, com_status
               FROM ' . $xoopsDB->prefix('xoopscomments') . "
               WHERE (com_id>0) AND (com_modid=$module_id) AND (com_status=" . XOOPS_COMMENT_ACTIVE . ') ';
        if (0 != $userid) {
            $sql .= ' AND com_uid=' . $userid . ' ';
        }

        if (is_array($queryarray) && $count = count($queryarray)) {
            $sql .= " AND ((com_title LIKE '%$queryarray[0]%' OR com_text LIKE '%$queryarray[0]%')";
            for ($i = 1; $i < $count; ++$i) {
                $sql .= " $andor ";
                $sql .= "(com_title LIKE '%$queryarray[$i]%' OR com_text LIKE '%$queryarray[$i]%')";
            }
            $sql .= ') ';
        }
        $i      = $ind;
        $sql    .= 'ORDER BY com_created DESC';
        $result = $xoopsDB->query($sql, $limit, $offset);
        while ($myrow = $xoopsDB->fetchArray($result)) {
            $display = true;
            list($entryID, $offline) = $xoopsDB->fetchRow($xoopsDB->query('
                                         SELECT entryID, offline
                                         FROM ' . $xoopsDB->prefix('lxentries') . ' WHERE entryID = ' . $myrow['com_itemid'] . ' '));
            if (1 == $offline) {
                $display = false;
            }
            if ($i + 1 > $limit) {
                $display = false;
            }

            if ($display) {
                $ret[$i]['image'] = 'assets/images/lx.png';
                $ret[$i]['link']  = 'entry.php?entryID=' . $myrow['com_itemid'] . $hightlight_key;
                $ret[$i]['title'] = $myrow['com_title'];
                $ret[$i]['time']  = $myrow['com_created'];
                $ret[$i]['uid']   = $myrow['com_uid'];
                ++$i;
            }
        }
    }

    return $ret;
}
