<?php
/**
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */

// Module Info
// The name of this module
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
define('_MI_LEXIKON_MULTICATS', 'Benötigen Sie Glossar-Kategorien?');
define('_MI_LEXIKON_MULTICATSDSC', "Falls 'Ja' können diverse Kategorien benutzt werden, ansonsten gibt es nur eine automatische Kategorie.");
define('_MI_LEXIKON_ALLOWSUBMIT', 'Dürfen User Einträge einschicken?');
define('_MI_LEXIKON_ALLOWSUBMITDSC', "Falls 'Ja', haben die User Zugriff auf das Absendeformular");
define('_MI_LEXIKON_CATSINMENU', 'Sollen die Kategorien im Menü angezeigt werden?');
define('_MI_LEXIKON_CATSINMENUDSC', "Wählen Sie 'Ja', falls Sie Links zu Kategorien im Hauptmenü haben wollen.");
define('_MI_LEXIKON_ANONSUBMIT', 'Dürfen Gäste Einträge einschicken?');
define('_MI_LEXIKON_ANONSUBMITDSC', "Falls 'Ja', haben Gäste Zugriff auf das Absendeformular");
define('_MI_LEXIKON_DATEFORMAT', 'Welches Format soll das Datum haben?');
define('_MI_LEXIKON_DATEFORMATDSC', "Benutzt den letzten Teil von language/german/global.php um die Datumsanzeige zu bestimmen. Beispiel: 'd-M-Y H:i' wird zu '23-Jan-2011 22:35'");
define('_MI_LEXIKON_ALLOWREQ', 'Dürfen Gäste Einträge angefragen ?');
define('_MI_LEXIKON_ALLOWREQDSC', "Falls 'Ja', haben auch Gauml;ste Zugriff auf das Anfrageformular.");
define('_MI_LEXIKON_PERPAGE', 'Anzahl der Einträge pro Seite (Admin-Seite)?');
define('_MI_LEXIKON_PERPAGEDSC', 'Anzahl der Einträge die auf einmal auf der Admin-Seite angezeigt werden.');
define('_MI_LEXIKON_PERPAGEINDEX', 'Anzahl der Einträge pro Seite (User-Seite)?');
define('_MI_LEXIKON_PERPAGEINDEXDSC', 'Anzahl der Einträge die auf jeder Seite der User-Seite angezeigt werden.');
define('_MI_LEXIKON_BLOCKSPERPAGE', 'Anzahl der Einträge in den Blöcken?');
define('_MI_LEXIKON_BLOCKSPERPAGEDSC', 'Anzahl der Einträge die in jedem Blocks auf der Startseite gezeigt werden (Standardwert 5).');
define('_MI_LEXIKON_AUTOAPPROVE', 'Einträge automatisch freigeben?');
define('_MI_LEXIKON_AUTOAPPROVEDSC', "Falls 'Ja' werden die eingesendeten Einträge automatisch freigegeben.");
define('_MI_LEXIKON_ALLOWADMINHITS', 'Sollen Admin-Aufrufe mitgezählt werden?');
define('_MI_LEXIKON_ALLOWADMINHITSDSC', "Falls 'Ja', wird sich der Zähler auch bei Admin-Aufrufen erhöhen.");
define('_MI_LEXIKON_MAILTOADMIN', 'E-Mail an Admin bei jeder neuen Einsendung?');
define('_MI_LEXIKON_MAILTOADMINDSC', "Falls 'Ja', wird der Admin bei jeder neuen Einsendung eine E-Mail erhalten.");
define('_MI_LEXIKON_MAILTOSENDER', 'E-Mail an Einsender bei jeder neuen Einsendung, Anforderung oder Änderung?');
define('_MI_LEXIKON_MAILTOSENDERDSC', "Falls 'Ja', wird der Benutzer bei jeder neuen Einsendung, Bearbeitung oder Anfrage eine Versand-Bestätigung erhalten. Falls `Benachrichtigen bei Freigabe` ausgewählt wurde, erhält der Einsender eine weitere E-Mail nach der Freischaltung.");
define('_MI_LEXIKON_RANDOMLENGTH', 'Länge der anzuzeigenden Zeile bei zufälligen Definitionen?');
define('_MI_LEXIKON_RANDOMLENGTHDSC', 'Wieviele Zeichen sollen bei den zufälligen Definitionen angezeigt werden? Gilt für die Index-Seite und für den Block (Vorgabe: 150 Zeichen)');
define('_MI_LEXIKON_LINKTERMS', 'Links anzeigen zu anderen Begriffen in den Definitionen?');
define('_MI_LEXIKON_LINKTERMSDSC', "Falls 'Ja' wird in den Definitionen automatisch zu anderen Einträgen im Glossar verlinkt.");
define('_MI_LEXIKON_FORM_OPTIONS', 'Formular Optionen');
define('_MI_LEXIKON_FORM_OPTIONSDSC', 'Hier kann gewählt werden welcher Editor zur Eingabe verwendet wird.<br>Sofern ein anderer Editor als XOOPS-DHTML-Editor ausgewählt wird, so muss dieser im Verzeichnis class/xoopseditor installiert sein.');
define('_MI_LEXIKON_EDIGUEST', 'Eingabe Optionen für Einsendungen');
define('_MI_LEXIKON_EDIGUESTDSC', 'Sollen Benutzer und Gäste Editoren für Einsendungen verwenden dürfen?');
define('_MI_LEXIKON_DISPPROL', 'Zeige Submitter bei Eintrag?');
define('_MI_LEXIKON_DISPPROLDSC', "Falls 'Ja' wird der Autor des Eintrags gezeigt.");
define('_MI_LEXIKON_HEADER', 'Hauptseite - einleitender Text:');
define('_MI_LEXIKON_HEADERDSC', 'Hier kann für den Modulheader ein Text oder Javascript Code eingegeben werden (HTML ist erlaubt).');
define('_MI_LEXIKON_AUTHORPROFILE', 'Autoren-Profil verwenden?');
define('_MI_LEXIKON_AUTHORPROFILEDSC', "Falls 'Ja', wird der Benutzername mit dem Glossar-Profil des Autors verlinkt und ein Link zur Autorenliste erscheint im Menü.");
define('_MI_LEXIKON_SHOWDAT', 'Zeige Datum im Block der neuesten Einträge?');
define('_MI_LEXIKON_SHOWDATDSC', "Falls 'Ja' wird auf der Startseite das Datum bei den neuesten Einträgen gezeigt.");
define('_MI_LEXIKON_SHOWCTR', 'Zeige Zähler im Block der beliebtesten Einträge?');
define('_MI_LEXIKON_SHOWCTRDSC', "Falls 'Ja' wird der Zähler auf der Startseite bei populärsten Einträgen gezeigt.");
define('_MI_LEXIKON_CAPTCHA', 'Captcha für Einsendungen verwenden?');
define('_MI_LEXIKON_CAPTCHADSC', 'Xoops Frameworks wird benötigt.');
define('_MI_LEXIKON_KEYWORDS_HIGH', 'Suchwörter farblich hervorheben?');
define('_MI_LEXIKON_KEYWORDS_HIGHDSC', ' Bei Ja, werden Suchwörter in den Definitionen hervorgehoben');
define('_MI_LEXIKON_BOOKMARK_ME', 'Zeige Social Bookmarks?');
define('_MI_LEXIKON_BOOKMARK_MEDSC', 'Die Icons sind auf der Seite der Einträge zu sehen.');
define('_MI_LEXIKON_METANUM', 'Maximale Anzahl an Meta Keywords die automatisch generiert werden?');
define('_MI_LEXIKON_METANUMDSC', 'Hier ihre Anzahl der zu generierenden meta-Keywörter wählen. <BR> Bei einem Wert von Null 0, werden die Keywörter der Webseite verwendet.');
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
define('_MI_LEXIKON_USESHOTS', 'Kategoriebilder verwenden?');
define('_MI_LEXIKON_USESHOTSDSC', 'Wenn `Ja` dann werden Kategorienbilder dargestellt.<br> <em>Das Uploadverzeichnis für Kategoriebilder ist uploads/lexikon/categories/images</em>');
define('_MI_LEXIKON_LOGOWIDTH', 'Breite der Kategoriebilder im Menü:');
define('_MI_LEXIKON_LOGOWIDTHDSC', 'Größe der Vorschaubilder im Menü (Standardwert: 20px)');
define('_MI_LEXIKON_IMCATWD', 'Breite der Kategoriebilder bei Kategorie-Ansicht:');
define('_MI_LEXIKON_IMCATWDDSC', 'Breite des Logos wenn Einzel-Kategorien betrachtet werden. (Standardwert: 50px)');
define('_MI_LEXIKON_RSS', 'Dürfen Gäste RSS Syndikation benutzen?');
define('_MI_LEXIKON_RSSDSC', 'Wenn diese Option gewählt wird, sind die neuesten Einträge für Gäste abrufbar. Bei `Nein` haben nur Benutzer Zugang zur Syndikation.');
define('_MI_LEXIKON_SYNDICATION', 'Webmastercontent Syndikation verwenden?');
define('_MI_LEXIKON_SYNDICATIONDSC', 'Wenn diese Option gewählt wird, haben Benutzer Zugang zur Syndikation.');
// new configs in version 1.52
define('_MI_LEXIKON_IMGUPLOADWD', 'Maximale Höhe/Breite für Bilderupload');
define('_MI_LEXIKON_IMGUPLOADWD_DESC', 'Setzt die maximale erlaubte Höhe/Breite für das Hochladen eines Bildes.');
define('_MI_LEXIKON_IMGUPLOADSIZE', 'Maximale Dateigröße für Bilderupload');
define('_MI_LEXIKON_IMGUPLOADSIZE_DESC', 'Setzt die maximale erlaubte Dateigröße für das Hochladen eines Bildes in Bytes (10485760 = 1 MB).');
// bookmarks
define('_MI_LEXIKON_ADDTHIS1', 'Addthis Popup Fenster verwenden');
define('_MI_LEXIKON_ADDTHIS2', 'Addthis dropdown Auswahlbox');
// linkterms
define('_MI_LEXIKON_POPUP', 'Popup Fenster');
define('_MI_LEXIKON_TOOLTIP', 'Tooltips');
define('_MI_LEXIKON_BUBBLETIPS', 'Bubble Tooltips');
define('_MI_LEXIKON_SHADOWTIPS', 'Shadow Tooltips');
// Names of admin menu items
define('_MI_LEXIKON_ADMENU0', 'Übersicht');
define('_MI_LEXIKON_ADMENU1', 'Index');
define('_MI_LEXIKON_ADMENU2', 'Kategorien');
define('_MI_LEXIKON_ADMENU3', 'Einträge');
define('_MI_LEXIKON_ADMENU4', 'Blöcke/Gruppen');
define('_MI_LEXIKON_ADMENU5', 'Zum Modul');
//mondarse
define('_MI_LEXIKON_ADMENU6', 'Import');
define('_MI_LEXIKON_ADMENU7', 'Anfragen');
define('_MI_LEXIKON_ADMENU8', 'Einsendungen');
define('_MI_LEXIKON_ADMENU9', 'Berechtigungen');
define('_MI_LEXIKON_ADMENU10', 'Über');
define('_MI_LEXIKON_ADMENU11', 'Kommentare');
define('_MI_LEXIKON_ADMENU12', 'Statistiken');
// SubMenues xoops 2.2.x
define('_MI_LEXIKON_CONFIGCAT_EXTENDED', '&raquo; Erweiterte Konfiguration');
define('_MI_LEXIKON_CONFIGCAT_EXTENDEDDSC', 'besondere Einstellungen der Einträge.');
//Names of Blocks and Block information
define('_MI_LEXIKON_ENTRIESNEW', 'Neueste Begriffe');
define('_MI_LEXIKON_ENTRIESTOP', 'Meistgelesene Begriffe');
define('_MI_LEXIKON_RANDOMTERM', 'Zufälliger Begriff');
define('_MI_LEXIKON_TERMINITIAL', 'Lexikon Index');
define('_MI_LEXIKON_CATS', 'Lexikon Kategorien');
define('_MI_LEXIKON_SPOT', 'Spotlight Lexikon');
define('_MI_LEXIKON_BNAME8', 'Lexikon  Autoren');
define('_MI_LEXIKON_BNAME9', 'Scrolling Definitions');
// Notification event descriptions and mail templates
define('_MI_LEXIKON_NOTIFY', 'Global');
define('_MI_LEXIKON_NOTIFYDSC', 'Globale Benachrichtigungs-Optionen');
define('_MI_LEXIKON_NOTIFY_CAT', 'Kategorie');
define('_MI_LEXIKON_NOTIFY_CATDSC', 'Benachrichtigungsoptionen für aktuelle Kategorie');
define('_MI_LEXIKON_NOTIFY_TERM', 'Definition');
define('_MI_LEXIKON_NOTIFY_TERMDSC', 'Benachrichtigungsoptionen für aktuelle Definition');
define('_MI_LEXIKON_NEWPOST_NOTIFY', 'Neue Definition');
define('_MI_LEXIKON_NEWPOST_NOTIFYCAP', 'Benachrichtigung wenn ein neuer Eintrag veröffentlicht worden ist.');
define('_MI_LEXIKON_NEWPOST_NOTIFYDSC', 'Eine Benachrichtigung erhalten wenn eine neue Definition veröffentlicht worden ist.');
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
define('_MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFYDSC', 'Eine Benachrichtigung erhalten wenn eine Definition eingeschickt worden ist, die noch freizugeben ist.');
define('_MI_LEXIKON_GLOBAL_TERMREQUEST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Definitions-Anfrage');
define('_MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFY', 'Neue Definition eingeschickt');
define('_MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFYCAP', 'Benachrichtigen wenn eine neue Definition eingeschickt worden ist (noch freizugeben) für die aktuelle Kategorie.');
define('_MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFYDSC', 'Eine Benachrichtigung erhalten wenn eine neue Definition eingeschickt worden ist (noch freizugeben) für die aktuelle Kategorie.');
define('_MI_LEXIKON_CATEGORY_TERMSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Neue Definition eingeschickt für die aktuelle Kategorie');
define('_MI_LEXIKON_CATEGORY_NEWTERM_NOTIFY', 'Neue Definition');
define('_MI_LEXIKON_CATEGORY_NEWTERM_NOTIFYCAP', 'Benachrichtigung wenn ein neuer Eintrag in der aktuellen Kategorie veröffentlicht worden ist.');
define('_MI_LEXIKON_CATEGORY_NEWTERM_NOTIFYDSC', 'Eine Benachrichtigung erhalten wenn ein neuer Eintrag in der aktuellen Kategorie veröffentlicht worden ist .');
define('_MI_LEXIKON_CATEGORY_NEWTERM_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: neuer Eintrag in der Kategorie');
define('_MI_LEXIKON_TERM_APPROVE_NOTIFY', 'Begriff Freigegeben');
define('_MI_LEXIKON_TERM_APPROVE_NOTIFYCAP', 'Benachrichtigung wenn meine Einsendung freigegeben wurde.');
define('_MI_LEXIKON_TERM_APPROVE_NOTIFYDSC', 'Eine Benachrichtigung erhalten wenn meine Einsendung freigegeben wurde.');
define('_MI_LEXIKON_TERM_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Einsendung freigegeben');
define('_MI_LEXIKON_IMPORT', 'Import');
//1.52
define('_MI_LEXIKON_BLOCKADMIN', 'Blockverwaltung');
define('_MI_LEXIKON_SHOWSUBMISSIONS', 'Einsendungen');
define('_MI_LEXIKON_HOME', 'Übersicht');
define('_MI_LEXIKON_ABOUT', 'Über');
// Admin
define('_MI_LEXIKON_DESC', 'Dieses Modul dient zum ...');
//Blocks
define('_MI_LEXIKON_CATEGORY_BLOCK', 'Block Kategorien');
define('_MI_LEXIKON_ENTRIES_BLOCK', 'Block Einträge');
//Config
define('_MI_LEXIKON_EDITOR_ADMIN', 'Editor: Admin');
define('_MI_LEXIKON_EDITOR_ADMIN_DESC', 'Wähle den Editor für Admin-Bereich');
define('_MI_LEXIKON_EDITOR_USER', 'Editor: User');
define('_MI_LEXIKON_EDITOR_USER_DESC', 'Wähle den Editor für User-Bereich');
define('_MI_LEXIKON_KEYWORDS', 'Schlüsselworter');
define('_MI_LEXIKON_KEYWORDS_DESC', 'Bitte Schlüsselwörter angeben (getrennt durch ein Komma)');
define('_MI_LEXIKON_MAXSIZE', 'Maximale Größe');
define('_MI_LEXIKON_MAXSIZE_DESC', 'Definieren Sie bitte die maximale Größe für einen Dateiupload');
define('_MI_LEXIKON_MIMETYPES', 'Mime-Types');
define('_MI_LEXIKON_MIMETYPES_DESC', 'Definieren Sie bitte die zulässigen Dateitypen');
define('_MI_LEXIKON_IDPAYPAL', 'Paypal ID');
define('_MI_LEXIKON_IDPAYPAL_DESC', 'Deinen PayPal IDfür Spenden hier angeben.');
define('_MI_LEXIKON_ADVERTISE', 'Code Werbung');
define('_MI_LEXIKON_ADVERTISE_DESC', 'Bitte Code für Werbungen eingeben');
define('_MI_LEXIKON_BOOKMARKS', 'Social Bookmarks');
define('_MI_LEXIKON_BOOKMARKS_DESC', 'Social Bookmarks anzeigen');
define('_MI_LEXIKON_FBCOMMENTS', 'Facebook-Kommentare');
define('_MI_LEXIKON_FBCOMMENTS_DESC', 'Facebook-Kommentare erlauben');
// Help
define('_MI_LEXIKON_OVERVIEW', 'Übersicht');
//help multi-page
define('_MI_LEXIKON_DISCLAIMER', 'Disclaimer');
define('_MI_LEXIKON_LICENSE', 'License');
define('_MI_LEXIKON_SUPPORT', 'Support');
// Permissions Groups
define('_MI_LEXIKON_GROUPS', 'Gruppenzugriff');
define('_MI_LEXIKON_GROUPS_DESC', 'Definiere Zugriffsrechte je Gruppe');
define('_MI_LEXIKON_ADMINGROUPS', 'Berechtigungen Admin-Gruppen');
define('_MI_LEXIKON_ADMINGROUPS_DESC', 'Welche Gruppen erhalten Zugriff auf die Toosl und die Berechtigungsseiten');
