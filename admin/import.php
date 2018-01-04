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

require_once __DIR__ . '/admin_header.php';

xoops_cp_header();

$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));
lx_importMenu(9);
$adminObject->addItemButton(_AM_LEXIKON_IMPORT_WORDBOOK, 'importwordbook.php', 'add');
$adminObject->addItemButton(_AM_LEXIKON_IMPORT_DICTIONARY, 'importdictionary.php', 'add');
$adminObject->addItemButton(_AM_LEXIKON_IMPORT_GLOSSAIRE, 'importglossaire.php', 'add');
$adminObject->addItemButton(_AM_LEXIKON_IMPORT_WIWIMOD, 'importwiwimod.php', 'add');
$adminObject->addItemButton(_AM_LEXIKON_IMPORT_XWORDS, 'importxwords.php', 'add');
$adminObject->displayButton('left');

require_once __DIR__ . '/admin_footer.php';
