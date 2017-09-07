<?php
/**
 * $Id: main.php v 1.0 8 May 2004 hsalazar Exp $
 * Module: Lexikon - glossary module
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */
define('_AM_LEXIKON_NOCOLEXISTS', 'К сожалению, нет категории определений.<br>Пожалуйста, свяжитесь с администратором сайта и сообщите ему об этом.');
define('_MD_LEXIKON_ALL', 'Все');
define('_MD_LEXIKON_ALLCATS', 'Все категории');
define('_MD_LEXIKON_ALLOFTHEM', '0 : Все категории');
define('_MD_LEXIKON_ANDOR', 'Область поиска');
define('_MD_LEXIKON_ANDORALL', 'Все слова (И)');
define('_MD_LEXIKON_ANDORANY', 'Любые слова (ИЛИ)');
define('_MD_LEXIKON_ANDOREXACT', 'Полное совпадение');
define('_MD_LEXIKON_ANONYMOUS', 'Анонимный');
define('_MD_LEXIKON_ASKEDBY', 'Срок по просьбе:');
define('_MD_LEXIKON_ASKFORDEF', 'Запрос определения');
define('_MD_LEXIKON_BEGINWITHLETTER', 'записи, начинающиеся с этой буквы.');
define('_MD_LEXIKON_BROWSECAT', 'Просмотр по категориям');
define('_MD_LEXIKON_BROWSELETTER', 'Поиск по буквам');
//define("_MD_LEXIKON_CATEGORY","Search in category");
define('_MD_LEXIKON_CATEGORY', "<span style='display: block;line-height:1em;'>Искать в категории</style>");
define('_MD_LEXIKON_CATS', 'Категории: ');
define('_MD_LEXIKON_COUNT', 'Эта запись была просмотрена %s раз.');
define('_MD_LEXIKON_CREATE', 'Добавить запись');
define('_MD_LEXIKON_DEFINITION', '[*] Определение');
define('_MD_LEXIKON_DEFINITIONREQ', 'Запрос определения');
define('_MD_LEXIKON_DEFINITIONSUB', 'Представлено определение');
define('_MD_LEXIKON_DEFINS', 'Определения');
define('_MD_LEXIKON_DEFS', 'Определения: ');
define('_MD_LEXIKON_DELTERM', 'Удалить этот термин');
define('_MD_LEXIKON_EDITTERM', 'Редактировать этот термин');
define('_MD_LEXIKON_EMAILLEFT', 'Пользователь оставил этот email адрес: ');
define('_MD_LEXIKON_ENTRIES', ' записи.');
define('_MD_LEXIKON_ENTRIESINCAT', 'в глоссарии.');
define('_MD_LEXIKON_ENTRY', '[*] Запись');
define('_MD_LEXIKON_ENTRYCATEGORY', 'Категория: ');
define('_MD_LEXIKON_ENTRYCOUNT', 'В этой категории ');
define('_MD_LEXIKON_ENTRYDEFINITION', 'Определение: ');
define('_MD_LEXIKON_ENTRYREFERENCE', 'Справка: ');
define('_MD_LEXIKON_ENTRYRELATEDURL', 'Относящийся сайт: ');
define('_MD_LEXIKON_ERRORSAVINGDB', 'ОШИБКА: База данных не была обновлена ​​из-за ошибки!');
define('_MD_LEXIKON_GOODDAY', 'Добрый день, ');
define('_MD_LEXIKON_GOODDAY2', 'Добрый день, %s.');
define('_MD_LEXIKON_HOME', 'Главная');
define('_MD_LEXIKON_INALLGLOSSARIES', 'записи во всех наших словарях.');
define('_MD_LEXIKON_INTENTRY', 'Посмотрите на эту запись на %s');
define('_MD_LEXIKON_INTENTRYFOUND', 'Вот интересную запись я нашел на %s');
define('_MD_LEXIKON_INTROREQUEST', 'Вы не нашли определение которое искали? Представьте термин нам, заполнив эту форму. Мы будем искать его определение и добавим его в базу данных.');
define('_MD_LEXIKON_LOOKON', 'Поиск');
define('_MD_LEXIKON_MAINHEAD', 'Добро пожаловать на %s');
define(
    '_MD_LEXIKON_MAININTRO',
       'В этой области Вы найдете наш каталог словарных записей, а также последнюю запись из каждой из наших категорий. Нажмите на имя категории, чтобы увидеть все записи, связанные с данной категорией, или на название элемента для чтения непосредственно записи. В зависимости от привилегий, Вы можете оценить каждую запись, выберать параметры уведомления или оставить свои комментарии.'
);
define('_MD_LEXIKON_MAINNOSELECTCAT', 'Вы не выбрали корректную категорию');
define('_MD_LEXIKON_MAINNOTOPICS', 'Нет записей в этой категории');
define('_MD_LEXIKON_MESSAGESENT', 'Сообщение отправлено %s .');
define('_MD_LEXIKON_NO', 'Нет');
define('_MD_LEXIKON_NOENTRIESINCAT', 'Нет записей в этом глоссарии.');
define('_MD_LEXIKON_NORESULTS', 'Нет никаких ссылок на данные поиска. Пожалуйста, используйте другое слово и попробуйте еще раз.');
define('_MD_LEXIKON_NOSEARCHTERM', 'Вы не заполнили слово для поиска. Пожалуйста, сделайте это сейчас в форме поиска.');
define('_MD_LEXIKON_NOTERMSINLETTER', 'Нет записей, которые начинаются с этой буквы.');
define('_MD_LEXIKON_NOTIFY', 'Уведомлять о публикоции?');
define('_MD_LEXIKON_NOTIFYONPUB', 'Пользователь попросил, чтобы получать уведомления, когда мы делаем что-то с определением.<br>');
define('_MD_LEXIKON_OTHER', 'Другое');
define('_MD_LEXIKON_POPULARENT', 'Популярные записи');
define('_MD_LEXIKON_POSTED', 'Опубликовано');
define('_MD_LEXIKON_PRINTERFRIENDLY', 'Распечатать эту запись в удобном формате');
define('_MD_LEXIKON_PRINTTERM', 'Печать в удобном формате');
define('_MD_LEXIKON_RANDOMTERM', 'Случайный термин');
define('_MD_LEXIKON_RECEIVED', 'Запись, которую Вы послали была получена. Мы рассмотрим ее как можно скорее.');
define('_MD_LEXIKON_RECEIVEDANDAPPROVED', 'Запись, которую Вы послали была получена. Так как автоматическое утверждение включено, мы опубликовали ее сразу.');
define('_MD_LEXIKON_RECENTENT', 'Последние записи');
define('_MD_LEXIKON_REFERENCE', "Справка<BR><span style='font-size: x-small; font-weight: normal; line-height:1em;'>(При желании, Вы можете дать источник определения здесь.)</span>");
define('_MD_LEXIKON_REQRCVD', 'Ваш запрос был получен. Мы рассмотрим его как можно скорее.');
define('_MD_LEXIKON_REQSEND', 'Запрос отправлен');
define('_MD_LEXIKON_REQTERM', '[*] Термин для определения');
define('_MD_LEXIKON_REQUESTDEF', 'Запрос определения');
define('_MD_LEXIKON_REQUESTFORM', 'Запрос определения');
define('_MD_LEXIKON_REQUESTSENT', 'Ваш запрос был направлен к веб-мастеру %s.');
define('_MD_LEXIKON_RETURN', 'Вернуться');
define('_MD_LEXIKON_RETURN2INDEX', 'Вернуться на главную');
define('_MD_LEXIKON_SEARCH', 'Поиск!');
define('_MD_LEXIKON_SEARCHENTRY', 'Поиск');
define('_MD_LEXIKON_SEARCHHEAD', 'Результаты поиска');
define('_MD_LEXIKON_SECTIONNAME', 'Словарные статьи');
define('_MD_LEXIKON_SEEMORE', 'Дополнительные определения...');
define('_MD_LEXIKON_SENDSTORY', 'Отправить эту запись другу по электронной почте');
define('_MD_LEXIKON_SENDTOFRIEND', 'Отправить этот термин другу по электронной почте');
define('_MD_LEXIKON_SENT', 'Представлены на: ');
define('_MD_LEXIKON_SENTCONFIRMMAIL', 'Ваши комментарии были направлены <b>%s</b> в качестве подтверждения на email.');
define(
    '_MD_LEXIKON_STILLNOTHINGHERE',
       "<h4 style='margin: 0;'>Глоссарии пустые</h4>К сожалению: у нас нет ни одной записи в наших словарях. Вы можете помочь нам с просьбой некоторых записей, определение которых Вы хотели бы знать. Если вы зарегистрированный пользователь, Вы можете также помочь, представив некоторые термины, определение которые знаете."
);
define('_MD_LEXIKON_SUB_SMNAME', 'Заполните эту форму, чтобы отправить запись');
define('_MD_LEXIKON_SUB_SNEWNAME', 'Добавить запись в %s');
define(
    '_MD_LEXIKON_SUB_SNEWNAMEDESC',
       'пожалуйста, заполните следующую форму, чтобы отправить новую запись на наш сайт. Как Вам хорошо известно, что запись должна быть проверена администратором, прежде чем она будет опубликована. Тем не менее, если администратор включил настройку автоматического утверждения, Ваша запись будет опубликована мгновенно.'
);
define('_MD_LEXIKON_SUBMIT', 'Отправить запрос');
define('_MD_LEXIKON_SUBMITART', 'Добавить запись');
define('_MD_LEXIKON_SUBMITENTRY', 'Добавить запись');
define('_MD_LEXIKON_SUBMITTEDBY', 'от %s, ');
define('_MD_LEXIKON_SUBMITTEDON', 'на %s');
define('_MD_LEXIKON_SUBMITTED', 'Отправлено ');
define('_MD_LEXIKON_SUBMITTER', 'Отправил: ');
//define("_MD_LEXIKON_SUBMITTED","Submitted by %s, on %s.");
//define("_MD_LEXIKON_TERM","Search term");
define('_MD_LEXIKON_TERM', "<span style='display: block;line-height:1em;'>Искать термин</span>");
define('_MD_LEXIKON_TERMS', 'Термины');
define('_MD_LEXIKON_TERMSDEFS', 'Термины и определения');
define('_MD_LEXIKON_THANKS1', 'Благодарим за Ваше участие!<br>');
define('_MD_LEXIKON_THANKS2', 'Благодарим Вас за запрос на определение!');
define('_MD_LEXIKON_THANKYOU', 'Спасибо, что нашли время, чтобы запросить определение, которое обогатит глоссарий %s.');
define('_MD_LEXIKON_THEREWERE', 'Записи %s соответствующие поисковому запросу: <b>%s</b>.');
define('_MD_LEXIKON_TIMESREAD', 'Время чтения');
define('_MD_LEXIKON_UNKNOWNERROR', 'ОШИБКА. Страницы с таким определением не существует!');
define('_MD_LEXIKON_URL', "URL<span style='font-size: x-small; font-weight: normal; line-height:1em;'><BR>(Введите действительный URL с или без префикса HTTP.)</span>");
define('_MD_LEXIKON_USERMAIL', 'E-mail');
define('_MD_LEXIKON_USERNAME', 'Имя');
define('_MD_LEXIKON_WEBMASTER', 'ВебМастер');
define('_MD_LEXIKON_WEHAVE', 'В настоящее время мы имеем');
define('_MD_LEXIKON_WHOASKED', '%s просил нас найти определение: ');
define('_MD_LEXIKON_WHOSUBMITTED', '%s представил определение этого термина: ');
define('_MD_LEXIKON_YES', 'Да');

