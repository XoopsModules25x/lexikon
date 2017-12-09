<?php
/**
 *
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */

// Module Info
// The name of this module
global $xoopsModule;
define('_MI_LEXIKON_MD_NAME', 'Lexikon');

// A brief description of this module
define('_MI_LEXIKON_MD_DESC', 'A multicategory glossary');

// Sub menus in main menu block
define('_MI_LEXIKON_SUB_SMNAME0', 'Administration');
define('_MI_LEXIKON_SUB_SMNAME1', 'Submit an entry');
define('_MI_LEXIKON_SUB_SMNAME2', 'Request a definition');
define('_MI_LEXIKON_SUB_SMNAME3', 'Search for a definition');
define('_MI_LEXIKON_SUB_SMNAME4', 'New definition');
define('_MI_LEXIKON_SUB_SMNAME6', 'Authorlist');
define('_MI_LEXIKON_SUB_SMNAME7', 'WebmasterContent');

// module option
$cf = 1;

define('_MI_LEXIKON_MULTICATS', "$cf. Do you want to have glossary categories?");
define('_MI_LEXIKON_MULTICATSDSC', "If set to 'Yes', will allow you to have glossary categories. If set to no, will have a single automatic category.");

define('_MI_LEXIKON_ALLOWSUBMIT', "$cf. Can users submit entries?");
define('_MI_LEXIKON_ALLOWSUBMITDSC', "If set to 'Yes', users will have access to a submission form.");

++$cf;

define('_MI_LEXIKON_CATSINMENU', "$cf. Should the categories be shown in the menu?");
define('_MI_LEXIKON_CATSINMENUDSC', "If set to 'Yes' if you want links to categories in the main menu.");

define('_MI_LEXIKON_ANONSUBMIT', "$cf. Can guests submit entries?");
define('_MI_LEXIKON_ANONSUBMITDSC', "If set to 'Yes', guests will have access to a submission form.");

++$cf;

define('_MI_LEXIKON_DATEFORMAT', "$cf. In what format should the date appear?");
define('_MI_LEXIKON_DATEFORMATDSC', "Use the final part of language/english/global.php to select a display style. Example: 'd-M-Y H:i' translates to '23-May-2011 22:35'");

define('_MI_LEXIKON_ALLOWREQ', "$cf. Can guests request entries?");
define('_MI_LEXIKON_ALLOWREQDSC', "If set to 'Yes', guests will as well have access to a request form.");

++$cf;

define('_MI_LEXIKON_PERPAGE', "$cf. Number of entries per page (Admin side)?");
define('_MI_LEXIKON_PERPAGEDSC', 'Number of entries that will be shown at once in the table that displays active entries in the admin side.');

++$cf;

define('_MI_LEXIKON_PERPAGEINDEX', "$cf. Number of entries per page (User side)?");
define('_MI_LEXIKON_PERPAGEINDEXDSC', 'Number of entries that will be shown on each page in the user side of the module.');

++$cf;

define('_MI_LEXIKON_BLOCKSPERPAGE', "$cf. Number of entries per Block?");
define('_MI_LEXIKON_BLOCKSPERPAGEDSC', 'How many entries do you want to show in the boxes in the main page ? (Default: 5)');

++$cf;

define('_MI_LEXIKON_AUTOAPPROVE', "$cf. Approve entries automatically?");
define('_MI_LEXIKON_AUTOAPPROVEDSC', "If set to 'Yes', XOOPS will publish submitted entries without admin intervention.");

++$cf;

define('_MI_LEXIKON_ALLOWADMINHITS', "$cf. Will the admin hits be included in the counter?");
define('_MI_LEXIKON_ALLOWADMINHITSDSC', "If set to 'Yes', will increase counter for each entry on admin visits.");

++$cf;

define('_MI_LEXIKON_MAILTOADMIN', "$cf. Send mail to admin on each new submission?");
define('_MI_LEXIKON_MAILTOADMINDSC', "If set to 'Yes', the manager will receive an e-mail for every submitted entry.");

