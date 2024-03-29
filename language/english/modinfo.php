<?php
/**
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */

// Module Info
// The name of this module
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
define('_MI_LEXIKON_MULTICATS', 'Do you want to have glossary categories?');
define('_MI_LEXIKON_MULTICATSDSC', "If set to 'Yes', will allow you to have glossary categories. If set to no, will have a single automatic category.");
define('_MI_LEXIKON_ALLOWSUBMIT', 'Can users submit entries?');
define('_MI_LEXIKON_ALLOWSUBMITDSC', "If set to 'Yes', users will have access to a submission form.");
define('_MI_LEXIKON_CATSINMENU', 'Should the categories be shown in the menu?');
define('_MI_LEXIKON_CATSINMENUDSC', "If set to 'Yes' if you want links to categories in the main menu.");
define('_MI_LEXIKON_ANONSUBMIT', 'Can guests submit entries?');
define('_MI_LEXIKON_ANONSUBMITDSC', "If set to 'Yes', guests will have access to a submission form.");
define('_MI_LEXIKON_DATEFORMAT', 'In what format should the date appear?');
define('_MI_LEXIKON_DATEFORMATDSC', "Use the final part of language/english/global.php to select a display style. Example: 'd-M-Y H:i' translates to '23-May-2011 22:35'");
define('_MI_LEXIKON_ALLOWREQ', 'Can guests request entries?');
define('_MI_LEXIKON_ALLOWREQDSC', "If set to 'Yes', guests will as well have access to a request form.");
define('_MI_LEXIKON_PERPAGE', 'Number of entries per page (Admin side)?');
define('_MI_LEXIKON_PERPAGEDSC', 'Number of entries that will be shown at once in the table that displays active entries in the admin side.');
define('_MI_LEXIKON_PERPAGEINDEX', 'Number of entries per page (User side)?');
define('_MI_LEXIKON_PERPAGEINDEXDSC', 'Number of entries that will be shown on each page in the user side of the module.');
define('_MI_LEXIKON_BLOCKSPERPAGE', 'Number of entries per Block?');
define('_MI_LEXIKON_BLOCKSPERPAGEDSC', 'How many entries do you want to show in the boxes in the main page ? (Default: 5)');
define('_MI_LEXIKON_AUTOAPPROVE', 'Approve entries automatically?');
define('_MI_LEXIKON_AUTOAPPROVEDSC', "If set to 'Yes', XOOPS will publish submitted entries without admin intervention.");
define('_MI_LEXIKON_ALLOWADMINHITS', 'Will the admin hits be included in the counter?');
define('_MI_LEXIKON_ALLOWADMINHITSDSC', "If set to 'Yes', will increase counter for each entry on admin visits.");
define('_MI_LEXIKON_MAILTOADMIN', 'Send mail to admin on each new submission?');
define('_MI_LEXIKON_MAILTOADMINDSC', "If set to 'Yes', the manager will receive an e-mail for every submitted entry.");
define('_MI_LEXIKON_MAILTOSENDER', 'Send mail to user on each new submission?');
define('_MI_LEXIKON_MAILTOSENDERDSC', "If set to 'Yes', the user will receive a confirmation e-mail for every modified, submitted or requested entry. If 'Notify on publish' is ticked, the user will as well receive a confirmation e-mail on publication of the entry.");
define('_MI_LEXIKON_RANDOMLENGTH', 'Length of string to show in random definitions?');
define('_MI_LEXIKON_RANDOMLENGTHDSC', 'How many characters do you want to show in the random term boxes, both in the main page and in the block? (Default: 150)');
define('_MI_LEXIKON_LINKTERMS', 'Show links to other glossary terms in the definitions?');
define('_MI_LEXIKON_LINKTERMSDSC', "If set to 'yes', will automatically link in your definitions those terms you already have in your glossaries.");
define('_MI_LEXIKON_FORM_OPTIONS', 'Form Option');
define('_MI_LEXIKON_FORM_OPTIONSDSC', 'What kind of editor would you like to use. <br>Please note that if you choose any other editor than the Xoops-DHTML-Editor, it must be installed under class/xoopseditor.');
define('_MI_LEXIKON_EDIGUEST', 'Form Options for submissions');
define('_MI_LEXIKON_EDIGUESTDSC', 'Shall Guests may use editors?');
define('_MI_LEXIKON_DISPPROL', 'Show Submitter on every entry?');
define('_MI_LEXIKON_DISPPROLDSC', "If set to 'yes', will display the author of the entry.");
define('_MI_LEXIKON_HEADER', 'Main Page Introductory Text:');
define('_MI_LEXIKON_HEADERDSC', 'You can use this section to display some descriptive or introductory text. HTML is allowed.');
define('_MI_LEXIKON_AUTHORPROFILE', 'Use author profile?');
define('_MI_LEXIKON_AUTHORPROFILEDSC', "If set to 'yes', the submitter will be linked to the authors glossary-profile. additionally a link to the authorlist will show up in the menu.");
define('_MI_LEXIKON_SHOWDAT', 'Show Date in block of recent Entries?');
define('_MI_LEXIKON_SHOWDATDSC', "If set to 'yes', will display the Date of the recent Entries in the block on the Startpage.");
define('_MI_LEXIKON_SHOWCTR', 'Show Counter in block of popular Entries?');
define('_MI_LEXIKON_SHOWCTRDSC', "If set to 'yes', will display the Counter of  popular Entries in the block on the Startpage.");
define('_MI_LEXIKON_CAPTCHA', 'Use captcha for submissions?');
define('_MI_LEXIKON_CAPTCHADSC', 'Xoops Frameworks is required.');
define('_MI_LEXIKON_KEYWORDS_HIGH', 'Use keywords highlighting search?');
define('_MI_LEXIKON_KEYWORDS_HIGHDSC', ' If you set this option to Yes, search keywords will be highlighted in the definitions');
define('_MI_LEXIKON_BOOKMARK_ME', 'Display social bookmarks?');
define('_MI_LEXIKON_BOOKMARK_MEDSC', "The icons will be visible on the entry's page");
define('_MI_LEXIKON_METANUM', 'Maximum count of meta keywords to auto-generate?');
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
define('_MI_LEXIKON_USESHOTS', 'Use category images?');
define('_MI_LEXIKON_USESHOTSDSC', "If set to 'yes', will display the category image.<br> <em>The Uploadfolder is: uploads/lexikon/categories/images</em>");
define('_MI_LEXIKON_LOGOWIDTH', 'Width of the category images in the menue:');
define('_MI_LEXIKON_LOGOWIDTHDSC', 'Size of thumbnails in the menue (default:20px)');
define('_MI_LEXIKON_IMCATWD', 'Width of the category images in category view:');
define('_MI_LEXIKON_IMCATWDDSC', 'Size of thumbnails in category view mode (default:50px)');
define('_MI_LEXIKON_RSS', 'Enable RSS Syndication for guests?');
define('_MI_LEXIKON_RSSDSC', "If you set this option to 'Yes', newest entries will be available for guests. If `No` only users will have access to Syndication.");
define('_MI_LEXIKON_SYNDICATION', 'Enable Webmaster Content Syndication?');
define('_MI_LEXIKON_SYNDICATIONDSC', "If you set this option to 'Yes', users will have access to content syndication.");
// new configs in version 1.52
define('_MI_LEXIKON_IMGUPLOADWD', 'Max height/width for image upload');
define('_MI_LEXIKON_IMGUPLOADWD_DESC', 'Define the maximum height/width in pixel for uploading an image');
define('_MI_LEXIKON_IMGUPLOADSIZE', 'Max size for image upload');
define('_MI_LEXIKON_IMGUPLOADSIZE_DESC', 'Define the maximum size in bytes (10485760 = 1 MB) for uploading an image');
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
define('_MI_LEXIKON_IMPORT', 'Import');
//1.52
define('_MI_LEXIKON_BLOCKADMIN', 'Blocks Admin');
define('_MI_LEXIKON_SHOWSUBMISSIONS', 'Submissions');
define('_MI_LEXIKON_HOME', 'Home');
define('_MI_LEXIKON_ABOUT', 'About');
// Admin
define('_MI_LEXIKON_DESC', 'This module is for doing following...');
//Blocks
define('_MI_LEXIKON_CATEGORY_BLOCK', 'Category block');
define('_MI_LEXIKON_ENTRIES_BLOCK', 'Entries block');
//Config
define('_MI_LEXIKON_EDITOR_ADMIN', 'Editor: Admin');
define('_MI_LEXIKON_EDITOR_ADMIN_DESC', 'Select the Editor to use by the Admin');
define('_MI_LEXIKON_EDITOR_USER', 'Editor: User');
define('_MI_LEXIKON_EDITOR_USER_DESC', 'Select the Editor to use by the User');
define('_MI_LEXIKON_KEYWORDS', 'Keywords');
define('_MI_LEXIKON_KEYWORDS_DESC', 'Insert here the keywords (separate by comma)');
define('_MI_LEXIKON_MAXSIZE', 'Max size');
define('_MI_LEXIKON_MAXSIZE_DESC', 'Set a number of max size uploads file in byte');
define('_MI_LEXIKON_MIMETYPES', 'Mime Types');
define('_MI_LEXIKON_MIMETYPES_DESC', 'Set the mime types selected');
define('_MI_LEXIKON_IDPAYPAL', 'Paypal ID');
define('_MI_LEXIKON_IDPAYPAL_DESC', 'Insert here your PayPal ID for donactions.');
define('_MI_LEXIKON_ADVERTISE', 'Advertisement Code');
define('_MI_LEXIKON_ADVERTISE_DESC', 'Insert here the advertisement code');
define('_MI_LEXIKON_BOOKMARKS', 'Social Bookmarks');
define('_MI_LEXIKON_BOOKMARKS_DESC', 'Show Social Bookmarks in the form');
define('_MI_LEXIKON_FBCOMMENTS', 'Facebook comments');
define('_MI_LEXIKON_FBCOMMENTS_DESC', 'Allow Facebook comments in the form');
// Help
define('_MI_LEXIKON_OVERVIEW', 'Overview');
//help multi-page
define('_MI_LEXIKON_DISCLAIMER', 'Disclaimer');
define('_MI_LEXIKON_LICENSE', 'License');
define('_MI_LEXIKON_SUPPORT', 'Support');
// Permissions Groups
define('_MI_LEXIKON_GROUPS', 'Groups access');
define('_MI_LEXIKON_GROUPS_DESC', 'Select general access permission for groups.');
define('_MI_LEXIKON_ADMINGROUPS', 'Admin Group Permissions');
define('_MI_LEXIKON_ADMINGROUPS_DESC', 'Which groups have access to tools and permissions page');
