<?php
include_once "fachada.php";
$titulo_pagina = "Adicionar Alternativas";
include_once "header.php";

$titulo = "Adiciona Alternativas à Questão";

$questao_id = @$_GET["questao_id"];
$id= @$_GET["id"];

$questao_dao = $factory->getQuestaoDao();
$questao = $questao_dao->buscaPorID($questao_id);
$tipo_resposta = $questao->getTipo();
$alternativas =  $questao->getAlternativas();

$alternaviva_dao = $factory->getAlternativaDao();
$alternativa = $alternaviva_dao->buscaPorId($id);

if($questao==null) {
    $questao = new Questao(null, null, null, null, null);
}

if(!isset($alternativa)) {
    $titulo = "Cadastrar Alternativa";
    $alternativa = new Alternativa(null, null, null);
} else {
    $titulo = "Editar Alternativa";
}

?> 
    <section class="col-12 col-sm-10 col-lg-6 m-auto py-3">
        <h2 class="text-center p-2"><?=$titulo?></h2>
        
            <div class="mb-3 d-sm-flex">
                <label for="descricao" class="col-12 col-sm-2 form-label align-self-center" >Descrição:</label>
                <input type= "text" class="form-control" id="descricao" name="descricao" aria-describedby="Descricao" value="<?=$questao->getDescricao()?>" disabled/>
            </div>
            <div class="mb-3 d-sm-flex">
                <label for="tipo_resposta" class="col-12 col-sm-2 form-label align-self-center" >Tipo da Questão:</label>
                <input type= "text" class="form-control" id="tipo_resposta" name="tipo_resposta" aria-describedby="Tipo Resposta" value="<?=$tipo_resposta?>" disabled/>
            </div><br><br>
            
            <h5>Nova Alternativa</h5> <br>
            <form action="salvaAlternativa.php" method=post>
                <input type="hidden" value="<?=$questao_id?>" name="questao_id">
                <input type="hidden" id="id" name="id" value="<?=$alternativa->getId()?>" />
                
            <div class="mb-3 d-flex">
                <label for="descricao" class="col-2 form-label align-self-center">Descrição:</label>
                <input type= "text" class="form-control" id="descricao" name="descricao" aria-describedby="Descrição" value="<?=$alternativa->getDescricao()?>" required/>
            </div>

            <div class="mb-3 d-flex">
                <label for="isCorreta" class="col-2 form-label align-self-center">Alternativa Correta:</label>
                <input type="checkbox" class="" id="isCorreta" name="isCorreta" aria-describedby="isCorreta" value="true" <?php if($alternativa->getIsCorreta()){echo "checked";}?>/>
            </div>
            <br>
           
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <?php
                // echo "<a class=\"btn btn-primary\" href=\"editaAlternativa.php?questao_id={$questao_id}\">Adicionar Alternativa</a>";
                ?>
                <input id="add_alternativa" name="add_alternativa" class="btn btn-primary" type="submit" value="Adicionar Alternativa"/>
                <a class="btn btn-secondary" href="questao.php">Voltar</a>
            </div>
        </form> <br>
            <h5>Alternativas da Questão</h5> <br>
            <?php
                if($alternativas) {
                
                    echo "<table class=\"table\">";
                    echo "<tr>";
                        echo "<th>Descrição</th>";
                        echo "<th>Alternativa Correta</th>";
                        echo "<th>Ações</th>";
                    echo "</tr>";

                    foreach ($alternativas as $umAlternativas) {

                        echo "<tr>";
                            echo "<td>{$umAlternativas->getDescricao()}</td>";

                            if($umAlternativas->getIsCorreta()){
                                echo "<td>Sim</td>";
                            }
                                
                            else{
                                echo "<td>Não</td>";
                            }
                            echo "<td>";

                            // link para editar uma Alternativa
                            echo "<a href='editaAlternativa.php?questao_id={$questao_id}&id={$umAlternativas->getId()}'><i class=\"bi bi-pencil-square\"></i></a>";

                            // link para excluir uma Alternativa
                            echo "<a href='excluiAlternativa.php?questao_id={$questao_id}&id={$umAlternativas->getId()}' onclick=\"return confirm('Quer mesmo excluir?');\">
                            <i class=\"bi bi-trash3\"></i>
                            </a>";

                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>Não foram encontrados registros";
                }
            ?>
    </section>
    
<?php	
   include_once "footer.php";
?>