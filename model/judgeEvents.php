
<?php

session_start();

include_once "./Objects/DataBase.php";

$connection = mysqli_connect(SERVER, USER, PASS, DB);

if (!$connection) {
    http_response_code(500);
    echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
}

if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'es';
}

if (isset($_SESSION['judgeUser'])) {
    $user = $_SESSION['judgeUser'];
} else {
    if ($lang == "es") {
        die("Por favor inicie secion");
    } else {
        die("Please Log in");
    }
}

$stmt = "SELECT e.id_evento, e.nombre FROM evento e JOIN competencia c ON e.id_evento = c.id_evento JOIN participa p ON c.id_competencia = p.id_competencia WHERE p.usuario_juez = '$user' GROUP BY e.id_evento ORDER BY e.id_evento DESC";

$response = mysqli_query($connection, $stmt);

if (!$response) {
    http_response_code(500);
    echo json_encode(array("error" => "Error: " . $stmt));
} else {

    while ($event = $response->fetch_assoc()) {
        echo "<section class='competition__element competition__element-active'>
        <button value='" . $event["id_evento"] . "' class='competition__info'>
        <div class='competition__info__container'>
            <p class='competition__id'>" . $event["id_evento"] . "</p>
            <h2 class='competition__name'>" . $event["nombre"] . "</h2>
        </div>
        </button>
        </section>";
    }
}

?>