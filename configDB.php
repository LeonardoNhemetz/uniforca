<?php

session_start();
$valor = $_COOKIE['nome'];

$servername = "category-app-mysql";
$username = "root";
$password = "root";
$dbname = "$valor"

?>