-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 11 2023 г., 12:15
-- Версия сервера: 10.1.48-MariaDB
-- Версия PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `video`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `title` varchar(64) CHARACTER SET utf8 NOT NULL,
  `url` varchar(64) CHARACTER SET utf8 NOT NULL,
  `image` varchar(64) CHARACTER SET utf8 NOT NULL,
  `specs` text CHARACTER SET utf8
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `title`, `url`, `image`, `specs`) VALUES
(1, 'Видеонаблюдение', 'video', 'camera.png', '[\"Основной цвет\",\"Установка камеры\",\"Тип матрицы\",\"Физический размер матрицы\",\"Число пикселей матрицы\",\"Максимальное разрешение\",\"Максимальная частота кадров\",\"Форматы записи видео\",\"Встроенный микрофон\",\"Стандарт Wi-Fi\",\"Ночная съемка\",\"Система обнаружения движения\",\"Рабочая температура\"]'),
(2, 'Домофонные системы', 'domofon', 'domofon.png', '[\"Основной цвет\",\"Назначение\",\"Материал\",\"Поддержка\",\"Напряжение питания\",\"Габариты\"]'),
(3, 'Контроль доступа', 'control', 'control.png', '[\"Тип механизма\",\"Рабочая температура\"]'),
(4, 'Охрана', 'security', 'security.png', '[\"Способ установки\",\"Функции\",\"Операционная система\",\"Тип оповещения\",\"Пульт управления\",\"Комплектация\"]'),
(5, 'Климат контроль', 'climat', 'climat.png', '[\"Назначение прибора\",\"Гигростат\",\"Обслуживаемая площадь\",\"Ионизация\",\"Дисплей\",\"Дополнительно\"]'),
(6, 'Сигнализация', 'alarm', 'alarm.png', '[\"Материал корпуса\",\"Протокол передачи данных\",\"Тип оповещения пользователя\",\"Напряжение питания\",\"Модель потребления\",\"Комплектация\"]\n'),
(7, 'Карты памяти', 'cards', 'sdcard.jpg', '[\"Объём\",\"Максимальная скорость записи\",\"Максимальная скорость чтения\",\"Класс скорости\",\"Адаптер в комплекте\"]'),
(8, 'Сетевые кабели и коммутация', 'cables', 'cables.jpg', '[\"Длина\",\"Тип витой пары\",\"Категория\",\"Макс. скорость передачи данных\",\"Материал проводника\",\"Тип разъема\",\"Защита замка\"]');

-- --------------------------------------------------------

--
-- Структура таблицы `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `email` varchar(128) CHARACTER SET utf8 NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL,
  `time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `message`, `time`, `status`) VALUES
