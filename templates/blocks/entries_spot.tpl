<{if $block.display != 0}>
    <{if $block.verticaltemplate == 1}>
        <{if $block.showpicask == 1}>
            <{if $block.catimage}>
                <div style="float: left; width: 80px; margin-right: 10px; border: 1px solid black; "><img
                            src="<{$xoops_url}>/uploads/lexikon/categories/images/<{$block.catimage}>" width="80"
                            ALT="<{$block.name}>"/></div>
            <{/if}>
        <{/if}>
        <h3 style="margin: 6px 0;"><{*$block.userlinks*}><a
                    href="<{$xoops_url}>/modules/<{$block.moduledir}>/entry.php?entryID=<{$block.termID}>"
                    TITLE="<{$block.title}>"><{$block.title}></a></h3>
        <br>
        <{$smarty.const._MB_LEXIKON_CATEGORY}>
        <a href="<{$xoops_url}>/modules/lexikon/category.php?categoryID=<{$block.catID}>"
           TITLE="<{$block.name}>:<{$block.cattitle}>"><{$block.name}></a>
        <br>
        <{if $block.showbylineask == 1}><{$smarty.const._MB_LEXIKON_BY}><{$block.authorname}><{/if}>
        <{if $block.showdateask == 1}><span style="font-size: x-small; margin: 0 0 6px 0;"><{$block.date}></span><{/if}>
        <{if $block.showstatsask == 1}><span
                style="font-size: x-small; margin-top: 4px;"><{$smarty.const._MB_LEXIKON_HIT}><{$block.hits}> <{$block.comments}>
            <br>
            <br>
            </span><{/if}>
        <div style="margin: 8px 0 2px 0;border-bottom: solid 1px #ddd;">
            <{$block.introtext}>
            <br><br></div>
        <div style="font-size: 12px; font-weight: bold; padding: 2px 6px; margin: 6px 0 0 0; border-bottom: solid 1px #ddd;float: left; width: 49%;">
            <img src="<{$xoops_url}>/assets/images/pointer.gif"
                 alt=""/> <a
                    href="<{$xoops_url}>/modules/lexikon/category.php?categoryID=<{$block.catID}>"
                    TITLE="<{$block.name}>: <{$block.cattitle}>"><{$smarty.const._MB_LEXIKON_MOREHERE}></a></div>
        <div style="clear:both; padding-left: 5px;">
            <ul style="list-style: disc outside;">
                <{foreach item=morelinks from=$block.links}>
                    <li style="list-style: disc outside; margin: 5px; padding-left: 5px;"> [<{$morelinks.subdate}>] <a
                                href="<{$xoops_url}>/modules/<{$block.moduledir}>/entry.php?entryID=<{$morelinks.id}>"
                                TITLE="<{$morelinks.title}>"><{$morelinks.head}></a></li>
                <{/foreach}>
            </ul>
        </div>
    <{elseif verticaltemplate == 0}>
        <div style="float:left; width: 48%; margin-right: 8px;">
            <{if $block.showpicask == 1}>
                <{if $block.catimage}>
                    <div style="float: left; width: 80px; margin: 10px 10px 0 2px; border: 1px solid black; "><img
                                src="<{$xoops_url}>/uploads/lexikon/categories/images/<{$block.catimage}>"
                                width="80" ALT="<{$block.name}>"/></div>
                <{/if}>
            <{/if}>
            <h3 style="margin: 6px 0;"><a
                        href="<{$xoops_url}>/modules/<{$block.moduledir}>/entry.php?entryID=<{$block.termID}>"
                        TITLE="<{$block.title}>"><{$block.title}></a></h3>
            <br><{$smarty.const._MB_LEXIKON_CATEGORY}> <a
                    href="<{$xoops_url}>/modules/lexikon/category.php?categoryID=<{$block.catID}>"
                    TITLE="<{$block.name}>"><{$block.name}></a>
            <br><{if $block.showdateask == 1}><span
                    style="font-size: x-small; margin: 0 0 6px 0;"><{$block.date}></span><{/if}>
            <{if $block.showbylineask == 1}><{$smarty.const._MB_LEXIKON_BY}><{$block.authorname}><{/if}>
            <{if $block.showstatsask == 1}><span
                    style="font-size: x-small; margin-top: 4px;"><{$smarty.const._MB_LEXIKON_HIT}><{$block.hits}> <{$block.comments}></span><{/if}>
            <br>
            <div style="margin: 20px 2px 2px 20px; clear: both;"><{$block.introtext}></div>
        </div>
        <div style="clear:right; float:left; padding-left: 5px;width: 48%;">
            <div style="font-size: 12px; font-weight: bold; padding: 1px 1px; margin: 0 0 0 0; border-bottom: solid 1px #ddd;">
                <img src="<{$xoops_url}>/assets/images/pointer.gif" alt=""/> <a
                        href="<{$xoops_url}>/modules/lexikon/category.php?categoryID=<{$block.catID}>"
                        TITLE="<{$block.name}>"><{$smarty.const._MB_LEXIKON_MOREHERE}></a></div>
            <ul style="list-style: disc outside;">
                <{foreach item=morelinks from=$block.links}>
                    <li style="list-style: disc outside; margin-left: 5px; padding-left: 5px;"> [<{$morelinks.subdate}>]
                        <a
                                href="<{$xoops_url}>/modules/<{$block.moduledir}>/entry.php?entryID=<{$morelinks.id}>"
                                TITLE="<{$morelinks.title}>"><{$morelinks.head}></a></li>
                <{/foreach}>
            </ul>
        </div>
        <div style="height: 0; clear: both;"></div>
    <{/if}>
<{elseif $block.display == 0}>
    <div style="font-size: 12px; font-weight: bold; background-color: #ccc; padding: 2px 6px; margin: 0;"><{$smarty.const._MB_LEXIKON_NOTHINGYET}></div>
<{/if}>