define('_MD_LEXIKON_OPTIONS', 'Опции');
define('_MD_LEXIKON_DOHTML', 'Разрешить HTML?');
define('_MD_LEXIKON_DOSMILEY', 'Разрешить смайлики?');
define('_MD_LEXIKON_DOXCODE', 'Разрешить XOOPS-коды?');
define('_MD_LEXIKON_BREAKS', 'Разрешить переносы строк?');
define('_MD_LEXIKON_SUBANDREQ', 'Отправил и запрсил термины');
define('_MD_LEXIKON_SUB', 'Отправил термины: ');
define('_MD_LEXIKON_REQ', 'Запрошенные термины: ');
define('_MD_LEXIKON_NOSUB', 'В настоящее время нет никаких представленных терминов.');
define('_MD_LEXIKON_NOREQ', 'В настоящее время нет запрашиваемых терминов.');
define('_MD_LEXIKON_WRITEHERE', 'Пожалуйста, напишите здесь определение.');
define('_MD_LEXIKON_GUEST', 'Гость');
define('_MD_LEXIKON_NOCATSINSYSTEM', 'Ошибка. Такой категории не существует.');
define('_MD_LEXIKON_REQUESTSUGGEST', '(Нажмите продолжить, чтобы предложить определение термина)');

define('_MD_LEXIKON_ENTRYISOFF', 'К сожалению, это определение в настоящее время отсутствует.');
define('_MD_LEXIKON_ITEMEXISTS', 'Извините. Термин уже существует.');

