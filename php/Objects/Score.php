<?php

class Score
{

    private $_scoreValue;
    private $_participantCi;
    private $_judgeNumber;

    public function __construct($scoreValue, $participantCi, $judgeNumber)
    {

        $this->_scoreValue = $scoreValue;
        $this->_participantCi = $participantCi;
        $this->_judgeNumber = $judgeNumber;
    }

    public function getScoreValue()
    {
        return $this->_scoreValue;
    }

    public function setScoreValue($scoreValue)
    {
        $this->_scoreValue = $scoreValue;
    }

    public function getParticipantCi()
    {
        return $this->_participantCi;
    }

    public function setParticipantCi($participantCi)
    {
        $this->_participantCi = $participantCi;
    }

    public function getJudgeNumber()
    {
        return $this->_judgeNumber;
    }

    public function setJudgeNumber($judgeNumber)
    {
        $this->_judgeNumber = $judgeNumber;
    }
}
