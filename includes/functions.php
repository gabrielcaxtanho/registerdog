<?php
function emptyInputLogin($username, $pwd)
{
    $result = true;

    if (empty($username) || empty($pwd)) {
        $result = true;
    } else {
        $result = false;
    }

    return $result;
}


function loginUser($conn, $username, $pwd)
{
    $uidExists = uidExists($conn, $username, $username);

    if ($uidExists == false) {
        header("location: ../login?error=wronglogin");
        exit();
    }

    $pwdHashed = $uidExists["usersPwd"];
    $_SESSION["useraprovacao"] = getAprov($uidExists);

    $checkPwd = password_verify($pwd, $pwdHashed);

    if ($checkPwd === false) {
        header("location: ../login?error=wronglogin");
        exit();
    } else if ($_SESSION["useraprovacao"] === 'Aguardando') {
        header("location: ../login?error=waitaprov");
        exit();
    } else if ($_SESSION["useraprovacao"] === 'Bloqueado') {
        header("location: ../login?error=bloquser");
        exit();
    } else if ($checkPwd === true) {
        session_start();
        $_SESSION["userid"] = $uidExists["usersId"];
        $_SESSION["useruid"] = $uidExists["usersUid"];
        $_SESSION["userperm"]  = getPermission($uidExists);
        $_SESSION["userfirstname"] = getNameUser($uidExists);

        header("location: ../dash");
        exit();
    }
}


function getPermission($uidExists)
{

    if ($uidExists["usersPerm"] == '1ADM') {
        return 'Administrador';
    } else if ($uidExists["usersPerm"] == '3COL') {
        return 'Colaborador(a)';
    }
}

function getNameUser($uidExists)
{
    $nomeCompleto = $uidExists["usersName"];
    $nomeCompleto = explode(" ", $uidExists["usersName"]);

    return $nomeCompleto[0];
}

function getAprov($uidExists)
{
    if ($uidExists["usersAprov"] == 'AGRDD') {
        return 'Aguardando';
    } else if ($uidExists["usersAprov"] == 'APROV') {
        return 'Aprovado';
    } else if ($uidExists["usersAprov"] == 'BLOQD') {
        return 'Bloqueado';
    }
}


function newPassword($conn, $email)
{

    $uidExists = uidExists($conn, $email, $email);

    if ($uidExists == false) {
        header("location: ../senha?error=wrongemail");
        exit();
    }

    $uid = $uidExists["usersUid"];
    $userEmail = $uidExists["usersEmail"];
    $celular = $uidExists["usersCel"];
    $nomeCompleto = $uidExists["usersName"];
    $nomeCompleto = explode(" ", $uidExists["usersName"]);
    $nome = $nomeCompleto[0];

 /*    $pwd = generatePwd();
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    editPwdFromRecover($conn, $uid, $hashedPwd, $userEmail, $nome, $pwd, $celular); */
}

function uidExists($conn, $username, $email)
{
    $sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);
    $prepare = mysqli_stmt_prepare($stmt, $sql);


    if (!$prepare) {
        header("location: ../cadastro?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        mysqli_stmt_close($stmt);
        $result = false;
        return $result;
    }
}

