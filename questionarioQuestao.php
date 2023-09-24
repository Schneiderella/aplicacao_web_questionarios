<?php
include_once "fachada.php";

$questionario_id = @$_GET["questionario_id"];

$questionario_dao = $factory->getQuestionarioDao();
$questionario = $questionario_dao->buscaPorID($questionario_id);
$questionarioQuestao = $questionario->getQuestionarioQuestoes();
 

if($questionario==null) {
    $questionario = new Questionario(null, null, null, null, null, null);
}

$titulo_pagina = "Cadastro de Questionários";
include_once "header.php";

?>
    <section class="col-12 col-sm-8 col-lg-6 m-auto">
        <div class="pt-3">
            <h3>Vinculação de questões</h3>
        </div>
        <form action="salvaQuestionario.php" method=post>
            <div class="mb-3">
    
            <label class="form-label" for="id">Questionario:</label>
            <input class="form-control" type= "int" value="<?= $questionario->getId() ?>" name="id" disabled />

            <br>
            <label class="form-label" for="nome">Nome:</label>
            <input class="form-control" type= "text" value="<?=$questionario->getNome()?>" name="nome" disabled/>
            <br>
            <label class="form-label" for="descricao">Descrição:</label>
            <input class="form-control" type= "text" value="<?=$questionario->getDescricao()?>" name="descricao" disabled/>
            <br>
            </div>

            <h5>Questões</h5>
            <?php
            if($questionarioQuestao) {
            
                echo "<table class=\"table\">";
                echo "<tr>";
                    echo "<th>Ordem</th>";
                    echo "<th>Descrição</th>";
                    echo "<th>Tipo de questão</th>";
                    echo "<th>Pontos</th>";
                    echo "<th>Ações</th>";
                echo "</tr>";

                foreach ($questionarioQuestao as $umQuestao) {

                    echo "<tr>";
                        echo "<td>{$umQuestao->getOrdem()}º</td>";
                        echo "<td>{$umQuestao->getQuestao()->getDescricao()}</td>";
                        echo "<td>{$umQuestao->getQuestao()->getTipo()}</td>";
                        echo "<td>{$umQuestao->getPontos()}</td>";
                        echo "<td>";

                        // link para editar um Questionario
                        echo "<a href='editaQuestionarioQuestao.php?questionario_id={$questionario_id}&id={$umQuestao->getId()}'><i class=\"bi bi-pencil-square\"></i></a>";

                        // link para excluir um Questionario
                        echo "<a href='excluiQuestionarioQuestao.php?questionario_id={$questionario_id}&id={$umQuestao->getId()}' onclick=\"return confirm('Quer mesmo excluir?');\">
                        <i class=\"bi bi-trash3\"></i>
                        </a>";

                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Não foram encontrados registros";
            }

            ?>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <?php
                echo "<a class=\"btn btn-primary\" href=\"editaQuestionarioQuestao.php?questionario_id={$questionario_id}\">Adicionar Questão</a>";
                ?>
                <a class="btn btn-secondary" href="questionarios.php">Voltar</a>
            </div>
        </form>
        <br>
    </section>
    
<?php	
   include_once "footer.php";
?>