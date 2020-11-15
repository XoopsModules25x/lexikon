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

use XoopsModules\Lexikon\{
    Helper,
    Utility,
    CategoriesHandler
};


require_once \dirname(\dirname(__DIR__)) . '/config/config.php';

$moduleDirName = \basename(\dirname(\dirname(__DIR__)));

$helper = Helper::getInstance();

\xoops_load('XoopsFormLoader');

/**
 * Class LexikonEntriesForm
 */
class EntriesForm extends \XoopsThemeForm
{
    public $targetObject;

    /**
     * Constructor
     *
     * @param $target
     */
    public function __construct($target)
    {
        /** @var \XoopsDatabase $db */

        $db      = \XoopsDatabaseFactory::getDatabaseConnection();
        $helper  = Helper::getInstance();
        $utility = new Utility();

        $this->targetObject = $target;

        $title = $this->targetObject->isNew() ? \sprintf(\AM_LEXIKON_ENTRIES_ADD) : \sprintf(\AM_LEXIKON_ENTRIES_EDIT);
        parent::__construct($title, 'form', \xoops_getenv('SCRIPT_NAME'), 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        //include ID field, it's needed so the module knows if it is a new form or an edited form

        $hidden = new \XoopsFormHidden('entryID', $this->targetObject->getVar('entryID'));
        $this->addElement($hidden);
        unset($hidden);

        // EntryID
        $this->addElement(new \XoopsFormLabel(\AM_LEXIKON_ENTRIES_ENTRYID, $this->targetObject->getVar('entryID'), 'entryID'));
        // CategoryID
        //        $categoriesHandler    = xoops_getModuleHandler('categories', 'lexikon');

        $categoriesHandler = new CategoriesHandler($db);

        $categories_id_select = new \XoopsFormSelect(\AM_LEXIKON_ENTRIES_CATEGORYID, 'categoryID', $this->targetObject->getVar('name'));
        $categories_id_select->addOptionArray($categoriesHandler->getList());

        $this->addElement($categories_id_select, false);
        // Uid
        $this->addElement(new \XoopsFormSelectUser(\AM_LEXIKON_ENTRIES_UID, 'uid', false, $this->targetObject->getVar('uid'), 1, false), false);

        // Term
        $this->addElement(new \XoopsFormText(\AM_LEXIKON_ENTRIES_TERM, 'term', 50, 255, $this->targetObject->getVar('term')), false);
        // Init
        $this->addElement(new \XoopsFormText(\AM_LEXIKON_ENTRIES_INIT, 'init', 50, 255, $this->targetObject->getVar('init')), false);

        // Definition

        /*
                if (class_exists('XoopsFormEditor')) {
                    $editorOptions           = [];
                    $editorOptions['name']   = 'definition';
                    $editorOptions['value']  = $this->targetObject->getVar('definition', 'e');
                    $editorOptions['rows']   = 5;
                    $editorOptions['cols']   = 40;
                    $editorOptions['width']  = '100%';
                    $editorOptions['height'] = '400px';
                    //$editorOptions['editor'] = xoops_getModuleOption('lexikon_editor', 'lexikon');
                    //$this->addElement( new \XoopsFormEditor(AM_LEXIKON_ENTRIES_DEFINITION, 'definition', $editorOptions), false  );
                    if ($helper->isUserAdmin()) {
                        $descEditor = new \XoopsFormEditor(AM_LEXIKON_ENTRIES_DEFINITION, $helper->getConfig('lexikonEditorAdmin'), $editorOptions, $nohtml = false, $onfailure = 'textarea');
                    } else {
                        $descEditor = new \XoopsFormEditor(AM_LEXIKON_ENTRIES_DEFINITION, $helper->getConfig('lexikonEditorUser'), $editorOptions, $nohtml = false, $onfailure = 'textarea');
                    }
                } else {
                    $descEditor = new \XoopsFormDhtmlTextArea(AM_LEXIKON_ENTRIES_DEFINITION, 'description', $this->targetObject->getVar('description', 'e'), '100%', '100%');
                }
                $this->addElement($descEditor);
        */

        $definition = $this->targetObject->getVar('definition', 'e');
        $editor     = $utility::getWysiwygForm(_AM_LEXIKON_ENTRYDEF, 'definition', $definition, 15, 60);
        if (_MD_LEXIKON_WRITEHERE == $definition) {
            $editor->setExtra('onfocus="this.select()"');
        }
        $this->addElement($editor, true);
        unset($editor);

        // Ref
        if (\class_exists('XoopsFormEditor')) {
            $editorOptions           = [];
            $editorOptions['name']   = 'ref';
            $editorOptions['value']  = $this->targetObject->getVar('ref', 'e');
            $editorOptions['rows']   = 5;
            $editorOptions['cols']   = 40;
            $editorOptions['width']  = '100%';
            $editorOptions['height'] = '400px';
            //$editorOptions['editor'] = xoops_getModuleOption('lexikon_editor', 'lexikon');
            //$this->addElement( new \XoopsFormEditor(AM_LEXIKON_ENTRIES_REF, 'ref', $editorOptions), false  );
            if ($helper->isUserAdmin()) {
                $descEditor = new \XoopsFormEditor(\AM_LEXIKON_ENTRIES_REF, $helper->getConfig('lexikonEditorAdmin'), $editorOptions, $nohtml = false, $onfailure = 'textarea');
            } else {
                $descEditor = new \XoopsFormEditor(\AM_LEXIKON_ENTRIES_REF, $helper->getConfig('lexikonEditorUser'), $editorOptions, $nohtml = false, $onfailure = 'textarea');
            }
        } else {
            $descEditor = new \XoopsFormDhtmlTextArea(\AM_LEXIKON_ENTRIES_REF, 'description', $this->targetObject->getVar('description', 'e'), '100%', '100%');
        }
        $this->addElement($descEditor);
        // Url
        $this->addElement(new \XoopsFormText(\AM_LEXIKON_ENTRIES_URL, 'url', 50, 255, $this->targetObject->getVar('url')), false);
        // Submit
        $submit       = $this->targetObject->isNew() ? 0 : $this->targetObject->getVar('submit');
        $check_submit = new \XoopsFormCheckBox(\_SUBMIT, 'submit', $submit);
        $check_submit->addOption(1, ' ');
        $this->addElement($check_submit);
        // Datesub
        $this->addElement(new \XoopsFormDateTime(\AM_LEXIKON_ENTRIES_DATESUB, 'datesub', '', \strtotime($this->targetObject->getVar('datesub'))));
        // Counter
        //        $this->addElement(new \XoopsFormText(AM_LEXIKON_ENTRIES_COUNTER, 'counter', 50, 255, $this->targetObject->getVar('counter')), false);
        // Html
        $html       = $this->targetObject->isNew() ? 0 : $this->targetObject->getVar('html');
        $check_html = new \XoopsFormCheckBox(\AM_LEXIKON_ENTRIES_HTML, 'html', $html);
        $check_html->addOption(1, ' ');
        $this->addElement($check_html);
        // Smiley
        $smiley       = $this->targetObject->isNew() ? 0 : $this->targetObject->getVar('smiley');
        $check_smiley = new \XoopsFormCheckBox(\AM_LEXIKON_ENTRIES_SMILEY, 'smiley', $smiley);
        $check_smiley->addOption(1, ' ');
        $this->addElement($check_smiley);
        // Xcodes
        $xcodes       = $this->targetObject->isNew() ? 0 : $this->targetObject->getVar('xcodes');
        $check_xcodes = new \XoopsFormCheckBox(\AM_LEXIKON_ENTRIES_XCODES, 'xcodes', $xcodes);
        $check_xcodes->addOption(1, ' ');
        $this->addElement($check_xcodes);
        // Breaks
        $breaks       = $this->targetObject->isNew() ? 0 : $this->targetObject->getVar('breaks');
        $check_breaks = new \XoopsFormCheckBox(\AM_LEXIKON_ENTRIES_BREAKS, 'breaks', $breaks);
        $check_breaks->addOption(1, ' ');
        $this->addElement($check_breaks);
        // Block
        $block       = $this->targetObject->isNew() ? 0 : $this->targetObject->getVar('block');
        $check_block = new \XoopsFormCheckBox(\AM_LEXIKON_ENTRIES_BLOCK, 'block', $block);
        $check_block->addOption(1, ' ');
        $this->addElement($check_block);
        // Offline
        $offline       = $this->targetObject->isNew() ? 0 : $this->targetObject->getVar('offline');
        $check_offline = new \XoopsFormCheckBox(\AM_LEXIKON_ENTRIES_OFFLINE, 'offline', $offline);
        $check_offline->addOption(1, ' ');
        $this->addElement($check_offline);
        // Notifypub
        $notifypub       = $this->targetObject->isNew() ? 0 : $this->targetObject->getVar('notifypub');
        $check_notifypub = new \XoopsFormCheckBox(\AM_LEXIKON_ENTRIES_NOTIFYPUB, 'notifypub', $notifypub);
        $check_notifypub->addOption(1, ' ');
        $this->addElement($check_notifypub);
        // Request
        //        $request       = $this->targetObject->isNew() ? 0 : $this->targetObject->getVar('request');
        //        $check_request = new \XoopsFormCheckBox(AM_LEXIKON_ENTRIES_REQUEST, 'request', $request);
        //        $check_request->addOption(1, ' ');
        //        $this->addElement($check_request);
        // Comments
        //$this->addElement(new \XoopsFormText(AM_LEXIKON_ENTRIES_COMMENTS, 'comments', 50, 255, $this->targetObject->getVar('comments')), false);
        // Item_tag
        //        $this->addElement(new \XoopsFormTextArea(AM_LEXIKON_ENTRIES_ITEM_TAG, 'item_tag', $this->targetObject->getVar('item_tag'), 4, 47), false);

        $this->addElement(new \XoopsFormHidden('op', 'save'));
        $this->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    }
}
