<!DOCTYPE html>
<html>

<head>
    <!-- <link rel='stylesheet' href='./css/style.css'> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php
    include_once 'php/header_index.php';
    ?>
    <main class="header">
        <h1 class="header__texto" style="color: #756AB6;"><i class="fa-solid fa-paw fa-2xl" style="color: silver; margin: 22px;"></i>PET REGISTER</h1>
        <h3 class="header__texto " style="color: #E0AED0;">TODOS SEUS REGISTROS EM UM SO LUGAR</h3>
    </main>

    <?php
    // Conexão com o banco de dados
    require 'conexao.php';

    // Consultando Pets Cadastrados
    $nomePet = isset($_GET['nomePet']) ? $_GET['nomePet'] : '';
    $sql_select = "SELECT p.nome AS nomePet, r.nome AS raca, d.nome AS nomeTutor, d.contato AS contatoTutor, s.descricao AS servicospet, s.observacoes, v.dataVisita 
                  FROM pets p 
                  LEFT JOIN racas r ON p.raca_id = r.id 
                  LEFT JOIN donos d ON p.dono_id = d.id 
                  LEFT JOIN visitaspet v ON p.id = v.pet_id 
                  LEFT JOIN servicos s ON v.servico_id = s.id 
                  WHERE p.nome LIKE :nomePet";
    $stmt_select = $dbh->prepare($sql_select);
    $stmt_select->bindValue(':nomePet', "%$nomePet%");
    $stmt_select->execute();

    // Consultando Raças mais Frequentes
    $sql_racas_frequentes = "SELECT r.nome AS raca, COUNT(v.id) AS frequencia
                             FROM visitaspet v
                             JOIN pets p ON v.pet_id = p.id
                             JOIN racas r ON p.raca_id = r.id
                             GROUP BY r.nome
                             ORDER BY frequencia DESC";
    $stmt_racas_frequentes = $dbh->query($sql_racas_frequentes);

    // Consultando Serviços mais Solicitados
    $sql_servicos_solicitados = "SELECT s.descricao AS servico, COUNT(v.id) AS frequencia
                                 FROM visitaspet v
                                 JOIN servicos s ON v.servico_id = s.id
                                 GROUP BY s.descricao
                                 ORDER BY frequencia DESC";
    $stmt_servicos_solicitados = $dbh->query($sql_servicos_solicitados);

    // Consultando Visitas por Dono com detalhes dos pets
    $sql_visitas_dono = "SELECT d.nome AS dono, COUNT(v.id) AS visitas, p.nome AS nomePet, d.contato AS contatoTutor, s.descricao AS servicospet, s.observacoes, v.dataVisita
                         FROM visitaspet v
                         JOIN pets p ON v.pet_id = p.id
                         JOIN donos d ON p.dono_id = d.id
                         JOIN servicos s ON v.servico_id = s.id
                         GROUP BY d.nome, p.nome, d.contato, s.descricao, s.observacoes, v.dataVisita
                         ORDER BY visitas DESC";
    $stmt_visitas_dono = $dbh->query($sql_visitas_dono);

    // Consultando o Pet com mais visitas
    $sql_pet_mais_frequente = "SELECT p.nome AS pet, COUNT(v.id) AS visitas
                               FROM visitaspet v
                               JOIN pets p ON v.pet_id = p.id
                               GROUP BY p.nome
                               ORDER BY visitas DESC
                               LIMIT 1";
    $stmt_pet_mais_frequente = $dbh->query($sql_pet_mais_frequente);
    $row_pet_mais_frequente = $stmt_pet_mais_frequente->fetch(PDO::FETCH_ASSOC);

    // Consultando o Dono com mais visitas
    $sql_dono_mais_frequente = "SELECT d.nome AS dono, COUNT(v.id) AS visitas
                                FROM visitaspet v
                                JOIN pets p ON v.pet_id = p.id
                                JOIN donos d ON p.dono_id = d.id
                                GROUP BY d.nome
                                ORDER BY visitas DESC
                                LIMIT 1";
    $stmt_dono_mais_frequente = $dbh->query($sql_dono_mais_frequente);
    $row_dono_mais_frequente = $stmt_dono_mais_frequente->fetch(PDO::FETCH_ASSOC);

    // Verifica se há erro na execução das consultas
    if (!$stmt_select || !$stmt_racas_frequentes || !$stmt_servicos_solicitados || !$stmt_visitas_dono || !$stmt_pet_mais_frequente || !$stmt_dono_mais_frequente) {
        die('Erro na consulta SQL: ' . $dbh->errorInfo()[2]);
    }
    ?>
    <main class="rodape" style="display: flex; flex-direction: row; justify-content: space-evenly;">


        <section>
            <div style="display: flex; flex-direction: column;">
                <!-- Card do Dono Mais Frequente -->
                <div class="col-md-4">
                    <div class="card mb-3" style="border: solid #00ffb5;">
                        <div class="card-body">
                            <i class="fa-solid fa-crown" style="color: #FFD43B; font-size: 2em;"></i>
                            <h5 class="card-title" style="padding-top: 0.5em;">Dono Mais Frequente</h5>
                            <p class="card-text"><strong>Dono:</strong> <?= htmlspecialchars($row_dono_mais_frequente['dono']) ?></p>
                            <p class="card-text"><strong>Visitas:</strong> <?= htmlspecialchars($row_dono_mais_frequente['visitas']) ?></p>
                        </div>
                    </div>
                </div>
                <!-- Card do Pet Mais Frequente -->
                <div class="col-md-4">
                    <div class="card mb-3" style="border: solid #00ffb5;">
                        <div class="card-body">
                            <i class="fa-solid fa-crown" style="color: #FFD43B; font-size: 2em;"></i>
                            <h5 class="card-title" style="padding-top: 0.5em;">Pet Mais Frequente</h5>
                            <p class="card-text"><strong>Pet:</strong> <?= htmlspecialchars($row_pet_mais_frequente['pet']) ?></p>
                            <p class="card-text"><strong>Visitas:</strong> <?= htmlspecialchars($row_pet_mais_frequente['visitas']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <img src="./img/family.png" alt="" style="opacity: 0.6;">
        <button style="    background-color: cadetblue; border-radius: 4%; color: white; padding: 1%; border-style: hidden;">click aqui e conheça o petResgate</button>
    </main>
    <aside class="container mt-5" id="rodape" style="display: flex; flex-direction: column; align-items: flex-end;">
        <style>
            .round-button {
                border-radius: 50%;
                width: 50px;
                height: 50px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 1px solid #ccc;
                margin: 5px;
                background-color: #f8f9fa;
            }

            .round-button i {
                font-size: 20px;
            }

            .accordion-button {
                display: none;
                /* Hide the default accordion buttons */
            }
        </style>

        <div class="d-flex justify-content-around mb-3">
            <!-- Botões Redondos -->
            <button class="round-button" style="background-color: cadetblue;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVisitas" aria-expanded="false" aria-controls="collapseVisitas">
                <i style="color: white;" class="fa-solid fa-users-viewfinder"></i>
            </button>
            <button class="round-button" style="background-color: cadetblue;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRacas" aria-expanded="false" aria-controls="collapseRacas">
                <i style=" color: white;" class="fa-solid fa-paw"></i>
            </button>
            <button class="round-button" style="background-color: cadetblue;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseServicos" aria-expanded="true" aria-controls="collapseServicos">
                <i style=" color: white;" class="fa-solid fa-hand-holding-dollar"></i>
            </button>
        </div>

        <div class="accordion" id="accordionExample">
            <!-- Visitas por Dono -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingVisitas">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVisitas" aria-expanded="false" aria-controls="collapseVisitas">
                        <i class="fa-solid fa-users-viewfinder"></i> Visitas por Dono
                    </button>
                </h2>
                <div id="collapseVisitas" class="accordion-collapse collapse" aria-labelledby="headingVisitas" data-bs-parent="#accordionExample">
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
                                            <p class="card-text"><strong>Serviços:</strong> <?= htmlspecialchars($row['servicospet']) ?></p>
                                            <p class="card-text"><strong>Data da Visita:</strong> <?= htmlspecialchars($row['dataVisita']) ?></p>
                                            <p class="card-text"><strong>Observações:</strong> <?= htmlspecialchars($row['observacoes']) ?></p>
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
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRacas" aria-expanded="false" aria-controls="collapseRacas">
                        <i class="fa-solid fa-paw"></i> Raças Mais Frequentes
                    </button>
                </h2>
                <div id="collapseRacas" class="accordion-collapse collapse" aria-labelledby="headingRacas" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row row-cols-1 row-cols-md-2 g-4">
                            <?php while ($row = $stmt_racas_frequentes->fetch(PDO::FETCH_ASSOC)) : ?>
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($row['raca']) ?></h5>
                                            <p class="card-text"><strong>Frequência:</strong> <?= htmlspecialchars($row['frequencia']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serviços Mais Solicitados -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingServicos">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseServicos" aria-expanded="true" aria-controls="collapseServicos">
                        <i class="fa-solid fa-hand-holding-dollar"></i> Serviços Mais Solicitados
                    </button>
                </h2>
                <div id="collapseServicos" class="accordion-collapse collapse show" aria-labelledby="headingServicos" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row row-cols-1 row-cols-md-2 g-4">
                            <?php while ($row = $stmt_servicos_solicitados->fetch(PDO::FETCH_ASSOC)) : ?>
                                <div class="col">
                                    <div class="card" style="margin-bottom: 5%;">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($row['servico']) ?></h5>
                                            <p class="card-text"><strong>Frequência:</strong> <?= htmlspecialchars($row['frequencia']) ?></p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </aside>


</body>
<?php 
   include_once 'php/footer_index.php';
?>

</html>
<script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>