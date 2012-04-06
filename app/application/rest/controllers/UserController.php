<?php
class Admin_UserController extends Zend_Controller_Action 
{
	public function indexAction()
	{
		$orgCode = $this->getRequest()->getParam('orgCode');
		if(is_null($orgCode)) {
			
		}
	}
	
	public function createAction()
	{
		
	}
	
	public function editAction()
	{
		$orgCode = $this->getRequest()->getParam('orgCode');
		
		require APP_PATH.'/admin/forms/User/Edit.php';
		$form = new Form_User_Edit();
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$ru = App_Factory::_m('RemoteUser');
			$ruDoc = $ru->addFilter('loginName', $form->getValue('loginName'))
				->fetchOne();
			if(is_null($ruDoc)) {
				$ruDoc = $ru->create();
				$ruDoc->loginName = $form->getValue('loginName');
				$ruDoc->orgCode = $orgCode;
				$ruDoc->password = rand(111111, 999999);
				$ruDoc->save();
			}
		}
		$this->view->form = $form;
	}
	
	public function deleteAction()
	{
		
	}
}