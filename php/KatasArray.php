<?php

require_once("Kata.php");

class KatasArray
{

    private $_katas;

    public function __construct()
    {
        $file = fopen("../txt/katas.txt", "r");

        while (!feof($file)) {
            $line = fgets($file, 256);
            $values = explode(":", $line);
            $this->_katas[$values[0]] = new Kata($values[0], $values[1]);
        }

        fclose($file);
    }

    public function getKatas()
    {
        return $this->_katas;
    }

    public function setKatas($katas)
    {
        $this->_katas = $katas;
    }

    public function getKata($id)
    {
        foreach ($this->_katas as $kata) {
            if ($kata->getId() == $id) {
                return $kata;
            }
        }
    }
}
