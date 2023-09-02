<?php

class Competes
{

    private $_ci;
    private $_competitionID;
    private $_numRound;
    private $_position;


    public function __construct($ci, $copmetitionID, $numRound)
    {
        $this->_ci = $ci;
        $this->_competitionID = $copmetitionID;
        $this->_numRound = $numRound;
    }

    public function getCi()
    {
        return $this->_ci;
    }

    public function setCi($ci)
    {
        $this->_ci = $ci;
    }

    public function getCompetitionID()
    {
        return $this->_competitionID;
    }

    public function setCompetitionID($competitionID)
    {
        $this->_competitionID = $competitionID;
    }

    public function getNumRound()
    {
        return $this->_numRound;
    }

    public function setNumRound($numRound)
    {
        $this->_numRound = $numRound;
    }

    public function getPosition()
    {
        return $this->_position;
    }

    public function setPosition($position)
    {
        $this->_position = $position;
    }

    public function enterCompetes()
    {
        if (!$this->exists()) {
            $connection = mysqli_connect(SERVER, USER, PASS, DB);

            if (!$connection) {
                http_response_code(500);
                echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
            }

            $stmt = $connection->prepare(
                "INSERT INTO compite (ci, id_competencia, num_ronda) VALUES (?,?,?)"
            );

            $stmt->bind_param("iii", $this->_ci, $this->_competitionID, $this->_numRound);
            if ($stmt->execute()) {
                return "Informacion registrada con exito";
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

        $stmt = "SELECT * FROM compite WHERE ci = $this->_ci AND id_competencia = $this->_competitionID AND num_ronda = $this->_numRound";

        $response = mysqli_query($connection, $stmt);

        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error al ingresar: " . $stmt));
        } else {
            if ($response->num_rows <= 0) {
                return false;
            } else {
                http_response_code(400);
                echo json_encode(array("error" => "Participante ya registrado"));
                return true;
            }
        }
    }
}
