<?php
class Form_User_Edit extends Zend_Form
{
	public function init()
	{
		$this->addElement('text', 'loginName', array(
			'label' => 'Login Name'
		));
	}
}