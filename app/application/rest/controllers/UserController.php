<?php
class Rest_UserController extends Zend_Rest_Controller 
{
	public function init()
	{
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
	}
	
	public function indexAction()
	{
		$orgCode = $this->getRequest()->getParam('orgCode');
		
		$consumer = $this->getRequest()->getParam('consumer');
		$timestamp = $this->getRequest()->getParam('timestamp');
		$token = $this->getRequest()->getParam('token');
		$sig = $this->getRequest()->getParam('sig');
		
//		$result = Class_SSO::validateSig($consumer, $timeStamp, $token, $sig, 15);
		$result = true;
		if($result == 'success') {
			$co = App_Factory::_m('RemoteUser');
			$co->setFields(array('loginName', 'userType'));
			$co->addFilter('orgCode', $orgCode);
			
	        $result = array();
			$co->sort('loginName', 1);
			$data = $co->fetchAll(true);
			$dataSize = $co->count();
			
			$result['data'] = $data;
	        $this->_helper->json($result);
		}
	}
	
	public function getAction()
	{
		
	}
	
	public function postAction()
	{
		
	}
	
	public function putAction()
	{
		
	}
	
	public function deleteAction()
	{
		
	}
}