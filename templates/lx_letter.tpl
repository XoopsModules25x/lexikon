<{* New Header block *}>
<table id="moduleheader">
    <tr>
        <td width="100%"><span class="leftheader"><a href="<{$xoops_url}>">
  <{$smarty.const._MD_LEXIKON_HOME}></a>  <img src='assets/images/arrow.gif' align='absmiddle'/> <a
                        href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/index.php"><{$lang_modulename}></a> <img
                        src='assets/images/arrow.gif' align='absmiddle'/>
                <{if $pagetype == '0'}>
                    <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php"><{$pageinitial}></a>





                                                                                                                           <{elseif $pagetype == '1'}>





                    <a href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$pageinitial}>"><{$pageinitial}></a>
                <{/if}>
   </span>
        </td>
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
                    <{if $letterlinks.total > 0}> <a style="<{if $letterlinks.linktext == $pageinitial}>color:#900<{/if}>;"; href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/letter.php?init=<{$letterlinks.id}>" title="[ <{$letterlinks.total}> ]" ><{/if}><{$letterlinks.linktext}>
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
    <{if $pagetype == '0'}>
        <h2 class="cat"><{$smarty.const._MD_LEXIKON_ALL}></h2>
        <div class="letters"><{$smarty.const._MD_LEXIKON_WEHAVE}> <{$totalentries}> <{$smarty.const._MD_LEXIKON_INALLGLOSSARIES}></div>
        <br>
        <{foreach item=eachentry from=$entriesarray.single}>
            <h4 class="term" style="clear:both;"><{$eachentry.microlinks}><a
                        href="<{$xoops_url}>/modules/<{$eachentry.dir}>/entry.php?entryID=<{$eachentry.id}>"><{$eachentry.term}></a> <{if $multicats == 1}>
                <a style='color: #456;'
                   href="<{$xoops_url}>/modules/<{$eachentry.dir}>/category.php?categoryID=<{$eachentry.catid}>">
                    [<{$eachentry.catname}>]</A><{/if}></h4>
            <div class="definition"><{$eachentry.definition}></div>
            <{if $eachentry.comments }><{$eachentry.comments}><br><{/if}>
            <br>
            <br>
        <{/foreach}>
        <div align='left'><{$entriesarray.navbar}></div>
        <div class="letters"> [ <a href='javascript:history.go(-1)'><{$smarty.const._MD_LEXIKON_RETURN}></a><b> | </b><a
                    href='./index.php'><{$smarty.const._MD_LEXIKON_RETURN2INDEX}></a> ]
        </div>
    <{elseif $pagetype == '1'}>
        <h2 class="cat"><{$firstletter}></h2>
        <div class="letters"><{$smarty.const._MD_LEXIKON_WEHAVE}> <{$totalentries}> <{$smarty.const._MD_LEXIKON_BEGINWITHLETTER}></div>
        <br>
        <{foreach item=eachentry from=$entriesarray2.single}>
            <h4 class="term" style="clear:both;"><{$eachentry.microlinks}><a
                        href="<{$xoops_url}>/modules/<{$eachentry.dir}>/entry.php?entryID=<{$eachentry.id}>"><{$eachentry.term}></a> <{if $multicats == 1}>
                <a style='color: #456;'
                   href="<{$xoops_url}>/modules/<{$eachentry.dir}>/category.php?categoryID=<{$eachentry.catid}>">
                    [<{$eachentry.catname}>]</A><{/if}></h4>
            <div class="definition"><{$eachentry.definition}></div>
            <{if $eachentry.comments }><{$eachentry.comments}><br><{/if}>
            <br>
            <br>
        <{/foreach}>
        <div align="left"><{$entriesarray2.navbar}></div>
        <div class='letters'> [ <a href='javascript:history.go(-1)'><{$smarty.const._MD_LEXIKON_RETURN}></a><b> | </b><a
                    href='./index.php'><{$smarty.const._MD_LEXIKON_RETURN2INDEX}></a> ]
        </div>
    <{/if}>
