<?php

namespace XoopsModules\Lexikon\Form;

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
use XoopsFormButton;
use XoopsFormCheckBox;
use XoopsFormDhtmlTextArea;
use XoopsFormEditor;
use XoopsFormElementTray;
use XoopsFormHidden;
use XoopsFormLabel;
use XoopsFormText;
use XoopsModules\Lexikon\{
    Form
};
use XoopsThemeForm;

require_once dirname(dirname(__DIR__)) . '/config/config.php';

$moduleDirName = basename(dirname(dirname(__DIR__)));
$permHelper    = new \Xmf\Module\Helper\Permission($moduleDirName);

$helper = Helper::getInstance();

xoops_load('XoopsFormLoader');

/**
 * Class LexikonCategoriesForm
 */
class CategoriesForm extends XoopsThemeForm
{
    public $targetObject;

    /**
     * Constructor
     *
     * @param $target
     */
    public function __construct($target)
    {
        global $helper;
        $this->targetObject = $target;

        $title = $this->targetObject->isNew() ? sprintf(AM_LEXIKON_CATEGORIES_ADD) : sprintf(AM_LEXIKON_CATEGORIES_EDIT);
        parent::__construct($title, 'form', xoops_getenv('SCRIPT_NAME'), 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        //include ID field, it's needed so the module knows if it is a new form or an edited form

        $hidden = new XoopsFormHidden('categoryID', $this->targetObject->getVar('categoryID'));
        $this->addElement($hidden);
        unset($hidden);

        // CategoryID
        $this->addElement(new XoopsFormLabel(AM_LEXIKON_CATEGORIES_CATEGORYID, $this->targetObject->getVar('categoryID'), 'categoryID'));
        // Name
        $this->addElement(new XoopsFormText(AM_LEXIKON_CATEGORIES_NAME, 'name', 50, 255, $this->targetObject->getVar('name')), false);
        // Description
        if (class_exists('XoopsFormEditor')) {
            $editorOptions           = [];
            $editorOptions['name']   = 'description';
            $editorOptions['value']  = $this->targetObject->getVar('description', 'e');
            $editorOptions['rows']   = 5;
            $editorOptions['cols']   = 40;
            $editorOptions['width']  = '100%';
            $editorOptions['height'] = '400px';
            //$editorOptions['editor'] = xoops_getModuleOption('lexikon_editor', 'lexikon');
            //$this->addElement( new \XoopsFormEditor(AM_LEXIKON_CATEGORIES_DESCRIPTION, 'description', $editorOptions), false  );
            if ($helper->isUserAdmin()) {
                $descEditor = new XoopsFormEditor(AM_LEXIKON_CATEGORIES_DESCRIPTION, $helper->getConfig('lexikonEditorAdmin'), $editorOptions, $nohtml = false, $onfailure = 'textarea');
            } else {
                $descEditor = new XoopsFormEditor(AM_LEXIKON_CATEGORIES_DESCRIPTION, $helper->getConfig('lexikonEditorUser'), $editorOptions, $nohtml = false, $onfailure = 'textarea');
            }
        } else {
            $descEditor = new XoopsFormDhtmlTextArea(AM_LEXIKON_CATEGORIES_DESCRIPTION, 'description', $this->targetObject->getVar('description', 'e'), '100%', '100%');
        }
        $this->addElement($descEditor);
        // Total
        $this->addElement(new XoopsFormText(AM_LEXIKON_CATEGORIES_TOTAL, 'total', 50, 255, $this->targetObject->getVar('total')), false);
        // Weight
        $this->addElement(new XoopsFormText(AM_LEXIKON_CATEGORIES_WEIGHT, 'weight', 50, 255, $this->targetObject->getVar('weight')), false);
        // Logourl
        $this->addElement(new XoopsFormText(AM_LEXIKON_CATEGORIES_LOGOURL, 'logourl', 50, 255, $this->targetObject->getVar('logourl')), false);

        //permissions
        /** @var \XoopsMemberHandler $memberHandler */
        $memberHandler = xoops_getHandler('member');
        $groupList     = $memberHandler->getGroupList();
        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler = xoops_getHandler('groupperm');
        $fullList         = array_keys($groupList);

        //========================================================================

        $mid            = $GLOBALS['xoopsModule']->mid();
        $groupIdAdmin   = 0;
        $groupNameAdmin = '';

        // create admin checkbox
        foreach ($groupList as $groupId => $groupName) {
            if (XOOPS_GROUP_ADMIN == $groupId) {
                $groupIdAdmin   = $groupId;
                $groupNameAdmin = $groupName;
            }
        }

        $selectPermAdmin = new XoopsFormCheckBox('', 'admin', XOOPS_GROUP_ADMIN);
        $selectPermAdmin->addOption($groupIdAdmin, $groupNameAdmin);
        $selectPermAdmin->setExtra("disabled='disabled'"); //comment it out, if you want to allow to remove permissions for the admin

        // ********************************************************
        // permission view items
        $cat_gperms_read     = $grouppermHandler->getGroupIds('lexikon_view', $this->targetObject->getVar('categoryID'), $mid);
        $arr_cat_gperms_read = $this->targetObject->isNew() ? '0' : $cat_gperms_read;

        $permsTray = new XoopsFormElementTray(AM_LEXIKON_PERMISSIONS_VIEW, '');

        $selectAllReadCheckbox = new XoopsFormCheckBox('', 'adminbox1', 1);
        $selectAllReadCheckbox->addOption('allbox', _AM_SYSTEM_ALL);
        $selectAllReadCheckbox->setExtra(" onclick='xoopsCheckGroup(\"form\", \"adminbox1\" , \"groupsRead[]\");' ");
        $selectAllReadCheckbox->setClass('xo-checkall');
        $permsTray->addElement($selectAllReadCheckbox);

        // checkbox webmaster
        $permsTray->addElement($selectPermAdmin, false);
        // checkboxes other groups
        //$selectPerm = new \XoopsFormCheckBox('', 'cat_gperms_read', $arr_cat_gperms_read);
        //$selectPerm = new \XoopsFormCheckBox('', 'groupsRead[]', $this->targetObject->getGroupsRead());
        $selectPerm = new XoopsFormCheckBox('', 'groupsRead[]', $arr_cat_gperms_read);
        foreach ($groupList as $groupId => $groupName) {
            if (XOOPS_GROUP_ADMIN != $groupId) {
                $selectPerm->addOption($groupId, $groupName);
            }
        }
        $permsTray->addElement($selectPerm, false);
        $this->addElement($permsTray, false);
        unset($permsTray, $selectPerm);

        // ********************************************************
        // permission submit item
        $cat_gperms_create     = $grouppermHandler->getGroupIds('lexikon_submit', $this->targetObject->getVar('categoryID'), $mid);
        $arr_cat_gperms_create = $this->targetObject->isNew() ? '0' : $cat_gperms_create;

        $permsTray = new XoopsFormElementTray(AM_LEXIKON_PERMISSIONS_SUBMIT, '');

        $selectAllSubmitCheckbox = new XoopsFormCheckBox('', 'adminbox2', 1);
        $selectAllSubmitCheckbox->addOption('allbox', _AM_SYSTEM_ALL);
        $selectAllSubmitCheckbox->setExtra(" onclick='xoopsCheckGroup(\"form\", \"adminbox2\" , \"groupsSubmit[]\");' ");
        $selectAllSubmitCheckbox->setClass('xo-checkall');
        $permsTray->addElement($selectAllSubmitCheckbox);

        // checkbox webmaster
        $permsTray->addElement($selectPermAdmin, false);
        // checkboxes other groups
        //$selectPerm = new \XoopsFormCheckBox('', 'cat_gperms_create', $arr_cat_gperms_create);
        $selectPerm = new XoopsFormCheckBox('', 'groupsSubmit[]', $arr_cat_gperms_create);
        foreach ($groupList as $groupId => $groupName) {
            if (XOOPS_GROUP_ADMIN != $groupId) {
                $selectPerm->addOption($groupId, $groupName);
            }
        }
        $permsTray->addElement($selectPerm, false);
        $this->addElement($permsTray, false);
        unset($permsTray, $selectPerm);

        // ********************************************************
        // permission approve items
        $cat_gperms_admin     = $grouppermHandler->getGroupIds('lexikon_approve', $this->targetObject->getVar('categoryID'), $mid);
        $arr_cat_gperms_admin = $this->targetObject->isNew() ? '0' : $cat_gperms_admin;

        $permsTray = new XoopsFormElementTray(AM_LEXIKON_PERMISSIONS_APPROVE, '');

        $selectAllModerateCheckbox = new XoopsFormCheckBox('', 'adminbox3', 1);
        $selectAllModerateCheckbox->addOption('allbox', _AM_SYSTEM_ALL);
        $selectAllModerateCheckbox->setExtra(" onclick='xoopsCheckGroup(\"form\", \"adminbox3\" , \"groupsModeration[]\");' ");
        $selectAllModerateCheckbox->setClass('xo-checkall');
        $permsTray->addElement($selectAllModerateCheckbox);

        // checkbox webmaster
        $permsTray->addElement($selectPermAdmin, false);
        // checkboxes other groups
        //$selectPerm = new \XoopsFormCheckBox('', 'cat_gperms_admin', $arr_cat_gperms_admin);
        $selectPerm = new XoopsFormCheckBox('', 'groupsModeration[]', $arr_cat_gperms_admin);
        foreach ($groupList as $groupId => $groupName) {
            if (XOOPS_GROUP_ADMIN != $groupId && XOOPS_GROUP_ANONYMOUS != $groupId) {
                $selectPerm->addOption($groupId, $groupName);
            }
        }
        $permsTray->addElement($selectPerm, false);
        $this->addElement($permsTray, false);
        unset($permsTray, $selectPerm);

        //=========================================================================
        $this->addElement(new XoopsFormHidden('op', 'save'));
        $this->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    }
}
