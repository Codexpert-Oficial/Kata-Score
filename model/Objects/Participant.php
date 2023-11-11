<?php

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
                echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
            }

            $stmt = $connection->prepare(
                "INSERT INTO competidor (ci, nombre_competidor, apellido_competidor) VALUES (?,?,?)"
            );

            $stmt->bind_param("iss", $this->_ci, $this->_name, $this->_lastName);
            if ($stmt->execute()) {
                if ($_COOKIE['lang'] == "es") {
                    return "Participante registrado con exito";
                } else {
                    return "Participant registered successfully";
                }
            } else {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . $stmt->error));
            }
            $stmt->close();
        } else {
            $connection = mysqli_connect(SERVER, USER, PASS, DB);

            if (!$connection) {
                http_response_code(500);
                echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
            }

            $stmt = $connection->prepare(
                "UPDATE competidor SET nombre_competidor = ?, apellido_competidor = ? WHERE ci = ?"
            );

            $stmt->bind_param("ssi", $this->_name, $this->_lastName, $this->_ci);
            if ($stmt->execute()) {
                if ($_COOKIE['lang'] == "es") {
                    return "Participante registrado con exito";
                } else {
                    return "Participant registered successfully";
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

        $stmt = "SELECT * FROM competidor WHERE ci = $this->_ci";

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

    public function setSchool($idSchool)
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = $connection->prepare("DELETE FROM estudia WHERE ci = ?");

        $stmt->bind_param("i", $this->_ci);

        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }

        $stmt->close();

        $stmt = $connection->prepare("INSERT INTO estudia (id_escuela,ci) VALUES (?,?)");

        $stmt->bind_param("ii", $idSchool, $this->_ci);

        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }

        $stmt->close();
    }

    public function getSchool()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM estudia JOIN escuela ON estudia.id_escuela = escuela.id_escuela WHERE ci = $this->_ci";

        $response = mysqli_query($connection, $stmt);

        if ($response->num_rows <= 0) {
            return false;
        }

        return $response->fetch_assoc();
    }
}
