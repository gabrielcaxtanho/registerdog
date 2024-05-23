<?php
require 'conexao.php';

$mensagem = '';

// Verifica se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pegando os valores do formulário
    $raca = $_POST['raca'] ?? '';
    $nome_pet = $_POST['nomePet'] ?? '';
    $nome_tutor = $_POST['nomeTutor'] ?? '';
    $higienizacao = $_POST['higienizacao'] ?? '';
    $observacoes = $_POST['observacao'] ?? '';

    // Mapeamento dos valores de higienizacao para as descrições
    $higienizacao_descricao = '';
    if ($higienizacao === 'banho') {
        $higienizacao_descricao = 'BANHO';
    } elseif ($higienizacao === 'tosa') {
        $higienizacao_descricao = 'TOSA';
    } elseif ($higienizacao === 'banhoTosa') {
        $higienizacao_descricao = 'BANHO/TOSA';
    }

    try {
        // Cria a consulta SQL para inserção
        $sql = "INSERT INTO pets (raca, nomePet, nomeTutor, higienizacao, observacoes) 
                VALUES (:raca, :nome_pet, :nome_tutor, :higienizacao, :observacoes)";
        
        // Prepara a consulta
        $stmt = $dbh->prepare($sql);
        
        // Vincula os parâmetros
        $stmt->bindParam(':raca', $raca);
        $stmt->bindParam(':nome_pet', $nome_pet);
        $stmt->bindParam(':nome_tutor', $nome_tutor);
        $stmt->bindParam(':higienizacao', $higienizacao);
        $stmt->bindParam(':observacoes', $observacoes);
        
        // Executa a consulta
        $stmt->execute();

        $mensagem = "<p style='color: green; text-align: center; font-size: 1.5em;'>
        Dados inseridos com sucesso!
        </p>";

    } catch (PDOException $e) {
        // Exibe uma mensagem de erro em caso de falha na inserção
        $mensagem = 'Falha na inserção: ' . $e->getMessage();
    }
}

// Consulta SQL para selecionar todos os registros
$sql_select = "SELECT  raca, nomePet, nomeTutor, higienizacao, observacoes FROM pets";
$stmt_select = $dbh->query($sql_select);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Pets</title>
    <link rel="stylesheet" href="css/cadastro_style.css">
</head>
<body>
    <main class="rodape">
        <h1 class="rodape__texto"><i class="fa-solid fa-paw fa-2xl" style="color: #ffffff; margin: 22px;"></i>PET REGISTER</h1>
        <h3 class="rodape__texto">TODOS SEUS REGISTROS EM UM SÓ LUGAR</h3>
    </main>

    <?php if ($mensagem): ?>
        <section class="mensagem">
            <?= $mensagem ?>
        </section>
    <?php endif; ?>

    <section class="principal">
        <section class="principal__imagemfundo">
            <div class="principal__elemento">
               <section class="formulario_central">
                    <h1>CADASTRO DO PET</h1>
                    <form action="" method="post" class="formulario">  
                        <div class="elemento_formulario">
                            <label for="raca">RAÇA:</label>
                            <input class="campo" type="text" id="raca" name="raca" required>
                        </div>
                        <div class="elemento_formulario">
                            <label for="nomePet">NOME DO PET:</label>
                            <input class="campo" type="text" id="nomePet" name="nomePet" required>
                        </div>
                        <div class="elemento_formulario">
                            <label for="nomeTutor">NOME TUTOR:</label>
                            <input class="campo" type="text" id="nomeTutor" name="nomeTutor" required>
                        </div>
                        <div class="elemento_formulario">
                            <label>HIGIENIZAÇÃO:</label>
                            <div class="elemento_formulario_2">
                                <input type="radio" name="higienizacao" value="banho" required>
                                <label for="banho">BANHO</label>
                            </div>
                            <div class="elemento_formulario_2">
                                <input type="radio" name="higienizacao" value="tosa">
                                <label for="tosa">TOSA</label>
                            </div>
                            <div class="elemento_formulario_2">
                                <input type="radio" name="higienizacao" value="banhoTosa">
                                <label for="banhoTosa">BANHO/TOSA</label>
                            </div>
                        </div>
                        <div class="elemento_formulario">
                            <label for="observacao">OBSERVAÇÕES:</label>
                            <input class="campo" type="text" id="observacao" name="observacao" required>
                        </div>
                        <div class="elemento_formulario_botoes">
                            <button type="submit" class="button-19" role="button">CADASTRAR PET</button>
                            <button type="button" class="button-19_2" role="button" onclick="window.location.href='./index.html'">CANCELAR</button>
                        </div>
                    </form>
               </section>
            </div>
        </section>
        
    </section>

    <script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
</body>
</html>
