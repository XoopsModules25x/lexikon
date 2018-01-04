<?php
/**
 * Module: Lexikon - glossary module
 * Author: hsalazar
 * Licence: GNU
 */

$rform = new \XoopsThemeForm(_MD_LEXIKON_REQUESTFORM, 'requestform', 'request.php');

if (!$xoopsUser) {
    $username_v = _MD_LEXIKON_ANONYMOUS;
}

$name_text = new \XoopsFormText(_MD_LEXIKON_USERNAME, 'username', 35, 100, $username_v);
$rform->addElement($name_text, false);

$email_text = new \XoopsFormText(_MD_LEXIKON_USERMAIL, 'usermail', 40, 100, $usermail_v);
$rform->addElement($email_text, false);

$reqterm_text = new \XoopsFormText(_MD_LEXIKON_REQTERM, 'reqterm', 30, 150);
$rform->addElement($reqterm_text, true);

if (is_object($xoopsUser)) {
    $notify_checkbox = new \XoopsFormCheckBox('', 'notifypub', $notifypub);
    $notify_checkbox->addOption(1, _MD_LEXIKON_NOTIFY);
    $rform->addElement($notify_checkbox);
}

$submit_button = new \XoopsFormButton('', 'submit', _MD_LEXIKON_SUBMIT, 'submit');
$rform->addElement($submit_button);
