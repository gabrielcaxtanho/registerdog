<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Dashboard</title>
    <link rel="icon" href="./img/logopets1.png" type="image">
    <style>
        .chart-container {
            width: 50%;
        }

        #chartPie,
        #chartPieServico {
            width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <?php include_once 'php/header_index.php'; ?>
    <?php
    require './bd/conexao.php';

    $nomePet = isset($_GET['nomePet']) ? $_GET['nomePet'] : '';

    // Consultas para dados estatísticos
    $sql_racas_frequentes = "SELECT r.nome AS raca, COUNT(f.id) AS frequencia
FROM frequencia f
JOIN pets p ON f.idPet = p.id
JOIN racas r ON p.id = r.idpet
GROUP BY r.nome
ORDER BY frequencia DESC";
    $result_racas_frequentes = $conn->query($sql_racas_frequentes);

    if (!$result_racas_frequentes) {
        die('Erro ao consultar raças: ' . $conn->error);
    }

    $sql_servicos_solicitados = "SELECT s.descricao AS servico, COUNT(f.id) AS frequencia
FROM frequencia f
JOIN servico s ON f.idServico = s.id
GROUP BY s.descricao
ORDER BY frequencia DESC";
    $result_servicos_solicitados = $conn->query($sql_servicos_solicitados);

    if (!$result_servicos_solicitados) {
        die('Erro ao consultar serviços: ' . $conn->error);
    }

    // Consultas para pet e dono mais frequentes
    $sql_pet_mais_frequente = "SELECT p.nome AS pet, COUNT(f.id) AS visitas
FROM frequencia f
JOIN pets p ON f.idPet = p.id
GROUP BY p.nome
ORDER BY visitas DESC
LIMIT 1";
    $result_pet_mais_frequente = $conn->query($sql_pet_mais_frequente);
    $row_pet_mais_frequente = $result_pet_mais_frequente ? $result_pet_mais_frequente->fetch_assoc() : null;

    $sql_dono_mais_frequente = "SELECT d.nome AS dono, COUNT(f.id) AS visitas
FROM frequencia f
JOIN pets p ON f.idPet = p.id
JOIN donos d ON p.idDono = d.id
GROUP BY d.nome
ORDER BY visitas DESC
LIMIT 1";
    $result_dono_mais_frequente = $conn->query($sql_dono_mais_frequente);
    $row_dono_mais_frequente = $result_dono_mais_frequente ? $result_dono_mais_frequente->fetch_assoc() : null;


    if (!$result_servicos_solicitados) {
        die('Erro ao consultar serviços: ' . $conn->error);
    }

    // Gera dados JSON para gráficos
    function gerarCoresAleatorias($quantidade)
    {
        $cores = [];
        for ($i = 0; $i < $quantidade; $i++) {
            $cores[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }
        return $cores;
    }

    $servicos = [];
    while ($row_servico = $result_servicos_solicitados->fetch_assoc()) {
        $servicos[] = $row_servico;
    }

    $data_servicos = json_encode([
        'labels' => array_column($servicos, 'servico'),
        'data' => array_column($servicos, 'frequencia'),
        'colors' => gerarCoresAleatorias(count($servicos))
    ]);
    ?>



    <div class="container-fluid" style="margin-top: 5em;">
        <div class="row" style="display: flex; justify-content: center;">
            <!-- Seção de estatísticas com cartões -->
            <div class="col-md-6">
                <div class="card shadow rounded p-4 h-100" style="border-top: #2e8a97 7px solid;">
                    <h5 class="text-ciano-agiliza"><i class="fa-solid fa-ranking-star"></i> Ranking dos mais frequentes</h5>
                    <span class="text-muted">pet e dono</span>

                    <div class="row my-6">
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
                </div>
            </div>

            <!-- Gráficos -->
            <div class="col-md-4">
                <div class="chart-container">
                    <div class="card shadow rounded p-4 h-100">
                        <h5 class="text-ciano-agiliza">Estatísticas</h5>

                        <!-- Gráfico de pizza para raças mais frequentes -->
                         <!-- <div class="card mb-3">
                            <div class="card-body text-center">
                                <h5 class="card-title">Raças mais frequentes</h5>
                                <canvas id="chartPie"></canvas>
                            </div>
                        </div>  -->


                        <!-- Gráfico de pizza para serviços mais solicitados -->
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Serviços mais solicitados</h5>
                                <canvas id="chartPieServico"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dados para o gráfico de serviços mais solicitados
            const ctxPieServico = document.getElementById('chartPieServico').getContext('2d');
            const dataServicos = <?php echo $data_servicos; ?>;

            console.log('Dados do gráfico de serviços:', dataServicos);

            if (dataServicos.labels.length > 0) {
                new Chart(ctxPieServico, {
                    type: 'pie',
                    data: {
                        labels: dataServicos.labels,
                        datasets: [{
                            data: dataServicos.data,
                            backgroundColor: dataServicos.colors,
                            borderColor: '#ffffff',
                            borderWidth: 1
                        }]
                    }
                });
            } else {
                console.error('Nenhum dado para o gráfico de serviços.');
            }
        });
    </script>

</body>