function createUser($conn, $nomeSobrenome, $petshop, $username, $celular, $cidade, $estado, $pwd, $pwdrepeat, $aprov, $perm)
{
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    $sql_check = "SELECT * FROM users WHERE userName = ? OR celular = ?";
    $stmt_check = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt_check, $sql_check)) {
        header("Location: ../cadastro?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt_check, "ss", $username, $celular);
    mysqli_stmt_execute($stmt_check);
    $resultData = mysqli_stmt_get_result($stmt_check);

    if (mysqli_fetch_assoc($resultData)) {
        header("Location: .././cadastroUser.php?error=userorexists");
        exit();
    }

    mysqli_stmt_close($stmt_check);

    $sql = "INSERT INTO users (nomeSobrenome, nomePetShop, celular, cidade, estado, password, dataInicio, usersAprov, userName, userperm) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)";
    
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        // Mensagem de depuração
        echo "Erro na preparação do SQL: " . mysqli_error($conn);
        header("Location: .././cadastroUser.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sssssssss", $nomeSobrenome, $petshop, $celular, $cidade, $estado, $hashedPwd, $aprov, $username, $perm);

    if (!mysqli_stmt_execute($stmt)) {
        echo "Erro na execução do SQL: " . mysqli_error($conn);
        header("Location: .././cadastroUser.php?error=executionfailed");
    } else {
        header("Location: .././cadastroUser.php?success=usercreated");
    }

    mysqli_stmt_close($stmt);
    exit();
}


function editUser($conn, $nome, $uf, $email, $uid, $celular, $identificador, $aprov, $perm, $dep, $usersid)
{
    $sql = "UPDATE users SET usersName='$nome', usersUf='$uf', usersEmail='$email', usersUid='$uid', usersCel='$celular', usersIdentificador='$identificador', usersAprov='$aprov', usersPerm='$perm', usersDepartamento='$dep' WHERE usersId='$usersid'";


    if (mysqli_query($conn, $sql)) {
        header("location: ../users?error=none");
    } else {
        header("location: ../users?error=stmfailed");
    }
    mysqli_close($conn);


    exit();
}

function aprovUser($conn, $id, $nome, $uid, $email, $celular)
{
    $aprov = "APROV";

    $sql = "UPDATE users SET usersAprov='$aprov' WHERE usersId='$id'";

    if (mysqli_query($conn, $sql)) {
        header("location: users");
    } else {
        header("location: users?error=stmfailed");
    }
    mysqli_close($conn);

    //notificar

    $content = '*Olá, ' . $nome . '!* Já está tudo pronto para seu 1º acesso no Portal Conecta. Por favor, entre no site dev.conecta.cpmhdigital.com.br e efetue o login com seu usuário *' . $uid . '*. Caso tenha alguma dificuldade, entre em contato com nosso suporte pelo e-mail negocios@cpmh.com.br ou pelo número +55(61)999468880.';


    $cel = implode('', explode(' ', $celular));
    $cel = implode('', explode('-', $cel));
    $cel = implode('', explode('(', $cel));
    $cel = implode('', explode(')', $cel));
    $notificationCelular = '+55' . $cel;


    /* sendEmailNotificationCadastroAprovado($email, $nome, $uid);

    sendNotification($notificationCelular, $content); */
}


function deleteUser($conn, $id)
{
    $sql = "DELETE FROM users WHERE usersId='$id'";

    if (mysqli_query($conn, $sql)) {
        header("location: users?error=deleted");
    } else {
        header("location: users?error=stmtfailed");
    }
    mysqli_close($conn);
}
/* 
function editPwd($conn, $user, $pwd, $confirmpwd)
{

    if (pwdMatch($pwd, $confirmpwd)) {
        header("location: ../profile?usuario=" . $user . "&error=pwderror");
        exit();
    } else {
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    }

    $sql = "UPDATE users SET usersPwd='$hashedPwd' WHERE usersUid='$user'";

    if (mysqli_query($conn, $sql)) {

        header("location: ../profile?usuario=" . $user . "&error=none");
    } else {
        header("location: ../profile?usuario=" . $user . "&error=stmfailed");
    }
    mysqli_close($conn);
} */
function emptyInputSignup($name, $username, $email, $celular, $identificador, $uf, $pwd, $pwdrepeat)
{
    $result = true;

    if (empty($name) || empty($email) || empty($username) || empty($pwd) || empty($pwdrepeat || empty($celular) || empty($uf) || empty($identificador))) {
        $result = true;
    } else {
        $result = false;
    }

    return $result;
}
function invalidUid($username)
{
    $result = true;

    if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $result = true;
    } else {
        $result = false;
    }

    return $result;
}
function invalidEmail($email)
{
    $result = true;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    } else {
        $result = false;
    }

    return $result;
}

function pwdMatch($pwd, $pwdrepeat)
{
    $result = true;

    if ($pwd !== $pwdrepeat) {
        $result = true;
    } else {
        $result = false;
    }

    return $result;
}
?>