<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>dashboard</title>
    <link rel="icon" href="./img/logopets1.png" type="image">
    <style>
        /* Estilos para barras de progresso verticais */
        .progress-vertical {
            position: relative;
            width: 30px;
            /* Largura da barra de progresso */
            height: 200px;
            /* Altura da barra de progresso */
            display: flex;
            align-items: flex-end;
            margin-right: 20px;
            /* Espaçamento entre barras */
        }

        .progress-bar {
            width: 100%;
            /* Largura da barra de progresso */
            transition: height 0.6s ease;
            /* Transição suave para altura */
        }

        .progress-bar-striped {
            background-image: linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        }

        .progress-bar-animated {
            animation: progress-bar-stripes 1s linear infinite;
        }

        @keyframes progress-bar-stripes {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 100% 0;
            }
        }
    </style>

</head>

<body>
    <?php include_once 'php/header_index.php'; ?>

    <?php
    require './bd/conexao.php';


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

    <div class="container">
        <div class="row justify-content-between align-items-baseline mb-4">
            <div class="col" style="margin-left: 2%;">
                <!-- Linha separadora para o título -->
                <hr class="border-white">

                <!-- Card principal -->
                <div class="card shadow rounded p-4 h-100" style="border-top: #2e8a97 7px solid;">
                    <h5 class="text-ciano-agiliza"><i class="fa-solid fa-ranking-star"></i> Ranking dos mais frequentes</h5>
                    <span class="text-muted">pet e dono</span>

                    <!-- Seção de estatísticas com cartões -->
                    <div class="row my-4">
                        <div class="col-md-6">
                            <div class="card mb-3 border-primary shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fa-solid fa-crown text-warning" style="font-size: 2em;"></i>
                                    <h5 class="card-title mt-2">Dono Mais Frequente</h5>
                                    <p class="card-text"><strong>Dono:</strong> <?= htmlspecialchars($row_dono_mais_frequente['dono']) ?></p>
                                    <p class="card-text"><strong>Visitas:</strong> <?= htmlspecialchars($row_dono_mais_frequente['visitas']) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3 border-primary shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fa-solid fa-crown text-warning" style="font-size: 2em;"></i>
                                    <h5 class="card-title mt-2">Pet Mais Frequente</h5>
                                    <p class="card-text"><strong>Pet:</strong> <?= htmlspecialchars($row_pet_mais_frequente['pet']) ?></p>
                                    <p class="card-text"><strong>Visitas:</strong> <?= htmlspecialchars($row_pet_mais_frequente['visitas']) ?></p>
                                </div>
                            </div>
                        </div>

                        <h5 class="text-ciano-agiliza" style="margin-top:2em"><i class="fa-solid fa-chart-line"></i> Frequência de atendimentos!</h5>
                        <span class="text-muted">atendimentos mais solicitados</span>

                        <div class="progress" style="height: 5%; font-size: 1.1em; margin-bottom:0.1em; margin-top:2em" role="progressbar" aria-label="Animated Success striped example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 25%">tosa</div>
                        </div>
                        <div class="progress" style="height: 5%; font-size: 1.1em; margin-bottom:0.1em" role="progressbar" aria-label="Animated Info striped example" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" style="width: 50%">banho e tosa</div>
                        </div>
                        <div class="progress" style="height: 5%; font-size: 1.1em; margin-bottom:0.1em" role="progressbar" aria-label="Animated Warning striped example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" style="width: 75%">banho</div>
                        </div>
                        <!-- <div class="progress" style="height: 5%; font-size: 1.1em; margin-bottom:0.1em" role="progressbar" aria-label="Animated Danger striped example" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" style="width: 100%">banho</div>
                        </div> -->
                    </div>
                </div>
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