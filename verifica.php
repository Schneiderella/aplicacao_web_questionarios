<?php 
// Inicia sessões 
include_once "comum.php";
		
if ( is_session_started() === FALSE ) {
    session_start();
}

error_log("LOGIN");

// Verifica se existe os dados da sessão de login 
if(!isset($_SESSION["id"]) || !isset($_SESSION["nome"])) { 
    error_log("SEM USUÁRIO LOGADO - Vai para login.php");
    // Usuário não logado! Redireciona para a página de login 
    header("Location: login.php"); 
    exit; 
} 
?>