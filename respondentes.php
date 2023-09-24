<?php
// layout do cabeçalho

include "verifica.php";

$titulo_pagina = "Listagem de Usuários Respondentes";

include_once "header.php";
include_once "fachada.php";

echo "<section>";

// procura respondentes

$dao = $factory->getRespondenteDao();
$respondentes = $dao->buscaTodos();

//!importante! colocar mais colunas e ajustar layout

// mostra todos os respondentes
if($respondentes) {

	echo "<table class='table table-hover table-responsive table-bordered'>";
	echo "<tr>";
		echo "<th>Id usuario respondente</th>";
		echo "<th>Login</th>";
		echo "<th>Nome</th>";
	echo "</tr>";

	foreach ($respondentes as $respondente) {

		echo "<tr>";
			echo "<td>{$respondente->getId()}</td>";
			echo "<td>{$respondente->getLogin()}</td>";
			echo "<td>{$respondente->getNome()}</td>";
			echo "<td>";
				// botão para mostrar um usuário
				echo "<a href='mostra_respondente.php?id={$respondente->getId()}' class='btn btn-primary left-margin'>";
					echo "<span class='glyphicon glyphicon-list'></span> Mostra";
				echo "</a>";
				// botão para alterar um usuário
				echo "<a href='modifica_respondente.php?id={$respondente->getId()}' class='btn btn-info left-margin'>";
				echo "<span class='glyphicon glyphicon-edit'></span> Altera";
				echo "</a>";
				// botão para remover um usuário
				echo "<a href='remove_respondente.php?id={$respondente->getId()}' class='btn btn-danger left-margin'";
				echo "onclick=\"return confirm('Tem certeza que quer excluir?')\">";
				echo "<span class='glyphicon glyphicon-remove'></span> Exclui";
				echo "</a>";
			echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
}

echo "<a href='novo_respondente.php' class='btn btn-primary left-margin'>";
echo "Novo";
echo "</a>";

echo "</section>";

// layout do rodapé
include_once "footer.php";
?>

