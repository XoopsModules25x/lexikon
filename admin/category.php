<?php
/**
 *
 * Module: Lexikon - glossary module
 * Author: hsalazar
 * Licence: GNU
 */

use Xmf\Request;
use XoopsModules\Lexikon;

// -- General Stuff -- //
require_once __DIR__ . '/admin_header.php';
$myts = \MyTextSanitizer::getInstance();
xoops_cp_header();
xoops_load('XoopsUserUtility');
$adminObject  = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));
$adminObject->addItemButton(_AM_LEXIKON_CREATECAT, 'category.php?op=addcat', 'add');
$adminObject->displayButton('left');
$op = '';

/* -- Available operations -- */

function categoryDefault()
{
    $op = 'default';
    require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
    require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

    $startentry = isset($_GET['startentry']) ? (int)$_GET['startentry'] : 0;
    $startcat   = isset($_GET['startcat']) ? (int)$_GET['startcat'] : 0;
    $startsub   = isset($_GET['startsub']) ? (int)$_GET['startsub'] : 0;
    $datesub    = isset($_GET['datesub']) ? (int)$_GET['datesub'] : 0;

    global $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsModule, $entryID, $pathIcon16;

    $myts = \MyTextSanitizer::getInstance();
    //    lx_adminMenu(1, _AM_LEXIKON_CATS);
    $result01 = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('lxcategories') . ' ');
    list($totalcategories) = $xoopsDB->fetchRow($result01);

    $result02 = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('lxentries') . ' WHERE submit = 0');
    list($totalpublished) = $xoopsDB->fetchRow($result02);

    $result03 = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('lxentries') . " WHERE submit = '1' AND request = '0' ");
    list($totalsubmitted) = $xoopsDB->fetchRow($result03);

    $result04 = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('lxentries') . " WHERE submit = '1' AND request = '1' ");
    list($totalrequested) = $xoopsDB->fetchRow($result04);

    if (1 == $xoopsModuleConfig['multicats']) {
        /**
         * Code to show existing categories
         **/

        echo " <table class='outer' width='100%' border='0'>
        <tr>
        <td colspan='7' class='odd'>
        <strong>" . _AM_LEXIKON_SHOWCATS . ' (' . $totalcategories . ')' . '</strong></td></tr>';
        echo '<tr>';
        // create existing columns table //doppio
        $resultC1 = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('lxcategories') . ' ');
        list($numrows) = $xoopsDB->fetchRow($resultC1);
        $sql      = 'SELECT * FROM ' . $xoopsDB->prefix('lxcategories') . ' ORDER BY weight';
        $resultC2 = $xoopsDB->query($sql, $xoopsModuleConfig['perpage'], $startcat);

        echo "<th style='width:40px; text-align:center;'>" . _AM_LEXIKON_ID . "</td>
        <th style='text-align:center;'><b>" . _AM_LEXIKON_WEIGHT . "</b></td>
        <th style='width:30%; text-align:center;'>" . _AM_LEXIKON_CATNAME . "</td>
        <th style='width:10px; text-align:center;'>" . _AM_LEXIKON_ENTRIES . "</td>
        <th style='width:*; text-align:center;'>" . _AM_LEXIKON_DESCRIP . "</td>
        <th style='width:60px; text-align:center;'>" . _AM_LEXIKON_ACTION . '</td>
        </tr>';

        $class = 'odd';
        if ($numrows > 0) { // That is, if there ARE columns in the system
            while (list($categoryID, $name, $description, $total, $weight, $logourl) = $xoopsDB->fetchRow($resultC2)) {
                $name = $myts->htmlSpecialChars($name);
                $description = strip_tags(htmlspecialchars_decode($description));
                $modify      = "<a href='category.php?op=mod&categoryID=" . $categoryID . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _AM_LEXIKON_EDITCAT . "'></a>";
                $delete      = "<a href='category.php?op=del&categoryID=" . $categoryID . "'><img src=" . $pathIcon16 . "/delete.png  alt='" . _AM_LEXIKON_DELETECAT . "'></a>";

                echo "<tr class='" . $class . "'>";
                $class = ('even' === $class) ? 'odd' : 'even';

                echo "
                <td style='text-align:center;'>" . $categoryID . "</td>
                <td style='width:10; text-align:center;'>" . $weight . "</td>
                <td style='text-align:left;'><a href='../category.php?categoryID=" . $categoryID . "'>" . $name . "</a></td>
                <td style='text-align:center;'>" . $total . "</td>
                <td style='text-align:left;'>" . $description . "</td>
                <td style='text-align:center;'>" . $modify . '-' . $delete . '</td>
                </tr></div>';
            }
        } else { // that is, $numrows = 0, there's no columns yet
            echo '<div><tr>';
            echo "<td class='odd' align='center' colspan= '7'>" . _AM_LEXIKON_NOCATS . '</td>';
            echo '</tr></div>';
            $categoryID = '0';
        }
        echo "</table>\n";
        $pagenav = new \XoopsPageNav($numrows, $xoopsModuleConfig['perpage'], $startcat, 'startcat');
        echo '<div style="text-align:right;">' . $pagenav->renderNav(8) . '</div>';
        echo "<br><br>\n";
        echo '</div>';
    } else {
        redirect_header('index.php', 1, sprintf(_AM_LEXIKON_SINGLECAT, ''));
    }
}

