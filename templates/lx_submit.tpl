<table id="moduleheader">
    <tr>
        <td width="100%"><span class="leftheader"><a href="<{$xoops_url}>"><{$smarty.const._MD_LEXIKON_HOME}></a> <img
                        src='assets/images/arrow.gif' align='absmiddle'/> <a
                        href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/index.php"><{$lang_modulename}></a> <img
                        src='assets/images/arrow.gif'
                        align='absmiddle'/> <{$smarty.const._MD_LEXIKON_SUBMITART}></span></td>
        </span></td>
        <td width="100"><span class="rightheader"><{$lang_modulename}></span></td>
    </tr>
</table>

<h3 class="cat"><{$send_def_to}></h3>
<p class='intro'><{$smarty.const._MD_LEXIKON_GOODDAY}>
    <b><{$lx_user_name}></b>, <{$smarty.const._MD_LEXIKON_SUB_SNEWNAMEDESC}></p>

<{$storyform.javascript}>
<form name="<{$storyform.name}>" action="<{$storyform.action}>" method="<{$storyform.method}>" <{$storyform.extra}>>
    <fieldset>
        <legend><{$storyform.title}></legend>
        <table cellspacing="1">
            <{foreach item=element from=$storyform.elements}>
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
