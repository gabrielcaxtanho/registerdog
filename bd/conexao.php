<?php
$serverName = "localhost:3307";
$dbUsername = "root";
$dbPassword = "";
$dbName = "registros";


$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);
// $mysqli = new mysqli($serverName, $dbUsername, $dbPassword, $dbName) or die(mysqli_error($mysqli));
if(mysqli_connect_errno())
{
    echo "Error: Falha ao conectar-se com o banco de dados MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
