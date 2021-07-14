<?php
/**
 * Module: Lexikon - glossary module
 * Author: hsalazar
 * Modifs: Yerres
 * Licence: GNU
 */

use Xmf\Module\Admin;
use Xmf\Request;
//use XoopsModules\Tag;
use XoopsModules\Lexikon\{
    Helper,
    Utility,
    LexikonTree
};
/** @var Helper $helper */

require_once __DIR__ . '/admin_header.php';
$myts = \MyTextSanitizer::getInstance();

$helper = Helper::getInstance();

xoops_cp_header();
$adminObject = Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));
$adminObject->addItemButton(_AM_LEXIKON_CREATEENTRY, 'entry.php?op=add', 'add');
$adminObject->displayButton('left');

$op = '';
error_reporting(E_ALL);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
/* -- Available operations -- */
function entryDefault()
{
    global $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModule, $entryID, $pathIcon16;
    $helper = Helper::getInstance();
    require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
    require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
    xoops_load('XoopsUserUtility');
    //    lx_adminMenu(2, _AM_LEXIKON_ENTRIES);

    $startentry = \Xmf\Request::getInt('startentry', 0, 'GET');
    $startcat   = \Xmf\Request::getInt('startcat', 0, 'GET');
    $startsub   = \Xmf\Request::getInt('startsub', 0, 'GET');
    $datesub    = \Xmf\Request::getInt('datesub', 0, 'GET');

    $myts = \MyTextSanitizer::getInstance();

    $result01 = $xoopsDB->query(
        'SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxcategories') . ' '
    );
    [$totalcategories] = $xoopsDB->fetchRow($result01);

    $result02 = $xoopsDB->query(
        'SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxentries') . '
                                   WHERE submit = 0'
    );
    [$totalpublished] = $xoopsDB->fetchRow($result02);

    $result03 = $xoopsDB->query(
        'SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxentries') . "
                                   WHERE submit = '1' AND request = '0' "
    );
    [$totalsubmitted] = $xoopsDB->fetchRow($result03);

    $result04 = $xoopsDB->query(
        'SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxentries') . "
                                   WHERE submit = '1' AND request = '1' "
    );
    [$totalrequested] = $xoopsDB->fetchRow($result04);

    /**
     * Code to show existing terms
     **/

    // create existing terms table
    $resultA1 = $xoopsDB->query(
        'SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxentries') . '
                                   WHERE submit = 0'
    );
    [$numrows] = $xoopsDB->fetchRow($resultA1);

    $sql      = 'SELECT entryID, categoryID, term, uid, datesub, offline
           FROM ' . $xoopsDB->prefix('lxentries') . '
           WHERE submit = 0
           ORDER BY entryID DESC';
    $resultA2 = $xoopsDB->query($sql, $helper->getConfig('perpage'), $startentry);
    $result   = $xoopsDB->query($sql, $helper->getConfig('perpage'));

    echo "  <table class='outer' width='100%' border='0'>
    <tr>
    <td colspan='7' class='odd'>
    <strong>" . _AM_LEXIKON_SHOWENTRIES . ' (' . $totalpublished . ')' . '</strong></td></tr>';
    echo '<tr>';

    echo "<th style='width:40px; text-align:center;'>" . _AM_LEXIKON_ENTRYID . '</td>';
    if (1 == $helper->getConfig('multicats')) {
        echo "<th style='width:20%; text-align:center;'>" . _AM_LEXIKON_ENTRYCATNAME . '</td>';
    }
    echo "<th style='width:*; text-align:center;'>" . _AM_LEXIKON_ENTRYTERM . "</td>
    <th style='width:90px; text-align:center;'>" . _AM_LEXIKON_SUBMITTER . "</td>
    <th style='width:90px; text-align:center;'>" . _AM_LEXIKON_ENTRYCREATED . "</td>
    <th style='width:30px; text-align:center;'>" . _AM_LEXIKON_STATUS . "</td>
    <th style='width:60px; text-align:center;'>" . _AM_LEXIKON_ACTION . '</td>
    </tr>';
    $class = 'odd';
    if ($numrows > 0) {
        // That is, if there ARE entries in the system

        while (list($entryID, $categoryID, $term, $uid, $created, $offline) = $xoopsDB->fetchRow($resultA2)) {
            $resultA3 = $xoopsDB->query(
                'SELECT name
                                           FROM ' . $xoopsDB->prefix('lxcategories') . "
                                           WHERE categoryID = '$categoryID'"
            );
            [$name] = $xoopsDB->fetchRow($resultA3);

            $sentby  = \XoopsUserUtility::getUnameFromId($uid);
            $catname = htmlspecialchars($name);
            $term    = htmlspecialchars($term);
            $created = formatTimestamp($created, 's');
            $modify  = "<a href='entry.php?op=mod&entryID=" . $entryID . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _AM_LEXIKON_EDITENTRY . "'></a>";
            $delete  = "<a href='entry.php?op=del&entryID=" . $entryID . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _AM_LEXIKON_DELETEENTRY . "'></a>";

            if (0 == $offline) {
                $status = '<img src=' . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/on.gif alt='" . _AM_LEXIKON_ENTRYISON . "'>";
            } else {
                $status = '<img src=' . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/off.gif alt='" . _AM_LEXIKON_ENTRYISOFF . "'>";
            }
            echo "<div><tr class='" . $class . "'>";
            $class = ('even' === $class) ? 'odd' : 'even';

            echo "<td align='center'>" . $entryID . '</td>';

            if (1 == $helper->getConfig('multicats')) {
                echo "<td class='odd' style='text-align:left;'>" . $catname . '</td>';
            }
            echo "<td class='odd' style='text-align:left;'><a href='../entry.php?entryID=" . $entryID . "'>" . $term . "</a></td>
            <td class='odd' style='text-align:center;'>" . $sentby . "</td>
            <td class='odd' style='text-align:center;'>" . $created . "</td>
            <td class='odd' style='text-align:center;'>" . $status . "</td>
            <td class='even' style='text-align:center;'>" . $modify . '-' . $delete . '</td>
            </tr></div>';
        }
    } else { // that is, $numrows = 0, there's no entries yet
        echo '<div><tr>';
        echo "<td class='odd' align='center' colspan= '7'>" . _AM_LEXIKON_NOTERMS . '</td>';
        echo '</tr></div>';
    }
    echo "</table>\n";
    $pagenav = new \XoopsPageNav($numrows, $helper->getConfig('perpage'), $startentry, 'startentry');
    echo '<div style="text-align:right;">' . $pagenav->renderNav(8) . '</div>';
    echo "<br>\n";
    echo '</div>';
}

