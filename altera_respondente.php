<?php
include_once "fachada.php";

$id = @$_GET["id"];
$nome = @$_GET["nome"];
$cpf = @$_GET["cpf"];
$email = @$_GET["email"];
$telefone = @$_GET["telefone"];
$login = @$_GET["login"];
$senha = @$_GET["senha"];

$respondente = new Respondente($id, $nome, $cpf, $email, $telefone, $login, $senha);
$dao = $factory->getRespondenteDao();

//$respondente->setPassword(md5($respondente->getPassword()));

$dao->altera($respondente);

header("Location: respondentes.php");
exit;

?>