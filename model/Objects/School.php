<?php

class School
{
    private $_id;
    private $_name;
    private $_technique;

    public function __construct($name, $technique)
    {
        $this->_name = $name;
        $this->_technique = $technique;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getTechnique()
    {
        return $this->_technique;
    }

    public function setTechnique($technique)
    {
        $this->_technique = $technique;
    }

    public function enterSchool()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = $connection->prepare(
            "INSERT INTO escuela (nombre, tecnica) 
            VALUES(?,?)"
        );

        $stmt->bind_param("ss", $this->_name, $this->_technique);
        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        } else {
            $stmt->close();

            $stmt = "SELECT id_escuela FROM escuela ORDER BY id_escuela DESC LIMIT 1";
            $response = mysqli_query($connection, $stmt);
            $response = $response->fetch_assoc();

            $this->_id = $response['id_escuela'];
            if ($_COOKIE['lang'] == "es") {
                return "Escuela creada con exito";
            } else {
                return "School created successfully";
            }
        }
    }
}
