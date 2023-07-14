<?php

require_once("Score.php");

class ScoresArray
{

    private $_scores = array();

    public function __construct($ci)
    {

        $file = fopen("../txt/scores.txt", "r");

        while (!feof($file)) {
            $line = fgets($file, 256);
            if ($line != "") {
                $values = explode(":", $line);
                if ($values[1] == $ci) {
                    $this->_scores[] = new Score($values[0], $values[1], $values[2]);
                }
            }
        }
    }

    public function getScores()
    {
        return $this->_scores;
    }

    public function setScores($scores)
    {
        $this->_scores = $scores;
    }

    public function enterScore($score)
    {
        if (!$this->exists($score->getParticipantCi(), $score->getJudgeNumber())) {
            $this->_scores[] = $score;
            $this->saveScore($score);
            return "Puntaje ingresado";
        } else {
            return "Puntaje ya registrado";
        }
    }

    public function saveScore($score)
    {
        $file = fopen("../txt/scores.txt", "a");
        $line = implode(":", (array)$score);
        fputs($file, $line);
        fclose($file);
    }

    public function exists($ci, $number)
    {
        foreach ($this->_scores as $score) {
            if ($score->getParticipantCi() == $ci && $score->getJudgeNumber() == $number) {
                return true;
            }
        }
        return false;
    }

    public function getMax()
    {
        $position = 0;

        for ($i = 1; $i < count($this->_scores); $i++) {
            if ($this->_scores[$position]->getScoreValue() < $this->_scores[$i]->getScoreValue()) {
                $position = $i;
            }
        }

        return $position;
    }

    public function getMin()
    {
        $position = 0;

        for ($i = 1; $i < count($this->_scores); $i++) {
            if ($this->_scores[$position]->getScoreValue() > $this->_scores[$i]->getScoreValue()) {
                $position = $i;
            }
        }

        return $position;
    }

    public function calcTotal()
    {
        $total = 0;
        foreach ($this->_scores as $score) {
            $total += $score->getScoreValue();
        }

        $max = $this->_scores[$this->getMax()]->getScoreValue();
        $min = $this->_scores[$this->getMin()]->getScoreValue();

        $total -= $max;
        $total -= $min;

        return $total;
    }

    public function removeScores()
    {
        $file = fopen("../txt/scores.txt", "w");
        fwrite($file, "");
        $this->_scores = array();
    }
}