/**
 * Code to edit categories
 * @param string $categoryID
 */
function categoryEdit($categoryID = '')
{
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    require_once XOOPS_ROOT_PATH . '/class/uploader.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

    $utility      = new Lexikon\Utility();

    $weight      = 1;
    $name        = '';
    $description = '';
    $logourl     = '';

    global $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsModule;

    // If there is a parameter, and the id exists, retrieve data: we're editing a column
    if ($categoryID) {
        $result = $xoopsDB->query('
                                     SELECT categoryID, name, description, total, weight,logourl
                                     FROM ' . $xoopsDB->prefix('lxcategories') . "
                                     WHERE categoryID = '$categoryID'");

        list($categoryID, $name, $description, $total, $weight, $logourl) = $xoopsDB->fetchRow($result);
        $myts = \MyTextSanitizer::getInstance();
        $name = $myts->htmlSpecialChars($name);
        //permissions
        $memberHandler = xoops_getHandler('member');
        $group_list    = $memberHandler->getGroupList();
        $gpermHandler  = xoops_getHandler('groupperm');

        $groups = $gpermHandler->getGroupIds('lexikon_view', $categoryID, $xoopsModule->getVar('mid'));
        //        $groups = $groups;
        if (0 == $xoopsDB->getRowsNum($result)) {
            redirect_header('index.php', 1, _AM_LEXIKON_NOCATTOEDIT);
        }
        if (0 == $xoopsDB->getRowsNum($result)) {
            redirect_header('index.php', 1, _AM_LEXIKON_NOCATTOEDIT);
        }
        //$myts = \MyTextSanitizer::getInstance();
        //        lx_adminMenu(1, _AM_LEXIKON_CATS);

        echo "<strong style='color: #2F5376;margin-top: 6px;font-size:medium'>" . _AM_LEXIKON_CATSHEADER . '</strong>';
        $sform = new \XoopsThemeForm(_AM_LEXIKON_MODCAT . ": $name", 'op', xoops_getenv('PHP_SELF'), 'post', true);
    } else {
        //$myts = \MyTextSanitizer::getInstance();
        //        lx_adminMenu(1, _AM_LEXIKON_CATS);
        $groups = true;
        echo "<strong style='color: #2F5376;margin-top: 6px;font-size:medium'>" . _AM_LEXIKON_CATSHEADER . '</strong>';
        $sform = new \XoopsThemeForm(_AM_LEXIKON_NEWCAT, 'op', xoops_getenv('PHP_SELF'), 'post', true);
    }

    $sform->setExtra('enctype="multipart/form-data"');
    $sform->addElement(new \XoopsFormText(_AM_LEXIKON_CATNAME, 'name', 50, 80, $name), true);

    $editor =  $utility::getWysiwygForm(_AM_LEXIKON_CATDESCRIPT, 'description', $description, 7, 60);
    $sform->addElement($editor, true);
    unset($editor);

    $sform->addElement(new \XoopsFormText(_AM_LEXIKON_CATPOSIT, 'weight', 4, 4, $weight), true);
    $sform->addElement(new \XoopsFormHidden('categoryID', $categoryID));
    //CategoryImage
    if (1 == $xoopsModuleConfig['useshots']) {
        //CategoryImage :: Common querys from Article module by phppp
        $image_option_tray = new \XoopsFormElementTray('<strong>' . _AM_LEXIKON_CATIMGUPLOAD . '</strong>', '<br>');
        $image_option_tray->addElement(new \XoopsFormFile('', 'userfile', ''));
        $sform->addElement($image_option_tray);
        unset($image_tray);
        unset($image_option_tray);

        $path_catimg       = 'uploads/' . $xoopsModule->getVar('dirname') . '/categories/images';
        $image_option_tray = new \XoopsFormElementTray(_AM_LEXIKON_CATIMAGE . '<br>' . _AM_LEXIKON_CATIMG_DSC . '<br>' . $path_catimg);
        $image_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . '/' . $path_catimg . '/');
        array_unshift($image_array, _NONE);

        $image_select = new \XoopsFormSelect('', 'logourl', $logourl);
        $image_select->addOptionArray($image_array);
        $image_select->setExtra("onchange=\"showImgSelected('img', 'logourl', '/" . $path_catimg . "/', '', '" . XOOPS_URL . "')\"");
        $image_tray = new \XoopsFormElementTray('', '&nbsp;');
        $image_tray->addElement($image_select);
        if (!empty($logourl) && file_exists(XOOPS_ROOT_PATH . '/' . $path_catimg . '/' . $logourl)) {
            $image_tray->addElement(new \XoopsFormLabel('', "<div style='padding: 4px;'><img src=\"" . XOOPS_URL . '/' . $path_catimg . '/' . $logourl . '" name="img" id="img" alt="" ></div>'));
        } else {
            $image_tray->addElement(new \XoopsFormLabel('', "<div style='padding: 4px;'><img src=\"" . XOOPS_URL . '/' . $path_catimg . '/blank.gif" name="img" id="img" alt="" ></div>'));
        }
        $image_option_tray->addElement($image_tray);
        $sform->addElement($image_option_tray);
    }
    $sform->addElement(new \XoopsFormSelectGroup(_AM_LEXIKON_CAT_GROUPSVIEW, 'groups', true, $groups, 5, true));

    $button_tray = new \XoopsFormElementTray('', '');
    $hidden      = new \XoopsFormHidden('op', 'addcategory');
    $button_tray->addElement($hidden);

    // No ID for column -- then it's new column, button says 'Create'
    if (!$categoryID) {
        $butt_create = new \XoopsFormButton('', '', _AM_LEXIKON_CREATE, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
        $button_tray->addElement($butt_create);

        $butt_clear = new \XoopsFormButton('', '', _AM_LEXIKON_CLEAR, 'reset');
        $button_tray->addElement($butt_clear);

        $butt_cancel = new \XoopsFormButton('', '', _AM_LEXIKON_CANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($butt_cancel);
    } else { // button says 'Update'
        $butt_create = new \XoopsFormButton('', '', _AM_LEXIKON_MODIFY, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
        $button_tray->addElement($butt_create);

        $butt_cancel = new \XoopsFormButton('', '', _AM_LEXIKON_CANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($butt_cancel);
    }

    $sform->addElement($button_tray);
    $sform->display();
    unset($hidden);
}

/**
 * Code to delete existing categories
 * @param string $categoryID
 */
function categoryDelete($categoryID = '')
{
    //global $xoopsDB, $xoopsConfig;
    global $xoopsConfig, $xoopsDB, $xoopsModule;
    $idc = Request::getInt('categoryID', '');
    if ('' == $idc) {
        $idc = $_GET['categoryID'];
    }
    if ($idc <= 0) {
        header('location: category.php');
        die();
    }

    $ok     = Request::getInt('ok', 0, 'POST'); //isset($_POST['ok']) ? (int)$_POST['ok'] : 0;
    $result = $xoopsDB->query('SELECT categoryID, name FROM ' . $xoopsDB->prefix('lxcategories') . " WHERE categoryID = $idc");
    list($categoryID, $name) = $xoopsDB->fetchRow($result);
    // confirmed, so delete
    if (1 == $ok) {
        //get all entries in the category
        $result3 = $xoopsDB->query('SELECT entryID from ' . $xoopsDB->prefix('lxentries') . " where categoryID = $idc");
        //now for each entry, delete the coments
        while (list($entryID) = $xoopsDB->fetchRow($result3)) {
            xoops_comment_delete($xoopsModule->getVar('mid'), $entryID);
            xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'term', $entryID);
        }
        $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('lxcategories') . " WHERE categoryID='$idc'");
        $result2 = $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('lxentries') . " WHERE categoryID = $idc");
        // remove permissions
        xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'lexikon_view', $categoryID);
        xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'lexikon_submit', $categoryID);
        xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'lexikon_approve', $categoryID);
        xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'lexikon_request', $categoryID);
        // delete notifications
        xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'global', $categoryID);
        xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'category', $categoryID);

        redirect_header('category.php', 1, sprintf(_AM_LEXIKON_CATISDELETED, $name));
    } else {
        xoops_confirm(['op' => 'del', 'categoryID' => $categoryID, 'ok' => 1, 'name' => $name], 'category.php', _AM_LEXIKON_DELETETHISCAT . '<br>' . $name, _AM_LEXIKON_DELETE);
        require_once __DIR__ . '/admin_footer.php';
    }
}

