<{* needed for baloon tips*}>
<{if $balloontips}>
    <div id="bubble_tooltip">
        <div class="bubble_top"><span></span></div>
        <div class="bubble_middle"><span id="bubble_tooltip_content">Content is coming here as you probably can see.</span></div>
        <div class="bubble_bottom"></div>
    </div>
<{/if}>
<div id="moduleheader">
    <div class="leftheader"><{$smarty.const._MD_LEXIKON_HOME}>&nbsp;<img src='assets/images/arrow.gif' style="vertical-align:middle;" alt="<{$lang_modulename}>">&nbsp;
        <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/"><{$lang_modulename}></a>&nbsp;<img src='assets/images/arrow.gif' style="vertical-align:middle;" alt="<{$thisterm.init}>">&nbsp;
        <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$thisterm.init}>"><{$thisterm.init}></a>&nbsp;<img src='assets/images/arrow.gif' style="vertical-align:middle;" alt="<{$thisterm.term}>">&nbsp;<{$thisterm.term}></div>
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

    <{if $multicats == 1}>
        <div class="catback"><b><{$smarty.const._MD_LEXIKON_ENTRYCATEGORY}></b>
            <a href="<{$xoops_url}>/modules/<{$thisterm.dir}>/category.php?categoryID=<{$thisterm.categoryID}>"><{$thisterm.catname}></a>
        </div>
    <{/if}>

    <h4 class="term"><{$microlinks}><{$thisterm.term}></h4>
    <b><{$smarty.const._MD_LEXIKON_ENTRYDEFINITION}></b>
    <div class="small"><{$thisterm.definition}></div>

    <{if $thisterm.ref}>
        <div class="small"><b><{$smarty.const._MD_LEXIKON_ENTRYREFERENCE}></b><{$thisterm.ref}></div>
    <{/if}>

    <{if $thisterm.url}>
        <div class="xsmall"><b><{$smarty.const._MD_LEXIKON_ENTRYRELATEDURL}></b><{$thisterm.url}></div>
    <{/if}>

    <div class="clearer">
        <div style="margin:0 1.0em 0 0; text-align:right;"><br><span class="standard"><span style="color: #4e505c; ">
<{$smarty.const._MD_LEXIKON_SUBMITTED}>
                    <{if $showsubmitter }><{$submitter}><{/if}> <{$submittedon}><br>
                    <{$counter}> </span></span></p></div>

        <div class="entryfooter">
<span class="standard"><{$microlinksnew}>
    <{if $bookmarkme == 3}>
        &nbsp; <!-- AddThis Bookmark Button -->
        <a href="http://www.addthis.com/bookmark.php"
           onclick="addthis_url = location.href; addthis_title = document.title; return addthis_click(this);"
           target="_blank"><img src="assets/images/addthis_button1-bm.gif" style="vertical-align: middle; width:125px; height:16px;" alt="AddThis Social Bookmark Button"></a>
        <script type="text/javascript">var addthis_pub = 'JJXUY2C9CQIWTKI1';</script>
        <script type="text/javascript" src="http://s9.addthis.com/js/widget.php?v=10"></script>

<{elseif $bookmarkme == 4}>
    &nbsp; <!-- AddThis Bookmark dropdown -->


        <script type="text/javascript">
      addthis_url = location.href;
      addthis_title = document.title;
      addthis_pub = 'JJXUY2C9CQIWTKI1';
    </script>
        <script type="text/javascript" src="http://s7.addthis.com/js/addthis_widget.php?v=12"></script>
    <{/if}>
</span>
        </div>
        <{if $bookmarkme == 2}>
            <{include file="db:lx_bookmark.tpl"}>
        <{/if}>
        <{if $tagbar}>
            <div class="letters">
                <{include file="db:lx_tag_bar.tpl"}>
            </div>
        <{/if}>

        <!-- start comments -->
        <div class="pad5">
            <{$commentsnav}>
            <{$lang_notice}>
        </div>

        <div class="pad5">
            <!-- start comments loop -->
            <{if $comment_mode == "flat"}>
                <{include file="db:system_comments_flat.tpl"}>
            <{elseif $comment_mode == "thread"}>
                <{include file="db:system_comments_thread.tpl"}>
            <{elseif $comment_mode == "nest"}>
                <{include file="db:system_comments_nest.tpl"}>
            <{/if}>
            <!-- end comments loop -->
            <!-- end comments -->
        </div>
    </div>
</div>
<{include file='db:system_notification_select.tpl'}>
