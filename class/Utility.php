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
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team, hsalazar
 */

use Xmf\Request;
use Xmf\Module\Helper;

/**
 * Class LexikonUtility
 */
class LexikonUtility
{

    /**
     * Function responsible for checking if a directory exists, we can also write in and create an index.html file
     *
     * @param string $folder The full path of the directory to check
     *
     * @return void
     */
    public static function createFolder($folder)
    {
        try {
            if (!file_exists($folder)) {
                if (!mkdir($folder) && !is_dir($folder)) {
                    throw new \RuntimeException(sprintf('Unable to create the %s directory', $folder));
                } else {
                    file_put_contents($folder . '/index.html', '<script>history.go(-1);</script>');
                }
            }
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n", '<br>';
        }
    }

    /**
     * @param $file
     * @param $folder
     * @return bool
     */
    public static function copyFile($file, $folder)
    {
        return copy($file, $folder);
        //        try {
        //            if (!is_dir($folder)) {
        //                throw new \RuntimeException(sprintf('Unable to copy file as: %s ', $folder));
        //            } else {
        //                return copy($file, $folder);
        //            }
        //        } catch (Exception $e) {
        //            echo 'Caught exception: ', $e->getMessage(), "\n", "<br>";
        //        }
        //        return false;
    }

    /**
     * @param $src
     * @param $dst
     */
    public static function recurseCopy($src, $dst)
    {
        $dir = opendir($src);
        //    @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (('.' !== $file) && ('..' !== $file)) {
                if (is_dir($src . '/' . $file)) {
                    self::recurseCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    /**
     *
     * Verifies XOOPS version meets minimum requirements for this module
     * @static
     * @param XoopsModule $module
     *
     * @return bool true if meets requirements, false if not
     */
    public static function checkXoopsVer(XoopsModule $module)
    {
        xoops_loadLanguage('admin', $module->dirname());
        //check for minimum XOOPS version
        $currentVer  = substr(XOOPS_VERSION, 6); // get the numeric part of string
        $currArray   = explode('.', $currentVer);
        $requiredVer = '' . $module->getInfo('min_xoops'); //making sure it's a string
        $reqArray    = explode('.', $requiredVer);
        $success     = true;
        foreach ($reqArray as $k => $v) {
            if (isset($currArray[$k])) {
                if ($currArray[$k] > $v) {
                    break;
                } elseif ($currArray[$k] == $v) {
                    continue;
                } else {
                    $success = false;
                    break;
                }
            } else {
                if ((int)$v > 0) { // handles things like x.x.x.0_RC2
                    $success = false;
                    break;
                }
            }
        }

        if (!$success) {
            $module->setErrors(sprintf(_AM_ADSLIGHT_ERROR_BAD_XOOPS, $requiredVer, $currentVer));
        }

        return $success;
    }

    /**
     *
     * Verifies PHP version meets minimum requirements for this module
     * @static
     * @param XoopsModule $module
     *
     * @return bool true if meets requirements, false if not
     */
    public static function checkPhpVer(XoopsModule $module)
    {
        xoops_loadLanguage('admin', $module->dirname());
        // check for minimum PHP version
        $success = true;
        $verNum  = PHP_VERSION;
        $reqVer  = $module->getInfo('min_php');
        if (false !== $reqVer && '' !== $reqVer) {
            if (version_compare($verNum, $reqVer, '<')) {
                $module->setErrors(sprintf(_AM_ADSLIGHT_ERROR_BAD_PHP, $reqVer, $verNum));
                $success = false;
            }
        }

        return $success;
    }

    /**
     * LexikonUtility::getLinkedUnameFromId()
     *
     * @param  integer $userid Userid of author etc
     * @param  integer $name   :  0 Use Usenamer 1 Use realname
     * @return string
     */
    public static function getLinkedUnameFromId($userid = 0, $name = 0)
    {
        if (!is_numeric($userid)) {
            return $userid;
        }

        $userid = (int)$userid;
        if ($userid > 0) {
            $memberHandler = xoops_getHandler('member');
            $user          = $memberHandler->getUser($userid);

            if (is_object($user)) {
                $ts        = MyTextSanitizer::getInstance();
                $username  = $user->getVar('uname');
                $usernameu = $user->getVar('name');

                if ($name && !empty($usernameu)) {
                    $username = $user->getVar('name');
                }
                if (!empty($usernameu)) {
                    $linkeduser = "$usernameu [<a href='"
                                          . XOOPS_URL
                                          . '/userinfo.php?uid='
                                          . $userid
                                          . "'>"
                                          . $ts->htmlSpecialChars($username)
                                          . '</a>]';
                } else {
                    $linkeduser = "<a href='"
                              . XOOPS_URL
                              . '/userinfo.php?uid='
                              . $userid
                              . "'>"
                              . ucfirst($ts->htmlSpecialChars($username))
                              . '</a>';
                }

                return $linkeduser;
            }
        }

        return $GLOBALS['xoopsConfig']['anonymous'];
    }

    /**
     * @param $user
     */
    public static function getUserForm($user)
    {
        global $xoopsDB, $xoopsConfig;

        echo "<select name='author'>";
        echo "<option value='-1'>------</option>";
        $result = $xoopsDB->query('SELECT uid, uname FROM ' . $xoopsDB->prefix('users') . ' ORDER BY uname');

        while (list($uid, $uname) = $xoopsDB->fetchRow($result)) {
            if ($uid == $user) {
                $opt_selected = 'selected';
            } else {
                $opt_selected = '';
            }
            echo "<option value='" . $uid . "' $opt_selected>" . $uname . '</option>';
        }
        echo '</select></div>';
    }

    public static function calculateTotals()
    {
        global $xoopsUser, $xoopsDB, $xoopsModule;
        $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $gpermHandler = xoops_getHandler('groupperm');

        $result01 = $xoopsDB->query('SELECT categoryID, total FROM ' . $xoopsDB->prefix('lxcategories') . ' ');
        list($totalcategories) = $xoopsDB->getRowsNum($result01);
        while (list($categoryID, $total) = $xoopsDB->fetchRow($result01)) {
            if ($gpermHandler->checkRight('lexikon_view', $categoryID, $groups, $xoopsModule->getVar('mid'))) {
                $newcount = self::countByCategory($categoryID);
                $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('lxcategories') . " SET total = '$newcount' WHERE categoryID = '$categoryID'");
            }
        }
    }

    /**
     * @param $c
     * @return int
     */
    public static function countByCategory($c)
    {
        global $xoopsUser, $xoopsDB, $xoopsModule;
        $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $gpermHandler = xoops_getHandler('groupperm');
        $count        = 0;
        $sql          = $xoopsDB->query('SELECT entryID FROM ' . $xoopsDB->prefix('lxentries') . " WHERE offline = '0' AND categoryID = '$c'");
        while ($myrow = $xoopsDB->fetchArray($sql)) {
            ++$count;
        }

        return $count;
    }

    /**
     * @return int
     */
    public static function countCats()
    {
        global $xoopsUser, $xoopsModule;
        $gpermHandler = xoops_getHandler('groupperm');
        $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $totalcats    = $gpermHandler->getItemIds('lexikon_view', $groups, $xoopsModule->getVar('mid'));

        return count($totalcats);
    }

    /**
     * @return mixed
     */
    public static function countWords()
    {
        global $xoopsUser, $xoopsDB;
        $gpermHandler = xoops_getHandler('groupperm');
        $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname('lexikon');
        $module_id     = $module->getVar('mid');
        $allowed_cats  = $gpermHandler->getItemIds('lexikon_view', $groups, $module_id);
        $catids        = implode(',', $allowed_cats);
        $catperms      = " AND categoryID IN ($catids) ";

        $pubwords       = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('lxentries') . " WHERE submit = '0' AND offline ='0' AND request = '0' " . $catperms . ' ');
        $publishedwords = $xoopsDB->getRowsNum($pubwords);

        return $publishedwords;
    }

