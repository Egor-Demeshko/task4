<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать файл в "wp-config.php"
 * и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки базы данных
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры базы данных: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'justfitby_docs' );

/** Имя пользователя базы данных */
define( 'DB_USER', 'justfitby_docs_ZWdvcg' );

/** Пароль к базе данных */
define( 'DB_PASSWORD', 'Y0OqPA@Xlo(=' );

/** Имя сервера базы данных */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу. Можно сгенерировать их с помощью
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}.
 *
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными.
 * Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '+`QN!`hB*/z!OPC##EOPBTLe=;8l[j!1wNRJ@:pRZP,ANW-{ui~6O9x Gbf3s]:a' );
define( 'SECURE_AUTH_KEY',  'k7[7$1Q)$Vs0KtLp(I3BBJokv44-H){tk_Y|P-ZFFLn>vF|uhvt.wOR9niWVyy``' );
define( 'LOGGED_IN_KEY',    '0e}2w6})=l&5bWDlJSEsO[v eF*LPv/]e8vXXm}>a?W9v[s,iiq&;m&z=zYQe&y1' );
define( 'NONCE_KEY',        'B(Fw]KP}8S>TV,0KNrgjE-8I~)E6JiHw=0+W#;(3fD&d^fL,~BWx,cX99%4$p*SB' );
define( 'AUTH_SALT',        't?Fv(-i{IkF{RbM#)Rl0qifV3VY?ha,,]fz|*rtUNDA$Qc:i2!DJ/hBSFCR4odb0' );
define( 'SECURE_AUTH_SALT', 'uzOs-y-=aM1zhYONik6~*N#d6{KYKBayxkv[))*)1qN{fw-&pI7G>F`yj?O/y>>B' );
define( 'LOGGED_IN_SALT',   'aq}P=ITD$YrBJ8BmKNzU>vWDp8??6D1sVzU/^{Nitt~k0(WQ^coIip2w{NQ3XeZ#' );
define( 'NONCE_SALT',       'cV2I`bgxHHmTnNq5(VjIHiBApHf(lgmyv^2r@mA[oNCwey)N}:uqh|}g8&_f/]dl' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */



/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
