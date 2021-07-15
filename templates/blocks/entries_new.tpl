<{if $block.marquee == '1'}>
    <marquee onmouseover="this.stop();" onmouseout="this.start();" direction="<{$block.direction}>"
             style="width:100%;height:160px;" bgcolor="<{$block.bgcolor}>" scrollamount="<{$block.speed}>"
             scrolldelay=5 <{if $block.alternate}>behavior="alternate"<{/if}>>
        <ul>
            <{foreach item=newentries from=$block.newstuff}>
                <li style="display:outline; margin:0; padding:0;list-style: none outside;">
                <a href="<{$xoops_url}>/modules/<{$newentries.dir}>/entry.php?entryID=<{$newentries.id}>"><{$newentries.linktext}></a>
                &nbsp;
                <{if $block.showdate}>
                    <div style='color: gray; font-size: 85%;'>[&nbsp;<{$newentries.date}>&nbsp;]</div>
                    </li>
                <{/if}>
                </li>
                <br>
            <{/foreach}>
        </ul>
    </marquee>
<{else}>
    <ul>
        <{foreach item=newentries from=$block.newstuff}>
            <li>
                <a href="<{$xoops_url}>/modules/<{$newentries.dir}>/entry.php?entryID=<{$newentries.id}>"><{$newentries.linktext}></a><{if $block.showdate}>&nbsp;[&nbsp;<{$newentries.date}>&nbsp;]<{/if}>
            </li>
        <{/foreach}>
    </ul>
<{/if}>
