<?php

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment -- to change to production
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
realpath(APPLICATION_PATH . '/../library'),
get_include_path(),
)));

/**
 * Zend modules
 */
require_once 'Zend/Application.php';
require_once 'Zend/Registry.php';
require_once 'Zend/Loader/Autoloader.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Config/Xml.php';
require_once 'Zend/Cache.php';
require_once 'Zend/Translate.php';


try{
	$config = new Zend_Config_Xml('../application/config.xml','app');

	$title  = $config->title;
	$version = $config->version;
	$description = $config->description;
	$state = $config->state;

	Zend_Registry::set('title',$title);
	Zend_Registry::set('description',$description);
	Zend_Registry::set('version',$version);
	Zend_Registry::set('state',$state);
} catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}




Zend_Loader_Autoloader::getInstance();


// Create application, bootstrap, and run
$application = new Zend_Application(
APPLICATION_ENV,
APPLICATION_PATH . '/configs/application.ini'
);


            ini_set('display_errors', 'on');


            $application->bootstrap()
            ->run();
       
