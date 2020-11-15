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
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 * @link            https://xoops.org/
 * @since           1.0.0
 */

use XoopsModules\Lexikon\{
    Helper,
    Utility
};
/** @var Helper $helper */
/** @var Utility $utility */

/**
 * Prepares system prior to attempting to uninstall module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to uninstall, false if not
 */
function xoops_module_pre_uninstall_lexikon(\XoopsModule $module)
{
    // Do some synchronization if needed
    return true;
}

/**
 * Performs tasks required during uninstallation of the module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if uninstallation successful, false if not
 */
function xoops_module_uninstall_lexikon(\XoopsModule $module)
{
    require dirname(__DIR__) . '/preloads/autoloader.php';
    $moduleDirName      = basename(dirname(__DIR__));
    $moduleDirNameUpper = mb_strtoupper($moduleDirName); //$capsDirName

    $helper  = Helper::getInstance();
    $utility = new Utility();
    //    $configurator = new Lexikon\Common\Configurator();

    // Load language files
    $helper->loadLanguage('admin');
    $helper->loadLanguage('common');
    $success = true;

    //------------------------------------------------------------------
    // Remove uploads folder (and all subfolders) if they exist
    //------------------------------------------------------------------
    /*
        $old_directories = [$GLOBALS['xoops']->path("uploads/{$moduleDirName}")];
        foreach ($old_directories as $old_dir) {
            $dirInfo = new \SplFileInfo($old_dir);
            if ($dirInfo->isDir()) {
                // The directory exists so delete it
                if (false === $utility::rrmdir($old_dir)) {
                    $module->setErrors(sprintf(constant('CO_' . $moduleDirNameUpper . '_ERROR_BAD_DEL_PATH'), $old_dir));
                    $success = false;
                }
            }
            unset($dirInfo);
        }
        */

    /*
    //------------ START ----------------
    //------------------------------------------------------------------
    // Remove xsitemap.xml from XOOPS root folder if it exists
    //------------------------------------------------------------------
    $xmlfile = $GLOBALS['xoops']->path('xsitemap.xml');
    if (is_file($xmlfile)) {
        if (false === ($delOk = unlink($xmlfile))) {
            $module->setErrors(sprintf(constant('CO_' . $moduleDirNameUpper . '_ERROR_BAD_REMOVE'), $xmlfile));
        }
    }
//    return $success && $delOk; // use this if you're using this routine
*/

    return $success;
    //------------ END  ----------------
}
