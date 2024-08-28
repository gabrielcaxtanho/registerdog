<?php
if (isset($_POST["submit"])) {
    $nomeSobrenome = addslashes($_POST["nomeSobrenome"]);
    $petshop = addslashes($_POST["nomePetShop"]);
    $username = addslashes($_POST["userName"]);
    $celular = addslashes($_POST["celular"]);
    $cidade = addslashes($_POST["cidade"]);
    $estado = addslashes($_POST["estado"]);

    require_once '../bd/conexao.php';
    require_once 'functions.php';

    $pwd = addslashes($_POST["password"]);
    $pwdrepeat = addslashes($_POST["confirmpassword"]);
    $perm = "3COL";
    $aprov = "AGRDD";

    // Adiciona o parâmetro $conn na chamada da função
    createUser($conn, $nomeSobrenome, $petshop, $username, $celular, $cidade, $estado, $pwd, $pwdrepeat, $aprov, $perm);
} else {
    header("location: .././cadastroUser.php?error=none");
    exit();
}

