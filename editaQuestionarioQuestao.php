<?php
include_once "fachada.php";

$questionario_id = @$_GET["questionario_id"];
$id= @$_GET["id"];

$questionarioQuestao_dao = $factory->getQuestionarioQuestaoDao();
$questionarioQuestao = $questionarioQuestao_dao->buscaPorID($id);

$questao_dao = $factory->getQuestaoDao();
$questoes = $questao_dao->buscaTodos();


if($questionarioQuestao==null) {
    $questionarioQuestao = new QuestionarioQuestao(null, null, null, null);
}

$titulo_pagina = "Adição de Questões ao Questionário";
include_once "header.php";

?>
    <section class="col-12 col-sm-8 col-lg-6 m-auto">
        <div class="pt-3">
            <h3>Adição de Questões ao Questionário</h3>
        </div>

        <form action="salvaQuestionarioQuestao.php" method=post>
            <div class="mb-3">
                <input type="hidden" value="<?=$questionario_id?>" name="questionario_id">
        <?php 
            if($questionarioQuestao->getId()) {
                echo "<label class=\"form-label\" for=\"id\">Id:</label>";
                echo "<input class=\"form-control\" type= \"int\" value=\"". $questionarioQuestao->getId() ."\" name=\"id\" readonly />";
            }
        ?>
            <br>
            <label class="form-label" for="ordem">Ordem:</label>
            <input class="form-control" type= "number" value="<?=$questionarioQuestao->getOrdem()?>" name="ordem" required/>
            <br>

            <!-- Busca por questao -->
            <label class="form-label" for="busca-questao">Questão:</label>
            <input class="form-control mb-2" type="text" name="busca-questao" id="busca_questao" placeholder="Digite o nome da questão para filtrar"/>
            <div class="table-responsive" id="lista-questoes"></div>

            <?php //Se está editando, obtem id p/ selecionar
                if ($questionarioQuestao->getQuestao() != null) {
                  echo "<input type='hidden' id='questao-id' value='" . $questionarioQuestao->getQuestao()->getId() . "' />";
                }
            ?>
        
            <label class="form-label" for="pontos">Pontos:</label>
            <input class="form-control" type= "number" value="<?=$questionarioQuestao->getPontos()?>" name="pontos" required/>
            <br>
            <br>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <input class="btn btn-primary" type= "submit" value="Salvar"/>
                <a class="btn btn-secondary" href="questionarioQuestao.php?questionario_id=<?=$questionario_id?>">Voltar</a>
            </div>
        </form>
        <br>
    </section>
    
<?php	
   include_once "footer.php";
?>

<script>
  $(document).ready(function(){

    let questaoId = $("#questao-id").val();

    load_data(1, "", questaoId);

    function load_data(page, query = '', questaoId = null) {
      $.ajax({
        url:"./ajax/fetch_questao.php",
        method:"POST",
        data:{page:page, query:query, id: questaoId},
        success:function(data) {
          $('#lista-questoes').html(data);
        }
      });
    }

    $(document).on('click', '.page-link', function(){
      var page = $(this).data('page_number');
      var query = $('#busca_questao').val();
      load_data(page, query);
    });

    $('#busca_questao').keyup(function(){
      var query = $('#busca_questao').val();
      load_data(1, query);
    });

  });
</script>