++$cf;
define('_MI_LEXIKON_MAILTOSENDER', "$cf. Send mail to user on each new submission?");
define(
    '_MI_LEXIKON_MAILTOSENDERDSC',
       "If set to 'Yes', the user will receive a confirmation e-mail for every modified, submitted or requested entry. If 'Notify on publish' is ticked, the user will as well receive a confirmation e-mail on publication of the entry."
);
++$cf;

define('_MI_LEXIKON_RANDOMLENGTH', "$cf. Length of string to show in random definitions?");
define('_MI_LEXIKON_RANDOMLENGTHDSC', 'How many characters do you want to show in the random term boxes, both in the main page and in the block? (Default: 150)');

++$cf;
define('_MI_LEXIKON_LINKTERMS', "$cf. Show links to other glossary terms in the definitions?");
define('_MI_LEXIKON_LINKTERMSDSC', "If set to 'yes', will automatically link in your definitions those terms you already have in your glossaries.");

++$cf;
define('_MI_LEXIKON_FORM_OPTIONS', "$cf. Form Option");
define(
    '_MI_LEXIKON_FORM_OPTIONSDSC',
       'What kind of editor would you like to use. <br>Please note that if you choose any other editor than the Xoops-DHTML-Editor, it must be installed under class/xoopseditor.'
);

++$cf;
define('_MI_LEXIKON_EDIGUEST', "$cf. Form Options for submissions");
define('_MI_LEXIKON_EDIGUESTDSC', 'Shall Guests may use editors?');

define('_MI_LEXIKON_DISPPROL', "$cf. Show Submitter on every entry?");
define('_MI_LEXIKON_DISPPROLDSC', "If set to 'yes', will display the author of the entry.");

++$cf;
define('_MI_LEXIKON_HEADER', "$cf. Main Page Introductory Text:");
define('_MI_LEXIKON_HEADERDSC', 'You can use this section to display some descriptive or introductory text. HTML is allowed.');

++$cf;
define('_MI_LEXIKON_AUTHORPROFILE', "$cf. Use author profile?");
define('_MI_LEXIKON_AUTHORPROFILEDSC', "If set to 'yes', the submitter will be linked to the authors glossary-profile. additionally a link to the authorlist will show up in the menu.");

++$cf;
define('_MI_LEXIKON_SHOWDAT', "$cf. Show Date in block of recent Entries?");
define('_MI_LEXIKON_SHOWDATDSC', "If set to 'yes', will display the Date of the recent Entries in the block on the Startpage.");
++$cf;
define('_MI_LEXIKON_SHOWCTR', "$cf. Show Counter in block of popular Entries?");
define('_MI_LEXIKON_SHOWCTRDSC', "If set to 'yes', will display the Counter of  popular Entries in the block on the Startpage.");
++$cf;
define('_MI_LEXIKON_CAPTCHA', "$cf. Use captcha for submissions?");
define('_MI_LEXIKON_CAPTCHADSC', 'Xoops Frameworks is required.');
++$cf;
define('_MI_LEXIKON_KEYWORDS_HIGH', "$cf. Use keywords highlighting search?");
define('_MI_LEXIKON_KEYWORDS_HIGHDSC', ' If you set this option to Yes, search keywords will be highlighted in the definitions');
++$cf;
define('_MI_LEXIKON_BOOKMARK_ME', "$cf. Display social bookmarks?");
define('_MI_LEXIKON_BOOKMARK_MEDSC', "The icons will be visible on the entry's page");
++$cf;
define('_MI_LEXIKON_METANUM', "$cf. Maximum count of meta keywords to auto-generate?");
define('_MI_LEXIKON_METANUMDSC', 'Set here the maximum number of meta keywords to generate.<br> If set to Zero, the Module will use the sites Keywords');
define('_MI_LEXIKON_METANUM_0', '0');
define('_MI_LEXIKON_METANUM_5', '5');
define('_MI_LEXIKON_METANUM_10', '10');
define('_MI_LEXIKON_METANUM_20', '20');
define('_MI_LEXIKON_METANUM_30', '30');
define('_MI_LEXIKON_METANUM_40', '40');
define('_MI_LEXIKON_METANUM_50', '50');
define('_MI_LEXIKON_METANUM_60', '60');
define('_MI_LEXIKON_METANUM_70', '70');
define('_MI_LEXIKON_METANUM_80', '80');
++$cf;
define('_MI_LEXIKON_USESHOTS', "$cf. Use category images?");
define('_MI_LEXIKON_USESHOTSDSC', "If set to 'yes', will display the category image.<br> <em>The Uploadfolder is: uploads/lexikon/categories/images</em>");
++$cf;
define('_MI_LEXIKON_LOGOWIDTH', "$cf. Width of the category images in the menue:");