    // To display the list of categories
    /**
     * @return array
     */
    public static function getCategoryArray()
    {
        global $xoopsDB, $xoopsModuleConfig, $xoopsUser, $xoopsModule;
        $myts         = MyTextSanitizer::getInstance();
        $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $gpermHandler = xoops_getHandler('groupperm');
        $block0       = [];
        $count        = 1;
        $resultcat    = $xoopsDB->query('SELECT categoryID, name, total, logourl FROM '
                                    . $xoopsDB->prefix('lxcategories')
                                    . ' ORDER BY weight ASC');
        while (list($catID, $name, $total, $logourl) = $xoopsDB->fetchRow($resultcat)) {
            if ($gpermHandler->checkRight('lexikon_view', $catID, $groups, $xoopsModule->getVar('mid'))) {
                $catlinks = [];
                ++$count;
                if ($logourl && 'http://' !== $logourl) {
                    $logourl = $myts->htmlSpecialChars($logourl);
                } else {
                    $logourl = '';
                }
                $xoopsModule          = XoopsModule::getByDirname('lexikon');
                $catlinks['id']       = (int)$catID;
                $catlinks['total']    = (int)$total;
                $catlinks['linktext'] = $myts->htmlSpecialChars($name);
                $catlinks['image']    = $logourl;
                $catlinks['count']    = $count;

                $block0['categories'][] = $catlinks;
            }
        }

        return $block0;
    }

    /**
     * @return array
     */
    //function uchr($a) {
//    if (is_scalar($a)) $a= func_get_args();
//    $str= '';
//    foreach ($a as $code) $str.= html_entity_decode('&#'.$code.';',ENT_NOQUOTES,'UTF-8');
//    return $str;
    //}

    public static function getAlphaArray()
    {
        global $xoopsUser, $xoopsDB, $xoopsModule;
        $gpermHandler = xoops_getHandler('groupperm');
        $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname('lexikon');
        $module_id     = $module->getVar('mid');
        $allowed_cats  = $gpermHandler->getItemIds('lexikon_view', $groups, $module_id);
        $catids        = implode(',', $allowed_cats);
        $catperms      = " AND categoryID IN ($catids) ";
        $alpha         = [];
        /**
         * @param $a
         * @return null|string|string[]
         */
        function unichr($a)
        {
            return mb_convert_encoding(pack('N', $a), mb_internal_encoding(), 'UCS-4BE');
        }
        for ($a = 48; $a < (48 + 10); ++$a) {
            $letterlinks             = [];
            $initial                 = unichr($a);
            $sql                     = $xoopsDB->query('SELECT entryID FROM '
                                                       . $xoopsDB->prefix('lxentries')
                                                       . " WHERE init = '$initial' AND submit = '0' AND offline ='0' AND request = '0' "
                                                       . $catperms
                                                       . '');
            $howmany                 = $xoopsDB->getRowsNum($sql);
            $letterlinks['total']    = $howmany;
            $letterlinks['id']       = unichr($a);
            $letterlinks['linktext'] = unichr($a);

            $alpha['initial'][] = $letterlinks;
        }
        for ($a = 65; $a < (65 + 26); ++$a) {
            $letterlinks             = [];
            $initial                 = unichr($a);
            $sql                     = $xoopsDB->query('SELECT entryID FROM '
                                                       . $xoopsDB->prefix('lxentries')
                                                       . " WHERE init = '$initial' AND submit = '0' AND offline ='0' AND request = '0' "
                                                       . $catperms
                                                       . '');
            $howmany                 = $xoopsDB->getRowsNum($sql);
            $letterlinks['total']    = $howmany;
            $letterlinks['id']       = unichr($a);
            $letterlinks['linktext'] = unichr($a);

            $alpha['initial'][] = $letterlinks;
        }
        /*for ($a = 1040; $a < (1040 + 32); ++$a) {
            $letterlinks             = [];
            $initial                 = unichr($a);
            $sql                     = $xoopsDB->query('SELECT entryID FROM '
                                                           . $xoopsDB->prefix('lxentries')
                                                           . " WHERE init = '$initial' AND submit = '0' AND offline ='0' AND request = '0' "
                                                           . $catperms
                                                           . '');
            $howmany                 = $xoopsDB->getRowsNum($sql);
            $letterlinks['total']    = $howmany;
            $letterlinks['id']       = unichr($a);
            $letterlinks['linktext'] = unichr($a);
            $alpha['initial'][] = $letterlinks;
        }*/
    
        return $alpha;
    }

    /**
     * chr() with unicode support
     * I found this on this site http://en.php.net/chr
     * don't take credit for this.
     * @param $initials
     * @return string
     */
    public static function getUchr($initials)
    {
        if (is_scalar($initials)) {
            $initials = func_get_args();
        }
        $str = '';
        foreach ($initials as $init) {
            $str .= html_entity_decode('&#' . $init . ';', ENT_NOQUOTES, 'UTF-8');
        }

        return $str;
    }

