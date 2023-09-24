<?php

include_once "fachada.php";
$titulo_pagina = "";
include_once "header.php";

$id = @$_POST["id"];
$nome = @$_POST["nome"];
$cpf = @$_POST["cpf"];
$email = @$_POST["email"];
$instituicao = @$_POST["instituicao"];
$login = @$_POST["login"];
$senha = @$_POST["senha"];
$is_admin = isset($_POST["is_admin"]) ? $_POST["is_admin"] : false;

$mensagem = "Não foi possível salvar os dados do elaborador";
$sucesso = false;

if (!empty($nome) && !empty($cpf) && !empty($email) && !empty($instituicao) && !empty($login) && !empty($senha)){
    
    $dao = $factory->getElaboradorDao();
    
    $elaborador = new Elaborador($id, $login, $senha, $nome, $email, $cpf, $instituicao, $is_admin);

    if (strlen($cpf) < 11){
        $mensagem = "Cpf informado é inválido";
    } else {

        if(!$id) {
    
            $buscaPorLogin = $dao->buscaPorLogin($elaborador->getLogin());
            $buscaPorCpf = $dao->buscaPorCpf($elaborador->getCpf());
    
            if ( isset($buscaPorLogin) && $buscaPorLogin->getId() != $elaborador->getId()) {
    
                $mensagem = "Login já cadastrado";
            } elseif (isset($buscaPorCpf) && $buscaPorCpf->getId() != $elaborador->getId()){
    
                $mensagem = "CPF já cadastrado";
            } else {
                
                $sucesso = $dao->insere($elaborador);
            }
        
        } else {
            $sucesso = $dao->altera($elaborador);
        }

    }
    
} else {
    $mensagem = "Nao enviou todos os parâmetros";
}

if ($sucesso){
    header("Location: index.php");
} else {
    //Se ocorreu um erro mostra mensagem
    $tipo = "danger";
    $titulo = "Erro ao salvar o elaborador";
    include_once "mostraMensagem.php";
}

include_once "footer.php";

?>
