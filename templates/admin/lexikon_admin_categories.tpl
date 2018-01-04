<{if $categoriesRows > 0}>
    <div class="outer">
         <form name="select" action="categories.php?op=" method="POST"
              onsubmit="if(window.document.select.op.value =='') {return false;} else if (window.document.select.op.value =='delete') {return deleteSubmitValid('categoriesId[]');} else if (isOneChecked('categoriesId[]')) {return true;} else {alert('<{$smarty.const.AM_CATEGORIES_SELECTED_ERROR}>'); return false;}">
            <input type="hidden" name="confirm" value="1"/>
            <div class="floatleft">
                   <select name="op">
                       <option value=""><{$smarty.const.AM_LEXIKON_SELECT}></option>
                       <option value="delete"><{$smarty.const.AM_LEXIKON_SELECTED_DELETE}></option>
                   </select>
                   <input id="submitUp" class="formButton" type="submit" name="submitselect" value="<{$smarty.const._SUBMIT}>" title="<{$smarty.const._SUBMIT}>"  />
               </div>
            <div class="floatcenter0">
                <div id="pagenav"><{$pagenav}></div>
            </div>



          <table class="$categories" cellpadding="0" cellspacing="0" width="100%">
            <tr><th align="center" width="5%"><input name="allbox" title="allbox" id="allbox" onclick="xoopsCheckAll('select', 'allbox');" type="checkbox" title="Check All"  value="Check All" /></th>  <th class="center"><{$selectorcategoryID}></th>  <th class="center"><{$selectorname}></th>  <th class="center"><{$selectordescription}></th>  <th class="center"><{$selectortotal}></th>  <th class="center"><{$selectorweight}></th>  <th class="center"><{$selectorlogourl}></th>

<th class="center width5"><{$smarty.const.AM_LEXIKON_FORM_ACTION}></th>
</tr>
<{foreach item=categoriesArray from=$categoriesArrays}>
<tr class="<{cycle values="odd,even"}>">

<td align="center" style="vertical-align:middle;"><input type="checkbox" name="categories_id[]"  title ="categories_id[]" id="categories_id[]" value="<{$categoriesArray.categories_id}>" /></td>
<td class='center'><{$categoriesArray.categoryID}></td>
<td class='center'><{$categoriesArray.name}></td>
<td class='center'><{$categoriesArray.description}></td>
<td class='center'><{$categoriesArray.total}></td>
<td class='center'><{$categoriesArray.weight}></td>
<td class='center'><{$categoriesArray.logourl}></td>


<td class="center width5"><{$categoriesArray.edit_delete}></td>
</tr>
<{/foreach}>
</table>
<br>
<br>
<{else}>
<table width="100%" cellspacing="1" class="outer">
<tr>

<th align="center" width="5%"><input name="allbox" title="allbox" id="allbox" onclick="xoopsCheckAll('select', 'allbox');" type="checkbox" title="Check All"  value="Check All" /></th>  <th class="center"><{$selectorcategoryID}></th>  <th class="center"><{$selectorname}></th>  <th class="center"><{$selectordescription}></th>  <th class="center"><{$selectortotal}></th>  <th class="center"><{$selectorweight}></th>  <th class="center"><{$selectorlogourl}></th>

<th class="center width5"><{$smarty.const.AM_LEXIKON_FORM_ACTION}></th>
</tr>
<tr>
<td class="errorMsg" colspan="11">There are no $categories</td>
</tr>
</table>
</div>
<br>
<br>
<{/if}>
