<?php

include_once "fachada.php";
$titulo_pagina = "";
include_once "header.php";

$id = @$_POST["id"];
$questao_id = @$_POST["questao_id"];
$descricao = @$_POST["descricao"];
$isCorreta = isset($_POST["isCorreta"]) ? $_POST["isCorreta"] : false;

$mensagem = "Não foi possível salvar dados do alternativa";

if (isset($questao_id) && !empty($questao_id) && isset($descricao) && !empty($descricao)){
    
    $alternativa_dao = $factory->getAlternativaDao();
    $alternativa = new Alternativa($id, $descricao, $isCorreta);

    if(!$id) {
        $sucesso = $alternativa_dao->insere($alternativa, $questao_id);
        
    } else {
        $sucesso = $alternativa_dao->altera($alternativa);
    }

} else {
    $mensagem = "Dados incompletos";
}

if ($sucesso){
    header("Location: alternativa.php?questao_id=" . $questao_id);
} else {
    //Se ocorreu um erro mostra mensagem
    $tipo = "danger";
    $titulo = "Erro ao salvar a alternativa";
    include_once "mostraMensagem.php";
}

include_once "footer.php";

?>