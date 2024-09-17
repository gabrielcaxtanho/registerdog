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
        <title>perfil</title>
        <link rel="icon" href="img/logopets1.png" type="image">
        <link rel="stylesheet" href="./css/style.css">
    </head>

    <body>
        <?php include_once 'php/header_index.php'; ?>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <h2 class="text-center" id="font">Meu Perfil</h2>
                    <hr class="border-white">
                    <div class="shadow rounded p-4 mb-4" style="border-top: #2e8a97 7px solid;">
                        <form id="includes/addPet.ini.php" method="post" action="includes/addPet.ini.php">
                            <!-- Corrigido para o arquivo correto -->
                            <div class="mb-3">
                                <label for="raca" class="form-label"><strong>Nome e Sobrenome</strong><b style="color: #ff0000;">*</b></label>
                                <input class="form-control" type="text" id="raca" name="raca" required>
                            </div>
                            <div class="mb-3">
                                <label for="nomePet" class="form-label"><strong>Nome fantasia</strong><b style="color: #ff0000;">*</b></label>
                                <input class="form-control" type="text" id="nomePet" name="nomePet" required>
                            </div>
                            <div class="mb-3">
                                <label for="nomeTutor" class="form-label"><strong>Cidade</strong><b style="color: #ff0000;">*</b></label>
                                <input class="form-control" type="text" id="nomeTutor" name="nomeTutor" required>
                            </div>
                            <div class="mb-3">
                                <label for="contatoTutor" class="form-label"><strong>Telefone</strong><b style="color: #ff0000;">*</b></label>
                                <input class="form-control" type="text" id="contatoTutor" name="contatoTutor" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><strong>Estado</strong><b style="color: #ff0000;">*</b></label>
                                <!-- <div class="form-check">
                                    <input class="form-check-input" type="radio" name="descricao" value="Banho" required>
                                    <label class="form-check-label" for="banho">Banho</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="descricao" value="Tosa">
                                    <label class="form-check-label" for="tosa">Tosa</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="descricao" value="Banho e Tosa">
                                    <label class="form-check-label" for="banhoTosa">Banho e Tosa</label>
                                </div> -->
                            </div>
                            <div class="mb-3">
                                <label for="observacao" class="form-label"><strong>Observações</strong></label>
                                <input class="form-control" type="text" id="observacao" name="observacao">
                            </div>
                            <div class="card-footer bg-transparent text-center">
                                <button type="submit" class="btn btn-primary w-100 mb-3">atualizar perfil</button>
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