<?php

class Application_Model_User
{

	protected $_id;
	protected $_username;
	protected $_password;
	protected $_firstname;
	protected $_lastname;

	public function __construct(array $options = null)
	{
		if (is_array($options)) {
			$this->setOptions($options);
		}
	}

	public function __set($name, $value)
	{
		$method = 'set' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid user property');
		}
		$this->$method($value);
	}

	public function __get($name)
	{
		$method = 'get' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid user property');
		}
		return $this->$method();
	}

	public function setOptions(array $options)
	{
		$methods = get_class_methods($this);
		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)) {
				$this->$method($value);
			}
		}
		return $this;
	}

	public function setUsername($text)
	{
		$this->_username = (string) $text;
		return $this;
	}

	public function getUsername()
	{
		return $this->_username;
	}

	public function setPassword($text)
	{
		$this->_password = (string) $text;
		return $this;
	}

	public function getPassword()
	{
		return $this->_password;
	}

	public function setFirstname($text)
	{
		$this->_firstname = (string) $text;
		return $this;
	}

	public function getFirstname()
	{
		return $this->_firstname;
	}

	public function setLastname($text)
	{
		$this->_lastname = (string) $text;
		return $this;
	}

	public function getLastname()
	{
		return $this->_lastname;
	}


	public function setId($id)
	{
		$this->_id = (int) $id;
		return $this;
	}

	public function getId()
	{
		return $this->_id;
	}

}

