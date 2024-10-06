<?php
session_start();
if (isset($_SESSION["userEmail"])) {
    require_once(__DIR__ . "/includes/functions.inc.php");

?>

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
        ?>

        <div class="container-fluid" style="margin: 5em 0;">
            <div class="row" style="display: flex; justify-content: center;">
                <!-- <span class="text-muted text-small"><?php echo $_SESSION["nomePetshop"];  ?></span> -->
                
                <!-- Seção de estatísticas com cartões -->
                <div class="col-md-8">
                    <h5 class="txt-ciano-agiliza" style="font-weight: 400; margin-bottom: 2em;">😄 Olá, <?php echo $_SESSION["userName"];  ?>! Bem-vindo a <b style="font-weight: 700;">sua Dashboard </b></h5>

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
                <div class="col-md-4" style="margin-right: -167px;">
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
    <?php include_once 'php/footer_index.php'; ?>
    </body>
<?php
} else {
    header("location: index.php");
    exit();
}
?>

</html>