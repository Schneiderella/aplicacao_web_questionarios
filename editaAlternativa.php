<?php
include_once "fachada.php";
$titulo_pagina = "Cadastro alternativa";
include_once "header.php";

$questao_id = @$_GET["questao_id"];
$id= @$_GET["id"];

$alternaviva_dao = $factory->getAlternativaDao();
$alternativa = $alternaviva_dao->buscaPorId($id);

if(!isset($alternativa)) {
    $titulo = "Cadastrar Alternativa";
    $alternativa = new Alternativa(null, null, null);
} else {
    $titulo = "Editar Alternativa";
}

?>
    <section class="col-8 m-auto py-3">
        <h2 class="text-center p-2"><?=$titulo?></h2>

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
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <input class="btn btn-primary" type= "submit" value="Salvar"/>
                <a class="btn btn-secondary" href="alternativa.php?questao_id=<?=$questao_id?>">Voltar</a>
            </div>
        </form>
    </section>
    
<?php	
   include_once "footer.php";
?>