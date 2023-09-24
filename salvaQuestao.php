<?php

include_once "fachada.php";

$id = @$_POST["id"];
$descricao = @$_POST["descricao"];
$tipoResposta = @$_POST["tipo_resposta"];

$tmpImagem = $_FILES["imagem"]["tmp_name"]; //nome temp. do arquivo enviado
$nomeImagem = $_FILES["imagem"]["name"]; //nome do arquivo

$add_alternativa = @$_POST["add_alternativa"];

$nomeImagem = str_replace(" ", "_", $nomeImagem);


$isDiscursiva = false;
$isObjetiva = false;
$isMultiplaEscolha  = false;

$questao = null;
$sucesso = false;
$mensagem = "Ocorreu um erro ao salvar as informações";

if (!isset($descricao) || empty($descricao) || !isset($tipoResposta) || empty($tipoResposta)) {
    $mensagem = "Nao enviou todos os parâmetros";
} else {
    
    if($tipoResposta==="discursiva"){
        $isDiscursiva = true;
    }
    elseif($tipoResposta==="objetiva"){
        $isObjetiva = true;
    }
    elseif($tipoResposta==="multiplaEscolha"){
        $isMultiplaEscolha = true;
    }

    $dao = $factory->getQuestaoDao();

    if($id){
        $questao = $dao->buscaPorID($id);
    }

    if (!file_exists("./uploads")){
        mkdir("./uploads", 0777);
    }


    if (($tmpImagem!='') and ($tmpImagem!='')){
        $sucesso = copy($tmpImagem, "./uploads/$nomeImagem");
    }    


 

    if($questao===null) {
        $questao = new Questao($id, $descricao, $isDiscursiva, $isObjetiva, $isMultiplaEscolha);
        if ($sucesso) { $questao->setImagem($nomeImagem);}
        $novaQuestao = $dao->insere($questao);
        $sucesso = $novaQuestao[0];
        $id = $novaQuestao[1];


    } else {
        $questao->setDescricao($descricao);
        $questao->setIsDiscursiva($isDiscursiva);
        $questao->setIsObjetiva($isObjetiva);
        $questao->setIsMultiplaEscolha($isMultiplaEscolha);
        if ($sucesso) { $questao->setImagem($nomeImagem);}
        $sucesso = $dao->altera($questao);
    }
}


if ($sucesso) {
    if($add_alternativa){
        header("Location: alternativa.php?questao_id=$id");
    }else{
        header("Location: questao.php");
    }
    
} else {
    //Se ocorreu um erro mostra mensagem
    $tipo = "danger";
    $titulo = "Erro ao salvar a questão";
    include_once "mostraMensagem.php";
}


?>
