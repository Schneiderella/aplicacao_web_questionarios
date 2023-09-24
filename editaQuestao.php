<?php
include_once "fachada.php";

$questao_id = @$_GET["questao_id"];

$questao_dao = $factory->getQuestaoDao();
$questao = $questao_dao->buscaPorID($questao_id);

if($questao==null) {
    $questao = new Questao(null, null, null, null, null);
}

$tipo_resposta = $questao->getTipo();
$alternativas =  $questao->getAlternativas();


$titulo_pagina = "Cadastro de Questão";
include_once "header.php";

?>
    <section class="col-12 col-sm-8 col-lg-6 m-auto">
        <div class="pt-3">
            <h3>Cadastro de questão</h3>
        </div>

        <form action="salvaQuestao.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <?php 
                if($questao->getId()) {
                    echo "<input class=\"form-control\" type=hidden \"hidden\" value=\"". $questao->getId() ."\" name=\"id\" readonly />";
                    }
                ?>
                <br>
                <div class="mb-3">
                    <label class="form-label" for="nome">Descrição*:</label>
                    <input class="form-control" maxlength="255" type= "text" value="<?=$questao->getDescricao()?>" name="descricao"/>
                </div>

                <legend>Tipo de resposta*</legend><br>
                <fieldset class="mb-3 d-grid gap-2 d-md-flex justify-content-md-between">
                    <div>
                        <input type="radio" id="discursiva" name="tipo_resposta"  value="discursiva" <?php if($questao->getIsDiscursiva()){echo "checked";}?>>
                        <label for="discursiva">Discursiva</label><br>
                    </div>
                    <div>                    
                        <input type="radio" id="objetiva" name="tipo_resposta" value="objetiva" <?php if($questao->getIsObjetiva()){echo "checked";}?>>
                        <label for="objetiva">Objetiva</label><br>
                    </div>
                    <div>
                        <input type="radio" id="multiplaEscolha" name="tipo_resposta" value="multiplaEscolha" <?php if($questao->getIsMultiplaEscolha()){echo "checked";}?>>
                        <label for="multiplaEscolha">Multipla Escolha</label>
                    </div>
                </fieldset><br>

                <div class="mb-3">
                    <label for="imagem" class="form-label">Imagem explicativa (opcional): </label>
                    <input class="form-control" type="<?=$questao->getImagem() == null ? "hidden" : "text"?>" disabled value="<?="Imagem selecionada: " . $questao->getImagem()?>">
                    <input class="form-control" type="file" accept="image/*" name="imagem" id="imagem">
                </div>
            </div><br><br>
            
            <div class="d-grid gap-2 mb-3 d-md-flex justify-content-md-end">
                <input class="btn btn-primary" type="submit" value="Salvar"/>
                <input id="add_alternativa" name="add_alternativa" class="btn btn-primary" type="submit" value="Adicionar Alternativa"/>
                <a class="btn btn-secondary" href="questao.php">Voltar</a>
            </div>
        
        </form>
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
        <br>
    </section>
    
<?php	
   include_once "footer.php";
?>


<script>

let discursiva = $('#discursiva');
let objetiva = $('#objetiva');
let multiplaEscolha = $('#multiplaEscolha');

$(document).ready(function(){
    if (discursiva.is(':checked')){
        $('#add_alternativa').hide()
    }
});

$(objetiva).click(function(){
    if((objetiva).is(':checked'))
    $('#add_alternativa').show()
});

$(multiplaEscolha).click(function(){
    if((multiplaEscolha).is(':checked'))
    $('#add_alternativa').show()
});

$(discursiva).click(function(){
    if((discursiva).is(':checked'))
    $('#add_alternativa').hide()
});

</script>