<?php

include_once "fachada.php";
$titulo_pagina = "";
include_once "header.php";

$respostas = @$_POST;
$oferta_id = @$_GET['oferta_id'];
$respondente_id = @$_GET['respondente_id'];

$sucesso = false;
$mensagem = "Não foi possível salvar respostas";

if (!isset($respostas) || empty($respostas) || !isset($oferta_id) || empty($oferta_id) || !isset($respondente_id) || empty($respondente_id)) {
    $mensagem = "Não foram enviados todos os parâmetros";
} else {

    $submissaoDao = $factory->getSubmissaoRespostaDao();
    $qtdSubmissao = 1 + $submissaoDao->totalPorRespondente($respondente_id);
    
    // Onde deve ser coletado os dados  desses campos?
    $nomeOcasiao = "Envio " . $qtdSubmissao;
    $descricao = "descrição para submissão";
    
    $oferta_dao = $factory->getOfertaDao();
    $oferta = $oferta_dao->buscaPorId($oferta_id);
    
    $submissaoResposta_dao = $factory->getSubmissaoRespostaDao();
    $submissao = new Submissao(null, $nomeOcasiao, $descricao, date("Y-m-d H:i:s"), $respondente_id, $oferta);
    
    foreach($respostas as $key=>$value){
        $texto = null;
        $alternativa = null;
        $nota = null;
        
        $key_array = explode ("_", $key);
        $tipo_resposta = end($key_array);
        $questao_id = $key_array[0];
        $questao_dao = $factory->getQuestaoDao();
        $questao = $questao_dao->buscaPorId($questao_id); 
        
    
        if($tipo_resposta==='texto'){
            $texto = $value;
        } else {
            $value_array = explode ("_", $value);
            $alternativa_id =  end($value_array);
            $alternativa_dao = $factory->getAlternativaDao();
            $alternativa = $alternativa_dao->buscaPorId($alternativa_id); 
            
            // Correção automática: se a alternativa selecionada é a correta, inclui nota na resposta, conforme pontos atribuídos a questão
            $is_correta = $alternativa->getIsCorreta();
            if($is_correta){
                $quationario = $oferta->getQuestionario();
                $questionarioQuestao_dao = $factory->getQuestionarioQuestaoDao();
                
                $questionarioQuestao = $questionarioQuestao_dao->buscaQuestionarioQuestao($questao, $quationario);
                $nota = $questionarioQuestao->getPontos(); 
            } else {
                // se a alternativa é incrreta, zera a nota da resposta
                $nota = 0; 
            }
    
        }
    
        $resposta = new Resposta(null, $questao, $texto, $alternativa, $nota);
        $submissao->addResposta($resposta);
        
    }
    
    $sucesso = $submissaoResposta_dao->insere($submissao);
}

if ($sucesso) {
    header("Location: listaOfertasQuestionario.php");
} else {
    
    //Se ocorreu um erro mostra mensagem
    $tipo = "danger";
    $titulo = "Erro ao salvar respostas";
    include_once "mostraMensagem.php";

}

include_once "footer.php";

?>