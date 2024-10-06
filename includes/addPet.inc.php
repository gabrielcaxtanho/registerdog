    <?php
    session_start();
    require_once '../bd/conexao.php';
    require_once 'functions.inc.php';

    if (isset($_SESSION["userEmail"])) {

        if (isset($_POST["submit"])) {

            $raca = addslashes($_POST['raca'] ?? '');
            $nome = addslashes($_POST['nomePet'] ?? '');
            $dono = addslashes($_POST['nomeTutor'] ?? '');
            $idade = addslashes($_POST['idade'] ?? '');
            $contato = addslashes($_POST['contatoTutor'] ?? '');
            $observacao = addslashes($_POST['observacao'] ?? '');
            $idResp = $_SESSION["userid"]; // Pegando o usuário logado da sessão

            date_default_timezone_set('America/Sao_Paulo');
            $hoje = date('Y-m-d');
            $agora = date('H:i:s');
            /* echo "<p>raca: $raca, <p>nome dono: $dono, <p>nome pet: $nome ";
        echo "<p>idade: $idade, contato: $contato, observacao: $observacao </p>";
        echo "<p>resp: $idResp</p>";
        echo "Data e Hora de Início: " . $hoje . $agora;

        die();  */

            createPet($conn, $raca, $nome, $idade, $dono, $contato, $observacao, $idResp, $hoje, $agora);
        } else {
            header("location: ../cadastro.php?error=none");
            exit();
        }
    }
