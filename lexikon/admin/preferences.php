<?php
/**
* $Id: preferences.php,v 1.3 2005/09/06 18:51:55 malanciault Exp $
* Module: Lexikon
* Author: Xavier JIMENEZ
* Licence: GNU
*/

include_once "../../../mainfile.php";

include_once XOOPS_ROOT_PATH . "/kernel/module.php";
include_once XOOPS_ROOT_PATH . "/class/xoopstree.php";
include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
    include "../language/".$xoopsConfig['language']."/main.php";
} else {
    include "../language/english/main.php";
}
include_once XOOPS_ROOT_PATH."/modules/lexikon/include/functions.php";
include_once XOOPS_ROOT_PATH."/modules/lexikon/admin/functions.php";
include_once XOOPS_ROOT_PATH."/kernel/module.php";
$xoopsModule = XoopsModule::getByDirname("lexikon");


ob_start();
//lx_adminmenu(0, _PREFERENCES);
$btnsbar = ob_get_contents();
ob_end_clean();

function addAdminMenu($buf) {
	global $btnsbar;
	
	$pattern = array(
	"#admin.php?#",
	"#(<div class='content'>)#",
	);
	$replace = array(
	"preferences.php?",
	" $1 <br />".$btnsbar . "<div style='clear: both' class='content'>",
	);
	$html = preg_replace($pattern,$replace,$buf);
	return $html;
	
	//		ereg("(.*)(<div class='content'>.*)",$buf,$regs);
	//		return $regs[1].$btnsbar.$regs[2];
}


/*
* Display and capture preferences screen
*/

if (!isset($_POST['fct'])) $_GET['fct'] = $_GET['fct'] = "preferences";
if (!isset($_POST['op'])) $_GET['op' ] = $_GET['op' ] = "showmod";
if (!isset($_POST['mod'])) $_GET['mod'] = $_GET['mod'] = $xoopsModule->getVar('mid');
chdir(XOOPS_ROOT_PATH."/modules/system/");
ob_start("addAdminMenu");
include XOOPS_ROOT_PATH."/modules/system/admin.php";
ob_end_flush();
?>