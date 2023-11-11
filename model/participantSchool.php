<?php

include_once "./Objects/DataBase.php";

$connection = mysqli_connect(SERVER, USER, PASS, DB);

if (!$connection) {
    http_response_code(500);
    echo json_encode(array("error" => "Error: " . mysqli_connect_error()));
}

$stmt = "SELECT * FROM escuela";

$response = mysqli_query($connection, $stmt);

while ($school = $response->fetch_assoc()) {
    echo "<option value = '" . $school['id_escuela'] . "'>" . $school['nombre'] . "</option>";
}
