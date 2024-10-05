<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>login</title>
    <link rel="icon" href="img/logopets1.png" type="image">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        background-color: #d5feff;
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
</head>

<header id="header"></header>

<body>
    <?php
    include_once './bd/conexao.php';
    ?>

    <div class="container">
        <div class="row py-4">
            <div class="col">
                <div class="font">
                    <h3 class="text-center fw-bold"><b>NOVO USUÁRIO</b></h3>
                </div>
            </div>

        </div>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-4" style="display: flex; align-content: center; flex-wrap: wrap;">
                <div class="font">
                    <h2 style="text-align: center; color: darkcyan;"> PET REGISTER</h2>
                    <h4 class="text-secondary" style="text-align: center;">TODOS OS SEUS REGISTROS EM UM SÓ LUGAR</h4>
                    <div class="d-flex-de flex-column align-items-center p-3 bg-light rounded shadow-sm" style="margin-top: 3em;">
                        <!--  <img src="./img/logopets.png" alt="Logo Pets" width="50%"> -->
                        <p class="text-secondary text-center mb-2">Otimize o atendimento e melhore a experiência dos seus clientes peludos, garantindo um serviço mais eficiente e personalizado. Registre-se agora e simplifique a gestão do seu estabelecimento!</p>
                        <small><i class="fa-solid fa-paw text-danger-emphasis"></i></small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div>
                    <?php
                    if (isset($_GET['error'])) {
                        if ($_GET['error'] == "stmtfailed") {
                            echo "<p class='error'>Erro ao processar o pedido. Por favor, tente novamente.</p>";
                        } else if ($_GET['error'] == "userorexists") {
                            echo "<p class='error'>Nome de usuário ou celular já estão em uso.</p>";
                        } else if ($_GET['error'] == "executionfailed") {
                            echo "<p class='error'>Erro ao inserir dados. Por favor, tente novamente.</p>";
                        }
                    } else if (isset($_GET['success'])) {
                        if ($_GET['success'] == "usercreated") {
                            echo "<p class='success'>Usuário criado com sucesso!</p>";
                        }
                    }
                    ?>

                </div>
                <hr class="border-white">
                <div class="shadow rounded p-4 mb-4" style="border-top: #2e8a97 7px solid;">
                    <form id="registrationForm" action="includes/cadastro.inc.php" method="POST" class="row g-3 needs-validation" novalidate>
                        <div class="col-md-6">
                            <label for="validationServer01" class="form-label">Nome e Sobrenome</label>
                            <input type="text" class="form-control" id="validationServer01" name="userName" minlength="9" required>
                            <div class="invalid-feedback">
                                Nome e Sobrenome deve ter pelo menos 5 caracteres.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="validationServer02" class="form-label"><strong> Nome do PetShop </strong></label>
                            <input type="text" class="form-control" id="validationServer02" name="nomePetShop" minlength="5" required>
                            <div class="invalid-feedback">
                                Nome fantasia deve ter pelo menos 5 caracteres.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="validationServerEmail" class="form-label">E-mail</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="text" class="form-control" id="validationServer01" name="userEmail" minlength="9" required placeholder="seuemail@dominio.com">
                                <div class="invalid-feedback">
                                    Por favor, insira um e-mail válido.
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="password1" class="form-label">Senha</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-eye-slash" id="togglePassword1" style="cursor: pointer;"></i>
                                </span>
                                <input type="password" class="form-control" id="password1" name='password' required>
                                <div class="invalid-feedback">
                                    Senha é obrigatória.
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="password2" class="form-label">Insira a senha novamente</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-eye-slash" id="togglePassword2" style="cursor: pointer;"></i>
                                </span>
                                <input type="password" class="form-control" id="password2" name='confirmpassword' required>
                                <div class="invalid-feedback">
                                    Senha deve corresponder.
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="validationServer03" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="validationServer03" name="cidade" required>
                            <div class="invalid-feedback">
                                Por favor, informe uma cidade válida.
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="validationServer04" class="form-label">Estado</label>
                            <select class="form-select" id="validationServer04" name='estado' required>
                                <option selected disabled value="">Escolha...</option>
                                <option value="AC">Acre (AC)</option>
                                <option value="AL">Alagoas (AL)</option>
                                <option value="AP">Amapá (AP)</option>
                                <option value="AM">Amazonas (AM)</option>
                                <option value="BA">Bahia (BA)</option>
                                <option value="CE">Ceará (CE)</option>
                                <option value="DF">Distrito Federal (DF)</option>
                                <option value="ES">Espírito Santo (ES)</option>
                                <option value="GO">Goiás (GO)</option>
                                <option value="MA">Maranhão (MA)</option>
                                <option value="MT">Mato Grosso (MT)</option>
                                <option value="MS">Mato Grosso do Sul (MS)</option>
                                <option value="MG">Minas Gerais (MG)</option>
                                <option value="PA">Pará (PA)</option>
                                <option value="PB">Paraíba (PB)</option>
                                <option value="PR">Paraná (PR)</option>
                                <option value="PE">Pernambuco (PE)</option>
                                <option value="PI">Piauí (PI)</option>
                                <option value="RJ">Rio de Janeiro (RJ)</option>
                                <option value="RN">Rio Grande do Norte (RN)</option>
                                <option value="RS">Rio Grande do Sul (RS)</option>
                                <option value="RO">Rondônia (RO)</option>
                                <option value="RR">Roraima (RR)</option>
                                <option value="SC">Santa Catarina (SC)</option>
                                <option value="SP">São Paulo (SP)</option>
                                <option value="SE">Sergipe (SE)</option>
                                <option value="TO">Tocantins (TO)</option>
                            </select>
                            <div class="invalid-feedback">
                                Por favor, selecione um estado válido.
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="validationServer05" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="validationServer05" name="celular" required placeholder="(xx) xxxxx-xxxx">
                            <div class="invalid-feedback">
                                Por favor, informe um número de telefone válido.
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="invalidCheck3" required>
                                <label class="form-check-label" for="invalidCheck3">
                                    Concordo com os termos e condições
                                </label>
                                <div class="invalid-feedback">
                                    Você deve concordar antes de enviar.
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary" type='submit' name='submit' id='submit'>Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer text-center">
        <div class="">
            <a href="index.php">
                <div class="alert-secondary">Já tenho cadastro! Acessar</div>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showPass() {

            event.preventDefault();
            var passInput = document.getElementById('login-input-2');
            if (passInput.type == 'password') {
                passInput.type = 'text';
                console.log('mostrou');
            } else {
                passInput.type = 'password';
                console.log('escondeu');
            }
        }

        function verifyEspec(elem) {

            var elem = elem.value;

            if (elem == 'outros') {
                document.getElementById('outraespec').hidden = false;

            } else {
                document.getElementById('outraespec').hidden = true;
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword1 = document.getElementById('togglePassword1');
            const password1 = document.getElementById('password1');
            const togglePassword2 = document.getElementById('togglePassword2');
            const password2 = document.getElementById('password2');

            function togglePasswordVisibility(input, icon) {
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                icon.classList.toggle('fa-eye', type === 'text');
                icon.classList.toggle('fa-eye-slash', type === 'password');
            }

            togglePassword1.addEventListener('click', function() {
                togglePasswordVisibility(password1, this);
            });

            togglePassword2.addEventListener('click', function() {
                togglePasswordVisibility(password2, this);
            });

            const form = document.getElementById('registrationForm');
            form.addEventListener('submit', function(event) {
                let valid = true;

                // Clear previous error messages
                password2.classList.remove('is-invalid');
                password2.nextElementSibling.textContent = '';

                // Check if passwords match
                if (password1.value !== password2.value) {
                    valid = false;
                    password2.classList.add('is-invalid');
                    password2.nextElementSibling.textContent = 'As senhas não correspondem.';
                }

                // Prevent form submission if validation fails
                if (!valid) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                // Add Bootstrap validation classes
                form.classList.add('was-validated');
            });

            // Real-time validation
            function handleValidation() {
                var form = document.getElementById('registrationForm');
                var inputs = form.querySelectorAll('input, select');

                inputs.forEach(function(input) {
                    input.addEventListener('input', function() {
                        if (input.checkValidity()) {
                            input.classList.remove('is-invalid');
                            input.classList.add('is-valid');
                        } else {
                            input.classList.remove('is-valid');
                            input.classList.add('is-invalid');
                        }
                    });
                });

                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                        form.classList.add('was-validated');
                    } else {
                        // Reset feedback for successful validation
                        inputs.forEach(function(input) {
                            input.classList.remove('is-valid');
                            input.classList.remove('is-invalid');
                        });
                    }
                }, false);
            }

            handleValidation();
        });
    </script>

    <footer class="p-3 bg-dark-subtle text-dark-emphasis" style="margin-top: 7em;">
        <div class="container">
            <div class="text-center">
                <div class="col" style="display: flex; justify-content: space-around; margin-top: 2em">
                    <div class="col-4 mb-3 d-flex flex-column align-items-center">
                        <p><strong>Contato para Vendas</strong></p>
                        <p>
                            <i class="fa-solid fa-phone"></i> (XX) XXXX-XXXX<br>
                            <i class="fa-solid fa-envelope"></i> vendas@sistemasL2A.com
                        </p>
                    </div>
                    <div class="col-5 mb-3 d-flex flex-column align-items-center">
                        <p><strong>Endereço</strong></p>
                        <p>Rua Exemplo, 123, Bairro, Cidade, Estado, CEP</p>
                    </div>
                </div>
                <p class="mt-3">
                    <i class="fa-solid fa-globe"></i>
                    <a href="https://www.sistemasL2A.com" target="_blank" class="text-dark-emphasis text-decoration-none">
                        www.sistemasL2A.com
                    </a>
                </p>
                <hr class="w-100">
                <p class="text-center">
                    <i class="fa-solid fa-copyright"></i> Sistemas L2A 2020 - 2024<br>
                    Todos os direitos reservados.
                </p>
                <p>Versão 1.0</p>
            </div>

        </div>
        </div>
    </footer>

    <script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
<?php

?>