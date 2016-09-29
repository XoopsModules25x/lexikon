<div>
    <ul>
        <{foreach item=author from=$block.authors}>
            <li>
                <{if $block.profile}>
                    <a href="<{$xoops_url}>/modules/lexikon/profile.php?uid=<{$author.uid}>"
                       TITLE="<{$author.name}>"><{$author.name}></a>
                    (<{$author.count}>)
                <{else}>
                    <a href="<{$xoops_url}>/userinfo.php?uid=<{$author.uid}>"
                       TITLE="<{$author.name}>"><{$author.name}></a>
                    (<{$author.count}>)
                <{/if}>
            </li>
        <{/foreach}>
    </ul>
</div>
