<?php

class Category
{

    private $_ageRange;
    private $_gender;

    public function __construct($ageRange, $gender)
    {
        $this->_ageRange = $ageRange;
        $this->_gender = $gender;
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
}
