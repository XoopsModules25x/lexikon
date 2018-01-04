<{if $entriesRows > 0}>
    <div class="outer">
        <form name="select" action="entries.php?op=" method="POST"
              onsubmit="if(window.document.select.op.value =='') {return false;} else if (window.document.select.op.value =='delete') {return deleteSubmitValid('entriesId[]');} else if (isOneChecked('entriesId[]')) {return true;} else {alert('<{$smarty.const.AM_ENTRIES_SELECTED_ERROR}>'); return false;}">
            <input type="hidden" name="confirm" value="1"/>
            <div class="floatleft">
                <select name="op">
                    <option value=""><{$smarty.const.AM_LEXIKON_SELECT}></option>
                    <option value="delete"><{$smarty.const.AM_LEXIKON_SELECTED_DELETE}></option>
                </select>
                <input id="submitUp" class="formButton" type="submit" name="submitselect" value="<{$smarty.const._SUBMIT}>" title="<{$smarty.const._SUBMIT}>"/>
            </div>
            <div class="floatcenter0">
                <div id="pagenav"><{$pagenav}></div>
            </div>


            <table class="$entries" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <th align="center" width="5%"><input name="allbox" title="allbox" id="allbox" onclick="xoopsCheckAll('select', 'allbox');" type="checkbox" title="Check All" value="Check All"/></th>
                    <th class="center"><{$selectorentryID}></th>
                    <th class="center"><{$selectorcategoryID}></th>
                    <th class="center"><{$selectoruid}></th>
                    <th class="center"><{$selectorterm}></th>
                    <{*<th class="center"><{$selectorinit}></th>*}>
                    <th class="center"><{$selectordefinition}></th>
                    <{*<th class="center"><{$selectorref}></th>*}>
                    <{*<th class="center"><{$selectorurl}></th>*}>
                    <{*<th class="center"><{$selectorsubmit}></th>*}>
                    <th class="center"><{$selectordatesub}></th>
                    <{*<th class="center"><{$selectorcounter}></th>*}>
                    <{*<th class="center"><{$selectorhtml}></th>*}>
                    <{*<th class="center"><{$selectorsmiley}></th>*}>
                    <{*<th class="center"><{$selectorxcodes}></th>*}>
                    <{*<th class="center"><{$selectorbreaks}></th>*}>
                    <{*<th class="center"><{$selectorblock}></th>*}>
                    <th class="center"><{$selectoroffline}></th>
                    <{*<th class="center"><{$selectornotifypub}></th>*}>
                    <{*<th class="center"><{$selectorrequest}></th>*}>
                    <th class="center"><{$selectorcomments}></th>
                    <{*<th class="center"><{$selectoritem_tag}></th>*}>

                    <th class="center width5"><{$smarty.const.AM_LEXIKON_FORM_ACTION}></th>
                </tr>
                <{foreach item=entriesArray from=$entriesArrays}>
                    <tr class="<{cycle values="odd,even"}>">

                        <td align="center" style="vertical-align:middle;"><input type="checkbox" name="entries_id[]" title="entries_id[]" id="entries_id[]" value="<{$entriesArray.entries_id}>"/></td>
                        <td class='center'><{$entriesArray.entryID}></td>
                        <td class='center'><{$entriesArray.categoryID}></td>
                        <td class='center'><{$entriesArray.uid}></td>
                        <td class='left'><{$entriesArray.term}></td>
                        <{*<td class='center'><{$entriesArray.init}></td>*}>
                        <td class='left'><{$entriesArray.definition}></td>
                        <{*<td class='center'><{$entriesArray.ref}></td>*}>
                        <{*<td class='center'><{$entriesArray.url}></td>*}>
                        <{*<td class='center'><{$entriesArray.submit}></td>*}>
                        <td class='center'><{$entriesArray.datesub}></td>
                        <{*<td class='center'><{$entriesArray.counter}></td>*}>
                        <{*<td class='center'><{$entriesArray.html}></td>*}>
                        <{*<td class='center'><{$entriesArray.smiley}></td>*}>
                        <{*<td class='center'><{$entriesArray.xcodes}></td>*}>
                        <{*<td class='center'><{$entriesArray.breaks}></td>*}>
                        <{*<td class='center'><{$entriesArray.block}></td>*}>
                        <td class='center'><{$entriesArray.offline}></td>
                        <{*<td class='center'><{$entriesArray.notifypub}></td>*}>
                        <{*<td class='center'><{$entriesArray.request}></td>*}>
                        <td class='center'><{$entriesArray.comments}></td>
                        <{*<td class='center'><{$entriesArray.item_tag}></td>*}>

                        <td class="center width5"><{$entriesArray.edit_delete}></td>
                    </tr>
                <{/foreach}>
            </table>
            <br>
            <div class="floatcenter0">
                <div id="pagenav"><{$pagenav}></div>
            </div>
            <br>
            <{else}>
            <table width="100%" cellspacing="1" class="outer">
                <tr>
                    <th align="center" width="5%"><input name="allbox" title="allbox" id="allbox" onclick="xoopsCheckAll('select', 'allbox');" type="checkbox" title="Check All" value="Check All"/></th>
                    <th class="center"><{$selectorentryID}></th>
                    <th class="center"><{$selectorcategoryID}></th>
                    <th class="center"><{$selectoruid}></th>
                    <th class="center"><{$selectorterm}></th>
                    <th class="center"><{$selectorinit}></th>
                    <th class="center"><{$selectordefinition}></th>
                    <th class="center"><{$selectorref}></th>
                    <th class="center"><{$selectorurl}></th>
                    <th class="center"><{$selectorsubmit}></th>
                    <th class="center"><{$selectordatesub}></th>
                    <th class="center"><{$selectorcounter}></th>
                    <th class="center"><{$selectorhtml}></th>
                    <th class="center"><{$selectorsmiley}></th>
                    <th class="center"><{$selectorxcodes}></th>
                    <th class="center"><{$selectorbreaks}></th>
                    <th class="center"><{$selectorblock}></th>
                    <th class="center"><{$selectoroffline}></th>
                    <th class="center"><{$selectornotifypub}></th>
                    <th class="center"><{$selectorrequest}></th>
                    <th class="center"><{$selectorcomments}></th>
                    <th class="center"><{$selectoritem_tag}></th>
                    <th class="center width5"><{$smarty.const.AM_LEXIKON_FORM_ACTION}></th>
                </tr>
                <tr>
                    <td class="errorMsg" colspan="11">There are no $entries</td>
                </tr>
            </table>
    </div>
    <br>
    <br>
<{/if}>
