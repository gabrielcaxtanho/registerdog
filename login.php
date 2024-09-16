<!DOCTYPE html>
<html lang="pt-br">
<!-- <?php include("php/head_login.php"); ?> -->


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="./css/style.css">
</head>

<header id="header"></header>
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
        background-color: #ffd5d5;
        color: #cd0505;
        ;
        text-align: center;
        padding: 1em;
    }

    .success {
        background-color: #31b7bb;
        color: white;
        text-align: center;
        padding: 1em;
    }
</style>

<body>
    <?php
    include_once './bd/conexao.php';
    ?>

    <!--    -->

    <div class="container-lg">
        <div class="d-flex" style="display: flex; justify-content: space-evenly; flex-wrap: wrap; margin-top: 6em">
            <!-- <div class="col-md-4 m-4">
                <div class="font">
                    <h2 style="text-align: center; color: darkcyan;"> PET REGISTER</h2>
                    <h4 class="text-secondary" style="text-align: center;">TODOS OS SEUS REGISTROS EM UM SÓ LUGAR</h4>
                    <img src="./img/logopets.png" alt="Logo Pets" width="50%" style="margin-left: 8em;">
                </div>
            </div> -->
            <div class="col-md-6">
                <!-- <div>
                    <?php
                    if (isset($_GET['error'])) {
                        if ($_GET['error'] == "bloquser") {
                            echo "<p class='error'>Usuário Bloqueado</p>";
                        } else if ($_GET['error'] == "stmtfailed") {
                            echo "<p class='error'>Nenhum Usuário encontrado.</p>";
                        } /* else if ($_GET['error'] == "wronglogin") {
                            echo "<p class='error'>Senha Incorreta.</p>";
                        } */
                    } else if (isset($_GET['success'])) {
                        if ($_GET['success'] == "usercreated") {
                            echo "<p class='success'>Usuário criado com sucesso!</p>";
                        }
                    }
                    ?>
                </div> -->

                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="font">
                                <h2 style="text-align: center; color: darkcyan;"> PET REGISTER</h2>
                                <h4 class="text-secondary" style="text-align: center;">TODOS OS SEUS REGISTROS EM UM SÓ LUGAR</h4>
                            </div>

                            <hr class="border-white">
                            <div class="shadow rounded p-4 mb-4" style="border-top: #2e8a97 7px solid;">

                                <form id="registrationForm" action="includes/login.inc.php" method="POST" class="row g-3 needs-validation" novalidate>
                                    <div class="form-group">
                                        <input id="login-input-1" class="form-control" name="uid" type="text" placeholder="E-mail/Usuário" />
                                    </div>

                                    <div class="input-group mb-3">
                                        <input id="login-input-2" class="form-control" name="pwd" type="password" placeholder="Senha" aria-label="Senha" aria-describedby="show-pass-btn">
                                        <button class="btn btn-outline-secondary" type="button" id="show-pass-btn" onclick="showPass()">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="card-footer bg-transparent text-end">
                                        <button class="btn btn-primary" type='submit' name='submit' id='submit'>login</button>
                                    </div>
                                </form>

                                <script>
                                    $('#login-input-2').keyup(function(e) {
                                        if (e.keyCode == 13) {
                                            $('#login').click();
                                        }
                                    });
                                </script>
                                <?php
                                if (isset($_GET["error"])) {
                                    if ($_GET["error"] == "emptyinput") {
                                        echo "<div class='my-3 alert alert-danger p-3 text-center'>Preencha todos os campos!</div>";
                                    } else if ($_GET["error"] == "wronglogin") {
                                        echo "<div class='my-3 alert alert-danger p-3 text-center'>Usuário/E-mail ou senha errados, tente novamente!</div>";
                                    }else if ($_GET["error"] == "stmtfailed") {
                                        echo "<div class='my-3 alert alert-danger p-3 text-center'>Nenhum Usuário encontrado</div>";
                                    } else if ($_GET["error"] == "waitaprov") {
                                        //echo "<div id='red-warning'></div>";

                                        //Pop-up
                                        echo "
                                                            <div class='modal-dialog'  name='myModal' tabindex='-1' role='dialog'>
                                                                <div class='modal-content'>
                                                                    <div class='modal-header'>
                                                                        <h5 class='modal-title' id='exampleModalLabel'>Cadastro Pendente</h5>                                                                       
                                                                        
                                                                        </button>
                                                                    </div>
                                                                    <div class='modal-body'>
                                                                        <p>Cadastro em processo de validação, enviaremos no seu número cadastrado o link para seu 1º acesso.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        ";
                                    } else if ($_GET["error"] == "bloquser") {
                                        //echo "<div id='red-warning'></div>";

                                        //Pop-up
                                        echo "
                                                            <div class='modal-dialog'  name='myModal' tabindex='-1' role='dialog'>
                                                                <div class='modal-content'>
                                                                    <div class='modal-header'>
                                                                        <h5 class='modal-title' id='exampleModalLabel'>Usuário Bloqueado</h5>
                                                                        
                                                                        
                                                                        </button>
                                                                    </div>
                                                                    <div class='modal-body'>
                                                                        <p>Detectamos algumas atividades suspeitas e sua conta foi bloqueada. Caso ache que tenha havido algum engano entre em contato conosco.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        ";
                                    }
                                }
                                ?>
                                <div class="d-flex justify-content-center py-2 my-4">
                                    <div class=""><a href="cadastro.php" class="btn btn-outline-secondary">Não tem uma conta? Cadastre-se</a></div>

                                </div>

                                <div class="text-center py-1">
                                    <div class=""><a href="senha" class="text-fab">Esqueceu sua senha? Recuperar</a></div>
                                </div>
                                <div class="py-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </main>
    <script>
        function showPass() {

            event.preventDefault();
            var passInput = document.getElementById('login-input-2');
            if (passInput.type == 'password') {
                passInput.type = 'text';

            } else {
                passInput.type = 'password';

            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
</body>

</html>