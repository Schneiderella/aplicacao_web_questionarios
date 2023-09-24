<?php
include_once "fachada.php";
$titulo_pagina = "Respostas";
include_once "header.php";

$questionario_id = @$_GET["questionario_id"];
$submissao_id = @$_GET["submissao_id"];
$nota = 0;
$total_nota_questionario = 0;
$nota_final = true;

if (is_session_started() === FALSE ) {
    session_start();
  }
  
  $usuarioId = null; // Obter id do usuario logado
  $tipoUsuario = null; // Obter tipo do usuario logado
  
  if(isset($_SESSION['id'])) {
      $usuarioId = $_SESSION['id'];
      $tipoUsuario = $_SESSION['tipo'];
  }

if (!isset($submissao_id) && !isset($questionario_id)){ ?>

    <section class="col-8 m-auto">
        <div class="alert alert-warning" role="warning">
            <h4 class="alert-heading text-center">Não foram encontradas submissões</h4>
            <p class="text-center"><?=$erro?></p>
        </div>
    </section>

<?php } else {

    echo "<section class='col-8 m-auto py-3'>";

        $questionarioDao = $factory->getQuestionarioDao();
        $submissaoDao = $factory->getSubmissaoRespostaDao();
        $questaoDao = $factory->getQuestaoDao();

        $questionario = $questionarioDao->buscaPorId($questionario_id);
        $submissao = $submissaoDao->buscaPorId($submissao_id);
        $respostas = $submissaoDao->buscaRespostas($submissao);
        $respondente = $submissao->getRespondente();
        echo "<h2 class='text-center p-2'>Respostas - " . ucfirst($questionario->getNome()) . "</h2>";
        echo "<h5 class='text-center p-2'>Respondente: " .$respondente->getNome() . "</h5>";
        echo "<p class='text-center p-2'>" . $questionario->getDescricao() . "</p>";
        echo "<form action='corrigirQuestionario.php' method='post'>";

        foreach($questionario->getQuestionarioQuestoes() as $questionarioQuestoes) {
            $total_nota_questionario += $questionarioQuestoes->getPontos();
            $questao = $questaoDao->buscaPorID($questionarioQuestoes->getQuestao()->getId());

            $nvRespostas = buscaResposta($respostas, $questao->getId());

            if ($nvRespostas != null & count($nvRespostas) > 0){
                $resposta = $nvRespostas[0]; //Podem vir várias respostas caso seja questao de multipla escolha
            
            echo "<div class='pt-2'>";
            
            echo "<label for='#".$resposta->getId()."_nota'>Nota questão ".$questionarioQuestoes->getOrdem().":  </label>";
             
            if($resposta->getNota() != null ||$resposta->getNota() === 0){
                $nota += $resposta->getNota();
                echo "<input type='number' disabled id='' name='' value='" . $resposta->getNota() . "'>";
            }else{
                if($tipoUsuario === 1){
                    echo "<input type='number' id='" . $resposta->getId() . "_nota' name='" . $resposta->getId() . "_nota' value='' required>";
                }
                else{
                    $nota_final = false;
                   echo "<input disabled type='text' id='' name='' value='Não avaliado' >"; 
                }
            }

            echo "<label for=''> de " .$questionarioQuestoes->getPontos() . " pontos</label><br><br>";
            echo "<p>" . $questionarioQuestoes->getOrdem() . "- " . $questao->getDescricao() . "</p>";

            if (($questionarioQuestoes->getQuestao()->getImagem())  != null|| ($questionarioQuestoes->getQuestao()->getImagem()) != ''){
                echo "<img width='200' class='mb-3 img-fluid' src='./uploads/" . $questionarioQuestoes->getQuestao()->getImagem() . "'>";
            }


            if ($questao->getIsDiscursiva()){
                echo "<textarea disabled type='text' class='form-control' id='" . $questao->getId() . "_texto' name='" . $questao->getId() . "_texto' aria-describedby='Texto' rows='5'/>" . $resposta->getTexto() . "</textarea>";
                echo "<p>Observação:</p>";

                if($tipoUsuario === 1){
                    
                    echo "<textarea type='text' class='form-control' id='" . $resposta->getId() . "_observacao' name='" . $resposta->getId() . "_observacao' aria-describedby='Texto' rows='3'>"
                    . $resposta->getObservacao() . "</textarea>";
                }else{
                echo "<textarea disabled type='text' class='form-control' id='' name='' aria-describedby='Texto' rows='3'>" . $resposta->getObservacao() . "</textarea>";
                }
            } else {
                
                foreach($questao->getAlternativas() as $alternativa){

                    $tipo = $questao->getIsObjetiva() ? "radio" : "checkbox";
                    $selecionado = "";
                    $alternativaSel = $resposta->getAlternativa();

                    //Caso multipla escolha
                    if (count($nvRespostas) > 1) {

                        $alternativasSel = array();
                        foreach($nvRespostas as $resp) {
                            $alternativasSel[] = $resp->getAlternativa();
                        }

                        $alternativaSel = buscaAlternativa($alternativasSel, $alternativa->getId());
                    }

                    if (isset($alternativaSel) && $alternativaSel->getId() == $alternativa->getId()) {
                        $selecionado = "checked";
                    }

                    echo "<div class='mb-2 d-flex'>";
                    
                    echo "<input disabled " . $selecionado . " type='" . $tipo . "' class='m-1'
                        id='" . $questao->getId() . "_" . $alternativa->getId() . "_alternativa' 
                        name='" . $questao->getId() . "_" . $alternativa->getId() . "_alternativa'
                        aria-describedby='Alternativa' 
                        value='" . $questao->getId() . "_" . $alternativa->getId() . "'/>";

                    echo "<label for='nome' class='form-label align-self-center my-auto'> " . $alternativa->getDescricao() . "</label>";

                    if (!isset($alternativaSel)){
                        
                        //Resposta correta - Não respondente
                        if ($alternativa->getIsCorreta()) {
                            echo "<span class='badge rounded-pill text-bg-secondary ms-2'>Resposta correta</span>";
                        }

                    } else {
                        
                        //Resposta correta - Respondente errou
                        if (!$alternativaSel->getIsCorreta() && $alternativa->getIsCorreta()) {
                            echo "<span class='badge rounded-pill text-bg-secondary ms-2'>Resposta correta</span>";
                        }

                        //Resposta errada do respondente
                        if ($selecionado == "checked" && !$alternativa->getIsCorreta()) {
                            echo "<span class='badge rounded-pill text-bg-danger ms-2'>Você respondeu</span>";
                        }

                        //Resposta certa do respondente
                        if ($selecionado == "checked" && $alternativa->getIsCorreta()) {
                            echo "<span class='badge rounded-pill text-bg-success ms-2'>Correto!</span>";
                        }

                    }

                    echo"</div>";
                }
            }
            echo"</div>";
            echo "<br><hr><br>";
        }
    }

        // ------------------------------ Nota final  parcial ----------------------------------
        echo "<div class='d-grid gap-2 d-md-flex justify-content-md-start'> ";
        if($nota_final){
            echo "<p class='m-0'><b>Nota final: </b></p>";
        }else{
            echo "<p class='m-0'><b>Nota parcial: </b></p>";
        }
        
        echo "<p class='m-0'>" . $nota . "/". $total_nota_questionario . "</p>";
        if($questionario->getNotaAprovacao() <= $nota){
            echo "<span class='badge rounded-pill text-bg-success ms-2'>Aprovação!</span>";
        }else{
            echo "<span class='badge rounded-pill text-bg-danger ms-2'>Recuperação</span>";
        }
        echo "</div>";

        // ---------------------------- Botões ------------------------------------
        echo "<div class='d-grid gap-2 d-md-flex justify-content-md-end'>";
            if($tipoUsuario === 1){
            echo " <input class='btn btn-primary' type= 'submit' value='Salvar'/>";
            }
            echo " <a class='btn btn-secondary' href='listaOfertasQuestionario.php'>Voltar</a>

            

        </div>";
        echo "</form><br><br><br>
    </section>";
    
 }

include_once "footer.php";

function buscaResposta($respostas, $questaoId) {

    $novaResposta = array();

    foreach($respostas as $resposta) {
        if ($resposta->getQuestao()->getId() == $questaoId){
            $novaResposta[] = $resposta;
        }
    }

    return $novaResposta;
}

function buscaAlternativa($alternativas, $altId) {

    $novaAlternativa = null;

    foreach($alternativas as $alternativa) {
        if ($alternativa->getId() == $altId){
            $novaAlternativa = $alternativa;
        }
    }

    return $novaAlternativa;
}

?>






