<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project http://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team, Kazumi Ono (AKA onokazu)
 */


defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

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
class MyXoopsGroupPermForm extends XoopsForm
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
            'selected' => false
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
        global $xoopsGTicket;

        // load all child ids for javascript codes
        foreach (array_keys($this->_itemTree) as $item_id) {
            $this->_itemTree[$item_id]['allchild'] = [];
            $this->_loadAllChildItemIds($item_id, $this->_itemTree[$item_id]['allchild']);
        }
        $gpermHandler  = xoops_getHandler('groupperm');
        $memberHandler = xoops_getHandler('member');
        $glist         = $memberHandler->getGroupList();
        foreach (array_keys($glist) as $i) {
            // get selected item id(s) for each group
            $selected = $gpermHandler->getItemIds($this->_permName, $i, $this->_modid);
            $ele      = new MyXoopsGroupFormCheckBox($glist[$i], 'perms[' . $this->_permName . ']', $i, $selected);
            $ele->setOptionTree($this->_itemTree);

            foreach ($this->_appendix as $key => $append) {
                $this->_appendix[$key]['selected'] = $gpermHandler->checkRight($append['permname'], $append['itemid'], $i, $this->_modid);
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
        $ret      .= "<form name='"
                     . $this->getName()
                     . "' id='"
                     . $this->getName()
                     . "' action='"
                     . $this->getAction()
                     . "' method='"
                     . $this->getMethod()
                     . "'"
                     . $this->getExtra()
                     . ">\n<table width='100%' class='outer' cellspacing='1'>\n";
        $elements = $this->getElements();
        foreach (array_keys($elements) as $i) {
            if (!is_object($elements[$i])) {
                $ret .= $elements[$i];
            } elseif (!$elements[$i]->isHidden()) {
                $ret .= "<tr valign='top' align='left'><td class='head'>" . $elements[$i]->getCaption();
                if ($elements[$i]->getDescription() != '') {
                    $ret .= '<br><br><span style="font-weight: normal;">' . $elements[$i]->getDescription() . '</span>';
                }
                $ret .= "</td>\n<td class='even'>\n" . $elements[$i]->render() . "\n</td></tr>\n";
            } else {
                $ret .= $elements[$i]->render();
            }
        }
        $ret .= '</table>' . $xoopsGTicket->getTicketHtml(__LINE__, 1800, 'myblocksadmin') . '</form>';

        return $ret;
    }
}

/**
 * Renders checkbox options for a group permission form
 *
 * @author       Kazumi Ono    <onokazu@myweb.ne.jp>
 * @copyright    copyright (c) 2000-2003 XOOPS.org
 *
 * @package      kernel
 * @subpackage   form
 */
class MyXoopsGroupFormCheckBox extends XoopsFormElement
{
    /**
     * Pre-selected value(s)
     * @var array;
     */
    public $_value;
    /**
     * Group ID
     * @var int
     */
    public $_groupId;
    /**
     * Option tree
     * @var array
     */
    public $_optionTree;
    /**
     * Appendix
     * @var array ('permname'=>,'itemid'=>,'itemname'=>,'selected'=>)
     */
    public $_appendix = [];

    /**
     * Constructor
     * @param      $caption
     * @param      $name
     * @param      $groupId
     * @param null $values
     */
    public function __construct($caption, $name, $groupId, $values = null)
    {
        $this->setCaption($caption);
        $this->setName($name);
        if (isset($values)) {
            $this->setValue($values);
        }
        $this->_groupId = $groupId;
    }

