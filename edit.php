<?php
// Conexão com o banco de dados
require 'conexao.php';

// Verifica se o parâmetro id foi enviado via GET
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Consulta SQL para buscar informações do pet com base no ID
$sql_select_pet = "
    SELECT
        p.nome AS nomePet,
        p.raca_id,
        p.dono_id,
        v.servico_id,
        v.dataVisita,
        s.observacoes
    FROM
        pets p
    LEFT JOIN
        visitaspet v ON p.id = v.pet_id
    LEFT JOIN
        servicos s ON v.servico_id = s.id
    WHERE
        p.id = :id
";

// Prepara a consulta
$stmt_select_pet = $dbh->prepare($sql_select_pet);

// Substitui o parâmetro :id pelo valor recebido via GET
$stmt_select_pet->bindValue(':id', $id);

// Executa a consulta
$stmt_select_pet->execute();

// Verifica se encontrou o pet com o ID especificado
if ($stmt_select_pet->rowCount() == 0) {
    die('Pet não encontrado');
}

// Recupera os dados do pet
$pet = $stmt_select_pet->fetch(PDO::FETCH_ASSOC);

// Consulta para buscar todas as raças disponíveis
$sql_racas = "SELECT id, nome FROM racas";
$stmt_racas = $dbh->query($sql_racas);
$racas = $stmt_racas->fetchAll(PDO::FETCH_ASSOC);

// Consulta para buscar todos os donos disponíveis
$sql_donos = "SELECT id, nome FROM donos";
$stmt_donos = $dbh->query($sql_donos);
$donos = $stmt_donos->fetchAll(PDO::FETCH_ASSOC);

// Consulta para buscar todos os serviços disponíveis
$sql_servicos = "SELECT id, descricao FROM servicos";
$stmt_servicos = $dbh->query($sql_servicos);
$servicos = $stmt_servicos->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Editar Pet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/petcadastrado.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once 'php/header_index.php'; ?>

    <div class="container"> 
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h4 class="text-center"><i class="fa-solid fa-paw fa-2xl" style="color: #ffffff; margin: 22px;"></i> editar informações do Pet <strong style="text-transform: uppercase; color: #4b6d89;"><?= htmlspecialchars($pet['nomePet']) ?></strong></h4>
            <hr class="border-white">
            <div class="shadow rounded p-4 mb-4" style="border-top: #2e8a97 7px solid;">
                <form id="editForm" method="post" action="salvar_edicao.php">
                    <input type="hidden" name="petId" value="<?= $id ?>">

                    <div class="mb-3">
                        <label for="nomePet" class="form-label" style="font-family: cursive; font-size: larger;">Nome do Pet:</label>
                        <input type="text" id="nomePet" name="nomePet" class="form-control" value="<?= htmlspecialchars($pet['nomePet']) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="raca" class="form-label" style="font-family: cursive; font-size: larger;">Raça:</label>
                        <select id="raca" name="raca" class="form-select">
                            <?php foreach ($racas as $raca) : ?>
                                <option value="<?= $raca['id'] ?>" <?= ($raca['id'] == $pet['raca_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($raca['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nomeTutor" class="form-label" style="font-family: cursive; font-size: larger;">Nome do Tutor:</label>
                        <select id="nomeTutor" name="nomeTutor" class="form-select">
                            <?php foreach ($donos as $dono) : ?>
                                <option value="<?= $dono['id'] ?>" <?= ($dono['id'] == $pet['dono_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dono['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="contatoTutor" class="form-label" style="font-family: cursive; font-size: larger;">Contato do Tutor:</label>
                        <input type="text" id="contatoTutor" name="contatoTutor" class="form-control" value="<?= htmlspecialchars($pet['contatoTutor']) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="servico" class="form-label" style="font-family: cursive; font-size: larger;">Serviço:</label>
                        <select id="servico" name="servico" class="form-select">
                            <?php foreach ($servicos as $servico) : ?>
                                <option value="<?= $servico['id'] ?>" <?= ($servico['id'] == $pet['servico_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($servico['descricao']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="dataVisita" class="form-label" style="font-family: cursive; font-size: larger;">Data da Visita:</label>
                        <input type="date" id="dataVisita" name="dataVisita" class="form-control" value="<?= $pet['dataVisita'] ?>">
                    </div>

                    <div class="mb-3">
                        <label for="observacoes" class="form-label" style="font-family: cursive; font-size: larger;">Observações:</label>
                        <textarea id="observacoes" name="observacoes" class="form-control"><?= htmlspecialchars($pet['observacoes']) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include_once 'php/footer_index.php'; ?>
</body>

</html>