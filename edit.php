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
</head>

<body>
<?php
    include_once 'php/header_index.php';
    ?>
    <main class="rodape">
        <h1 class="rodape__texto"><i class="fa-solid fa-paw fa-2xl" style="color: #ffffff; margin: 22px;"></i>EDITAR PET</h1>
        
        <section class="resultado" id="secaoPets">
            <div class="cards-container">
                <div class="card">
                    <form id="editForm" method="post" action="salvar_edicao.php">
                        <input type="hidden" name="petId" value="<?= $id ?>">
                        
                        <label for="nomePet">Nome do Pet:</label><br>
                        <input type="text" id="nomePet" name="nomePet" value="<?= htmlspecialchars($pet['nomePet']) ?>"><br>

                        <label for="raca">Raça:</label><br>
                        <select id="raca" name="raca">
                            <?php foreach ($racas as $raca) : ?>
                                <option value="<?= $raca['id'] ?>" <?= ($raca['id'] == $pet['raca_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($raca['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select><br>

                        <label for="nomeTutor">Nome do Tutor:</label><br>
                        <select id="nomeTutor" name="nomeTutor">
                            <?php foreach ($donos as $dono) : ?>
                                <option value="<?= $dono['id'] ?>" <?= ($dono['id'] == $pet['dono_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dono['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select><br>

                        <label for="contatoTutor">Contato do Tutor:</label><br>
                        <input type="text" id="contatoTutor" name="contatoTutor" value="<?= htmlspecialchars($pet['contatoTutor']) ?>"><br>

                        <label for="servico">Serviço:</label><br>
                        <select id="servico" name="servico">
                            <?php foreach ($servicos as $servico) : ?>
                                <option value="<?= $servico['id'] ?>" <?= ($servico['id'] == $pet['servico_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($servico['descricao']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select><br>

                        <label for="dataVisita">Data da Visita:</label><br>
                        <input type="date" id="dataVisita" name="dataVisita" value="<?= $pet['dataVisita'] ?>"><br>

                        <label for="observacoes">Observações:</label><br>
                        <textarea id="observacoes" name="observacoes"><?= htmlspecialchars($pet['observacoes']) ?></textarea><br>

                        <button type="submit">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
</body>
<?php 
   include_once 'php/footer_index.php';
?>
</html>