define('_MI_LEXIKON_LOGOWIDTHDSC', 'Size of thumbnails in the menue (default:20px)');

++$cf;
define('_MI_LEXIKON_IMCATWD', "$cf. Width of the category images in category view:");
define('_MI_LEXIKON_IMCATWDDSC', 'Size of thumbnails in category view mode (default:50px)');
++$cf;
define('_MI_LEXIKON_RSS', "$cf. Enable RSS Syndication for guests?");
define('_MI_LEXIKON_RSSDSC', "If you set this option to 'Yes', newest entries will be available for guests. If `No` only users will have access to Syndication.");
++$cf;

define('_MI_LEXIKON_SYNDICATION', "$cf. Enable Webmaster Content Syndication?");
define('_MI_LEXIKON_SYNDICATIONDSC', "If you set this option to 'Yes', users will have access to content syndication.");

// new configs in version 1.52
$cf++;
define('_MI_LEXIKON_IMGUPLOADWD', "$cf. Max height/width for image upload");
define('_MI_LEXIKON_IMGUPLOADWD_DESC', 'Define the maximum height/width in pixel for uploading an image');
$cf++;
define('_MI_LEXIKON_IMGUPLOADSIZE', "$cf. Max size for image upload");
define('_MI_LEXIKON_IMGUPLOADSIZE_DESC', 'Define the maximum size in bytes (10485760 = 1 MB) for uploading an image');
// end new configs in 1.52


// bookmarks
define('_MI_LEXIKON_ADDTHIS1', 'Use Addthis Popup window');
define('_MI_LEXIKON_ADDTHIS2', 'Use Addthis dropdown select box');
// linkterms
define('_MI_LEXIKON_POPUP', 'Popup window');
define('_MI_LEXIKON_TOOLTIP', 'Tooltip');
define('_MI_LEXIKON_BUBBLETIPS', 'Bubble Tooltips');
define('_MI_LEXIKON_SHADOWTIPS', 'Shadow Tooltips');

// Names of admin menu items

define('_MI_LEXIKON_ADMENU0', 'Main');
define('_MI_LEXIKON_ADMENU1', 'Index');
define('_MI_LEXIKON_ADMENU2', 'Categories');
define('_MI_LEXIKON_ADMENU3', 'Entries');
define('_MI_LEXIKON_ADMENU4', 'Blocks/Groups');
define('_MI_LEXIKON_ADMENU5', 'Go to module');

//mondarse

define('_MI_LEXIKON_ADMENU6', 'Import');
define('_MI_LEXIKON_ADMENU7', 'Requests');
define('_MI_LEXIKON_ADMENU8', 'Submissions');
define('_MI_LEXIKON_ADMENU9', 'Permissions');
define('_MI_LEXIKON_ADMENU10', 'About');
define('_MI_LEXIKON_ADMENU11', 'Comments');
define('_MI_LEXIKON_ADMENU12', 'Statistics');

// SubMenues xoops 2.2.x
define('_MI_LEXIKON_CONFIGCAT_EXTENDED', '&raquo; Extended Configuration');
define('_MI_LEXIKON_CONFIGCAT_EXTENDEDDSC', 'special options.');

//Names of Blocks and Block information

