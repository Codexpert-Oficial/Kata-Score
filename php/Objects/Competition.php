<?php

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

        $round = $response->fetch_assoc();

        return $round['num_ronda'];
    }
}
