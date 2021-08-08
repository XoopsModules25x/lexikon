<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Module: lexikon
 *
 * @category        Module
 * @package         lexikon
 * @author          XOOPS Development Team <name@site.com> - <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 * @link            https://xoops.org/
 * @since           1.0.0
 */

use Xmf\Module\Helper\Permission;
use Xmf\Request;
use XoopsModules\Lexikon\{
    CategoriesHandler,
    Entries,
    EntriesHandler,
    Helper,
    Utility
};
/** @var Helper $helper */
/** @var Admin $adminObject */

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();
//It recovered the value of argument op in URL$
$op    = Request::getString('op', 'list');
$order = Request::getString('order', 'desc');
$sort  = Request::getString('sort', '');

xoops_load('XoopsUserUtility');
$moduleDirName = \basename(\dirname(__DIR__));

$adminObject->displayNavigation(basename(__FILE__));
$permHelper = new Permission($moduleDirName);
$uploadDir  = XOOPS_UPLOAD_PATH . "/$moduleDirName/images/";
$uploadUrl  = XOOPS_UPLOAD_URL . "/$moduleDirName/images/";

$db                = \XoopsDatabaseFactory::getDatabaseConnection();
$entriesHandler    = new EntriesHandler($db);
$categoriesHandler = new CategoriesHandler($db);

