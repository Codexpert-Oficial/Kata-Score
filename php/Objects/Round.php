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
                die();
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
                die();
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
            die();
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
}
