<?php

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
realpath(APPLICATION_PATH . '/../library'),
get_include_path(),
)));

/** Zend_Application */

require_once 'Zend/Application.php';

/** Zend Registry **/
require_once 'Zend/Registry.php';
require_once 'Zend/Db/Adapter/Pdo/Mysql.php';
require_once 'Zend/Config/Xml.php';


try{
	$config = new Zend_Config_Xml('../application/config.xml','app');

	$title  = $config->appName;
	$params = $config->database->toArray();

	$DB = new Zend_Db_Adapter_Pdo_Mysql($params);


} catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}


$DB->setFetchMode(Zend_Db::FETCH_OBJ);
Zend_Registry::set('DB',$DB);


require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();



// Create application, bootstrap, and run
$application = new Zend_Application(
APPLICATION_ENV,
APPLICATION_PATH . '/configs/application.ini'
);

ini_set('display_errors', 'on');
	
			
$application->bootstrap()
->run();