<?php
define("BASE_PATH", getenv('BASE_PATH'));
define("CONTAINER_PATH", BASE_PATH.'/service-sso');
define("APP_PATH", CONTAINER_PATH.'/app/application');

$libPath = BASE_PATH.'/include';
$commonLibPath = BASE_PATH.'/libraries/common';
$ssoLibPath = BASE_PATH.'/libraries/service-sso';
set_include_path($libPath.PATH_SEPARATOR.$commonLibPath.PATH_SEPARATOR.$ssoLibPath);

define("APP_ENV", getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production');
define("LOCAL_CHARSET", getenv('LOCAL_CHARSET') ? getenv('LOCAL_CHARSET') : 'UTF-8');

require_once $libPath."/Zend/Application.php";
$application = new Zend_Application(APP_ENV, BASE_PATH.'/configs/sso/application.ini');
$application->bootstrap()->run();