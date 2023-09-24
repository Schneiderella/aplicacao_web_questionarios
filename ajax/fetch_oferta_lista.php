<?php

$nome = $_POST['query'];
$page_post = @$_POST['page'];
$limit_post = @$_POST['limit'];

include_once('../fachada.php');

$limit = $limit = $limit_post ? $limit_post : '5';
$page = 1;

if ($page_post > 1) {
  $start = (($page_post - 1) * $limit);
  $page = $page_post;
} else {
  $start = 0;
}

$dao = $factory->getOfertaDao();
$ofertas = $dao->buscaPorDesc($nome, $limit, $start);
$total_data = $dao->contaRegistrosDesc($nome);
$total_data_pagina = count($ofertas);

$output = "";

if ($total_data > 0) {
  $output .= "<table class='table'>
  <tr>
      <th>#</th>
      <th>Data</th>
      <th>Questionário</th>
      <th>Respondente</th>
      <th>Ações</th>	
  </tr>
  <tbody>";


  foreach ($ofertas as $oferta) {
    $output .= "
    <td>" .$oferta->getId()."</td>
    <td>" . Date("d/m/Y h:m:s", strtotime($oferta->getData())) ."</td>
    <td>". ucfirst($oferta->getQuestionario()->getNome()) . "</td>
    <td>" . $oferta->getRespondente()->getNome() ."</td>
    <td>
      <a href='editaOferta.php?id=" . $oferta->getId() . "'><i class='bi bi-pencil-square'></i></a>
      <a href='excluiOferta.php?id=" . $oferta->getId() . "' onclick='return confirm('Quer mesmo excluir?');'>
        <i class='bi bi-trash3'></i>
      </a>
		</td>
		</tr>";
	}
  $output .= "</table>
	</div>";
    
} else {
    $output .= "
    <p>Não foram encontrados registros
  ";
}

include_once('paginacao.php');

echo $output;
