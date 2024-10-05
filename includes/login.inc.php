<?php
session_start(); 
require_once '../bd/conexao.php';
require_once 'functions.inc.php';

if (isset($_POST["submit"])) {
    $username = addslashes($_POST["uid"]);
    $pwd = addslashes($_POST["pwd"]);

    if (emptyInputLogin($username, $pwd) !== false) {
        header("location: ../index?error=emptyinput");
        exit();
    }

    loginUser($conn, $username, $pwd);
} else {
    header("location: ../dash.php");
    exit();
}