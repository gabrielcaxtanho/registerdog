<?php

/* require_once '../bd/conexao.php';
require_once 'functions.inc.php';

if (isset($_POST["submit"])) {

$userName = isset($_POST['userName']) ? $_POST['userName'] : null;
$userEmail = $_POST['userEmail'] ;
$petshop = isset($_POST['nomePetShop']) ? $_POST['nomePetShop'] : null;
$username = isset($_POST['username']) ? $_POST['username'] : null;
$celular = isset($_POST['celular']) ? $_POST['celular'] : null;
$cidade = isset($_POST['cidade']) ? $_POST['cidade'] : null;
$estado = isset($_POST['estado']) ? $_POST['estado'] : null;

 if (empty($userEmail)) {
    header("Location: ../cadastroUser.php?error=emailrequired");
    exit();
} 

if ($userEmail === null) {
    echo "Erro: E-mail não foi enviado corretamente.";
    exit();
}


$pwd = addslashes($_POST["password"]);
$pwdrepeat = addslashes($_POST["confirmpassword"]);
$perm = "3COL";
$aprov = "AGRDD";
date_default_timezone_set('America/Sao_Paulo');
    $dataInicio = date('Y-m-d H:i:s');

echo "<p>nome: $userName, <p>pet shop: $petshop, <p>email: $userEmail ";
echo "<p>celular: $celular, cidade: $cidade, estado: $estado </p>";
echo "<p>senha: $pwd, cfnSenha: $pwdrepeat, aprov: $aprov </p>";
echo "Data e Hora de Início: " . $dataInicio;

    createUser($conn, $userName, $petshop, $username, $userEmail,  $dataInicio, $celular, $cidade, $estado, $pwd, $pwdrepeat, $aprov, $perm);
} else {
    header("location: .././cadastroUser.php?error=none");
    exit();
    
}*/

require_once '../bd/conexao.php';
require_once 'functions.inc.php';

if (isset($_POST["submit"])) {

    $userName = isset($_POST['userName']) ? $_POST['userName'] : null;
    $userEmail = isset($_POST['userEmail']) ? $_POST['userEmail'] : null;
    $petshop = isset($_POST['nomePetShop']) ? $_POST['nomePetShop'] : null;
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $celular = isset($_POST['celular']) ? $_POST['celular'] : null;
    $cidade = isset($_POST['cidade']) ? $_POST['cidade'] : null;
    $estado = isset($_POST['estado']) ? $_POST['estado'] : null;

    if (empty($userEmail)) {
        header("Location: ../cadastroUser.php?error=emailrequired");
        exit();
    }

    if ($userEmail === null) {
        echo "Erro: E-mail não foi enviado corretamente.";
        exit();
    }

    $pwd = addslashes($_POST["password"]);
    $pwdrepeat = addslashes($_POST["confirmpassword"]);
    $perm = "3COL";
    $aprov = "AGRDD";
    date_default_timezone_set('America/Sao_Paulo');
    
    createUser($conn, $userName, $petshop, $userEmail, $celular, $cidade, $estado, $pwd, $pwdrepeat, $aprov, $perm);
} else {
    header("location: .././cadastroUser.php?error=none");
    exit();
}
