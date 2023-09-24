<?php

include_once "fachada.php";
$titulo_pagina = "";
include_once "header.php";

$id = @$_POST["id"];
$data = @$_POST["data"];
$questionarioId = @$_POST["questionario"];
$respondenteId = @$_POST["respondente"];

$sucesso = false;
$mensagem = "Não foi possível salvar oferta";

if (isset($data) && !empty($data) && isset($questionarioId) && !empty($questionarioId) && isset($respondenteId) && !empty($respondenteId)){
    
    $ofertaDao = $factory->getOfertaDao();
    $questionarioDao = $factory->getQuestionarioDao();
    $respondenteDao = $factory->getRespondenteDao();

    $questionario = $questionarioDao->buscaPorID($questionarioId);
    $respondente = $respondenteDao->buscaPorID($respondenteId);
    
    $oferta = new Oferta($id, $data, $questionario, $respondente);

    if(!$id) {
        $sucesso = $ofertaDao->insere($oferta);
    
    } else {
        $sucesso = $ofertaDao->altera($oferta);
    }
    
} else {
    $mensagem = "Nao enviou todos os parâmetros";
}

if ($sucesso){
    header("Location: oferta.php");
} else {
    //Se ocorreu um erro mostra mensagem
    $tipo = "danger";
    $titulo = "Erro ao salvar a oferta";
    include_once "mostraMensagem.php";
}

include_once "footer.php";

?>
