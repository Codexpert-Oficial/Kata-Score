<?php

require_once("KatasArray.php");

class Participant
{

    private $_ci;
    private $_name;
    private $_lastName;

    public function __construct($ci, $name, $lastName)
    {
        $this->_ci = $ci;
        $this->_name = $name;
        $this->_lastName = $lastName;
    }

    public function getCi()
    {
        return $this->_ci;
    }

    public function setCi($ci)
    {
        $this->_ci = $ci;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getLastName()
    {
        return $this->_lastName;
    }

    public function setLastName($lastName)
    {
        $this->_lastName = $lastName;
    }

    public function enterParticipant()
    {
        if (!$this->exists()) {
            $connection = mysqli_connect(SERVER, USER, PASS, DB);

            if (!$connection) {
                http_response_code(500);
                echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
            }

            $stmt = $connection->prepare(
                "INSERT INTO competidor (ci, nombre_competidor, apellido_competidor) VALUES (?,?,?)"
            );

            $stmt->bind_param("iss", $this->_ci, $this->_name, $this->_lastName);
            if ($stmt->execute()) {
                return "Participante registrado con exito";
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

        $stmt = "SELECT * FROM competidor WHERE ci = $this->_ci";

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

    public function __toString()
    {
        return "<tr><td>" . $this->_ci . "</td><td>" . $this->_name . "</td><td>" . $this->_lastName . "</td></tr>";
    }
}
