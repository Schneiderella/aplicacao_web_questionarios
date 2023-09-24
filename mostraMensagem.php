<?php 
include_once "fachada.php";
$titulo_pagina = "";
include_once "header.php";

if (!isset($titulo) || empty($titulo)){
    $titulo = "Ocorreu um erro";
}

if (!isset($mensagem) || empty($mensagem)){
    $mensagem = "Tente novamente mais tarde";
}

//tipo danger ou warning
if (!isset($tipo) || empty($tipo)){
    $tipo = "danger";
}

?>
    <section class="col-8 m-auto">
        <div class="alert alert-<?=$tipo?>" role="<?=$tipo?>">
            <h4 class="alert-heading text-center"><?=$titulo?></h4>
            <p class="text-center"><?=$mensagem?></p>
        </div>
    </section>
<?php

include_once "footer.php";

?>