    /* sample */
    /*
        echo LexikonUtility::getUchr(23383); echo '<br>';
        echo LexikonUtility::getUchr(23383,215,23383); echo '<br>';
        echo LexikonUtility::getUchr(array(23383,215,23383,215,23383)); echo '<br>';
    */

    // Functional links
    /**
     * @param $variable
     * @return string
     */
    public static function getServiceLinks($variable)
    {
        global $xoopsUser, $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsConfig, $entrytype;

        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $moduleInfo    = $moduleHandler->get($xoopsModule->getVar('mid'));
        $pathIcon16    = \Xmf\Module\Admin::iconUrl('', 16);

        $srvlinks = '';
        if ($xoopsUser) {
            if ($xoopsUser->isAdmin()) {
                $srvlinks .= '<a TITLE="'
                             . _EDIT
                             . '" href="/modules/lexikon/admin/entry.php?op=mod&entryID='
                             . $variable['id']
                             . '" target="_blank"><img src="'
                             . $pathIcon16 . '/edit.png"   alt="'
                             . _MD_LEXIKON_EDITTERM
                             . '" style="width:16px; height:16px;"></a>&nbsp;<a TITLE="'
                             . _DELETE
                             . '" href="admin/entry.php?op=del&entryID='
                             . $variable['id']
                             . '" target="_self"><img src="'
                             . $pathIcon16 . '/delete.png" alt="'
                             . _MD_LEXIKON_DELTERM
                             . '" style="width:16px; height:16px;"></a>&nbsp;';
            }
        }
        if ('1' != $entrytype) {
            $srvlinks .= '<a TITLE="'
                         . _MD_LEXIKON_PRINTTERM
                         . '" href="print.php?entryID='
                         . $variable['id']
                         . '" target="_blank"><img src="'
                         . $pathIcon16 . '/printer.png"  alt="'
                         . _MD_LEXIKON_PRINTTERM
                         . '" style="width:16px; height:16px;"></a>&nbsp;<a TITLE="'
                         . _MD_LEXIKON_SENDTOFRIEND
                         . '" href="mailto:?subject='
                         . sprintf(_MD_LEXIKON_INTENTRY, $xoopsConfig['sitename'])
                         . '&amp;body='
                         . sprintf(_MD_LEXIKON_INTENTRYFOUND, $xoopsConfig['sitename'])
                         . ': '
                         . XOOPS_URL
                         . '/modules/'
                         . $xoopsModule->dirname()
                         . '/entry.php?entryID='
                         . $variable['id']
                         . ' " target="_blank"><img src="'
                         . $pathIcon16 . '/mail_replay.png" alt="'
                         . _MD_LEXIKON_SENDTOFRIEND
                         . '" style="width:16px; height:16px;"></a>&nbsp;';
            if ((0 != $xoopsModuleConfig['com_rule'])
                && (!empty($xoopsModuleConfig['com_anonpost'])
                    || is_object($xoopsUser))
            ) {
                $srvlinks .= '<a TITLE="'
                             . _COMMENTS
                             . '?" href="comment_new.php?com_itemid='
                             . $variable['id']
                             . '" target="_parent"><img src="assets/images/comments.gif" alt="'
                             . _COMMENTS
                             . '?" style="width:16px; height:16px;"></a>&nbsp;';
            }
        }

        return $srvlinks;
    }

    // entry footer
    /**
     * @param $variable
     * @return string
     */
    public static function getServiceLinksNew($variable)
    {
        global $xoopsUser, $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsConfig, $myts;
        $srvlinks2 = '<a TITLE="'
                     . _MD_LEXIKON_PRINTTERM
                     . '" href="print.php?entryID='
                     . $variable['id']
                     . '" target="_blank"><img src="assets/images/print.gif" alt="'
                     . _MD_LEXIKON_PRINTTERM
                     . '" style="vertical-align: middle; width:16px; height:16px; margin: 2px 4px;"> '
                     . _MD_LEXIKON_PRINTTERM2
                     . '</a>&nbsp; <a TITLE="'
                     . _MD_LEXIKON_SENDTOFRIEND
                     . '" href="mailto:?subject='
                     . sprintf(_MD_LEXIKON_INTENTRY, $xoopsConfig['sitename'])
                     . '&amp;body='
                     . sprintf(_MD_LEXIKON_INTENTRYFOUND, $xoopsConfig['sitename'])
                     . ': '
                     . $variable['term']
                     . ' '
                     . XOOPS_URL
                     . '/modules/'
                     . $xoopsModule->dirname()
                     . '/entry.php?entryID='
                     . $variable['id']
                     . ' " target="_blank"><img src="assets/images/friend.gif" alt="'
                     . _MD_LEXIKON_SENDTOFRIEND
                     . '" style="vertical-align: middle; width:16px; height:16px; margin: 2px 4px;"> '
                     . _MD_LEXIKON_SENDTOFRIEND2
                     . '</a>&nbsp;';

        return $srvlinks2;
    }

    /**
     * @return string
     */
    public static function showSearchForm()
    {
        global $xoopsUser, $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsConfig;
        $gpermHandler = xoops_getHandler('groupperm');
        $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;

        $searchform = '<table style="width:100%;">';
        $searchform .= '<form name="op" id="op" action="search.php" method="post">';
        $searchform .= '<tr><td style="text-align: right; line-height: 200%; width:150px;">';
        $searchform .= _MD_LEXIKON_LOOKON . '</td><td style="width:10px;">&nbsp;</td><td style="text-align: left;">';
        $searchform .= '<select name="type"><option value="1">' . _MD_LEXIKON_TERMS . '</option><option value="2">' . _MD_LEXIKON_DEFINS . '</option>';
        $searchform .= '<option SELECTED value="3">' . _MD_LEXIKON_TERMSDEFS . '</option></select></td></tr>';

        if (1 == $xoopsModuleConfig['multicats']) {
            $searchform .= '<tr><td style="text-align: right; line-height: 200%;">' . _MD_LEXIKON_CATEGORY . '</td>';
            $searchform .= '<td>&nbsp;</td><td style="text-align: left;">';
            $resultcat  = $xoopsDB->query('SELECT categoryID, name FROM ' . $xoopsDB->prefix('lxcategories') . ' ORDER BY categoryID');
            $searchform .= '<select name="categoryID">';
            $searchform .= '<option value="0">' . _MD_LEXIKON_ALLOFTHEM . '</option>';

            while (list($categoryID, $name) = $xoopsDB->fetchRow($resultcat)) {
                if ($gpermHandler->checkRight('lexikon_view', (int)$categoryID, $groups, $xoopsModule->getVar('mid'))) {
                    $searchform .= "<option value=\"$categoryID\">$categoryID : $name</option>";
                }
            }
            $searchform .= '</select></td></tr>';
        }

        $searchform .= '<tr><td style="text-align: right; line-height: 200%;">';
        $searchform .= _MD_LEXIKON_TERM . '</td><td>&nbsp;</td><td style="text-align: left;">';
        $searchform .= '<input type="text" name="term" class="searchBox" ></td></tr><tr>';
        $searchform .= '<td>&nbsp;</td><td>&nbsp;</td><td><input type="submit" class="btnDefault" value="' . _MD_LEXIKON_SEARCH . '" >';
        $searchform .= '</td></tr></form></table>';

        return $searchform;
    }

