<?php

/*$servername = "sql313.epizy.com";
$username = "epiz_33820552";
$password = "k4atccugPZC";
$dbname = "epiz_33820552_cad_imobiliarias";*/

$servername = "category-app-mysql";
$username = "root";
$password = "root";
$dbname = "uniforca";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->error) {
    die("Conexão falhou: " . mysqli_connect_error());
}

?>