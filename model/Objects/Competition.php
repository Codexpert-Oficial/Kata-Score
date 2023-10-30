<?php

require_once("Round.php");
require_once("Competes.php");
require_once("Pool.php");

class Competition
{
    private $_id;
    private $_state;
    private $_date;
    private $_teamType;
    private $_name;
    private $_ageRange;
    private $_gender;

    public function __construct($state, $date, $teamType, $name, $ageRange, $gender)
    {
        $this->_state = $state;
        $this->_date = $date;
        $this->_name = $name;
        $this->_ageRange = $ageRange;
        $this->_gender = $gender;
        $this->_teamType = $teamType;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
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

    public function getTeamType()
    {
        return $this->_teamType;
    }

    public function setTeamType($teamType)
    {
        $this->_teamType = $teamType;
    }

    public function getState()
    {
        return $this->_state;
    }

    public function setState($state)
    {
        $this->_state = $state;
    }

    public function getDate()
    {
        return $this->_date;
    }

    public function setDate($date)
    {
        $this->_date = $date;
    }


    public function enterCompetition()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
        }

        $stmt = $connection->prepare(
            "INSERT INTO competencia (estado, fecha, tipo_equipos, nombre, rango_etario, sexo) 
            VALUES(?,?,?,?,?,?)"
        );

        $stmt->bind_param("ssssss", $this->_state, $this->_date, $this->_teamType, $this->_name, $this->_ageRange, $this->_gender);
        $stmt->execute();
        $stmt->close();

        $stmt = "SELECT id_competencia FROM competencia ORDER BY id_competencia DESC LIMIT 1";
        $response = mysqli_query($connection, $stmt);
        $response = $response->fetch_assoc();

        $this->_id = $response['id_competencia'];

        return "Competencia creada con exito";
    }

    public function getLastRound()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM ronda WHERE id_competencia = $this->_id ORDER BY num_ronda DESC LIMIT 1";

        $response = mysqli_query($connection, $stmt);
        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error al ingresar: " . $stmt));
        } else {
            $round = $response->fetch_assoc();
            return $round['num_ronda'];
        }
    }

    public function passRound()
    {

        $prevRound = $this->getLastRound();
        $round = new Round($prevRound, $this->_id);
        $firstRound = new Round(1, $this->_id);
        $poolsCount = $round->getPools()->num_rows;

        if ($poolsCount <= 0 || !$round->allScored() || !$round->setPositions()) {
            return false;
        }

        $participants = $firstRound->getParticipants();
        $totalParticipants = $participants->num_rows;

        $poolsInPool = 1;

        if ($totalParticipants <= 3) {

            return false;
        } else if ($totalParticipants <= 4) {

            if ($prevRound >= 2) {
                return false;
            }

            $participantsPerPool = 2;
        } else if ($totalParticipants <= 10) {

            if ($prevRound >= 2) {
                return false;
            }

            $participantsPerPool = 3;
        } else if ($totalParticipants <= 24) {

            switch ($prevRound) {
                case 1:
                    $participantsPerPool = 4;
                    break;
                case 2:
                    $participantsPerPool = 3;
                    break;
                default:
                    return false;
            }
        } else if ($totalParticipants <= 48) {

            switch ($prevRound) {
                case 1:
                    $participantsPerPool = 4;
                    $poolsInPool = 2;
                    $poolsCount = $poolsCount / 2;
                    break;
                case 2:
                    $participantsPerPool = 4;
                    break;
                case 3:
                    $participantsPerPool = 3;
                    break;
                default:
                    return false;
            }
        } else if ($totalParticipants <= 96) {

            switch ($prevRound) {
                case 1:
                    $participantsPerPool = 4;
                    $poolsInPool = 2;
                    $poolsCount = $poolsCount / 2;
                    break;
                case 2:
                    $participantsPerPool = 4;
                    $poolsInPool = 2;
                    $poolsCount = $poolsCount / 2;
                    break;
                case 3:
                    $participantsPerPool = 4;
                    break;
                case 4:
                    $participantsPerPool = 3;
                    break;
                default:
                    return false;
            }
        } else {
            return false;
        }

        $newRound = new Round($prevRound + 1, $this->_id);
        $newRound->enterRound();

        for ($x = 1; $x <= $poolsCount; $x++) {
            if ($x % 2 == 0) {
                $belt = 'AO';
            } else {
                $belt = 'AKA';
            }

            $pools[$x] = new Pool($x, $belt, $this->_id, $prevRound + 1);
            $pools[$x]->enterPool();

            $z = 0;

            for ($y = 0; $y < $poolsInPool; $y++) {

                $pool = new Pool($x + $y + $z, $belt, $this->_id, $prevRound);

                $participants = $pool->getParticipantsByScore();

                if (!$participants) {
                    return false;
                }

                $i = 0;

                while ($participant = $participants->fetch_assoc() && $i < $participantsPerPool) {

                    $competes = new Competes($participant['ci'], $newRound->getNumber(), $this->_id);
                    $competes->enterCompetes();

                    $pools[$x]->addParticipant($participant['ci']);

                    $i++;
                }
            }

            if ($poolsInPool > 1) {
                $z++;
            }
        }

        return true;
    }
}
