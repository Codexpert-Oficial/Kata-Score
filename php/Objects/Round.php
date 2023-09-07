<?php

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
            echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM ronda WHERE num_ronda = $this->_number AND id_competencia = $this->_competitionID";

        $response = mysqli_query($connection, $stmt);

        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error al ingresar: " . $stmt));
        } else {
            if ($response->num_rows <= 0) {
                return false;
            } else {
                http_response_code(400);
                echo json_encode(array("error" => "Ronda ya registrada"));
                return true;
            }
        }
    }

    public function participantExists($ci)
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM compite WHERE num_ronda = $this->_number AND id_competencia = $this->_competitionID AND ci = $ci";

        $response = mysqli_query($connection, $stmt);

        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error al ingresar: " . $stmt));
        } else {
            if ($response->num_rows <= 0) {
                http_response_code(400);
                echo json_encode(array("error" => "Participante no esta registrado en esta ronda"));
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
            echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
        }

        $stmt = $connection->prepare("UPDATE compite SET activo = FALSE WHERE num_ronda = ? AND id_competencia = ?");

        $stmt->bind_param("ii", $this->_number, $this->_competitionID);
        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(array("error" => "Error en la consulta: " . $stmt));
        }
        $stmt->close();

        $stmt = $connection->prepare("UPDATE compite SET activo = TRUE WHERE ci = ? AND num_ronda = ? AND id_competencia = ?");

        $stmt->bind_param("iii", $ci, $this->_number, $this->_competitionID);
        if ($stmt->execute()) {
            return "Accion realizada con exito";
        } else {
            http_response_code(500);
            echo json_encode(array("error" => "Error en la consulta: " . $stmt));
        }
        $stmt->close();
    }

    public function getActiveParticipant()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM competidor JOIN compite ON competidor.ci = compite.ci WHERE num_ronda = $this->_number AND id_competencia = $this->_competitionID AND activo = TRUE";

        $response = mysqli_query($connection, $stmt);

        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error al ingresar: " . $stmt));
        } else {
            $participant = $response->fetch_assoc();
            return $participant;
        }
    }
}
