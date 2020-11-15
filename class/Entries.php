<?php

namespace XoopsModules\Lexikon;

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
use XoopsModules\Lexikon;

require_once \dirname(__DIR__) . '/include/common.php';

$moduleDirName = \basename(\dirname(__DIR__));

$permHelper = new \Xmf\Module\Helper\Permission($moduleDirName);

/**
 * Class LexikonEntries
 */
class Entries extends \XoopsObject
{
    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('entryID', \XOBJ_DTYPE_INT);
        $this->initVar('categoryID', \XOBJ_DTYPE_INT);
        $this->initVar('term', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('init', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('definition', \XOBJ_DTYPE_OTHER);
        $this->initVar('ref', \XOBJ_DTYPE_OTHER);
        $this->initVar('url', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('uid', \XOBJ_DTYPE_INT);
        $this->initVar('submit', \XOBJ_DTYPE_INT);
        $this->initVar('datesub', \XOBJ_DTYPE_TIMESTAMP);
        $this->initVar('counter', \XOBJ_DTYPE_INT);
        $this->initVar('html', \XOBJ_DTYPE_INT);
        $this->initVar('smiley', \XOBJ_DTYPE_INT);
        $this->initVar('xcodes', \XOBJ_DTYPE_INT);
        $this->initVar('breaks', \XOBJ_DTYPE_INT);
        $this->initVar('block', \XOBJ_DTYPE_INT);
        $this->initVar('offline', \XOBJ_DTYPE_INT);
        $this->initVar('notifypub', \XOBJ_DTYPE_INT);
        $this->initVar('request', \XOBJ_DTYPE_INT);
        $this->initVar('comments', \XOBJ_DTYPE_INT);
        //        $this->initVar('item_tag', XOBJ_DTYPE_OTHER);
    }

    /**
     * Get form
     *
     * @param null
     * @return Form\EntriesForm
     */
    public function getForm()
    {
        //        require_once XOOPS_ROOT_PATH . '/modules/lexikon/class/form/entries.php';

        $form = new Form\EntriesForm($this);

        return $form;
    }

    /**
     * @return array|null
     */
    public function getGroupsRead()
    {
        global $permHelper;
        //return $this->publisher->getHandler('permission')->getGrantedGroupsById('entries_read', entryID);
        return $permHelper->getGroupsForItem('sbcolumns_read', $this->getVar('entryID'));
    }

    /**
     * @return array|null
     */
    public function getGroupsSubmit()
    {
        global $permHelper;
        //        return $this->publisher->getHandler('permission')->getGrantedGroupsById('entries_submit', entryID);
        return $permHelper->getGroupsForItem('sbcolumns_submit', $this->getVar('entryID'));
    }

    /**
     * @return array|null
     */
    public function getGroupsModeration()
    {
        global $permHelper;
        //        return $this->publisher->getHandler('permission')->getGrantedGroupsById('entries_moderation', entryID);
        return $permHelper->getGroupsForItem('sbcolumns_moderation', $this->getVar('entryID'));
    }
}
