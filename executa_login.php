<?php 
// Métodos de acesso ao banco de dados 
require "fachada.php"; 
 
// Inicia sessão 
session_start();

// Recupera o login 
$login = isset($_POST['login']) ? addslashes(trim($_POST['login'])) : FALSE; 
// Recupera a senha, a criptografando em MD5 
$senha = isset($_POST['senha']) ? md5(trim($_POST['senha'])) : FALSE;
 
// Usuário não forneceu a senha ou o login 
if(!$login || !$senha) 
{ 
    echo 'login = ' . $login . ' / senha = ' . $senha . '<br>';
    echo 'Você deve digitar sua senha e login!<br>'; 
    echo '<a href="login.php">Efetuar Login</a>';
    exit; 
}  

$respondenteDao = $factory->getRespondenteDao();
$elaboradorDao = $factory->getElaboradorDao();

$respondente = $respondenteDao->buscaPorLogin($login);
$elaborador = $elaboradorDao->buscaPorLogin($login);

$problemas = FALSE;
$usuario = null;
$tipo = null;

if (isset($respondente)){

    $usuario = $respondente;
    $tipo = 2;

} elseif (isset($elaborador)){

    $usuario = $elaborador;
    $tipo = $usuario->getIsAdmin() ? 0 : 1;

} else {
    $problemas = TRUE;
}

if(!$problemas && isset($usuario)) {

    // Agora verifica a senha 
    if(!strcmp($senha, $usuario->getSenha())) { 
        // TUDO OK! Agora, passa os dados para a sessão e redireciona o usuário 
        $_SESSION['id']= $usuario->getId(); 
        $_SESSION['nome'] = stripslashes($usuario->getNome()); 
        $_SESSION['tipo']= $tipo; // 0 - admin  1 - elaborador  2 - respondente
        header('Location: index.php'); 
        exit; 
    } else {
        $problemas = TRUE; 
    }

} else {
    $problemas = TRUE; 
}

if($problemas==TRUE) {
    header('Location: index.php'); 
    exit; 
}
?>
