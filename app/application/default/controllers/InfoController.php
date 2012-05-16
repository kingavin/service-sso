<?php
class InfoController extends Zend_Controller_Action
{
	public function init()
	{
		$this->_helper->contextSwitch()
             ->addActionContext('get', 'xml')
             ->initContext();
	}
	
	public function getAction()
	{
		$orgCode = $this->getRequest()->getParam('orgCode');
		$userId = $this->getRequest()->getParam('userId');
		
		
		
		$user = App_Factory::_m('RemoteUser')->find($userId);
		
		Zend_Debug::dump($user);
		
		if(is_null($user)) {
			
		}
		
		echo $orgCode.' <br /> '.$userId;
		
//		if() {
			
//		}
	}
}