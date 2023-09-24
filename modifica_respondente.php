<?php
include_once "fachada.php";
$titulo_pagina = "Alteração de Usuário Respondente";
include_once "header.php";
include "verifica.php";

$id = @$_GET["id"];

$dao = $factory->getRespondenteDao();
$respondente = $dao->buscaPorId($id); //  testar buscaPorID

// layout do cabeçalho

// !importante! ajustar campos necessários de acordo com atributos de respondente



include_once "header.php";
 ?>
 <section>
<form action="altera_respondente.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
         <tr>
            <td>Login</td>
            <td><input type='text' name='login' value='<?php echo $respondente->getLogin();?>' class='form-control' /></td>
        </tr>
        <tr>
            <td>Nome</td>
            <td><input type='text' name='nome' value='<?php echo $respondente->getNome();?>'class='form-control' /></td>
        </tr>
        <tr>
            <td>Senha</td>
            <td><input type='password' name='senha' value='<?php echo $respondente->getSenha();?>' class='form-control' /></td>
        </tr>
        <tr>
            <td>
            </td>
            <td>
                <button type="submit" class="btn btn-primary">Alterar</button>
                <a href='usuarios.php' class='btn btn-primary left-margin'>Cancela</a>
            </td>
        </tr>
    </table>
    <input type='hidden' name='id' value='<?php echo $respondente->getId();?>'/>
</form>
</section>
<?php
// layout do rodapé
include_once "footer.php";
?>


