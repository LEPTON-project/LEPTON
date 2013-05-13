<?php

if(defined('WB_PATH')) { die('By security reasons it is not permitted to load \'config.php\' twice!! Forbidden call from \''.$_SERVER['SCRIPT_NAME'].'\'!'); }

define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'lepton_2');
define('TABLE_PREFIX', 'lepton_200_');

define('WB_SERVER_ADDR', '127.0.0.1');
define('WB_PATH', dirname(__FILE__));
define('WB_URL', 'http://localhost:8888/projekte/github/LEPTON_2/upload');
define('ADMIN_PATH', WB_PATH.'/admins');
define('ADMIN_URL', 'http://localhost:8888/projekte/github/LEPTON_2/upload/admins');

define('LEPTON_GUID', '5bf95153-7c34-424a-86f0-1a2cd9911e38');
define('LEPTON_SERVICE_FOR', '');
define('LEPTON_SERVICE_ACTIVE', 0);
define('LEPTON_URL', WB_URL);
define('LEPTON_PATH', WB_PATH);

if (!defined('LEPTON_INSTALL')) require_once(WB_PATH.'/framework/initialize.php');

?>