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
		$mongoDb = new App_Mongo_Db_Adapter('service-sso', Class_Server::getMongoServer());
		App_Mongo_Db_Collection::setDefaultAdapter($mongoDb);
	}
	
	protected function _initSession()
	{
		Zend_Session::start();
	}
	
    protected function _initController()
    {
        $controller = Zend_Controller_Front::getInstance();
        $controller->setControllerDirectory(array(
            'default' => APP_PATH.'/default/controllers',
        	'user' => APP_PATH.'/user/controllers',
        	'admin' => APP_PATH.'/admin/controllers',
        	'rest' => APP_PATH.'/rest/controllers'
        ));
        
        $controller->throwExceptions(true);
        Zend_Layout::startMvc();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('template');
        
        $csu = Class_Session_User::getInstance();
        
        $controller->registerPlugin(new App_Plugin_BackendSsoAuth(
        	$csu,
        	App_Plugin_BackendSsoAuth::SERVICE_SSO,
        	Class_Server::API_KEY,
        	array('admin', 'rest', 'user')
        ));
        //add action helper path
        Zend_Controller_Action_HelperBroker::addPath(APP_PATH.'/helpers', 'Helper');
        
        //add view helper path
        $view = Zend_Layout::getMvcInstance()->getView();
        $view->addHelperPath(APP_PATH.'/helpers','Helper');
    }
    
	protected function _initRouter()
	{
		$controller = Zend_Controller_Front::getInstance();
		$router = $controller->getRouter();
		$csu = Class_Session_User::getInstance();
		$userRoute = new Zend_Controller_Router_Route(
			':userId/user/:controller/:action/*',
			array(
				'userId'    => $csu->getUserId(),
				'module' => 'user',
				'controller' => 'index',
				'action'     => 'index'
			),
			array('userId' => '([a-z0-9]+)')
		);
		$router->addRoute('user', $userRoute);
		$router->addRoute('rest', new Zend_Rest_Route($controller, array(), array('rest')));
	}
}