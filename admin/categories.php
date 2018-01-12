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
 * @author          XOOPS Development Team <name@site.com> - <http://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 * @link            https://xoops.org/
 * @since           1.0.0
 */

use Xmf\Module\Admin;
use Xmf\Database\Tables;
use Xmf\Debug;
use Xmf\Module\Helper;
use Xmf\Module\Helper\Permission;
use Xmf\Request;
use XoopsModules\Lexikon;
use XoopsModules\Lexikon\Form;

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();
//It recovered the value of argument op in URL$
$op    = Request::getString('op', 'list');
$order = Request::getString('order', 'desc');
$sort  = Request::getString('sort', '');

$adminObject->displayNavigation(basename(__FILE__));
/** @var Permission $permHelper */
$permHelper = new Permission($moduleDirName);
$uploadDir  = XOOPS_UPLOAD_PATH . '/lexikon/images/';
$uploadUrl  = XOOPS_UPLOAD_URL . '/lexikon/images/';

switch ($op) {
    case 'list':
    default:
        $adminObject->addItemButton(AM_LEXIKON_ADD_CATEGORIES, 'categories.php?op=new', 'add');
        echo $adminObject->displayButton('left');
        $start                     = Request::getInt('start', 0);
        $categoriesPaginationLimit = $GLOBALS['xoopsModuleConfig']['userpager'];

        $criteria = new \CriteriaCompo();
        $criteria->setSort('categoryID ASC, categoryID');
        $criteria->setOrder('ASC');
        $criteria->setLimit($categoriesPaginationLimit);
        $criteria->setStart($start);
        $categoriesTempRows  = $categoriesHandler->getCount();
        $categoriesTempArray = $categoriesHandler->getAll($criteria);/*
//
// 
                    <th class='center width5'>".AM_LEXIKON_FORM_ACTION."</th>
//                    </tr>";
//            $class = "odd";
*/

        // Display Page Navigation
        if ($categoriesTempRows > $categoriesPaginationLimit) {
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

            $pagenav = new \XoopsPageNav($categoriesTempRows, $categoriesPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . '');
            $GLOBALS['xoopsTpl']->assign('pagenav', null === $pagenav ? $pagenav->renderNav() : '');
        }

        $GLOBALS['xoopsTpl']->assign('categoriesRows', $categoriesTempRows);
        $categoriesArray = [];

        //    $fields = explode('|', categoryID:tinyint:4::NOT NULL::primary:ID|name:varchar:100::NOT NULL:::Category|description:text:0::NOT NULL:::Description|total:int:11::NOT NULL:0::Total|weight:int:11::NOT NULL:1::Weight|logourl:varchar:150::NOT NULL:::Logo URL);
        //    $fieldsCount    = count($fields);

        $criteria = new \CriteriaCompo();

        //$criteria->setOrder('DESC');
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($categoriesPaginationLimit);
        $criteria->setStart($start);

        $categoriesCount     = $categoriesHandler->getCount($criteria);
        $categoriesTempArray = $categoriesHandler->getAll($criteria);

        //    for ($i = 0; $i < $fieldsCount; ++$i) {
        if ($categoriesCount > 0) {
            foreach (array_keys($categoriesTempArray) as $i) {


                //        $field = explode(':', $fields[$i]);

                $selectorcategoryID = Lexikon\Utility::selectSorting(AM_LEXIKON_CATEGORIES_CATEGORYID, 'categoryID');
                $GLOBALS['xoopsTpl']->assign('selectorcategoryID', $selectorcategoryID);
                $categoriesArray['categoryID'] = $categoriesTempArray[$i]->getVar('categoryID');

                $selectorname = Lexikon\Utility::selectSorting(AM_LEXIKON_CATEGORIES_NAME, 'name');
                $GLOBALS['xoopsTpl']->assign('selectorname', $selectorname);
                $categoriesArray['name'] = $categoriesTempArray[$i]->getVar('name');

                $selectordescription = Lexikon\Utility::selectSorting(AM_LEXIKON_CATEGORIES_DESCRIPTION, 'description');
                $GLOBALS['xoopsTpl']->assign('selectordescription', $selectordescription);
                $categoriesArray['description'] = ($categoriesTempArray[$i]->getVar('description'));

                $selectortotal = Lexikon\Utility::selectSorting(AM_LEXIKON_CATEGORIES_TOTAL, 'total');
                $GLOBALS['xoopsTpl']->assign('selectortotal', $selectortotal);
                $categoriesArray['total'] = $categoriesTempArray[$i]->getVar('total');

                $selectorweight = Lexikon\Utility::selectSorting(AM_LEXIKON_CATEGORIES_WEIGHT, 'weight');
                $GLOBALS['xoopsTpl']->assign('selectorweight', $selectorweight);
                $categoriesArray['weight'] = $categoriesTempArray[$i]->getVar('weight');

                $selectorlogourl = Lexikon\Utility::selectSorting(AM_LEXIKON_CATEGORIES_LOGOURL, 'logourl');
                $GLOBALS['xoopsTpl']->assign('selectorlogourl', $selectorlogourl);
                $categoriesArray['logourl']     = $categoriesTempArray[$i]->getVar('logourl');
                $categoriesArray['edit_delete'] = "<a href='categories.php?op=edit&categoryID=" . $i . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
               <a href='categories.php?op=delete&categoryID=" . $i . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
               <a href='categories.php?op=clone&categoryID=" . $i . "'><img src=" . $pathIcon16 . "/editcopy.png alt='" . _CLONE . "' title='" . _CLONE . "'></a>";

                $GLOBALS['xoopsTpl']->append_by_ref('categoriesArrays', $categoriesArray);
                unset($categoriesArray);
            }
            unset($categoriesTempArray);
            // Display Navigation
            if ($categoriesCount > $categoriesPaginationLimit) {
                require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($categoriesCount, $categoriesPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . '');
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }

            //                     echo "<td class='center width5'>

            //                    <a href='categories.php?op=edit&categoryID=".$i."'><img src=".$pathIcon16."/edit.png alt='"._EDIT."' title='"._EDIT."'></a>
            //                    <a href='categories.php?op=delete&categoryID=".$i."'><img src=".$pathIcon16."/delete.png alt='"._DELETE."' title='"._DELETE."'></a>
            //                    </td>";

            //                echo "</tr>";

            //            }

            //            echo "</table><br><br>";

            //        } else {

            //            echo "<table width='100%' cellspacing='1' class='outer'>

            //                    <tr>

            //                     <th class='center width5'>".AM_LEXIKON_FORM_ACTION."XXX</th>
            //                    </tr><tr><td class='errorMsg' colspan='7'>There are noXXX categories</td></tr>";
            //            echo "</table><br><br>";

            //-------------------------------------------

            echo $GLOBALS['xoopsTpl']->fetch(XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/templates/admin/lexikon_admin_categories.tpl');
        }

        break;

    case 'new':
        $adminObject->addItemButton(AM_LEXIKON_CATEGORIES_LIST, 'categories.php', 'list');
        echo $adminObject->displayButton('left');

        $categoriesObject = $categoriesHandler->create();
        $form             = $categoriesObject->getForm();
        $form->display();
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('categories.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (0 != Request::getInt('categoryID', 0)) {
            $categoriesObject = $categoriesHandler->get(Request::getInt('categoryID', 0));
        } else {
            $categoriesObject = $categoriesHandler->create();
        }
        // Form save fields
        $categoriesObject->setVar('name', Request::getVar('name', ''));
        $categoriesObject->setVar('description', Request::getText('description', ''));
        $categoriesObject->setVar('total', Request::getVar('total', ''));
        $categoriesObject->setVar('weight', Request::getVar('weight', ''));
        $categoriesObject->setVar('logourl', Request::getVar('logourl', ''));
        //Permissions
        //===============================================================

        $mid = $GLOBALS['xoopsModule']->mid();
        /** @var XoopsGroupPermHandler $gpermHandler */
        $gpermHandler = xoops_getHandler('groupperm');
        $categoryID   = Request::getInt('categoryID', 0);

        /**
         * @param $myArray
         * @param $permissionGroup
         * @param $categoryID
         * @param $gpermHandler
         * @param $permissionName
         * @param $mid
         */
        function setPermissions($myArray, $permissionGroup, $categoryID, $gpermHandler, $permissionName, $mid)
        {
            $permissionArray = $myArray;
            if ($categoryID > 0) {
                $sql = 'DELETE FROM `' . $GLOBALS['xoopsDB']->prefix('group_permission') . "` WHERE `gperm_name` = '" . $permissionName . "' AND `gperm_itemid`= $categoryID;";
                $GLOBALS['xoopsDB']->query($sql);
            }
            //admin
            $gperm = $gpermHandler->create();
            $gperm->setVar('gperm_groupid', XOOPS_GROUP_ADMIN);
            $gperm->setVar('gperm_name', $permissionName);
            $gperm->setVar('gperm_modid', $mid);
            $gperm->setVar('gperm_itemid', $categoryID);
            $gpermHandler->insert($gperm);
            unset($gperm);
            //non-Admin groups
            if (is_array($permissionArray)) {
                foreach ($permissionArray as $key => $cat_groupperm) {
                    if ($cat_groupperm > 0) {
                        $gperm = $gpermHandler->create();
                        $gperm->setVar('gperm_groupid', $cat_groupperm);
                        $gperm->setVar('gperm_name', $permissionName);
                        $gperm->setVar('gperm_modid', $mid);
                        $gperm->setVar('gperm_itemid', $categoryID);
                        $gpermHandler->insert($gperm);
                        unset($gperm);
                    }
                }
            } elseif ($permissionArray > 0) {
                $gperm = $gpermHandler->create();
                $gperm->setVar('gperm_groupid', $permissionArray);
                $gperm->setVar('gperm_name', $permissionName);
                $gperm->setVar('gperm_modid', $mid);
                $gperm->setVar('gperm_itemid', $categoryID);
                $gpermHandler->insert($gperm);
                unset($gperm);
            }
        }

        //setPermissions for View items
        $permissionGroup   = 'groupsRead';
        $permissionName    = 'lexikon_view';
        $permissionArray   = Request::getArray($permissionGroup, '');
        $permissionArray[] = XOOPS_GROUP_ADMIN;
        //setPermissions($permissionArray, $permissionGroup, $categoryID, $gpermHandler, $permissionName, $mid);
        $permHelper->savePermissionForItem($permissionName, $categoryID, $permissionArray);

        //setPermissions for Submit items
        $permissionGroup   = 'groupsSubmit';
        $permissionName    = 'lexikon_submit';
        $permissionArray   = Request::getArray($permissionGroup, '');
        $permissionArray[] = XOOPS_GROUP_ADMIN;
        //setPermissions($permissionArray, $permissionGroup, $categoryID, $gpermHandler, $permissionName, $mid);
        $permHelper->savePermissionForItem($permissionName, $categoryID, $permissionArray);

        //setPermissions for Approve items
        $permissionGroup   = 'groupsModeration';
        $permissionName    = 'lexikon_approve';
        $permissionArray   = Request::getArray($permissionGroup, '');
        $permissionArray[] = XOOPS_GROUP_ADMIN;
        //setPermissions($permissionArray, $permissionGroup, $categoryID, $gpermHandler, $permissionName, $mid);
        $permHelper->savePermissionForItem($permissionName, $categoryID, $permissionArray);

        /*
                    //Form lexikon_view
                    $arr_lexikon_view = Request::getArray('cat_gperms_read');
                    if ($categoryID > 0) {
                        $sql
                            =
                            'DELETE FROM `' . $GLOBALS['xoopsDB']->prefix('group_permission') . "` WHERE `gperm_name`='lexikon_view' AND `gperm_itemid`=$categoryID;";
                        $GLOBALS['xoopsDB']->query($sql);
                    }
                    //admin
                    $gperm = $gpermHandler->create();
                    $gperm->setVar('gperm_groupid', XOOPS_GROUP_ADMIN);
                    $gperm->setVar('gperm_name', 'lexikon_view');
                    $gperm->setVar('gperm_modid', $mid);
                    $gperm->setVar('gperm_itemid', $categoryID);
                    $gpermHandler->insert($gperm);
                    unset($gperm);
                    if (is_array($arr_lexikon_view)) {
                        foreach ($arr_lexikon_view as $key => $cat_groupperm) {
                            $gperm = $gpermHandler->create();
                            $gperm->setVar('gperm_groupid', $cat_groupperm);
                            $gperm->setVar('gperm_name', 'lexikon_view');
                            $gperm->setVar('gperm_modid', $mid);
                            $gperm->setVar('gperm_itemid', $categoryID);
                            $gpermHandler->insert($gperm);
                            unset($gperm);
                        }
                    } else {
                        $gperm = $gpermHandler->create();
                        $gperm->setVar('gperm_groupid', $arr_lexikon_view);
                        $gperm->setVar('gperm_name', 'lexikon_view');
                        $gperm->setVar('gperm_modid', $mid);
                        $gperm->setVar('gperm_itemid', $categoryID);
                        $gpermHandler->insert($gperm);
                        unset($gperm);
                    }
        */

        //===============================================================

        if ($categoriesHandler->insert($categoriesObject)) {
            redirect_header('categories.php?op=list', 2, AM_LEXIKON_FORMOK);
        }

        echo $categoriesObject->getHtmlErrors();
        $form = $categoriesObject->getForm();
        $form->display();
        break;

    case 'edit':
        $adminObject->addItemButton(AM_LEXIKON_ADD_CATEGORIES, 'categories.php?op=new', 'add');
        $adminObject->addItemButton(AM_LEXIKON_CATEGORIES_LIST, 'categories.php', 'list');
        echo $adminObject->displayButton('left');
        $categoriesObject = $categoriesHandler->get(Request::getString('categoryID', ''));
        $form             = $categoriesObject->getForm();
        $form->display();
        break;

    case 'delete':
        $categoriesObject = $categoriesHandler->get(Request::getString('categoryID', ''));
        if (1 == Request::getInt('ok', 0)) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('categories.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($categoriesHandler->delete($categoriesObject)) {
                redirect_header('categories.php', 3, AM_LEXIKON_FORMDELOK);
            } else {
                echo $categoriesObject->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'categoryID' => Request::getString('categoryID', ''), 'op' => 'delete'], Request::getCmd('REQUEST_URI', '', 'SERVER'), sprintf(AM_LEXIKON_FORMSUREDEL, $categoriesObject->getVar('categoryID')));
        }
        break;

    case 'clone':

        $id_field = Request::getString('categoryID', '');

        if (Lexikon\Utility::cloneRecord('lexikon_categories', 'categoryID', $id_field)) {
            redirect_header('categories.php', 3, AM_LEXIKON_CLONED_OK);
        } else {
            redirect_header('categories.php', 3, AM_LEXIKON_CLONED_FAILED);
        }

        break;
}
require_once __DIR__ . '/admin_footer.php';
