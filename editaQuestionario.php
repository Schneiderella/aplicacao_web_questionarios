<?php
include_once "fachada.php";

$questionario_id = @$_GET["questionario_id"];

$questionario_dao = $factory->getQuestionarioDao();
$questionario = $questionario_dao->buscaPorID($questionario_id);

if($questionario==null) {
    $questionario = new Questionario(null, null, null, null, null, null);
}

$titulo_pagina = "Cadastro de Questionários";
include_once "header.php";

?>
    <section class="col-12 col-sm-8 col-lg-6 m-auto">
        <div class="pt-3">
            <h3>Cadastro de questionários</h3>
        </div>

        <form action="salvaQuestionario.php" method=post>

        <?php 
            if($questionario->getId()) {
                echo "<label class='form-label' for='id'>Id:</label>";
                echo "<input class='form-control' type='int' value='". $questionario->getId() ."' name='id' readonly />";
            }
        ?>
            <label class="form-label" for="nome">Nome:</label>
            <input class="form-control mb-3" type= "text" value="<?=$questionario->getNome()?>" name="nome"/>

            <label class="form-label" for="descricao">Descrição:</label>
            <input class="form-control mb-3" type= "text" value="<?=$questionario->getDescricao()?>" name="descricao"/>

            <label class="form-label" for="dataCriacao">Data de Criação:</label>
            <?php 
                if($questionario->getDataCriacao()) {
                    
                    echo "<input class=\"form-control\" type= \"date\" value=" .$questionario->getDataCriacao() .  " name=\"dataCriacao\" disabled/>";
                }
                else{
                    echo "<input class=\"form-control mb-3\" type= \"date\" value=" . date('Y-m-d H:i:s') . " name=\"dataCriacao\" disabled/>";
                }
            ?>
            
            <label class="form-label" for="notaAprovacao">Nota para Aprovação:</label>
            <input class="form-control mb-3" type= "number" value="<?=$questionario->getNotaAprovacao()?>" name="notaAprovacao"/>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                <input class="btn btn-primary" type= "submit" value="Salvar"/>
                <a class="btn btn-secondary" href="questionarios.php">Voltar</a>
            </div>
        </form>
    </section>
    
<?php	
   include_once "footer.php";
?>