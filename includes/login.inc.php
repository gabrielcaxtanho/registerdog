<?php
session_start(); // Inicia a sessão no início do arquivo

if (isset($_POST["submit"])) {
    $username = addslashes($_POST["uid"]);
    $pwd = addslashes($_POST["pwd"]);

    require_once '../bd/conexao.php';
    require_once 'functions.php';

    // Verifica se os campos estão preenchidos
    if (emptyInputLogin($username, $pwd) !== false) {
        header("location: ../login?error=emptyinput");
        exit();
    }

    // Função de login
    loginUser($conn, $username, $pwd);
} else {
    header("location: ../login");
    exit();
}
/* 
function loginUser($conn, $username, $pwd) {
    // Verifica se o usuário existe
    $uidExists = uidExists($conn, $username, $username);

    if ($uidExists === false) {
        header("location: ../login?error=wronglogin");
        exit();
    }

    // Verifica a senha
    $pwdHashed = $uidExists["usersPwd"];
    $checkPwd = password_verify($pwd, $pwdHashed);

    // Se a senha estiver incorreta
    if ($checkPwd === false) {
        header("location: ../login?error=wronglogin");
        exit();
    }

    // Verifica status de aprovação do usuário
    $_SESSION["useraprovacao"] = getAprov($uidExists);

    if ($_SESSION["useraprovacao"] === 'Aguardando') {
        header("location: ../login?error=waitaprov");
        exit();
    } else if ($_SESSION["useraprovacao"] === 'Bloqueado') {
        header("location: ../login?error=bloquser");
        exit();
    }

    // Se senha correta e aprovação ok
    if ($checkPwd === true) {
        // Inicia a sessão do usuário
        session_start();
        $_SESSION["userid"] = $uidExists["usersId"];
        $_SESSION["useruid"] = $uidExists["usersUid"];
        $_SESSION["userperm"] = getPermission($uidExists);

        // Redireciona para o dashboard
        header("location: ../dash");
        exit();
    }
}
 */