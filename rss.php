<?php
/**
 * Module: Lexikon - glossary module
 * Author: Yerres
 * Licence: GNU
 */

global $xoopsModule, $xoopsUser;

include __DIR__ . '/../../mainfile.php';
$GLOBALS['xoopsLogger']->activated = false;
if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
//error_reporting(E_ALL |E_ERROR | E_WARNING | E_PARSE);
header('Content-Type:text/xml; charset=utf-8');
include_once $GLOBALS['xoops']->path('class/template.php');
$tpl                 = new XoopsTpl();
$tpl->caching        = 0;
$tpl->cache_lifetime = 3600;

$db           = XoopsDatabaseFactory::getDatabaseConnection();
$myts         = MyTextSanitizer::getInstance();
$category_rss = isset($_GET['categoryID']) ? $_GET['categoryID'] : 0;
//permissions
$gpermHandler = xoops_getHandler('groupperm');
$groups        = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname('lexikon');
$module_id     = $module->getVar('mid');
$allowed_cats  = $gpermHandler->getItemIds('lexikon_view', $groups, $module_id);
$catids        = implode(',', $allowed_cats);
$catperms      = " AND categoryID IN ($catids) ";

if ($category_rss <= 0) {
    $result = $db->query('SELECT * FROM '
                          . $db->prefix('lxentries')
                          . '  WHERE offline=0 '
                          . $catperms
                          . "  ORDER BY 'datesub' DESC LIMIT 0,50");
} else {
    $result = $db->query('SELECT * FROM '
                          . $db->prefix('lxentries')
                          . " WHERE categoryID='$category_rss'  "
                          . $catperms
                          . '  ORDER BY `datesub` DESC LIMIT 0,50');
    $info   = $db->fetchArray($db->query('SELECT * FROM '
                                          . $db->prefix('lxcategories')
                                          . " WHERE categoryID='$category_rss'"));
}
if (!$tpl->is_cached('db:lexikon_rss.tpl')) {
    xoops_load('XoopsLocal');
    if ($category_rss > 0) {
        $tpl->assign('channel_title', htmlspecialchars($xoopsConfig['sitename'] . ' - ' . sprintf(_MD_LEXIKON_INCATS, $info['name']), ENT_QUOTES, 'utf-8'));
        $tpl->assign('channel_link', XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/category.php?categoryID=' . $category_rss);
        $tpl->assign('channel_desc', sprintf(_MD_LEXIKON_INCATS_DESC, $info['name'], $xoopsConfig['sitename']));
        $tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
        $tpl->assign('channel_generator', 'XOOPS Lexikon');
        $tpl->assign('channel_category', 'Categories');
        $tpl->assign('channel_editor', $xoopsConfig['adminmail']);
        $tpl->assign('channel_webmaster', $xoopsConfig['adminmail']);
        $tpl->assign('channel_language', _LANGCODE);
        $tpl->assign('image_url', XOOPS_URL . '/images/logo.gif');
        $dimention = getimagesize(XOOPS_ROOT_PATH . '/images/logo.gif');
        if (empty($dimention[0])) {
            $width = 128;
        } else {
            $width = ($dimention[0] > 128) ? 128 : $dimention[0];
        }
        if (empty($dimention[1])) {
            $height = 128;
        } else {
            $height = ($dimention[1] > 128) ? 128 : $dimention[1];
        }
        $tpl->assign('image_width', $width);
        $tpl->assign('image_height', $height);
    } else {
        $tpl->assign('channel_title', htmlspecialchars($xoopsConfig['sitename'] . ' - ' . sprintf(_MD_LEXIKON_INCATS, $xoopsModule->getVar('dirname')), ENT_QUOTES, 'utf-8'));
        $tpl->assign('channel_link', XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname'));
        $tpl->assign('channel_desc', sprintf(_MD_LEXIKON_LASTDESC, $xoopsConfig['sitename']));
        $tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
        $tpl->assign('channel_generator', 'XOOPS Lexikon');
        $tpl->assign('channel_category', 'Entries');
        $tpl->assign('channel_editor', $xoopsConfig['adminmail']);
        $tpl->assign('channel_webmaster', $xoopsConfig['adminmail']);
        $tpl->assign('channel_language', _LANGCODE);
        $tpl->assign('image_url', XOOPS_URL . '/images/logo.gif');
        $dimention = getimagesize(XOOPS_ROOT_PATH . '/images/logo.gif');
        if (empty($dimention[0])) {
            $width = 128;
        } else {
            $width = ($dimention[0] > 128) ? 128 : $dimention[0];
        }
        if (empty($dimention[1])) {
            $height = 128;
        } else {
            $height = ($dimention[1] > 128) ? 128 : $dimention[1];
        }
        $tpl->assign('image_width', $width);
        $tpl->assign('image_height', $height);
    }
    while ($row = $db->fetchArray($result)) {
        $tpl->append('items', array(
            'title'       => htmlspecialchars($row['term'], ENT_QUOTES, 'utf-8'),
            'link'        => XOOPS_URL . '/modules/lexikon/entry.php?entryID=' . $row['entryID'],
            'guid'        => XOOPS_URL . '/modules/lexikon/entry.php?entryID=' . $row['entryID'],
            'pubdate'     => formatTimestamp($row['datesub'], 'rss'),
            'description' => htmlspecialchars($myts->displayTarea($row['definition'], 1, 1, 1), ENT_QUOTES)
        ));
    }
}
$tpl->display('db:lexikon_rss.tpl');
