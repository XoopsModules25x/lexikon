<{* Alphabet block *}>
<div class="clearer">
    <div class="toprow">
        <fieldset>
            <legend>&nbsp;<{$smarty.const._MD_LEXIKON_BROWSELETTER}>&nbsp;</legend>
            <div class="search_abc_l">
                <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php"
                ><abbr name="[&nbsp;<{$publishedwords}>&nbsp;]"><{$smarty.const._MD_LEXIKON_ALL}></abbr></a></div>
            <div class="search_abc_c">&nbsp;|
                <{foreach item=letterlinks from=$alpha.initial}>
                    <{if $letterlinks.total > 0}>&nbsp;<a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$letterlinks.id}>"><abbr name="[&nbsp;<{$letterlinks.total}>&nbsp;]"><{/if}><{$letterlinks.linktext}></abbr>
                    <{if $letterlinks.total > 0}></a><{/if}>&nbsp;|
                <{/foreach}></div>
            <div class="search_abc_r">
                <{if $totalother > 0}>&nbsp;<a
                        href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$smarty.const._MD_LEXIKON_OTHER}>"
                ><abbr name="[&nbsp;<{$totalother}>&nbsp;]"><{/if}><{$smarty.const._MD_LEXIKON_OTHER}></abbr>
                    <{if $totalother > 0}></a><{/if}>
            </div>
        </fieldset>
    </div>
</div>

<{* Category block *}>
<{if $multicats == 1}>
    <div class="clearer">
        <fieldset>
            <legend>&nbsp;<{$smarty.const._MD_LEXIKON_BROWSECAT}>&nbsp;</legend>
            <div class="letters">
                <{foreach item=catlinks from=$block0.categories}>
                <{if $catlinks.image != "" && $show_screenshot == '1'}>
                    <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php?categoryID=<{$category.id}>"
                       target="_parent">
                        <img src="<{$xoops_url}>/uploads/<{$lang_moduledirname}>/categories/images/<{$catlinks.image}>"
                             style="width:<{$logo_maximgwidth}> vertical-align:middle;"
                             alt="[&nbsp;<{$catlinks.total}>&nbsp;]"></a>
                <{/if}>
                <{if $catlinks.total > 0}>&nbsp;<a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php?categoryID=<{$catlinks.id}>" title="[&nbsp;<{$catlinks.total}>&nbsp;]"><{/if}><{$catlinks.linktext}>
                <{if $catlinks.total > 0}></a>&nbsp;<{/if}>[&nbsp;<{$catlinks.total}>&nbsp;]
                <{/foreach}>&nbsp;
                <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php"
                   title="[&nbsp;<{$publishedwords}>&nbsp;]"><{$smarty.const._MD_LEXIKON_ALLCATS}></a>&nbsp;[&nbsp;<{$publishedwords}>&nbsp;]
            </div>
        </fieldset>
    </div>
<{/if}>
