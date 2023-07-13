<?php

session_start();

if (isset($_SESSION["round"])) {
    $_SESSION["round"]++;
    echo "Ronda actual: " . $_SESSION["round"];
} else {

    $_SESSION["round"] = 2;
}
