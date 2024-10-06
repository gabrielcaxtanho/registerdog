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

function loginUser($conn, $userEmail, $pwd)
{
    session_start();

    $uidExists = uidExists($conn, $userEmail, $pwd);

    if ($uidExists === false) {
        echo "Usuário não encontrado";
        exit();
    }

    $pwdHashed = $uidExists["password"];
    $_SESSION["useraprovacao"] = getAprov($uidExists);

    $checkPwd = password_verify($pwd, $pwdHashed);

    if ($checkPwd === false) {
        header("Location: /registerPet/index.php?error=wronglogin");
        exit();
    } else if ($_SESSION["useraprovacao"] === 'Aguardando') {
        header("location: ../index.php?error=waitaprov");
        exit();
    } else if ($_SESSION["useraprovacao"] === 'Bloqueado') {
        header("location: ../index.php?error=bloquser");
        exit();
    } else if ($checkPwd === true) {
        $_SESSION["userid"] = $uidExists["userid"];
        $_SESSION["userName"] = $uidExists["userName"];
        $_SESSION["userEmail"] = $uidExists["userEmail"];
        $_SESSION["userperm"]  = getPermission($uidExists);

        /* echo "<pre>";
        var_dump($_SESSION);
        echo "</pre>"; */

        echo "Login bem-sucedido";
        header("Location: ../dash.php");
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

    $uid = $uidExists["userName"];
    $userEmail = $uidExists["userEmail"];
    $celular = $uidExists["celular"];
    $userName = $uidExists["usersName"];
    $userName = explode(" ", $uidExists["usersName"]);
    $nome = $userName[0];

    /*    $pwd = generatePwd();
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    editPwdFromRecover($conn, $uid, $hashedPwd, $userEmail, $nome, $pwd, $celular); */
}

function uidExists($conn, $userEmail, $userName)
{
    $sql = "SELECT * FROM users WHERE userEmail = ? OR userName = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Erro na preparação da consulta SQL: " . mysqli_error($conn);
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $userEmail, $userName);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        header("Location: /registerPet/index.php?error=stmtfailed");
        exit();
    }
}

function createUser($conn, $userName, $petshop, $userEmail, $celular, $cidade, $estado, $pwd, $pwdrepeat, $aprov, $perm)
{
    // Verificar se as senhas são iguais
    /* if ($pwd !== $pwdrepeat) {
        header("Location: ../cadastroUser.php?error=passwordmismatch");
        exit();
    } */

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    // Verificar se o usuário já existe
    $sql_check = "SELECT * FROM users WHERE userEmail = ? OR celular = ?";
    $stmt_check = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt_check, $sql_check)) {
        header("Location: ../cadastroUser.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt_check, "ss", $userEmail, $celular);
    mysqli_stmt_execute($stmt_check);
    $resultData = mysqli_stmt_get_result($stmt_check);
    $resultCheck = mysqli_fetch_assoc($resultData);

    if ($resultCheck) {
        header("Location: ../cadastroUser.php?error=userorexists");
        exit();
    }

    mysqli_stmt_close($stmt_check);

    // Inserção de dados no banco
    $sql = "INSERT INTO users (userName, nomePetShop, celular, cidade, estado, password, dataInicio, usersAprov, userEmail, userperm) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Erro na preparação do SQL: " . mysqli_error($conn);
        exit();
    }

    // Correção na ordem dos parâmetros
    mysqli_stmt_bind_param($stmt, "sssssssss", $userName, $petshop, $celular, $cidade, $estado, $hashedPwd, $aprov, $userEmail, $perm);

    if (!mysqli_stmt_execute($stmt)) {
        echo "Erro na execução do SQL: " . mysqli_error($conn);
        exit();
    } else {
        header("Location: ../cadastroUser.php?success=usercreated");
    }

    mysqli_stmt_close($stmt);
    exit();
}

