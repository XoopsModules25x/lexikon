<{if $block.layout == 1}>
<table style="border: 1px solid #ffffff; padding: 2px 4px; text-align: center;">
    <tbody>
    <tr>
        <{/if}>
        <{foreach item=letterlinks from=$block.initstuff}>
        <{if $block.layout == 1}>
        <td style="width:40px;"><{/if}>
            <{if $letterlinks.total}><a
                    href="<{$xoops_url}>/modules/<{$block.moduledirname}>/letter.php?init=<{$letterlinks.id}>"
                    title="[ <{$letterlinks.total}> ]"><{/if}><{$letterlinks.linktext}>&nbsp;<{if $letterlinks.total}></a><{/if}>
            <{if $block.layout == 1}>
        </td><{if $letterlinks.count is div by $block.number }></tr>
    <tr> <{/if}>
        <{/if}>
        <{/foreach}><{*$letterlinks.dir*}>
        <{if $block.layout == 1}>
    </tr>
    </tbody>
</table>
<{/if}>
