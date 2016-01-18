<?php
/**
 * $Id: storyform.inc.php v 1.0 8 May 2004 hsalazar Exp $
 * Module: Lexikon
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Chanegs: Yerres
 * Licence: GNU
 */

global $term, $definition, $ref, $url, $xoopsUser, $xoopsModule, $xoopsModuleConfig;

include_once XOOPS_ROOT_PATH . "/class/xoopstree.php";
include XOOPS_ROOT_PATH . "/class/xoopslists.php";
include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

$guesteditoruse = $xoopsModuleConfig['wysiwyg_guests'];
$myts =& MyTextSanitizer::getInstance();
$mytree = new XoopsTree( $xoopsDB -> prefix( "lxcategories" ), "categoryID", "0" );
$sform = new XoopsThemeForm( _MD_LEXIKON_SUB_SMNAME, "storyform", xoops_getenv( 'PHP_SELF' ) );

if ($xoopsModuleConfig['multicats'] == '1') {
    // perms adapted category select
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gperm_handler =& xoops_gethandler('groupperm');
    $allowed_cats =& $gperm_handler->getItemIds("lexikon_submit", $groups, $xoopsModule->getVar('mid'));
    if (is_array($allowed_cats)) {
        $mytree = new XoopsTree( $xoopsDB->prefix( "lxcategories" ), "categoryID" , "0" );
        $categoryselect = new XoopsFormSelect(_MD_LEXIKON_ENTRYCATEGORY, 'categoryID', $allowed_cats);
        $tbl = array();
        $tbl = $mytree->getChildTreeArray(0,'name');
        foreach($tbl as $oneline) {
            if (in_array($oneline['categoryID'], $allowed_cats)) {
                if ($oneline['prefix']=='.') {
                    $oneline['prefix']='';
                }
        
                $oneline['prefix'] = str_replace('.','-',$oneline['prefix']);
                //if (in_array($oneline['categoryID'], $allowed_cats)) {
                    $categoryselect->addOption($oneline['categoryID'], $oneline['prefix'].' '.$oneline['name']);
                    }
                }
        }
    $sform->addElement( $categoryselect, true );
/*    ob_start();
    $sform -> addElement( new XoopsFormHidden( 'categoryID', $categoryID ) );
    $mytree -> makeMySelBox( "name", "name", $categoryID );
    $sform -> addElement( new XoopsFormLabel( _MD_LEXIKON_ENTRYCATEGORY, ob_get_contents() ) );
    ob_end_clean();
*/
}
// This part is common to edit/add
$myts =& MyTextSanitizer::getInstance();
$term = $myts->htmlSpecialChars($term);
$sform -> addElement( new XoopsFormText( _MD_LEXIKON_ENTRY, 'term', 50, 80, $term ), true );

/*$editor = lx_getWysiwygForm( _MD_LEXIKON_DEFINITION, 'definition', _MD_LEXIKON_WRITEHERE, 15, 60 );
  if ($definition == _MD_LEXIKON_WRITEHERE) {
      $editor -> setExtra( 'onfocus="this.select()"' );
  }
  $sform->addElement($editor,true);
  unset($editor);
*/
//editor for guests/users
if(isset( $guesteditoruse )){
  //if(isset($xoopsUser) && is_object($xoopsUser) ) {
  if ( $xoopsUser ) {
    $editor = lx_getWysiwygForm( _MD_LEXIKON_DEFINITION, 'definition', _MD_LEXIKON_WRITEHERE, 15, 60 );
    if ($definition == _MD_LEXIKON_WRITEHERE) {
        $editor -> setExtra( 'onfocus="this.select()"' );
    }
    $sform->addElement($editor,true);
    unset($editor);
  } else {
    $def_block = new XoopsFormDhtmlTextArea( _MD_LEXIKON_DEFINITION, 'definition', _MD_LEXIKON_WRITEHERE, 15, 60 );
    $def_block -> setExtra( 'onfocus="this.select()"' );
    $sform -> addElement ( $def_block, true );
  }
}

$sform -> addElement( new XoopsFormTextArea( _MD_LEXIKON_REFERENCE, 'ref', $ref, 5, 50 ), false );
$sform -> addElement( new XoopsFormText( _MD_LEXIKON_URL, 'url', 50, 80, $url ), false );

if ( is_object( $xoopsUser ) ) {
    $uid = $xoopsUser->getVar('uid');
    $sform -> addElement( new XoopsFormHidden( 'uid', $uid ) );

    $notify_checkbox = new XoopsFormCheckBox( '', 'notifypub', $notifypub );
    $notify_checkbox -> addOption( 1, _MD_LEXIKON_NOTIFY );
    $sform -> addElement( $notify_checkbox );
}
//--- Captcha - Ohne Gewähr
if (lx_getmoduleoption('captcha') == 1) {
    $skipMember = 1;
} elseif (lx_getmoduleoption('captcha') == 2){
    $skipMember = 0;
}
if ($xoopsModuleConfig['captcha'] != 0) {
  xoops_load('XoopsFormCaptcha');
  if ( class_exists( 'XoopsFormCaptcha' ) )  $sform -> addElement( new XoopsFormCaptcha ('', 'xoopscaptcha', $skipMember), true);
}
$button_tray = new XoopsFormElementTray( '', '' );
$hidden = new XoopsFormHidden( 'op', 'post' );
$button_tray -> addElement( $hidden );
$button_tray -> addElement( new XoopsFormButton( '', 'post', _MD_LEXIKON_CREATE, 'submit' ) );

$sform -> addElement( $button_tray );

unset( $hidden );
