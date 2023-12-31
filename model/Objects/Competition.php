<?php

require_once("Round.php");
require_once("Competes.php");
require_once("Pool.php");

class Competition
{
    private $_id;
    private $_state;
    private $_date;
    private $_name;
    private $_ageRange;
    private $_gender;
    private $_eventID;

    public function __construct($state, $date, $name, $ageRange, $gender, $eventID)
    {
        $this->_state = $state;
        $this->_date = $date;
        $this->_name = $name;
        $this->_ageRange = $ageRange;
        $this->_gender = $gender;
        $this->_eventID = $eventID;
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

    public function getEventID()
    {
        return $this->_eventID;
    }

    public function setEventID($eventID)
    {
        $this->_eventID = $eventID;
    }


    public function enterCompetition()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = $connection->prepare(
            "INSERT INTO competencia (estado, fecha, nombre, rango_etario, sexo, id_evento) 
            VALUES(?,?,?,?,?,?)"
        );

        $stmt->bind_param("sssssi", $this->_state, $this->_date, $this->_name, $this->_ageRange, $this->_gender, $this->_eventID);
        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();

        $stmt = "SELECT id_competencia FROM competencia ORDER BY id_competencia DESC LIMIT 1";
        $response = mysqli_query($connection, $stmt);
        $response = $response->fetch_assoc();

        $this->_id = $response['id_competencia'];
        if ($_COOKIE['lang'] == "es") {
            return "Competencia creada con exito";
        } else {
            return "Competition created successfully";
        }
    }

    public function getLastRound()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM ronda WHERE id_competencia = $this->_id ORDER BY num_ronda DESC LIMIT 1";