switch ($op) {
    case 'list':
    default:
        $adminObject->addItemButton(_AM_LEXIKON_ADD_ENTRIES, 'entries.php?op=new', 'add');
        echo $adminObject->displayButton('left');
        $start                  = Request::getInt('start', 0);
        $entriesPaginationLimit = $GLOBALS['xoopsModuleConfig']['userpager'];

        $criteria = new \CriteriaCompo();
        $criteria->setSort('entryID ASC, term');
        $criteria->setOrder('ASC');
        $criteria->setLimit($entriesPaginationLimit);
        $criteria->setStart($start);
        $entriesTempRows  = $entriesHandler->getCount();
        $entriesTempArray = $entriesHandler->getAll($criteria); /*
//
//
                    <th class='center width5'>".AM_LEXIKON_FORM_ACTION."</th>
//                    </tr>";
//            $class = "odd";
*/

        // Display Page Navigation
        if ($entriesTempRows > $entriesPaginationLimit) {
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

            $pagenav = new \XoopsPageNav($entriesTempRows, $entriesPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . '');
            $GLOBALS['xoopsTpl']->assign('pagenav', null === $pagenav ? $pagenav->renderNav() : '');
        }

        $GLOBALS['xoopsTpl']->assign('entriesRows', $entriesTempRows);
        $entriesArray = [];

        //    $fields = explode('|', entryID:int:8::NOT NULL::primary:ID|categoryID:tinyint:4::NOT NULL:0::Category|term:varchar:255::NOT NULL:0::Term|init:varchar:1::NOT NULL:0::Init|definition:text:0::NOT NULL:::Definition|ref:text:0::NOT NULL:::Reference|url:varchar:255::NOT NULL:0::URL|uid:int:6::NULL:1::User|submit:datetime:1::NOT NULL:0::Submitter|datesub:datetime:11::NOT NULL:1033141070::Submitted|counter:int:8:unsigned:NOT NULL:0::Counter|html:int:11::NOT NULL:0::HTML|smiley:int:11::NOT NULL:0::Smiley|xcodes:int:11::NOT NULL:0::xCodes|breaks:int:11::NOT NULL:1::Breaks|block:int:11::NOT NULL:0::Block|offline:int:11::NOT NULL:0::Offline|notifypub:int:11::NOT NULL:0::Notify on Pub|request:int:11::NOT NULL:0::Request|comments:int:11:unsigned:NOT NULL:0::Comments|item_tag:text:0::NULL:::Tag);
        //    $fieldsCount    = count($fields);

        $criteria = new \CriteriaCompo();

        //$criteria->setOrder('DESC');
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($entriesPaginationLimit);
        $criteria->setStart($start);

        $entriesCount     = $entriesHandler->getCount($criteria);
        $entriesTempArray = $entriesHandler->getAll($criteria);

        //    for ($i = 0; $i < $fieldsCount; ++$i) {
        if ($entriesCount > 0) {
            foreach (array_keys($entriesTempArray) as $i) {
                //        $field = explode(':', $fields[$i]);

                $selectorentryID = Utility::selectSorting(_AM_LEXIKON_ENTRIES_ENTRYID, 'entryID');
                $GLOBALS['xoopsTpl']->assign('selectorentryID', $selectorentryID);
                $entryID = $entriesTempArray[$i]->getVar('entryID');
                $entriesArray['entryID'] = $entryID;

                $selectorcategoryID = Utility::selectSorting(_AM_LEXIKON_ENTRIES_CATEGORYID, 'categoryID');
                $GLOBALS['xoopsTpl']->assign('selectorcategoryID', $selectorcategoryID);
                $categoryID = $entriesTempArray[$i]->getVar('categoryID');
                $categoryName = $categoriesHandler->get($entriesTempArray[$i]->getVar('categoryID'))->getVar('name');
                $entriesArray['categoryID']  = "<a href='../category.php?categoryID=" . $categoryID . "'>" .  $categoryName . '</a>';

                $selectorterm = Utility::selectSorting(_AM_LEXIKON_ENTRIES_TERM, 'term');
                $GLOBALS['xoopsTpl']->assign('selectorterm', $selectorterm);
                $entriesArray['term'] = $entriesTempArray[$i]->getVar('term');
                $entryTerm = $entriesTempArray[$i]->getVar('term');
                $entriesArray['term']  = "<a href='../entry.php?entryID=" . $entryID . "'>" .  $entryTerm . '</a>';


                $selectorinit = Utility::selectSorting(_AM_LEXIKON_ENTRIES_INIT, 'init');
                $GLOBALS['xoopsTpl']->assign('selectorinit', $selectorinit);
                $entriesArray['init'] = $entriesTempArray[$i]->getVar('init');

                $selectordefinition = Utility::selectSorting(_AM_LEXIKON_ENTRIES_DEFINITION, 'definition');
                $GLOBALS['xoopsTpl']->assign('selectordefinition', $selectordefinition);

                $entriesArray['definition'] = Utility::truncateTagSafe($entriesTempArray[$i]->getVar('definition'), 80, $etc = '...', $breakWords = false);

                $selectorref = Utility::selectSorting(_AM_LEXIKON_ENTRIES_REF, 'ref');
                $GLOBALS['xoopsTpl']->assign('selectorref', $selectorref);
                $entriesArray['ref'] = $entriesTempArray[$i]->getVar('ref');

                $selectorurl = Utility::selectSorting(_AM_LEXIKON_ENTRIES_URL, 'url');
                $GLOBALS['xoopsTpl']->assign('selectorurl', $selectorurl);
                $entriesArray['url'] = $entriesTempArray[$i]->getVar('url');

                $selectorsubmit = Utility::selectSorting(_SUBMIT, 'submit');
                $GLOBALS['xoopsTpl']->assign('selectorsubmit', $selectorsubmit);
                $entriesArray['submit'] = $entriesTempArray[$i]->getVar('submit');

                $selectordatesub = Utility::selectSorting(_AM_LEXIKON_ENTRIES_DATESUB, 'datesub');
                $GLOBALS['xoopsTpl']->assign('selectordatesub', $selectordatesub);
                //                $entriesArray['datesub'] = date(_DATESTRING, strtotime($entriesTempArray[$i]->getVar('datesub')));
                $date                    = $entriesTempArray[$i]->getVar('datesub');
                $entriesArray['datesub'] = formatTimestamp($date, _SHORTDATESTRING);

                //                formatTimestamp($date, 'd M Y')


                $selectoruid = Utility::selectSorting(_AM_LEXIKON_ENTRIES_UID, 'uid');
                $GLOBALS['xoopsTpl']->assign('selectoruid', $selectoruid);
                $userId = $entriesTempArray[$i]->getVar('uid');
                $userName = \XoopsUserUtility::getUnameFromId($entriesTempArray[$i]->getVar('uid'));
//                $entriesArray['uid'] = \XoopsUserUtility::getUnameFromId($entriesTempArray[$i]->getVar('uid'));

                $entriesArray['uid']  = "<a href='" . XOOPS_URL . '/modules/profile/userinfo.php?uid=' . $userId . "'>" . $userName . '</a>';



                $selectorcounter = Utility::selectSorting(_AM_LEXIKON_ENTRIES_COUNTER, 'counter');
                $GLOBALS['xoopsTpl']->assign('selectorcounter', $selectorcounter);
                $entriesArray['counter'] = $entriesTempArray[$i]->getVar('counter');

                $selectorhtml = Utility::selectSorting(_AM_LEXIKON_ENTRIES_HTML, 'html');
                $GLOBALS['xoopsTpl']->assign('selectorhtml', $selectorhtml);
                $entriesArray['html'] = $entriesTempArray[$i]->getVar('html');

                $selectorsmiley = Utility::selectSorting(_AM_LEXIKON_ENTRIES_SMILEY, 'smiley');
                $GLOBALS['xoopsTpl']->assign('selectorsmiley', $selectorsmiley);
                $entriesArray['smiley'] = $entriesTempArray[$i]->getVar('smiley');

                $selectorxcodes = Utility::selectSorting(_AM_LEXIKON_ENTRIES_XCODES, 'xcodes');
                $GLOBALS['xoopsTpl']->assign('selectorxcodes', $selectorxcodes);
                $entriesArray['xcodes'] = $entriesTempArray[$i]->getVar('xcodes');

                $selectorbreaks = Utility::selectSorting(_AM_LEXIKON_ENTRIES_BREAKS, 'breaks');
                $GLOBALS['xoopsTpl']->assign('selectorbreaks', $selectorbreaks);
                $entriesArray['breaks'] = $entriesTempArray[$i]->getVar('breaks');

                $selectorblock = Utility::selectSorting(_AM_LEXIKON_ENTRIES_BLOCK, 'block');
                $GLOBALS['xoopsTpl']->assign('selectorblock', $selectorblock);
                $entriesArray['block'] = $entriesTempArray[$i]->getVar('block');

                $selectoroffline = Utility::selectSorting(_MD_LEXIKON_ENTRIES_ONLINE, 'online');
                $GLOBALS['xoopsTpl']->assign('selectoroffline', $selectoroffline);
                //                $entriesArray['offline'] = $entriesTempArray[$i]->getVar('offline');
                $entriesArray['offline'] = (1 == $entriesTempArray[$i]->getVar('offline') ? $icons['0'] : "<border='0'>" . $icons['1']);

                $selectornotifypub = Utility::selectSorting(_AM_LEXIKON_ENTRIES_NOTIFYPUB, 'notifypub');
                $GLOBALS['xoopsTpl']->assign('selectornotifypub', $selectornotifypub);
                $entriesArray['notifypub'] = $entriesTempArray[$i]->getVar('notifypub');

                $selectorrequest = Utility::selectSorting(_AM_LEXIKON_ENTRIES_REQUEST, 'request');
                $GLOBALS['xoopsTpl']->assign('selectorrequest', $selectorrequest);
                $entriesArray['request'] = $entriesTempArray[$i]->getVar('request');

                $selectorcomments = Utility::selectSorting(_AM_LEXIKON_ENTRIES_COMMENTS, 'comments');
                $GLOBALS['xoopsTpl']->assign('selectorcomments', $selectorcomments);
                $entriesArray['comments'] = $entriesTempArray[$i]->getVar('comments');

                $selectoritem_tag = Utility::selectSorting(_AM_LEXIKON_ENTRIES_ITEM_TAG, 'item_tag');
                $GLOBALS['xoopsTpl']->assign('selectoritem_tag', $selectoritem_tag);
                $entriesArray['item_tag']    = strip_tags($entriesTempArray[$i]->getVar('item_tag'));
                $entriesArray['edit_delete'] = "<a href='entries.php?op=edit&entryID=" . $i . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
               <a href='entries.php?op=delete&entryID=" . $i . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
               <a href='entries.php?op=clone&entryID=" . $i . "'><img src=" . $pathIcon16 . "/editcopy.png alt='" . _CLONE . "' title='" . _CLONE . "'></a>";

                $GLOBALS['xoopsTpl']->append_by_ref('entriesArrays', $entriesArray);
                unset($entriesArray);
            }
            unset($entriesTempArray);
            // Display Navigation
            if ($entriesCount > $entriesPaginationLimit) {
                require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($entriesCount, $entriesPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . '');
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }

            //                     echo "<td class='center width5'>

            //                    <a href='entries.php?op=edit&entryID=".$i."'><img src=".$pathIcon16."/edit.png alt='"._EDIT."' title='"._EDIT."'></a>
            //                    <a href='entries.php?op=delete&entryID=".$i."'><img src=".$pathIcon16."/delete.png alt='"._DELETE."' title='"._DELETE."'></a>
            //                    </td>";

            //                echo "</tr>";

            //            }

            //            echo "</table><br><br>";

            //        } else {

            //            echo "<table width='100%' cellspacing='1' class='outer'>

            //                    <tr>

            //                     <th class='center width5'>".AM_LEXIKON_FORM_ACTION."XXX</th>
            //                    </tr><tr><td class='errorMsg' colspan='22'>There are noXXX entries</td></tr>";
            //            echo "</table><br><br>";

            //-------------------------------------------

            echo $GLOBALS['xoopsTpl']->fetch(XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/templates/admin/lexikon_admin_entries.tpl');
        }

        break;
    case 'new':
        $adminObject->addItemButton(_AM_LEXIKON_ENTRIES_LIST, 'entries.php', 'list');
        echo $adminObject->displayButton('left');

        /** @var Entries $entriesObject */
        $entriesObject = $entriesHandler->create();
        $form          = $entriesObject->getForm();
        $form->display();
        break;
    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('entries.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (0 != Request::getInt('entryID', 0)) {
            $entriesObject = $entriesHandler->get(Request::getInt('entryID', 0));
        } else {
            $entriesObject = $entriesHandler->create();
        }
        // Form save fields
        $entriesObject->setVar('categoryID', Request::getVar('categoryID', ''));
        $entriesObject->setVar('term', Request::getVar('term', ''));
        $entriesObject->setVar('init', Request::getVar('init', ''));
        $entriesObject->setVar('definition', Request::getText('definition', ''));
        $entriesObject->setVar('ref', Request::getText('ref', ''));
        $entriesObject->setVar('url', Request::getVar('url', ''));
        $entriesObject->setVar('uid', Request::getVar('uid', ''));
        $entriesObject->setVar('submit', ((1 == Request::getInt('submit', 0)) ? '1' : '0'));
        $entriesObject->setVar('datesub', strtotime($_REQUEST['datesub']['date']) + $_REQUEST['datesub']['time']);
        $resDate     = Request::getArray('datesub', [], 'POST');
        $dateTimeObj = \DateTime::createFromFormat(_SHORTDATESTRING, $resDate['date']);
        $dateTimeObj->setTime(0, 0, 0);
        $entriesObject->setVar('datesub', $dateTimeObj->getTimestamp() + $resDate['time']);

        $entriesObject->setVar('counter', Request::getVar('counter', ''));
        $entriesObject->setVar('html', ((1 == Request::getInt('html', 0)) ? '1' : '0'));
        $entriesObject->setVar('smiley', ((1 == Request::getInt('smiley', 0)) ? '1' : '0'));
        $entriesObject->setVar('xcodes', ((1 == Request::getInt('xcodes', 0)) ? '1' : '0'));
        $entriesObject->setVar('breaks', ((1 == Request::getInt('breaks', 0)) ? '1' : '0'));
        $entriesObject->setVar('block', ((1 == Request::getInt('block', 0)) ? '1' : '0'));
        $entriesObject->setVar('offline', ((1 == Request::getInt('offline', 0)) ? '1' : '0'));
        $entriesObject->setVar('notifypub', ((1 == Request::getInt('notifypub', 0)) ? '1' : '0'));
        $entriesObject->setVar('request', ((1 == Request::getInt('request', 0)) ? '1' : '0'));
        $entriesObject->setVar('comments', Request::getVar('comments', ''));
        $entriesObject->setVar('item_tag', Request::getVar('item_tag', ''));
        if ($entriesHandler->insert($entriesObject)) {
            redirect_header('entries.php?op=list', 2, _AM_LEXIKON_FORMOK);
        }

        echo $entriesObject->getHtmlErrors();
        $form = $entriesObject->getForm();
        $form->display();
        break;
    case 'edit':
    case 'mod':
        $adminObject->addItemButton(_AM_LEXIKON_ADD_ENTRIES, 'entries.php?op=new', 'add');
        $adminObject->addItemButton(_AM_LEXIKON_ENTRIES_LIST, 'entries.php', 'list');
        echo $adminObject->displayButton('left');
        $entriesObject = $entriesHandler->get(Request::getString('entryID', ''));
        $form          = $entriesObject->getForm();
        $form->display();
        break;
    case 'delete':
        $entriesObject = $entriesHandler->get(Request::getString('entryID', ''));
        if (1 == Request::getInt('ok', 0)) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('entries.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($entriesHandler->delete($entriesObject)) {
                redirect_header('entries.php', 3, _AM_LEXIKON_FORMDELOK);
            } else {
                echo $entriesObject->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'entryID' => Request::getString('entryID', ''), 'op' => 'delete'], Request::getCmd('REQUEST_URI', '', 'SERVER'), sprintf(_AM_LEXIKON_FORMSUREDEL, $entriesObject->getVar('term')));
        }
        break;
    case 'clone':

        $id_field = Request::getString('entryID', '');

        if ((int)$id_field > 0 && Utility::cloneRecord('lxentries', 'entryID', (int)$id_field)) {
            redirect_header('entries.php', 3, _AM_LEXIKON_CLONED_OK);
        } else {
            redirect_header('entries.php', 3, _AM_LEXIKON_CLONED_FAILED);
        }

        break;
}
require_once __DIR__ . '/admin_footer.php';
