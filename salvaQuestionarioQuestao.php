<?php

include_once "fachada.php";

$id = @$_POST["id"];
$questionarioId = @$_POST["questionario_id"];
$questaoId = @$_POST["questao_id"];
$ordem = @$_POST["ordem"];
$pontos = @$_POST["pontos"];

$sucesso = false;
$mensagem = "Não foi possível salvar as informações";

if (!isset($questionarioId) || empty($questionarioId) || !isset($questaoId) || empty($questaoId) || !isset($ordem) || empty($ordem) || !isset($pontos) || empty($pontos)) {
    $mensagem = "Não enviou todos os parâmetros";
} else {

    $questionarioQuestaoDao = $factory->getQuestionarioQuestaoDao();
    
    $questaoDao = $factory->getQuestaoDao();
    $questao = $questaoDao->buscaPorID($questaoId);
    
    if(isset($id) && !empty($id)){
        $questionarioQuestao = $questionarioQuestaoDao->buscaPorID($id);
    }
    
    if(!isset($questionarioQuestao)) {
        
        $questionarioQuestao = new QuestionarioQuestao($id, $ordem, $pontos, $questao);
        $sucesso = $questionarioQuestaoDao->insere($questionarioQuestao, $questionarioId);
    
    } else {
        $questionarioQuestao->setOrdem($ordem);
        $questionarioQuestao->setPontos($pontos);
        $questionarioQuestao->setQuestao($questao);
        $sucesso = $questionarioQuestaoDao->altera($questionarioQuestao, $questionarioId);
    }

}

if ($sucesso) {
    header("Location: questionarioQuestao.php?questionario_id=" . $questionarioId); 
} else {
    //Se ocorreu um erro mostra mensagem
    $tipo = "danger";
    $titulo = "Erro ao salvar o vínculo entre questionário e questão";
    include_once "mostraMensagem.php";
}



?>
