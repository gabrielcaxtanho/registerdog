<?php

echo "<pre>";
echo "Dados do POST:\n";
var_dump($_POST);
echo "</pre>";


if (isset($_POST["submit"])) {
    $raca =  addslashes($_POST['raca'] ?? '');
    $nome_pet = addslashes($_POST['nomePet'] ?? '');
    $nome_tutor = addslashes($_POST['nomeTutor'] ?? '');
    $contato_tutor = addslashes($_POST['contatoTutor'] ?? '');
    $descricao_servico = addslashes($_POST['descricao'] ?? '');
    $observacao = addslashes($_POST['observacao'] ?? '');

    require_once '../bd/conexao.php';
    require_once 'functions.inc.php';

    $conn->begin_transaction();

    createPet($conn, $nome_tutor, $contato_tutor, $raca, $nome_pet, $descricao_servico, $observacao);
} else {
    header("location: .././cadastro.php?error=none");
    exit();
}
