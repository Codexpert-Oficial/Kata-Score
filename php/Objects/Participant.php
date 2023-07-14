<?php

require_once("KatasArray.php");

class Participant
{

    private $_ci;
    private $_name;
    private $_lastName;
    private $_ageRange;
    private $_gender;
    private $_kata;
    private $_pool;

    public function __construct($ci, $name, $lastName, $ageRange, $gender, $kata, $pool)
    {
        $this->_ci = $ci;
        $this->_name = $name;
        $this->_lastName = $lastName;
        $this->_ageRange = $ageRange;
        $this->_gender = $gender;
        $this->_kata = $kata;
        $this->_pool = $pool;
    }

    public function getCi()
    {
        return $this->_ci;
    }

    public function setCi($ci)
    {
        $this->_ci = $ci;
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

    public function getKata()
    {
        return $this->_kata;
    }

    public function setKata($kata)
    {
        $this->_kata = $kata;
    }

    public function getAgeRange()
    {
        return $this->_ageRange;
    }

    public function setAgeRange($ageRange)
    {
        $this->_ageRange = $ageRange;
    }

    public function getGender()
    {
        return $this->_gender;
    }

    public function setGender($gender)
    {
        $this->_gender = $gender;
    }

    public function getPool()
    {
        return $this->_pool;
    }

    public function setPool($pool)
    {
        $this->_pool = $pool;
    }

    public function getKataName()
    {
        $katas = new KatasArray();
        $kata = $katas->getKata($this->_kata - 1);
        return $kata->getName();
    }

    public function __toString()
    {
        return "<tr><td>" . $this->_ci . "</td><td>" . $this->_name . "</td><td>" . $this->_lastName . "</td><td>" . $this->_ageRange . "</td><td>" . $this->_gender . "</td><td>" . $this->_kata . "</td><td>" . $this->getKataName() . "</td></tr>";
    }
}
