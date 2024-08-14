<?php
// Conexão com o banco de dados
require 'conexao.php';

// Verifica se o parâmetro nomePet foi enviado via GET
$nomePet = isset($_GET['nomePet']) ? $_GET['nomePet'] : '';

// Consulta SQL para buscar informações de pets com base no nome do pet
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
    

// Prepara a consulta
$stmt_select = $dbh->prepare($sql_select);

// Substitui o parâmetro :nomePet pelo valor apropriado (com wildcard %)
$stmt_select->bindValue(':nomePet', "%$nomePet%");

// Executa a consulta
$stmt_select->execute();

// Verifica se houve erro na execução da consulta
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
    <link rel="stylesheet" href="css/petcadastrado.css">
</head>

<body>
    <main class="rodape">
        <h1 class="rodape__texto"><i class="fa-solid fa-paw fa-2xl" style="color: #ffffff; margin: 22px;"></i>PET REGISTER</h1>
        <h3 class="rodape__texto">TODOS OS SEUS REGISTROS EM UM SÓ LUGAR</h3>

        <h2>Registros de Pets</h2>

        <div class="principal__elemento">
            <form id="pesquisa" method="get" action="">
                <label class="nomePet" for="nomePet">Pesquisar</label>
                <input class="inputnomePet" type="text" id="" name="nomePet" value="<?= htmlspecialchars($nomePet) ?>" placeholder="Digite o nome do pet">
                <button type="submit" id="submit">Buscar</button>
            </form>
        </div>

        <section class="resultado" id="secaoPets">
            <div class="cards-container">
                <?php while ($row = $stmt_select->fetch(PDO::FETCH_ASSOC)) : ?>
                    <div class="card" id="pet-<?= $row['petId'] ?>">
                        <h3><?= htmlspecialchars($row['nomePet']) ?></h3>
                        <p><strong>Raça:</strong> <?= htmlspecialchars($row['raca']) ?></p>
                        <p><strong>Nome do Tutor:</strong> <?= htmlspecialchars($row['nomeTutor']) ?></p>
                        <p><strong>Contato do Tutor:</strong> <?= htmlspecialchars($row['contatoTutor']) ?></p>
                        <p><strong>Serviços:</strong> <?= htmlspecialchars($row['servicos']) ?></p>
                        <p><strong>Data da Visita:</strong> <?= htmlspecialchars($row['dataVisita']) ?></p>
                        <p><strong>Observações:</strong> <?= htmlspecialchars($row['observacoes']) ?></p>
                        <!-- Ajuste o href abaixo para passar o ID do pet -->
                        <a class="btn btn-primary" href="edit.php?id=<?= $row['petId'] ?>"><i class="fa-solid fa-pencil"></i></a>
                    </div>
                <?php endwhile; ?>

            </div>
        </section>
    </main>
    <script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
</body>

</html>