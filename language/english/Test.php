<?php

namespace XoopsModules\Lexikon;

/**
 *
 */
class Test
{
    public const MODULE_NAME = 'Lexikon';
    public const ROOT = $_SERVER['DOCUMENT_ROOT'] . 'project/';
    public const MODULE_DIRNAME = \basename(\dirname(__DIR__));
    public const MODULE_URL = XOOPS_URL . '/modules/' . self::MODULE_DIRNAME;
    public const IMAGES_URL = PUBLISHER_URL . '/assets/images';
    public const ADMIN_URL = PUBLISHER_URL . '/admin';
    public const UPLOADS_URL = XOOPS_URL . '/uploads/' . self::MODULE_DIRNAME;
    public const MODULE_PATH = XOOPS_ROOT_PATH . '/modules/' . self::MODULE_DIRNAME;
    public const UPLOADS_PATH = XOOPS_ROOT_PATH . '/uploads/' . self::MODULE_DIRNAME;

    //Application Folders (from xHelp module)
    /*
    define('BASE_PATH', XOOPS_ROOT_PATH.'/modules/'. XHELP_DIR_NAME);
    define('CLASS_PATH', XHELP_BASE_PATH.'/class');
    define('BASE_URL', XHELP_SITE_URL .'/modules/'. XHELP_DIR_NAME);
*/
}
