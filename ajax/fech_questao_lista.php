
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

$dao = $factory->getQuestaoDao();
$questao_list = $dao->buscaPorDesc($nome, $limit, $start, );
$total_data = $dao->contaRegistros($nome);
$total_data_pagina = count($questao_list);

$output = "";

if ($total_data > 0) {
    $output .= "<table class='table'>
    <tr>
        <th>#</th>
        <th>Descrição</th>
        <th>Tipo de questão</th>	
        <th>Ações</th>	
    </tr>
    <tbody>";

    foreach ($questao_list as $questao) {

        $output .= "<tr>
            <td>" . $questao->getId() ."</td>
            <td>" .$questao->getDescricao() ."</td>
            <td>" . $questao->getTipo() ."</td>
            <td>
                <a href='editaQuestao.php?questao_id=" .$questao->getId() ."'>
                    <i class=\"bi bi-pencil-square\"></i>
                </a>
                <a href='excluiQuestao.php?questao_id=" .$questao->getId() . 
                    "' onclick=\"return confirm('Quer mesmo excluir?');\">
                    <i class=\"bi bi-trash3\"></i>
                </a>";

        $output .= "</td>
                </tr>";
    }
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