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
 * Renders checkbox options for a group permission form
 *
 * @author       Kazumi Ono    <onokazu@myweb.ne.jp>
 * @copyright    copyright (c) 2000-2003 XOOPS.org
 *
 * @package      kernel
 * @subpackage   form
 */
class GroupFormCheckBox extends \XoopsFormElement
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
        if (\is_array($value)) {
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
    public function setOptionTree($optionTree)
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

        if (\count($this->_appendix) > 0) {
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
                $ret     .= "<td class=\"odd\"><input type=\"checkbox\" name=\"{$name}[groups][$this->_groupId][$itemid]\" id=\"{$name}[groups][$this->_groupId][$itemid]\" value=\"1\" $checked>{$append['itemname']}<input type=\"hidden\" name=\"{$name}[parents][$itemid]\" value=\"\"><input type=\"hidden\" name=\"{$name}[itemname][$itemid]\" value=\"{$append['itemname']}\"><br></td>";
                ++$cols;
            }
            $ret .= '</tr></table>';
        }

        $ret  .= '<table class="outer"><tr>';
        $cols = 1;
        if (!empty($this->_optionTree[0]['children'])) {
            foreach ($this->_optionTree[0]['children'] as $topitem) {
                if ($cols > 4) {
                    $ret  .= '</tr><tr>';
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
        $tree .= $prefix . '<input type="checkbox" name="' . $this->getName() . '[groups][' . $this->_groupId . '][' . $option['id'] . ']" id="' . $this->getName() . '[groups][' . $this->_groupId . '][' . $option['id'] . ']" onclick="';
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
        if (isset($this->_value) && \in_array($option['id'], $this->_value)) {
            $tree .= ' checked';
        }
        $tree .= '>' . $option['name'] . '<input type="hidden" name="' . $this->getName() . '[parents][' . $option['id'] . ']" value="' . \implode(':', $parentIds) . '"><input type="hidden" name="' . $this->getName() . '[itemname][' . $option['id'] . ']" value="' . \htmlspecialchars(
                $option['name'],
                \ENT_QUOTES | \ENT_HTML5
            ) . "\"><br>\n";
        if (isset($option['children'])) {
            foreach ($option['children'] as $child) {
                \array_push($parentIds, $option['id']);
                $this->_renderOptionTree($tree, $this->_optionTree[$child], $prefix . '&nbsp;-', $parentIds);
            }
        }
    }
}
