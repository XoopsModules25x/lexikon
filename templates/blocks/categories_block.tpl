<ul>
    <{foreach item=catids from=$block.catstuff}>
        <li>
            <a href="<{$xoops_url}>/modules/<{$catids.dir}>/category.php?categoryID=<{$catids.id}>"><{$catids.linktext}></a>
            [<{$catids.total}>]
        </li>
    <{/foreach}>
</ul>
