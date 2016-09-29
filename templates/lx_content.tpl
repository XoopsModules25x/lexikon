<table id="moduleheader">
    <tr>
        <td width="100%"><span class="leftheader"><a href="<{$xoops_url}>"><{$smarty.const._MD_LEXIKON_HOME}></a> <img
                        src='assets/images/arrow.gif' align='absmiddle'/> <a
                        href="<{$xoops_url}>/modules/<{$lang_moduledirname}>/index.php"><{$lang_modulename}></a> <img
                        src='assets/images/arrow.gif'
                        align='absmiddle'/> <{$smarty.const._MD_LEXIKON_SYNDICATION}></span></td>
        <td width="100"><span class="rightheader"><{$lang_modulename}></span></td>
    </tr>
</table>

<h3 class="cat"><{$smarty.const._MD_LEXIKON_SYNDICATION}></h3>
<div class="intro"><{$introcontentsyn}></div>
<form name="<{$yform.name}>" action="" method="<{$yform.method}>" <{$yform.extra}> >
    <fieldset>
        <legend> <{$yform.title}> </legend>
        <br>
        <{*$smarty.const._MD_LEXIKON_SYNEXPLAIN}><br>*}>
        <{$smarty.const._INFO}>:<br>
        <UL>
            <LI style='list-style-type:disc;' ;><{$smarty.const._MD_LEXIKON_SYNEXPLAIN1}></LI>
            <LI style='list-style-type:disc;' ;><{$smarty.const._MD_LEXIKON_SYNEXPLAIN2}></LI>
            <LI style='list-style-type:disc;' ;><{$smarty.const._MD_LEXIKON_SYNEXPLAIN3}></LI>
        </UL>

        <table cellspacing="2" cellspacing="3">
            <P><br>
                <tr>
                    <td width="160" style="text-align: left; line-height: 200%;"><{$yform.elements.txt.caption}></td>
                    <td style="text-align: left;"><{$yform.elements.txt.body}></td>
                </tr>
                <tr>
                    <td width="160" style="line-height: 200%;">&nbsp;</td>
                    <td style="text-align: left;">
                        <{*<input type=button value="select all" onClick="javascript:this.form.txt.focus();this.form.txt.select();"  style="font-size: 12px; font-family: arial,verdana; border: 1 solid #336699;">*}>
                        <input type=button value="select"
                               onClick="this.form.txt.focus();this.form.txt.select(); document.execCommand('Copy')"
                               style="font-size: 12px; font-family: verdana,arial, sans-serif; "/>
                    </td>
                </tr>
        </table>
    </fieldset>
</form>

<P>&nbsp;<P>
<div align="center">
    <B><{$smarty.const._PREVIEW}></B><br>
    <IFRAME style="background-color: #FFFFFF;" ; src="<{$xoops_url}>/modules/<{$lang_moduledirname}>/syndication.php"
            frameborder="0" width="240" height="280" allowtransparency="true" topmargin="0"
            leftmargin="0" scrolling='no' marginwidth="0" marginheight="0"/>
    [Your user agent does not support frames or is currently configured not to display frames.]</IFRAME>
</div>
