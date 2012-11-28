<?php

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

	protected $_name = 'L_USERS';

	public function getTableName()
	{
		return $this->_name;
	}

}

