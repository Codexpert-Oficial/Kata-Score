<?php

require_once('Round.php');

class Pool
{
    private $_id;
    private $_belt;
    private $_competitionID;
    private $_round;

    public function __construct($id, $belt, $competitionID, $round)
    {
        $this->_id = $id;
        $this->_belt = $belt;
        $this->_competitionID = $competitionID;
        $this->_round = $round;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getBelt()
    {
        return $this->_belt;
    }

    public function setBelt($belt)
    {
        $this->_belt = $belt;
    }

    public function getCompetitionID()
    {
        return $this->_competitionID;
    }

    public function setCompetitionID($competitionID)
    {
        $this->_competitionID = $competitionID;
    }

    public function getRound()
    {
        return $this->_round;
    }

    public function setRound($round)
    {
        $this->_round = $round;
    }

    public function enterPool()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
        }

        $stmt = $connection->prepare(
            "INSERT INTO pool (cinturon, id_pool, id_competencia, num_ronda) VALUES (?,?,?,?)"
        );

        $stmt->bind_param("siii", $this->_belt, $this->_id, $this->_competitionID, $this->_round);
        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(array("error" => "Error en la consulta: " . $stmt->error));
        }
        $stmt->close();
    }

    public function exists()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM pool WHERE id_pool = $this->_id AND id_competencia = $this->_competitionID AND num_ronda = $this->_round";

        $response = mysqli_query($connection, $stmt);

        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error al ingresar: " . $stmt));
        } else {
            if ($response->num_rows <= 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function addParticipant($ci)
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
        }

        $stmt = $connection->prepare(
            "INSERT INTO pertenece (id_pool, ci, id_competencia, num_ronda) VALUES (?,?,?,?)"
        );

        $stmt->bind_param("iiii", $this->_id, $ci, $this->_competitionID, $this->_round);
        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(array("error" => "Error en la consulta: " . $stmt->error));
        }
        $stmt->close();
    }

    public function removeParticipant($ci)
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
        }

        $stmt = $connection->prepare(
            "DELETE FROM pertenece WHERE ci = ? AND id_competencia = ? AND num_ronda = ?"
        );

        $stmt->bind_param("iii", $ci, $this->_competitionID, $this->_round);
        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(array("error" => "Error en la consulta: " . $stmt));
        }
        $stmt->close();
    }

    public function allScored()
    {
        $round = new Round($this->_round, $this->_competitionID);

        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM pertenece WHERE id_pool = $this->_id AND id_competencia = $this->_competitionID AND num_ronda = $this->_round";

        $response = mysqli_query($connection, $stmt);

        $cont = 0;

        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error en la consulta: " . $stmt));
            return false;
        }

        if ($response->num_rows === 0) {
            return false;
        }

        while ($participant = $response->fetch_assoc()) {
            if ($round->isScored($participant['ci'])) {
                $cont++;
            }
        }

        if ($cont == $response->num_rows) {
            return true;
        }

        return false;
    }

    public function getParticipantsByScore()
    {

        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            return false;
        }

        $stmt = "SELECT puntua.ci ,SUM(puntaje) - MAX(puntaje) - MIN(puntaje) AS puntaje_final
                    FROM puntua
                    JOIN pertenece ON pertenece.ci = puntua.ci AND pertenece.id_competencia = puntua.id_competencia AND pertenece.num_ronda = puntua.num_ronda
                    WHERE pertenece.id_pool = $this->_id
                    AND puntua.id_competencia = $this->_competitionID
                    AND puntua.num_ronda = $this->_round
                    GROUP BY ci
                    ORDER BY puntaje_final DESC";

        $response = mysqli_query($connection, $stmt);

        return $response;
    }
}
