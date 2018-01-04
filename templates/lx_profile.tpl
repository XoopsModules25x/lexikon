<div id="moduleheader">
    <div class="leftheader"><{$smarty.const._MD_LEXIKON_HOME}>&nbsp;<img
                src='assets/images/arrow.gif' style="vertical-align:middle;" alt="<{$lang_modulename}>">&nbsp;<a
                href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/index.php"><{$lang_modulename}></a>&nbsp;<img
                src='assets/images/arrow.gif' style="vertical-align:middle;" alt="<{$lang_modulename}>">&nbsp;<{$smarty.const._MD_LEXIKON_AUTHORPROFILE}>&nbsp;<{$author_name}></div>
    <div class="rightheader"><{$lang_modulename}></div>
    <hr style="clear: both;">
    <br>
    <div class="clearer">
        <h2 class="cat"><img src="<{$user_avatarurl}>" alt="<{$author_name_with_link}>"><br><{$lang_authorprofile}>&nbsp;<{$author_name_with_link}>
        </h2>
    </div>
    <{*
    <div class="clearer"><div style="text-align: left; font-size: small;">
    <{$authorterms}>
    </div></div>
    *}>
    <br>
    <div class="category">
        <div class="catname">
            <{if $nothing==false}>
                <img src='<{$xoops_url}>/modules/<{$lang_moduledirname}>/assets/images/square-green.gif' style="vertical-align:middle;" alt="square-green">
                <{$submitted}>
                <br>
                <img src='<{$xoops_url}>/modules/<{$lang_moduledirname}>/assets/images/square-red.gif' style="vertical-align:middle;" alt="square-red">
                <{$waiting}>
            <{/if}>
        </div>
    </div>

    <div class="clearer">
        <table class="outer" style="width:100%; border-spacing: 1px; padding: 2px;">
            <!--<tr>
        <th colspan="4" class="odd" align="center"><{$lang_terms_by_this_author}> <{$author_name}></th>
    </tr>-->
            <tr class="odd" style="text-align:center;">
                <td><{$smarty.const._MD_LEXIKON_DATETERM}></td>
                <td><{$smarty.const._MD_LEXIKON_TERMS}></td>
                <td><{$smarty.const._MD_LEXIKON_HITS}></td>
            </tr>
            <{if $nothing==false}>
                <{foreach item=d from=$entries}>
                    <tr class="<{cycle values="even,odd"}>">
                        <td style="font-size:11px; text-align:center;"><{$d.date}></td>
                        <td style="text-align:left;"><a href="entry.php?entryID=<{$d.id}>"><{$d.name}></a></td>
                        <td style="text-align:center;"><{$d.counter}></td>
                    </tr>
                <{/foreach}>
            <{/if}>
        </table>
        <{if $navi==true}>
            <div class="search_abc_l"></div>
            <div class="search_abc_c">[&nbsp;<a href='javascript:history.go(-1)'><{$smarty.const._MD_LEXIKON_RETURN}></a><b>&nbsp;|&nbsp;</b><a href='./'><{$smarty.const._MD_LEXIKON_RETURN2INDEX}></a>&nbsp;]
            </div>
            <div class="search_abc_r"><{$authortermsarr.navbar}></div>
        <{/if}></div>

    <div style="text-align: center; font-size: small;">
        <{if $nothing==true}><{$nothing}><{/if}>
    </div>
