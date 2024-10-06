<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        #header {
            background-image: url('./img/img.jpg');
            /*  background-image: url('https://images.hdqwalls.com/download/windows-11-minimal-white-4k-y6-1920x1080.jpg'); */
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: white;
            height: 40px;
        }
    </style>
</head>

<header id="header"></header>

<nav class="header bg-light bg-gradient shadow-sm p-2 d-flex justify-content-between">
    <div class="btn-container d-flex align-items-center">
        <button class="btn btn-outline-secondary" onclick="openNav()">
            <i class="fa-solid fa-bars-staggered" style="font-size: 150%;"></i>
        </button>
    </div>
    <div class="d-flex align-items-center" style="margin-right: 6%;">
        <a href='acompanhamento.php' title="acompanhamento" class="text-dark me-3">
            <i class='fas fa-list fa-1x' style="font-size: 140%; color: cadetblue"></i>
        </a>
        <a href='cadastrado.php' class="text-dark" title="pet cadastrado">
            <i class="fa-solid fa-magnifying-glass-chart" style="font-size: 140%; color: cadetblue; margin: 22%;"></i>
        </a>
    </div>
</nav>


<div id="mySidenav" class="sidenav shadow font-montserrat">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
    <div class="container-fluid">
        <div class="row text-center mt-5">
            <div class=" align-items-center">
                <span class="bg-fab p-1 mx-2" style="border-radius: 5px;">
                    <img src="logo.png" alt="">
                </span>
                <div>
                    <small class="p-0" style="font-weight: 700; color: #146ba0; font-size: 10pt;"><i class="fa-solid fa-paw"></i> Register Pet</small>
                    <br>
                    <small style="font-weight: 400; color: silver;">Powered by L2A</small>
                </div>
            </div>
        </div>
    </div>

    <div id="user-box">
        <!-- PHP code for user session can be inserted here -->
        <div class="col d-flex justify-content-center py-2">
            <!-- PHP code to display user name and permission -->
        </div>
    </div>

    <a href="dash.php"><i class="fas fa-home fa-1x"></i> Início</a>
    <a href="perfil.php"><i class="far fa-id-badge"></i> Meu Perfil</a>
    <a href='cadastro.php'><i class="fa-solid fa-address-card"></i></i> Cadastro</a>
    <a href='historico.php'><i class="fa-solid fa-magnifying-glass-chart"></i> Historico</a>
    <a href='acompanhamento.php'><i class='fas fa-list fa-1x'></i> Acompanhamento</a>
    <a class="deactivated"><i class='fas fa-cog fa-1x'></i> Configurações</a>
    <a class="deactivated"><i class="fas fa-boxes fa-1x"></i> Produtos</a>
    <a href="suporte.php"><i class="far fa-life-ring fa-1x mr-1"></i> Suporte</a>

    <!-- Admin and Manager sections can be conditionally displayed here with PHP -->
    <!-- Add more details and sections as needed -->

</div>


<style>
    .sidenav {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        background-color: #f8f9fa;
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 60px;
    }

    .sidenav a {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        font-size: 18px;
        color: #146ba0;
        display: block;
        transition: 0.3s;
    }

    .sidenav a:hover {
        color: #37474F;
    }

    .sidenav .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
    }

    .main-content {
        margin-left: 0;
        padding: 20px;
        transition: margin-left 0.5s;
    }
</style>

<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        document.querySelector('.main-content').style.marginLeft = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        document.querySelector('.main-content').style.marginLeft = "0";
    }

    document.addEventListener('DOMContentLoaded', function() {
        var toastEl = document.querySelector('.toast');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    });
</script>

<script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>