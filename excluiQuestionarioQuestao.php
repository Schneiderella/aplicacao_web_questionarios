<?php

include_once "fachada.php";

$id = @$_GET["id"];
$questionario_id = @$_GET["questionario_id"];

$dao = $factory->getQuestionarioQuestaoDao();

$questionarioQuestao = new QuestionarioQuestao($id, null, null, null);

if ($dao->remove($questionarioQuestao)) {
    header("Location: questionarioQuestao.php?questionario_id=" . $questionario_id );
} else {
    $tipo = "danger";
    $titulo = "Não foi possível excluir o Vínculo entre questionário e questão";
    include_once "mostraMensagem.php";
}


?>