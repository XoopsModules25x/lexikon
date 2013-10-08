<?php
// $Id: notification.inc.php,v 1.1 2003/04/01 23:40:27 w4z004 Exp $
//  ------------------------------------------------------------------------ //

if( ! defined( 'XOOPS_ROOT_PATH' ) ) die( 'XOOPS root path not defined' ) ;

function lexikon_notify_iteminfo($category, $item_id){
	/*global $xoopsModule, $xoopsModuleConfig, $xoopsConfig;

	if (empty($xoopsModule) || $xoopsModule->getVar('dirname') != 'lexikon') {	
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->getByDirname('lexikon');
		$config_handler =& xoops_gethandler('config');
		$config =& $config_handler->getConfigsByCat(0,$module->getVar('mid'));
	} else {
		$module =& $xoopsModule;
		$config =& $xoopsModuleConfig;
	}*/
	if(strpos(dirname(__FILE__),'/')>0) {
		$pathparts = explode("/", dirname(__FILE__));
	} else {
		$pathparts = explode("\\", dirname(__FILE__));
	}
	$moduleDirName = $pathparts[array_search('modules', $pathparts)+1];// checken
	
	if ($category=='global') {
		$item['name'] = '';
		$item['url'] = '';
		return $item;
	}
	$item_id = intval($item_id);
	
	global $xoopsDB;
	if ($category=='category') {
		// Assume we have a valid category id
		$sql = 'SELECT name FROM ' . $xoopsDB->prefix('lxcategories') . ' WHERE categoryID = '.$item_id;
		if (!$result = $xoopsDB->query($sql)){
			  redirect_header("index.php", 2, _ERRORS);
    		exit();
		}
		$result = $xoopsDB->query($sql);
		$result_array = $xoopsDB->fetchArray($result);
		$item['name'] = $result_array['name'];
		$item['url'] = XOOPS_URL . '/modules/lexikon/category.php?categoryID=' . $item_id;
		return $item;
	}
	
	if ($category=='term') {
		// Assume we have a valid entry id
		$sql = 'SELECT entryID,term FROM '.$xoopsDB->prefix('lxentries') . ' WHERE entryID = ' . $item_id;
		if (!$result = $xoopsDB->query($sql)){
			  redirect_header("index.php", 2, _ERRORS);
    		exit();
		}
		$result = $xoopsDB->query($sql);
		$result_array = $xoopsDB->fetchArray($result);
		$item['name'] = $result_array['term'];
		$item['url'] = XOOPS_URL . '/modules/lexikon/entry.php?entryID=' . $item_id;
		return $item;
	}
}
?>