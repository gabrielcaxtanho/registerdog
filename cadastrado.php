<?php
// Conexão com o banco de dados
require 'conexao.php';

$nomePet = isset($_GET['nomePet']) ? $_GET['nomePet'] : '';

$sql_select = "SELECT p.nome AS nomePet, r.nome AS raca, d.nome AS nomeTutor, d.contato AS contatoTutor, s.descricao AS servicos, s.observacoes, v.dataVisita 
              FROM pets p 
              LEFT JOIN racas r ON p.raca_id = r.id 
              LEFT JOIN donos d ON p.dono_id = d.id 
              LEFT JOIN visitaspet v ON p.id = v.pet_id 
              LEFT JOIN servicos s ON v.servico_id = s.id 
              WHERE p.nome LIKE :nomePet";

$stmt_select = $dbh->prepare($sql_select);
$stmt_select->bindValue(':nomePet', "%$nomePet%");
$stmt_select->execute();

// Verifica se há erro na execução da consulta
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
    <script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
</head>

<body>
    <main class="rodape">
        <h1 class="rodape__texto"><i class="fa-solid fa-paw fa-2xl" style="color: #ffffff; margin: 22px;"></i>PET REGISTER</h1>
        <h3 class="rodape__texto">TODOS OS SEUS REGISTROS EM UM SÓ LUGAR</h3>

    <h2>Registros de Pets</h2>

    <div class="principal__elemento">
        <form id="pesquisa" method="get" action="">
            <label class="nomePet" for="nomePet">Pesquisar</label>
            <input class="inputnomePet" type="text" id="" name="nomePet" value="<?= htmlspecialchars($nomePet) ?>"  placeholder="Digite o nome do pet">
            <button type="submit" id="submit">Buscar</button>
        </form>
    </div>

    <section class="resultado" id="secaoPets" style="display: block;">
        <div class="cards-container" id="secaoPets">
            <?php while ($row = $stmt_select->fetch(PDO::FETCH_ASSOC)) : ?>
                <div class="card">
                    <h3><?= htmlspecialchars($row['nomePet']) ?></h3>
                    <p><strong>Raça:</strong> <?= htmlspecialchars($row['raca']) ?></p>
                    <p><strong>Nome do Tutor:</strong> <?= htmlspecialchars($row['nomeTutor']) ?></p>
                    <p><strong>Contato do Tutor:</strong> <?= htmlspecialchars($row['contatoTutor']) ?></p>
                    <p><strong>Serviços:</strong> <?= htmlspecialchars($row['servicos']) ?></p>
                    <p><strong>Data da Visita:</strong> <?= htmlspecialchars($row['dataVisita']) ?></p>
                    <p><strong>Observações:</strong> <?= htmlspecialchars($row['observacoes']) ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
    </main>
</body>

</html>
