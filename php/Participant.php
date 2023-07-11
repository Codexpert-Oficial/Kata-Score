<?php

require_once("Category.php");
require_once("Kata.php");

class Participant
{
    private $_name;
    private $_lastName;
    private $_category;
    private $_kata;

    public function __construct($name, $lastName, $category, $kata)
    {
        $this->_name = $name;
        $this->_lastName = $lastName;
        $this->_category = $category;
        $this->_kata = $kata;
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

    public function getCategory()
    {
        return $this->_category;
    }

    public function setCategory($category)
    {
        $this->_category = $category;
    }

    public function getKata()
    {
        return $this->_kata;
    }

    public function setKata($kata)
    {
        $this->_kata = $kata;
    }
}
