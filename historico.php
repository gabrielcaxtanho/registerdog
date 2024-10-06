<?php
session_start();
if (isset($_SESSION["userEmail"])) {
    require_once(__DIR__ . "/includes/functions.inc.php");
    require_once(__DIR__ . "/bd/conexao.php"); // Conexão com o banco de dados

    // Verifica se foi feita uma pesquisa
    $nomePet = isset($_GET['nomePet']) ? $_GET['nomePet'] : '';

    // Consulta ao banco de dados para buscar os pets
    if (!empty($nomePet)) {
        // Se foi feita uma pesquisa, filtra pelo nome do pet
        $sql = "SELECT pets.id AS petId, pets.nome AS nomePet, racas.nome AS raca, donos.nome AS nomeTutor, donos.telefone AS contatoTutor, pets.data AS dataVisita, pets.hora AS hora, pets.observacao AS observacoes
                FROM pets
                JOIN racas ON pets.idRaca = racas.id
                JOIN donos ON pets.dono = donos.id
                WHERE pets.nome LIKE ?";
        $stmt = $conn->prepare($sql);
        $nomePetParam = '%' . $nomePet . '%';
        $stmt->bind_param('s', $nomePetParam);
    } else {
        // Se não foi feita uma pesquisa, mostra todos os pets
        $sql = "SELECT pets.id AS petId, pets.nome AS nomePet, racas.nome AS raca, donos.nome AS nomeTutor, donos.telefone AS contatoTutor, pets.data AS dataVisita, pets.hora AS hora, pets.observacao AS observacoes
                FROM pets
                JOIN racas ON pets.idRaca = racas.id
                JOIN donos ON pets.dono = donos.id";
        $stmt = $conn->prepare($sql);
    }

    // Executa a query
    $stmt->execute();
    $result = $stmt->get_result();
    $results = $result->fetch_all(MYSQLI_ASSOC);
?>

<head>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <?php include_once 'php/header_index.php'; ?>
    <main class="font">
        <div class="container-fluid py-4">
            <div class="row d-flex justify-content-center">
                <div class="col-sm-10">
                    <div class="row d-flex justify-content-center">
                        <div class="col-sm d-flex justify-content-start" style="flex-direction: column;">
                            <h5 class="text-muted"><b>Log de Atividades</b></h5>
                            <small class="text-muted">Histórico de Pets</small>
                        </div>
                    </div>
                    <hr>

                    <div class="container my-4">
                        <div class="row justify-content-end">
                            <div class="col-md-3">
                                <form id="pesquisa" method="get" action="">
                                    <div class="mb-3">
                                        <label for="nomePet" class="form-label">Pesquisar</label>
                                        <input type="text" class="form-control" id="nomePet" name="nomePet" value="<?= htmlspecialchars($nomePet) ?>" placeholder="Digite o nome do pet">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-30" id="submit">Buscar</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($results)): ?>
                    <div class="content-panel" style="overflow-x: scroll;">
                        <table id="table" class="table table-striped table-advance table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Responsável</th>
                                    <th>Pet</th>
                                    <th>Raça</th>
                                    <th>Data/Hora</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['petId']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nomeTutor']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nomePet']); ?></td>
                                    <td><?php echo htmlspecialchars($row['raca']); ?></td>
                                    <td>
                                        <?php
                                        $dataVisita = new DateTime($row['dataVisita']);
                                        echo $dataVisita->format('d/m/Y') . ' ás ' . htmlspecialchars($row['hora']);
                                        ?>
                                    </td>
                                    <td><a href="edit.php?id=<?= $row['petId'] ?>" class="btn btn-primary">Editar</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-center">Nenhum pet encontrado.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
    <?php include_once 'php/footer_index.php'; ?>
</body>

</html>

<?php
    $stmt->close();
    $conn->close();
} else {
    header("location: login.php");
    exit();
}
?>
