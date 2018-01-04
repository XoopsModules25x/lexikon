<?php
/**
 * $Id: main.php v 1.0 8 May 2004 hsalazar Exp $
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */
// Module Info
// The name of this module
global $xoopsModule;
define('_MI_LEXIKON_MD_NAME', 'Lexikon');
// A brief description of this module
define('_MI_LEXIKON_MD_DESC', 'Ein Multikategorie-Glossar');
// Sub menus in main menu block
define('_MI_LEXIKON_SUB_SMNAME0', 'Administration');
define('_MI_LEXIKON_SUB_SMNAME1', 'Eintrag einschicken');
define('_MI_LEXIKON_SUB_SMNAME2', 'Definition anfordern');
define('_MI_LEXIKON_SUB_SMNAME3', 'Definition suchen');
define('_MI_LEXIKON_SUB_SMNAME4', 'neuer Eintrag');
define('_MI_LEXIKON_SUB_SMNAME6', 'Autorenliste');
define('_MI_LEXIKON_SUB_SMNAME7', 'Webmastercontent');
// module option
$cf = 1;
define('_MI_LEXIKON_MULTICATS', "$cf. Ben&ouml;tigen Sie Glossar-Kategorien?");
define('_MI_LEXIKON_MULTICATSDSC', "Falls 'Ja' k&ouml;nnen diverse Kategorien benutzt werden, ansonsten gibt es nur eine automatische Kategorie.");
define('_MI_LEXIKON_ALLOWSUBMIT', "$cf. D&uuml;rfen User Eintr&auml;ge einschicken?");
define('_MI_LEXIKON_ALLOWSUBMITDSC', "Falls 'Ja', haben die User Zugriff auf das Absendeformular");
$cf++;
define('_MI_LEXIKON_CATSINMENU', "$cf. Sollen die Kategorien im Men&uuml; angezeigt werden?");
define('_MI_LEXIKON_CATSINMENUDSC', "W&auml;hlen Sie 'Ja', falls Sie Link zu Kategorien im Hauptmen&uuml; haben wollen.");
define('_MI_LEXIKON_ANONSUBMIT', "$cf. D&uuml;rfen G&auml;ste Eintr&auml;ge einschicken?");
define('_MI_LEXIKON_ANONSUBMITDSC', "Falls 'Ja', haben G&auml;ste Zugriff auf das Absendeformular");
$cf++;
define('_MI_LEXIKON_DATEFORMAT', "$cf. Welches Format soll das Datum haben?");
define('_MI_LEXIKON_DATEFORMATDSC', "Benutzt den letzten Teil von language/english/global.php um die Datumsanzeige zu bestimmen. Beispiel: 'd-M-Y H:i' wird zu '23-Mar-2011 22:35'");
define('_MI_LEXIKON_ALLOWREQ', "$cf. D&uuml;rfen Gäste Eintr&auml;ge angefragen ?");
define('_MI_LEXIKON_ALLOWREQDSC', "Falls 'Ja', haben auch Gauml;ste Zugriff auf das Anfrageformular.");
$cf++;
define('_MI_LEXIKON_PERPAGE', "$cf. Anzahl der Eintr&auml;ge pro Seite (Admin-Seite)?");
define('_MI_LEXIKON_PERPAGEDSC', 'Anzahl der Eintr&auml;ge die auf einmal auf der Admin-Seite angezeigt werden.');
$cf++;
define('_MI_LEXIKON_PERPAGEINDEX', "$cf. Anzahl der Eintr&auml;ge pro Seite (User-Seite)?");
define('_MI_LEXIKON_PERPAGEINDEXDSC', 'Anzahl der Eintr&auml;ge die auf jeder Seite der User-Seite angezeigt werden.');
$cf++;
define('_MI_LEXIKON_BLOCKSPERPAGE', "$cf. Anzahl der Eintr&auml;ge in den Blocks auf der Startseite?");
define('_MI_LEXIKON_BLOCKSPERPAGEDSC', 'Anzahl der Eintr&auml;ge die in jedem Blocks auf der Startseite gezeigt werden.');
$cf++;
define('_MI_LEXIKON_AUTOAPPROVE', "$cf. Eintr&auml;ge automatisch freigeben?");
define('_MI_LEXIKON_AUTOAPPROVEDSC', "Falls 'Ja' werden die Eintr&auml;ge automatisch freigegeben.");
$cf++;
define('_MI_LEXIKON_ALLOWADMINHITS', "$cf. Sollen Admin-Aufrufe mitgez&auml;hlt werden?");
define('_MI_LEXIKON_ALLOWADMINHITSDSC', "Falls 'Ja', wird sich der Z&auml;hler auch bei Admin-Aufrufen erh&ouml;hen.");
$cf++;
define('_MI_LEXIKON_MAILTOADMIN', "$cf. E-Mail an Admin bei jeder neuen Einsendung?");
define('_MI_LEXIKON_MAILTOADMINDSC', "Falls 'Ja', wird der Admin bei jeder neuen Einsendung eine E-Mail erhalten.");
$cf++;
define('_MI_LEXIKON_MAILTOSENDER', "$cf. E-Mail an Einsender bei jeder neuen Einsendung, Anforderung oder Änderung?");
define('_MI_LEXIKON_MAILTOSENDERDSC', "Falls 'Ja', wird der Benutzer bei jeder neuen Einsendung eine Versand-Bestätigung erhalten. Falls `Benachrichtigen` ausgewählt wurde, erhält der Einsender eine weitere E-Mail nach der Freischaltung.");
$cf++;
define('_MI_LEXIKON_RANDOMLENGTH', "$cf. L&auml;nge der anzuzeigenden Zeile bei zuf&auml;lligen Definitionen?");
define('_MI_LEXIKON_RANDOMLENGTHDSC', 'Wieviele Zeichen sollen bei den zuf&auml;lligen Definitionen angezeigt werden? Gilt f&uuml;r die Index-Seite und f&uuml;r den Block (Vorgabe: 150 Zeichen)');
$cf++;
define('_MI_LEXIKON_LINKTERMS', "$cf. Links anzeigen zu anderen Begriffen in den Definitionen?");
define('_MI_LEXIKON_LINKTERMSDSC', "Falls 'Ja' wird in den Definitionen automatisch zu anderen Eintr&auml;gen im Glossar verlinkt.");
$cf++;
define('_MI_LEXIKON_FORM_OPTIONS', "$cf. Eingabe Optionen");
define('_MI_LEXIKON_FORM_OPTIONSDSC', 'Hier kann gew&auml;hlt werden welcher Editor zur Eingabe verwendet wird. Wurde kein spezieller Editor installiert, kann nur kompakt oder DHTML gew&auml;hlt werden. <em>Der Editor muss auf der Site im Verzeichnis class/xoopseditor installiert sein.</em>');
$cf++;
define('_MI_LEXIKON_EDIGUEST', "$cf. Eingabe Optionen für Einsendungen");
define('_MI_LEXIKON_EDIGUESTDSC', 'Sollen Benutzer und Gäste Editoren für Einsendungen verwenden dürfen?');
define('_MI_LEXIKON_DISPPROL', "$cf. Zeige Submitter bei Eintrag?");
define('_MI_LEXIKON_DISPPROLDSC', "Falls 'Ja' wird  der Autor im Footer des Eintrags gezeigt.");
$cf++;
define('_MI_LEXIKON_HEADER', "$cf. Hauptseite - einleitender Text:");
define('_MI_LEXIKON_HEADERDSC', 'Hier kann für den Modulheader ein Text oder Javascript Code eingegeben werden (HTML ist erlaubt).');
$cf++;
define('_MI_LEXIKON_AUTHORPROFILE', "$cf. Autoren-Profil verwenden?");
define('_MI_LEXIKON_AUTHORPROFILEDSC', "Falls 'Ja', wird der Benutzername mit dem Glossar-Profil des Autors verlinkt und ein Link zur Autorenliste erscheint im Men&uuml;.");
$cf++;
define('_MI_LEXIKON_SHOWDAT', "$cf. Zeige Datum im Block der neuesten Eintr&auml;ge?");
define('_MI_LEXIKON_SHOWDATDSC', "Falls 'Ja' wird auf der Startseite das Datum bei den neuesten Eintr&auml;gen gezeigt.");
$cf++;
define('_MI_LEXIKON_SHOWCTR', "$cf. Zeige Counter im Block der beliebtesten Eintr&auml;ge?");
define('_MI_LEXIKON_SHOWCTRDSC', "Falls 'Ja' wird der Z&auml;hler auf der Startseite bei popul&auml;rsten Eintr&auml;gen gezeigt.");
$cf++;
define('_MI_LEXIKON_CAPTCHA', "$cf. Captcha f&uuml;r Einsendungen verwenden?");
define('_MI_LEXIKON_CAPTCHADSC', 'Xoops Frameworks wird benötigt.');
$cf++;
define('_MI_LEXIKON_KEYWORDS_HIGH', "$cf. Suchwörter hervorheben ?");
define('_MI_LEXIKON_KEYWORDS_HIGHDSC', ' Bei Ja, werden Suchwörter in den Definitionen hervorgehoben');
$cf++;
define('_MI_LEXIKON_BOOKMARK_ME', "$cf. Zeige social Bookmarks ?");
define('_MI_LEXIKON_BOOKMARK_MEDSC', 'Die Icons sind auf der Seite der Eintr&auml;ge zu sehen.');
$cf++;
define('_MI_LEXIKON_METANUM', "$cf. Maximale Anzahl an Meta Keywords die automatisch generiert werden?");
define('_MI_LEXIKON_METANUMDSC', 'Hier ihre Anzahl der zu generierenden meta-Keywörter wählen. Die Keywörter müssen mindestens so lang sein wie das xoops_keywords_limit. <BR> Bei einem Wert von Null 0, werden die Keywörter der site verwendet.');
define('_MI_LEXIKON_METANUM_0', '0');
define('_MI_LEXIKON_METANUM_5', '5');
define('_MI_LEXIKON_METANUM_10', '10');
define('_MI_LEXIKON_METANUM_20', '20');
define('_MI_LEXIKON_METANUM_30', '30');
define('_MI_LEXIKON_METANUM_40', '40');
define('_MI_LEXIKON_METANUM_50', '50');
define('_MI_LEXIKON_METANUM_60', '60');
define('_MI_LEXIKON_METANUM_70', '70');
define('_MI_LEXIKON_METANUM_80', '80');
$cf++;
define('_MI_LEXIKON_USESHOTS', "$cf. Thumbnails als Kategoriebilder:");
define('_MI_LEXIKON_USESHOTSDSC',
       'Unterst&uuml;tzte Dateitypen: JPG, GIF, PNG.<br>Thumbnails werden zur Darstellung der Kategoriebilder benutzt. Einstellung `Nein` bewirkt, dass keine Kategorienbilder dargestellt werden.<br> <em>Das Uploadverzeichnis für Kategoriebilder ist uploads/lexikon/categories/images</em>');
