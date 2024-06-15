<?php
require 'conexao.php';

$mensagem = null;

// Processamento do formulário quando é submetido via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $raca = $_POST['raca'] ?? '';
    $nome_pet = $_POST['nomePet'] ?? '';
    $nome_tutor = $_POST['nomeTutor'] ?? '';
    $contato_tutor = $_POST['contatoTutor'] ?? '';
    $descricao_servico = $_POST['descricao'] ?? '';
    $observacao = $_POST['observacao'] ?? '';

    // Verificação se já existe um pet com o mesmo nome e dono
    $sql_check_nome = "SELECT COUNT(*) FROM pets WHERE nome = :nome_pet";
    $stmt_check_nome = $dbh->prepare($sql_check_nome);
    $stmt_check_nome->bindParam(':nome_pet', $nome_pet);
    $stmt_check_nome->execute();
    $count = $stmt_check_nome->fetchColumn();

    if ($count > 0) {
        $mensagem = "<p style='color: red; text-align: center; font-size: 1.5em;'>Já existe um pet com o nome e dono informado.</p>";
    } else {
        try {
            // Início da transação para garantir atomicidade das operações
            $dbh->beginTransaction();

            // Inserção ou atualização da raça do pet
            $sql_raca = "INSERT INTO racas (nome) VALUES (:raca) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)";
            $stmt_raca = $dbh->prepare($sql_raca);
            $stmt_raca->bindParam(':raca', $raca);
            $stmt_raca->execute();
            $raca_id = $dbh->lastInsertId();

            // Inserção ou atualização do dono do pet
            $sql_dono = "INSERT INTO donos (nome, contato) VALUES (:nome_tutor, :contato_tutor) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)";
            $stmt_dono = $dbh->prepare($sql_dono);
            $stmt_dono->bindParam(':nome_tutor', $nome_tutor);
            $stmt_dono->bindParam(':contato_tutor', $contato_tutor);
            $stmt_dono->execute();
            $dono_id = $dbh->lastInsertId();

            // Inserção do pet
            $sql_pet = "INSERT INTO pets (nome, raca_id, dono_id) VALUES (:nome_pet, :raca_id, :dono_id)";
            $stmt_pet = $dbh->prepare($sql_pet);
            $stmt_pet->bindParam(':nome_pet', $nome_pet);
            $stmt_pet->bindParam(':raca_id', $raca_id);
            $stmt_pet->bindParam(':dono_id', $dono_id);
            $stmt_pet->execute();
            $pet_id = $dbh->lastInsertId();

            // Busca do ID do serviço selecionado
            $sql_servico = "SELECT id FROM servicos WHERE descricao = :descricao_servico";
            $stmt_servico = $dbh->prepare($sql_servico);
            $stmt_servico->bindParam(':descricao_servico', $descricao_servico);
            $stmt_servico->execute();
            $servico = $stmt_servico->fetch(PDO::FETCH_ASSOC);
            $servico_id = $servico['id'];

            // Inserção na tabela visitaspet
            $sql_visita = "INSERT INTO visitaspet (pet_id, servico_id, dataVisita, observacoes) VALUES (:pet_id, :servico_id, NOW(), :observacao)";
            $stmt_visita = $dbh->prepare($sql_visita);
            $stmt_visita->bindParam(':pet_id', $pet_id);
            $stmt_visita->bindParam(':servico_id', $servico_id);
            $stmt_visita->bindParam(':observacao', $observacao);
            $stmt_visita->execute();

            // Confirmação da transação
            $dbh->commit();
            $mensagem = "<p style='color: green; text-align: center; font-size: 1.5em;'>Pet cadastrado com sucesso!</p>";
        } catch (Exception $e) {
            // Rollback em caso de erro
            $dbh->rollBack();
            $mensagem = "<p style='color: red; text-align: center; font-size: 1.5em;'>Erro ao cadastrar pet: " . $e->getMessage() . "</p>";
        }
    }
}
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
    <?php if ($mensagem) : ?>
        <section class="mensagem">
            <?= $mensagem ?>
        </section>
    <?php endif; ?>

    <section class="principal">
        <section class="principal__imagemfundo">
            <div class="principal__elemento">
                <section class="formulario_central">
                    <h1 id="titulo-cadastro">cadastro pet</h1>
                    <form action="" method="post" class="formulario">
                        <div class="elemento_formulario">
                            <label for="raca">raça<b style="color: #ff0000;">*</b></label>
                            <input class="campo" type="text" id="raca" name="raca" required>
                        </div>
                        <div class="elemento_formulario">
                            <label for="nomePet">nome do pet<b style="color: #ff0000;">*</b></label>
                            <input class="campo" type="text" id="nomePet" name="nomePet" required>
                        </div>
                        <div class="elemento_formulario">
                            <label for="nomeTutor">nome do tutor<b style="color: #ff0000;">*</b></label>
                            <input class="campo" type="text" id="nomeTutor" name="nomeTutor" required>
                        </div>
                        <div class="elemento_formulario">
                            <label for="contatoTutor">contato do tutor<b style="color: #ff0000;">*</b></label>
                            <input class="campo" type="text" id="contatoTutor" name="contatoTutor" required>
                        </div>
                        <div class="elemento_formulario">
                            <label>serviços<b style="color: #ff0000;">*</b></label>
                            <div class="elemento_formulario_2">
                                <input type="radio" name="descricao" value="Banho" required>
                                <label for="banho">banho</label>
                            </div>
                            <div class="elemento_formulario_2">
                                <input type="radio" name="descricao" value="Tosa">
                                <label for="tosa">tosa</label>
                            </div>
                            <div class="elemento_formulario_2">
                                <input type="radio" name="descricao" value="Banho e Tosa">
                                <label for="banhoTosa">banho e tosa</label>
                            </div>
                            <div class="elemento_formulario_2">
                                <input type="radio" name="descricao" value="Vacinação">
                                <label for="vacinacao">vacinação</label>
                            </div>
                            <div class="elemento_formulario_2">
                                <input type="radio" name="descricao" value="Consulta Veterinária">
                                <label for="consultaVeterinaria">consulta veterinária</label>
                            </div>
                        </div>
                        <div class="elemento_formulario">
                            <label for="observacao">observações</label>
                            <input class="campo" type="text" id="observacao" name="observacao">
                        </div>
                        <div class="elemento_formulario_botoes">
                            <button type="submit" class="button-19" role="button">CADASTRAR PET</button>
                            <button type="button" class="button-19_2" role="button" onclick="window.location.href='./index.php'">CANCELAR</button>
                        </div>
                    </form>
                </section>
            </div>
        </section>

    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
</body>
</html>