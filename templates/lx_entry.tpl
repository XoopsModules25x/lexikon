<style type="text/css">
    <!--
    .entryfooter {
        width: 98%;
        padding: 4px;
        margin: 5px;
        border-top: 1px solid silver;
        border-bottom: 1px solid silver;
    }

    .standard {
        font-size: 11px;
        line-height: 15px;
    }

    -->
</style>
<{* needed for baloon tips*}>
<{if $balloontips}>
    <div id="bubble_tooltip">
        <div class="bubble_top"><span></span></div>
        <div class="bubble_middle"><span
                    id="bubble_tooltip_content">Content is coming here as you probably can see.</span></div>
        <div class="bubble_bottom"></div>
    </div>
<{/if}>
<table id="moduleheader">
    <tr>
        <td width="100%"><span class="leftheader"><a href="<{$xoops_url}>"><{$smarty.const._MD_LEXIKON_HOME}></a>
<img src='assets/images/arrow.gif' align='absmiddle'/>
 <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/index.php"><{$lang_modulename}></a>
 <img src='assets/images/arrow.gif' align='absmiddle'/>
  <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$thisterm.init}>"><{$thisterm.init}></a>
   <img src='assets/images/arrow.gif' align='absmiddle'/> <{$thisterm.term}></span></td>
        <td width="100"><span class="rightheader"><{$lang_modulename}></span></td>
    </tr>
</table>


<{* Alphabet block *}>
<div class="clearer">
    <div class="toprow">
        <fieldset>
            <legend><{$smarty.const._MD_LEXIKON_BROWSELETTER}></legend>
            <div class="letters">
                <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php"
                   title="[ <{$publishedwords}> ]"><{$smarty.const._MD_LEXIKON_ALL}></a> |
                <{foreach item=letterlinks from=$alpha.initial}>
                    <{if $letterlinks.total > 0}> <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$letterlinks.id}>" title="[ <{$letterlinks.total}> ]" ><{/if}><{$letterlinks.linktext}>
                    <{if $letterlinks.total > 0}></a><{/if}> |
                <{/foreach}>
                <{if $totalother > 0}><a
                        href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$smarty.const._MD_LEXIKON_OTHER}>"
                        title="[ <{$totalother}> ]"><{/if}><{$smarty.const._MD_LEXIKON_OTHER}>
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
                <legend><{$smarty.const._MD_LEXIKON_BROWSECAT}></legend>
                <table id="Lxcategory" border="0">
                    <tr>
                        <td>
                            <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php"
                               title="[ <{$publishedwords}> ]"><{$smarty.const._MD_LEXIKON_ALLCATS}></a>
                            [<{$publishedwords}>]
                        </td>
                        <!-- Start category loop -->
                        <{foreach item=catlinks from=$block0.categories}>
                        <td>
                            <{if $catlinks.image != "" && $show_screenshot == true}>
                                <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php?categoryID=<{$catlinks.id}>"
                                   target="_parent"><img
                                            src="<{$xoops_url}>/modules/<{$lang_moduledirname}>/assets/images/uploads/<{$catlinks.image}>"
                                            width="<{$logo_maximgwidth}>" align="left" class="floatLeft"
                                            alt="[<{$catlinks.name}>]&nbsp;[<{$catlinks.total}>]"/></A>
                            <{/if}>
                            <{if $catlinks.count > 0}>
                                <{if $catlinks.total > 0}><a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php?categoryID=<{$catlinks.id}>" title="[<{$catlinks.total}>]"><{/if}><{$catlinks.linktext}>
                                <{if $catlinks.total > 0}></a> <{/if}>[<{$catlinks.total}>]
                            <{/if}>
                        </td>
                        <{if $catlinks.count is div by 4}> </tr>
                    <tr> <{/if}>
                        <{/foreach}>
                        <!-- End category loop -->
        </DIV>
        </tr></table>
        </fieldset>
    <{/if}>
<{else}>
    <{if $multicats == 1}>
        <div class="clearer">
            <fieldset class="item" style="border:1px solid #778;margin:1em 0em;text-align:left;background-color:trans;">
                <legend><{$smarty.const._MD_LEXIKON_BROWSECAT}></legend>
                <div class="letters" style="margin:1em 0em;width:100%;padding:0em;text-align:center;line-height:1.3em;">
                    <{foreach item=catlinks from=$block0.categories}>
                        <{if $catlinks.image != "" && $show_screenshot == true}>
                            <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php?categoryID=<{$category.id}>"
                               target="_parent">
                                <img src="<{$xoops_url}>/modules/<{$lang_moduledirname}>/assets/images/uploads/<{$catlinks.image}>"
                                     width="<{$logo_maximgwidth}>" align="middle"
                                     alt="[<{$catlinks.total}>]"/></A>
                        <{/if}>
                        <{if $catlinks.total > 0}><a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php?categoryID=<{$catlinks.id}>" title="[<{$catlinks.total}>]"><{/if}><{$catlinks.linktext}>
                        <{if $catlinks.total > 0}></a> <{/if}>[<{$catlinks.total}>] |
                    <{/foreach}>
                    <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php"
                       title="[ <{$publishedwords}> ]"><{$smarty.const._MD_LEXIKON_ALLCATS}></a>[<{$publishedwords}>]
                </div>
            </fieldset>
        </div>
    <{/if}>
<{/if}>

<b>
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
        <div align="right" style="margin:0em 1.0em 0em 0em;"><br><span class="standard"><span style="color: #4e505c; ">
<{$smarty.const._MD_LEXIKON_SUBMITTED}>
                    <{if $showsubmitter }><{$submitter}><{/if}> <{$submittedon}><br>
                    <{$counter}> </span></span></p></div>

        <div class="entryfooter">
<span class="standard"><{$microlinksnew}>
    <{if $bookmarkme == 3}>
        &nbsp; <!-- AddThis Bookmark Button -->
        <a href="http://www.addthis.com/bookmark.php"
           onclick="addthis_url = location.href; addthis_title = document.title; return addthis_click(this);"
           target="_blank">
    <img src="assets/images/addthis_button1-bm.gif" align="absmiddle" width="125" height="16" border="0"
         alt="AddThis Social Bookmark Button"/></a>
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
        </DIV>

        <{if $bookmarkme == 2}>
            <{include file="db:lx_bookmark.tpl"}>
        <{/if}>
        <{if $tagbar}>
            <div class="letters">
                <{include file="db:lx_tag_bar.tpl"}>
            </div>
        <{/if}>

        <!-- start comments -->
        <div style="text-align: center; padding: 3px; margin: 3px;">
            <{$commentsnav}>
            <{$lang_notice}>
        </div>

        <div style="margin: 3px; padding: 3px;">
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

        <br>
        <br>
        <{include file='db:system_notification_select.tpl'}>
