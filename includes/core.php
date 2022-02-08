<?php


define('APP', true);
define('VERSION', '2.2');



//start session
if(!isset($_SESSION))
{
    session_start();
}


// Error Reporting
if(!DEBUG)
{
    error_reporting(0);
}
else
{
    ini_set('display_error',1);
    ini_set('error_reporting',E_ALL);
    error_reporting(-1);
}


// Connect to Database
include(ROOT.'/includes/Database.class.php');
$db = new Database($config);
$config = $db->get_config();

//Set timezone
if(!empty($config['timezone'])){
    date_default_timezone_set($config["timezone"]);
  }

// Start Application
include(ROOT.'/includes/App.class.php');
$app = new App($db,$config);


// Get theme functions file
if(file_exists(ROOT.'/theme/functions.php'))
{
    include(TEMPLATE.'/functions.php');
}


//Application Helpers
include(ROOT.'/includes/Helper.class.php');
include(ROOT.'/includes/Proxy.class.php');
include(ROOT.'/includes/Cache.class.php');
include(ROOT.'/includes/Stream.class.php');
include(ROOT.'/includes/Upload.class.php');
include(ROOT.'/includes/Link.class.php');
include(ROOT.'/includes/Server.class.php');
include(ROOT.'/includes/User.class.php');
include(ROOT.'/includes/library/JSPacker.php');

include(ROOT.'/includes/sources/GDrive.class.php');
include(ROOT.'/includes/sources/GPhoto.class.php');
include(ROOT.'/includes/sources/OneDrive.class.php');
include(ROOT.'/includes/sources/Yandex.class.php');



function getThemeURI()
{
    return PROOT . '/theme';
}

function getPlayerURI($p)
{
    $p = ROOT."/players/{$p}";
    if(file_exists($p))
    {
        return $p;
    }
}













