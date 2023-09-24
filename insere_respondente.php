<?php
include_once "fachada.php";
include_once "header.php";

$login = @$_GET["login"];
$senha = @$_GET["senha"];
$nome = @$_GET["nome"];

$nome = @$_GET["nome"];
$cpf = @$_GET["cpf"];
$email = @$_GET["email"];
$telefone = @$_GET["telefone"];
$login = @$_GET["login"];
$senha = @$_GET["senha"];

$respondente = new Respondente(null, $login, $senha, $nome, $email, $cpf, $telefone);
$dao = $factory->getRespondenteDao();
$dao->insere($respondente);

header("Location: respondentes.php");
exit;

?>