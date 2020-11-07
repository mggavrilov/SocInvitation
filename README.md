# SocInvitation

## 1. Въведение
SocInvitation е система, предназначена да сканира покани на студенти за представяне на реферати за курса „Уеб технологии“. Тя предоставя на администратора възможността да сканира папка, съдържаща картинки с покани на студенти, както и списък със студенти (било то от CSV файл или от базата данни) и му дава информация за това кои от студентите в списъка имат изработена покана в директорията и кои не. Системата също има интеграция с Facebook и позволява да се следят постовете с покани на студентите в групата. След като Facebook групата бъде сканирана, администраторът може да разглежда класацията, която дава информация за това колко пъти са били харесани и коментирани поканите, дали са били качени навреме, дали са били генерирани автоматично от системата и т.н. SocInvitation има функционалност, чрез която може да се публикува автоматично пост с покана във Facebook групата за студенти, които са пропуснали срока да качат поканите си. Ето как изглежда един генериран пост:
 
![Alt text](/docs/1.png?raw=true)
 
Системата също може да засича дали даден студент е качил собствена покана след като му е била генерирана автоматично такава и да изтрива автоматично генерираната. Тъй като има интеграция с Facebook, чието API предоставя user tokens с ограничен период на валидност, системата предоставя лесен и автоматичен начин за администратора да я упълномощи чрез Facebook, както и да засича дали token-ът е изтекъл и да го подновява. 

## 2. Теория 
Тук ще бъдат разяснени някои от особеностите на технологиите, използвани за реализацията на системата.

За качването на цяла директория наведнъж се използва HTML директивата webkitdirectory, която все още е експериментална и няма пълна поддръжка във всички браузъри. Тествано е под Chrome и работи както се очаква.

За визуализирането на таблици в системата се използва JavaScript плъгина Vanilla DataTables, който е с отворен код и не използва допълнителни библиотеки като jQuery. Тя позволява обикновени HTML таблици да бъдат лесно стилизирани, страницирани, сортирани и да се търси в съдържанието им.

За интеграцията с Facebook са необходими няколко неща. На първо място трябва да бъде регистриран акаунт на разработчик в https://developers.facebook.com. След това, трябва да се създаде приложение (App) от този профил, което „представя“ нашата система пред Facebook. Тоест, ако администраторът иска да използва функционалностите на системата, свързани с Facebook, той трябва да упълномощи това приложение да използва някои от данните на профила му. Приложението има ID и таен ключ, които трябва да бъдат зададени в системата, а след това се раздават token-и, чрез които приложението има достъп до Facebook групата на курса за определено време. Когато това време изтече, системата подновява token-а автоматично.

## 3. Използвани технологии
 - PHP 5.6.32
 - Facebook Graph API v3.0
 - HTML5, CSS, JavaScript, Vanilla DataTables JS plugin
 - MySQL

## 4. Инсталация и настройки 
 - SQL командите, намиращи се в db.sql файла трябва да бъдат изпълнени в базата от данни, която ще обслужва системата.
 - Настройките за свързване с базата от данни се намират в /models/Database.php
 - db.sql файлът съдържа работещи настройки за комуникация с API-то на Facebook, но при нужда от смяна, тези настройки могат да бъдат променяни от таба „Настройки“, когато приложението се пусне.
 - Login с “admin” : “socinvitation”
 - Тъй като приложението е в „development mode” от гледна точка на Facebook (за production трябва да премине ревю от тях, а това няма как да стане, когато е на localhost), когато му разрешим достъп от профила си, то автоматично слага поверителността си на “Only Me”. В такъв случай, приложението не може да поства в групата, защото не може потребител да поства в група и видимостта на поста да е „Only Me”. За да се оправи този проблем, трябва да се отиде в настройките на Facebook акаунта -> Apps and Websites -> View and Edit върху приложението -> App Visibility се слага на “Public”.
 - Настройките, свързани с Facebook приложението (напр. app_id, app_secret и т.н.) могат да бъдат променяни от таба „Настройки“, когато системата е пусната. Всички те (без fb_user_access_token) трябва да бъдат настроени ръчно, ако е необходимо системата да ползва друго Facebook приложение или друга Facebook група. Генерирането на user access token-а става автоматично като се иска упълномощяване от Facebook профила на администратора. Групата, с която ще е свързана системата, трябва да принадлежи на профила, който упълномощява системата.
 - Други настройки, които трябва да се зададат от настройките на приложението в https://developers.facebook.com (примерните са ако се deploy-ва на localhost):

App Domains: localhost

Website Site URL: http://localhost/


## 5. Кратко ръководство на потребителя 
Login

![Alt text](/docs/2.png?raw=true)

За момента credential-ите са hard code-нати и са:

Username: admin

Password: socinvitation 

Таб „Сканиране“

![Alt text](/docs/3.png?raw=true)


