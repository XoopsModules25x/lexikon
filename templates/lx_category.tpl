<style type="text/css">
    h2.cat {
        text-align: center !important;
        font-size: 14px !important;
        font-style: bold !important;
        margin: 0;
        color: #2F5376;
    }

    h3.cat {
        text-align: left;
        clear: both !important;
        margin: 1em 1em 0.5em 1em !important;
        padding: 0.3em 0 !important;
        border-top: 1px dotted #cfcfcf !important;
        font-size: 14px !important;
        color: #2F5376 !important;
        background-color: trans !important;
    }
</style>

<{* New Header block *}>
<table id="moduleheader">
    <tr>
        <td width="100%"><span class="leftheader"><a href="<{$xoops_url}>"><{$smarty.const._MD_LEXIKON_HOME}></a>
 <img src='assets/images/arrow.gif' align='absmiddle'/>
  <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/index.php"><{$lang_modulename}></a>   <img
                        src='assets/images/arrow.gif' align='absmiddle'/>
                <{if $pagetype == '0'}><a
                    href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php"><{$smarty.const._MD_LEXIKON_ALLCATS}></a>
                <{elseif $pagetype == '1'}><{$singlecat.name}><{/if}></span>
        </td>
        <td width="100"><span class="rightheader"><{$lang_modulename}></span>
        </td>
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
                                            src="<{$xoops_url}>/uploads/<{$lang_moduledirname}>/categories/images/<{$catlinks.image}>"
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
            <fieldset class="item" style="border:1px solid #778;margin:1em 0;text-align:left;background-color:trans;">
                <legend><{$smarty.const._MD_LEXIKON_BROWSECAT}></legend>
                <div class="letters" style="margin:1em 0;width:100%;padding:0;text-align:center;line-height:1.3em;">
                    <{foreach item=catlinks from=$block0.categories}>
                        <{if $catlinks.image != "" && $show_screenshot == true}>
                            <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php?categoryID=<{$category.id}>"
                               target="_parent">
                                <img src="<{$xoops_url}>/uploads/<{$lang_moduledirname}>/categories/images/<{$catlinks.image}>"
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

<{if $pagetype == '0'}>
<b>
    <h2 class="cat"><{$smarty.const._MD_LEXIKON_ALLCATS}></h2>

    <{foreach item=eachcat from=$catsarray.single}>
        <h3 class="cat">
            <{if $eachcat.image != "" && $show_screenshot == '1'}>
                <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/category.php?categoryID=<{$eachcat.id}>"
                   target="_parent">
                    <img src="<{$xoops_url}>/uploads/lexikon/categories/images/<{$eachcat.image}>"
                         width="<{$imgcatwd}>" align="bottom" vspace="2" hspace="2" border="0"
                         alt="[<{$eachcat.name}>]"/></A>
            <{/if}>

            <a href="<{$xoops_url}>/modules/<{$eachcat.dir}>/category.php?categoryID=<{$eachcat.id}>"><{$eachcat.name}></a>
        </h3>
        <div class="introcen"><{$eachcat.description}><br></div>
        <div class="letters"><{$smarty.const._MD_LEXIKON_WEHAVE}> <{$eachcat.total}> <{$smarty.const._MD_LEXIKON_ENTRIESINCAT}></div>
        <br>
    <{/foreach}>

    <div align='left'><{$catsarray.navbar}></div>
    <p>
    <div align='center'> [ <a href='javascript:history.go(-1)'><{$smarty.const._MD_LEXIKON_RETURN}></a><b> | </b><a
                href='./index.php'><{$smarty.const._MD_LEXIKON_RETURN2INDEX}></a> ]
    </div>

    <{* syndication *}>
    <{if $syndication == true}>
        <div align="center" style="padding: 4px;"><br><br>
            <a href="rss.php" title="recent entries"><img src="assets/images/rss.gif" border="0"/></a>
        </div>
    <{/if}>

    <{elseif $pagetype == '1'}>
    <b>

        <h2 class="cat"><{$singlecat.name|spacify}></h2>
        <div class="introcen">
            <{if $singlecat.image != "" && $show_screenshot == '1'}>
        <img src="<{$xoops_url}>/uploads/lexikon/categories/images/<{$singlecat.image}>" width="<{$imgcatwd}>"
             align="center" vspace="2" hspace="2" border="0" alt="[<{$singlecat.name}>]"/>
            <b><{/if}>
            <{$singlecat.description}></div>

        <div class="letters"><{$smarty.const._MD_LEXIKON_WEHAVE}> <{$singlecat.total}> <{$smarty.const._MD_LEXIKON_ENTRIESINCAT}>
        </div>
        <br>

        <{foreach item=eachentry from=$entriesarray.single}>
            <h4 style="clear:both;"><{$eachentry.microlinks}><a
                        href="<{$xoops_url}>/modules/<{$eachentry.dir}>/entry.php?entryID=<{$eachentry.id}>"><{$eachentry.term}></a>
            </h4>
            <div class="definition"><{$eachentry.definition}></div>
            <{if $eachentry.comments }><{$eachentry.comments}><br><{/if}>
            <br>
        <{/foreach}>

        <div align='left'><{$entriesarray.navbar}></div>
        <p>
        <div align='center'> [ <a href='javascript:history.go(-1)'><{$smarty.const._MD_LEXIKON_RETURN}></a><b> | </b><a
                    href='./index.php'><{$smarty.const._MD_LEXIKON_RETURN2INDEX}></a> ]
        </div>

        <{* syndication *}>
        <{if $syndication == true}>
            <div align="center" style="padding: 4px;"><br><br>
                <a href="rss.php?categoryID=<{$singlecat.id}>" title="Recent terms in this category"><img
                            src="assets/images/rss.gif" border="0"/></a>
            </div>
        <{/if}>

        <{/if}>
        <br>
        <br>
        <{include file='db:system_notification_select.tpl'}>