//RSS syndication
define('_MD_LEXIKON_INCATS', 'Последние записи в %s');
define('_MD_LEXIKON_INCATS_DESC', 'Последние записи в категории %s из %s');
define('_MD_LEXIKON_LASTDESC', 'Последние записи с сайта %s');

// new servicelinks
define('_MD_LEXIKON_PRINTTERM2', 'Печать');
define('_MD_LEXIKON_SENDTOFRIEND2', 'Email другу');

// bookmarks
define('_LEXIKON_BOOKMARK_ME', 'Добавить в закладки Entry');
define('_MD_LEXIKON_BKMK_TO_MISTERWONG', 'Добавить в закладки Mister Wong');
define('_MD_LEXIKON_BKMK_TO_BLINKLIST', 'Добавить в закладки Blinklist');
define('_MD_LEXIKON_BKMK_TO_DELICIOUS', 'Добавить в закладки del.icio.us');
define('_MD_LEXIKON_BKMK_TO_DIGG', 'Добавить в закладки Digg');
define('_MD_LEXIKON_BKMK_TO_NEWSVINE', 'Добавить в закладки Newsvine');
define('_MD_LEXIKON_BKMK_TO_REDDIT', 'Добавить в закладки Reddit');
define('_MD_LEXIKON_BKMK_TO_GOOGLE', 'Добавить в закладки Google');
define('_MD_LEXIKON_BKMK_TO_TECHNORATI', 'Добавить в закладки Technorati');
define('_MD_LEXIKON_BKMK_TO_FARK', 'Добавить в закладки Fark');
define('_MD_LEXIKON_BKMK_TO_FACEBOOK', 'Поделиться с друзьями в Facebook');
define('_MD_LEXIKON_BKMK_TO_TWITTER', 'Twitter');
define('_MD_LEXIKON_BKMK_TO_DIIGO', 'Закладка для DIIGO');
define('_MD_LEXIKON_BKMK_TO_STUDIVZ', 'Закладка для StudiVZ');
define('_MD_LEXIKON_BKMK_TO_LINKARENA', 'Закладка для Linkarena');
define('_MD_LEXIKON_BKMK_TO_ONEVIEW', 'Закладка для Oneview');
define('_MD_LEXIKON_BKMK_TO_STUMBLEUPON', 'Закладка для Stumbleupon');

