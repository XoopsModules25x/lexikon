<div id="moduleheader">
    <div class="leftheader"><{$smarty.const._MD_LEXIKON_HOME}></a>&nbsp;<img
                src='assets/images/arrow.gif' style="vertical-align:middle;" alt="<{$lang_modulename}>">&nbsp;<a
                href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/index.php"><{$lang_modulename}></a>&nbsp;<img
                src='assets/images/arrow.gif' style="vertical-align:middle;" alt="<{$lang_modulename}>">&nbsp;<{$smarty.const._MD_LEXIKON_SUBMITART}></div>
    <div class="rightheader"><{$lang_modulename}></div>
</div>
<hr style="clear: both;">

<h2 class="cat"><{$send_def_to}></h2>
<p class='intro'><{$smarty.const._MD_LEXIKON_GOODDAY}>
    <b><{$lx_user_name}></b>,&nbsp;<{$smarty.const._MD_LEXIKON_SUB_SNEWNAMEDESC}></p>
<{$storyform.javascript}>
<form name="<{$storyform.name}>" action="<{$storyform.action}>" method="<{$storyform.method}>" <{$storyform.extra}>>
    <fieldset>
        <legend>&nbsp;<{$storyform.title}>&nbsp;</legend>
        <table cellspacing="1">
            <{foreach item=element from=$storyform.elements}>
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
