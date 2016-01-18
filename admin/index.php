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
 * @copyright    The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author     XOOPS Development Team
 * @version    $Id $
 */

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/include/cp_header.php';
include_once dirname(__FILE__) . '/admin_header.php';

xoops_cp_header();

    $indexAdmin = new ModuleAdmin();

//IndexTable();
$summary = lexikon_summary();

$indexAdmin->addInfoBox(_AM_LEXIKON_SUMMARY);
$indexAdmin->addInfoBoxLine(_AM_LEXIKON_SUMMARY,   _AM_LEXIKON_TOTALENTRIES2,$summary['publishedEntries'],  'Green');
$indexAdmin->addInfoBoxLine(_AM_LEXIKON_SUMMARY,   _AM_LEXIKON_TOTALCATS2, $summary['availableCategories'], 'Green');
$indexAdmin->addInfoBoxLine(_AM_LEXIKON_SUMMARY,   _AM_LEXIKON_TOTALSUBM2, $summary['submittedEntries'],  'Red');
$indexAdmin->addInfoBoxLine(_AM_LEXIKON_SUMMARY,   _AM_LEXIKON_TOTALREQ2,$summary['requestedEntries'],  'Red');

    echo $indexAdmin->addNavigation('index.php');
    echo $indexAdmin->renderIndex();

include "admin_footer.php";
