<?php

require './bd/conexao.php';

$nomePet = isset($_GET['nomePet']) ? $_GET['nomePet'] : '';

// Inicialize uma variável para armazenar os resultados
$results = [];

// Verifique se o campo de pesquisa não está vazio
if (!empty($nomePet)) {
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

    // Armazene os resultados
    $results = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
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
        <h3>TODOS OS SEUS REGISTROS EM UM SÓ LUGAR</h3>

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

        <!-- Exiba a seção de resultados apenas se houver resultados -->
        <?php if (!empty($results)): ?>
            <div class="container">
                <section class="resultado" id="secaoPets">
                    <div class="row justify-content-center">
                        <?php foreach ($results as $row): ?>
                            <div class="col-md-4 mb-4">
                                <div class="shadow rounded p-4 mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title text-center text-secondary" style="text-transform: uppercase;"><?= htmlspecialchars($row['nomePet']) ?></h5>
                                        <hr style="border-top: #2e8a97 7px solid;">
                                        <p class="card-text text-secondary"><strong>Raça:</strong> <?= htmlspecialchars($row['raca']) ?></p>
                                        <p class="card-text text-secondary"><strong>Nome do Tutor:</strong> <?= htmlspecialchars($row['nomeTutor']) ?></p>
                                        <p class="card-text text-secondary"><strong>Contato do Tutor:</strong> <?= htmlspecialchars($row['contatoTutor']) ?></p>
                                        <p class="card-text text-secondary">
                                            <strong>Data do Cadastro:</strong>
                                            <?php
                                            // Verifica se a dataVisita não é nula ou vazia
                                            if (!empty($row['dataVisita'])) {
                                                $dataVisita = new DateTime($row['dataVisita']);
                                                echo $dataVisita->format('d-m-Y');
                                            } else {
                                                echo 'Data não disponível';
                                            }
                                            ?>
                                        </p>

                                        <p class="card-text text-secondary"><strong>Observações:</strong> <?= htmlspecialchars($row['observacoes']) ?></p>
                                    </div>
                                    <hr>
                                    <div class="card-footer bg-transparent text-center">
                                        <a class="btn" style="color: mediumblue;" href="edit.php?id=<?= $row['petId'] ?>"><i class="fa-solid fa-pencil"></i> Editar</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>
        <?php elseif (!empty($nomePet)): ?>
            <!-- Mensagem quando não há resultados -->
            <div class="col-md-8 offset-md-2">
                <p>Nenhum pet encontrado com o nome "<?php echo htmlspecialchars($nomePet); ?>"</p>
            </div>
        <?php endif; ?>
    </main>
    <script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
    <?php include_once 'php/footer_index.php'; ?>
</body>

</html>