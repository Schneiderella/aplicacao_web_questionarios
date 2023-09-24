<?php
include_once "fachada.php";
include "verifica.php";
$id = @$_GET["id"];

$dao = $factory->getRespondenteDao();
$respondente = $dao->buscaPorId($id); //testar se é 'buscaPorID($id)'
if($respondente) {
	$titulo_pagina = "Exibindo Usuário Respondente: " . $respondente->getNome();
} else {
	$titulo_pagina = "Usuário Respondente não encontrado!";
} 

// layout do cabeçalho
include_once "header.php";
if($respondente) {
echo "<section>";
//dados do respondente
// !importante! colocar outros atributos de respondente

echo "<h1> Login : " . $respondente->getLogin() . "</h1>";
echo "<p> Id usuario : " . $respondente->getId() . "</p>";
echo "<p> Nome : " . $respondente->getNome() . "</p>";
// botão voltar
echo "<a href='respondentes.php' class='btn btn-primary left-margin'>";
echo "Voltar";
echo "</a>";
echo "</section>";
}

// layout do rodapé
include_once "footer.php";
?>
