<?php

require_once("Participant.php");

class ParticipantsArray
{

    private $_participants = array();

    public function __construct()
    {
        $file = fopen("../txt/participants.txt", "r");

        while (!feof($file)) {
            $line = fgets($file, 256);
            if ($line != "") {
                $values = explode(":", $line);
                $this->_participants[] = new Participant($values[0], $values[1], $values[2], $values[3], $values[4], $values[5]);
            }
        }
    }

    public function getParticipants()
    {
        return $this->_participants;
    }

    public function setParticipants($participants)
    {
        $this->_participants = $participants;
    }

    public function exists($ci)
    {
        foreach ($this->_participants as $participant) {
            if ($participant->getCi() == $ci) {
                return true;
            }
        }
        return false;
    }

    public function getParticipant($ci)
    {
        foreach ($this->_participants as $participant) {
            if ($participant->getCi() == $ci) {
                return $participant;
            }
        }
    }

    public function enterParticipant($participant)
    {
        if (!$this->exists($participant->getCi())) {
            $this->_participants[] = $participant;
            $this->saveParticipant($participant);
            return "Participante ingresado";
        } else {
            return "Participante ya registrado";
        }
    }

    public function saveParticipant($participant)
    {
        $file = fopen("../txt/participants.txt", "a");
        $line = implode(":", (array)$participant);
        fputs($file, $line);

        fclose($file);
    }

    public function listParticipants()
    {
        foreach ($this->_participants as $participant) {
            echo $participant;
        }
    }

    public function removeParticipants()
    {
        $file = fopen("../txt/participants.txt", "w");
        fwrite($file, "");
        $this->_participants = array();
    }

    public function removeParticipant($ci)
    {
        foreach ($this->_participants as $key => $participant) {
            if ($participant->getCi() == $ci) {
                $x = $key;
            }
        }
        if (isset($x)) {
            unset($this->_participants[$x]);

            $file = fopen("../txt/participants.txt", "w");
            fwrite($file, "");

            foreach ($this->_participants as $participant) {
                $line = implode(":", (array)$participant);
                fputs($file, $line);
            }

            return "Participante eliminado con exito";
        }

        return "Participante no registrado";
    }
}
