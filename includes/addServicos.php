<?php
session_start();
require_once '../bd/conexao.php';
require_once 'functions.inc.php';

// Verifica se o usuário está logado
if (isset($_SESSION["userEmail"])) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $petId = $_POST['petId'];
        $servicos = explode(',', $_POST['servicos']); // Serviços selecionados
        $datasVisita = $_POST['datasVisita']; // Datas de visita
        $observacao = isset($_POST['observacao']) ? $_POST['observacao'] : ''; // Observações opcionais
        
        $idCliente = $_SESSION["userid"]; // ID do cliente (dono)

        // Itera sobre os serviços selecionados
        foreach ($servicos as $servicoId) {
            // Itera sobre as datas de visita (se houver mais de uma)
            foreach ($datasVisita as $dataVisita) {
                // Chama a função para adicionar o serviço ao pet
                addservicoPet($conn, $petId, $servicoId, $dataVisita, $idCliente, $observacao);
            }
        }

        // Redireciona após a operação bem-sucedida
        header("location: ../cadastro.php?status=success");
        exit();
    } else {
        // Se o método não for POST, redireciona com erro
        header("location: ../cadastro.php?error=invalid_method");
        exit();
    }
} else {
    // Se o usuário não estiver logado, redireciona para a página de login
    header("location: ../login.php");
    exit();
}
    