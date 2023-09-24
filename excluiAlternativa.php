<?php

include_once "fachada.php";

$id = @$_GET["id"];
$questao_id = @$_GET["questao_id"];

$dao = $factory->getAlternativaDao();

$alternativa = new Alternativa($id, null, null);

if ($dao->remove($alternativa)) {
    header("Location: alternativa.php?questao_id=" . $questao_id );
} else {
    $tipo = "danger";
    $titulo = "Não foi possível excluir a alternativa";
    include_once "mostraMensagem.php";
}


?>