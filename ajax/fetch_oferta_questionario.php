<?php

$nome = @$_POST['query'];
$page_post = @$_POST['page'];
$limit_post = @$_POST['limit'];
$respondenteId = @$_POST['usuario'];

include_once('../fachada.php');

$limit = $limit_post ? $limit_post : '3';
$page = 1;

if ($page_post > 1) {
    $start = (($page_post - 1) * $limit);
    $page = $page_post;
} else {
    $start = 0;
}

$dao = $factory->getOfertaDao();
$submissaoDao = $factory->getSubmissaoRespostaDao();
$ofertas = $dao->buscaPorRespondente($respondenteId, $limit, $start, $nome);
$total_data = $dao->contaRegistros($respondenteId, $nome);
$total_data_pagina = count($ofertas);

$output = "<div class='row row-cols-1 row-cols-md-3 row-cols-xl-3 g-4'>";

if ($total_data > 0) {
    foreach ($ofertas as $oferta) {
      
    $questionario = $oferta->getQuestionario();
    $questionarioId = $questionario->getId();
    $submissoes = $submissaoDao->buscaPorResultados($respondenteId, $questionarioId);

    $output .= "
        <div>
            <article class='card text-center mx-2 mb-3'>
                <div class='card-body'>
                    <h5 class='card-title'>" . ucfirst($questionario->getNome()) . "</h5>
                    <p class='card-text'>" . $questionario->getDescricao() . "</p>";
                
                    if($submissoes){
                        $output .= "<a href='listaSubmissoes.php?questionario_id=" . $questionario->getId() . "' class='button-primary btn btn-primary'>Ver Resultados</a>";
                    }else{
                    
                        $output .= " 
                        <a href='responder.php?questionario_id=" .$questionario->getId() . "&oferta_id=" .$oferta->getId() . "&respondente_id=" . $respondenteId . "' class='button-primary btn btn-primary'>
                            Responder
                        </a>";
                    }
                    $output .= "
                </div> 
            </article>
        </div>";
    }
} else {
    $output .= "
    <div class='alert alert-warning my-3' role='alert'>
        Não há ofertas disponíveis no momento
    </div>";
}
$output .= "</div>";
$output .= "<div><label>Estão sendo mostrados " . $total_data_pagina ." registros de um total de " . $total_data . "</label></div><br>";

include_once('paginacao.php');

echo $output;
