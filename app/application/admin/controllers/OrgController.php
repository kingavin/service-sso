<?php
class Admin_OrgController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$this->_helper->template->actionMenu(array('create'));
	}
	
	public function createAction()
	{
		$this->_forward('edit');
	}
	
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$ro = App_Factory::_m('RemoteOrganization');
		
		$userDocs = array();
		if(is_null($id)) {
			$roDoc = $ro->create();
		} else {
			$roDoc = $ro->find($id);
			if(is_null($roDoc)) {
				throw new Exception('remote organization not found with id: '.$id);
			}
			
			$userDocs = App_Factory::_m('RemoteUser')->addFilter('orgCode', $id)
				->fetchDoc();
		}
		
		require APP_PATH.'/admin/forms/Org/Edit.php';
		$form = new Form_Org_Edit();
		$form->populate($roDoc->toArray());
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$roDoc->orgName = $form->getValue('orgName');
			$roDoc->save();
			$this->_helper->redirector->gotoSimple('index');
		}
		
		$this->view->orgCode = $id;
		$this->view->userDocs = $userDocs;
		$this->view->form = $form;
		
		if($roDoc->isNewDocument()) {
			$this->_helper->template->actionMenu(array('save'));
		} else {
			$this->_helper->template->actionMenu(array('save', 'delete'));
		}
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$ro = App_Factory::_m('RemoteOrganization');
		$roDoc = $ro->find($id);
		$roDoc->isActive = false;
		$roDoc->save();
		$this->_helper->redirector->gotoSimple('index');
	}
}