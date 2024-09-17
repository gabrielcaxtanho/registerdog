<?php
session_start(); 

if (isset($_POST["submit"])) {
    $username = addslashes($_POST["uid"]);
    $pwd = addslashes($_POST["pwd"]);

    require_once '../bd/conexao.php';
    require_once 'functions.inc.php';


    if (emptyInputLogin($username, $pwd) !== false) {
        header("location: ../login?error=emptyinput");
        exit();
    }

    loginUser($conn, $username, $pwd);
} else {
    header("location: ../login");
    exit();
}