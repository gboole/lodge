<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Session/Namespace.php';


class UserController extends Zend_Controller_Action
{

	/**
	 * Base redirect
	 */
	public function indexAction()
	{
		$request = $this->getRequest();
		$auth = Zend_Auth::getInstance();


		if(!$auth->hasIdentity()){
			$this->_redirect('/user/loginform');
		}else{
			$this->_redirect('/user/userpage');
		}
	}

	/**
	 * Create new user - setting up the form
	 */
	public function registerAction()
	{
		$this->view->assign('action',"process");
	}

	/**
	 * Create new user - create action
	 */
	public function processAction()
	{
		$translate = Zend_Registry::get('Zend_Translate');
		try{
			$request = $this->getRequest();

			$userMapper = new Application_Model_UserMapper();
			$user = new Application_Model_User();

			$userMapper->findByUsername($request->getParam('username'), $user);

			if ($user->id) {
				$this->view->assign('description',$translate->translate("username_already_exists"));
			}else{
					
				$user->setPassword(md5($request->getParam('password')))
				->setUsername($request->getParam('username'))
				->setFirstname($request->getParam('firstname'))
				->setLastname($request->getParam('lastname'));
				$userMapper->save($user);
				$this->view->assign('description',$translate->translate("registration_success"));
			}

		}catch (Exception $e) {echo $e;}


	}

	public function listAction()
	{
		try{
			$user = new Application_Model_UserMapper();
			$this->view->result = $user->fetchAll();

		}catch (Excetpion $e){echo $e;}

		$this->view->assign('datas',$this->view->result);

	}



	/**
	 * Edit user profile
	 */
	public function editAction()
	{
		try{

		 $request = $this->getRequest();
		 $id 	 = $request->getParam("id");

		 $user = new Application_Model_UserMapper();
		 $result = new Application_Model_User();
		 $user->find($id, $result);

		 $this->view->assign('data',$result);
		 $this->view->assign('action', $request->getBaseURL()."/user/processedit");
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}


	/**
	 * Edit profile - process the data
	 */
	public function processeditAction()
	{
		try{
		 $translate = Zend_Registry::get('Zend_Translate');
		 $request = $this->getRequest();

		 $userMapper = new Application_Model_UserMapper();
		 $user = new Application_Model_User();

		 if($request->getParam('password') !== NULL)
		 {
		 $user->setId($request->getParam('id'))
		 ->setPassword(md5($request->getParam('password')))
		 ->setUsername($request->getParam('username'))
		 ->setFirstname($request->getParam('firstname'))
		 ->setLastname($request->getParam('lastname'));

		 $userMapper->save($user);
		 $this->view->assign('description',$translate->translate("editing_error"));
		 }else{
		 	$this->view->assign('description',$translate->translate("editing_success"));
		 }
		 
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}

	}

	/**
	 * User deletion function, get from request the id
	 * TODO: get from auth/session
	 */
	public function delAction()
	{
		$translate = Zend_Registry::get('Zend_Translate');
		$userMapper = new Application_Model_UserMapper();
		$user = new Application_Model_User();

		$request = $this->getRequest();
		$userMapper->deleteById($request->getParam('id'));

		//TODO Show the result
		$this->view->assign('description',$translate->translate("deleting_success"));
		$this->view->assign('list',$request->getBaseURL()."/user/list");


	}

	/**
	 * Login
	 */
	public function loginformAction()
	{

		$translate = Zend_Registry::get('Zend_Translate');

		$ns = new Zend_Session_Namespace('LodgeNS');

		if(!isset($ns->yourLoginRequest)){
			$ns->yourLoginRequest = 1;
		}else{
			$ns->yourLoginRequest++;
		}

		//TODO Get from configuration
		$ns->setExpirationSeconds(300);

		$request = $this->getRequest();
		$this->view->assign('request', $ns->yourLoginRequest);
		$this->view->assign('action', $request->getBaseURL()."/user/auth");

	}

	/**
	 * Function for the authentication of the User -- to move to AuthController
	 */
	public function authAction(){

		// Get connection with UserMapper
		$userMapper = new Application_Model_UserMapper();
			
		$request 	= $this->getRequest();

		try{
			$auth		= Zend_Auth::getInstance();
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();

			// New Auth Adapter
			$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
			$authAdapter->setTableName($userMapper->getDbTable()->getTableName())
			->setIdentityColumn('username')
			->setCredentialColumn('password');
		}catch(Exception $e){
			echo $e;
		}

		// Set the input credential values
		$uname = $request->getParam('username');
		$paswd = $request->getParam('password');

		$authAdapter->setIdentity($uname);
		$authAdapter->setCredential(md5($paswd));
		
		
		print_r($authAdapter);
		try{
			// Perform the authentication query, saving the result
			$result = $auth->authenticate($authAdapter);

		}catch (Zend_Auth_Adapter_Exception $authE) {echo "Error during authentication: ".$authE;}
		if($result->isValid()){
			// Go!
			$data = $authAdapter->getResultRowObject(null,'password');
			$auth->getStorage()->write($data);
			$this->_redirect('/user/userpage');
		}else{
			// Retry
			$this->_redirect('/user/loginform');
		}
	}


	public function userpageAction(){
		$auth		= Zend_Auth::getInstance();
		$translate = Zend_Registry::get('Zend_Translate');


		if(!$auth->hasIdentity()){
	  $this->_redirect('/user/loginform');
		}

		$request = $this->getRequest();
		$user		= $auth->getIdentity();
		$firstname	= $user->firstname;
		$lastname	= $user->lastname;
		$username	= $user->username;
		$id	= $user->id;
		$logoutUrl  = $request->getBaseURL().'/user/logout';
		$editProfileUrl  = $request->getBaseURL().'/user/editprofile';

		$ns = new Zend_Session_Namespace('HelloWorld');
			
		if(!isset($ns->yourLoginRequest)){
			$ns->yourLoginRequest = 1;
		}else{
			$ns->yourLoginRequest++;
		}
			
		$this->view->assign('request', $ns->yourLoginRequest);

		$this->view->assign('firstname', $firstname);
		$this->view->assign('id', $id);
		$this->view->assign('urllogout',$logoutUrl);
		$this->view->assign('processeditprofile',$editProfileUrl);
	}

	public function logoutAction()
	{
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		$this->_redirect('/user');
	}

	public function statsAction()
	{
		$ns = new Zend_Session_Namespace('HelloWorld');
		foreach ($ns as $index => $value) {
			echo "ns->$index = '$value';";
			echo "<br />";
		}

	}

	public function editprofileAction()
	{
		try{
			$auth		= Zend_Auth::getInstance();

			if(!$auth->hasIdentity()){
				$this->_redirect('/user/loginform');
			}
		 $request = $this->getRequest();
		 $user		= $auth->getIdentity();
		 $id	= $user->id;

		 $user = new Application_Model_UserMapper();
		 $result = new Application_Model_User();
		 $user->find($id, $result);


		 $this->view->assign('data',$result);
		 $this->view->assign('action', $request->getBaseURL()."/user/processedit");
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}


}
?>
