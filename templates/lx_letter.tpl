<{* New Header block *}>
<div id="moduleheader">
    <div class="leftheader"><{$smarty.const._MD_LEXIKON_HOME}>&nbsp;<img src='assets/images/arrow.gif' style="vertical-align:middle;" alt="<{$lang_modulename}>">&nbsp;<a
                href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/"><{$lang_modulename}></a>&nbsp;<img
                src='assets/images/arrow.gif' style="vertical-align:middle;" alt="<{$lang_modulename}>">&nbsp;
        <{if $pagetype == '0'}>
            <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php"><{$pageinitial}></a>
            &nbsp;
        <{elseif $pagetype == '1'}>
            <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$pageinitial}>"><{$pageinitial}></a>
        <{/if}>
    </div>
    <div class="rightheader"><{$lang_modulename}></div>
    <hr style="clear: both;">

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
    <{if $layout == '0'}>
        <{if $multicats == 1 && count($block0.categories) gt 0 }>
            <div class="clearer">
                <fieldset>
                    <legend>&nbsp;<{$smarty.const._MD_LEXIKON_BROWSECAT}>&nbsp;</legend>
                    <table id="Lxcategory">
                        <tr>
                            <td>
                                <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php"
                                   title="[&nbsp;<{$publishedwords}>&nbsp;]"><{$smarty.const._MD_LEXIKON_ALLCATS}></a>
                                [<{$publishedwords}>]
                            </td>
                            <!-- Start category loop -->
                            <{foreach item=catlinks from=$block0.categories}>
                            <td>
                                <{if $catlinks.image != "" && $show_screenshot == true}>
                                    <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php?categoryID=<{$catlinks.id}>"
                                       target="_parent"><img
                                                src="<{$xoops_url}>/uploads/<{$lang_moduledirname}>/categories/images/<{$catlinks.image}>"
                                                style="width:<{$logo_maximgwidth}>; text-align: left;" class="floatLeft"
                                                alt="[&nbsp;<{$catlinks.name}>&nbsp;]&nbsp;[&nbsp;<{$catlinks.total}>&nbsp;]"></a>
                                <{/if}>
                                <{if $catlinks.count > 0}>
                                    <{if $catlinks.total > 0}><a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php?categoryID=<{$catlinks.id}>" title="[&nbsp;<{$catlinks.total}>&nbsp;]"><{/if}><{$catlinks.linktext}>
                                    <{if $catlinks.total > 0}></a>&nbsp;<{/if}>[&nbsp;<{$catlinks.total}>&nbsp;]
                                <{/if}>
                            </td>
                            <{if $catlinks.count is div by 4}>
                        </tr>
                        <tr>
                            <{/if}>
                            <{/foreach}>
                            <!-- End category loop -->
            </div>
            </tr>
            </table>
            </fieldset>
        <{/if}>
    <{else}>
        <{if $multicats == 1}>
            <div class="clearer">
                <fieldset>
                    <legend>&nbsp;<{$smarty.const._MD_LEXIKON_BROWSECAT}>&nbsp;</legend>
                    <div class="letters">
                        <{foreach item=catlinks from=$block0.categories}>
                        <{if $catlinks.image != "" && $show_screenshot == true}>
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
    <{/if}>

    <{if $pagetype == '0'}>
        <h2 class="cat"><{$smarty.const._MD_LEXIKON_ALL}></h2>
        <div class="letters"><{$smarty.const._MD_LEXIKON_WEHAVE}>&nbsp;<{$totalentries}>&nbsp;<{$smarty.const._MD_LEXIKON_INALLGLOSSARIES}></div>
        <{foreach item=eachentry from=$entriesarray.single}>
            <h4 class="term"><{$eachentry.microlinks}><a href="<{$xoops_url}>/modules/<{$eachentry.dir}>/entry.php?entryID=<{$eachentry.id}>"><{$eachentry.term}></a>&nbsp;<{if $multicats == 1}>
            <a class="catname_min" href="<{$xoops_url}>/modules/<{$eachentry.dir}>/category.php?categoryID=<{$eachentry.catid}>">[&nbsp;<{$eachentry.catname}>&nbsp;]</a>
            </h4><{/if}>
            <{if $eachentry.definition }>
                <div class="definition"><{$eachentry.definition}></div><{/if}>
            <{if $eachentry.comments }>
                <div class="xsmall">[&nbsp;<{$eachentry.comments}>&nbsp;]</div><{/if}>
        <{/foreach}>
        <div class="search_abc_l"></div>
        <div class="search_abc_c">[&nbsp;<a href='javascript:history.go(-1)'><{$smarty.const._MD_LEXIKON_RETURN}></a><b>&nbsp;|&nbsp;</b><a href='./'><{$smarty.const._MD_LEXIKON_RETURN2INDEX}></a>&nbsp;]
        </div>
        <div class="search_abc_r"><{$entriesarray.navbar}></div>
    <{elseif $pagetype == '1'}>
        <h2 class="cat"><{$firstletter}></h2>
        <div class="letters"><{$smarty.const._MD_LEXIKON_WEHAVE}>&nbsp;<{$totalentries}>&nbsp;<{$smarty.const._MD_LEXIKON_BEGINWITHLETTER}></div>
        <{foreach item=eachentry from=$entriesarray2.single}>
            <h4 class="term" style="clear:both;"><{$eachentry.microlinks}><a href="<{$xoops_url}>/modules/<{$eachentry.dir}>/entry.php?entryID=<{$eachentry.id}>"><{$eachentry.term}></a>&nbsp;<{if $multicats == 1}>
            <a class="catname_min" href="<{$xoops_url}>/modules/<{$eachentry.dir}>/category.php?categoryID=<{$eachentry.catid}>">[&nbsp;<{$eachentry.catname}>&nbsp;]</a>
            </h4><{/if}>
            <{if $eachentry.definition }>
                <div class="definition"><{$eachentry.definition}></div><{/if}>
            <{if $eachentry.comments }>
                <div class="xsmall">[&nbsp;<{$eachentry.comments}>&nbsp;]</div><{/if}>
        <{/foreach}>
        <div class="search_abc_l"></div>
        <div class="search_abc_c">[&nbsp;<a href='javascript:history.go(-1)'><{$smarty.const._MD_LEXIKON_RETURN}></a><b>&nbsp;|&nbsp;</b><a href='./'><{$smarty.const._MD_LEXIKON_RETURN2INDEX}></a>&nbsp;]
        </div>
        <div class="search_abc_r"><{$entriesarray2.navbar}></div>
    <{/if}>
</div>