    /**
     * @param $needle
     * @param $haystack
     * @param $hlS
     * @param $hlE
     * @return string
     */
    public static function getHTMLHighlight($needle, $haystack, $hlS, $hlE)
    {
        $parts = explode('>', $haystack);
        foreach ($parts as $key => $part) {
            $pL = '';
            $pR = '';

            if (false === ($pos = strpos($part, '<'))) {
                $pL = $part;
            } elseif ($pos > 0) {
                $pL = substr($part, 0, $pos);
                $pR = substr($part, $pos, strlen($part));
            }
            if ('' != $pL) {
                $parts[$key] = preg_replace('|(' . quotemeta($needle) . ')|iU', $hlS . '\\1' . $hlE, $pL) . $pR;
            }
        }

        return implode('>', $parts);
    }

    /* *******************************************************************************
     * Most of the following functions are modified functions from Herve's News Module
     * other functions are from  AMS by Novasmart/Mithrandir
     * others from Red Mexico Soft Rmdp
     * others from Xhelp 0.78 thanks to ackbarr and eric_juden
     * *******************************************************************************
     */

    // Create the meta keywords based on content
    /**
     * @param $content
     */
    public static function extractKeywords($content)
    {
        global $xoopsTpl, $xoTheme, $xoopsModule, $xoopsModuleConfig;
        require_once XOOPS_ROOT_PATH . '/modules/lexikon/include/common.inc.php';
        $keywords_count = $xoopsModuleConfig['metakeywordsnum'];
        $tmp            = [];
        if (isset($_SESSION['xoops_keywords_limit'])) {    // Search the "Minimum keyword length"
            $limit = $_SESSION['xoops_keywords_limit'];
        } else {
            $configHandler                    = xoops_getHandler('config');
            $xoopsConfigSearch                = $configHandler->getConfigsByCat(XOOPS_CONF_SEARCH);
            $limit                            = $xoopsConfigSearch['keyword_min'];
            $_SESSION['xoops_keywords_limit'] = $limit;
        }
        $myts            = MyTextSanitizer::getInstance();
        $content         = str_replace('<br>', ' ', $content);
        $content         = $myts->undoHtmlSpecialChars($content);
        $content         = strip_tags($content);
        $content         = strtolower($content);
        $search_pattern  = [
            '&nbsp;',
            "\t",
            "\r\n",
            "\r",
            "\n",
            ',',
            '.',
            "'",
            ';',
            ':',
            ')',
            '(',
            '"',
            '?',
            '!',
            '{',
            '}',
            '[',
            ']',
            '<',
            '>',
            '/',
            '+',
            '-',
            '_',
            '\\',
            '*'
        ];
        $replace_pattern = [
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ];
        $content         = str_replace($search_pattern, $replace_pattern, $content);
        $keywords        = explode(' ', $content);
        switch (META_KEYWORDS_ORDER) {
            case 1:    // Returns keywords in the same order that they were created in the text
                $keywords = array_unique($keywords);
                break;

            case 2:    // the keywords order is made according to the reverse keywords frequency (so the less frequent words appear in first in the list)
                $keywords = array_count_values($keywords);
                asort($keywords);
                $keywords = array_keys($keywords);
                break;

            case 3:    // Same as previous, the only difference is that the most frequent words will appear in first in the list
                $keywords = array_count_values($keywords);
                arsort($keywords);
                $keywords = array_keys($keywords);
                break;
        }
        foreach ($keywords as $keyword) {
            if (strlen($keyword) >= $limit && !is_numeric($keyword)) {
                $tmp[] = $keyword;
            }
        }
        $tmp = array_slice($tmp, 0, $keywords_count);
        if (count($tmp) > 0) {
            if (isset($xoTheme) && is_object($xoTheme)) {
                $xoTheme->addMeta('meta', 'keywords', implode(',', $tmp));
            } else {    // Compatibility for old Xoops versions
                $xoopsTpl->assign('xoops_meta_keywords', implode(',', $tmp));
            }
        } else {
            if (!isset($configHandler) || !is_object($configHandler)) {
                $configHandler = xoops_getHandler('config');
            }
            $xoopsConfigMetaFooter = $configHandler->getConfigsByCat(XOOPS_CONF_METAFOOTER);
            if (isset($xoTheme) && is_object($xoTheme)) {
                $xoTheme->addMeta('meta', 'keywords', $xoopsConfigMetaFooter['meta_keywords']);
            } else {    // Compatibility for old Xoops versions
                $xoopsTpl->assign('xoops_meta_keywords', $xoopsConfigMetaFooter['meta_keywords']);
            }
        }
    }

    // Create meta description based on content
    /**
     * @param $content
     */
    public static function getMetaDescription($content)
    {
        global $xoopsTpl, $xoTheme;
        $myts    = MyTextSanitizer::getInstance();
        $content = $myts->undoHtmlSpecialChars($myts->displayTarea($content));
        if (isset($xoTheme) && is_object($xoTheme)) {
            $xoTheme->addMeta('meta', 'description', strip_tags($content));
        } else {  // Compatibility for old Xoops versions
            $xoopsTpl->assign('xoops_meta_description', strip_tags($content));
        }
    }

