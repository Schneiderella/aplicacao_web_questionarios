<?php
include_once "fachada.php";

$id = @$_GET["id"];

$respondente = new Respondente($id, $login, $senha, $nome, $email, $cpf, $telefone);
$dao = $factory->getRespondenteDao();
$dao->removePorId($id); // removePorID testar

header("Location: usuarios.php");
exit;

?>