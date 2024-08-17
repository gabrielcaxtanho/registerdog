<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Pet Register Dashboard</title>
</head>

<body>
    <?php include_once 'php/header_index.php'; ?>

    <?php
    require 'conexao.php';


    $nomePet = isset($_GET['nomePet']) ? $_GET['nomePet'] : '';
    $sql_select = "SELECT p.nome AS nomePet, r.nome AS raca, d.nome AS nomeTutor, d.contato AS contatoTutor, 
                   s.descricao AS servicospet, s.observacoes, v.dataVisita 
                   FROM pets p 
                   LEFT JOIN racas r ON p.raca_id = r.id 
                   LEFT JOIN donos d ON p.dono_id = d.id 
                   LEFT JOIN visitaspet v ON p.id = v.pet_id 
                   LEFT JOIN servicos s ON v.servico_id = s.id 
                   WHERE p.nome LIKE :nomePet";
    $stmt_select = $dbh->prepare($sql_select);
    $stmt_select->bindValue(':nomePet', "%$nomePet%");
    $stmt_select->execute();

    
    $sql_racas_frequentes = "SELECT r.nome AS raca, COUNT(v.id) AS frequencia
                             FROM visitaspet v
                             JOIN pets p ON v.pet_id = p.id
                             JOIN racas r ON p.raca_id = r.id
                             GROUP BY r.nome
                             ORDER BY frequencia DESC";
    $stmt_racas_frequentes = $dbh->query($sql_racas_frequentes);

   
    $sql_servicos_solicitados = "SELECT s.descricao AS servico, COUNT(v.id) AS frequencia
                                 FROM visitaspet v
                                 JOIN servicos s ON v.servico_id = s.id
                                 GROUP BY s.descricao
                                 ORDER BY frequencia DESC";
    $stmt_servicos_solicitados = $dbh->query($sql_servicos_solicitados);

    
    $sql_visitas_dono = "SELECT d.nome AS dono, COUNT(v.id) AS visitas, p.nome AS nomePet, d.contato AS contatoTutor, 
                         s.descricao AS servicospet, s.observacoes, v.dataVisita
                         FROM visitaspet v
                         JOIN pets p ON v.pet_id = p.id
                         JOIN donos d ON p.dono_id = d.id
                         JOIN servicos s ON v.servico_id = s.id
                         GROUP BY d.nome, p.nome, d.contato, s.descricao, s.observacoes, v.dataVisita
                         ORDER BY visitas DESC";
    $stmt_visitas_dono = $dbh->query($sql_visitas_dono);

    
    $sql_pet_mais_frequente = "SELECT p.nome AS pet, COUNT(v.id) AS visitas
                               FROM visitaspet v
                               JOIN pets p ON v.pet_id = p.id
                               GROUP BY p.nome
                               ORDER BY visitas DESC
                               LIMIT 1";
    $stmt_pet_mais_frequente = $dbh->query($sql_pet_mais_frequente);
    $row_pet_mais_frequente = $stmt_pet_mais_frequente->fetch(PDO::FETCH_ASSOC);


    $sql_dono_mais_frequente = "SELECT d.nome AS dono, COUNT(v.id) AS visitas
                                FROM visitaspet v
                                JOIN pets p ON v.pet_id = p.id
                                JOIN donos d ON p.dono_id = d.id
                                GROUP BY d.nome
                                ORDER BY visitas DESC
                                LIMIT 1";
    $stmt_dono_mais_frequente = $dbh->query($sql_dono_mais_frequente);
    $row_dono_mais_frequente = $stmt_dono_mais_frequente->fetch(PDO::FETCH_ASSOC);

    
    if (!$stmt_select || !$stmt_racas_frequentes || !$stmt_servicos_solicitados || !$stmt_visitas_dono || !$stmt_pet_mais_frequente || !$stmt_dono_mais_frequente) {
        die('Erro na consulta SQL: ' . $dbh->errorInfo()[2]);
    }
    ?>

    <div class="container my-4">
        <div class="row">
            <div class="col">
                <button class="btn btn-primary" style="background-color: cadetblue;">Click aqui e conheça o petResgate</button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">
                <hr class="border-white">
                <div class="card shadow rounded p-4 h-100" style="border-top: #2e8a97 7px solid;">
                    <h5 class="text-ciano-agiliza">😄 Frequência!</h5>
                    <span class="text-muted">Pet e Dono</span>

                    <div class="row my-4">
                        <div class="col-md-6">
                            <div class="card mb-3 border-primary">
                                <div class="card-body">
                                    <i class="fa-solid fa-crown text-warning" style="font-size: 2em;"></i>
                                    <h5 class="card-title mt-2">Dono Mais Frequente</h5>
                                    <p class="card-text"><strong>Dono:</strong> <?= htmlspecialchars($row_dono_mais_frequente['dono']) ?></p>
                                    <p class="card-text"><strong>Visitas:</strong> <?= htmlspecialchars($row_dono_mais_frequente['visitas']) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-3 border-primary">
                                <div class="card-body">
                                    <i class="fa-solid fa-crown text-warning" style="font-size: 2em;"></i>
                                    <h5 class="card-title mt-2">Pet Mais Frequente</h5>
                                    <p class="card-text"><strong>Pet:</strong> <?= htmlspecialchars($row_pet_mais_frequente['pet']) ?></p>
                                    <p class="card-text"><strong>Visitas:</strong> <?= htmlspecialchars($row_pet_mais_frequente['visitas']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-around mb-4">
                        <button class="btn btn-circle btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVisitas">
                            <i class="fa-solid fa-users-viewfinder text-white"></i>
                        </button>
                        <button class="btn btn-circle btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRacas">
                            <i class="fa-solid fa-paw text-white"></i>
                        </button>
                        <button class="btn btn-circle btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseServicos">
                            <i class="fa-solid fa-hand-holding-dollar text-white"></i>
                        </button>
                    </div>

                    <div class="accordion" id="accordionExample">
                        <!-- Visitas por Dono -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingVisitas">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVisitas">
                                    <i class="fa-solid fa-users-viewfinder"></i> Visitas por Dono
                                </button>
                            </h2>
                            <div id="collapseVisitas" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row row-cols-1 row-cols-md-2 g-4">
                                        <?php while ($row = $stmt_visitas_dono->fetch(PDO::FETCH_ASSOC)) : ?>
                                            <div class="col">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title"><?= htmlspecialchars($row['dono']) ?></h5>
                                                        <p class="card-text"><strong>Visitas:</strong> <?= htmlspecialchars($row['visitas']) ?></p>
                                                        <p class="card-text"><strong>Nome do Pet:</strong> <?= htmlspecialchars($row['nomePet']) ?></p>
                                                        <p class="card-text"><strong>Nome do Tutor:</strong> <?= htmlspecialchars($row['dono']) ?></p>
                                                        <p class="card-text"><strong>Contato do Tutor:</strong> <?= htmlspecialchars($row['contatoTutor']) ?></p>
                                                        <p class="card-text"><strong>Serviço:</strong> <?= htmlspecialchars($row['servicospet']) ?></p>
                                                        <p class="card-text"><strong>Observações:</strong> <?= htmlspecialchars($row['observacoes']) ?></p>
                                                        <p class="card-text"><strong>Data da Visita:</strong> <?= htmlspecialchars($row['dataVisita']) ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Raças Mais Frequentes -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingRacas">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRacas">
                                    <i class="fa-solid fa-paw"></i> Raças Mais Frequentes
                                </button>
                            </h2>
                            <div id="collapseRacas" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Raça</th>
                                                <th>Frequência</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $stmt_racas_frequentes->fetch(PDO::FETCH_ASSOC)) : ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['raca']) ?></td>
                                                    <td><?= htmlspecialchars($row['frequencia']) ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Serviços Mais Solicitados -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingServicos">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseServicos">
                                    <i class="fa-solid fa-hand-holding-dollar"></i> Serviços Mais Solicitados
                                </button>
                            </h2>
                            <div id="collapseServicos" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Serviço</th>
                                                <th>Frequência</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $stmt_servicos_solicitados->fetch(PDO::FETCH_ASSOC)) : ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['servico']) ?></td>
                                                    <td><?= htmlspecialchars($row['frequencia']) ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Fim do accordion -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/5fe78ee910.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-qLL9TLZS4MuW34xtMy0fQpQkCPMRC33Xji0KSm8UGcx2f2uOaayDquNk8eQ1A5ai" crossorigin="anonymous"></script>

    <?php
        include_once 'php/footer_index.php';
    ?>
</body>

</html>
