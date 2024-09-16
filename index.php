<?php

if (isset($_SESSION["userEmail"])) {
    require_once("includes/functions.inc.php");
?>

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
                                    <p class="card-text"><strong>Dono:</strong>
                                        <? if ($row_dono_mais_frequente) {
                                            echo 'Dono: ' . $row_dono_mais_frequente['dono'];
                                        } else {
                                            echo 'Dono: Nenhum dado encontrado';
                                        }
                                        ?>
                                    </p>
                                    <p class="card-text"><strong>Visitas:</strong>
                                        <? if ($row_dono_mais_frequente) {

                                            echo 'Visitas: ' . $row_dono_mais_frequente['visitas'];
                                        } else {
                                            echo 'Visitas: Nenhum dado encontrado';
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
                                        <? if ($row_pet_mais_frequente) {

                                            echo 'pets: ' . $row_pet_mais_frequente['pets'];
                                        } else {
                                            echo 'pets: Nenhum dado encontrado';
                                        } ?>
                                    </p>
                                    <p class="card-text"><strong>Visitas:</strong>
                                        <? if ($row_pet_mais_frequente) {

                                            echo 'visitas: ' . $row_pet_mais_frequente['visitas'];
                                        } else {
                                            echo 'visitas: Nenhum dado encontrado';
                                        } ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h5 class="text-ciano-agiliza" style="margin-top:2em"><i class="fa-solid fa-chart-line"></i> Frequência de atendimentos!</h5>
                        <span class="text-muted">Atendimentos mais solicitados</span>

                        <?php
                        // Total de serviços solicitados para calcular as porcentagens
                        $total_servicos_solicitados = 0;
                        while ($row = $result_servicos_solicitados->fetch_assoc()) {
                            $total_servicos_solicitados += $row['frequencia'];
                        }

                        // Exibe as barras de progresso com base nos dados
                        $result_servicos_solicitados->data_seek(0); // Resetar o cursor para a primeira linha

                        while ($row = $result_servicos_solicitados->fetch_assoc()) {
                            $servico = $row['servico'];
                            $frequencia = $row['frequencia'];

                            // Calcula a porcentagem da frequência em relação ao total
                            $porcentagem = ($total_servicos_solicitados > 0) ? ($frequencia / $total_servicos_solicitados) * 100 : 0;

                            // Escolhe a cor da barra dependendo do serviço
                            $bar_color = '';
                            switch (strtolower($servico)) {
                                case 'banho':
                                    $bar_color = 'bg-warning';
                                    break;
                                case 'tosa':
                                    $bar_color = 'bg-success';
                                    break;
                                case 'banho e tosa':
                                    $bar_color = 'bg-info';
                                    break;
                                default:
                                    $bar_color = 'bg-secondary'; 
                                    break;
                            }

                            // Exibe a barra de progresso
                            echo '<div class="progress" style="height: 5%; font-size: 1.1em; margin-bottom:0.1em; margin-top:2em" role="progressbar" aria-valuenow="' . $porcentagem . '" aria-valuemin="0" aria-valuemax="100">';
                            echo '<div class="progress-bar progress-bar-striped progress-bar-animated ' . $bar_color . '" style="width: ' . $porcentagem . '%">' . $servico . '</div>';
                            echo '</div>';
                        }
                        ?>

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
<?php
} else {
    header("location: login.php");
    exit();
}

?>
