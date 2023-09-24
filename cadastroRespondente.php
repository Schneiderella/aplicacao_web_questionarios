<?php
include_once "fachada.php";
$titulo_pagina = "Cadastro respondente";
include_once "header.php";

$id = @$_GET["id"]; //hey

$dao = $factory->getRespondenteDao();
$respondente = $dao->buscaPorID($id);

if(!isset($respondente)) {
    $titulo = "Cadastre-se";
    $respondente = new Respondente(null, null, null, null, null, null, null);
} else {
    $titulo = $respondente->getLogin();
}

?>
    <section class="col-8 m-auto py-3">
        <h2 class="text-center p-2"><?=$titulo?></h2>
        <form action="salvaRespondente.php" method="post">

            <input type="hidden" id="id" name="id" value="<?=$respondente->getId()?>" />

            <div class="mb-3 d-flex">
                <label for="nome" class='col-2 form-label align-self-center'>Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" aria-describedby="Nome" value="<?=$respondente->getNome()?>" required/>
            </div>

            <div class="mb-3 d-flex">
                <label for="cpf" class='col-2 form-label align-self-center'>Cpf:</label>
                <input type="text" class="form-control" id="cpf" name="cpf" aria-describedby="cpf" value="<?=$respondente->getCpf()?>" required/>
            </div>

            <div class="mb-3 d-flex">
                <label for="email" class='col-2 form-label align-self-center'>Email:</label>
                <input type="text" class="form-control" id="email" name="email" aria-describedby="email" value="<?=$respondente->getEmail()?>" required/>
            </div>

            <div class="mb-3 d-flex">
                <label for="telefone" class='col-2 form-label align-self-center'>Telefone:</label>
                <input type="text" class="form-control" id="telefone" name="telefone" aria-describedby="telefone" value="<?=$respondente->getTelefone()?>" required/>
            </div>

            <div class="mb-3 d-flex">
                <label for="login" class='col-2 form-label align-self-center'>Login:</label>
                <input type="text" class="form-control" id="login" name="login" aria-describedby="login" value="<?=$respondente->getLogin()?>" required/>
            </div>

            <div class="mb-3 d-flex">
                <label for="senha" class='col-2 form-label align-self-center'>Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" aria-describedby="senha" value="<?=$respondente->getSenha()?>" required/>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <input class="btn btn-primary" type= "submit" value="Salvar"/>
                <a class="btn btn-secondary" href="index.php">Voltar</a>
            </div>

        </form>
    </section>
<?php

include_once "footer.php";
?>