<?php

$nome = @$_POST['query'];
$page_post = @$_POST['page'];
$limit_post = @$_POST['limit'];
$elaboradorId = @$_POST['usuario'];

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
$daoSubmissao = $factory->getSubmissaoRespostaDao();
// $questionarios = $dao->buscaPorElaborador($elaboradorId, $nome, $limit, $start);
// $total_data = $dao->contaPorElaborador($elaboradorId, $nome);
$questionarios = $dao->buscaPorNome($nome, $limit, $start);
$total_data = $dao->contaPorNome($nome);
// $total_data_pagina = count($ofertas);

$output = "<div class='row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3'>";

if ($total_data > 0) {
    foreach ($questionarios as $questionario) {

        $questionarioQuestao = $questionario->getQuestionarioQuestoes();

        $ofertaDao = $factory->getOfertaDao();
        $ofertas = $ofertaDao->buscaPorQuestionario($questionario->getId());

        $output .= "
        <div>
            <article class='card text-center mx-2 mb-2'>
                <div class='card-body'>
                    <h5 class='card-title'>" . ucfirst($questionario->getNome()) . "</h5>
                    <p class='card-text'>" . $questionario->getDescricao() . "</p>";
                
                    if($ofertas){

                        $output .= "
                            <div class='accordion' id='accordionExample'>
                                <div class='accordion-item'>
                                    <h2 class='accordion-header'>
                                        <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse" . $questionario->getId() . "' aria-expanded='false' aria-controls='collapse" . $questionario->getId() . "' onclick='collapseBtn(" . $questionario->getId() . ")'>
                                            Lista respondentes
                                        </button>
                                    </h2>
                                    <div id='collapse" . $questionario->getId() . "' class='accordion-collapse collapse' data-bs-parent='#accordionParentt'>
                                        <div class='accordion-body'>
                                            <ul class='list-group'>
                                            ";

                                            foreach($ofertas as $oferta) {
                                                $submissao = $daoSubmissao->buscaPorResultados($oferta->getRespondente()->getId(), $oferta->getQuestionario()->getId());
                
                                                $output .="
                                                    <li class='list-group-item'>" . 
                                                        "<div class='d-sm-flex justify-content-end align-items-center'>
                                                            <p class='col-sm-8 m-0'>" . $oferta->getRespondente()->getNome() . "</p>";
                                                            if(!isset($submissao) || empty($submissao)){
                                                                $output .="<button class='button-secondary btn btn-secondary'>Aguardar</button>";
                                                            }else{
                                                                $output .="<a href='listaSubmissoes.php?questionario_id=" . $questionario->getId() ."&respondente_id=" . $oferta->getRespondente()->getId() . "' class='button-primary btn btn-primary'>Ver Respostas</a>";
                                                            }
                                                        
                                                            $output .="</div>" .
                                                    "</li>
                                                ";
                                            }
                                            
                                            $output .="
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ";
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
// $output .= "<div><label>Estão sendo mostrados " . $total_data_pagina ." registros de um total de " . $total_data . "</label></div><br>";

include_once('paginacao.php');

echo $output;

?>

<script>
    function collapseBtn(questionario) {
        $('#collapse'+ questionario).toggleClass('show')
    }
</script>

