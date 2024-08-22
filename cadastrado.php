<?php

require './bd/conexao.php';

$nomePet = isset($_GET['nomePet']) ? $_GET['nomePet'] : '';


$sql_select = "
    SELECT
        p.id AS petId,
        p.nome AS nomePet,
        (SELECT nome FROM racas WHERE id = p.raca_id) AS raca,
        (SELECT nome FROM donos WHERE id = p.dono_id) AS nomeTutor,
        (SELECT contato FROM donos WHERE id = p.dono_id) AS contatoTutor,
        (SELECT descricao FROM servicos WHERE id = (SELECT servico_id FROM visitaspet WHERE pet_id = p.id LIMIT 1)) AS servicos,
        (SELECT observacoes FROM servicos WHERE id = (SELECT servico_id FROM visitaspet WHERE pet_id = p.id LIMIT 1)) AS observacoes,
        (SELECT dataVisita FROM visitaspet WHERE pet_id = p.id LIMIT 1) AS dataVisita,
        p.dono_id AS donoId
    FROM
        pets p
    WHERE
        p.nome LIKE :nomePet
";

$stmt_select = $dbh->prepare($sql_select);

$stmt_select->bindValue(':nomePet', "%$nomePet%");

$stmt_select->execute();

if (!$stmt_select) {
    die('Erro na consulta SQL: ' . $dbh->errorInfo()[2]);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pets Cadastrados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include_once 'php/header_index.php'; ?>
    <main class="font">
        <h1>PET REGISTER</h1>
        <h3">TODOS OS SEUS REGISTROS EM UM SÓ LUGAR</h3>

        <h2>Registros de Pets</h2>

        <div class="container my-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form id="pesquisa" method="get" action="">
                        <div class="mb-3">
                            <label for="nomePet" class="form-label">Pesquisar</label>
                            <input type="text" class="form-control" id="nomePet" name="nomePet" value="<?= htmlspecialchars($nomePet) ?>" placeholder="Digite o nome do pet">
                        </div>
                        <button type="submit" class="btn btn-primary w-30" id="submit">Buscar</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8 offset-md-2"> <!-- Centraliza a coluna de 8 colunas -->
            <section class="resultado" id="secaoPets">
                <div class="row"> <!-- Mantém o row apenas para os cartões -->
                    <?php while ($row = $stmt_select->fetch(PDO::FETCH_ASSOC)) : ?>
                        <div class="col-md-4 mb-4"> <!-- Altera o tamanho da coluna para 4 colunas no layout md (tablet e acima) -->
                            <div class="card shadow-sm border-primary h-100"> <!-- Utiliza as classes de cartão do Bootstrap -->
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($row['nomePet']) ?></h5>
                                    <p class="card-text"><strong>Raça:</strong> <?= htmlspecialchars($row['raca']) ?></p>
                                    <p class="card-text"><strong>Nome do Tutor:</strong> <?= htmlspecialchars($row['nomeTutor']) ?></p>
                                    <p class="card-text"><strong>Contato do Tutor:</strong> <?= htmlspecialchars($row['contatoTutor']) ?></p>
                                    <p class="card-text"><strong>Serviços:</strong> <?= htmlspecialchars($row['servicos']) ?></p>
                                    <p class="card-text"><strong>Data da Visita:</strong> <?= htmlspecialchars($row['dataVisita']) ?></p>
                                    <p class="card-text"><strong>Observações:</strong> <?= htmlspecialchars($row['observacoes']) ?></p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <a class="btn btn-primary w-100" href="edit.php?id=<?= $row['petId'] ?>"><i class="fa-solid fa-pencil"></i> Editar</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        </div>


    </main>
    <script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
    <?php
    include_once 'php/footer_index.php';
    ?>
</body>

</html>