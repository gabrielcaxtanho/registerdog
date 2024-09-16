<?php
if (isset($_POST["submit"])) {
    $username = addslashes($_POST["userName"]);
    $userEmail = addslashes($_POST["usersEmail"]);
    $petshop = addslashes($_POST["nomePetShop"]);
    $celular = addslashes($_POST["celular"]);
    $cidade = addslashes($_POST["cidade"]);
    $estado = addslashes($_POST["estado"]);

    require_once '../bd/conexao.php';
    require_once 'functions.php';

    $pwd = addslashes($_POST["password"]);
    $pwdrepeat = addslashes($_POST["confirmpassword"]);
    $perm = "3COL";
    $aprov = "AGRDD";

    createUser($conn, $nomeSobrenome, $petshop, $username, $celular, $cidade, $estado, $pwd, $pwdrepeat, $aprov, $perm);
} else {
    header("location: .././cadastroUser.php?error=none");
    exit();
}

