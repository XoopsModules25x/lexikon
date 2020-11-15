<?php

namespace XoopsModules\Lexikon;

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use XoopsForm;
use XoopsFormButton;
use XoopsFormElementTray;
use XoopsFormHidden;
use XoopsFormHiddenToken;






/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team, Kazumi Ono (AKA onokazu)
 */

require_once XOOPS_ROOT_PATH . '/class/xoopsform/formelement.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/formhidden.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/formhiddentoken.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/formbutton.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/formelementtray.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/form.php';

/**
 * Renders a form for setting module specific group permissions
 *
 * @author       Kazumi Ono    <onokazu@myweb.ne.jp>
 * @copyright    copyright (c) 2000-2003 XOOPS.org
 *
 * @package      kernel
 * @subpackage   form
 */
class GroupPermForm extends XoopsForm
{
    /**
     * Module ID
     * @var int
     */
    public $_modid;
    /**
     * Tree structure of items
     * @var array
     */
    public $_itemTree = [];
    /**
     * Name of permission
     * @var string
     */
    public $_permName;
    /**
     * Description of permission
     * @var string
     */
    public $_permDesc;
    /**
     * Appendix
     * @var array ('permname'=>,'itemid'=>,'itemname'=>,'selected'=>)
     */
    public $_appendix = [];

    /**
     * Constructor
     * @param $title
     * @param $modid
     * @param $permname
     * @param $permdesc
     */
    public function __construct($title, $modid, $permname, $permdesc)
    {
        //      $this->XoopsForm($title, 'groupperm_form', XOOPS_URL.'/modules/system/admin/groupperm.php', 'post'); GIJ
        parent::__construct($title, 'groupperm_form', '', 'post');
        $this->_modid    = (int)$modid;
        $this->_permName = $permname;
        $this->_permDesc = $permdesc;
        $this->addElement(new XoopsFormHidden('modid', $this->_modid));
        $this->addElement(new XoopsFormHiddenToken($permname));
    }

    /**
     * Adds an item to which permission will be assigned
     *
     * @param string $itemName
     * @param int    $itemId
     * @param int    $itemParent
     * @access public
     */
    public function addItem($itemId, $itemName, $itemParent = 0)
    {
        $this->_itemTree[$itemParent]['children'][] = $itemId;
        $this->_itemTree[$itemId]['parent']         = $itemParent;
        $this->_itemTree[$itemId]['name']           = $itemName;
        $this->_itemTree[$itemId]['id']             = $itemId;
    }

    /**
     * Add appendix
     *
     * @access public
     * @param $permName
     * @param $itemId
     * @param $itemName
     */
    public function addAppendix($permName, $itemId, $itemName)
    {
        $this->_appendix[] = [
            'permname' => $permName,
            'itemid'   => $itemId,
            'itemname' => $itemName,
            'selected' => false,
        ];
    }

    /**
     * Loads all child ids for an item to be used in javascript
     *
     * @param int   $itemId
     * @param array $childIds
     * @access private
     */
    public function _loadAllChildItemIds($itemId, &$childIds)
    {
        if (!empty($this->_itemTree[$itemId]['children'])) {
            $first_child = $this->_itemTree[$itemId]['children'];
            foreach ($first_child as $fcid) {
                array_push($childIds, $fcid);
                if (!empty($this->_itemTree[$fcid]['children'])) {
                    foreach ($this->_itemTree[$fcid]['children'] as $_fcid) {
                        array_push($childIds, $_fcid);
                        $this->_loadAllChildItemIds($_fcid, $childIds);
                    }
                }
            }
        }
    }

    /**
     * Renders the form
     *
     * @return string
     * @access public
     */
    public function render()
    {
        // load all child ids for javascript codes
        foreach (array_keys($this->_itemTree) as $item_id) {
            $this->_itemTree[$item_id]['allchild'] = [];
            $this->_loadAllChildItemIds($item_id, $this->_itemTree[$item_id]['allchild']);
        }
        $grouppermHandler = xoops_getHandler('groupperm');
        $memberHandler    = xoops_getHandler('member');
        $glist            = $memberHandler->getGroupList();
        foreach (array_keys($glist) as $i) {
            // get selected item id(s) for each group
            $selected = $grouppermHandler->getItemIds($this->_permName, $i, $this->_modid);
            $ele      = new GroupFormCheckBox($glist[$i], 'perms[' . $this->_permName . ']', $i, $selected);
            $ele->setOptionTree($this->_itemTree);

            foreach ($this->_appendix as $key => $append) {
                $this->_appendix[$key]['selected'] = $grouppermHandler->checkRight($append['permname'], $append['itemid'], $i, $this->_modid);
            }
            $ele->setAppendix($this->_appendix);
            $this->addElement($ele);
            unset($ele);
        }

        // GIJ start
        $jstray          = new XoopsFormElementTray(' &nbsp; ');
        $jsuncheckbutton = new XoopsFormButton('', 'none', _NONE, 'button');
        $jsuncheckbutton->setExtra("onclick=\"with(document.groupperm_form){for (i=0;i<length;i++) {if (elements[i].type=='checkbox') {elements[i].checked=false;}}}\"");
        $jscheckbutton = new XoopsFormButton('', 'all', _ALL, 'button');
        $jscheckbutton->setExtra("onclick=\"with(document.groupperm_form){for (i=0;i<length;i++) {if(elements[i].type=='checkbox' && (elements[i].name.indexOf('module_admin')<0 || elements[i].name.indexOf('[groups][1]')>=0)) {elements[i].checked=true;}}}\"");
        $jstray->addElement($jsuncheckbutton);
        $jstray->addElement($jscheckbutton);
        $this->addElement($jstray);
        // GIJ end

        $tray = new XoopsFormElementTray('');
        $tray->addElement(new XoopsFormButton('', 'reset', _CANCEL, 'reset'));
        $tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $this->addElement($tray);

        $ret      = '<h4>' . $this->getTitle() . '</h4>' . $this->_permDesc . '<br>';
        $ret      .= "<form name='" . $this->getName() . "' id='" . $this->getName() . "' action='" . $this->getAction() . "' method='" . $this->getMethod() . "'" . $this->getExtra() . ">\n<table width='100%' class='outer' cellspacing='1'>\n";
        $elements = $this->getElements();
        foreach (array_keys($elements) as $i) {
            if (!is_object($elements[$i])) {
                $ret .= $elements[$i];
            } elseif (!$elements[$i]->isHidden()) {
                $ret .= "<tr valign='top' align='left'><td class='head'>" . $elements[$i]->getCaption();
                if ('' != $elements[$i]->getDescription()) {
                    $ret .= '<br><br><span style="font-weight: normal;">' . $elements[$i]->getDescription() . '</span>';
                }
                $ret .= "</td>\n<td class='even'>\n" . $elements[$i]->render() . "\n</td></tr>\n";
            } else {
                $ret .= $elements[$i]->render();
            }
        }
        $ret .= '</table>' . $GLOBALS['xoopsSecurity']->getTokenHTML('myblocksadmin') . '</form>';

        return $ret;
    }
}
