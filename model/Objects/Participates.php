<?php

class Participates
{
    private $_judgeUser;
    private $_competitionId;
    private $_number;

    public function __construct($judgeUser, $competitionId, $number)
    {
        $this->_judgeUser = $judgeUser;
        $this->_competitionId = $competitionId;
        $this->_number = $number;
    }

    public function getJudgeUser()
    {
        return $this->_judgeUser;
    }

    public function setJudgeUser($judgeUser)
    {
        $this->_judgeUser = $judgeUser;
    }

    public function getCompetitionId()
    {
        return $this->_competitionId;
    }

    public function setCompetitionId($competitionId)
    {
        $this->_competitionId = $competitionId;
    }

    public function getNumber()
    {
        return $this->_number;
    }

    public function setNumber($number)
    {
        $this->_number = $number;
    }

    public function enterParticipates()
    {
        if (!$this->exists()) {
            $connection = mysqli_connect(SERVER, USER, PASS, DB);

            if (!$connection) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
            }

            $stmt = $connection->prepare(
                "INSERT INTO participa (id_competencia, num_juez, usuario_juez) VALUES (?,?,?)"
            );

            $stmt->bind_param("iis", $this->_competitionId, $this->_number, $this->_judgeUser);

            if ($stmt->execute()) {
                if ($_COOKIE['lang'] == "es") {
                    echo "Juez añadido con exito";
                } else {
                    echo "Judge added successfully";
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

        $stmt = "SELECT * FROM participa WHERE id_competencia = '$this->_competitionId' AND num_juez = '$this->_number'";

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
                    echo json_encode(array("error" => "Numero de juez ya asignado"));
                } else {
                    echo json_encode(array("error" => "Already assigned judge number"));
                }
                return true;
            }
        }
    }
}