//categories
define('_MD_LEXIKON_SINGLECAT', 'Модуль не имеет категории.');
define('_MD_LEXIKON_NOSHOTS', 'Нет доступных сриншотов');

// authorprofile
define('_MD_LEXIKON_AUTHORPROFILE', 'Условия того же автора');
//define("_MD_LEXIKON_AUTHORPROFILE","Glossary profile of the author");
define('_MD_LEXIKON_AUTHORPROFILETEXT', 'просмотреть все условия этого автора');
define('_MD_LEXIKON_AUTHORPROFILENOTERM', 'Пользователь %s еще не представил каких-либо условий.');
define('_MD_LEXIKON_CONTRIBUTORS', 'Глоссарий список авторов');
define('_MD_LEXIKON_CONTRIBUTORSLIST', 'Вот список авторов, которые внесли свой вклад в глоссарий нашего сайта.<BR> Нажмите на имя, чтобы увидеть его/ее вклад.');
define('_MD_LEXIKON_AUTHORTERMS', '%s представил %s определения в нашем глоссарии.');
define('_MD_LEXIKON_AUTHORWAITING', ' %s его/ее определения до сих пор ожидают утверждения.');
define('_MD_LEXIKON_NOWAITINGTERMS', 'Нет определений ожидающих одобрения.');
define('_MD_LEXIKON_DATETERM', 'Дата');
define('_MD_LEXIKON_HITS', 'Просмотры');
define('_MD_LEXIKON_AUTHOR', 'Имя пользователя');
define('_MD_LEXIKON_TOTAL', 'Записи');
define('_MD_LEXIKON_MAIL', 'E-Mail');
define('_MD_LEXIKON_PM', 'ЛС');
define('_MD_LEXIKON_LOCATION', 'Расположение');
define('_MD_LEXIKON_WWW', 'WWW');

