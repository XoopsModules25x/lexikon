<?php namespace XoopsModules\Lexikon;

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

use Xmf\Module\Helper\Permission;
use XoopsModules\Lexikon;
use XoopsModules\Lexikon\Form;

require_once __DIR__ . '/../include/common.php';

$moduleDirName = basename(dirname(__DIR__));

$permHelper = new Permission($moduleDirName);

/**
 * Class Categories
 */
class Categories extends \XoopsObject
{
    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('categoryID', XOBJ_DTYPE_INT);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('description', XOBJ_DTYPE_OTHER);
        $this->initVar('total', XOBJ_DTYPE_INT);
        $this->initVar('weight', XOBJ_DTYPE_INT);
        $this->initVar('logourl', XOBJ_DTYPE_TXTBOX);
    }

    /**
     * Get form
     *
     * @param null
     * @return Lexikon\Form\CategoriesForm
     */
    public function getForm()
    {
        require_once XOOPS_ROOT_PATH . '/modules/lexikon/class/form/categories.php';

        $form = new Form\CategoriesForm($this);
        return $form;
    }

    /**
     * @return array|null
     */
    public function getGroupsRead()
    {
        global $permHelper;
        //return $this->publisher->getHandler('permission')->getGrantedGroupsById('categories_read', categoryID);
        return $permHelper->getGroupsForItem('sbcolumns_read', $this->getVar('categoryID'));
    }

    /**
     * @return array|null
     */
    public function getGroupsSubmit()
    {
        global $permHelper;
        //        return $this->publisher->getHandler('permission')->getGrantedGroupsById('categories_submit', categoryID);
        return $permHelper->getGroupsForItem('sbcolumns_submit', $this->getVar('categoryID'));
    }

    /**
     * @return array|null
     */
    public function getGroupsModeration()
    {
        global $permHelper;
        //        return $this->publisher->getHandler('permission')->getGrantedGroupsById('categories_moderation', categoryID);
        return $permHelper->getGroupsForItem('sbcolumns_moderation', $this->getVar('categoryID'));
    }
}
