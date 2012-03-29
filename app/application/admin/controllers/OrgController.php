<?php
class Admin_OrgController extends Zend_Controller_Action
{
	public function indexAction()
	{
		
	}
	
	public function createAction()
	{
		
	}
	
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$ro = App_Factory::_m('RemoteOrganization');
		if(is_null($id)) {
			$roDoc = $ro->create();
		} else {
			$roDoc = $ro->find($id);
			if(is_null($roDoc)) {
				throw new Exception('remote organization not found with id: '.$id);
			}
		}
		
		require APP_PATH.'/admin/forms/Org/Edit.php';
		$form = new Form_Org_Edit();
		$form->populate($roDoc->toArray());
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$roDoc->orgName = $form->getValue('orgName');
			$roDoc->save();
		}
		$this->view->form = $form;
	}
}