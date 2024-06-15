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
                                <a style="display: flex; align-items: center;justify-content: space-evenly; font-size: 25px; font-family: fantasy; color: cadetblue;"  class="nav-link active" aria-current="page" href="./cadastro.php"><i class="fa-solid fa-user-plus fa-2xl" style="font-size: 174%;"></i>cadastre um pet</a>
                            </li>
                        </ul>
                        <form class="d-flex mt-3" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
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

    // Verifica se há erro na execução da consulta
    if (!$stmt_select) {
        die('Erro na consulta SQL: ' . $dbh->errorInfo()[2]);
    }
    ?>
    <main class="rodape">

        <h2>Registros de Pets</h2>

        <div class="principal__elemento">
            <form id="pesquisa" method="get" action="">
                <label class="nomePet" for="nomePet">Pesquisar</label>
                <input class="inputnomePet" type="text" id="" name="nomePet" value="<?= htmlspecialchars($nomePet) ?>" placeholder="Digite o nome do pet">
                <button type="submit" id="submit">Buscar</button>
            </form>
        </div>

        <section class="resultado" id="secaoPets" style="display: block;">
            <div class="cards-container" id="secaoPets">
                <?php while ($row = $stmt_select->fetch(PDO::FETCH_ASSOC)) : ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($row['nomePet']) ?></h3>
                        <p><strong>Raça:</strong> <?= htmlspecialchars($row['raca']) ?></p>
                        <p><strong>Nome do Tutor:</strong> <?= htmlspecialchars($row['nomeTutor']) ?></p>
                        <p><strong>Contato do Tutor:</strong> <?= htmlspecialchars($row['contatoTutor']) ?></p>
                        <p><strong>Serviços:</strong> <?= htmlspecialchars($row['servicospet']) ?></p>
                        <p><strong>Data da Visita:</strong> <?= htmlspecialchars($row['dataVisita']) ?></p>
                        <p><strong>Observações:</strong> <?= htmlspecialchars($row['observacoes']) ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
</body>

</html>