<?php
/**
 * Tag info
 *
 * @copyright      XOOPS Project (https://xoops.org)
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author         Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @package        module::tag
 */
// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');
/**
 * Get item fields:
 * title
 * content
 * time
 * link
 * uid
 * uname
 * tags
 *
 * @var array $items associative array of items: [modid][catid][itemid]
 *
 * @return boolean
 *
 */

function lexikon_tag_iteminfo(&$items)
{
    if (empty($items) || !is_array($items)) {
        return false;
    }

    global $xoopsDB;
    $myts = \MyTextSanitizer::getInstance();

    $items_id = [];

    foreach (array_keys($items) as $cat_id) {
        // Some handling here to build the link upon catid
        // If catid is not used, just skip it
        foreach (array_keys($items[$cat_id]) as $item_id) {
            // In article, the item_id is "art_id"
            $items_id[] = (int)$item_id;
        }
    }

    foreach (array_keys($items) as $cat_id) {
        foreach (array_keys($items[$cat_id]) as $item_id) {
            $sql                      = 'SELECT  l.entryID, l.categoryID, l.term AS ltitle, l.definition, l.uid, l.datesub, l.offline, c.name AS cname FROM '
                                        . $xoopsDB->prefix('lxentries')
                                        . ' l, '
                                        . $xoopsDB->prefix('lxcategories')
                                        . ' c WHERE l.entryID='
                                        . $item_id
                                        . ' AND l.categoryID=c.categoryID AND l.offline=0 ORDER BY l.datesub DESC';
            $result                   = $xoopsDB->query($sql);
            $row                      = $xoopsDB->fetchArray($result);
            $items[$cat_id][$item_id] = [
                'title'   => $row['ltitle'],
                'uid'     => $row['uid'],
                'link'    => "entry.php?entryID=$item_id",
                'time'    => $row['datesub'],
                'content' => $row['definition']
            ];
        }
    }
}
