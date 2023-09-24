<?php

include_once "fachada.php";

$questao_id = @$_GET["questao_id"];

$dao = $factory->getQuestaoDao();

$questao = new Questao($questao_id, null, null, null, null);

if ($dao->remove($questao)) {
    header("Location: questao.php");
} else {
    $tipo = "danger";
    $titulo = "Não foi possível excluir questão";
    include_once "mostraMensagem.php";
}


?>