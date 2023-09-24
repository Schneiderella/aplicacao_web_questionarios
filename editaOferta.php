<?php
include_once "fachada.php";

$id = @$_GET["id"];

$ofertaDao = $factory->getOfertaDao();
$respondenteDao = $factory->getRespondenteDao();
$questionarioDao = $factory->getQuestionarioDao();

$oferta = $ofertaDao->buscaPorID($id);
$respondentes = $respondenteDao->buscaTodos();
$questionarios = $questionarioDao->buscaTodos();

if($oferta==null) {
    $oferta = new Oferta(null, null, null, null);
}

$titulo_pagina = "Cadastro de Oferta";
include_once "header.php";

?>
    <section class="col-12 col-sm-8 col-lg-6 m-auto">
        <div class="pt-3">
            <h3>Cadastro de oferta</h3>
        </div>

        <form action="salvaOferta.php" method=post>
            <div class="mb-3">
            <?php 
                if($oferta->getId()) {
                    echo "<label class='form-label' for='id'>Id:</label>";
                    echo "<input class='form-control' type='int' value='". $oferta->getId() ."' name='id' readonly />";
                }
            ?>
            <div class="my-3">
                <label class="form-label" for="data">Data:</label>
                <input class="form-control" type="date" value="<?=$oferta->getData()?>" name="data"/>
            </div>

            <!-- Busca por questionario -->
            <input class="form-control mb-2" type="text" name="busca-questionario" id="busca_questionario" placeholder="Digite o nome do questionario para filtrar"/>
            <div class="tabela-questionario" id="lista-questionarios"></div>

            <?php //Se está editando, obtem id do questionario p/ selecionar posteriormente
                if ($oferta->getQuestionario() != null) {
                  echo "<input type='hidden' id='questionario-id' value='" . $oferta->getQuestionario()->getId() . "' />";
                }
            ?>

            <!-- Busca por respondente -->
            <input class="form-control mb-2" type="text" name="busca-respondente" id="busca_respondente" placeholder="Digite o nome do respondente para filtrar"/>
            <div class="tabela-respondente" id="lista-respondentes"></div>

            <?php //Se está editando, obtem id do respondente p/ selecionar posteriormente
                if ($oferta->getRespondente() != null) {
                  echo "<input type='hidden' id='respondente-id' value='" . $oferta->getRespondente()->getId() . "' />";
                }
            ?>
            
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <input class="btn btn-primary" type= "submit" value="Salvar"/>
                <a class="btn btn-secondary" href="oferta.php">Voltar</a>
            </div>
        </form>
        <br>
    </section>
    
<?php	
   include_once "footer.php";
?>

<script>
  $(document).ready(function(){

    let questionarioId = $("#questionario-id").val();
    let respondenteId = $("#respondente-id").val();

    getQuestionario(1, "", questionarioId);
    getRespondente(1, "", respondenteId);

    function getQuestionario(page, query = '', questionarioId = null) {
      $.ajax({
        url:"./ajax/fetch_questionario.php",
        method:"POST",
        data:{page:page, query:query, id: questionarioId},
        success:function(data) {
          $('#lista-questionarios').html(data);
        }
      });
    }

    function getRespondente(page, query = '', resondenteId = null) {
      $.ajax({
        url:"./ajax/fetch_respondente.php",
        method:"POST",
        data:{page:page, query:query, id: respondenteId},
        success:function(data) {
          $('#lista-respondentes').html(data);
        }
      });
    }

    $(document).on('click', '#lista-questionarios .page-link', function(){
      var page = $(this).data('page_number');
      var query = $('#busca_questionario').val();
      getQuestionario(page, query);
    });

    $(document).on('click', '#lista-respondentes .page-link', function(){
      var page = $(this).data('page_number');
      var query = $('#busca_respondente').val();
      getRespondente(page, query);
    });

    $('#busca_questionario').keyup(function(){
      var query = $('#busca_questionario').val();
      getQuestionario(1, query);
    });

    $('#busca_respondente').keyup(function(){
      var query = $('#busca_respondente').val();
      getRespondente(1, query);
    });

  });
</script>