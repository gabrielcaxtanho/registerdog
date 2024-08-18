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

    <div class="container-fluid">
        <div class="row justify-content-between align-items-baseline mb-4">
<!-- 
                   <div class="col-md-1"></div>  -->

            <!-- Coluna das Informações de Cachorros e Donos -->
            <div class="col-md-8" style="margin-left: 2%;">
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
                                        <!-- PHP loop para listar as visitas -->
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
                                            <!-- PHP loop para listar as raças mais frequentes -->
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
                                            <!-- PHP loop para listar os serviços mais solicitados -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Fim do accordion -->
                </div>
            </div>
            <!-- Coluna da Imagem -->
            <div class="col-md-2 text-center">
                <small style="font-weight: 700; border-bottom: groove; font-size: large;"> <i class="fa-regular fa-hand-point-down fa-bounce" style="font-size: x-large"></i> Conheça também o site</small>
                <h4 style="margin-bottom: 6%;"></h4>
                <a href="https://gabrielcaxtanho.github.io/betoplus/" target="_blank" class="image-link">
                    <img src="https://private-user-images.githubusercontent.com/96641560/339418336-d8ac7024-9443-45d8-a94e-908a93d01650.png?jwt=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJnaXRodWIuY29tIiwiYXVkIjoicmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSIsImtleSI6ImtleTUiLCJleHAiOjE3MjM5MzYyODQsIm5iZiI6MTcyMzkzNTk4NCwicGF0aCI6Ii85NjY0MTU2MC8zMzk0MTgzMzYtZDhhYzcwMjQtOTQ0My00NWQ4LWE5NGUtOTA4YTkzZDAxNjUwLnBuZz9YLUFtei1BbGdvcml0aG09QVdTNC1ITUFDLVNIQTI1NiZYLUFtei1DcmVkZW50aWFsPUFLSUFWQ09EWUxTQTUzUFFLNFpBJTJGMjAyNDA4MTclMkZ1cy1lYXN0LTElMkZzMyUyRmF3czRfcmVxdWVzdCZYLUFtei1EYXRlPTIwMjQwODE3VDIzMDYyNFomWC1BbXotRXhwaXJlcz0zMDAmWC1BbXotU2lnbmF0dXJlPTFmYTAzMTM2MzRhYmJkMjA3YTRmMWFiNDViNzZiODUyOWU1ZDMwMDM2ZmEzOWIyMjQwMjdkZGMzZWRiYmVhOGQmWC1BbXotU2lnbmVkSGVhZGVycz1ob3N0JmFjdG9yX2lkPTAma2V5X2lkPTAmcmVwb19pZD0wIn0.OJkDXWE72UI-mPse5cfahORgi6tzzsEFx5wSDre3hsM" alt="cachorro da raça pug com um brasão, para divulgar um site de rastreio animal. Clique para conhecer o site" class="image">
                    <span class="overlay-text">Clique para conhecer o site</span>
                </a>
            </div>
        </div>
    </div>

    <style>
        .image-link {
            position: relative;
            display: inline-block;
            width: 100%;
            max-width: 250px;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .image-link:hover {
            transform: scale(1.05);
        }

        .image {
            width: 90%;
            height: auto;
            border-radius: 15px;
            display: block;
            transition: opacity 0.3s ease;
        }

        .image-link:hover .image {
            opacity: 0.3;
        }

        .overlay-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .image-link:hover .overlay-text {
            opacity: 1;
        }
    </style>


    <script src="https://kit.fontawesome.com/5fe78ee910.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-qLL9TLZS4MuW34xtMy0fQpQkCPMRC33Xji0KSm8UGcx2f2uOaayDquNk8eQ1A5ai" crossorigin="anonymous"></script>

    <?php
    include_once 'php/footer_index.php';
    ?>
</body>

</html>