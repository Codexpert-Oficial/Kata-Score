<?php

class Judge
{
    private $_name;
    private $_lastName;
    private $_number;

    public function __construct($name, $lastName, $number)
    {
        $this->_name = $name;
        $this->_lastName = $lastName;
        $this->_number = $number;
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

    public function getNumber()
    {
        return $this->_number;
    }

    public function setNumber($number)
    {
        $this->_number = $number;
    }
}
