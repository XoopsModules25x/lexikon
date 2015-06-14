<?php
// comment callback functions

function lexikon_com_update($entry_ID, $total_num) {
    $db =& XoopsDatabaseFactory::getDatabaseConnection();
    $sql = 'UPDATE '.$db->prefix('lxentries').' SET comments = '.$total_num.' WHERE entryID = '.$entry_ID;
    $db->query($sql);
}

function lexikon_com_approve(&$comment) {
    // notification mail here
}
