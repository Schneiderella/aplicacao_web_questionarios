<?php
include_once "fachada.php";
$titulo_pagina = "Respopnder Questionário";
include_once "header.php";
include_once "comum.php";

$respondenteId = null; // Obter id do respondente logado

if (is_session_started() === FALSE ) {
    session_start();
}	

if(isset($_SESSION['id'])) {
    $respondenteId = $_SESSION['id'];
}

$questionario_id = @$_GET["questionario_id"]; 
$oferta_id = @$_GET["oferta_id"]; 

$dao = $factory->getQuestionarioDao();
$questionario = $dao->buscaPorID($questionario_id);
$titulo = $questionario->getNome();
$descricao = $questionario->getDescricao();

?>
    <section class="col-8 m-auto py-3">
        <h2 class="text-center p-2"><?=ucfirst($titulo)?></h2>
        <p class="text-center p-2"><?=$descricao?></p>
        <form action="salvaRespostas.php?oferta_id=<?=$oferta_id?>&respondente_id=<?=$respondenteId?>" method="post">

        <?php
            foreach($questionario->getQuestionarioQuestoes() as $questionarioQuestoes){

                echo "<div class='pt-2'>";
                    $questoes = $questionarioQuestoes->getQuestao();
                    echo "<p>" . $questionarioQuestoes->getOrdem() . "- ";
                    echo $questoes->getDescricao() . "</p>";
                    $questoes_id = $questoes->getId();
                    $isDiscursiva = $questoes->getIsDiscursiva();
                    $isObjetiva = $questoes->getIsObjetiva();
                    $isMultiplaEscolha = $questoes->getIsMultiplaEscolha();

                    $questao_dao = $factory->getQuestaoDao();
                    $alternativas = ($questao_dao->buscaPorID($questoes_id))->getAlternativas();

                    if (($questoes->getImagem() != null) || ($questoes->getImagem() != '')){
                        echo "<img width='200' class='mb-3 img-fluid' src='./uploads/" . $questoes->getImagem() . "'>";
                    }

                    if($isDiscursiva){
                        echo "<textarea type='text' class='form-control' 
                        id='" . $questoes_id . "_texto' 
                        name='" . $questoes_id . "_texto' 
                        aria-describedby='Texto' rows='5' required/></textarea>";
                    }
                    elseif($isObjetiva or $isMultiplaEscolha){
                        foreach($alternativas as $alternativa){
                            echo "<div class='mb-2 d-flex'>";
            
                                if($isObjetiva){
                                    echo "<input type='radio' class='m-1' 
                                    id='" . $questoes_id . "_" . $alternativa->getId() . "_alternativa' 
                                    name='" . $questoes_id . "_alternativa' 
                                    aria-describedby='Alternativa' 
                                    value='" . $questoes_id . "_" . $alternativa->getId() . "'/>";
                                }
                                if($isMultiplaEscolha){
                                    echo "<input type='checkbox' class='m-1' 
                                    id='" . $questoes_id . "_" . $alternativa->getId() . "_alternativa' 
                                    name='" . $questoes_id . "_" . $alternativa->getId() . "_alternativa' 
                                    aria-describedby='Alternativa' 
                                    value='" . $questoes_id . "_" . $alternativa->getId() . "' />";
                                }
                                echo "<label for='nome' class='form-label align-self-center my-auto'> " . $alternativa->getDescricao() . "</label>";
                            echo"</div>";

                        }
                    }
                echo "</div>";
            }
        
        ?>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <input class="btn btn-primary" type= "submit" value="Salvar"/>
                <a class="btn btn-secondary" href="listaOfertasQuestionario.php">Voltar</a>
            </div>
        </form>
    </section>
<?php

include_once "footer.php";
?>