$cf++;
define('_MI_LEXIKON_LOGOWIDTH', "$cf. Maximale Breite der Kategoriebilder im Menü:");
define('_MI_LEXIKON_LOGOWIDTHDSC', 'default:20px');
$cf++;
define('_MI_LEXIKON_IMCATWD', "$cf. Breite der Kategoriebilder bei Kategorie-ansicht:");
define('_MI_LEXIKON_IMCATWDDSC', 'Breite des Logos wenn einzel-Kategorien betrachtet werden. (default:50px)');
$cf++;
define('_MI_LEXIKON_RSS', "$cf. D&uuml;rfen G&auml;ste RSS Syndikation benutzen?");
define('_MI_LEXIKON_RSSDSC', 'Wenn diese Option gewählt wird, sind die Inhalte für Benutzer und Gäste abrufbar. Bei `Nein` haben nur Benutzer Zugang zur Syndikation.');
$cf++;
define('_MI_LEXIKON_SYNDICATION', "$cf. Webmastercontent Syndikation verwenden?");
define('_MI_LEXIKON_SYNDICATIONDSC', 'Wenn diese Option gewählt wird, haben Benutzer und Gäste Zugang zum WebmasterContent.');
// new configs in version 1.52
$cf++;
define('_MI_LEXIKON_IMGUPLOADWD', "$cf. Maximale Höhe/Breite Bildupload");
define('_MI_LEXIKON_IMGUPLOADWD_DESC', 'Definieren Sie die maximale Höhe/Breite in Pixel, die ein Bild beim Hochladen haben darf');
$cf++;
define('_MI_LEXIKON_IMGUPLOADSIZE', "$cf. Maximale Größe Bildupload");
define('_MI_LEXIKON_IMGUPLOADSIZE_DESC', 'Definieren Sie die maximale Größe in Bytes (10485760 = 1 MB), die ein Bild beim Hochladen haben darf');
// end new configs in 1.52

