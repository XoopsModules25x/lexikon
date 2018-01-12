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

$moduleDirName = basename(dirname(__DIR__));

$permHelper = new Permission($moduleDirName);

/**
 * Class LexikonEntriesHandler
 */
class EntriesHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     * @param null|\XoopsDatabase $db
     */

    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'lxentries', Entries::class, 'entryID', 'term');
    }
}
