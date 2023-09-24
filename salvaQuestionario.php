<?php

include_once "fachada.php";
include_once "comum.php";

$elaborador_id = null;

if (is_session_started() === FALSE ) {
    session_start();
}	

if(isset($_SESSION['id'])) {
    $elaborador_id = $_SESSION['id'];
}


$id = @$_POST["id"];
$nome = @$_POST["nome"];
$descricao = @$_POST["descricao"];
$notaAprovacao = @$_POST["notaAprovacao"];
$questionario = null;
$sucesso = false;
$mensagem = "Ocorreu um erro ao salvar as informações";

if (!isset($nome) || empty($nome) || !isset($descricao) || empty($descricao) || !isset($notaAprovacao) || empty($notaAprovacao)) {
    $mensagem = "Não enviou todos os parâmetros";
} else {
    
    $dao = $factory->getQuestionarioDao();

    $elaborador_dao = $factory->getElaboradorDao();
    $elaborador = $elaborador_dao->buscaPorID($elaborador_id);
    
    if($id){
        $questionario = $dao->buscaPorID($id);
    }
    
    if($questionario===null) {
        $questionario = new Questionario($id, $nome, $descricao, null, $notaAprovacao, $elaborador);
        $sucesso = $dao->insere($questionario);
    
    } else {
        $questionario->setNome($nome);
        $questionario->setDescricao($descricao);
        $questionario->setNotaAprovacao($notaAprovacao);
        $sucesso = $dao->altera($questionario);
    }
}


if ($sucesso) {
    header("Location: questionarios.php");
} else {
    //Se ocorreu um erro mostra mensagem
    $tipo = "danger";
    $titulo = "Erro ao salvar o questionário";
    include_once "mostraMensagem.php";
}


?>
