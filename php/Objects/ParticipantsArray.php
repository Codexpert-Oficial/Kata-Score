<?php

require_once("Participant.php");
require_once("ScoresArray.php");

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
                $this->_participants[] = new Participant($values[0], $values[1], $values[2], $values[3], $values[4], $values[5], $values[6]);
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
            return "<p>Participante ingresado</p>";
        } else {
            return "<p>Participante ya registrado</p>";
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
            $line = implode(":", (array)$participant);
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
        shuffle($this->_participants);
        if (count($this->_participants) <= 3) {
            foreach ($this->_participants as $participant) {
                $participant->setPool(1 . PHP_EOL);
            }
        } else if (count($this->_participants) <= 4) {
            $this->_participants[0]->setPool(1 . PHP_EOL);
            $this->_participants[1]->setPool(1 . PHP_EOL);
            $this->_participants[2]->setPool(2 . PHP_EOL);
            $this->_participants[3]->setPool(2 . PHP_EOL);
        } else if (count($this->_participants) <= 10) {
            if (count($this->_participants) % 2 == 0) {
                $finalPosition = count($this->_participants) / 2;
            } else {
                $finalPosition = (count($this->_participants) / 2) + 0.5;
            }

            for ($i = 0; $i < count($this->_participants); $i++) {
                if ($i < $finalPosition) {
                    $this->_participants[$i]->setPool(1 . PHP_EOL);
                } else {
                    $this->_participants[$i]->setPool(2 . PHP_EOL);
                }
            }
        } else if (count($this->_participants) <= 96) {
            $x = 1;
            for ($i = 0; $i < count($this->_participants); $i++) {
                if ($i < 8 * $x) {
                    $this->_participants[$i]->setPool($x . PHP_EOL);
                } else {
                    $x++;
                    $this->_participants[$i]->setPool($x . PHP_EOL);
                }
            }
        }

        $this->saveParticipants();
    }

    public function showParticipantsPool()
    {
        foreach ($this->_participants as $participant) {
            if ($participant->getPool() % 2 == 0) {
                $class = "blue";
            } else {
                $class = "red";
            }
            echo "<tr><td> " . $participant->getName() . "</td><td> " . $participant->getLastName() . "</td><td class='" . $class . "'> " . $participant->getPool() . "</td></tr> ";
        }
    }

    public function orderByScore()
    {
        for ($i = 0; $i < count($this->_participants); $i++) {
            for ($a = 0; $a < count($this->_participants) - 1; $a++) {
                $participant1 = $this->_participants[$a];
                $participant2 = $this->_participants[$a + 1];

                $scores1 = new ScoresArray($participant1->getCi());
                $totalScore1 = $scores1->calcTotal();

                $scores2 = new ScoresArray($participant2->getCi());
                $totalScore2 = $scores2->calcTotal();
                if ($totalScore1 < $totalScore2) {
                    $temp = $this->_participants[$a];
                    $this->_participants[$a] = $this->_participants[$a + 1];
                    $this->_participants[$a + 1] = $temp;
                }
            }
        }
        $this->saveParticipants();
    }

    public function showParticipantsScore()
    {
        foreach ($this->_participants as $participant) {
            $scores = new ScoresArray($participant->getCi());
            $totalScore = $scores->calcTotal();
            echo $participant->getName() . " " . $participant->getLastName() . " - Puntaje: " . $totalScore;
        }
    }

    public function showParticipantScore($ci)
    {
        foreach ($this->_participants as $participant) {
            if ($participant->getCi() == $ci) {
                $scores = new ScoresArray($participant->getCi());
                $totalScore = $scores->calcTotal();
                return "<td>" . $participant->getName() . "</td> <td>"  . $participant->getLastName() . "</td> <td>" . $totalScore . "</td>";
            }
        }
    }

    public function classifiedParticipants($pool)
    {
        $participantsOfPool = array();
        if (count($this->_participants) >= 11) {
            foreach ($this->_participants as $participant) {
                if ($participant->getPool() == $pool) {
                    $participantsOfPool[] = $participant;
                }
            }
            foreach ($participantsOfPool as $key => $participant) {
                if ($key < 4) {
                    echo "<tr><td>" . $key + 1 . "</td>" . $this->showParticipantScore($participant->getCi()) . "</tr>";
                }
            }
        } else {
            foreach ($this->_participants as $key => $participant) {
                if ($key < 4) {
                    echo "<tr><td>" . $key + 1 . "</td>" . $this->showParticipantScore($participant->getCi()) . "</tr>";
                }
            }
        }
    }
}