function editUser($conn, $nome, $uf, $email, $uid, $celular, $identificador, $aprov, $perm, $dep, $usersid)
{
    $sql = "UPDATE users SET usersName='$nome', usersUf='$uf', userEmail='$email', usersUid='$uid', usersCel='$celular', usersIdentificador='$identificador', usersAprov='$aprov', usersPerm='$perm', usersDepartamento='$dep' WHERE usersId='$usersid'";


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

function hoje()
{
    date_default_timezone_set('UTC');
    $dtz = new DateTimeZone("America/Sao_Paulo");
    $dt = new DateTime("now", $dtz);
    $hoje = $dt->format("Y-m-d");
    // $horaAtual = $dt->format("H:i:s");

    // $thisMonth = date('m');
    // $thisYear = date('Y');
    // $thisDay = date('d');
    // $hoje = $thisYear . "-" . $thisMonth . "-" . $thisDay;

    return $hoje;
}

function agora()
{

    date_default_timezone_set('UTC');
    $dtz = new DateTimeZone("America/Sao_Paulo");
    $dt = new DateTime("now", $dtz);
    // $hoje = $dt->format("Y-m-d");
    $thisHour = $dt->format("H:i:s");
    // $thisHour = date("H:i:s");

    return $thisHour;
}

function createDono($conn, $nomeTutor, $contato)
{
    $sql = "INSERT INTO donos (nome, contato) VALUES (?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Erro na preparação da query: " . mysqli_stmt_error($stmt);
        return null; // Retorna null se falhar
    }

    mysqli_stmt_bind_param($stmt, "ss", $nomeTutor, $contato);
    if (!mysqli_stmt_execute($stmt)) {
        echo "Erro ao inserir dono: " . mysqli_stmt_error($stmt);
        return null; // Retorna null se falhar
    }

    mysqli_stmt_close($stmt);
    return mysqli_insert_id($conn); // Retorna o ID do dono inserido
}

function createPet($conn, $raca, $nome, $idade, $dono, $contato, $observacao, $idResp, $hoje, $agora) {
    // Verifica se o dono já existe
    $sqlVerificaDono = "SELECT id FROM donos WHERE telefone = ?";
    $stmt = $conn->prepare($sqlVerificaDono);
    $stmt->bind_param('s', $contato);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Dono já existe, pega o ID
        $stmt->bind_result($dono);
        $stmt->fetch();
    } else {
        // Dono não existe, insere um novo
        $sqlInsereDono = "INSERT INTO donos (nome, telefone, data, idusers) VALUES (?, ?, NOW(), ?)";
        $stmtInsere = $conn->prepare($sqlInsereDono);
        $stmtInsere->bind_param('ssi', $dono, $contato, $idResp);
        $stmtInsere->execute();
        $dono = $stmtInsere->insert_id; // Pega o ID do novo dono inserido
        $stmtInsere->close(); // Fecha a query de inserção
    }

    // Verifica se a raça já existe
    $sqlVerificaRaca = "SELECT id FROM racas WHERE nome = ?";
    $stmt = $conn->prepare($sqlVerificaRaca);
    $stmt->bind_param('s', $raca);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Raça já existe, pega o ID
        $stmt->bind_result($raca);
        $stmt->fetch();
    } else {
        // Raça não existe, insere uma nova
        $sqlInsereRaca = "INSERT INTO racas (nome, idusers) VALUES (?, ?)";
        $stmtInsereRaca = $conn->prepare($sqlInsereRaca);
        $stmtInsereRaca->bind_param('si', $raca, $idResp);
        $stmtInsereRaca->execute();
        $raca = $stmtInsereRaca->insert_id; // Pega o ID da nova raça inserida
        $stmtInsereRaca->close(); // Fecha a query de inserção
    }

    // Inserir o novo pet associado ao dono e à raça
    $sqlInserePet = "INSERT INTO pets (dono, nome, idade, contato, hora, data, idusers, observacao, idRaca) VALUES (?, ?, ?, ?, CURTIME(), CURDATE(), ?, ?, ?)";
    $stmtInserePet = $conn->prepare($sqlInserePet);
    $stmtInserePet->bind_param('isiiisi', $dono, $nome, $idade, $contato, $idResp, $observacao, $raca);

    if ($stmtInserePet->execute()) {
        header("Location: ../cadastro.php?success=usercreated");
    } else {
        echo "Erro ao inserir registro: " . $stmtInserePet->error;
    }

    $stmt->close();
    $stmtInserePet->close();
}

function dd($parametro, $parametro2 = '', $parametro3 = ''): never
{

    echo '<pre>';
    print_r($parametro);
    echo '</pre>';
    die("<h1 style=\"color: rebeccapurple;\">Voce esta debugando o CÓDIGO <i class=\"bi bi-emoji-sunglasses-fill\"></i>     <i class=\"bi bi-code-slash\"></i>  </h1> <br>
    <img style=\"width: 150px; margin:100px;\" src=\"assetsnew/img/programador.jpg\" alt=\"\">");
}
