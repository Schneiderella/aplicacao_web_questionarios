<?php

$nome = @$_POST['query'];
$page_post = @$_POST['page'];
$limit_post = @$_POST['limit'];

include_once('../fachada.php');

$limit = $limit_post ? $limit_post : '3';
$page = 1;

if ($page_post > 1) {
    $start = (($page_post - 1) * $limit);
    $page = $page_post;
} else {
    $start = 0;
}

$dao = $factory->getQuestionarioDao();
$questionario_list = $dao->buscaPorNome($nome, $limit, $start, );
$total_data = $dao->contaPorNome($nome);
$total_data_pagina = count($questionario_list);

$output = "";

if ($total_data > 0) {
    $output .= "<table class='table'>
    <tr>
        <th>#</th>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Nota p/ Aprovação</th>
        <th>Responsável</th>	
        <th>Data</th>
        <th>Ações</th>	
    </tr>
    <tbody>";

    foreach ($questionario_list as $questionario) {
        $data = Date("d/m/y", strtotime($questionario->getDataCriacao()));

        $output .= "<tr>
            <td>" . $questionario->getId() ."</td>
            <td>" .$questionario->getNome() ."</td>
            <td>" .$questionario->getDescricao() ."</td>
            <td>" .$questionario->getNotaAprovacao() ."</td>
            <td>" . $questionario->getElaborador() ."</td>
            <td>" . $data ."</td>
            <td>
                <a href='editaQuestionario.php?questionario_id=" .$questionario->getId() ."'>
                    <i class=\"bi bi-pencil-square\"></i>
                </a>
                <a href='excluiQuestionario.php?questionario_id=" .$questionario->getId() . 
                    "' onclick=\"return confirm('Quer mesmo excluir?');\">
                    <i class=\"bi bi-trash3\"></i>
                </a>";

            $output .= "<a  title='Ver ou adicionar Questões' href='questionarioQuestao.php?questionario_id={$questionario->getId()}'>
                    <i class='bi bi-card-list'></i>
                </a>";

        }
        $output .= "</td>
                </tr>";
    
    $output .= "</tbody>
    </table>";

} else {
    $output .= "
      <div class='alert alert-warning my-3' role='alert'>
      Não foram encontrados registros
      </div>";
}

include_once('paginacao.php');

echo $output;

?>