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
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

use Xoopsmodules\lexikon;

require_once __DIR__ . '/../../../include/cp_header.php';
require_once __DIR__ . '/admin_header.php';

xoops_cp_header();

$adminObject = \Xmf\Module\Admin::getInstance();

//IndexTable();
$summary = $utility::getSummary();

$adminObject->addInfoBox(_AM_LEXIKON_SUMMARY);
$adminObject->addInfoBoxLine(sprintf(_AM_LEXIKON_TOTALENTRIES2, '<span class="green">' . $summary['publishedEntries'] . '</span>'), '', 'green');
$adminObject->addInfoBoxLine(sprintf(_AM_LEXIKON_TOTALCATS2, '<span class="green">' . $summary['availableCategories'] . '</span>'), '', 'green');
$adminObject->addInfoBoxLine(sprintf(_AM_LEXIKON_TOTALSUBM2, '<span class="red">' . $summary['submittedEntries'] . '</span>'), '', 'red');
$adminObject->addInfoBoxLine(sprintf(_AM_LEXIKON_TOTALREQ2, '<span class="red">' . $summary['requestedEntries'] . '</span>'), '', 'red');

$adminObject->displayNavigation(basename(__FILE__));
$adminObject->displayIndex();

echo $utility::getServerStats();

require_once __DIR__ . '/admin_footer.php';