    /**
     * Sets pre-selected values
     *
     * @param mixed $value A group ID or an array of group IDs
     * @access public
     */
    public function setValue($value)
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                $this->setValue($v);
            }
        } else {
            $this->_value[] = $value;
        }
    }

    /**
     * Sets the tree structure of items
     *
     * @param array $optionTree
     * @access public
     */
    public function setOptionTree(&$optionTree)
    {
        $this->_optionTree = $optionTree;
    }

    /**
     * Sets appendix of checkboxes
     *
     * @access public
     * @param $appendix
     */
    public function setAppendix($appendix)
    {
        $this->_appendix = $appendix;
    }

    /**
     * Renders checkbox options for this group
     *
     * @return string
     * @access public
     */
    public function render()
    {
        $ret = '';

        if (count($this->_appendix) > 0) {
            $ret  .= '<table class="outer"><tr>';
            $cols = 1;
            foreach ($this->_appendix as $append) {
                if ($cols > 4) {
                    $ret  .= '</tr><tr>';
                    $cols = 1;
                }
                $checked = $append['selected'] ? 'checked' : '';
                $name    = 'perms[' . $append['permname'] . ']';
                $itemid  = $append['itemid'];
                $itemid  = $append['itemid'];
                $ret    .= "<td class=\"odd\"><input type=\"checkbox\" name=\"{$name}[groups][$this->_groupId][$itemid]\" id=\"{$name}[groups][$this->_groupId][$itemid]\" value=\"1\" $checked />{$append['itemname']}<input type=\"hidden\" name=\"{$name}[parents][$itemid]\" value=\"\" /><input type=\"hidden\" name=\"{$name}[itemname][$itemid]\" value=\"{$append['itemname']}\" /><br></td>";
                ++$cols;
            }
            $ret .= '</tr></table>';
        }

        $ret  .= '<table class="outer"><tr>';
        $cols = 1;
        if (!empty($this->_optionTree[0]['children'])) {
            foreach ($this->_optionTree[0]['children'] as $topitem) {
                if ($cols > 4) {
                    $ret .= '</tr><tr>';
                    $cols = 1;
                }
                $tree   = '<td class="odd">';
                $prefix = '';
                $this->_renderOptionTree($tree, $this->_optionTree[$topitem], $prefix);
                $ret .= $tree . '</td>';
                ++$cols;
            }
        }
        $ret .= '</tr></table>';

        return $ret;
    }

    /**
     * Renders checkbox options for an item tree
     *
     * @param string $tree
     * @param array  $option
     * @param string $prefix
     * @param array  $parentIds
     * @access private
     */
    public function _renderOptionTree(&$tree, $option, $prefix, $parentIds = [])
    {
        $tree .= $prefix
                 . "<input type=\"checkbox\" name=\""
                 . $this->getName()
                 . '[groups]['
                 . $this->_groupId
                 . ']['
                 . $option['id']
                 . "]\" id=\""
                 . $this->getName()
                 . '[groups]['
                 . $this->_groupId
                 . ']['
                 . $option['id']
                 . "]\" onclick=\"";
        // If there are parent elements, add javascript that will
        // make them selecteded when this element is checked to make
        // sure permissions to parent items are added as well.
        foreach ($parentIds as $pid) {
            $parent_ele = $this->getName() . '[groups][' . $this->_groupId . '][' . $pid . ']';
            $tree       .= "var ele = xoopsGetElementById('" . $parent_ele . "'); if (ele.checked !== true) {ele.checked = this.checked;}";
        }
        // If there are child elements, add javascript that will
        // make them unchecked when this element is unchecked to make
        // sure permissions to child items are not added when there
        // is no permission to this item.
        foreach ($option['allchild'] as $cid) {
            $child_ele = $this->getName() . '[groups][' . $this->_groupId . '][' . $cid . ']';
            $tree      .= "var ele = xoopsGetElementById('" . $child_ele . "'); if (this.checked !== true) {ele.checked = false;}";
        }
        $tree .= '" value="1"';
        if (isset($this->_value) && in_array($option['id'], $this->_value)) {
            $tree .= ' checked';
        }
        $tree .= ' />'
                 . $option['name']
                 . "<input type=\"hidden\" name=\""
                 . $this->getName()
                 . '[parents]['
                 . $option['id']
                 . "]\" value=\""
                 . implode(':', $parentIds)
                 . "\" /><input type=\"hidden\" name=\""
                 . $this->getName()
                 . '[itemname]['
                 . $option['id']
                 . "]\" value=\""
                 . htmlspecialchars($option['name'])
                 . "\" /><br>\n";
        if (isset($option['children'])) {
            foreach ($option['children'] as $child) {
                array_push($parentIds, $option['id']);
                $this->_renderOptionTree($tree, $this->_optionTree[$child], $prefix . '&nbsp;-', $parentIds);
            }
        }
    }
}
