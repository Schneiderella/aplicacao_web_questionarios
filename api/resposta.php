<?php

require "../fachada.php";
include_once "validar_token.php";

$dao = $factory->getSubmissaoRespostaDao();

$request_method=$_SERVER["REQUEST_METHOD"];


if(!$token_valido){
    echo "Token inválido";
    http_response_code(401); // 401 Unauthorized
    exit();
}

switch($request_method) {
    case 'GET':

        // Busca respostas de uma submissao
        if(!empty($_GET["submissao_id"])) {
            $id=intval($_GET["submissao_id"]);
            $respostasJSON = toJSON($dao->buscaRespostasPorId($id));
            if($respostasJSON!=null) {
                echo $respostasJSON;
                http_response_code(200); // 200 OK
            } else {
                http_response_code(404); // 404 Not Found
            }
        } else {
            http_response_code(400); //Bad Request
            break;
        }

        break;

    default:
        http_response_code(405); //Method not allowed
        break;
}




?>