<div>
    <ul>
        <{foreach item=author from=$block.authors}>
            <li>
                <{if $block.profile}>
                    <a href="<{$xoops_url}>/modules/lexikon/profile.php?uid=<{$author.uid}>"
                       TITLE="<{$author.name}>"><{$author.name}></a>
                    &nbsp;(&nbsp;<{$author.count}>&nbsp;)
                <{else}>
                    <a href="<{$xoops_url}>/userinfo.php?uid=<{$author.uid}>"
                       TITLE="<{$author.name}>"><{$author.name}></a>
                    &nbsp;(&nbsp;<{$author.count}>&nbsp;)
                <{/if}>
            </li>
        <{/foreach}>
    </ul>
</div>