(1, 'admin', 'admin@lancom.ru', 'asdasd', 1684150233, 0),
(2, 'lan', 'admin@sc.ru', 'asdasd', 1684150338, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user` varchar(11) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `time` int(11) NOT NULL,
  `type` varchar(32) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `notifications`
--

INSERT INTO `notifications` (`id`, `user`, `content`, `time`, `type`) VALUES
(1, '1', '[]', 1684345104, 'ACCOUNT_CREATION'),
(2, '1', '{\"order_id\":1}', 1684347034, 'ORDER_CREATION'),
(3, '1', '{\"order_id\":\"1\"}', 1684347048, 'ORDER_PAYMENT'),
(4, '1', '{\"order_id\":\"1\",\"0\":\"order_status\"}', 1684347102, 'ORDER_CONFIRMATION'),
(5, '1', '{\"order_id\":\"1\",\"0\":\"order_status\"}', 1684351756, 'ORDER_CONFIRMATION'),
(6, '1', '{\"order_id\":\"1\",\"0\":\"order_status\"}', 1684352917, 'ORDER_CONFIRMATION'),
(7, '1', '{\"order_id\":\"1\",\"0\":\"order_status\"}', 1684352973, 'ORDER_CONFIRMATION'),
(8, '1', '{\"order_id\":2}', 1684700026, 'ORDER_CREATION'),
(9, '1', '{\"order_id\":\"2\"}', 1684700124, 'ORDER_PAYMENT'),
(10, '1', '{\"ip\":\"127.0.0.1\"}', 1687000330, 'LOGIN');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `products` text CHARACTER SET utf8mb4 NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `preview` varchar(64) CHARACTER SET utf8 NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `time`, `products`, `status`, `preview`, `price`) VALUES
(1, 1, 1684347034, '{\"1\":\"1\",\"3\":\"1\"}', 3, 'f43hefg.png', 17240),
(2, 1, 1684700026, '{\"1\":\"1\",\"3\":\"1\"}', 1, 'f43hefg.png', 17240);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `price` int(11) NOT NULL,
  `type` varchar(64) CHARACTER SET utf8 NOT NULL,
  `model` varchar(64) CHARACTER SET utf8 NOT NULL,
  `article` varchar(32) CHARACTER SET utf8 NOT NULL,
  `specs` text CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `rating` int(11) NOT NULL DEFAULT '0',
  `image` varchar(32) CHARACTER SET utf8 NOT NULL,
  `category` varchar(64) CHARACTER SET utf8 NOT NULL,
  `manufacturer` varchar(64) CHARACTER SET utf8 NOT NULL,
  `date_added` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `related` text CHARACTER SET utf8
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `type`, `model`, `article`, `specs`, `description`, `rating`, `image`, `category`, `manufacturer`, `date_added`, `amount`, `related`) VALUES
(1, 'EZVIZ Mini Trooper Универсальная Wi-Fi камера с базовой станцией', 16990, 'Wi-Fi или IP-камера', 'CS-CV316', 'SH-05C-12U60/60', '{\"Основной цвет\":\"Белый\",\"Установка камеры\":\"вне помещения, в помещении\",\"Тип матрицы\":\"Тип матрицы\",\"Физический размер матрицы\":\"1\\/4 дюйма\",\"Число пикселей матрицы\":\"2 мп\",\"Максимальное разрешение\":\"1280x720\",\"Максимальная частота кадров\":\"15 кадров\\/с\",\"Форматы записи видео\":\"H.264\",\"Встроенный микрофон\":\"есть\",\"Стандарт Wi-Fi\":\"802.11ac\\/b\\/g\\/n\",\"Ночная съемка\":\"есть\",\"Система обнаружения движения\":\"есть\",\"Рабочая температура\":\"от -10° до 55°C\"}', 'Скажи проводам нет\r\nMini Trooper - уникальная беспроводная камера, которая идет в комплекте с базовой станцией. Камера работает от комплекта батарей, ее можно использовать как в помещении, так и на улице.\r\nНикаких проводов. Совсем никаких.\r\nБольше не придется ломать голову, куда поставить камеру, чтобы провести к ней провода. Mini Trooper можно установить в абсолютно в любом удобном для вас месте.\r\nНепревзойденное качество видео\r\nКамеры EZVIZ обладают непревзойденным качеством HD видео. В отличие от любой другой камеры, запатентованный динамический алгоритм передачи видео EZVIZ обеспечивает четкие и яркие снимки в течение всего дня.\r\nСрок работы от батарей до 9 месяцев*\r\nКамера работает автономно от 4х батарей CR123A. Функция просмотра уровня заряда (в приложении EZVIZ) напомнит вам о необходимости подзарядки.* в зависимости от условий эксплуатации', 0, 'f43hefg.png', 'video', 'Ezviz', 1681735278, 12, '[\"3\"]'),
(2, 'IP-камера Digma DiVision 201', 1799, 'Wi-Fi или IP-камера', 'DiVision 201', 'M-7YJWAF', '{\"Основной цвет\":\"Белый\",\"Установка камеры\":\"в помещении\",\"Тип матрицы\":\"CMOS\",\"Физический размер матрицы\":\"1/4 дюйма\",\"Число пикселей матрицы\":\"1 мп\",\"Максимальное разрешение\":\"1280x720\",\"Максимальная частота кадров\":\"15 кадр./сек\",\"Форматы записи видео\":\"H.264, MJPEG\",\"Встроенный микрофон\":\"есть\",\"Стандарт Wi-Fi\":\" 4 (802.11n)\",\"Ночная съемка\":\"есть\",\"Система обнаружения движения\":\"есть\",\"Рабочая температура\":\"от -10° до 55°C\"}', 'IP-камера Digma DiVision 201 является высокотехнологичным и надежным оборудованием, которое идеально подойдет для установки в какое-нибудь помещение — например, в офис. Данное устройство отличается высоким качеством записи видео — максимальное разрешение составляет 1280x720, при этом число кадров в секунду может достигать отметки в 15 кадров/сек.\nIP-камера Digma DiVision 201 подключается беспроводным способом. Работая в паре со специальным приложением, камера сможет передавать вам данные на смартфон в любую точку мира. Кроме того, можно настроить и режим передачи видео — камера может реагировать на движение или какой-либо определенный сценарий, а также работать в цикличном режиме. Запись можно осуществлять как на накопитель формата microSD (объем может достигать 64 Гб), так и какое-нибудь облачное хранилище данных. Отдельно отметим, что отлично себя камера показывает и в темное время суток, что сделает ее использование максимально эффективным и полезным. Комплектуется модель специальным адаптером питания, кабелем USB и комплектом, с помощью которого осуществляется крепление устройства в необходимое место.', 0, '04875d24.jpg', 'video', 'Digma', 1684153896, 1, '[\"3\"]'),
(3, 'Патч-корд DEXP HtsPcUSt5E050', 250, 'патч-корд', 'DEXP HtsPcUSt5E050', 'HGQETEMR', '{\"Длина\":\"5 м\",\"Тип витой пары\":\"U/UTP\",\"Категория\":\"5e\",\"Макс. скорость передачи данных\":\"1000 Мбит\",\"Материал проводника\":\"омедненный алюминий\",\"Тип разъема\":\"RJ-45\",\"Защита замка\":\"есть\"}', 'Патч-корд DEXP это длинный 5-ти метровый кабель с типом витой пары UTP и многопроволочными жилами внутри. Долгий эксплуатационный срок делает изделию отличную репутацию. Патч-корд многожильный, а не цельный, поэтому у кабеля улучшенные гибкость и прочность. На концах кабеля расположены стандартные коннекторы RJ-45, которые заменяются, если необходимо, специальным инструментом.\nDEXP - это патч-корд категории 5e с прямым обжимом, используется в процессе строительства компьютерных систем из кабеля, а так же когда нужно подключить стационарный ПК к сети Интернет. Данная модель работает в любых условиях.', 0, 'd91265cb.jpg', 'cables', 'DEXP', 1684158883, 21, NULL),
(4, 'Трубка домофона J2000-8 белый', 180, 'трубка домофона', 'J2000-8', 'NMROF7AN', '{\"Основной цвет\":\"белый\",\"Назначение\":\"для домофона\",\"Материал\":\"пластик\",\"Поддержка\":\"FILMANN, KEYMAN, Lascomex, MARSHAL, RAIKMANN\",\"Напряжение питания\":\"220В\",\"Габариты\":\"196*85*50\"}', 'Трубка домофона J2000-8 относится к категории цифровых домофонных трубок, что определяет ее совместимость с широчайшим модельным рядом подъездных цифровых домофонов фирм FILMANN, KEYMAN, Lascomex, MARSHAL, RAIKMANN и др. За счет классической белой расцветки устройство будет хорошо смотреться в интерьере вашей прихожей, а использование в качестве материала изготовления пластика обеспечило ему не только практичный уход, но и легкость (вес не превышает 220 г). Функционал трубки домофона J2000-8 предусматривает возможность настройки громкости вызова, светодиодный индикатор вызова, а также кнопки вызова консьержа и управления замком подъездной двери. Размеры устройства - 196x85x20 мм (длина, ширина и высота соответственно).', 0, 'ecee470a.jpg', 'domofon', 'Domofon', 1684738135, 5, NULL),
(5, 'Электронный замок СКУД Konan K2 (М)', 7900, 'СКУД', 'K2 (M)', 'ZAI7DCD3', '{\"Тип механизма\":\"электронный\",\"Рабочая температура\":\"-40°С до +55°С\"}', '— Разблокировка: приложение + отпечаток пальца + пароль + карта\n— Управление приложением, пароль генерируется из приложения и может быть установлен до ограничений по времени\n— Дата запуска в облачном сервисе Пользователь может быть добавлен или удален удаленно\n— 4 записи Attendace можно проверить удаленно Историю можно проверить удаленно\n— Одно приложение может управлять множеством систем Легкое обновление от обычной системы доступа к двери до bluetooth Смарт-системы доступа к двери\n— Делитесь eKeys через приложение с разными уровнями авторизации.\n— Защита клавиатуры: клавиатура блокируется на 5 минут после 5 нажатий неправильного кода доступа. Пароль Psuedo: ключ с любыми цифрами, замок разблокируется, если последние цифры состоят из реального кода доступа.\n— Автоматическая блокировка: Автоматическая блокировка в течение 5 секунд после разблокировки.\n— Мониторинг в реальном времени: Администратор получает push-уведомление, когда пользователь разблокируется с помощью приложения. Вход на другом мобильном устройстве: при входе на другом мобильном устройстве предыдущий автоматически выходит из системы.\n— Уведомление о сообщении: сообщение отправляется предыдущему администратору при сбросе блокировки.', 0, '74b32ed3.jpg', 'control', 'Konan', 1684745522, 1, NULL),
(6, 'Комплект умного дома Rubetek RK-3531', 1399, 'комплект умного дома', 'RK-3531', '4V2X959K', '{\"Способ установки\":\"проводной\\беспроводной\",\"Функции\":\"дистанционное управление электрическими приборами, оповещение об открытии двери, оповещение о движении\",\"Операционная система\":\"Android (7.0 и выше), iOS (13.0 и выше)\",\"Тип оповещения\":\"SMS-сообщения, push-сообщение\",\"Пульт управления\":\"нет\",\"Комплектация\":\"датчик движения, датчик открытия, умная розетка\"}', 'Комплект умный дом Rubetek RK-3531 «Контроль доступа» представляет собой оборудование, которое оповещает о попытках несанкционированного вторжения в дом или офис. Набор содержит датчик движения, датчик открытия, умную розетку. При обнаружении системой открытия дверей или окна, а также какого-либо движения, она посылает на смартфон уведомление в виде SMS-сообщения, либо push-сообщения. Для управления устройством нужно установить специальное приложение, которое позволяет включать и выключать электрические приборы.\nЦентром системы Rubetek RK-3531 «Контроль доступа» служит умная розетка, принимающая сигнал от беспроводных датчиков и передающая сообщения на смартфон. Для связи с датчиками розетка использует модуль Wi-Fi. Комплект подходит для установки в квартире или доме.', 0, '5e1ecbeb.jpg', 'security', 'Rubetek', 1684745978, 6, NULL),
(7, 'Климатический комплекс REMEZair RMCL-401', 18399, 'климатический комплекс', 'RMCL-401', 'PVU3GE1C', '{\"Назначение прибора\":\"вентиляция, ионизация воздуха, охлаждение воздуха, очистка воздуха, увлажнение воздуха\",\"Гигростат\":\"нет\",\"Обслуживаемая площадь\":\"100м2\",\"Ионизация\":\"есть\",\"Дисплей\":\"нет\",\"Дополнительно\":\"3 режима работы, корпус на колесиках, поворот ламелей 80°, функция самоочистки\"}', 'Климатический комплекс REMEZair RMCL-401 представлен в элегантном белом корпусе с большой емкостью на 8 л воды. Техника выполняет функции вентилятора и кондиционера, ионизатора, очистителя и увлажнителя. Мощность системы позволяет обслуживать пространство в 100 м².\nПрибор REMEZair RMCL-401 оснащен 3-мя режимами, таймером и программой самоочистки. Колесики помогут быстро и без лишних усилий передвинуть комплекс в нужную часть комнаты или другое помещение. Для управления функциями используется удобный пульт ДУ.', 0, '01dae62d.jpg', 'climat', 'REMEZair', 1684746629, 3, NULL),
(8, 'Датчик открытия Ezviz CS-T6-A', 899, 'датчик открытия', 'CS-T6-A', '78J6FP6G', '{\"Материал корпуса\":\"пластик\",\"Протокол передачи данных\":\"радиоканал\",\"Тип оповещения пользователя\":\"push-сообщение\",\"Напряжение питания\":\"5 В\",\"Модель потребления\":\"от аккумулятора\",\"Комплектация\":\"кабель питания, основание, стикер для монтажа\"}', 'Датчик открытия Ezviz CS-T6-A – одно из важных дополнений системы умного дома. Благодаря уведомлениям через специальное приложение вы всегда будете в курсе события, отсутствуя в собственном доме. Прибор совместим с центром управления Elviz A1 благодаря беспроводной технологии связи с охранной системой через радиоканал.\nУправление устройством Ezviz CS-T6-A осуществляется с собственного смартфона, что делает эксплуатацию максимально удобной. Прибор действует от аккумулятора, мощностью на 3 месяца эксплуатации. Имеется возможность подключения к общей системе сирены и видеокамеры. Рекомендуется использовать при влажности 10-90% и температуре окружающей среды -10~+55 градусов.', 0, 'a3ebe6e1.jpg', 'alarm', 'Ezviz', 1684752789, 6, ''),
(9, 'Карта памяти Samsung EVO Plus microSDXC 128 ГБ [MB-MC128KA/CN]', 1799, 'карта памяти', 'MB-MC128KA/CN', 'IM1D426B', '{\"Объём\":\"128\",\"Максимальная скорость записи\":\"130 Мбайт/сек\",\"Максимальная скорость чтения\":\"130 Мбайт/сек\",\"Класс скорости\":\"A2, UHS Class 3, Video Class 30\",\"Адаптер в комплекте\":\"есть\"}', 'Если вы ищите надежный накопитель данных для вашего смартфона или планшета, с которым гарантируется высокая степень быстродействия, то карта памяти Samsung EVO Plus станет для вас идеальным вариантом. Модель представлена в широко востребованном формате microSDXC, позволяющим использовать ее со многими представителями мобильной техники, присутствующей на рынке. Внушительный объем накопителя, достигающий 128 ГБ, обеспечит возможность хранения на нем огромного количества аудиофайлов, видеороликов и приложений различных форматов.\nКарта памяти Samsung EVO Plus демонстрирует высокий показатель скорости чтения данных, достигающий 130 Мбайт/сек, что гарантирует быстродействие при работе с различными файлами. Высокая степень защиты от внешнего воздействия свидетельствует о надежности накопителя, благодаря чему он подходит для использования в профессиональной фото- и видеосъемке. Наличие адаптера в комплекте поставки расширяет совместимость карты памяти возможностью ее использования в ПК или ноутбуке.', 0, '1c67a413.jpg', 'cards', 'Samsung', 1684776743, 28, '[\"\"]');

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `time` int(11) NOT NULL,
  `ip` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `hash`, `time`, `ip`, `active`) VALUES
(1, 1, 'edd512fd166e3a0d44301760174883f4', 1687000330, 2130706433, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `smart_home`
--

CREATE TABLE `smart_home` (
  `id` int(11) NOT NULL,
  `place` int(11) NOT NULL,
  `area` int(11) NOT NULL,
  `features` varchar(32) CHARACTER SET utf8 NOT NULL,
  `management` int(11) NOT NULL,
  `name` varchar(32) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `smart_home`
--

INSERT INTO `smart_home` (`id`, `place`, `area`, `features`, `management`, `name`, `phone`, `status`, `date`) VALUES
(1, 1, 64, '[2,3,4]', 4, 'Александр', '+7 (917) 915-6465', 0, 1684698942),
(2, 2, 153, '[1]', 1, 'Маргарита', '+7 (964) 374-8293', 0, 1684699002);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(128) CHARACTER SET utf8 NOT NULL,
  `password` varchar(128) CHARACTER SET utf8 NOT NULL,
  `first_name` varchar(128) CHARACTER SET utf8mb4 NOT NULL,
  `last_name` varchar(32) CHARACTER SET utf8 NOT NULL,
  `regdate` int(11) NOT NULL,
  `rank` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `regdate`, `rank`) VALUES
(1, 'admin@sc.ru', '$SHA$2598a3b8b94542a2$c1155898aa13b940f4ec4463fff89dbb45f8872bec341699b7c5b813cc9393f7', 'Алексей', 'Смирнов', 1684345104, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `smart_home`
--
ALTER TABLE `smart_home`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `smart_home`
--
ALTER TABLE `smart_home`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;