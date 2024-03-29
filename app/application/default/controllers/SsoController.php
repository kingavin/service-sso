<?php
class SsoController extends Zend_Controller_Action
{
	public function init()
	{
		$this->_helper->contextSwitch()
             ->addActionContext('info', 'xml')
             ->initContext();
	}
	
	public function indexAction()
	{
		$cru = Class_Session_RemoteUser::getInstance();
		$this->view->userId = $cru->getUserId();
		$this->view->userData = Zend_Json::decode($cru->getUserData());
	}
	
	public function loginAction()
	{
		$consumer = $this->getRequest()->getParam('consumer');
		$ret = $this->getRequest()->getParam('ret');
		$timeStamp = $this->getRequest()->getParam('timeStamp');
		$token = $this->getRequest()->getParam('token');
		$sig = $this->getRequest()->getParam('sig');
		
		if(empty($ret) || empty($consumer) || empty($token)) {
			throw new Exception('login format error');
		}
		
		$result = Class_SSO::validateLoginUrl($consumer, $ret, $timeStamp, $token, $sig);
		
		if($result != 'success') {
			switch($result) {
				case 'timeout':
					throw new Exception('Request Timeout');
			}
			throw new Exception('Sig Error');
		}
		
		
		$csr = Class_Session_RemoteUser::getInstance();
		if($csr->isLogin()) {
			$newToken = App_Factory::_m('Token')->create();
			$newToken->token = $token;
			$newToken->userId = $csr->getUserId();
			$newToken->userData = $csr->getUserData();
			$newToken->save();
			header("Location: ".$ret);
			exit(0);
		}
		
		$form = new Zend_Form();
		$form->addElement('text', 'loginName', array(
			'label' => '登录名',
			'required' => true
		));
		$form->addElement('password', 'password', array(
			'label' => '密码',
			'required' => true
		));
		$form->addElement('submit', 'submit', array(
			'label' => '确认'
		));
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$cookieData = $csr->login($form->getValues());
			if($cookieData === false) {
				$form->addErrorMessage('用户找不到或者密码错误');
			} else {
				$newToken = App_Factory::_m('Token')->create();
				$newToken->token = $token;
				$newToken->userId = $cookieData['userId'];
				$newToken->userData = $cookieData['userData'];
				$newToken->save();
				header("Location: ".$ret);
				exit(0);
			}
		}
		
		$this->view->form = $form;
		$this->view->errorMsg = $form->getErrorMessages();
	}
	
	public function logoutAction()
	{
		$csr = Class_Session_RemoteUser::getInstance();
		$csr->logout();
		$this->_helper->redirector->gotoSimple('index', 'index', 'default');
	}
	
	public function infoAction()
	{
		$token = $this->getRequest()->getParam('token');
		if(empty($token)) {
			$this->getResponse()->setHeader('result', 'fail');
			$this->getResponse()->setHttpResponseCode(403);
			$this->render('not-found');
		}
		$tokenCo = App_Factory::_m('Token');
		$tokenDoc = $tokenCo->addFilter('token', $token)
			->fetchOne();
		if(!is_null($tokenDoc)) {
			$this->view->userId = $tokenDoc->userId;
			$this->view->userData = Zend_Json::decode($tokenDoc->userData);
			$tokenDoc->delete();
		} else {
			$this->getResponse()->setHeader('result', 'fail');
			$this->getResponse()->setHttpResponseCode(403);
			$this->render('not-found');
		}
	}
}