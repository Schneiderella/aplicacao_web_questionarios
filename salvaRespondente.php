<?php

include_once "fachada.php";
$titulo_pagina = "";
include_once "header.php";

$id = @$_POST["id"];
$nome = @$_POST["nome"];
$cpf = @$_POST["cpf"];
$email = @$_POST["email"];
$telefone = @$_POST["telefone"];
$login = @$_POST["login"];
$senha = @$_POST["senha"];

$mensagem = "Não foi possível salvar dados do respondente";
$sucesso = false;

if (!empty($nome) && !empty($cpf) && !empty($email) && !empty($telefone) && !empty($login) && !empty($senha)){
    
    $dao = $factory->getRespondenteDao();
    
    $respondente = new Respondente($id, $login, $senha, $nome, $email, $cpf, $telefone);

    if (strlen($cpf) < 11){
        $mensagem = "Cpf informado é inválido";
    } else {

        if(!$id) {
    
            $buscaPorLogin = $dao->buscaPorLogin($respondente->getLogin());
            $buscaPorCpf = $dao->buscaPorCpf($respondente->getCpf());
    
            if ( isset($buscaPorLogin) && $buscaPorLogin->getId() != $respondente->getId()) {
    
                $mensagem = "Login já cadastrado";
            } elseif (isset($buscaPorCpf) && $buscaPorCpf->getId() != $respondente->getId()){
    
                $mensagem = "CPF já cadastrado";
            } else {
                
                $sucesso = $dao->insere($respondente);
            }
        
        } else {
            $sucesso = $dao->altera($respondente);
        }

    }

    if ($sucesso){
        header("Location: index.php");
    }
    
} else {
    $mensagem = "Nao enviou todos os parâmetros";
}

//Se ocorreu um erro mostra mensagem
$tipo = "danger";
$titulo = "Erro ao salvar Respondente";
include_once "mostraMensagem.php";

include_once "footer.php";

?>
