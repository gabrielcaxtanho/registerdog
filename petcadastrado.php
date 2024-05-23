<?php
// Conexão com o banco de dados
require 'conexao.php';

$nomePet = isset($_GET['nomePet']) ? $_GET['nomePet'] : '';

// Consulta SQL para selecionar registros pelo nome do pet
$sql_select = "SELECT raca, nomePet, nomeTutor, higienizacao, observacoes FROM pets WHERE nomePet LIKE :nomePet";
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
    </main>
    
    <h2>Registros de Pets</h2>

    <div class="principal__elemento">
                <form method="get" action="">
                    <label for="nomePet">Nome do Pet:</label>
                    <input type="text" id="nomePet" name="nomePet" value="<?= htmlspecialchars($nomePet) ?>">
                    <button type="submit" id="submit">Buscar</button>
                </form>
            </div>

    <section class="resultado" id="secaoPets" style="display: block;">
        <div class="cards-container" id="secaoPets">
        <?php while ($row = $stmt_select->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="card">
                <h3><?= htmlspecialchars($row['nomePet']) ?></h3>
                <p><strong>Raça:</strong> <?= htmlspecialchars($row['raca']) ?></p>
                <p><strong>Nome do Tutor:</strong> <?= htmlspecialchars($row['nomeTutor']) ?></p>
                <p><strong>Higienização:</strong> <?= htmlspecialchars(($row['higienizacao'] === 'banho') ? 'BANHO' : (($row['higienizacao'] === 'tosa') ? 'TOSA' : 'BANHO/TOSA')) ?></p>
                <p><strong>Observações:</strong> <?= htmlspecialchars($row['observacoes']) ?></p>
            </div>
        <?php endwhile; ?>
        </div>
    </section>
</body>
</html>
