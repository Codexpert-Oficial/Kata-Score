<?php session_start();

error_reporting(0);

include_once "./Objects/DataBase.php";

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}


include_once "./Objects/Competition.php";

if (isset($_SESSION['competition'])) {
    $competition = $_SESSION['competition'];

    if (isset($_POST['user'])) {
        $user = $_POST['user'];

        $connection = mysqli_connect(SERVER, USER, PASS, DB);

        if (!$connection) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
        }

        $stmt = $connection->prepare("DELETE FROM puntua WHERE usuario_juez = ? AND id_competencia = ?");

        $stmt->bind_param("si", $user, $competition);

        $stmt->execute();
        $stmt->close();

        $stmt = $connection->prepare("DELETE FROM participa WHERE usuario_juez = ? AND id_competencia = ?");

        $stmt->bind_param("si", $user, $competition);

        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(array("error" => "Error: " . $stmt->error));
        } else {
            $stmt->close();
            if ($lang == "es") {
                echo "Juez quitado con exito";
            } else {
                echo "Judge removed successfully";
            }
        }
    } else {
        http_response_code(400);
        if ($lang == "es") {
            echo json_encode(array("error" => "Ingrese los datos"));
        } else {
            echo json_encode(array("error" => "Enter the data"));
        }
    }
} else {
    http_response_code(400);
    if ($lang == "es") {
        echo json_encode(array("error" => "Seleccione una competencia"));
    } else {
        echo json_encode(array("error" => "Select a competition"));
    }
}
