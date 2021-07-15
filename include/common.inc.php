<?php

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

//=========================================================
// Constants
// these Options will be moved to xoops_version.php
//=========================================================

define('CONFIG_EXTENDED_AUTHORLIST', false); // display details in the modules' authorlist or just name and submitted terms
define('CONFIG_CATEGORY_LAYOUT_PLAIN', true); // standard layout or a structured category layout as in mylinks?
//define("CONFIG_HIGHLIGHTER",              true ) ;  // Use keywords highlighting ? keywords searched will be highlighted in the definition
//define("CONFIG_HIGHLIGHT_COLOUR",        '#FFFF80') ;// highlighting color ?
define('CONFIG_SEARCH_COMMENTS', false); // shall the module display its comments in the userprofile and search its own comments (global search) ?
define('META_KEYWORDS_ORDER', 1);     // Default order of meta keywords 1,2 or 3:
// 1 = Create keywords in the same order as in the text
// 2 = Keywords order according to the reverse keywords frequency (less frequent words appear first in the list)
// 3 = Same as '2', but most frequent words appear first in the list
//define("CONFIG_WYSIWYG_GUESTS",           false ) ; // Allow users/guests the use of wysiwyg editors on submissions ?
//define("CONFIG_TAGS_SUBMIT",              false ) ; // Allow users the enter tags for submitted terms ?
