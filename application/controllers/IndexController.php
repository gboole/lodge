<?php

class IndexController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		ini_set('display_errors', 'on');
		phpInfo();
		$this->view->assign('title', 'Hello, World!');
		$this->view->assign('wellcome','Wellcome to my site. This site is built using Zend Framework. Enjoy it!');
		$this->view->assign('webmaster','Wiwit');
		$this->_redirect('/user/userpage');
	}


}