// -- Edit function --
/**
 * @param string $entryID
 */
function entryEdit($entryID = '')
{
    global $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModule, $init;
    $helper = Helper::getInstance();

    $myts    = \MyTextSanitizer::getInstance();
    $utility = new Utility();
    /**
     * Clear all variables before we start
     */
    if (!isset($block)) {
        $block = 1;
    }
    if (!isset($html)) {
        $html = 1;
    }
    if (!isset($smiley)) {
        $smiley = 1;
    }
    if (!isset($xcodes)) {
        $xcodes = 1;
    }
    if (!isset($breaks)) {
        $breaks = 1;
    }
    if (!isset($offline)) {
        $offline = 0;
    }
    if (!isset($submit)) {
        $submit = 0;
    }
    if (!isset($request)) {
        $request = 0;
    }
    if (!isset($notifypub)) {
        $notifypub = 1;
    }
    if (!isset($categoryID)) {
        $categoryID = 1;
    }
    if (!isset($term)) {
        $term = '';
    }
    if (!isset($init)) {
        $init = '';
    }
    if (!isset($definition)) {
        $definition = _AM_LEXIKON_WRITEHERE;
    }
    if (!isset($ref)) {
        $ref = '';
    }
    if (!isset($url)) {
        $url = '';
    }
    if (!isset($datesub)) {
        $datesub = 0;
    }

    // If there is a parameter, and the id exists, retrieve data: we're editing an entry
    if ($entryID) {
        $result = $xoopsDB->query(
            '
                                     SELECT categoryID, term, init, definition, ref, url, uid, submit, datesub, html, smiley, xcodes, breaks, block, offline, notifypub, request
                                     FROM ' . $xoopsDB->prefix('lxentries') . "
                                     WHERE entryID = '$entryID'"
        );
        [$categoryID, $term, $init, $definition, $ref, $url, $uid, $submit, $datesub, $html, $smiley, $xcodes, $breaks, $block, $offline, $notifypub, $request] = $xoopsDB->fetchRow($result);

        if (!$xoopsDB->getRowsNum($result)) {
            redirect_header('index.php', 1, _AM_LEXIKON_NOENTRYTOEDIT);
        }
        $term = (htmlspecialchars($term));

        echo "<strong style='color: #2F5376; margin-top:6px; font-size:medium'>" . _AM_LEXIKON_ADMINENTRYMNGMT . '</strong>';
        $sform = new \XoopsThemeForm(_AM_LEXIKON_MODENTRY . ": $term", 'op', xoops_getenv('SCRIPT_NAME'), 'post', true);
    } else { // there's no parameter, so we're adding an entry
        $result01 = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('lxcategories') . ' ');
        [$totalcats] = $xoopsDB->fetchRow($result01);
        if (0 == $totalcats && 1 == $helper->getConfig('multicats')) {
            redirect_header('index.php', 1, _AM_LEXIKON_NEEDONECOLUMN);
        }
        $uid = $xoopsUser->getVar('uid');
        echo "<strong style='color: #2F5376; margin-top:6px; font-size:medium'>" . _AM_LEXIKON_ADMINENTRYMNGMT . '</strong>';
        $sform = new \XoopsThemeForm(_AM_LEXIKON_NEWENTRY, 'op', xoops_getenv('SCRIPT_NAME'), 'post', true);
    }

    $sform->setExtra('enctype="multipart/form-data"');
    // Category selector
    if (1 == $helper->getConfig('multicats')) {
        $mytree         = new LexikonTree($xoopsDB->prefix('lxcategories'), 'categoryID', '0');
        $categoryselect = new \XoopsFormSelect(_AM_LEXIKON_CATNAME, 'categoryID', $categoryID);
        $tbl            = [];
        $tbl            = $mytree->getChildTreeArray(0, 'name');
        foreach ($tbl as $oneline) {
            if ('.' === $oneline['prefix']) {
                $oneline['prefix'] = '';
            }
            $oneline['prefix'] = str_replace('.', '-', $oneline['prefix']);
            $categoryselect->addOption($oneline['categoryID'], $oneline['prefix'] . ' ' . $oneline['name']);
        }
        $sform->addElement($categoryselect, true);
    }

    // Author selector
    ob_start();
    $utility::getUserForm((int)$uid);
    $sform->addElement(new \XoopsFormLabel(_AM_LEXIKON_AUTHOR, ob_get_contents()));
    ob_end_clean();

    // Initial selector
    ob_start();
    lx_getinit((int)$init);
    $sform->addElement(new \XoopsFormLabel(_AM_LEXIKON_INIT, ob_get_contents()));
    ob_end_clean();

    // Term, definition, reference and related URL
    $sform->addElement(new \XoopsFormText(_AM_LEXIKON_ENTRYTERM, 'term', 50, 80, $term), true);

    // set editor according to the module's option "form_options"
    $editor = $utility::getWysiwygForm(_AM_LEXIKON_ENTRYDEF, 'definition', $definition, 15, 60);
    if (_MD_LEXIKON_WRITEHERE == $definition) {
        $editor->setExtra('onfocus="this.select()"');
    }
    $sform->addElement($editor, true);
    unset($editor);

    $sform->addElement(new \XoopsFormTextArea(_AM_LEXIKON_ENTRYREFERENCE, 'ref', $ref, 5, 60), false);
    $sform->addElement(new \XoopsFormText(_AM_LEXIKON_ENTRYURL, 'url', 50, 80, $url), false);

    // tags of this term - for module 'Tag'
    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $tagsModule    = $moduleHandler->getByDirname('tag');
