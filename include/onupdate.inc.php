<?php

// referer check
$ref = xoops_getenv('HTTP_REFERER');
if ('' == $ref || 0 === mb_strpos($ref, XOOPS_URL . '/modules/system/admin.php')) {
    /* module specific part */

    /* General part */

    // Keep the values of block's options when module is updated (by nobunobu)
    require __DIR__ . '/updateblock.inc.php';
}
