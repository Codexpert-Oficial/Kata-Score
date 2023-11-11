<?php

require_once('Pool.php');
require_once('Competes.php');

class Round
{

    private $_number;
    private $_competitionID;

    public function __construct($number, $competitionID)
    {
        $this->_number = $number;
        $this->_competitionID = $competitionID;
    }

    public function getNumber()
    {
        return $this->_number;
    }

    public function setNumber($number)
    {
        $this->_number = $number;
    }

    public function getCompetitionID()
    {
        return $this->_competitionID;
    }

    public function setCompetitionID($competitionID)
    {
        $this->_competitionID = $competitionID;
    }

    public function enterRound()
    {
        if (!$this->exists()) {
            $connection = mysqli_connect(SERVER, USER, PASS, DB);

            if (!$connection) {
                http_response_code(500);
                echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
            }

            $stmt = $connection->prepare(
                "INSERT INTO ronda (num_ronda, id_competencia) VALUES (?,?)"
            );

            $stmt->bind_param("ii", $this->_number, $this->_competitionID);
            if ($stmt->execute()) {
                return "Ronda registrada con exito";
            } else {
                http_response_code(500);
                echo json_encode(array("error" => "Error en la consulta: " . $stmt));
            }
            $stmt->close();
        }
    }

    public function exists()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM ronda WHERE num_ronda = $this->_number AND id_competencia = $this->_competitionID";

