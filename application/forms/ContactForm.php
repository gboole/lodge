<?php

class Application_Form_ContactForm extends Zend_Form

{

	public function __construct($options = null)

	{

		parent::__construct($options);

		$this->setName('contact_us');



		$title = new Zend_Form_Element_Select('title');

		$title->setLabel('Title')

		->setMultiOptions(array('mr'=>'Mr', 'mrs'=>'Mrs'))

		->setRequired(true)->addValidator('NotEmpty', true);



		$firstName = new Zend_Form_Element_Text('firstName');

		$firstName->setLabel('First name')

		->setRequired(true)

		->addValidator('NotEmpty');



		$lastName = new Zend_Form_Element_Text('lastName');

		$lastName->setLabel('Last name')

		->setRequired(true)

		->addValidator('NotEmpty');

		 

		$email = new Zend_Form_Element_Text('email');

		$email->setLabel('Email address')

		->addFilter('StringToLower')

		->setRequired(true)

		->addValidator('NotEmpty', true)

		->addValidator('EmailAddress');





		$submit = new Zend_Form_Element_Submit('submit');

		$submit->setLabel('Contact us');



		$this->addElements(array($title, $firstName,

		$lastName, $email, $submit));



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
