<?php

class Performs
{

    private $_ci;
    private $_competitionID;
    private $_round;
    private $_kata;

    public function __construct($ci, $competitionID, $round, $kata)
    {
        $this->_ci = $ci;
        $this->_competitionID = $competitionID;
        $this->_round = $round;
        $this->_kata = $kata;
    }

    public function getCi()
    {
        return $this->_ci;
    }

    public function setCi($ci)
    {
        $this->_ci = $ci;
    }

    public function getCompetitionId()
    {
        return $this->_competitionID;
    }

    public function setCompetitionId($competitionID)
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

    public function getKata()
    {
        return $this->_kata;
    }

    public function setkata($kata)
    {
        $this->_kata = $kata;
    }

    public function enterPerforms()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);
        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        if ($this->exists()) {
            $stmt = $connection->prepare("DELETE FROM realiza WHERE ci = ? AND id_competencia = ? AND num_ronda = ?");
            $stmt->bind_param("iii", $this->_ci, $this->_competitionID, $this->_round);
            if (!$stmt->execute()) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt));
            }
            $stmt->close();
        }

        $stmt = $connection->prepare(
            "INSERT INTO realiza (ci, id_kata, id_competencia, num_ronda) VALUES (?,?,?,?)"
        );

        $stmt->bind_param("iiii", $this->_ci, $this->_kata, $this->_competitionID, $this->_round);
        if ($stmt->execute()) {
            if ($_COOKIE['lang'] == "es") {
                return "Informacion registrada con exito";
            } else {
                return "Data registered successfully";
            }
        } else {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }
        $stmt->close();
    }

    public function exists()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM realiza WHERE ci = $this->_ci AND id_competencia = $this->_competitionID AND num_ronda = $this->_round";

        $response = mysqli_query($connection, $stmt);

        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt));
        } else {
            if ($response->num_rows <= 0) {
                return false;
            } else {
                return true;
            }
        }
    }
}