/**
 * @param string $categoryID
 */
function categorySave($categoryID = '')
{
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    require_once XOOPS_ROOT_PATH . '/class/uploader.php';
    global $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $xoopsModule, $xoopsDB, $myts, $categoryID;
    //print_r ($_POST);
    $categoryID  = Request::getInt('categoryID', 0);
    $weight      = Request::getInt('weight', 0); //isset($_POST['weight']) ? (int)$_POST['weight'] : (int)$_GET['weight'];
    $name        = Request::getString('name', ''); //isset($_POST['name']) ? htmlspecialchars($_POST['name']) : htmlspecialchars($_GET['name']);
    $description = $myts->htmlSpecialChars(Request::getString('description', ''));//isset($_POST['description']) ? htmlspecialchars($_POST['description']) : htmlspecialchars($_GET['description']);
    $description =& $myts->xoopsCodeDecode($myts->censorString($description), $allowimage = 1);
    $name        = $myts->addSlashes(Request::getString('name', '', 'POST'));
    $logourl     = $myts->addSlashes(Request::getString('logourl', '', 'POST'));
    $groups      = Request::getArray('group', [], 'POST'); //isset($_POST['groups']) ? $_POST['groups'] : array();
    // image upload
    $logourl       = '';
    $maxfilesize = $xoopsModuleConfig['imguploadsize'];
    $maxfilewidth  = $xoopsModuleConfig['imguploadwd'];
    $maxfileheight = $xoopsModuleConfig['imguploadwd'];
    if (!empty($_FILES['userfile']['name'])) {
        $allowed_mimetypes = [
            'image/gif',
            'image/jpeg',
            'image/pjpeg',
            'image/x-png',
            'image/png'
        ];
        $uploader          = new \XoopsMediaUploader(XOOPS_ROOT_PATH . '/uploads/' . $xoopsModule->getVar('dirname') . '/categories/images/', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);

        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            if (!$uploader->upload()) {
                echo $uploader->getErrors();
            } else {
                echo '<h4>' . _AM_LEXIKON_FILESUCCESS . '</h4>';
                $logourl = $uploader->getSavedFileName();
            }
        } else {
            echo $uploader->getErrors();
        }
    }
    $logourl = empty($logourl) ? (empty($_POST['logourl']) ? '' : $_POST['logourl']) : $logourl;

    // Run the query and update the data
    if (!$_POST['categoryID']) {
        if ($xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('lxcategories') . " (categoryID, name, description, weight, logourl)
                                 VALUES (0, '$name', '$description', '$weight', '$logourl')")) {
            $newid = $xoopsDB->getInsertId();
            // Increment author's posts count (only if it's a new definition)
            if (is_object($xoopsUser) && empty($categoryID)) {
                $memberHandler = xoops_getHandler('member');
                $submitter     = $memberHandler->getUser($uid);
                if (is_object($submitter)) {
                    $submitter->setVar('posts', $submitter->getVar('posts') + 1);
                    $res = $memberHandler->insertUser($submitter, true);
                    unset($submitter);
                }
            }
            //notification
            if (!empty($xoopsModuleConfig['notification_enabled'])) {
                if (0 == $newid) {
                    $newid = $xoopsDB->getInsertId();
                }
                global $xoopsModule;
                $notificationHandler = xoops_getHandler('notification');
                $tags                = [];
                $tags['ITEM_NAME']   = $name;
                $tags['ITEM_URL']    = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/category.php?categoryID=' . $newid;
                $notificationHandler->triggerEvent('global', 0, 'new_category', $tags);
            }
            lx_save_Permissions($groups, $categoryID, 'lexikon_view');
            redirect_header('category.php', 1, _AM_LEXIKON_CATCREATED);
        } else {
            redirect_header('index.php', 1, _AM_LEXIKON_NOTUPDATED);
        }
    } else {
        if ($xoopsDB->queryF('
                                UPDATE ' . $xoopsDB->prefix('lxcategories') . "
                                SET name = '$name', description = '$description', weight = '$weight' , logourl = '$logourl'
                                WHERE categoryID = '$categoryID'")) {
            lx_save_Permissions($groups, $categoryID, 'lexikon_view');
            redirect_header('category.php', 1, _AM_LEXIKON_CATMODIFIED);
        } else {
            redirect_header('index.php', 1, _AM_LEXIKON_NOTUPDATED);
        }
    }
}

/**
 * Available operations
 **/
$op = 'default';
if (isset($_POST['op'])) {
    $op = $_POST['op'];
} else {
    if (isset($_GET['op'])) {
        $op = $_GET['op'];
    }
}

switch ($op) {
    case 'mod':
        $categoryID = Request::getInt('categoryID', 0);
        categoryEdit($categoryID);
        break;

    case 'addcat':
        categoryEdit();
        break;

    case 'addcategory':
        categorySave();
        break;

    case 'del':
        categoryDelete();
        break;

    case 'default':
    default:
        categoryDefault();
        break;
}
require_once __DIR__ . '/admin_footer.php';
