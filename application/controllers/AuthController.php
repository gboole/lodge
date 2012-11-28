<?php

require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Session/Namespace.php';


class AuthController extends Zend_Controller_Action{

	protected function _process($values)

	{

		// Get our authentication adapter and check credentials

		$adapter = $this->_getAuthAdapter();

		$adapter->setIdentity($values['username']);

		$adapter->setCredential($values['password']);



		$auth = Zend_Auth::getInstance();

		$result = $auth->authenticate($adapter);

		if ($result->isValid()) {

			$user = $adapter->getResultRowObject();

			$auth->getStorage()->write($user);

			return true;

		}

		return false;

	}

	protected function _getAuthAdapter() {



		$dbAdapter = Zend_Db_Table::getDefaultAdapter();

		$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);



		$authAdapter->setTableName('users')

		->setIdentityColumn('username')

		->setCredentialColumn('password')

		->setCredentialTreatment('SHA1(CONCAT(?,salt))');





		return $authAdapter;

	}

}