//    if (is_object($tagsModule)) {
//        require_once XOOPS_ROOT_PATH . '/modules/tag/include/formtag.php';
//        $sform->addElement(new \TagFormTag('item_tag', 60, 255, $entryID, $catid = 0));
//    }

//    if (class_exists('TagFormTag')) {
//        $formobj['tags'] = new FormTag('tags', 60, 255, $xcontent['xcontent']->getVar('storyid'), $xcontent['xcontent']->getVar('catid'));
//    } else {
//        $formobj['tags'] = new \XoopsFormHidden('tags', $xcontent['xcontent']->getVar('tags'));
//    }


    // Code to take entry offline, for maintenance purposes
    $offline_radio = new \XoopsFormRadioYN(_AM_LEXIKON_SWITCHOFFLINE, 'offline', $offline, ' ' . _AM_LEXIKON_YES . '', ' ' . _AM_LEXIKON_NO . '');
    $sform->addElement($offline_radio);

    // Code to put entry in block
    $block_radio = new \XoopsFormRadioYN(_AM_LEXIKON_BLOCK, 'block', $block, ' ' . _AM_LEXIKON_YES . '', ' ' . _AM_LEXIKON_NO . '');
    $sform->addElement($block_radio);

    // VARIOUS OPTIONS
    $options_tray = new \XoopsFormElementTray(_AM_LEXIKON_OPTIONS, '<br>');
    if ($submit) {
        $notify_checkbox = new \XoopsFormCheckBox('', 'notifypub', $notifypub);
        $notify_checkbox->addOption(1, _AM_LEXIKON_NOTIFYPUBLISH);
        $options_tray->addElement($notify_checkbox);
    } else {
        $notifypub = 0;
    }
    $html_checkbox = new \XoopsFormCheckBox('', 'html', $html);
    $html_checkbox->addOption(1, _AM_LEXIKON_DOHTML);
    $options_tray->addElement($html_checkbox);

    $smiley_checkbox = new \XoopsFormCheckBox('', 'smiley', $smiley);
    $smiley_checkbox->addOption(1, _AM_LEXIKON_DOSMILEY);
    $options_tray->addElement($smiley_checkbox);

    $xcodes_checkbox = new \XoopsFormCheckBox('', 'xcodes', $xcodes);
    $xcodes_checkbox->addOption(1, _AM_LEXIKON_DOXCODE);
    $options_tray->addElement($xcodes_checkbox);

    $breaks_checkbox = new \XoopsFormCheckBox('', 'breaks', $breaks);
    $breaks_checkbox->addOption(1, _AM_LEXIKON_BREAKS);
    $options_tray->addElement($breaks_checkbox);

    $sform->addElement($options_tray);

    $sform->addElement(new \XoopsFormHidden('entryID', $entryID));

    $buttonTray = new \XoopsFormElementTray('', '');
    $hidden     = new \XoopsFormHidden('op', 'addentry');
    $buttonTray->addElement($hidden);

    if (!$entryID) { // there's no entryID? Then it's a new entry
        $butt_create = new \XoopsFormButton('', '', _AM_LEXIKON_CREATE, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addentry\'"');
        $buttonTray->addElement($butt_create);

        $butt_clear = new \XoopsFormButton('', '', _AM_LEXIKON_CLEAR, 'reset');
        $buttonTray->addElement($butt_clear);

        $butt_cancel = new \XoopsFormButton('', '', _AM_LEXIKON_CANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $buttonTray->addElement($butt_cancel);
    } else { // else, we're editing an existing entry
        $butt_create = new \XoopsFormButton('', '', _AM_LEXIKON_MODIFY, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addentry\'"');
        $buttonTray->addElement($butt_create);

        $butt_cancel = new \XoopsFormButton('', '', _AM_LEXIKON_CANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $buttonTray->addElement($butt_cancel);
    }

    $sform->addElement($buttonTray);
    $sform->display();
    unset($hidden);
}

/* Save */
/**
 * @param string $entryID
 */
function entrySave($entryID = '')
{
    global $xoopsUser, $xoopsConfig, $xoopsModule, $xoopsDB;
    $helper  = Helper::getInstance();
    $utility = new Utility();
    $myts    = \MyTextSanitizer::getInstance();
    $entryID = \Xmf\Request::getInt('entryID', \Xmf\Request::getInt('entryID', 0, 'GET'), 'POST');
    if (1 == $helper->getConfig('multicats')) {
        $categoryID = Request::getInt('categoryID', 0);
    } else {
        $categoryID = 1;
    }
    $block  = \Xmf\Request::getInt('block', \Xmf\Request::getInt('block', 0, 'GET'), 'POST');
    $breaks = \Xmf\Request::getInt('breaks', \Xmf\Request::getInt('breaks', 0, 'GET'), 'POST');

    $html    = \Xmf\Request::getInt('html', \Xmf\Request::getInt('html', 0, 'GET'), 'POST');
    $smiley  = \Xmf\Request::getInt('smiley', \Xmf\Request::getInt('smiley', 0, 'GET'), 'POST');
    $xcodes  = \Xmf\Request::getInt('xcodes', \Xmf\Request::getInt('xcodes', 0, 'GET'), 'POST');
    $offline = \Xmf\Request::getInt('offline', \Xmf\Request::getInt('offline', 0, 'GET'), 'POST');
    $term    = $myts->addSlashes(xoops_trim($_POST['term']));
    // LionHell pour initiale automatique
    $init = mb_substr($term, 0, 1);
    $init = preg_match('/[a-zA-Zа-яА-Я0-9]/', $init) ? mb_strtoupper($init) : '#';
    // Fin LionHell

    $definition = $myts->xoopsCodeDecode($myts->censorString($_POST['definition']), $allowimage = 1);
    $ref        = isset($_POST['ref']) ? $myts->addSlashes($myts->censorString($_POST['ref'])) : '';
    $url        = isset($_POST['url']) ? $myts->addSlashes($_POST['url']) : '';

    $date      = time();
    $submit    = 0;
    $notifypub = \Xmf\Request::getInt('notifypub', \Xmf\Request::getInt('notifypub', 0, 'GET'), 'POST');
    $request   = 0;
    $uid       = \Xmf\Request::getInt('author', $xoopsUser->uid(), 'POST');

    //-- module Tag
    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
//    $tagsModule    = $moduleHandler->getByDirname('tag');
//    if (is_object($tagsModule)) {
//        $tagHandler = Tag\Helper::getInstance()->getHandler('Tag'); // xoops_getModuleHandler('tag', 'tag');
//        $tagHandler->updateByItem($_POST['item_tag'], $entryID, $xoopsModule->getVar('dirname'), $catid = 0);
//    }
    // Save to database
    if (!$entryID) {
        // verify that the term does not exists
        if ($utility::isTermPresent($term, $xoopsDB->prefix('lxentries'))) {
            redirect_header('<script>javascript:history.go(-1)</script>', 2, _AM_LEXIKON_ITEMEXISTS . '<br>' . $term);
        }
        if ($xoopsDB->query(
            'INSERT INTO '
            . $xoopsDB->prefix('lxentries')
            . " (entryID, categoryID, term, init, definition, ref, url, uid, submit, datesub, html, smiley, xcodes, breaks, block, offline, notifypub, request ) VALUES (0, '$categoryID', '$term', '$init', '$definition', '$ref', '$url', '$uid', '$submit', '$date', '$html', '$smiley', '$xcodes', '$breaks', '$block', '$offline', '$notifypub', '$request' )"
        )) {
            $newid = $xoopsDB->getInsertId();
            // Increment author's posts count (only if it's a new definition)
            if (is_object($xoopsUser) && empty($entryID)) {
                /** @var \XoopsMemberHandler $memberHandler */
                $memberHandler = xoops_getHandler('member');
                $submitter     = $memberHandler->getUser($uid);
                if (is_object($submitter)) {
                    $submitter->setVar('posts', $submitter->getVar('posts') + 1);
                    $res = $memberHandler->insertUser($submitter, true);
                    unset($submitter);
                }
            }
            // trigger Notification only if its a new definition
            if (!empty($helper->getConfig('notification_enabled'))) {
                global $xoopsModule;
                if (0 == $newid) {
                    $newid = $xoopsDB->getInsertId();
                }
                /** @var XoopsNotificationHandler $notificationHandler */
                $notificationHandler   = xoops_getHandler('notification');
                $tags                  = [];
                $shortdefinition       = htmlspecialchars(xoops_substr(strip_tags($definition), 0, 45));
                $tags['ITEM_NAME']     = $term;
                $tags['ITEM_BODY']     = $shortdefinition;
                $tags['DATESUB']       = formatTimestamp($date, 'd M Y');
                $tags['ITEM_URL']      = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/entry.php?entryID=' . $newid;
                $sql                   = 'SELECT name FROM ' . $xoopsDB->prefix('lxcategories') . ' WHERE categoryID=' . $categoryID;
                $result                = $xoopsDB->query($sql);
                $row                   = $xoopsDB->fetchArray($result);
                $tags['CATEGORY_NAME'] = $row['name'];
                $tags['CATEGORY_URL']  = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/category.php?categoryID=' . $categoryID;
                $notificationHandler->triggerEvent('global', 0, 'new_post', $tags);
                $notificationHandler->triggerEvent('category', $categoryID, 'new_post', $tags);
            }
            $utility::calculateTotals();
            redirect_header('entry.php', 1, _AM_LEXIKON_ENTRYCREATEDOK);
        } else {
            redirect_header('index.php', 1, _AM_LEXIKON_ENTRYNOTCREATED);
        }
    } else { // That is, $entryID exists, thus we're editing an entry
        if ($xoopsDB->query(
            'UPDATE '
            . $xoopsDB->prefix('lxentries')
            . " SET term = '$term', categoryID = '$categoryID', init = '$init', definition = '$definition', ref = '$ref', url = '$url', uid = '$uid', submit = '$submit', datesub = '$date', html = '$html', smiley = '$smiley', xcodes = '$xcodes', breaks = '$breaks', block = '$block', offline = '$offline', notifypub = '$notifypub', request = '$request' WHERE entryID = '$entryID'"
        )) {
            // trigger Notification only if its a new submission
            if (!empty($helper->getConfig('notification_enabled'))) {
                global $xoopsModule;
                /** @var \XoopsNotificationHandler $notificationHandler */
                $notificationHandler = xoops_getHandler('notification');
                $tags                = [];
                $shortdefinition     = htmlspecialchars(xoops_substr(strip_tags($definition), 0, 45));
                $tags['ITEM_NAME']   = $term;
                $tags['ITEM_BODY']   = $shortdefinition;
                $tags['DATESUB']     = formatTimestamp($date, 'd M Y');
                $tags['ITEM_URL']    = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/entry.php?entryID=' . $entryID;
                $sql                 = 'SELECT name FROM ' . $xoopsDB->prefix('lxcategories') . ' WHERE categoryID=' . $categoryID;
                $result              = $xoopsDB->query($sql);
                $row                 = $xoopsDB->fetchArray($result);
                $tags['CATEGORY_NAME'] = $row['name'];
                $tags['CATEGORY_URL']  = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/category.php?categoryID=' . $categoryID;
                $notificationHandler->triggerEvent('global', 0, 'new_post', $tags);
                $notificationHandler->triggerEvent('category', $categoryID, 'new_post', $tags);
                $notificationHandler->triggerEvent('term', $entryID, 'approve', $tags);
            }

            $utility::calculateTotals();
            if ('0' == $notifypub) {
                redirect_header('entry.php', 1, _AM_LEXIKON_ENTRYMODIFIED);
            } else {
                $user        = new \XoopsUser($uid);
                $userMessage = sprintf(_MD_LEXIKON_GOODDAY2, $user->getVar('uname'));
                $userMessage .= "\n\n";
                if ('1' == $request) {
                    $userMessage .= sprintf(_MD_LEXIKON_CONFREQ, $xoopsConfig['sitename']);
                } else {
                    $userMessage .= sprintf(_MD_LEXIKON_CONFSUB);
                }
                $userMessage .= "\n";
                $userMessage .= sprintf(_MD_LEXIKON_APPROVED, $xoopsConfig['sitename']);
                $userMessage .= "\n\n";
                $userMessage .= sprintf(_MD_LEXIKON_REGARDS);
                $userMessage .= "\n";
                $userMessage .= "__________________\n";
                $userMessage .= '' . $xoopsConfig['sitename'] . ' ' . _MD_LEXIKON_WEBMASTER . "\n";
                $userMessage .= '' . $xoopsConfig['adminmail'] . '';
                $xoopsMailer = xoops_getMailer();
                $xoopsMailer->useMail();
                $xoopsMailer->setToEmails($user->getVar('email'));
                $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
                $xoopsMailer->setFromName($xoopsConfig['sitename'] . ' - ' . $xoopsModule->name());
                if ('1' == $request) {
                    $conf_subject = sprintf(_MD_LEXIKON_SUBJECTREQ, $xoopsConfig['sitename']);
                } else {
                    $conf_subject = sprintf(_MD_LEXIKON_SUBJECTSUB, $xoopsConfig['sitename']);
                }
                $xoopsMailer->setSubject($conf_subject);
                $xoopsMailer->setBody($userMessage);
                $xoopsMailer->send();
                $messagesent = sprintf(_AM_LEXIKON_SENTCONFIRMMAIL, $user->getVar('uname'));

                redirect_header('entry.php', 1, $messagesent);
            }
            redirect_header('entry.php', 1, _AM_LEXIKON_ENTRYMODIFIED);
        } else {
            redirect_header('index.php', 1, _AM_LEXIKON_ENTRYNOTUPDATED);
        }
    }
}

/**
 * @param string $entryID
 */
function entryDelete($entryID = '')
{
    global $xoopsDB, $xoopsModule;
    $entryID = \Xmf\Request::getInt('entryID', \Xmf\Request::getInt('entryID', 0, 'GET'), 'POST');
    $ok      = \Xmf\Request::getInt('ok', 0, 'POST');
    $result  = $xoopsDB->query('SELECT entryID, term, uid FROM ' . $xoopsDB->prefix('lxentries') . " WHERE entryID = $entryID");
    [$entryID, $term, $uid] = $xoopsDB->fetchRow($result);

    // confirmed, so delete
    if (1 == $ok) {
        $result = $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('lxentries') . " WHERE entryID = $entryID");
        xoops_comment_delete($xoopsModule->getVar('mid'), $entryID);
        // delete notifications
        xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'term', $entryID);
        // update user posts
        if (!empty($uid)) {
            $submitter = new \XoopsUser($uid);
            /** @var \XoopsMemberHandler $memberHandler */
            $memberHandler = xoops_getHandler('member');
            $memberHandler->updateUserByField($submitter, 'posts', $submitter->getVar('posts') - 1);
        }
        redirect_header('entry.php', 1, sprintf(_AM_LEXIKON_ENTRYISDELETED, $term));
    } else {
        xoops_confirm(['op' => 'del', 'entryID' => $entryID, 'ok' => 1, 'term' => $term], 'entry.php', _AM_LEXIKON_DELETETHISENTRY . '<br>' . $term, _AM_LEXIKON_DELETE);
        require_once __DIR__ . '/admin_footer.php';
    }
    exit();
}

/* -- Available operations -- */
$op = 'default';
if (\Xmf\Request::hasVar('op', 'POST')) {
    $op = $_POST['op'];
} else {
    if (\Xmf\Request::hasVar('op', 'GET')) {
        $op = $_GET['op'];
    }
}
switch ($op) {
    case 'mod':
        $entryID = \Xmf\Request::getInt('entryID', \Xmf\Request::getInt('entryID', 0, 'POST'), 'GET');
        entryEdit($entryID);
        break;
    case 'add':
        entryEdit();
        break;
    case 'addentry':
        entrySave();
        break;
    case 'del':
        entryDelete();
        break;
    case 'default':
    default:
        entryDefault();
        break;
}
require_once __DIR__ . '/admin_footer.php';