    // Create pagetitles
    /**
     * @param string $article
     * @param string $topic
     */
    public static function createPageTitle($article = '', $topic = '')
    {
        global $xoopsModule, $xoopsTpl;
        $myts    = MyTextSanitizer::getInstance();
        $content = '';
        if (!empty($article)) {
            $content .= strip_tags($myts->displayTarea($article));
        }
        if (!empty($topic)) {
            if ('' != xoops_trim($content)) {
                $content .= ' - ' . strip_tags($myts->displayTarea($topic));
            } else {
                $content .= strip_tags($myts->displayTarea($topic));
            }
        }
        if (is_object($xoopsModule) && '' != xoops_trim($xoopsModule->name())) {
            if ('' != xoops_trim($content)) {
                $content .= ' - ' . strip_tags($myts->displayTarea($xoopsModule->name()));
            } else {
                $content .= strip_tags($myts->displayTarea($xoopsModule->name()));
            }
        }
        if ('' != $content) {
            $xoopsTpl->assign('xoops_pagetitle', $content);
        }
    }

    // clear descriptions
    /**
     * @param $document
     * @return mixed
     */
    public static function convertHtml2text($document)
    {
        // PHP Manual:: function preg_replace $document should contain an HTML document.
        // This will remove HTML tags, javascript sections and white space. It will also
        // convert some common HTML entities to their text equivalent.

        $search = [
            "'<script[^>]*?>.*?</script>'si",  // Strip out javascript
            "'<[\/\!]*?[^<>]*?>'si",          // Strip out HTML tags
            "'([\r\n])[\s]+'",                // Strip out white space
            "'&(quot|#34);'i",                // Replace HTML entities
            "'&(amp|#38);'i",
            "'&(lt|#60);'i",
            "'&(gt|#62);'i",
            "'&(nbsp|#160);'i",
            "'&(iexcl|#161);'i",
            "'&(cent|#162);'i",
            "'&(pound|#163);'i",
            "'&(copy|#169);'i"
        ];

        $replace = [
            '',
            '',
            "\\1",
            '"',
            '&',
            '<',
            '>',
            ' ',
            chr(161),
            chr(162),
            chr(163),
            chr(169)
        ];

        $text = preg_replace($search, $replace, $document);

        $text = preg_replace_callback("&#(\d+)&", create_function('$matches', 'return chr($matches[1]);'), $text);

        return $text;
    }

    //Retrieve moduleoptions equivalent to $Xoopsmoduleconfig
    /**
     * @param         $option
     * @param  string $repmodule
     * @return bool
     */
    public static function getModuleOption($option, $repmodule = 'lexikon')
    {
        global $xoopsModuleConfig, $xoopsModule;
        static $tbloptions = [];
        if (is_array($tbloptions) && array_key_exists($option, $tbloptions)) {
            return $tbloptions[$option];
        }

        $retval = false;
        if (isset($xoopsModuleConfig)
            && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $repmodule
                && $xoopsModule->getVar('isactive'))
        ) {
            if (isset($xoopsModuleConfig[$option])) {
                $retval = $xoopsModuleConfig[$option];
            }
        } else {
            /** @var XoopsModuleHandler $moduleHandler */
            $moduleHandler = xoops_getHandler('module');
            $module        = $moduleHandler->getByDirname($repmodule);
            $configHandler = xoops_getHandler('config');
            if ($module) {
                $moduleConfig = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
                if (isset($moduleConfig[$option])) {
                    $retval = $moduleConfig[$option];
                }
            }
        }
        $tbloptions[$option] = $retval;

