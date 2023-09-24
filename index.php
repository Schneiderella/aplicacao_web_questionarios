<?php
$titulo_pagina = "Trabalho 01 - ADS-UCS";
include_once "header.php";
include_once "comum.php";

$respondenteId = null; 
$tipoUsuario = null;

if (is_session_started() === FALSE) {
    session_start();
}

if (isset($_SESSION['tipo'])) {
    $tipoUsuario = $_SESSION['tipo'];
}

?>

<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-transparent sidebar">
                <div class="sidebar-sticky">
                    <?php if ($tipoUsuario === 0): ?>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="cadastroElaborador.php">
                                    <i class="bi bi-person-plus-fill"></i>
                                    Cadastrar Elaborador
                                </a>
                            </li>
                        </ul>
                    <?php elseif ($tipoUsuario === 1): ?>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="questao.php">
                                    <i class="bi bi-journal-plus"></i>
                                    Questões
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="questionarios.php">
                                    <i class="bi bi-list-ul"></i>
                                    Questionários
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="oferta.php">
                                    <i class="bi bi-file-earmark-plus"></i>
                                    Ofertar Questionário
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="listaOfertasQuestionario.php">
                                    <i class="bi bi-journal-check"></i>
                                    Corrigir Questionários
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="estatistica.php">
                                    <i class="bi bi-bar-chart-fill"></i>
                                    Estatísticas
                                </a>
                            </li>
                        </ul>
                    <?php elseif ($tipoUsuario === 2): ?>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="listaOfertasQuestionario.php">
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                    Ver Questionários
                                </a>
                            </li>
                        </ul>
                    <?php else: ?>
                        <?php header("Location: login.php"); ?>
                    <?php endif; ?>
                </div>
            </nav>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <!-- Conteúdo principal da página -->
            </main>
        </div>
    </div>

<?php
include_once "footer.php";
?>
