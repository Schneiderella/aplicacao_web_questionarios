<?php
include_once "fachada.php";
include_once "comum.php";

$respondenteId = null;

if (isset($_GET['respondente_id'])) {
    $respondenteId = $_GET['respondente_id'];
} else {
    
    if (is_session_started() === FALSE ) {
        session_start();
    }	
    
    if(isset($_SESSION['id'])) {
        $respondenteId = $_SESSION['id'];
    }

}

$questionarioId = @$_GET["questionario_id"];

$questionarioDao = $factory->getQuestionarioDao();
$submissaoDao = $factory->getSubmissaoRespostaDao();
$questionario = $questionarioDao->buscaPorId($questionarioId);

$submissoes = $submissaoDao->buscaPorResultados($respondenteId, $questionarioId);


$nomeQuest = "";
if (isset($questionario)){
    $nomeQuest = " - " . ucfirst($questionario->getNome()); 
}

$titulo_pagina = "Submissões efetuadas";
include_once "header.php";

?>

    <section class="col-12 p-3 ">

        <h3 class="py-2">Submissões efetuadas <?=$nomeQuest?></h3>

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">

            <?php 
                if(!isset($submissoes) || empty($submissoes)){ ?>
                    <div class="alert alert-warning my-3" role="alert">
                        Não há submissões efetuadas para esse questionário
                    </div>  
                <?php
                }
            
                foreach($submissoes as $submissao){
                    $data = Date("d/m/y", strtotime($submissao->getData()));
                    ?>

                    <div>
                        <article class="card text-center mx-2 mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><?=ucfirst($submissao->getNomeOcasiao())?></h5>
                                <!-- <p class="card-text"><?=$submissao->getDescricao()?></p> -->
                                <p class="card-text">Respondido em <?=$data?></p>
                                <a href="respostas.php?submissao_id=<?=$submissao->getId()?>&questionario_id=<?=$questionario->getId()?>" class="btn btn-primary">Ver Resultados</a>
                            </div>
                        </article>
                    </div>

            <?php } ?>

        </div>

    
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a class="btn btn-secondary" href="listaOfertasQuestionario.php">Voltar</a>
        </div>
    </section>
<?php	
   include_once "footer.php";
?>