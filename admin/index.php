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
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

use Xmf\Module\Admin;
use Xmf\Request;
use Xmf\Yaml;
use XoopsModules\Lexikon\{
    Common,
    Helper,
    Utility
};

/** @var Admin $adminObject */
/** @var Helper $helper */
/** @var Utility $utility */

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once __DIR__ . '/admin_header.php';

xoops_cp_header();

$adminObject = Admin::getInstance();

//IndexTable();
$summary = $utility::getSummary();

$adminObject->addInfoBox(_AM_LEXIKON_SUMMARY);
$adminObject->addInfoBoxLine(sprintf(_AM_LEXIKON_TOTALENTRIES2, '<span class="green">' . $summary['publishedEntries'] . '</span>'), '', 'green');
$adminObject->addInfoBoxLine(sprintf(_AM_LEXIKON_TOTALCATS2, '<span class="green">' . $summary['availableCategories'] . '</span>'), '', 'green');
$adminObject->addInfoBoxLine(sprintf(_AM_LEXIKON_TOTALSUBM2, '<span class="red">' . $summary['submittedEntries'] . '</span>'), '', 'red');
$adminObject->addInfoBoxLine(sprintf(_AM_LEXIKON_TOTALREQ2, '<span class="red">' . $summary['requestedEntries'] . '</span>'), '', 'red');

$adminObject->displayNavigation(basename(__FILE__));

//check for latest release
//$newRelease = $utility->checkVerModule($helper);
//if (!empty($newRelease)) {
//    $adminObject->addItemButton($newRelease[0], $newRelease[1], 'download', 'style="color : Red"');
//}

//------------- Test Data ----------------------------

if ($helper->getConfig('displaySampleButton')) {
    $yamlFile            = dirname(__DIR__) . '/config/admin.yml';
    $config              = loadAdminConfig($yamlFile);
    $displaySampleButton = $config['displaySampleButton'];

    if (1 == $displaySampleButton) {
        xoops_loadLanguage('admin/modulesadmin', 'system');
        require_once dirname(__DIR__) . '/testdata/index.php';

        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'ADD_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=load', 'add');
        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=save', 'add');
        //    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA'), '__DIR__ . /../../testdata/index.php?op=exportschema', 'add');
        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'HIDE_SAMPLEDATA_BUTTONS'), '?op=hide_buttons', 'delete');
    } else {
        $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLEDATA_BUTTONS'), '?op=show_buttons', 'add');
        $displaySampleButton = $config['displaySampleButton'];
    }
    $adminObject->displayButton('left', '');
}

//------------- End Test Data ----------------------------

$adminObject->displayIndex();

/**
 * @param $yamlFile
 * @return array|bool
 */
function loadAdminConfig($yamlFile)
{
    $config = Yaml::readWrapped($yamlFile); // work with phpmyadmin YAML dumps
    return $config;
}

/**
 * @param $yamlFile
 */
function hideButtons($yamlFile)
{
    $app['displaySampleButton'] = 0;
    Yaml::save($app, $yamlFile);
    redirect_header('index.php', 0, '');
}

/**
 * @param $yamlFile
 */
function showButtons($yamlFile)
{
    $app['displaySampleButton'] = 1;
    Yaml::save($app, $yamlFile);
    redirect_header('index.php', 0, '');
}

$op = Request::getString('op', 0, 'GET');

switch ($op) {
    case 'hide_buttons':
        hideButtons($yamlFile);
        break;
    case 'show_buttons':
        showButtons($yamlFile);
        break;
}

echo $utility::getServerStats();

require __DIR__ . '/admin_footer.php';