        return $retval;
    }

    /**
     * Is Xoops 2.3.x ?
     *
     * @return boolean need to say it ?
     */
    public static function isX23()
    {
        $x23 = false;
        $xv  = str_replace('XOOPS ', '', XOOPS_VERSION);
        if (substr($xv, 2, 1) >= '3') {
            $x23 = true;
        }

        return $x23;
    }

    /**
     * Retreive an editor according to the module's option "form_options"
     * following function is from News modified by trabis
     * @param                                                                                                                                 $caption
     * @param                                                                                                                                 $name
     * @param  string                                                                                                                         $value
     * @param  string                                                                                                                         $width
     * @param  string                                                                                                                         $height
     * @param  string                                                                                                                         $supplemental
     * @return bool|XoopsFormDhtmlTextArea|XoopsFormEditor|XoopsFormFckeditor|XoopsFormHtmlarea|XoopsFormTextArea|XoopsFormTinyeditorTextArea
     */
    public static function getWysiwygForm($caption, $name, $value = '', $width = '100%', $height = '400px', $supplemental = '')
    {
        $editor_option            = strtolower(self::getModuleOption('form_options'));
        $editor                   = false;
        $editor_configs           = [];
        $editor_configs['name']   = $name;
        $editor_configs['value']  = $value;
        $editor_configs['rows']   = 35;
        $editor_configs['cols']   = 60;
        $editor_configs['width']  = '100%';
        $editor_configs['height'] = '350px';
        $editor_configs['editor'] = $editor_option;

        if (self::isX23()) {
            $editor = new XoopsFormEditor($caption, $name, $editor_configs);

            return $editor;
        }

        // Only for Xoops 2.0.x
        switch ($editor_option) {
            case 'fckeditor':
                if (is_readable(XOOPS_ROOT_PATH . '/class/fckeditor/formfckeditor.php')) {
                    require_once XOOPS_ROOT_PATH . '/class/fckeditor/formfckeditor.php';
                    $editor = new XoopsFormFckeditor($caption, $name, $value);
                }
                break;

            case 'htmlarea':
                if (is_readable(XOOPS_ROOT_PATH . '/class/htmlarea/formhtmlarea.php')) {
                    require_once XOOPS_ROOT_PATH . '/class/htmlarea/formhtmlarea.php';
                    $editor = new XoopsFormHtmlarea($caption, $name, $value);
                }
                break;

            case 'dhtmltextarea':
            case 'dhtml':
                $editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 10, 50, $supplemental);
                break;

            case 'textarea':
                $editor = new XoopsFormTextArea($caption, $name, $value);
                break;

            case 'tinyeditor':
            case 'tinymce':
                if (is_readable(XOOPS_ROOT_PATH . '/class/xoopseditor/tinyeditor/formtinyeditortextarea.php')) {
                    require_once XOOPS_ROOT_PATH . '/class/xoopseditor/tinyeditor/formtinyeditortextarea.php';
                    $editor = new XoopsFormTinyeditorTextArea([
                                                                  'caption' => $caption,
                                                                  'name'    => $name,
                                                                  'value'   => $value,
                                                                  'width'   => '100%',
                                                                  'height'  => '400px'
                                                              ]);
                }
                break;

            case 'koivi':
                if (is_readable(XOOPS_ROOT_PATH . '/class/wysiwyg/formwysiwygtextarea.php')) {
                    require_once XOOPS_ROOT_PATH . '/class/wysiwyg/formwysiwygtextarea.php';
                    $editor = new XoopsFormWysiwygTextArea($caption, $name, $value, $width, $height, '');
                }
                break;
        }

        return $editor;
    }

    /**
     * linkterms: assign module header
     *
     * tooltips (c) dhtmlgoodies
     */
    public static function getModuleHeader()
    {
        global $xoopsTpl, $xoTheme, $xoopsModule, $xoopsModuleConfig, $lexikon_module_header;
        if (isset($xoTheme) && is_object($xoTheme)) {
            $xoTheme->addStylesheet('modules/lexikon/assets/css/style.css');
            if (3 == $xoopsModuleConfig['linkterms']) {
                $xoTheme->addStylesheet('modules/lexikon/assets/css/linkterms.css');
                $xoTheme->addScript('/modules/lexikon/assets/js/tooltipscript2.js', ['type' => 'text/javascript']);
            }
            if (4 == $xoopsModuleConfig['linkterms']) {
                $xoTheme->addScript('/modules/lexikon/assets/js/popup.js', ['type' => 'text/javascript']);
            }
            if (5 == $xoopsModuleConfig['linkterms']) {
                $xoTheme->addStylesheet('modules/lexikon/assets/css/linkterms.css');
                $xoTheme->addScript('/modules/lexikon/assets/js/balloontooltip.js', ['type' => 'text/javascript']);
            }
            if (6 == $xoopsModuleConfig['linkterms']) {
                $xoTheme->addStylesheet('modules/lexikon/assets/css/linkterms.css');
                $xoTheme->addScript('/modules/lexikon/assets/js/shadowtooltip.js', ['type' => 'text/javascript']);
            }
        } else {
            $lexikon_url = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname');
            if (3 == $xoopsModuleConfig['linkterms']) {
                $lexikon_module_header = '<link rel="stylesheet" type="text/css" href="assets/css/style.css" >
            <link rel="stylesheet" type="text/css" href="assets/css/linkterms.css" >
            <script src="' . $lexikon_url . '/assets/js/tooltipscript2.js" type="text/javascript"></script>';
            }
            if (4 == $xoopsModuleConfig['linkterms']) {
                $lexikon_module_header = '<link rel="stylesheet" type="text/css" href="assets/css/style.css" >
            <link rel="stylesheet" type="text/css" href="assets/css/linkterms.css" >
            <script src="' . $lexikon_url . '/assets/js/popup.js" type="text/javascript"></script>';
            }
            if (5 == $xoopsModuleConfig['linkterms']) {
                $lexikon_module_header = '<link rel="stylesheet" type="text/css" href="assets/css/style.css" >
            <link rel="stylesheet" type="text/css" href="assets/css/linkterms.css" >
            <script src="' . $lexikon_url . '/assets/js/balloontooltip.js" type="text/javascript"></script>';
            }
            if (6 == $xoopsModuleConfig['linkterms']) {
                $lexikon_module_header = '<link rel="stylesheet" type="text/css" href="assets/css/style.css" >
            <link rel="stylesheet" type="text/css" href="assets/css/linkterms.css" >
            <script src="' . $lexikon_url . '/assets/js/shadowtooltip.js" type="text/javascript"></script>';
            }
        }
    }

    /**
     * Validate userid
     * @param $uids
     * @return bool
     */
    public static function getUserData($uids)
    {
        global $xoopsDB, $xoopsUser, $xoopsUserIsAdmin;

        if ($uids <= 0) {
            return false;
        }
        if ($uids > 0) {
            $memberHandler = xoops_getHandler('member');
            $user          = $memberHandler->getUser($uids);
            if (!is_object($user)) {
                return false;
            }
        }
        $result = $xoopsDB->query('SELECT * FROM '
                              . $xoopsDB->prefix('users')
                              . " WHERE uid='$uids'");
        if ($xoopsDB->getRowsNum($result) <= 0) {
            return false;
        }
        $row = $xoopsDB->fetchArray($result);

        return $row;
    }

    // Get all terms published by an author
    /**
     * @param $uid
     */
    public static function getAuthorProfile($uid)
    {
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        global $authortermstotal, $xoopsTpl, $xoopsDB, $xoopsUser, $xoopsModuleConfig;
        $myts = MyTextSanitizer::getInstance();
        //permissions
        $gpermHandler = xoops_getHandler('groupperm');
        $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname('lexikon');
        $module_id     = $module->getVar('mid');
        $allowed_cats  = $gpermHandler->getItemIds('lexikon_view', $groups, $module_id);
        $catids        = implode(',', $allowed_cats);
        $catperms      = " AND categoryID IN ($catids) ";

        $start = isset($_GET['start']) ? (int)$_GET['start'] : 0;
        $limit = $xoopsModuleConfig['indexperpage'];

        $sql = $xoopsDB->query('SELECT *
                              FROM ' . $xoopsDB->prefix('lxentries') . "
                              WHERE uid='" . (int)$uid . "' AND  offline = '0' AND submit = '0' AND request = '0' " . $catperms . "
                              ORDER BY term
                              LIMIT $start,$limit");

        while ($row = $xoopsDB->fetchArray($sql)) {
            $xoopsTpl->append('entries', [
                'id'      => $row['entryID'],
                'name'    => $row['term'],
                'date'    => date($xoopsModuleConfig['dateformat'], $row['datesub']),
                'counter' => $row['counter']
            ]);
        }

        $navstring                = '';
        $navstring                .= 'uid=' . $uid . '&start';
        $pagenav                  = new XoopsPageNav($authortermstotal, $xoopsModuleConfig['indexperpage'], $start, $navstring);
        $authortermsarr['navbar'] = '<span style="text-align:right;">' . $pagenav->renderNav(6) . '</span>';
        $xoopsTpl->assign('authortermsarr', $authortermsarr);
    }

    // Returns the author's IDs for authorslist
    /**
     * @param  int $limit
     * @param  int $start
     * @return array
     */
    public static function getAuthors($limit = 0, $start = 0)
    {
        global $xoopsDB;

        $ret    = [];
        $sql    = 'SELECT DISTINCT(uid) AS uid FROM '
              . $xoopsDB->prefix('lxentries')
              . ' WHERE offline = 0 ';
        $sql    .= ' ORDER BY uid';
        $result = $xoopsDB->query($sql);
        while ($myrow = $xoopsDB->fetchArray($result)) {
            $ret[] = $myrow['uid'];
        }

        return $ret;
    }

    // link to userprofile
    /**
     * @param $userid
     * @return string
     */
    public static function getLinkedProfileFromId($userid)
    {
        global $uid, $xoopsModule;
        $userid = (int)$uid;
        if ($userid > 0) {
            $memberHandler = xoops_getHandler('member');
            $user          = $memberHandler->getUser($userid);
            if (is_object($user)) {
                $linkeduser = '<A TITLE="'
                              . _MD_LEXIKON_AUTHORPROFILETEXT
                              . '" HREF="'
                              . XOOPS_URL
                              . '/modules/'
                              . $xoopsModule->dirname()
                              . '/profile.php?uid='
                              . $uid
                              . '">'
                              . $user->getVar('uname')
                              . '</a>';
                //$linkeduser = XoopsUserUtility::getUnameFromId ( $uid );
                //$linkeduser .= '<div style=\'position:relative; right: 4px; top: 2px;\'><A TITLE="'._MD_LEXIKON_AUTHORPROFILETEXT.'" HREF="'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/profile.php?uid='.$uid.'">'._MD_LEXIKON_AUTHORPROFILETEXT.'</a></div>';
                return $linkeduser;
            }
        }

        return $GLOBALS['xoopsConfig']['anonymous'];
    }

    // functionset to assign terms with accentuated or umlaut initials to the adequate initial
    /**
     * @param $string
     * @return mixed|string
     */
    public static function removeAccents($string)
    {
        $chars['in']  = chr(128)
                        . chr(131)
                        . chr(138)
                        . chr(142)
                        . chr(154)
                        . chr(158)
                        . chr(159)
                        . chr(162)
                        . chr(165)
                        . chr(181)
                        . chr(192)
                        . chr(193)
                        . chr(194)
                        . chr(195)
                        . chr(196)
                        . chr(197)
                        . chr(199)
                        . chr(200)
                        . chr(201)
                        . chr(202)
                        . chr(203)
                        . chr(204)
                        . chr(205)
                        . chr(206)
                        . chr(207)
                        . chr(209)
                        . chr(210)
                        . chr(211)
                        . chr(212)
                        . chr(213)
                        . chr(214)
                        . chr(216)
                        . chr(217)
                        . chr(218)
                        . chr(219)
                        . chr(220)
                        . chr(221)
                        . chr(224)
                        . chr(225)
                        . chr(226)
                        . chr(227)
                        . chr(228)
                        . chr(229)
                        . chr(231)
                        . chr(232)
                        . chr(233)
                        . chr(234)
                        . chr(235)
                        . chr(236)
                        . chr(237)
                        . chr(238)
                        . chr(239)
                        . chr(241)
                        . chr(242)
                        . chr(243)
                        . chr(244)
                        . chr(245)
                        . chr(246)
                        . chr(248)
                        . chr(249)
                        . chr(250)
                        . chr(251)
                        . chr(252)
                        . chr(253)
                        . chr(255);
        $chars['out'] = 'EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy';
        if (self::isUtf8($string)) {
            $invalid_latin_chars = [
                chr(197) . chr(146)            => 'OE',
                chr(197) . chr(147)            => 'oe',
                chr(197) . chr(160)            => 'S',
                chr(197) . chr(189)            => 'Z',
                chr(197) . chr(161)            => 's',
                chr(197) . chr(190)            => 'z',
                chr(226) . chr(130) . chr(172) => 'E'
            ];
            $string              = utf8_decode(strtr($string, $invalid_latin_chars));
        }
        $string              = strtr($string, $chars['in'], $chars['out']);
        $double_chars['in']  = [
            chr(140),
            chr(156),
            chr(198),
            chr(208),
            chr(222),
            chr(223),
            chr(230),
            chr(240),
            chr(254)
        ];
        $double_chars['out'] = [
            'OE',
            'oe',
            'AE',
            'DH',
            'TH',
            'ss',
            'ae',
            'dh',
            'th'
            ];
        $string              = str_replace($double_chars['in'], $double_chars['out'], $string);

        return $string;
    }

    /**
     * @param $Str
     * @return bool
     */
    public static function isUtf8($Str)
    { # by bmorel at ssi dot fr
        for ($i = 0, $iMax = strlen($Str); $i < $iMax; ++$i) {
            if (ord($Str[$i]) < 0x80) {
                continue;
            } # 0bbbbbbb
            elseif (0xC0 == (ord($Str[$i]) & 0xE0)) {
                $n = 1;
            } # 110bbbbb
            elseif (0xE0 == (ord($Str[$i]) & 0xF0)) {
                $n = 2;
            } # 1110bbbb
            elseif (0xF0 == (ord($Str[$i]) & 0xF8)) {
                $n = 3;
            } # 11110bbb
            elseif (0xF8 == (ord($Str[$i]) & 0xFC)) {
                $n = 4;
            } # 111110bb
            elseif (0xFC == (ord($Str[$i]) & 0xFE)) {
                $n = 5;
            } # 1111110b
            else {
                return false;
            } # Does not match any model
            for ($j = 0; $j < $n; ++$j) { # n bytes matching 10bbbbbb follow ?
                if ((++$i == strlen($Str)) || (0x80 != (ord($Str[$i]) & 0xC0))) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param $field
     * @return mixed|string
     */
    public static function sanitizeFieldName($field)
    {
        $field = self::removeAccents($field);
        $field = strtolower($field);
        $field = preg_replace('/&.+?;/', '', $field); // kill entities
        $field = preg_replace('/[^a-z0-9 _-]/', '', $field);
        $field = preg_replace('/\s+/', ' ', $field);
        $field = str_replace(' ', '-', $field);
        $field = preg_replace('|-+|', '-', $field);
        $field = trim($field, '-');

        return $field;
    }

    // Verify that a term does not exist for submissions and requests (both user frontend and admin backend)
    /**
     * @param $term
     * @param $table
     * @return mixed
     */
    public static function isTermPresent($term, $table)
    {
        global $xoopsDB;
        $sql    = sprintf('SELECT COUNT(*) FROM %s WHERE term = %s', $table, $xoopsDB->quoteString(addslashes($term)));
        $result = $xoopsDB->query($sql);
        list($count) = $xoopsDB->fetchRow($result);

        return $count;
    }

    // Static method to get author data block authors - from AMS
    /**
     * @param  int    $limit
     * @param  string $sort
     * @param  string $name
     * @param  string $compute_method
     * @return array|bool
     */
    public static function getBlockAuthors($limit = 5, $sort = 'count', $name = 'uname', $compute_method = 'average')
    {
        $limit = (int)$limit;
        if ('uname' !== $name) {
            $name = 'name';
        } //making sure that there is not invalid information in field value
        $ret = [];
        $db  = XoopsDatabaseFactory::getDatabaseConnection();
        if ('count' === $sort) {
            $sql = 'SELECT u.' . $name . ' AS name, u.uid , count( n.entryID ) AS count
              FROM ' . $db->prefix('users') . ' u, ' . $db->prefix('lxentries') . ' n
              WHERE u.uid = n.uid
              AND n.datesub > 0 AND n.datesub <= ' . time() . ' AND n.offline = 0 AND n.submit = 0
              GROUP BY u.uid ORDER BY count DESC';
        } elseif ('read' === $sort) {
            if ('average' === $compute_method) {
                $compute = 'sum( n.counter ) / count( n.entryID )';
            } else {
                $compute = 'sum( n.counter )';
            }
            $sql = 'SELECT u.' . $name . " AS name, u.uid , $compute AS count
              FROM " . $db->prefix('users') . ' u, ' . $db->prefix('lxentries') . ' n
              WHERE u.uid = n.uid
              AND n.datesub > 0 AND n.datesub <= ' . time() . ' AND n.offline = 0 AND n.submit = 0
              GROUP BY u.uid ORDER BY count DESC';
        }
        if (!$result = $db->query($sql, $limit)) {
            return false;
        }

        while ($row = $db->fetchArray($result)) {
            if ('name' === $name && '' == $row['name']) {
                $row['name'] = XoopsUser::getUnameFromId($row['uid']);
            }
            $row['count'] = round($row['count'], 0);
            $ret[]        = $row;
        }

        return $ret;
    }

    /**
     * close all unclosed xhtml tags *Test*
     *
     * @param  string $html
     * @return string
     * @author Milian Wolff <mail -at- milianw.de>
     */
    public static function closeTags2($html)
    {
        // put all opened tags into an array
        preg_match_all('#<([a-z]+)( .*)?(?!/)>#iU', $html, $result);
        $openedtags = $result[1];

        // put all closed tags into an array
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        // all tags are closed
        if (count($closedtags) == $len_opened) {
            return $html;
        }

        $openedtags = array_reverse($openedtags);
        // close tags
        for ($i = 0; $i < $len_opened; ++$i) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</' . $openedtags[$i] . '>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }

        return $html;
    }

    /**
     * @author   Monte Ohrt <monte at ohrt dot com>, modified by Amos Robinson
     *           <amos dot robinson at gmail dot com>
     * @param $string
     * @return string
     */
    public static function closeTags($string)
    {
        // match opened tags
        if (preg_match_all('/<([a-z\:\-]+)[^\/]>/', $string, $start_tags)) {
            $start_tags = $start_tags[1];
            // match closed tags
            if (preg_match_all('/<\/([a-z]+)>/', $string, $end_tags)) {
                $complete_tags = [];
                $end_tags      = $end_tags[1];

                foreach ($start_tags as $key => $val) {
                    $posb = array_search($val, $end_tags);
                    if (is_int($posb)) {
                        unset($end_tags[$posb]);
                    } else {
                        $complete_tags[] = $val;
                    }
                }
            } else {
                $complete_tags = $start_tags;
            }

            $complete_tags = array_reverse($complete_tags);
            for ($i = 0, $iMax = count($complete_tags); $i < $iMax; ++$i) {
                $string .= '</' . $complete_tags[$i] . '>';
            }
        }

        return $string;
    }

    /**
     * Smarty plugin
     * @package    Smarty
     * @subpackage plugins
     */
    /**
     * Smarty truncate_tagsafe modifier plugin
     *
     * Type:     modifier<br>
     * Name:     truncate_tagsafe<br>
     * Purpose:  Truncate a string to a certain length if necessary,
     *           optionally splitting in the middle of a word, and
     *           appending the $etc string or inserting $etc into the middle.
     *           Makes sure no tags are left half-open or half-closed (e.g. "Banana in a <a...")
     * @author   Monte Ohrt <monte at ohrt dot com>, modified by Amos Robinson
     *           <amos dot robinson at gmail dot com>
     * used in Block entries_scrolling.php
     * @param               $string
     * @param  int          $length
     * @param  string       $etc
     * @param  bool         $break_words
     * @return mixed|string
     */
    public static function truncateTagSafe($string, $length = 80, $etc = '...', $break_words = false)
    {
        if (0 == $length) {
            return '';
        }
        if (strlen($string) > $length) {
            $length -= strlen($etc);
            if (!$break_words) {
                $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
                $string = preg_replace('/<[^>]*$/', '', $string);
                $string = self::closeTags($string);
            }

            return $string . $etc;
        } else {
            return $string;
        }
    }

    /**
     * @return array
     */
    public static function getSummary()
    {
        global $xoopsDB;

        $summary = [];

        $result01 = $xoopsDB->query('SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxcategories') . ' ');
        list($totalcategories) = $xoopsDB->fetchRow($result01);

        $result02 = $xoopsDB->query('SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxentries') . '
                                   WHERE submit = 0');
        list($totalpublished) = $xoopsDB->fetchRow($result02);

        $result03 = $xoopsDB->query('SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxentries') . "
                                   WHERE submit = '1' AND request = '0' ");
        list($totalsubmitted) = $xoopsDB->fetchRow($result03);

        $result04 = $xoopsDB->query('SELECT COUNT(*)
                                   FROM ' . $xoopsDB->prefix('lxentries') . "
                                   WHERE submit = '1' AND request = '1' ");
        list($totalrequested) = $xoopsDB->fetchRow($result04);

        // Recuperer les valeurs dans la base de donnees

        $summary['publishedEntries']    = $totalpublished ?: '0';
        $summary['availableCategories'] = $totalcategories ?: '0';
        $summary['submittedEntries']    = $totalsubmitted ?: '0';
        $summary['requestedEntries']    = $totalrequested ?: '0';

        //print_r($summary);
        return $summary;
    } // end function
}
