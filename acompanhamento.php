<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Acompanhamento</title>
    <link rel="icon" href="img/logopets1.png" type="image">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/petcadastrado.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once 'php/header_index.php'; ?>

    <div class="main-content">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="https://i.pinimg.com/564x/66/a9/81/66a9818af810fdf31981405418e0ea43.jpg" width="90" class="rounded me-2" alt="...">
                <strong class="me-auto">Agora acompanhar eu pet no banho ficou mais fácil</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>


    <div class="container my-5">
        <div class="row justify-content-center">

            <div class="text-center" style="padding-bottom: 40px; display: flex; flex-direction: row; justify-content: center; align-items: center; gap: 5px;">
                <h4>Acompanhe aqui o andamento do seu Pet<h4><i class="fa-solid fa-cat"></i>
            </div>

            <div class="col-md-15">
                <!-- Primeiro Card -->
                <div class="mb-4 ">
                    <div class="card-body text-center">
                        <div class="card shadow rounded p-4 h-100" style="border-top: #D3D04F 7px solid;">
                            <div class="d-flex justify-content-center align-items-center">
                                <h5 class="card-title mb-0">BANHO</h5>
                                <i class="fa-solid fa-shower ms-2"></i>
                            </div>
                            <p class="card-text m-2">STATUS: CONCLUIDO</p>
                        </div>
                    </div>
                </div>

                <!-- Segundo Card -->
                <div class="mb-4 ">
                    <div class="card-body text-center">
                        <div class="card shadow rounded p-4 h-100" style="border-top: #8EAC50 7px solid;">
                            <div class="d-flex justify-content-center align-items-center">
                                <h5 class="card-title mb-0">TOSA</h5>
                                <i class="fa-solid fa-scissors ms-2"></i>
                            </div>
                            <p class="card-text m-2">STATUS: EM ANDAMENTO</p>
                        </div>
                    </div>
                </div>


                <!-- Terceiro Card -->
                <div class="mb-4 ">
                    <div class="card-body text-center">
                        <div class="card shadow rounded p-4 h-100" style="border-top: #17594A 7px solid;">
                            <div class="d-flex justify-content-center align-items-center">
                                <h5 class="card-title mb-0">SECAGEM</h5>
                                <i class="fa-solid fa-temperature-three-quarters ms-2"></i>
                            </div>
                            <p class="card-text m-2">STATUS: AGUARDANDO CONCLUSAO DE OUTRAS ETAPAS</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 16px; display: flex; flex-direction: column; align-items: center; text-align: center; margin: 2% 4% 2% auto; font-size: 1.3em; max-width: 300px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
        <a class="icon-link icon-link-hover text-decoration-none" style="--bs-link-hover-color-rgb: 25, 135, 84; color: #dc3545; display: inline-table;" href="https://gabrielcaxtanho.github.io/betoplus/" target="_blank">
            click e acesse
            <strong>petResgate<i class="fa-solid fa-shield-dog"></i></strong>
        </a>
        <small style="color: #6c757d; margin-top: 10px;">Proteja seu pet! Faça um rastreador para sua segurança</small>
    </div>



    <?php include_once 'php/footer_index.php'; ?>

    <script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>