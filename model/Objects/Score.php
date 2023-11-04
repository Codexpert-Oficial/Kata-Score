<?php

class Score
{

    private $_scoreValue;
    private $_ci;
    private $_judge;
    private $_competitionID;
    private $_round;

    public function __construct($scoreValue, $ci, $judge, $competitionID, $round)
    {
        $this->_scoreValue = $scoreValue;
        $this->_ci = $ci;
        $this->_judge = $judge;
        $this->_competitionID = $competitionID;
        $this->_round = $round;
    }

    public function getScoreValue()
    {
        return $this->_scoreValue;
    }

    public function setScoreValue($scoreValue)
    {
        $this->_scoreValue = $scoreValue;
    }

    public function getCi()
    {
        return $this->_ci;
    }

    public function setCi($ci)
    {
        $this->_ci = $ci;
    }

    public function getJudge()
    {
        return $this->_judge;
    }

    public function setJudge($judge)
    {
        $this->_judge = $judge;
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

    public function enterScore()
    {
        if (!$this->exists()) {
            $connection = mysqli_connect(SERVER, USER, PASS, DB);
            if (!$connection) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
            }

            $stmt = $connection->prepare("INSERT INTO puntua (ci, id_competencia, num_ronda, puntaje, usuario_juez) VALUES (?,?,?,?,?)");

            $stmt->bind_param("iiiis", $this->_ci, $this->_competitionID, $this->_round, $this->_scoreValue, $this->_judge);

            if ($stmt->execute()) {
                if ($_COOKIE['lang'] == "es") {
                    return "Puntaje registrado con exito";
                } else {
                    return "Score registered successfully";
                }
            } else {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt->error));
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

        $stmt = "SELECT * FROM puntua WHERE ci = $this->_ci AND id_competencia = $this->_competitionID AND num_ronda = $this->_round AND usuario_juez = '$this->_judge'";

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
                    echo json_encode(array("error" => "Puntaje ya registrado"));
                } else {
                    echo json_encode(array("error" => "Already registered score"));
                }
                return true;
            }
        }
    }
}
