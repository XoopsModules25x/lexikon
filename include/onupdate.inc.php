<?php

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

// referer check
$ref = xoops_getenv('HTTP_REFERER');
if ($ref == '' || strpos($ref, XOOPS_URL . '/modules/system/admin.php') === 0) {
    /* module specific part */

    /* General part */

    // Keep the values of block's options when module is updated (by nobunobu)
    include __DIR__ . '/updateblock.inc.php';
}
