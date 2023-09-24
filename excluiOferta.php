<?php

include_once "fachada.php";

$id = @$_GET["id"];

$dao = $factory->getOfertaDao();

$oferta = new Oferta($id, null, null, null, null);

if ($dao->remove($oferta)) {
    header("Location: oferta.php");
} else {
    $tipo = "danger";
    $titulo = "Não foi possível excluir Oferta";
    include_once "mostraMensagem.php";
}


?>