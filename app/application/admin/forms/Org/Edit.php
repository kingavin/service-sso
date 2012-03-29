<?php
class Form_Org_Edit extends Zend_Form
{
	public function init()
	{
		$this->addElement('text', 'orgName', array(
			'label' => '组织名',
			'required' => true
		));
	}
}