<?php

require "../fachada.php";
include "gerar_token.php";

$elaboradorDao = $factory->getElaboradorDao();
$respondenteDao = $factory->getRespondenteDao();

$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method) {
    
    case 'POST':

        
        //Validação todos campos informados
        $data = json_decode(file_get_contents('php://input'), true);
        $paramsOk = verificaParametros($data, ["login", "senha"]);

        if (!$paramsOk) {
            http_response_code(400); //Bad Request
            break;
        }

        // Recupera o login 
        $login = isset($data["login"]) ? addslashes(trim($data["login"])) : FALSE; 
        
        // Recupera a senha, a criptografando em MD5 
        $senha = isset($data["senha"]) ? md5(trim($data["senha"])) : FALSE;


        // Usuário não forneceu a senha ou o login 
        if(!$login || !$senha) 
        { 
            echo "Credenciais incorretas";
            http_response_code(400); //Bad Request
            break;
        }

        $elaborador = $elaboradorDao->buscaPorLogin($login);
        $respondente = $respondenteDao->buscaPorLogin($login);

        $problemas = FALSE;
        $usuario = null;
        $tipo = null;

        if (isset($respondente)){

            $usuario = $respondente;
            $tipo = 2;
            $dao = $respondenteDao;
        
        } elseif (isset($elaborador)){
            $usuario = $elaborador;
            $tipo = 1;
            $dao = $elaboradorDao;
        
        } else {
            $problemas = TRUE;
        }

        if(!$problemas && isset($usuario)) {
            if(!strcmp($senha, $usuario->getSenha())) { 

                $token =  getToken($login, $senha, $tipo);
                echo $token;
                http_response_code(200); //200 ok
                break;
            }else{
                echo "Credenciais incorretas";
                http_response_code(400); //Bad Request
                break;
            }
        }

        http_response_code(201); // 201 Created
        break;

    default:
        http_response_code(405); //Method not allowed
        break;
}

?>