define('_MI_LEXIKON_ENTRIESNEW', 'Newest Terms');
define('_MI_LEXIKON_ENTRIESTOP', 'Most Read Terms');
define('_MI_LEXIKON_RANDOMTERM', 'Random term');
define('_MI_LEXIKON_TERMINITIAL', 'Lexikon Index');
define('_MI_LEXIKON_CATS', 'Lexikon Categories');
define('_MI_LEXIKON_SPOT', 'Lexikon Spotlight');
define('_MI_LEXIKON_BNAME8', 'Lexikon Authors');
define('_MI_LEXIKON_BNAME9', 'Scrolling Definitions');

// Notification event descriptions and mail templates
define('_MI_LEXIKON_NOTIFY', 'Global');
define('_MI_LEXIKON_NOTIFYDSC', 'Global Notification options');
define('_MI_LEXIKON_NOTIFY_CAT', 'Category');
define('_MI_LEXIKON_NOTIFY_CATDSC', 'Notification options that apply to the current category');
define('_MI_LEXIKON_NOTIFY_TERM', 'Definition');
define('_MI_LEXIKON_NOTIFY_TERMDSC', 'Notification options that apply to the current definition');

define('_MI_LEXIKON_NEWPOST_NOTIFY', 'New Definition');
define('_MI_LEXIKON_NEWPOST_NOTIFYCAP', 'Notify me of new entries in the glossary.');
define('_MI_LEXIKON_NEWPOST_NOTIFYDSC', 'Receive notification when a new Definition is posted');
define('_MI_LEXIKON_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New Definition in glossary');

define('_MI_LEXIKON_NEWCAT_NOTIFY', 'New Category');
define('_MI_LEXIKON_NEWCAT_NOTIFYCAP', 'Notify me of new categories in the glossary.');
define('_MI_LEXIKON_NEWCAT_NOTIFYDSC', 'Receive notification when a new category is created');
define('_MI_LEXIKON_NEWCAT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New category in glossary');

define('_MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFY', 'New Definition Submitted');
define('_MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFYCAP', 'Notify me when any new Definition is submitted (awaiting approval).');
define('_MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFYDSC', 'Receive notification when any new Definition is submitted (awaiting approval).');
define('_MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New Definition submitted');

define('_MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFY', 'Definition request');
define('_MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFYCAP', 'Notify me when a Definition is requested (awaiting suggest).');
define('_MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFYDSC', 'Receive notification when a new Definition is request (awaiting approval).');
define('_MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Definition request');

define('_MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFY', 'New Definition Submitted');
define('_MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFYCAP', 'Notify me when any new Definition is submitted (awaiting approval) to the current category.');
define('_MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFYDSC', 'Receive notification when a new Definition is submitted (awaiting approval) to the current category.');
define('_MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New Definition submitted in category');

define('_MI_LEXIKON_CATEGORY_NEWTERM_NOTIFY', 'New Definition');
define('_MI_LEXIKON_CATEGORY_NEWTERM_NOTIFYCAP', 'Notify me when a new Definition is posted to the current category.');
define('_MI_LEXIKON_CATEGORY_NEWTERM_NOTIFYDSC', 'Receive notification when a new Definition is posted to the current category.');
define('_MI_LEXIKON_CATEGORY_NEWTERM_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New Definition in category');

define('_MI_LEXIKON_TERM_APPROVE_NOTIFY', 'Term Approved');
define('_MI_LEXIKON_TERM_APPROVE_NOTIFYCAP', 'Notify me when this term is approved.');
define('_MI_LEXIKON_TERM_APPROVE_NOTIFYDSC', 'Receive notification when this term is approved.');
define('_MI_LEXIKON_TERM_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Term approved');

//
define('_MI_LEXIKON_IMPORT', 'Import');

//1.52
//Help
define('_MI_LEXIKON_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_LEXIKON_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_LEXIKON_BACK_2_ADMIN', 'Back to Administration of ');
define('_MI_LEXIKON_HELP_OVERVIEW', 'Overview');
define('_MI_LEXIKON_NAME', 'Lexikon');
