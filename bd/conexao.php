<?php
$dsn = 'mysql:host=localhost:3307;dbname=registerpet';
$user = 'root'; 
$password = ''; 

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit; // interrompe a execução do script em caso de falha na conexão
}
?>
