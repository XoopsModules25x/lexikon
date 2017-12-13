<{* New Header block *}>
<div id="moduleheader">
    <div class="leftheader"><{$smarty.const._MD_LEXIKON_HOME}>&nbsp;
        <img src='assets/images/arrow.gif' style="vertical-align:middle;" alt="<{$lang_modulename}>">&nbsp;
        <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/"><{$lang_modulename}></a>&nbsp;<img
                src='assets/images/arrow.gif' style="vertical-align:middle;" alt="<{$lang_modulename}>">&nbsp;
        <{if $pagetype == '0'}><a
            href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php"><{$smarty.const._MD_LEXIKON_ALLCATS}></a>
        <{elseif $pagetype == '1'}><{$singlecat.name}><{/if}></div>
    <div class="rightheader"><{$lang_modulename}></div>
    <hr style="clear: both;">

    <{* Alphabet block *}>
    <div class="clearer">
        <div class="toprow">
            <fieldset>
                <legend>&nbsp;<{$smarty.const._MD_LEXIKON_BROWSELETTER}>&nbsp;</legend>
                <div class="search_abc_l">
                    <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php"><abbr name="[&nbsp;<{$publishedwords}>&nbsp;]"><{$smarty.const._MD_LEXIKON_ALL}></abbr></a></div>
                <div class="search_abc_c">&nbsp;|
                    <{foreach item=letterlinks from=$alpha.initial}>
                        <{if $letterlinks.total > 0}>&nbsp;<a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$letterlinks.id}>"><abbr name="[&nbsp;<{$letterlinks.total}>&nbsp;]"><{/if}><{$letterlinks.linktext}></abbr>
                        <{if $letterlinks.total > 0}></a><{/if}>&nbsp;|
                    <{/foreach}></div>
                <div class="search_abc_r">
                    <{if $totalother > 0}>&nbsp;<a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$smarty.const._MD_LEXIKON_OTHER}>"><abbr name="[&nbsp;<{$totalother}>&nbsp;]"><{/if}><{$smarty.const._MD_LEXIKON_OTHER}></abbr>
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
        <h2 class="cat"><{$smarty.const._MD_LEXIKON_ALLCATS}></h2>
        <{foreach item=eachcat from=$catsarray.single}>
            <h3 class="cat"><{if $eachcat.image != "" && $show_screenshot == '1'}>
                    <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php?categoryID=<{$eachcat.id}>" target="_parent">
                        <img src="<{$xoops_url}>/uploads/lexikon/categories/images/<{$eachcat.image}>" style="width:<{$imgcatwd}>; vertical-align:bottom; margin: 2px 2px;" alt="[<{$eachcat.name}>]"></a>
                <{/if}>
                <a href="<{$xoops_url}>/modules/<{$eachcat.dir}>/category.php?categoryID=<{$eachcat.id}>"><{$eachcat.name}></a>
            </h3>
            <{if $eachcat.description }>
                <div class="introcen"><{$eachcat.description}></div><{/if}>
            <div class="letters"><{$smarty.const._MD_LEXIKON_WEHAVE}>&nbsp;<{$eachcat.total}>&nbsp;<{$smarty.const._MD_LEXIKON_ENTRIESINCAT}></div>
            <br>
        <{/foreach}>
        <div class="search_abc_l"></div>
        <div class="search_abc_c">[&nbsp;<a href='javascript:history.go(-1)'><{$smarty.const._MD_LEXIKON_RETURN}></a><b>&nbsp;|&nbsp;</b><a href='./'><{$smarty.const._MD_LEXIKON_RETURN2INDEX}></a>&nbsp;]
        </div>
        <div class="search_abc_r"><{$entriesarray.navbar}></div>
        <{* syndication *}>
        <{if $syndication == true}>
            <div class="rss_bottom"><a href="rss.php" title="recent entries"><img src="assets/images/rss.gif" alt="RSS"></a>
            </div>
        <{/if}>
    <{elseif $pagetype == '1'}>
        <h2 class="cat"><{$singlecat.name}></h2>
        <div class="introcen">
            <{if $singlecat.image != "" && $show_screenshot == '1'}>
                <img src="<{$xoops_url}>/uploads/lexikon/categories/images/<{$singlecat.image}>" style="width:<{$imgcatwd}>; text-align:center; margin: 2px 2px;" alt="[<{$singlecat.name}>]">
            <{/if}>
            <{$singlecat.description}></div>
        <div class="letters"><{$smarty.const._MD_LEXIKON_WEHAVE}>&nbsp;<{$singlecat.total}>&nbsp;<{$smarty.const._MD_LEXIKON_ENTRIESINCAT}>
        </div>
        <{foreach item=eachentry from=$entriesarray.single}>
            <h4 class="term"><{$eachentry.microlinks}><a href="<{$xoops_url}>/modules/<{$eachentry.dir}>/entry.php?entryID=<{$eachentry.id}>"><{$eachentry.term}></a></h4>
            <{if $eachentry.definition }>
                <div class="definition"><{$eachentry.definition}></div><{/if}>
            <{if $eachentry.comments }>
                <div class="xsmall">[&nbsp;<{$eachentry.comments}>&nbsp;]</div><{/if}>
        <{/foreach}>
        <div class="search_abc_l"></div>
        <div class="search_abc_c">[&nbsp;<a href='javascript:history.go(-1)'><{$smarty.const._MD_LEXIKON_RETURN}></a><b>&nbsp;|&nbsp;</b><a href='./'><{$smarty.const._MD_LEXIKON_RETURN2INDEX}></a>&nbsp;]
        </div>
        <div class="search_abc_r"><{$entriesarray.navbar}></div>
        <{* syndication *}>
        <{if $syndication == true}>
            <div class="rss_bottom"><a href="rss.php?categoryID=<{$singlecat.id}>" title="Recent terms in this category"><img src="assets/images/rss.gif" alt="RSS"></a>
            </div>
        <{/if}>
    <{/if}>
</div>
<{include file='db:system_notification_select.tpl'}>
