<?php

class Application_Model_UserMapper
{

	protected $_dbTable;

	public function setDbTable($dbTable)
	{
		if (is_string($dbTable)) {
			$dbTable = new $dbTable();
		}
		if (!$dbTable instanceof Zend_Db_Table_Abstract) {
			throw new Exception('Invalid table data gateway provided');
		}
		$this->_dbTable = $dbTable;
		return $this;
	}

	public function getDbTable()
	{
		if (null === $this->_dbTable) {
			$this->setDbTable('Application_Model_DbTable_User');
		}
		return $this->_dbTable;
	}

	public function save(Application_Model_User $user)
	{
		$data = array(
            'password'   => $user->getPassword(),
            'username' => $user->getUsername(),
       		'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
		);

		if (null === ($id = $user->getId())) {
			unset($data['id']);
			$this->getDbTable()->insert($data);
		} else {
			$this->getDbTable()->update($data, array('id = ?' => $id));
		}
	}

	public function find($id, Application_Model_User $user)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}
		$row = $result->current();
		$user->setId($row->id)
		->setPassword($row->password)
		->setUsername($row->username)
		->setFirstname($row->firstname)
		->setLastname($row->lastname);
	}

	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries   = array();
		foreach ($resultSet as $row) {
			$entry = new Application_Model_User();
			$entry->setId($row->id)
			->setPassword($row->password)
			->setUsername($row->username)
			->setFirstname($row->firstname)
			->setLastname($row->lastname);
			$entries[] = $entry;
		}
		return $entries;
	}


	public function findByUsername($username, Application_Model_User $user)
	{
		$select = $this->getDbTable()->select()->where('username = ?', $username);
		$result = $this->getDbTable()->fetchAll($select);

		if (0 == count($result)) {
			return;
		}
		$row = $result->current();
		$user->setId($row->id)
		->setPassword($row->password)
		->setUsername($row->username)
		->setFirstname($row->firstname)
		->setLastname($row->lastname);
	}

	public function deleteById($id) {
		$this->getDbTable()->delete('id = '.$id);
	}

}

