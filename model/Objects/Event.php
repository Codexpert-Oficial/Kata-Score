<?php

class Event
{
    private $_id;
    private $_name;

    public function __construct($name)
    {
        $this->_name = $name;
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

    public function enterCompetition()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = $connection->prepare(
            "INSERT INTO evento (nombre) 
            VALUES(?)"
        );

        $stmt->bind_param("s", $this->_name);
        $stmt->execute();
        $stmt->close();

        $stmt = "SELECT id_evento FROM evento ORDER BY id_evento DESC LIMIT 1";
        $response = mysqli_query($connection, $stmt);
        $response = $response->fetch_assoc();

        $this->_id = $response['id_evento'];

        if ($_COOKIE['lang'] == "es") {
            return "Evento creada con exito";
        } else {
            return "Event created successfully";
        }
    }
}
