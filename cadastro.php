<?php
session_start();
if (isset($_SESSION["userEmail"])) {
    require_once(__DIR__ . "/includes/functions.inc.php");
    

$mensagem = $_SESSION['mensagem'] ?? null;
unset($_SESSION['mensagem']);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Pets</title>
    <link rel="icon" href="img/logopets1.png" type="image">
    <link rel="stylesheet" href="./css/style.css">
</head>

<style>
    #header {
        background-image: url('./img/img.jpg');
        /*  background-image: url('https://images.hdqwalls.com/download/windows-11-minimal-white-4k-y6-1920x1080.jpg'); */
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        color: white;
        height: 4em;
    }

    .d-flex-de {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: auto;
    }

    .text-secondary {
        color: #6c757d;
        font-size: 18px;
        text-align: center;
    }

    .fa-paw {
        font-size: 24px;
        color: #dc3545;
    }

    .error {
        background-color:#ffd5d5;;
        color: #cd0505;
        ;
        text-align: center;
        padding: 1em;
    }

    .success {
        background-color: #e6ffc2;
        color: #5c9700;
        text-align: center;
        padding: 1em;
    }
</style>

<body>
    <?php include_once 'php/header_index.php'; ?>

    <div class="mt-3" >
        <?php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == "stmtfailed") {
                echo "<p class='error'>Erro ao Adicionar Pet.</p>";
            } else if ($_GET['error'] == "userorexists") {
                echo "<p class='error'>Dono já existente.</p>";
            } else if ($_GET['error'] == "executionfailed") {
                echo "<p class='error'>Erro ao inserir dados. Por favor, tente novamente.</p>";
            }
        } else if (isset($_GET['success'])) {
            if ($_GET['success'] == "usercreated") {
                echo "<p class='success'>Pet criado com sucesso!</p>";
            }
        }
        ?>

    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2 class="text-center" id="font">Cadastrar Pet</h2>
                <hr class="border-white">
                <div class="shadow rounded p-4 mb-4" style="border-top: #2e8a97 7px solid;">
                    <form id="registrationForm" action="includes/addPet.inc.php" method="POST" class="row g-3 needs-validation" novalidate>
                        <!-- Corrigido para o arquivo correto -->
                        <div class="mb-3">
                            <label for="raca" class="form-label"><strong>Raça</strong><b style="color: #ff0000;">*</b></label>
                            <input class="form-control" type="text" id="raca" name="raca" required>
                        </div>
                        <div class="mb-3">
                            <label for="nomePet" class="form-label"><strong>Nome do Pet</strong><b style="color: #ff0000;">*</b></label>
                            <input class="form-control" type="text" id="nomePet" name="nomePet" required>
                        </div>
                        <div class="mb-3">
                            <label for="nomeTutor" class="form-label"><strong>Nome do Tutor</strong><b style="color: #ff0000;">*</b></label>
                            <input class="form-control" type="text" id="nomeTutor" name="nomeTutor" required>
                        </div>
                        <div class="mb-3">
                            <label for="idade" class="form-label"><strong>Idade do Pet</strong><b style="color: #ff0000;">*</b></label>
                            <input class="form-control" type="text" id="idade" name="idade" required>
                        </div>
                        <div class="mb-3">
                            <label for="contatoTutor" class="form-label"><strong>Contato</strong><b style="color: #ff0000;">*</b></label>
                            <input class="form-control" type="text" id="contatoTutor" name="contatoTutor" required>
                        </div>
                        <div class="mb-3">
                            <label for="observacao" class="form-label"><strong>Observações</strong></label>
                            <input class="form-control" type="text" id="observacao" name="observacao">
                        </div>
                        <div class="card-footer bg-transparent text-center">
                            <button class="btn btn-primary" type='submit' name='submit' id='submit'>Cadastrar pet</button>
                            <a href="./index.php" class="text-danger text-decoration-underline">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
    <?php include_once 'php/footer_index.php'; ?>
</body>

</html>
<?php
} else {
    header("location: login.php");
    exit();
}
?>