<div id="moduleheader">
    <div class="leftheader"><{$smarty.const._MD_LEXIKON_HOME}>&nbsp;<img
                src='assets/images/arrow.gif' style="vertical-align:middle;" alt="<{$lang_modulename}>">&nbsp;<a
                href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/index.php"><{$lang_modulename}></a>&nbsp;<img
                src='assets/images/arrow.gif' style="vertical-align:middle;" alt="<{$lang_modulename}>">&nbsp;<{$smarty.const._MD_LEXIKON_ASKFORDEF}></div>
    <div class="rightheader"><{$lang_modulename}></div>
</div>
<hr style="clear: both;">

<h2 class="cat"><{$smarty.const._MD_LEXIKON_ASKFORDEF}></h2>
<div class="intro"><{$smarty.const._MD_LEXIKON_INTROREQUEST}></div>
<{$requestform.javascript}>
<form name="<{$requestform.name}>" action="<{$requestform.action}>"
      method="<{$requestform.method}>" <{$requestform.extra}>>
    <fieldset>
        <legend>&nbsp;<{$requestform.title}>&nbsp;</legend>
        <table cellspacing="1">
            <{foreach item=element from=$requestform.elements}>
                <{if $element.hidden != true}>
                    <tr>
                        <td style="text-align: right; line-height: 200%; width:160px;"><{$element.caption}></td>
                        <td style="width:10px;">&nbsp;</td>
                        <td style="text-align: left;"><{$element.body}></td>
                    </tr>
                <{else}>
                    <{$element.body}>
                <{/if}>
            <{/foreach}>
        </table>
    </fieldset>
</form>
