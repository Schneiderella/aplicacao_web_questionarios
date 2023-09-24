<?php

include_once "fachada.php";

$questionario_id = @$_GET["questionario_id"];

$dao = $factory->getQuestionarioDao();

$questionario = new Questionario($questionario_id, null, null, null, null, null);

if ($dao->remove($questionario)) {
    header("Location: questionarios.php");
} else {
    $tipo = "danger";
    $titulo = "Não foi possível excluir Questionário";
    include_once "mostraMensagem.php";
}


?>