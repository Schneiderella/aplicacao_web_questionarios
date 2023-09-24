<?php

require "../fachada.php";
include_once "validar_token.php";

$dao = $factory->getRespondenteDao();

$request_method=$_SERVER["REQUEST_METHOD"];

if(!$token_valido){
    echo "Token inválido";
    http_response_code(401); // 401 Unauthorized
    exit();
}

switch($request_method) {
    case 'GET':

        // Busca um respondente
        if(!empty($_GET["id"])) {
            $id=intval($_GET["id"]);
            $respondenteJSON = toJSON($dao->buscaPorID($id));
            if($respondenteJSON!=null) {
                echo $respondenteJSON;
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

        // insere um respondente
        $data = json_decode(file_get_contents('php://input'), true);

        //Validação todos campos informados
        $paramsOk = verificaParametros($data, ["login", "senha", "nome", "email", "cpf", "telefone"]);

        if (!$paramsOk) {
            http_response_code(400); //Bad Request
            break;
        }

        $login = $data["login"];
        $senha = $data["senha"];
        $nome = $data["nome"];
        $email = $data["email"];
        $cpf = $data["cpf"];
        $telefone = $data["telefone"];

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

        $respondente = new Respondente(null, $login, $senha, $nome, $email, $cpf, $telefone);
        $dao->insere($respondente);
        http_response_code(201); // 201 Created
    
        break;
    case 'PUT':

        if(!empty($_GET["id"])) {
            $id=intval($_GET["id"]);
            $respondente = $dao->buscaPorId($id);
            if($respondente!=null) {
                $data = json_decode(file_get_contents('php://input'), true);
                $respondente->setLogin($data["login"]);
                $respondente->setSenha($data["senha"]);
                $respondente->setNome($data["nome"]);
                $respondente->setEmail($data["email"]);
                $respondente->setCpf($data["cpf"]);
                $respondente->setTelefone($data["telefone"]);

                // var_dump($respondente);
                $dao->altera($respondente);
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