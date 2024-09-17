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

        #chartPie {
            max-width: 10%;
            height: auto;
        }
    </style>
</head>

<body>
    <?php include_once 'php/header_index.php'; ?>

    <?php
    require './bd/conexao.php';

    $nomePet = isset($_GET['nomePet']) ? $_GET['nomePet'] : '';

    // Consulta para buscar os dados do pet
    $sql_select = "SELECT p.nome AS nomePet, r.nome AS raca, d.nome AS nomeTutor, d.telefone AS contatoTutor, 
           s.descricao AS servicospet, f.observacao, f.dataVisita 
           FROM pets p 
           LEFT JOIN racas r ON p.id = r.idpet 
           LEFT JOIN donos d ON p.idDono = d.id 
           LEFT JOIN frequencia f ON p.id = f.idPet 
           LEFT JOIN servico s ON f.idServico = s.id 
           WHERE p.nome LIKE ?";

    // Prepara a consulta
    $stmt_select = $conn->prepare($sql_select);

    if ($stmt_select === false) {
        // Erro ao preparar a consulta
        die('Erro ao preparar a consulta SQL: ' . $conn->error);
    }

    $param = "%$nomePet%";
    $stmt_select->bind_param("s", $param);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();

    // Verifica se a consulta executou corretamente
    if (!$result_select) {
        die('Erro ao executar a consulta SQL: ' . $stmt_select->error);
    }

    // Outras consultas que não precisam de prepared statements

    // Consulta para raças mais frequentes
    $sql_racas_frequentes = "SELECT r.nome AS raca, COUNT(f.id) AS frequencia
                     FROM frequencia f
                     JOIN pets p ON f.idPet = p.id
                     JOIN racas r ON p.id = r.idpet
                     GROUP BY r.nome
                     ORDER BY frequencia DESC";
    $result_racas_frequentes = $conn->query($sql_racas_frequentes);

    // Consulta para serviços mais solicitados
    $sql_servicos_solicitados = "SELECT s.descricao AS servico, COUNT(f.id) AS frequencia
                         FROM frequencia f
                         JOIN servico s ON f.idServico = s.id
                         GROUP BY s.descricao
                         ORDER BY frequencia DESC";
    $result_servicos_solicitados = $conn->query($sql_servicos_solicitados);

    // Consulta para visitas por dono
    $sql_visitas_dono = "SELECT d.nome AS dono, COUNT(f.id) AS visitas, p.nome AS nomePet, d.telefone AS contatoTutor, 
                 s.descricao AS servicospet, f.observacao, f.dataVisita
                 FROM frequencia f
                 JOIN pets p ON f.idPet = p.id
                 JOIN donos d ON p.idDono = d.id
                 JOIN servico s ON f.idServico = s.id
                 GROUP BY d.nome, p.nome, d.telefone, s.descricao, f.observacao, f.dataVisita
                 ORDER BY visitas DESC";
    $result_visitas_dono = $conn->query($sql_visitas_dono);

    // Consulta para o pet mais frequente
    $sql_pet_mais_frequente = "SELECT p.nome AS pet, COUNT(f.id) AS visitas
                       FROM frequencia f
                       JOIN pets p ON f.idPet = p.id
                       GROUP BY p.nome
                       ORDER BY visitas DESC
                       LIMIT 1";
    $result_pet_mais_frequente = $conn->query($sql_pet_mais_frequente);
    $row_pet_mais_frequente = $result_pet_mais_frequente->fetch_assoc();

    // Consulta para o dono mais frequente
    $sql_dono_mais_frequente = "SELECT d.nome AS dono, COUNT(f.id) AS visitas
                        FROM frequencia f
                        JOIN pets p ON f.idPet = p.id
                        JOIN donos d ON p.idDono = d.id
                        GROUP BY d.nome
                        ORDER BY visitas DESC
                        LIMIT 1";
    $result_dono_mais_frequente = $conn->query($sql_dono_mais_frequente);
    $row_dono_mais_frequente = $result_dono_mais_frequente->fetch_assoc();

    // Verifica erros nas consultas que utilizam `query`
    if (!$result_racas_frequentes || !$result_servicos_solicitados || !$result_visitas_dono || !$result_pet_mais_frequente || !$result_dono_mais_frequente) {
        die('Erro nas consultas SQL: ' . $conn->error);
    }

    // Gera cores aleatórias para o gráfico
    function gerarCoresAleatorias($quantidade) {
        $cores = [];
        for ($i = 0; $i < $quantidade; $i++) {
            $cores[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }
        return $cores;
    }
    ?>

    <div class="container-fluid" style="margin-bottom: 5em;">
        <!-- <div class="row justify-content-between align-items-baseline mb-4"> -->
            <div class="col" style="display: flex; margin-left: 2%; justify-content: space-evenly; align-items: center; margin-top: 2em;">
                <!-- Linha separadora para o título -->
                <hr class="border-white">

                <!-- Card principal -->
                <div class="container-sm">
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
                                        <p class="card-text"><strong>Dono:</strong>
                                            <?php
                                            if ($row_dono_mais_frequente) {
                                                echo $row_dono_mais_frequente['dono'];
                                            } else {
                                                echo 'Nenhum dado encontrado';
                                            }
                                            ?>
                                        </p>
                                        <p class="card-text"><strong>Visitas:</strong>
                                            <?php
                                            if ($row_dono_mais_frequente) {
                                                echo $row_dono_mais_frequente['visitas'];
                                            } else {
                                                echo 'Nenhum dado encontrado';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3 border-primary shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fa-solid fa-crown text-warning" style="font-size: 2em;"></i>
                                        <h5 class="card-title mt-2">Pet Mais Frequente</h5>
                                        <p class="card-text"><strong>Pet:</strong>
                                            <?php
                                            if ($row_pet_mais_frequente) {
                                                echo $row_pet_mais_frequente['pet'];
                                            } else {
                                                echo 'Nenhum dado encontrado';
                                            }
                                            ?>
                                        </p>
                                        <p class="card-text"><strong>Visitas:</strong>
                                            <?php
                                            if ($row_pet_mais_frequente) {
                                                echo $row_pet_mais_frequente['visitas'];
                                            } else {
                                                echo 'Nenhum dado encontrado';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráficos -->
                        <h5 class="text-ciano-agiliza">Estatísticas</h5>

                        <!-- Gráfico de pizza para raças mais frequentes -->
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <h5 class="card-title">Raças Mais Frequentes</h5>
                                <canvas id="chartPie"></canvas>
                            </div>
                        </div>

                        <!-- Gráfico de pizza para serviços mais solicitados -->
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <h5 class="card-title">Serviços Mais Solicitados</h5>
                                <canvas id="chartPieServico"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Função para gerar cores aleatórias
        function gerarCoresAleatorias(quantidade) {
            const cores = [];
            for (let i = 0; i < quantidade; i++) {
                cores.push('#' + Math.floor(Math.random() * 16777215).toString(16));
            }
            return cores;
        }

        // Gráfico de pizza para raças mais frequentes
        const ctxPie = document.getElementById('chartPie').getContext('2d');
        const dataRacas = {
            labels: <?php
                $racas = [];
                while ($row = $result_racas_frequentes->fetch_assoc()) {
                    $racas[] = $row;
                }
                echo json_encode(array_column($racas, 'raca'));
                ?>,
            datasets: [{
                label: 'Raças Mais Frequentes',
                data: <?php echo json_encode(array_column($racas, 'frequencia')); ?>,
                backgroundColor: gerarCoresAleatorias(<?php echo count($racas); ?>),
                borderColor: 'rgba(255, 255, 255, 1)',
                borderWidth: 1
            }]
        };
        new Chart(ctxPie, {
            type: 'pie',
            data: dataRacas
        });

        // Gráfico de pizza para serviços mais solicitados
        const ctxPieServico = document.getElementById('chartPieServico').getContext('2d');
        const dataServicos = {
            labels: <?php
                $servicos = [];
                while ($row = $result_servicos_solicitados->fetch_assoc()) {
                    $servicos[] = $row;
                }
                echo json_encode(array_column($servicos, 'servico'));
                ?>,
            datasets: [{
                label: 'Serviços Mais Solicitados',
                data: <?php echo json_encode(array_column($servicos, 'frequencia')); ?>,
                backgroundColor: gerarCoresAleatorias(<?php echo count($servicos); ?>),
                borderColor: 'rgba(255, 255, 255, 1)',
                borderWidth: 1
            }]
        };
        new Chart(ctxPieServico, {
            type: 'pie',
            data: dataServicos
        });
    </script>

</body>

</html>