Тук се намира основната логика на системата, а именно – сканиране за покани в папка и/или във Facebook група. В първото поле се избира директория с покани (незадължително), след това потребителят има възможност да сканира студенти от базата данни (ако преди това е бил сканиран CSV файл и студентите са били записани в базата) или от CSV файл, който трябва да прикачи. След това, има възможността да изчисти съществуващите записи в базата от данни (ако тази кутийка не е избрана, то съществуващите записи ще бъдат обновени, а тези които са нови – добавени). Най-накрая има кутийки за това дали да се сканира Facebook групата и дали да се генерират покани за закъснелите.

Тази форма може да бъде използвана по много различни начини. Например: сканиране само на CSV файл без нищо друго – запазва студенти в базата; сканиране на директория и студенти от CSV/базата – запазва студенти в базата и показва таблица за това кои от студентите в списъка имат картинки с покани в избраната директория; сканиране на студенти от CSV/базата и сканиране на Facebook – запазва студенти в базата и обновява данните за постовете им – могат да бъдат разгледани в „Класация“; сканиране на директория, студенти от CSV/базата и Facebook – запазва студенти в базата, показва таблица с това кои от студентите в списъка имат картинки с покани в директорията и информацията за постовете им от Facebook може да бъде разгледана в „Класация“.


Таб „Настройки“

![Alt text](/docs/4.png?raw=true)
 
Тук могат да бъдат променяни основните настройки на приложението. Системата не разрешава полетата да бъдат празни.


Таб „Класация“
![Alt text](/docs/5.png?raw=true)

![Alt text](/docs/6.png?raw=true)
 
 
Тук може да бъде разгледана информация за постовете на студентите във Facebook групата, след като е била сканирана от системата. При mouseover на снимките, те се уголемяват. Клетките „Създаден“ стават червени, когато потребител не е качил поканата си навреме (по-рано от полунощ на деня на презентацията), или когато поканата му е била автоматично генерирана, или когато няма информация за поканата му (напр. сканиран е CSV файл без да се сканира Facebook групата).

## 6. Примерни данни 
Примерни CSV файлове, както и директория с примерни покани има в папката /test/ на проекта. В db.sql файла, освен структурата на таблиците, се съдържат и няколко INSERT-а за примерни настройки на Facebook API-то. В случая е създадена тестова група във Facebook, която се управлява от личния ми акаунт и в нея има постнати няколко примерни покани.


## 7. Описание на програмния код 
Системата се базира бегло на MVC модела. Има модели и изгледи, а контролерите за отделните функционалности се намират в главната директория.

Използваните готови библиотеки се намират в /libraries/. В /helpers/ са помощните класове.

Всеки модел наследява класа Database, който съдържа конфигурацията за връзка с базата от данни и конструктор, в който се извършва свързването с базата.

Когато на контролер му е необходимо да използва даден модел, той се включва към файла с “require_once” и се инстанцира обект от съответния клас.

Когато на контролер му е необходимо да визуализира даден изглед, той се включва към файла по същия начин като моделите. Всеки контролер, който на даден етап ще визуализира изглед, задължително включва общите изгледи “header” и “footer”, в които има HTML, общ за всички страници на системата (напр. лого, меню, съобщения при работа и др.)

В /assets/ папката се съдържат снимките, стиловете и скриптовете, които се използват в системата.

script.js и style.css са файлове, които са включени в header файла, т.е. са валидни за всички страници на системата.

Файлове като process.js са валидни само за страницата Process (Сканиране).

## 8. Приноси на студента, ограничения и възможности за бъдещо разширение 
В условието на задачата се споменаваше, че трябва да е „зададено името на потребителя“, когато се проверяват постовете във Facebook. За съжаление, обаче, Facebook групите се управляват само чрез user access token, а в документацията на Graph API се споменава следното:

„Information (name and id) about the Profile that created the Post. If you read this field with a user access token, it returns only the current user.“

https://developers.facebook.com/docs/graph-api/reference/v3.0/post

Това означава, че няма как да се получи информация за името на потребителя, който е създал поста. Поради тази причина, системата засича само текста на поста и търси факултетен номер в него).

Друг проблем беше срещнат и с условието да се изтриват автоматично генерираните постове, ако системата засече, че потребителят вече е качил своя покана. Въпреки че в документацията е споменато, че приложението може да изтрива постове, които е създало в групата, по някаква причина при опит за изтриването на пост от групата, API-то връщаше грешка, гласяща "(#200) The user hasn't authorized the application to perform this action".

https://developers.facebook.com/docs/graph-api/reference/v3.0/post#deleting

Това продължаваше да се случва, дори когато user access token-ът беше получил всички възможни права от потребителя. Пуснат е bug report в официалния support форум на Facebook Developers по темата, но за съжаление отговор не беше получен навреме:

https://developers.facebook.com/support/bugs/194389697937639

## 9. Какво научих
Научих се да използвам API-то на Facebook чрез PHP и най-вече как да реализирам системата така, че да се съобразява с неговите изисквания и ограничения. Също така, затвърдих знанията си по PHP, HTML, CSS, JS и SQL.
