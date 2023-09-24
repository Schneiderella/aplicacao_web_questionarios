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

        // Busca questionarios respondidos
        if(!empty($_GET["respondente_id"])) {
            $id=intval($_GET["respondente_id"]);

            $submissoes = $dao->buscaPorRespondente($id);
            if($submissoes){
                foreach($submissoes as $submissao) {
                    $quest = $submissao->getOferta()->getQuestionario();
                    $questionarios[$quest->getId()] = $quest; 
                }

                $questionariosJSON = toJSON($questionarios);
                if($questionariosJSON!=null) {
                    echo $questionariosJSON;
                    http_response_code(200); // 200 OK
                } else {
                    http_response_code(404); // 404 Not Found
                }
            }else {
                echo 'Nenhum resultado encontrado';
                break;
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