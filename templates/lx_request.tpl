<table id="moduleheader">
    <tr>
        <td width="100%"><span class="leftheader"><a href="<{$xoops_url}>"><{$smarty.const._MD_LEXIKON_HOME}></a> <img
                        src='assets/images/arrow.gif' align='absmiddle'/> <a
                        href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/index.php"><{$lang_modulename}></a> <img
                        src='assets/images/arrow.gif'
                        align='absmiddle'/> <{$smarty.const._MD_LEXIKON_ASKFORDEF}></span></td>
        <td width="100"><span class="rightheader"><{$lang_modulename}></span></td>
    </tr>
</table>

<h3 class="cat"><{$smarty.const._MD_LEXIKON_ASKFORDEF}></h3>
<div class="intro"><{$smarty.const._MD_LEXIKON_INTROREQUEST}></div>
<{$requestform.javascript}>
<form name="<{$requestform.name}>" action="<{$requestform.action}>"
      method="<{$requestform.method}>" <{$requestform.extra}>>
    <fieldset>
        <legend><{$requestform.title}></legend>
        <table cellspacing="1">
            <{foreach item=element from=$requestform.elements}>
                <{if $element.hidden != true}>
                    <tr>
                        <td width="160" style="text-align: right; line-height: 200%;"><{$element.caption}></td>
                        <td width="10">&nbsp;</td>
                        <td style="text-align: left;"><{$element.body}></td>
                    </tr>
                <{else}>
                    <{$element.body}>
                <{/if}>
            <{/foreach}>
        </table>
    </fieldset>
</form>
<br>
