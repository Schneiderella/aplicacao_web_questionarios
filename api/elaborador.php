<?php

require "../fachada.php";
include_once "validar_token.php";

$dao = $factory->getElaboradorDao();

$request_method=$_SERVER["REQUEST_METHOD"];

if(!$token_valido){
    echo "Token inválido";
    http_response_code(401); // 401 Unauthorized
    exit();
}

switch($request_method) {
    case 'GET':

        // Busca um elaborador
        if(!empty($_GET["id"])) {
            $id=intval($_GET["id"]);
            $elaboradorJSON = toJSON($dao->buscaPorID($id));
            if($elaboradorJSON!=null) {
                echo $elaboradorJSON;
                http_response_code(200); // 200 OK
            } else {
                http_response_code(404); // 404 Not Found
            }
        } else { //Busca todos os respondentes
            echo toJSON($dao->buscaTodos());
            http_response_code(200); // 200 OK
        }

        break;
    case 'POST':

        // insere um elaborador
        $data = json_decode(file_get_contents('php://input'), true);
        
        //Validação todos campos informados
        $paramsOk = verificaParametros($data, ["login", "senha", "nome", "email", "cpf", "instituicao", "is_admin"]);

        if (!$paramsOk) {
            http_response_code(400); //Bad Request
            break;
        }

        $login = $data["login"];
        $senha = $data["senha"];
        $nome = $data["nome"];
        $email = $data["email"];
        $cpf = $data["cpf"];
        $instituicao = $data["instituicao"];
        $is_admin = $data["is_admin"];

        //Validação cpf
        if (strlen($cpf) < 11) {
            echo "Cpf informado é inválido";
            http_response_code(400); //Bad Request
            break;
        }

        //Validação login já cadastrado
        $buscaPorLogin = $dao->buscaPorLogin($login);
        if (isset($buscaPorLogin) && $buscaPorLogin->getId() != null) {
            echo "Login já cadastrado";
            http_response_code(400); //Bad Request
            break;
        }

        //Validação cpf já cadastrado
        $buscaPorCpf = $dao->buscaPorCpf($cpf);
        if (isset($buscaPorCpf) && $buscaPorCpf->getId() != null) {
            echo "CPF já cadastrado";
            http_response_code(400); //Bad Request
            break;
        }

        $elaborador = new Elaborador(null, $login, $senha, $nome, $email, $cpf, $instituicao, $is_admin);
        $dao->insere($elaborador);
        http_response_code(201); // 201 Created
    
        break;
    case 'PUT':

        if(!empty($_GET["id"])) {
            $id=intval($_GET["id"]);
            $elaborador = $dao->buscaPorId($id);
            if($elaborador!=null) {
                $data = json_decode(file_get_contents('php://input'), true);
                $elaborador->setLogin($data["login"]);
                $elaborador->setSenha($data["senha"]);
                $elaborador->setNome($data["nome"]);
                $elaborador->setEmail($data["email"]);
                $elaborador->setCpf($data["cpf"]);
                $elaborador->setInstituicao($data["instituicao"]);
                $elaborador->setIsAdmin($data["is_admin"]);

                $dao->altera($elaborador);
                http_response_code(200); // 200 OK
            } else {
                http_response_code(404); // 404 Not Found
            }
        }

        break;
    case 'DELETE':

        if(!empty($_GET["id"])) {
            $id = intval($_GET["id"]);
            $dao->removePorId($id);
            http_response_code(204); // 204 Deleted
        }

        break;
    default:
        http_response_code(405); //Method not allowed
        break;
}

?>