//webmastercontent
define('_MD_LEXIKON_RANDOMIZE', 'следующее определение');
define('_MD_LEXIKON_TERMOFTHEDAY', 'Термин дня');
define('_MD_LEXIKON_SYNDICATION', 'Для веб-мастеров');
define(
    '_MD_LEXIKON_SYNCODE',
       "&lt;iframe style='background-color: #FFFFFF'; marginwidth=0 marginheight=0 width='220' height='260' src='%s/modules/%s/syndication.php' frameborder='0' topmargin='0' leftmargin='0' scrolling='no' allowtransparency='true' /&gt; [Ваш браузер не поддерживает фреймы или настроен не отображать фреймы.]</iframe>"
);
define('_MD_LEXIKON_SUB_SYNNAME', 'Бесплатный контент для веб-мастеров Вашего сайта');
define('_MD_LEXIKON_INTROCONTENTSYN', 'Вам нравится глоссарии определения на %s ? Теперь вы можете легко интегрировать наши Словарь специальных терминов-определений на Вашем сайте. Просто скопируйте приведенный ниже HTML-код.');
define('_MD_LEXIKON_POWER', 'Powered by ');
define('_MD_LEXIKON_SYNEXPLAIN1', "нажмите кнопку 'выбрать' или скопировать HTML-код с помощью CTRL + C.");
define('_MD_LEXIKON_SYNEXPLAIN2', 'Добавьте сценарий, где вы хотите (используя сочетание клавиш CTRL + V или контекстное меню мыши (->Вставить)).');
define('_MD_LEXIKON_SYNEXPLAIN3', 'Вы можете изменить расположение и внешний вид так, чтобы она наилучшим образом соответствовала Вашим личным предпочтениям.');

// mail admin side
define('_MD_LEXIKON_SUBJECTREQ', 'Ваша просьба определение на %s');
define('_MD_LEXIKON_SUBJECTSUB', 'Ваше определение представлено на %s');
define('_MD_LEXIKON_SUBJECTMOD', 'Ваше определение-обновлено на %s');
define('_MD_LEXIKON_CONFREQ', 'Запрос онлайн!');
define('_MD_LEXIKON_CONFSUB', 'Подача утверждена!');
define('_MD_LEXIKON_CONFMOD', 'Модификация утверждена!');
define('_MD_LEXIKON_APPROVED', 'Ваш запрос был одобрен вебмастером %s и теперь он появляться в нашем глоссарии.');
define('_MD_LEXIKON_REGARDS', 'С наилучшими пожеланиями');
define('_MD_LEXIKON_DEFINITIONMOD', 'Глоссарий модификация');
// ...user side
define('_MD_LEXIKON_THANKS3', 'Благодарим Вас за отправку определения.');
define('_MD_LEXIKON_THANKSREVIEW', 'Благодарим Вас за модификацию.');
define('_MD_LEXIKON_THANKYOU2', 'Спасибо за обновление определения. Вы получите сообщение, как только изменения были пересмотрены.');
define('_MD_LEXIKON_THANKYOU3', 'Благодарим Вас за отправку определение. Вы получите сообщение, как только определение будет одобрено.');
define('_MD_LEXIKON_SUBMISSIONSENT', 'Сообщение %s отправлено.');

define('_MD_LEXIKON_MUSTREGFIRST', 'У Вас нет доступа к этому элементу.');

/**
 * @return array
 */
function getAlphabet()
{
    $alphabet = [
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        'А',
        'Б',
        'В',
        'Г',
        'Д',
        'Е',
        'Ё',
        'Є',
        'И',
        'Й',
        'К',
        'Л',
        'М',
        'Н',
        'О',
        'П',
        'Р',
        'С',
        'Т',
        'У',
        'Ф',
        'Х',
        'Ц',
        'Ч',
        'Ш',
        'Щ',
        'Ъ',
        'Ы',
        'Э',
        'Ю',
        'Я'
    ];

    return $alphabet;
}
