<?php
session_start();
if (isset($_SESSION["userEmail"])) {
    include_once './bd/conexao.php';

    // Verifica se o parâmetro id foi enviado via GET
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    // Verifica se o ID é um número
    if (!is_numeric($id)) {
        die('ID inválido.');
    }

    // Consulta SQL para buscar informações do pet com base no ID
    $sql_select_pet = "
        SELECT
            p.nome AS nomePet,
            p.idRaca, 
            p.dono,
            p.data,
            s.descricao AS servico,
            p.observacao
        FROM
            pets p
        LEFT JOIN
            frequencia v ON p.id = v.idPet
        LEFT JOIN
            servico s ON v.idServico = s.idServico
        WHERE
            p.id = ?
    ";

    // Prepara a consulta
    $stmt_select_pet = mysqli_prepare($conn, $sql_select_pet);

    // Verifica se a preparação da consulta foi bem-sucedida
    if ($stmt_select_pet === false) {
        die('Erro na preparação da consulta: ' . mysqli_error($conn));
    }

    // Substitui o parâmetro ? pelo valor recebido via GET
    mysqli_stmt_bind_param($stmt_select_pet, 'i', $id);

    // Executa a consulta
    if (!mysqli_stmt_execute($stmt_select_pet)) {
        die('Erro na execução da consulta: ' . mysqli_stmt_error($stmt_select_pet));
    }

    // Recupera os dados do pet
    $result = mysqli_stmt_get_result($stmt_select_pet);

    // Verifica se encontrou o pet com o ID especificado
    if (mysqli_num_rows($result) == 0) {
        die('Pet não encontrado');
    }

    // Recupera os dados do pet
    $pet = mysqli_fetch_assoc($result);

    // Consulta para buscar todas as raças disponíveis
    $sql_racas = "SELECT id, nome FROM racas";
    $result_racas = mysqli_query($conn, $sql_racas);
    if ($result_racas === false) {
        die('Erro ao buscar raças: ' . mysqli_error($conn));
    }
    $racas = mysqli_fetch_all($result_racas, MYSQLI_ASSOC);

    // Consulta para buscar todos os donos disponíveis
    $sql_donos = "SELECT id, nome FROM donos";
    $result_donos = mysqli_query($conn, $sql_donos);
    if ($result_donos === false) {
        die('Erro ao buscar donos: ' . mysqli_error($conn));
    }
    $donos = mysqli_fetch_all($result_donos, MYSQLI_ASSOC);
    
    // Consulta para buscar todos os serviços disponíveis
    $sql_servicos = "SELECT idServico, descricao FROM servico";
    $result_servicos = mysqli_query($conn, $sql_servicos);
    if ($result_servicos === false) {
        die('Erro ao buscar serviços: ' . mysqli_error($conn));
    }
    $servicos = mysqli_fetch_all($result_servicos, MYSQLI_ASSOC);
?>
    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="css/petcadastrado.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Informações do Pet</title>
        <link rel="icon" href="img/logopets1.png" type="image">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <style>
        p {
            font-weight: 700;
            font-family: cursive;
            text-align: center;
        }
    </style>

    <body>
        <?php include_once 'php/header_index.php'; ?>

        <div class="">
            <h4 class="text-center"><i class="fa-solid fa-paw fa-2xl" style="color: #ffffff; margin: 22px; margin-top:3%"></i> Informações do Pet <strong style="text-transform: uppercase; color: #4b6d89;"><?= htmlspecialchars($pet['nomePet']) ?></strong></h4>
            <div class="row justify-content-evenly"> 
                <div class="col-md-3" style="margin-left: 2%;">
                    <hr class="border-white">
                    <div class="shadow rounded p-4 mb-4" style="border-top: #2e8a97 7px solid;">
                        <div style="display: flex;  justify-content: space-around;">
                            <div class="mb-3">
                                <label class="form-label">Data do Cadastro:</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($pet['data']) ?? 'Não disponível' ?></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Observações:</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($pet['observacao']) ?? 'Sem observações' ?></p>
                            </div>
                        </div>
                        <div style="display: flex;  justify-content: space-around;">
                            <div class="mb-3">
                                <hr style="padding-left: 10px; margin-left: 10px;">
                                <label class="form-label">Nome do Pet:</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($pet['nomePet']) ?></p>
                            </div>

                            <div class="mb-3">
                                <hr>
                                <label class="form-label">Raça:</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars(array_column($racas, 'nome', 'id')[$pet['idRaca']]) ?? 'Não especificada' ?></p>
                            </div>

                            <div class="mb-3">
                                <hr>
                                <label class="form-label">Nome do Tutor:</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars(array_column($donos, 'nome', 'id')[$pet['dono']]) ?? 'Não especificado' ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <hr class="border-white">
                    <div class="shadow rounded p-4 mb-4" style="border-top: #2e8a97 7px solid;">
                        <form id="editForm" method="post" action="includes/addServicos.php">
                            <input type="hidden" name="petId" value="<?= $id ?>">

                            <div class="mb-3">
                                <label class="form-label" style="font-family: cursive; font-size: larger;">Serviços Selecionados:</label>
                                <div class="selected-services" id="selected-services"></div>
                                <input type="hidden" name="servicos" id="servicos-input">
                            </div>

                            <div id="servicos-container">
                                <div class="mb-3">
                                    <label class="form-label" style="font-family: cursive; font-size: larger;">Escolha os Serviços:</label>
                                    <div class="btn-group d-flex flex-wrap" role="group" aria-label="Serviços">
                                        <?php foreach ($servicos as $servico) : ?>
                                            <button type="button" class="btn servico-btn m-1" data-servico-id="<?= $servico['idServico'] ?>" data-servico-descricao="<?= htmlspecialchars($servico['descricao']) ?>">
                                                <i class="fa-solid fa-plus"></i> <?= htmlspecialchars($servico['descricao']) ?>
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-5">
                                <label for="dataVisita" class="form-label mt-3" style="font-family: cursive; font-size: larger;">Data da Visita:</label>
                                <input type="date" id="dataVisita" name="datasVisita[]" class="form-control">
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    

        <script>
            document.querySelectorAll('.servico-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const servicoId = this.dataset.servicoId;
                    const servicoDescricao = this.dataset.servicoDescricao;

                    // Adiciona o serviço selecionado na lista
                    const selectedServicesDiv = document.getElementById('selected-services');
                    selectedServicesDiv.innerHTML += `<div class="badge bg-secondary me-1 mb-1">${servicoDescricao} <button class="remove-service btn-close btn-close-white" aria-label="Close"></button></div>`;

                    // Adiciona o serviço ao campo oculto
                    const servicosInput = document.getElementById('servicos-input');
                    servicosInput.value += (servicosInput.value ? ',' : '') + servicoId;

                    // Remove o serviço da lista ao clicar no botão de fechar
                    const removeButtons = document.querySelectorAll('.remove-service');
                    removeButtons.forEach(btn => {
                        btn.addEventListener('click', function() {
                            this.parentElement.remove();
                            // Remove o ID do serviço do campo oculto
                            const removedServicoId = this.parentElement.textContent.trim();
                            const servicosArray = servicosInput.value.split(',').filter(id => id !== removedServicoId);
                            servicosInput.value = servicosArray.join(',');
                        });
                    });
                });
            });
        </script>
        
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    </body>
    </html>

<?php
} else {
    header('Location: login.php');
}
?>