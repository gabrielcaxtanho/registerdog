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
    <title>Historico</title>
    <link rel="icon" href="img/logopets1.png" type="image">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <?php include_once 'php/header_index.php'; ?>
    <main class="font">
       <!--  <h2>Historico de Pets</h2> -->

        <!-- <div class="container my-4">
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
        </div> -->

        <!-- Card com placeholders -->
        <div class="container-fluid py-4">
            <div class="row d-flex justify-content-center">

                <div class="col-sm-10">
                    <div class="row d-flex justify-content-center">
                        <div class="col-sm d-flex justify-content-start" style="flex-direction: column;">
                            <h5 class="text-muted"><b>Log de Atividades</b></h5>
                            <small class="text-muted">Historico de Pets</small>
                        </div>
                    </div>
                    <hr>
                    <div class="container my-4">
                        <div class="row justify-content-end">
                            <div class="col-md-3">
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

                    <?php if (empty($nomePet)): ?>
                        <div class="">
                            <div class="card-body">
                                <div class="content-panel" style="overflow-x: scroll;">
                                    <table id="table" class="table table-striped table-advance table-hover">

                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Responsável</th>
                                                <th>Pet</th>
                                                <th>Raça</th>
                                                <th>Data/Hora</th>
                                                <th>Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = mysqli_query($conn, "SELECT * FROM logatividades ORDER BY logId DESC;");

                                            while ($row = mysqli_fetch_array($ret)) {
                                                $OsRef = $row['logOsRef'];
                                                $Horario = $row['logHorario'];
                                                $User = $row['logUser'];
                                                $Status = $row['logStatus'];

                                                switch ($Status) {
                                                    case 'PAUSADO':
                                                        $badgeStatus = "badge-danger";
                                                        break;
                                                    case 'EM ANDAMENTO':
                                                        $badgeStatus = "badge-warning";
                                                        break;
                                                    case 'CONCLUÍDO':
                                                        $badgeStatus = "badge-success";
                                                        break;
                                                    case 'CRIADO':
                                                        $badgeStatus = "badge-info";
                                                        break;

                                                    default:
                                                        $badgeStatus = "badge-secondary";
                                                        break;
                                                }

                                                /* $data = dateAndHourFormat($Horario); */

                                            ?>

                                                <tr>
                                                    <td><?php echo $OsRef; ?></td>
                                                    <td><?php echo $data; ?></td>
                                                    <td><?php echo $User; ?></td>
                                                    <td><span class="badge <?php echo $badgeStatus; ?>"><?php echo $Status; ?></span></td>
                                                </tr>
                                            <?php
                                            } ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    <?php elseif (!empty($results)): ?>
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