<?php

class Judge
{
    private $_name;
    private $_lastName;
    private $_user;
    private $_password;

    public function __construct($name, $lastName, $user, $password)
    {
        $this->_name = $name;
        $this->_lastName = $lastName;
        $this->_user = $user;
        $this->_password = $password;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getLastName()
    {
        return $this->_lastName;
    }

    public function setLastName($lastName)
    {
        $this->_lastName = $lastName;
    }

    public function getUser()
    {
        return $this->_user;
    }

    public function setUser($user)
    {
        $this->_user = $user;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function setPassword($password)
    {
        $this->_password = $password;
    }
}
