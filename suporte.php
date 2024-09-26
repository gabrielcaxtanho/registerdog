<?php
session_start(); 

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

    <body>
        <?php include_once 'php/header_index.php'; ?>

        <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="text-center" id="font">SUPORTE REGISTERPET</h2>
            <hr class="border-white">
            <div class="shadow rounded p-4 mb-4 text-center" style="border-top: #2e8a97 7px solid;">
                <p>Para receber suporte, preencha o formulário clicando no botão abaixo e detalhe os problemas</p>
                <p>Para que uma equipe de suporte possa entrar em contato</p>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSdEHMlDspNFkQFvDJX9DSupMNYP62krAAcFGW4VoJx-KGJcng/viewform?usp=sf_link" class="btn btn-primary  ">Acessar Formulário de Suporte</a>
            </div>
        </div>
    </div>
</div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
        <?php include_once 'php/footer_index.php'; ?>
    </body>

    </html>



    