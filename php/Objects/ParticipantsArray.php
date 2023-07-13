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

    public function saveParticipants()
    {
        $file = fopen("../txt/participants.txt", "w");
        foreach ($this->_participants as $participant) {
            $line = implode(":", (array)$participant) . PHP_EOL;
            fwrite($file, $line);
        }
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

    /* public function getParticipantsOfCategory($ageRange, $gender){
        $participantsArray = array();

        foreach($this->_participants as $participant){
            if($participant->getAgeRange() == $ageRange && $participant->getGender() == $gender){
                $participantsArray[] = $participant;
            }
        }

        return $participantsArray;
    } */

    public function sortParticipants()
    {
        if (count($this->_participants) <= 3) {
            foreach ($this->_participants as $participant) {
                $participant->setPool(1);
            }
        } else if (count($this->_participants) <= 4) {
            shuffle($this->_participants);
            $this->_participants[0]->setPool(1);
            $this->_participants[1]->setPool(1);
            $this->_participants[2]->setPool(2);
            $this->_participants[3]->setPool(2);
        } else if (count($this->_participants) <= 10) {
            shuffle($this->_participants);
            if (count($this->_participants) % 2 == 0) {
                $finalPosition = count($this->_participants) / 2;
            } else {
                $finalPosition = (count($this->_participants) / 2) + 0.5;
            }

            for ($i = 0; $i < count($this->_participants); $i++) {
                if ($i < $finalPosition) {
                    $this->_participants[$i]->setPool(1);
                } else {
                    $this->_participants[$i]->setPool(2);
                }
            }
        } else if (count($this->_participants) <= 96) {
            $x = 1;
            for ($i = 0; $i < count($this->_participants); $i++) {
                if ($i < 8 * $x) {
                    $this->_participants[$i]->setPool($x);
                } else {
                    $x++;
                    $this->_participants[$i]->setPool($x);
                }
            }
        }

        $this->saveParticipants();
    }

    public function showParticipantsPool()
    {
        foreach ($this->_participants as $participant) {
            if ($participant->getPool() % 2 == 0) {
                echo "<p class='blue'>";
            } else {
                echo "<p class='red'>";
            }
            echo $participant->getName() . " " . $participant->getLastName() . " - Pool: " . $participant->getPool() . "</p>";
        }
    }
}
