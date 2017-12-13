<div id="moduleheader">
    <div class="leftheader"><{$smarty.const._MD_LEXIKON_HOME}><img src='assets/images/arrow.gif' style="vertical-align:middle;" alt="<{$lang_modulename}>">&nbsp;<a
                href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/index.php"><{$lang_modulename}></a>&nbsp;<img
                src='assets/images/arrow.gif' style="vertical-align:middle;" alt="<{$lang_modulename}>">&nbsp;<{$smarty.const._MD_LEXIKON_SEARCHHEAD}></div>
    <div class="rightheader"><{$lang_modulename}></div>
    <hr style="clear: both;">

    <h2 class="cat"><{$smarty.const._MD_LEXIKON_SEARCHHEAD}></h2>
    <p class="intro"><{$intro}></p>
    <div id="toprow">
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

    <div class="clearer2">
        <{foreach item=eachresult from=$resultset.match}>
            <h4><img src="<{$xoops_url}>/modules/<{$eachresult.dir}>/assets/images/lx.png">&nbsp;<{$eachresult.microlinks}>
                &nbsp;<a href="<{$xoops_url}>/modules/<{$eachresult.dir}>/entry.php?entryID=<{$eachresult.id}><{if $highlight == 1}><{$eachresult.keywords}><{/if}>"><{$eachresult.term}></a><{if $multicats == 1}>
                <a href="<{$xoops_url}>/modules/<{$eachresult.dir}>/category.php?categoryID=<{$eachresult.categoryID}>">
                    [&nbsp;<{$eachresult.catname}>&nbsp;]</a><{/if}></h4>
            <div class="result"><{$eachresult.definition}></div>
            <{if $eachresult.ref}>
                <div class="result"><{$eachresult.ref}></div><{/if}>
        <{/foreach}>
        <div class="search_abc_l"></div>
        <div class="search_abc_c"></div>
        <div class="search_abc_r"><{$resultset.navbar}></div>
    </div>
