<?php

class Judge
{
    private $_name;
    private $_lastName;
    private $_user;
    private $_password;

    public function __construct($name, $lastName, $user, $password)
    {
        $this->_name = $name;
        $this->_lastName = $lastName;
        $this->_user = $user;
        $this->_password = $password;
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

    public function getUser()
    {
        return $this->_user;
    }

    public function setUser($user)
    {
        $this->_user = $user;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function setPassword($password)
    {
        $this->_password = $password;
    }

    public function enterJudge()
    {
        if (!$this->exists()) {
            $connection = mysqli_connect(SERVER, USER, PASS, DB);

            if (!$connection) {
                http_response_code(500);
                echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
            }

            $stmt = $connection->prepare(
                "INSERT INTO juez (apellido_juez, clave_juez, nombre_juez, usuario_juez) VALUES (?,?,?,?)"
            );

            $stmt->bind_param("ssss", $this->_lastName, $this->_password, $this->_name, $this->_user);
            $stmt->execute();
            $stmt->close();

            return "Juez registrado con exito";
        }
    }

    public function exists()
    {
        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error de conexion: " . mysqli_connect_error()));
        }

        $stmt = "SELECT * FROM juez WHERE usuario_juez = '$this->_user'";

        $response = mysqli_query($connection, $stmt);

        if (!$response) {
            http_response_code(500);
            echo json_encode(array("error" => "Error al ingresar: " . $stmt));
        } else {
            if ($response->num_rows <= 0) {
                return false;
            } else {
                http_response_code(400);
                echo json_encode(array("error" => "Usuario ya registrado"));
                return true;
            }
        }
    }
}
