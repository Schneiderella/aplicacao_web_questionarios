<?php

include_once "comum.php";

if ( is_session_started() === FALSE ) {
    session_start();
    if(isset($_SESSION["id"]) || isset($_SESSION["nome"])) {
        session_destroy();
        header("location: index.php");
        exit();
    } 
} 
?>