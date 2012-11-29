<?php

	require_once 'Zend/Controller/Action.php';
	
class FormController extends Zend_Controller_Action

{

    function indexAction()

    {

        $this->view->pageTitle = "Zend_Form Example";

        $this->view->bodyCopy = "<p>Please fill out this form.</p>";



        $form = new Application_Form_ContactForm();



        if ($this->_request->isPost()) {

            $formData = $this->_request->getPost();

            if ($form->isValid($formData)) {

                echo 'success';

                exit;

            } else {

                $form->populate($formData);

            }

        }



        $this->view->form = $form;

    }

}