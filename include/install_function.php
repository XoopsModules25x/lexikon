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
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author       XOOPS Development Team
 */

//from extgallery

//error_reporting(E_ALL);

/**
 * @param $xoopsModule
 * @return bool
 */
function xoops_module_install_lexikon(\XoopsObject $xoopsModule)
{
    $module_id = $xoopsModule->getVar('mid');
    /** @var \XoopsGroupPermHandler $grouppermHandler */
    $grouppermHandler = xoops_getHandler('groupperm');
    /** @var \XoopsConfigHandler $configHandler */
    $configHandler = xoops_getHandler('config');

    /**
     * Default public category permission mask
     */

    // Access right
    $grouppermHandler->addRight('lexikon_view', 1, XOOPS_GROUP_ADMIN, $module_id);
    $grouppermHandler->addRight('lexikon_view', 1, XOOPS_GROUP_USERS, $module_id);
    $grouppermHandler->addRight('lexikon_view', 1, XOOPS_GROUP_ANONYMOUS, $module_id);

    // Public submit
    $grouppermHandler->addRight('lexikon_submit', 1, XOOPS_GROUP_ADMIN, $module_id);
    $grouppermHandler->addRight('lexikon_submit', 1, XOOPS_GROUP_USERS, $module_id);

    // Public request
    $grouppermHandler->addRight('lexikon_request', 1, XOOPS_GROUP_ADMIN, $module_id);
    $grouppermHandler->addRight('lexikon_request', 1, XOOPS_GROUP_USERS, $module_id);
    $grouppermHandler->addRight('lexikon_request', 1, XOOPS_GROUP_ANONYMOUS, $module_id);

    /**
     * Create default upload directories
     */
    // Copy base file
    $indexFile = XOOPS_UPLOAD_PATH . '/index.html';
    $blankFile = XOOPS_UPLOAD_PATH . '/blank.gif';
    // Making of uploads/lexikon folder
    $p_lexikon = XOOPS_UPLOAD_PATH . '/lexikon';
    if (!is_dir($p_lexikon)) {
        mkdir($p_lexikon, 0777);
        chmod($p_lexikon, 0777);
    }
    copy($indexFile, $p_lexikon . '/index.html');
    // Making of categories folder
    $pl_categories = $p_lexikon . '/categories';
    if (!is_dir($pl_categories)) {
        mkdir($pl_categories, 0777);
        chmod($pl_categories, 0777);
    }
    copy($indexFile, $pl_categories . '/index.html');

    $plc_images = $pl_categories . '/images';
    if (!is_dir($plc_images)) {
        mkdir($plc_images, 0777);
        chmod($plc_images, 0777);
    }
    copy($indexFile, $plc_images . '/index.html');
    copy($blankFile, $plc_images . '/blank.gif');
    // Making of entries folder
    $pl_entries = $p_lexikon . '/entries';
    if (!is_dir($pl_entries)) {
        mkdir($pl_entries, 0777);
        chmod($pl_entries, 0777);
    }
    copy($indexFile, $pl_entries . '/index.html');

    $ple_images = $pl_entries . '/images';
    if (!is_dir($ple_images)) {
        mkdir($ple_images, 0777);
        chmod($ple_images, 0777);
    }
    copy($indexFile, $ple_images . '/index.html');
    copy($blankFile, $ple_images . '/blank.gif');

    return true;
}
