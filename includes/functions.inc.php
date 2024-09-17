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

    $uidExists = uidExists($conn, $userEmail, $userEmail);

    if ($uidExists === false) {
        echo "Usuário não encontrado"; 
        exit(); 
    }

    $pwdHashed = $uidExists["password"];
    $_SESSION["useraprovacao"] = getAprov($uidExists);

    $checkPwd = password_verify($pwd, $pwdHashed);

    if ($checkPwd === false) {
        header("Location: /registerPet/login.php?error=wronglogin");
        exit();
    } else if ($_SESSION["useraprovacao"] === 'Aguardando') {
        header("location: ../login.php?error=waitaprov");
        exit();
    } else if ($_SESSION["useraprovacao"] === 'Bloqueado') {
        header("location: ../login.php?error=bloquser");
        exit();
    } else if ($checkPwd === true) {
        $_SESSION["userid"] = $uidExists["id"];
        $_SESSION["userName"] = $uidExists["userName"];
        $_SESSION["userEmail"] = $uidExists["userEmail"];
        $_SESSION["userperm"]  = getPermission($uidExists);

        /* echo "<pre>";
        var_dump($_SESSION);
        echo "</pre>"; */

        echo "Login bem-sucedido"; 
        header("Location: ../index.php"); 
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
    $userEmail = $uidExists["usersEmail"];
    $celular = $uidExists["celular"];
    $userName = $uidExists["usersName"];
    $userName = explode(" ", $uidExists["usersName"]);
    $nome = $userName[0];

    /*    $pwd = generatePwd();
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    editPwdFromRecover($conn, $uid, $hashedPwd, $userEmail, $nome, $pwd, $celular); */
}

function uidExists($conn, $userName, $userEmail)
{
    $sql = "SELECT * FROM users WHERE userName = ? OR userEmail = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Erro na preparação da consulta SQL: " . mysqli_error($conn); 
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $userName, $userEmail);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        echo "Nenhum usuário encontrado com userName: $userName e userEmail: $userEmail"; 
        header("Location: /registerPet/login.php?error=stmtfailed");
        exit();
    }
}

function createUser($conn, $userEmail, $petshop, $username, $celular, $cidade, $estado, $pwd, $pwdrepeat, $aprov, $perm)
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

    $sql = "INSERT INTO users (userName, nomePetShop, celular, cidade, estado, password, dataInicio, usersAprov, userEmail, userperm) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        // Mensagem de depuração
        echo "Erro na preparação do SQL: " . mysqli_error($conn);
        header("Location: .././cadastroUser.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sssssssss", $username,  $petshop, $celular, $cidade, $estado, $hashedPwd, $aprov, $userEmail, $perm);

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

function createPet($conn, $nome_tutor, $contato_tutor, $raca, $nome_pet, $descricao_servico, $observacao)
{
    // Inicia a transação
    $conn->begin_transaction();

    try {
        // Debug: Mostra valores recebidos
        error_log(print_r([$nome_tutor, $contato_tutor, $raca, $nome_pet, $descricao_servico, $observacao], true));

        // Verifica se o dono já existe
        $stmt = $conn->prepare("SELECT id FROM donos WHERE telefone = ?");
        if ($stmt === false) {
            throw new Exception("Erro na preparação da consulta: " . $conn->error);
        }
        $stmt->bind_param("s", $contato_tutor);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $dono_id = $row['id'];
        } else {
            $data = date('Y-m-d');

            // Insere o dono com a data
            $stmt = $conn->prepare("INSERT INTO donos (nome, telefone, data) VALUES (?, ?, ?)");
            if ($stmt === false) {
                throw new Exception("Erro na preparação da consulta: " . $conn->error);
            }

            // Adicione "s" para o terceiro parâmetro, que é a data
            $stmt->bind_param("sss", $nome_tutor, $contato_tutor, $data);
            $stmt->execute();
            $dono_id = $conn->insert_id;
        }


        // Verifica se a raça já existe
        $stmt = $conn->prepare("SELECT id FROM racas WHERE nome = ?");
        if ($stmt === false) {
            throw new Exception("Erro na preparação da consulta: " . $conn->error);
        }
        $stmt->bind_param("s", $raca);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $raca_id = $row['id'];
        } else {
            // Insere a raça
            $stmt = $conn->prepare("INSERT INTO racas (nome) VALUES (?)");
            if ($stmt === false) {
                throw new Exception("Erro na preparação da consulta: " . $conn->error);
            }
            $stmt->bind_param("s", $raca);
            $stmt->execute();
            $raca_id = $conn->insert_id;
        }

        // Insere o pet com a data atual
        $stmt = $conn->prepare("INSERT INTO pets (nome, idDono, idRaca, descricao) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception("Erro na preparação da consulta: " . $conn->error);
        }
        $stmt->bind_param("siss", $nome_pet, $dono_id, $raca_id);
        $stmt->execute();
        $pet_id = $conn->insert_id;

        // Verifica se o serviço já existe
        $stmt = $conn->prepare("SELECT id FROM servico WHERE descricao = ?");
        if ($stmt === false) {
            throw new Exception("Erro na preparação da consulta: " . $conn->error);
        }
        $stmt->bind_param("s", $descricao_servico);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $servico_id = $row['id'];
        } else {
            // Insere o serviço se não existir
            $stmt = $conn->prepare("INSERT INTO servico (descricao) VALUES (?)");
            if ($stmt === false) {
                throw new Exception("Erro na preparação da consulta: " . $conn->error);
            }
            $stmt->bind_param("s", $descricao_servico);
            $stmt->execute();
            $servico_id = $conn->insert_id;
        }

        // Insere a visita/serviço realizado
        $stmt = $conn->prepare("INSERT INTO frequencia (idCliente, idPet, idServico, dataVisita, observacao) VALUES (?, ?, ?, CURDATE(), ?)");
        if ($stmt === false) {
            throw new Exception("Erro na preparação da consulta: " . $conn->error);
        }
        $stmt->bind_param("iiis", $dono_id, $pet_id, $servico_id, $observacao);
        $stmt->execute();

        // Confirma a transação
        $conn->commit();

        // Redireciona com sucesso
        header("Location: .././cadastro.php?success=usercreated");
        exit();
    } catch (Exception $e) {
        // Desfaz a transação em caso de erro
        $conn->rollback();
        // Redireciona com erro
        header("Location: .././cadastro.php?error=" . urlencode($e->getMessage()));
        exit();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}
