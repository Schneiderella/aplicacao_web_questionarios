<?php
include_once "fachada.php";
$titulo_pagina = "Inserção de usuário Respondente"; //teste
include_once "header.php";

?>
    <section class="col-8 m-auto py-3">
        <h2 class="text-center p-2"><?=$titulo?></h2>
        <form action="insere_respondente.php" method="get">

            <div class="mb-3 d-flex">
                <label for="nome" class='col-2 form-label align-self-center'>Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" aria-describedby="Nome" value="<?=$elaborador->getNome()?>"/>
            </div>

            <div class="mb-3 d-flex">
                <label for="cpf" class='col-2 form-label align-self-center'>Cpf:</label>
                <input type="text" class="form-control" id="cpf" name="cpf" aria-describedby="cpf" value="<?=$elaborador->getCpf()?>"/>
            </div>

            <div class="mb-3 d-flex">
                <label for="email" class='col-2 form-label align-self-center'>Email:</label>
                <input type="text" class="form-control" id="email" name="email" aria-describedby="email" value="<?=$elaborador->getEmail()?>"/>
            </div>

            <div class="mb-3 d-flex">
                <label for="instituicao" class='col-2 form-label align-self-center'>Instituição:</label>
                <input type="text" class="form-control" id="instituicao" name="instituicao" aria-describedby="instituicao" value="<?=$elaborador->getInstituicao()?>"/>
            </div>

            <div class="mb-3 d-flex">
                <label for="login" class='col-2 form-label align-self-center'>Login:</label>
                <input type="text" class="form-control" id="login" name="login" aria-describedby="login" value="<?=$elaborador->getLogin()?>"/>
            </div>

            <div class="mb-3 d-flex">
                <label for="senha" class='col-2 form-label align-self-center'>Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" aria-describedby="senha" value="<?=$elaborador->getSenha()?>"/>
            </div>

            <div class="mb-3 d-flex">
                <label for="is_admin" class='col-2 form-label align-self-center'></label>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin" aria-describedby="is_admin" value="false" <?php if($elaborador->getIsAdmin()){echo "checked";}?>/>
                    <label class="form-check-label">Conceder acesso como administrador.</label>
                </div>                   
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