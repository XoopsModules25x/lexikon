<{if $block.marquee == '1'}>
    <marquee onmouseover="this.stop();" onmouseout="this.start();" direction="<{$block.direction}>"
             style="width:100%;height:160px;" bgcolor="<{$block.bgcolor}>" scrollamount="<{$block.speed}>"
             scrolldelay=5 <{if $block.alternate}>behavior="alternate"<{/if}>>
        <ul>
            <{foreach item=popentries from=$block.popstuff}>
                <li style="display:outline; margin:0; padding:0;list-style-type: none;list-style-position: outside;line-height:12px;">
                    <a href="<{$xoops_url}>/modules/<{$popentries.dir}>/entry.php?entryID=<{$popentries.id}>"><{$popentries.linktext}></a>
                    <{if $block.showcounter}><span style='color: gray; font-size: 85%;'>[<{$popentries.counter}>
                        ]</span><{/if}>
                </li>
                <br>
            <{/foreach}>
        </ul>
    </marquee>
<{else}>
    <ul>
        <{foreach item=popentries from=$block.popstuff}>
            <li>
                <a href="<{$xoops_url}>/modules/<{$popentries.dir}>/entry.php?entryID=<{$popentries.id}>"><{$popentries.linktext}></a><{if $block.showcounter}> [<{$popentries.counter}>] <{/if}>
            </li>
        <{/foreach}>
    </ul>
<{/if}>
