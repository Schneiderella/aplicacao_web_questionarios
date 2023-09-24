<?php

include_once "fachada.php";
$titulo_pagina = "";
include_once "header.php";

$correcoes = @$_POST;
var_dump($correcoes);
$sucesso = false;
$mensagem = "Não foi possível salvar correcao";

if (!isset($correcoes) || empty($correcoes)) {
    $mensagem = "Não foram enviados todos os parâmetros";
} else {
    
    $submissaoDao = $factory->getSubmissaoRespostaDao();
    
    foreach($correcoes as $key=>$value){
        var_dump($value);
        $observacao = null;
        $nota = null;

        $key_array = explode ("_", $key);
        $tipo_campo = end($key_array);
        $resposta_id = $key_array[0];
        $resposta = $submissaoDao->buscaRespostaPorId($resposta_id);

        if($tipo_campo==='nota'){
            $nota = $value;
            $resposta->setNota($nota);
        } 
        else if($tipo_campo==='observacao') {
            $observacao = $value;
            $resposta->setObservacao($observacao);
        }
        
        $sucesso = $submissaoDao->corrigeResposta($resposta);
        
    }
    
}
if ($sucesso) {
    header("Location: index.php");
} else {
    
    //Se ocorreu um erro mostra mensagem
    $tipo = "danger";
    $titulo = "Erro ao salvar correção";
    include_once "mostraMensagem.php";

}

include_once "footer.php";

?>