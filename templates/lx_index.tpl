<{* New Header block *}>
<div id="moduleheader">
    <div class="leftheader"><a href="<{$xoops_url}>"><{$smarty.const._MD_LEXIKON_HOME}></a>&nbsp;<img
                src='assets/images/arrow.gif' class="navig" alt="<{$lang_modulename}>">&nbsp;<a
                href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/"><{$lang_modulename}></a></div>
    <div class="rightheader"><{$lang_modulename}></div>
    <hr style="clear: both;">
    <{if $empty == 1}>
        <div class="empty"><{$smarty.const._MD_LEXIKON_STILLNOTHINGHERE}></div>
    <{/if}>

    <div class="toprow">
        <div id="search">
            <fieldset>
                <legend>&nbsp;<{$smarty.const._MD_LEXIKON_SEARCHENTRY}>&nbsp;</legend>
                <{$searchform}>
            </fieldset>
        </div>
        <div class="inventory">
            <fieldset>
                <legend>&nbsp;<{$smarty.const._MD_LEXIKON_WEHAVE}>:&nbsp;</legend>
                <b><{$smarty.const._MD_LEXIKON_DEFS}></b><{$publishedwords}><br>
                <b><{if $multicats == 1}><{$smarty.const._MD_LEXIKON_CATS}></b><{$totalcats}><br><{/if}>
                <input class="btnDefault" type="button" value="<{$smarty.const._MD_LEXIKON_SUBMITENTRY}>"
                       onclick="location.href = 'submit.php'"><br>
                <input class="btnDefault" type="button" value="<{$smarty.const._MD_LEXIKON_REQUESTDEF}>"
                       onclick="location.href = 'request.php' ">
            </fieldset>
        </div>
    </div>
    <{if $teaser == true}>
        <div class="teaser"><{$teaser}></div>
    <{/if}>

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
    <br>
    <div class="float30">
        <fieldset>
            <legend>&nbsp;<{$smarty.const._MD_LEXIKON_RECENTENT}>&nbsp;</legend>
            <ul>
                <{foreach item=newentries from=$block.newstuff}>
                    <li>
                        <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/entry.php?entryID=<{$newentries.id}>"><{$newentries.linktext}></a>&nbsp;<{if $showdate == 1}>
                        <span style="font-size: xx-small; color: #456;">[&nbsp;<{$newentries.date}>&nbsp;]</span><{/if}>
                    </li>
                <{/foreach}>
            </ul>
        </fieldset>
    </div>

    <div class="float30">
        <fieldset>
            <legend>&nbsp;<{$smarty.const._MD_LEXIKON_POPULARENT}>&nbsp;</legend>
            <ul>
                <{foreach item=popentries from=$block2.popstuff}>
                    <li>
                        <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/entry.php?entryID=<{$popentries.id}>"><{$popentries.linktext}></a>&nbsp;<{if $showcount == 1}>
                        <span style="font-size: xx-small; color: #456;">[&nbsp;<{$popentries.counter}>&nbsp;]</span><{/if}>
                    </li>
                <{/foreach}>
            </ul>
        </fieldset>
    </div>

    <div class="float30random">
        <fieldset>
            <legend>&nbsp;<{$smarty.const._MD_LEXIKON_RANDOMTERM}>&nbsp;</legend>
            <{if $multicats == 1}>
                <{if $empty != 1}>
                    <div class="catname"><a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php?categoryID=<{$random.categoryID}>"><{$random.categoryname}></a>
                    </div>
                <{/if}>
            <{/if}>
            <div class="pad4">
                <h5 class="term"><{$microlinks}><a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/entry.php?entryID=<{$random.id}>"><{$random.term}></a>
                </h5>
                <div class="nopadding"><{$random.definition}></div>
            </div>
        </fieldset>
    </div>
    <{if $userisadmin == 1}>
        <div class="clearer2">
            <fieldset>
                <legend>&nbsp;<{$smarty.const._MD_LEXIKON_SUBANDREQ}>&nbsp;</legend>
                <div class="submission">
                    <b><{$smarty.const._MD_LEXIKON_SUB}></b>
                    <{if $wehavesubs == '0'}><{$smarty.const._MD_LEXIKON_NOSUB}><{/if}>
                    <{foreach item=subentries from=$blockS.substuff}>
                        <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/admin/entry.php?op=mod&entryID=<{$subentries.id}>"><{$subentries.linktext}></a>
                        &nbsp;
                    <{/foreach}>
                </div>
                <div class="request">
                    <b><{$smarty.const._MD_LEXIKON_REQ}></b>
                    <{if $wehavereqs == '0'}><{$smarty.const._MD_LEXIKON_NOREQ}><{/if}>
                    <{foreach item=reqentries from=$blockR.reqstuff}>
                        <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/admin/entry.php?op=mod&entryID=<{$reqentries.id}>"><{$reqentries.linktext}></a>
                        &nbsp;
                    <{/foreach}>
                </div>
            </fieldset>
        </div>
    <{else}>
        <div class="clearer2">
            <fieldset>
                <legend>&nbsp;<{$smarty.const._MD_LEXIKON_REQ}>&nbsp;</legend>
                <div class="request">
                    <b><{$smarty.const._MD_LEXIKON_REQ}></b>
                    <{if $wehavereqs == '0'}><{$smarty.const._MD_LEXIKON_NOREQ}>
                    <{else}>
                        <br>
                        <span style="font-size:80%;"><{$smarty.const._MD_LEXIKON_REQUESTSUGGEST}></span>
                        <br>
                    <{/if}>
                    <{foreach item=reqentries from=$blockR.reqstuff}>
                        <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/submit.php?suggest=<{$reqentries.id}>"><{$reqentries.linktext}></a>
                        &nbsp;
                    <{/foreach}>
                </div>
            </fieldset>
        </div>
    <{/if}>
    <{if $syndication == true}>
        <div class="rss_bottom">
            <a href="rss.php" title="recent glossary definitions"><img src="assets/images/rss.gif" ALT="RSS"></a>
        </div>
    <{/if}>
</div>
<{include file='db:system_notification_select.tpl'}>
