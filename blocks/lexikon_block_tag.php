<?php
/**
 * Module: Lexikon -  glossary module
 * Licence: GNU
 */
/**
 * Tag management
 *
 * @copyright      XOOPS Project (http://xoops.org)
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author         Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since          1.00
 * @package        module::tag
 */

/**#@+
 * Function to display tag cloud
 *
 * Developer guide:
 * <ul>
 *    <li>Build your tag_block_cloud_show function, for example newbb_block_tag_cloud_show;</li>
 *    <li>Call the tag_block_cloud_show in your defined block function:<br>
 *        <code>
 *            function newbb_block_tag_cloud_show($options) {
 *                $catid        = $options[4];    // Not used by newbb, Only for demonstration
 *                if (!@include_once XOOPS_ROOT_PATH."/modules/tag/blocks/block.php") {
 *                    return null;
 *                }
 *                $block_content = tag_block_cloud_show($options, "newbb", $catid);
 *                return $block_content;
 *            }
 *        </code>
 *    </li>
 *    <li>Build your tag_block_cloud_edit function, for example newbb_block_tag_cloud_edit;</li>
 *    <li>Call the tag_block_cloud_edit in your defined block function:<br>
 *        <code>
 *            function newbb_block_tag_cloud_edit($options) {
 *                if (!@include_once XOOPS_ROOT_PATH."/modules/tag/blocks/block.php") {
 *                    return null;
 *                }
 *                $form = tag_block_cloud_edit($options);
 *                $form .= $CODE_FOR_GET_CATID;    // Not used by newbb, Only for demonstration
 *                return $form;
 *            }
 *        </code>
 *    </li>
 *    <li>Create your tag_block_cloud template, for example newbb_block_tag_cloud.html;</li>
 *    <li>Include tag_block_cloud template in your created block template:<br>
 *        <code>
 *            <{include file="db:tag_block_cloud.tpl"}>
 *        </code>
 *    </li>
 * </ul>
 *
 * {@link TagTag}
 *
 * @param    array $options :
 *                          $options[0] - number of tags to display
 *                          $options[1] - time duration, in days, 0 for all the time
 *                          $options[2] - max font size (px or %)
 *                          $options[3] - min font size (px or %)
 * @return array
 */

function lexikon_tag_block_cloud_show($options)
{
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname('lexikon');

    if (xoops_isActiveModule('tag')) {
        include_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';
        //$module_dirname = basename( dirname( __DIR__ ) ) ;
        //return tag_block_cloud_show($options, $module_dirname);
        return tag_block_cloud_show($options, $module->getVar('dirname'));
    }
}

/**
 * @param $options
 * @return string
 */
function lexikon_tag_block_cloud_edit($options)
{
    if (xoops_isActiveModule('tag')) {
        include_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        return tag_block_cloud_edit($options);
    }
}

/**
 * @param $options
 * @return array
 */
function lexikon_tag_block_top_show($options)
{
    if (xoops_isActiveModule('tag')) {
        include_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname('lexikon');

        return tag_block_top_show($options, $module->getVar('dirname'));
    }
}

/**
 * @param $options
 * @return string
 */
function lexikon_tag_block_top_edit($options)
{
    if (xoops_isActiveModule('tag')) {
        include_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        return tag_block_top_edit($options);
    }
}
