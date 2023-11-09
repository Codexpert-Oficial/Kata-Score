
<?php

session_start();

include_once "./Objects/DataBase.php";

$connection = mysqli_connect(SERVER, USER, PASS, DB);

if (!$connection) {
    http_response_code(500);
    echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
}

$stmt = "SELECT * FROM evento ORDER BY id_evento DESC";

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