<{if $block.style == '0'}>
    <marquee onmouseover="this.stop(); " onmouseout="this.start();" direction='<{$block.direction}>'
             style='width:100%;height:160px;' bgcolor='<{$block.bgcolor}>' scrollamount='<{$block.speed}>'
             scrolldelay='<{$block.speed}>' <{if $block.alternate}>behavior='alternate'<{/if}> >
        <{foreach item=term from=$block.scrollitems}>
            <li style="display:outline; margin:5px; padding:0;list-style-type: none;list-style-position: outside;">
                <a href="<{$xoops_url}>/modules/lexikon/entry.php?entryID=<{$term.id}>"><{$term.term}></a>
                <{if $block.includedate}>
                    <div style='color: gray; font-size: 85%;'><{$term.date}></div>
                <{/if}>
                <br>
                <div><{$term.definition}></div>
            </li>
            <br>
        <{/foreach}>
        <{* <{foreach item=term from=$block.scrollitems}>
            <li style="display:outline; margin:5px; padding:0;list-style-type: none;list-style-position: outside;">
               <a href="<{$xoops_url}>/modules/lexikon/entry.php?entryID=<{$term.id}>"><{$term.term}></a>
            </li>
          <{/foreach}>*}>
    </marquee>
<{elseif $block.style=='1'}>
    <style type="text/css">
        #pscroller {
            width: 99%;
            height: 160px;
            padding: 4px;
            background-color: <{$block.bgcolor}>;
        }

        #pscroller a {
            background-color: #e6e6e6;
            display: inline;
            margin: 0;
            padding: 2px;
            text-decoration: none;
        }

        #date {
            color: gray;
            font-size: 85%;
        }

        .someclass { /*class to apply to your scroller(s) if desired*/
        }
    </style>
    <script type="text/javascript">
        var lexikoncontent = new Array()
        $i = 0;
        <{foreach item=term from=$block.scrollitems}>
        lexikoncontent[++$i] = '<{$term.url}><{$term.term}></A><{if $block.includedate}><div id="date"><{$term.date}></div><{/if}><br><div><{$term.definition}></div>'
        <{/foreach}>
    </script>
    <script type="text/javascript" src="<{$xoops_url}>/modules/lexikon/assets/js/pausescroller.js"></script>
    <script type="text/javascript">
        //new pausescroller(name_of_message_array, CSS_ID, CSS_classname, pause_in_miliseconds)
        new pausescroller(lexikoncontent, "pscroller", "someclass", <{$block.speed}>000)
    </script>
<{else}>
    <style type="text/css">
        #domcontent {
            width: 98%;
            height: 160px;
            padding: 4px;
            background-color: <{$block.bgcolor}>;
        }

        #domcontent a {
            background-color: #e6e6e6;
            display: inline;
            margin: 0;
            padding: 2px;
            text-decoration: none;
        }

        #domcontent div { /*IE6 bug fix when text is bold and fade effect (alpha filter) is enabled. Style inner DIV with same color as outer DIV*/
            background-color: <{$block.bgcolor}>;
        }

        #date {
            color: gray;
            font-size: 85%;
        }

        .someclass { /*class to apply to your scroller(s) if desired*/
        }
    </style>
    <script type="text/javascript">
        var domcontent = new Array()
        $i = 0;
        <{foreach item=term from=$block.scrollitems}>
        domcontent[++$i] = '<{$term.url}><{$term.term}></A><{if $block.includedate}><div id="date"><{$term.date}></div><{/if}><br><div><{$term.definition}></div>'
        <{/foreach}>
    </script>
    <script type="text/javascript" src="<{$xoops_url}>/modules/lexikon/assets/js/domticker.js"></script>
    <script type="text/javascript">
        //new domticker(name_of_message_array, CSS_ID, CSS_classname, pause_in_miliseconds, optionalfadeswitch)
        new domticker(domcontent, "domcontent", "someclass", <{$block.speed}>000, "fadeit")
    </script>
<{/if}>
