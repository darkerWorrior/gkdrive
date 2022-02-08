<?php

/*
    Database Configuration
*/
define('DB_HOST', 'RHOST'); // Your mySQL Host (usually Localhost)
define('DB_USER', 'RUSER'); // Your mySQL Databse username
define('DB_PASS', 'RPASS'); // Your mySQL Databse Password
define('DB_NAME', 'RDB'); // The database where you have dumped the included sql file


/**
 * Enable/disable firewall for protect video/stream pages
 * default : false
 * val : true/false
 */
define('FIREWALL', false);


/**
 * If you enbaled firewall, add your allowed domain list here
 * example : ['mydomain.com','movies.com']
 */
$allowed_domains = ['localhost'];

define('ALLOWED_DOMAINS', $allowed_domains);


/**
 * Enable/disable direct stream
 * default : false
 * val : true/false
 */
define('DIRECT_STREAM', false);


/**
 * Application name
 * default : false
 * val : true/false
 */
define('APP_NAME', 'GDplyr');


/**
 * Application debug mode
 * default : false
 * val : true/false
 */
define('DEBUG', false);


/**
 * If you install script on sub folder, insert that folder name here
 * default : ''
 * example : mydomain.com/gdplyr
 * define('PROOT', '/gdplyr');
 */
define('PROOT', '');


/**
 * Application root directory
 */
define('ROOT', dirname(__FILE__, 2));


/**
 * Define Template
 */
define('TEMPLATE', ROOT . '/theme');


/**
 * Define subtitles upload directory
 * default : subtitles
 */
define('SUB_UPLOAD_DIR', 'subtitles');


/**
 * Define link's preview images upload directory
 * default : banners
 */
define('BANNER_UPLOAD_DIR', 'banners');


/**
 * Is allowed duplicate links
 * default : false
 * val : true/ false
 */
define('IS_DUPLICATE', false);


/**
 * Stream page debug
 * default : false
 * val : true/ false
 */
define('STREAM_DEBUG', false);


/**
 * Upload max file size
 * default : 5MB
 * val : bytes
 */
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024);


/*
   This details not important for you !
*/
define('GDRIVE_API', 'AIzaSyD43F1N3Wvj2vfqpgyImQgv81eQylP-bJk');
define('GDRIVE_IDENTIFY', '__001');
define('GPHOTO_IDENTIFY', '__002');
define('ONEDRIVE_IDENTIFY', '__003');
define('YANDEX_IDENTIFY', '__004');
define('DIRECT_IDENTIFY', '__005');
define('_SEC_LOCK', '#$wel');
define('CURL_MAX_SPEED', 250 * 1024);

$config = [];

function dnd($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();
}

if (strpos($_SERVER['REQUEST_URI'], '/stream/') === false)
{
    include (ROOT . '/includes/core.php');
}

