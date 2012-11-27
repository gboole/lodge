<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Session/Namespace.php';


class UserController extends Zend_Controller_Action
{

	public function indexAction()
	{
		$request = $this->getRequest();
		$auth		= Zend_Auth::getInstance();


		if(!$auth->hasIdentity()){
			$this->_redirect('/user/loginform');
		}else{
			$this->_redirect('/user/userpage');
		}
	}

	public function registerAction()
	{

		$request = $this->getRequest();

		$this->view->assign('action',"process");
		$this->view->assign('title','Member Registration');
		$this->view->assign('label_uname','Username');
		$this->view->assign('label_fname','First Name');
		$this->view->assign('label_lname','Last Name');
		$this->view->assign('label_pass','Password');
		$this->view->assign('label_submit','Register');
		$this->view->assign('description','Please enter this form completely:');
	}

	public function processAction()
	{
		try{
			$request = $this->getRequest();

			$userMapper = new Application_Model_UserMapper();
			$user = new Application_Model_User();

			$userMapper->findByUsername($request->getParam('username'), $user);

			if ($user->id) {
				$this->view->assign('description','Utente già presente');
			}else{
					
				$user->setPassword($request->getParam('password'))
				->setUsername($request->getParam('username'))
				->setFirstname($request->getParam('firstname'))
				->setLastname($request->getParam('lastname'));
				$userMapper->save($user);
				$this->view->assign('description','Registration succes');
			}

		}catch (Exception $e) {echo $e;}
		$this->view->assign('title','Registration Process');


	}

	public function listAction()
	{
		try{
			$user = new Application_Model_UserMapper();
			$this->view->result = $user->fetchAll();

		}catch (Excetpion $e){echo $e;}

		$this->view->assign('title','Member List');
		$this->view->assign('description','Below, our members:');
		$this->view->assign('datas',$this->view->result);

	}




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
		 $this->view->assign('title','Member Editing');
		 $this->view->assign('label_uname','Username');
		 $this->view->assign('label_fname','First Name');
		 $this->view->assign('label_lname','Last Name');
		 $this->view->assign('label_pass','Password');
		 $this->view->assign('label_submit','Edit');
		 $this->view->assign('description','Please update this form completely:');
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}


	public function processeditAction()
	{
		try{
		 $request = $this->getRequest();

		 $userMapper = new Application_Model_UserMapper();
		 $user = new Application_Model_User();

		 $user->setId($request->getParam('id'))
		 ->setPassword($request->getParam('password'))
		 ->setUsername($request->getParam('username'))
		 ->setFirstname($request->getParam('firstname'))
		 ->setLastname($request->getParam('lastname'));

		 $userMapper->save($user);

		 $this->view->assign('title','Editing Process');
		 $this->view->assign('description','Editing succes');
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}

	}

	public function delAction()
	{

		$userMapper = new Application_Model_UserMapper();
		$user = new Application_Model_User();

		$request = $this->getRequest();
		$userMapper->deleteById($request->getParam('id'));

		$this->view->assign('title','Delete Data');
		$this->view->assign('description','Deleting succes');
		$this->view->assign('list',$request->getBaseURL()."/user/list");


	}

	public function loginformAction()
	{

		$ns = new Zend_Session_Namespace('HelloWorld');

		if(!isset($ns->yourLoginRequest)){
			$ns->yourLoginRequest = 1;
		}else{
			$ns->yourLoginRequest++;
		}

		$ns->setExpirationSeconds(60);

		$request = $this->getRequest();
		$this->view->assign('request', $ns->yourLoginRequest);
		$this->view->assign('action', $request->getBaseURL()."/user/auth");
		$this->view->assign('title', 'Login Form');
		$this->view->assign('username', 'User Name');
		$this->view->assign('password', 'Password');

	}

	public function authAction(){
		try{

			$request 	= $this->getRequest();
			$registry 	= Zend_Registry::getInstance();
			$auth		= Zend_Auth::getInstance();

			$DB = $registry['DB'];

			$authAdapter = new Zend_Auth_Adapter_DbTable($DB);
			$authAdapter->setTableName('L_USERS')
			->setIdentityColumn('username')
			->setCredentialColumn('password');

			// Set the input credential values
			$uname = $request->getParam('username');
			$paswd = $request->getParam('password');
			$authAdapter->setIdentity($uname);
			$authAdapter->setCredential(md5($paswd));

			// Perform the authentication query, saving the result
			$result = $auth->authenticate($authAdapter);
		}catch (Exception $e) {echo $e;}
		if($result->isValid()){
			$data = $authAdapter->getResultRowObject(null,'password');
			$auth->getStorage()->write($data);
			$this->_redirect('/user/userpage');
		}else{
			$this->_redirect('/user/loginform');
		}
	}


	public function userpageAction(){
		$auth		= Zend_Auth::getInstance();

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

		$this->view->assign('username', $username);
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
		 $this->view->assign('title','Member Editing');
		 $this->view->assign('label_uname','Username');
		 $this->view->assign('label_fname','First Name');
		 $this->view->assign('label_lname','Last Name');
		 $this->view->assign('label_pass','Password');
		 $this->view->assign('label_submit','Edit');
		 $this->view->assign('description','Please update this form completely:');
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}


}
?>