// bookmarks
define('_MI_LEXIKON_ADDTHIS1', 'Addthis Popup Fenster');
define('_MI_LEXIKON_ADDTHIS2', 'Addthis dropdown Auswahlbox');
// linkterms
define('_MI_LEXIKON_POPUP', 'Popup Fenster');
define('_MI_LEXIKON_TOOLTIP', 'Tooltips');
define('_MI_LEXIKON_BUBBLETIPS', 'Bubble Tooltips');
define('_MI_LEXIKON_SHADOWTIPS', 'Shadow Tooltips');
// Names of admin menu items
define('_MI_LEXIKON_ADMENU0', 'Main');
define('_MI_LEXIKON_ADMENU1', 'Index');
define('_MI_LEXIKON_ADMENU2', 'Kategorien');
define('_MI_LEXIKON_ADMENU3', 'Eintr&auml;ge');
define('_MI_LEXIKON_ADMENU4', 'Bl&ouml;cke/Gruppen');
define('_MI_LEXIKON_ADMENU5', 'Zum Modul');
//mondarse
define('_MI_LEXIKON_ADMENU6', 'Import');
define('_MI_LEXIKON_ADMENU7', 'Anfragen');
define('_MI_LEXIKON_ADMENU8', 'Einsendungen');
define('_MI_LEXIKON_ADMENU9', 'Berechtigungen');
define('_MI_LEXIKON_ADMENU10', 'Über');
define('_MI_LEXIKON_ADMENU11', 'Kommentare');
define('_MI_LEXIKON_ADMENU12', 'Statistik');
// SubMenues xoops 2.2.x
define('_MI_LEXIKON_CONFIGCAT_EXTENDED', '&raquo; Erweiterte Konfiguration');
define('_MI_LEXIKON_CONFIGCAT_EXTENDEDDSC', 'besondere Einstellungen der Eintr&auml;ge.');
//Names of Blocks and Block information
define('_MI_LEXIKON_ENTRIESNEW', 'Neueste Begriffe');
define('_MI_LEXIKON_ENTRIESTOP', 'Meistgelesene Begriffe');
define('_MI_LEXIKON_RANDOMTERM', 'Zuf&auml;lliger Begriff');
define('_MI_LEXIKON_TERMINITIAL', 'Lexikon Index');
define('_MI_LEXIKON_CATS', 'Lexikon Kategorien');
define('_MI_LEXIKON_SPOT', 'Spotlight Lexikon');
define('_MI_LEXIKON_BNAME8', 'Lexikon  Autoren');
define('_MI_LEXIKON_BNAME9', 'Scrolling Definitions');
// Notification event descriptions and mail templates
define('_MI_LEXIKON_NOTIFY', 'Allgemein');
define('_MI_LEXIKON_NOTIFYDSC', 'Benachrichtigungs-Optionen die den aktuellen Eintrag betreffen.');
define('_MI_LEXIKON_NOTIFY_CAT', 'Kategorie');
define('_MI_LEXIKON_NOTIFY_CATDSC', 'Benachrichtigung wenn eine neue Kategorie angelegt worden ist');
define('_MI_LEXIKON_NOTIFY_TERM', 'Definition');
define('_MI_LEXIKON_NOTIFY_TERMDSC', 'Benachrichtigung wenn eine neue Defintion veröffentlicht worden ist');
define('_MI_LEXIKON_NEWPOST_NOTIFY', 'Neuer Eintrag');
define('_MI_LEXIKON_NEWPOST_NOTIFYCAP', 'Benachrichtigung wenn ein neuer Eintrag ver&ouml;ffentlicht worden ist.');
define('_MI_LEXIKON_NEWPOST_NOTIFYDSC', 'Eine Benachrichtigung erhalten wenn ein neuer Artikel ver&ouml;ffentlicht worden ist.');
define('_MI_LEXIKON_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Neuer Eintrag im Lexikon veröffentlicht');
define('_MI_LEXIKON_NEWCAT_NOTIFY', 'Neue Kategorie');
define('_MI_LEXIKON_NEWCAT_NOTIFYCAP', 'Benachrichtigen wenn eine neue Kategorie angelegt worden ist.');
define('_MI_LEXIKON_NEWCAT_NOTIFYDSC', 'Eine Benachrichtigung erhalten wenn eine neue Kategorie angelegt worden ist.');
define('_MI_LEXIKON_NEWCAT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Neue Kategorie im Lexikon');
define('_MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFY', 'Neue Definition eingeschickt');
define('_MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFYCAP', 'Benachrichtigen wenn eine neue Definition eingeschickt worden ist (noch freizugeben).');
define('_MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFYDSC', 'Eine Benachrichtigung erhalten wenn eine neue Definition eingeschickt worden ist, die noch freizugeben ist.');
define('_MI_LEXIKON_GLOBAL_TERMSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Neue Definition eingeschickt');
define('_MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFY', 'Definitions-Anfrage');
define('_MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFYCAP', 'Benachrichtigen wenn eine Definition angefragt worden ist (noch zu beantworten).');
define('_MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFYDSC', 'Eine Benachrichtigung erhalten wenn eine Definition eingeschickt worden ist, die noch zu vervollständigen ist.');
define('_MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Definitions-Anfrage');
define('_MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFY', 'Neue Definition eingeschickt');
define('_MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFYCAP', 'Benachrichtigen wenn eine neue Definition eingeschickt worden ist (noch freizugeben) f&uuml;r die aktuelle Kategorie.');
define('_MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFYDSC', 'Eine Benachrichtigung erhalten wenn eine neue Definition eingeschickt worden ist (noch freizugeben) f&uuml;r die aktuelle Kategorie.');
define('_MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Neue Definition eingeschickt f&uuml;r die aktuelle Kategorie');
define('_MI_LEXIKON_CATEGORY_NEWTERM_NOTIFY', 'Neue Definition');
define('_MI_LEXIKON_CATEGORY_NEWTERM_NOTIFYCAP', 'Benachrichtigung wenn ein neuer Eintrag in der aktuellen Kategorie ver&ouml;ffentlicht worden ist.');
define('_MI_LEXIKON_CATEGORY_NEWTERM_NOTIFYDSC', 'Eine Benachrichtigung erhalten wenn ein neuer Eintrag in der aktuellen Kategorie ver&ouml;ffentlicht worden ist .');
define('_MI_LEXIKON_CATEGORY_NEWTERM_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: neuer Eintrag in der Kategorie');
define('_MI_LEXIKON_TERM_APPROVE_NOTIFY', 'Begriff Freigegeben');
define('_MI_LEXIKON_TERM_APPROVE_NOTIFYCAP', 'Benachrichtigung wenn meine Einsendung freigegeben wurde.');
define('_MI_LEXIKON_TERM_APPROVE_NOTIFYDSC', 'Eine Benachrichtigung erhalten wenn meine Einsendung freigegeben wurde.');
define('_MI_LEXIKON_TERM_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Einsendung freigegeben');

define('_MI_LEXIKON_IMPORT', 'Import');

define('_MI_LEXIKON_HOME', 'Home');
define('_MI_LEXIKON_ABOUT', 'About');