        $response = mysqli_query($connection, $stmt);

        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt));
        } else {
            if ($response->num_rows <= 0) {
                return false;
            } else {
                http_response_code(400);
                if ($_COOKIE['lang'] == "es") {
                    echo json_encode(array("error" => "Ronda ya registrada"));
                } else {
                    echo json_encode(array("error" => "Already registered round"));
                }
                return true;
            }
        }
    }

    public function participantExists($ci)
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM compite WHERE num_ronda = $this->_number AND id_competencia = $this->_competitionID AND ci = $ci";

        $response = mysqli_query($connection, $stmt);

        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt));
        } else {
            if ($response->num_rows <= 0) {
                http_response_code(400);
                if ($_COOKIE['lang'] == "es") {
                    echo json_encode(array("error" => "Participante no esta registrado en esta ronda"));
                } else {
                    echo json_encode(array("error" => "Participant is not registered in this round"));
                }
                return false;
            } else {
                return true;
            }
        }
    }

    public function setActiveParticipant($ci)
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = $connection->prepare("UPDATE compite SET activo = FALSE WHERE num_ronda = ? AND id_competencia = ?");

        $stmt->bind_param("ii", $this->_number, $this->_competitionID);
        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();

        $stmt = $connection->prepare("UPDATE compite SET activo = TRUE WHERE ci = ? AND num_ronda = ? AND id_competencia = ?");

        $stmt->bind_param("iii", $ci, $this->_number, $this->_competitionID);
        if ($stmt->execute()) {
            if ($_COOKIE['lang'] == "es") {
                return "Accion realizada con exito";
            } else {
                return "Action done successfully";
            }
        } else {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();
    }

    public function getActiveParticipant()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM competidor JOIN compite ON competidor.ci = compite.ci WHERE num_ronda = $this->_number AND id_competencia = $this->_competitionID AND activo = TRUE";

        $response = mysqli_query($connection, $stmt);

        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt));
        } else if ($response->num_rows <= 0) {
            return false;
        } else {
            $participant = $response->fetch_assoc();
            return $participant;
        }
    }

    public function getParticipants()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM competidor JOIN compite ON competidor.ci = compite.ci WHERE num_ronda = $this->_number AND id_competencia = $this->_competitionID";

        $response = mysqli_query($connection, $stmt);

        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt));
        } else {
            return $response;
        }
    }

    public function removePools()
    {

        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = $connection->prepare("DELETE FROM pertenece WHERE id_competencia = ? AND num_ronda = ?");

        $stmt->bind_param("ii", $this->_competitionID, $this->_number);

        $stmt->execute();

        $stmt->close();

        $stmt = $connection->prepare("DELETE FROM pool WHERE id_competencia = ? AND num_ronda = ?");

        $stmt->bind_param("ii", $this->_competitionID, $this->_number);

        $stmt->execute();

        $stmt->close();
    }

    public function createPools()
    {
        $this->removePools();

        $participants = $this->getParticipants();

        $participantsCount = $participants->num_rows;

        while ($participant = $participants->fetch_assoc()) {
            $participantsCi[] = $participant['ci'];
        }

        shuffle($participantsCi);

        if ($participantsCount <= 3) {

            $pool = new Pool(1, 'AKA', $this->_competitionID, $this->_number);
            if (!$pool->exists()) {
                $pool->enterPool();
            }

            foreach ($participantsCi as $participantCi) {
                $pool->removeParticipant($participantCi);
                $pool->addParticipant($participantCi);
            }
        } else if ($participantsCount <= 96) {

            if ($participantsCount <= 24) {
                $poolsCant = 2;
            } else if ($participantsCount <= 48) {
                $poolsCant = 4;
            } else {
                $poolsCant = 8;
            }

            $finalPosition = intdiv($participantsCount, $poolsCant);

            if ($participantsCount % $poolsCant != 0) {
                $finalPosition++;
            }

            for ($i = 1; $i <= $poolsCant; $i++) {
                if ($i % 2 == 0) {
                    $pools[$i] = new Pool($i, 'AO', $this->_competitionID, $this->_number);
                } else {
                    $pools[$i] = new Pool($i, 'AKA', $this->_competitionID, $this->_number);
                }
                $pools[$i]->enterPool();
            }

            $cont = 1;

            foreach ($participantsCi as $key => $participantCi) {
                if ($key < $finalPosition) {
                    $pools[$cont]->addParticipant($participantCi);
                } else if ($key < ($participantsCount / $poolsCant) * $cont) {
                    $pools[$cont]->addParticipant($participantCi);
                } else {
                    $cont++;
                    $pools[$cont]->addParticipant($participantCi);
                }
            }
        }
    }

    public function getPools()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM pool WHERE num_ronda = $this->_number AND id_competencia = $this->_competitionID";

        $response = mysqli_query($connection, $stmt);

        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt));
        } else {
            return $response;
        }
    }

    public function getParticipantsPools()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = "SELECT competidor.*, pertenece.id_pool FROM competidor 
        JOIN compite ON competidor.ci = compite.ci 
        LEFT JOIN pertenece ON competidor.ci = pertenece.ci AND compite.num_ronda = pertenece.num_ronda AND compite.id_competencia = pertenece.id_competencia  
        WHERE compite.num_ronda = $this->_number AND compite.id_competencia = $this->_competitionID ORDER BY id_pool ASC";

        $response = mysqli_query($connection, $stmt);

        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt));
        } else {
            return $response;
        }
    }

    public function isScored($ci)
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM puntua WHERE ci = $ci AND id_competencia = $this->_competitionID AND num_ronda = $this->_number";

        $response = mysqli_query($connection, $stmt);

        if (!$response) {

            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt));
        } else if ($response->num_rows < 5) {
            return false;
        } else {
            return true;
        }
    }

    public function allScored()
    {

        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM compite WHERE id_competencia = $this->_competitionID AND num_ronda = $this->_number";

        $response = mysqli_query($connection, $stmt);

        $cont = 0;

        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt));
            return false;
        }

        if ($response->num_rows <= 0) {
            return false;
        }

        while ($participant = $response->fetch_assoc()) {
            if ($this->isScored($participant['ci'])) {
                $cont++;
            }
        }

        if ($cont == $response->num_rows) {
            return true;
        }

        return false;
    }

    public function totalScore($ci)
    {

        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM competidor JOIN puntua ON competidor.ci = puntua.ci WHERE competidor.ci = $ci AND puntua.id_competencia = $this->_competitionID AND puntua.num_ronda = $this->_number ORDER BY puntua.puntaje DESC";

        $response = mysqli_query($connection, $stmt);

        if (!$response) {

            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt));
        } else if ($response->num_rows < 5) {

            http_response_code(400);
            if ($_COOKIE['lang'] == "es") {
                echo json_encode(array("error" => "No todos los jueces puntuaron al participante"));
            } else {
                echo json_encode(array("error" => "Not all the judges rated the participant"));
            }
        } else {
            $total = 0;

            while ($score = $response->fetch_assoc()) {
                $scores[] = $score['puntaje'];
                $total += $score['puntaje'];
                $extraScore = $score['puntaje_extra'];
            }

            $total = $total - $scores[4] - $scores[0] + $extraScore;

            return $total;
        }
    }

    public function setPositions()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            return false;
        }

        $pools = $this->getPools();

        while ($pool = $pools->fetch_assoc()) {

            $idPool = $pool['id_pool'];

            $stmt = "SELECT puntua.ci ,SUM(puntaje) - MAX(puntaje) - MIN(puntaje) + COALESCE(puntaje_extra, 0) AS puntaje_final
                    FROM puntua
                    JOIN pertenece ON pertenece.ci = puntua.ci AND pertenece.id_competencia = puntua.id_competencia AND pertenece.num_ronda = puntua.num_ronda
                    JOIN competidor ON puntua.ci = competidor.ci
                    WHERE pertenece.id_pool = $idPool
                    AND puntua.id_competencia = $this->_competitionID
                    AND puntua.num_ronda = $this->_number
                    GROUP BY puntua.ci
                    ORDER BY puntaje_final DESC";

            $participants = mysqli_query($connection, $stmt);

            if (!$participants) {
                return false;
            }

            $cont = 0;

            while ($participant = $participants->fetch_assoc()) {
                $cont++;
                $competes = new Competes($participant['ci'], $this->_competitionID, $this->_number);
                $competes->setPosition($cont);

                if (!$competes->enterPosition()) {
                    return false;
                }
            }
        }
        return true;
    }
}
