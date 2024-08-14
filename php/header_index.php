<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<header>
    <nav class="navbar  p-3 mb-2 bg-info-subtle text-info-emphasis" id="nav">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header p-3 mb-2 bg-info-subtle text-info-emphasis">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Register PETS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav d-flex justify-content-center align-items-center">
                        <li class="nav-item">
                            <a class="nav-link active text-info-emphasis" aria-current="page" href="./cadastrado.php"><i class="fa-solid fa-house"></i> Inicio</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav d-flex justify-content-center align-items-center">
                        <li class="nav-item">
                            <a class="nav-link active text-info-emphasis" aria-current="page" href="./cadastrado.php"><i class="fa-solid fa-address-book" style="font-size: 1em;"></i> Pets Cadastrados</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav d-flex justify-content-center align-items-center">
                        <li class="nav-item">
                            <a class="nav-link active text-info-emphasis" aria-current="page" href="./cadastro.php"><i class="fa-solid fa-user-plus fa-2xl" style="font-size: 1em;"></i> Cadastre um Pet</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <img src="https://i.pinimg.com/564x/66/a9/81/66a9818af810fdf31981405418e0ea43.jpg" width="80" class="rounded me-2" alt="...">
        <strong class="me-auto">Olá, Pronto para registrar mais um pet?</strong>
        <!-- <small>1 min agora</small> -->
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <!-- <div class="toast-body">
        Olá, Pronto para registrar mais um pet?
    </div> -->
</div>
<script src="https://kit.fontawesome.com/c0eae24639.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toastEl = document.querySelector('.toast');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    });
</script>