        $response = mysqli_query($connection, $stmt);
        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt));
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

        $z = 0;

        for ($x = 1; $x <= $poolsCount; $x++) {

            if ($x % 2 == 0) {
                $belt = 'AO';
            } else {
                $belt = 'AKA';
            }

            $pools[$x] = new Pool($x, $belt, $this->_id, $prevRound + 1);
            $pools[$x]->enterPool();

            for ($y = 0; $y < $poolsInPool; $y++) {

                $pool = new Pool($x + $y + $z, $belt, $this->_id, $prevRound);

                $participants = $pool->getParticipantsByScore();

                if (!$participants) {
                    return false;
                }

                $i = 0;

                while ($i < $participantsPerPool && $participant = $participants->fetch_assoc()) {

                    $competes = new Competes($participant["ci"], $this->_id, $newRound->getNumber());
                    $competes->enterCompetes();

                    $pools[$x]->addParticipant($participant["ci"]);
                    $i++;
                }
            }

            if ($poolsInPool > 1) {
                $z++;
            }
        }

        return true;
    }

    public function setModality($modality, $category)
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        if ($modality == "karate") {
            $stmt = $connection->prepare("INSERT INTO karate (id_competencia) VALUES (?)");
            $stmt->bind_param("i", $this->_id);
        } else {
            $stmt = $connection->prepare("INSERT INTO `para-karate` (id_competencia,categoria) VALUES (?, ?)");
            $stmt->bind_param("is", $this->_id, $category);
        }

        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
    }

    public function isLastRound()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $numRound = $this->getLastRound();

        $round = new Round(1, $this->_id);

        $participants = $round->getParticipants();
        $cantParticipants = $participants->num_rows;

        if ($cantParticipants <= 3 && $numRound == 1) {
            return true;
        } else if ($cantParticipants <= 10 && $numRound == 2) {
            return true;
        } else if ($cantParticipants <= 24 && $numRound == 3) {
            return true;
        } else if ($cantParticipants <= 48 && $numRound == 4) {
            return true;
        } else if ($cantParticipants <= 96 && $numRound == 5) {
            return true;
        } else {
            return false;
        }
    }

    public function getLastRoundClassifieds()
    {

        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $numRound = $this->getLastRound();

        $round = new Round(1, $this->_id);

        $participants = $round->getParticipants();
        $cantParticipants = $participants->num_rows;

        if ($cantParticipants <= 3) {

            $round = new Round($numRound, $this->_id);

            $round->setPositions();

            return true;
        } else if ($cantParticipants <= 4) {

            $prevRound = $numRound - 1;

            $stmt = "SELECT *, SUM(p.puntaje) - MAX(p.puntaje) - MIN(p.puntaje) + COALESCE(puntaje_extra, 0) AS puntaje_final from compite c 
            join puntua p on c.id_competencia = p.id_competencia and c.ci = p.ci 
            join competidor on c.ci = competidor.ci 
            where c.id_competencia = $this->_id and c.num_ronda = $prevRound and p.num_ronda = $numRound and puesto = 1 
            group by c.ci order by puntaje_final desc";

            $response = mysqli_query($connection, $stmt);

            $cont = 0;
            while ($participant = $response->fetch_assoc()) {
                $cont++;
                $competes = new Competes($participant['ci'], $this->_id, $numRound);
                $competes->setPosition($cont);

                if (!$competes->enterPosition()) {
                    return false;
                }
            }

            $stmt = "SELECT *, SUM(p.puntaje) - MAX(p.puntaje) - MIN(p.puntaje) + COALESCE(puntaje_extra, 0) AS puntaje_final from compite c 
            join puntua p on c.id_competencia = p.id_competencia and c.ci = p.ci 
            join competidor on c.ci = competidor.ci 
            where c.id_competencia = $this->_id and c.num_ronda = $prevRound and p.num_ronda = $numRound and puesto = 2 
            group by c.ci order by puntaje_final desc";

            $response = mysqli_query($connection, $stmt);

            while ($participant = $response->fetch_assoc()) {
                $competes = new Competes($participant['ci'], $this->_id, $numRound);
                $competes->setPosition(3);

                if (!$competes->enterPosition()) {
                    return false;
                }
            }

            return true;
        } else if ($cantParticipants <= 5) {

            $prevRound = $numRound - 1;

            $stmt = "SELECT *, SUM(p.puntaje) - MAX(p.puntaje) - MIN(p.puntaje) + COALESCE(puntaje_extra, 0) AS puntaje_final from compite c 
            join puntua p on c.id_competencia = p.id_competencia and c.ci = p.ci 
            join competidor on c.ci = competidor.ci 
            where c.id_competencia = $this->_id and c.num_ronda = $prevRound and p.num_ronda = $numRound and puesto = 1 
            group by c.ci order by puntaje_final desc";

            $response = mysqli_query($connection, $stmt);

            $cont = 0;
            while ($participant = $response->fetch_assoc()) {
                $cont++;
                $competes = new Competes($participant['ci'], $this->_id, $numRound);
                $competes->setPosition($cont);

                if (!$competes->enterPosition()) {
                    return false;
                }
            }

            $stmt = "SELECT *, SUM(p.puntaje) - MAX(p.puntaje) - MIN(p.puntaje) + COALESCE(puntaje_extra, 0) AS puntaje_final from compite c 
            join puntua p on c.id_competencia = p.id_competencia and c.ci = p.ci 
            join competidor on c.ci = competidor.ci 
            where c.id_competencia = $this->_id and c.num_ronda = $prevRound and p.num_ronda = $numRound and puesto = 2 
            group by c.ci order by puntaje_final desc";

            $response = mysqli_query($connection, $stmt);

            $participant = $response->fetch_assoc();
            $competes = new Competes($participant['ci'], $this->_id, $numRound);
            $competes->setPosition(2);

            if (!$competes->enterPosition()) {
                return false;
            }

            $stmt = "SELECT *, SUM(p.puntaje) - MAX(p.puntaje) - MIN(p.puntaje) + COALESCE(puntaje_extra, 0) AS puntaje_final from compite c 
            join puntua p on c.id_competencia = p.id_competencia and c.ci = p.ci 
            join competidor on c.ci = competidor.ci 
            join pertenece on c.ci = pertenece.ci and c.id_competencia = pertenece.id_competencia and c.num_ronda = pertenece.num_ronda
            where (c.id_competencia = $this->_id and c.num_ronda = $prevRound and p.num_ronda = $numRound) and ((puesto = 2 and  id_pool = 2) or (puesto = 3 and  id_pool = 1))
            group by c.ci order by puntaje_final desc";

            $response = mysqli_query($connection, $stmt);
            $response->fetch_assoc();
            $competes = new Competes($participant['ci'], $this->_id, $numRound);
            $competes->setPosition(3);

            if (!$competes->enterPosition()) {
                return false;
            }


            return true;
        } else if ($cantParticipants <= 96) {

            $prevRound = $numRound - 1;

            $stmt = "SELECT *, SUM(p.puntaje) - MAX(p.puntaje) - MIN(p.puntaje) + COALESCE(puntaje_extra, 0) AS puntaje_final from compite c 
            join puntua p on c.id_competencia = p.id_competencia and c.ci = p.ci 
            join competidor on c.ci = competidor.ci 
            where c.id_competencia = $this->_id and c.num_ronda = $prevRound and p.num_ronda = $numRound and puesto = 1 
            group by c.ci order by puntaje_final desc";

            $response = mysqli_query($connection, $stmt);

            $cont = 0;
            while ($participant = $response->fetch_assoc()) {
                $cont++;
                $competes = new Competes($participant['ci'], $this->_id, $numRound);
                $competes->setPosition($cont);

                if (!$competes->enterPosition()) {
                    return false;
                }
            }

            $stmt = "SELECT *, SUM(p.puntaje) - MAX(p.puntaje) - MIN(p.puntaje) + COALESCE(puntaje_extra, 0) AS puntaje_final from compite c 
            join puntua p on c.id_competencia = p.id_competencia and c.ci = p.ci 
            join competidor on c.ci = competidor.ci 
            join pertenece on c.ci = pertenece.ci and c.id_competencia = pertenece.id_competencia and c.num_ronda = pertenece.num_ronda
            where (c.id_competencia = $this->_id and c.num_ronda = $prevRound and p.num_ronda = $numRound) and ((puesto = 2 and  id_pool = 1) or (puesto = 3 and  id_pool = 2))
            group by c.ci order by puntaje_final desc";

            $responses[] = mysqli_query($connection, $stmt);

            $stmt = "SELECT *, SUM(p.puntaje) - MAX(p.puntaje) - MIN(p.puntaje) + COALESCE(puntaje_extra, 0) AS puntaje_final from compite c 
            join puntua p on c.id_competencia = p.id_competencia and c.ci = p.ci 
            join competidor on c.ci = competidor.ci 
            join pertenece on c.ci = pertenece.ci and c.id_competencia = pertenece.id_competencia and c.num_ronda = pertenece.num_ronda
            where (c.id_competencia = $this->_id and c.num_ronda = $prevRound and p.num_ronda = $numRound) and ((puesto = 2 and  id_pool = 2) or (puesto = 3 and  id_pool = 1))
            group by c.ci order by puntaje_final desc";

            $responses[] = mysqli_query($connection, $stmt);

            for ($i = 0; $i < 2; $i++) {

                $participant = $responses[$i]->fetch_assoc();
                $competes = new Competes($participant['ci'], $this->_id, $numRound);
                $competes->setPosition(3);

                if (!$competes->enterPosition()) {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }
}
