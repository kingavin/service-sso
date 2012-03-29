<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initAutoloader()
	{
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('Class_');
		$autoloader->registerNamespace('Twig_');
		$autoloader->registerNamespace('App_');
	}
	
	protected function _initMongoDb()
	{
		$mongoDb = new App_Mongo_Db_Adapter('service-sso');
		App_Mongo_Db_Collection::setDefaultAdapter($mongoDb);
	}
	
	protected function _initSession()
	{
		Zend_Session::start();
	}
	
    protected function _initController()
    {
    	Zend_Controller_Action_HelperBroker::addPath(APP_PATH.'/helpers', 'Helper');
        $controller = Zend_Controller_Front::getInstance();
        $controller->setControllerDirectory(array(
            'default' => APP_PATH.'/default/controllers',
        	'admin' => APP_PATH.'/admin/controllers'
        ));
        
        $controller->throwExceptions(true);
        Zend_Layout::startMvc();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('template');
    }
}