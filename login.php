<?php
include_once "fachada.php";
$titulo_pagina = "Login";
include_once "header.php";

?>

<div class="modal modal-sheet position-static d-block p-4 py-md-5" tabindex="-1" role="dialog" id="modalSignin">
    <div class="modal-dialog" role="document">         
        <div class="col-12 text-center">
            <h1><img src="../aplicacao_web_questionarios/libs/img/Qlogo.png" style="max-width:80px" alt="Quest logo"></h1>
        </div>
        <div class="modal-content shadow">   
            <section class="col-10 m-auto py-3">
                <br>
                <form action="executa_login.php" method="POST" role="form">
                    <h1 class="fw-bold mb-0 fs-2"><?=$titulo_pagina?></h1>
                    <p><h6>Entre com seus dados de acesso</h6></p>
                    <div class="mb-3 d-flex">                        
                        <input type="text" class="form-control" id="login" name="login" placeholder="Login" aria-describedby="Login" />
                    </div>
                    <div class="mb-3 d-flex">                    
                        <input type="password" class="form-control" id="senha" name="senha" placeholder="Sua senha" aria-describedby="senha" />
                    </div>
                    <div class="col-12 text-center">            
                        <button class="w-100 btn btn-primary" type="submit">Entrar</button>                        
                        <small><a href="#" class="link-underline-light">Esqueceu sua senha?</a></small>        
                        <hr>
                    </div>                    
                </form>
                <div class="mb-3 d-flex">
                    <p class="text-body-secondary">Não possui uma conta?
                    <a class="btn btn-sm btn-outline-primary" href="cadastroRespondente.php">Criar conta</a>
                    </p>
                </div>
            </section>
        </div>
    </div>
</div>
<?php
// layout do rodapé
include_once "footer.php";
?>



