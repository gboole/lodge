<?php

class Application_Form_LoginForm extends Zend_Form

{

	public function __construct($options = null)

	{
		$translate = Zend_Registry::get('Zend_Translate');
		parent::__construct($options);

		$this->setName('login');

		$username = new Zend_Form_Element_Text('username');

		$username->setLabel($translate->translate("username"))
		->setRequired(true)
		->addValidator(new Zend_Validate_Alnum())
		->addValidator('NotEmpty');

		$password = new Zend_Form_Element_Password('password');

		$password->setLabel($translate->translate("password"))
		->setRequired(true)
		->addValidator('NotEmpty');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel($translate->translate("login"));
		$this->addElements(array($username,$password, $submit));

		$this->clearDecorators();
		
		$this->addDecorator('FormElements')
		->addDecorator('HtmlTag', array('tag' => '<ul>'))
		->addDecorator('Form');

		$this->setElementDecorators(array(
		array('ViewHelper'),
		array('Errors'),
		array('Description'),
		array('Label', array('separator'=>' ')),
		array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
		));

		// buttons do not need labels
		$submit->setDecorators(array(
		array('ViewHelper'),
		array('Description'),
		array('HtmlTag', array('class'=>'submit-group')),
		));


	}

}
