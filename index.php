<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='css/style.css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <header>
        <nav class="navbar bg-body-tertiary" id="nav">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <i class="fa-solid fa-bars" style="color: #ffffff; font-size: 178%;"></i>
                </button>
                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Painel Controle Pets</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a style="display: flex; align-items: center;justify-content: space-evenly; font-size: 25px; font-family: fantasy; color: cadetblue;" class="nav-link active" aria-current="page" href="./cadastrado.php"><i class="fa-solid fa-address-book" style="font-size: 200%;"></i>pets cadastrados</a>
                            </li>
                        </ul>
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a style="display: flex; align-items: center;justify-content: space-evenly; font-size: 25px; font-family: fantasy; color: cadetblue;" class="nav-link active" aria-current="page" href="./cadastro.php"><i class="fa-solid fa-user-plus fa-2xl" style="font-size: 174%;"></i>cadastre um pet</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <main class="header">
                    <h1 class="header__texto"><i class="fa-solid fa-paw fa-2xl" style="color: #dedede; margin: 22px;"></i>PET REGISTER</h1>
                    <h3 class="header__texto">TODOS SEUS REGISTROS EM UM SO LUGAR</h3>
                </main>
            </div>
        </nav>
    </header>

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
<main class="rodape">
<section>
    <div class="row">
        <!-- Card do Dono Mais Frequente -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <i class="fa-solid fa-crown" style="color: #FFD43B; font-size: 2em;"></i>
                    <h5 class="card-title"  style="padding-top: 0.5em;">Dono Mais Frequente</h5>
                    <p class="card-text"><strong>Dono:</strong> <?= htmlspecialchars($row_dono_mais_frequente['dono']) ?></p>
                    <p class="card-text"><strong>Visitas:</strong> <?= htmlspecialchars($row_dono_mais_frequente['visitas']) ?></p>
                </div>
            </div>
        </div>
        <!-- Card do Pet Mais Frequente -->
        <div class="col-md-4">
            <div class="card mb-3">
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

<section class="container mt-5" id="rodape">
        <div class="accordion" id="accordionExample">

            <!-- Visitas por Dono -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingVisitas">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVisitas" aria-expanded="true" aria-controls="collapseVisitas">
                        Visitas por Dono
                    </button>
                </h2>
                <div id="collapseVisitas" class="accordion-collapse collapse show" aria-labelledby="headingVisitas" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row row-cols-md-3 g-3">
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
                        Raças Mais Frequentes
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
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseServicos" aria-expanded="false" aria-controls="collapseServicos">
                        Serviços Mais Solicitados
                    </button>
                </h2>
                <div id="collapseServicos" class="accordion-collapse collapse" aria-labelledby="headingServicos" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row row-cols-1 row-cols-md-2 g-4">
                            <?php while ($row = $stmt_servicos_solicitados->fetch(PDO::FETCH_ASSOC)) : ?>
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($row['servico']) ?></h5>
                                            <p class="card-text"><strong>Frequência:</strong> <?= htmlspecialchars($row['frequencia']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
</section>
</main>
<script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
</body>

</html>
