<?php

// Pega token da requisição
$headers = getallheaders();
if(empty($headers['Authorization'])){
    echo "Método de autorização ausente";
    http_response_code(400); //Bad Request
    exit();
}

$authorization_array = explode(' ', $headers['Authorization'] );
$token = $authorization_array[1];
$token_array = explode('.', $token);

// Pega dados do token
$header = $token_array[0];
$payload = $token_array[1];
$signature = $token_array[2];

$chave = 'eyJsb2dpbiI6ImJlYSIsImV4cCI6MTY4ODMyMzk4OH0';

$validar_assinatura = base64_encode(
    hash_hmac('sha256', "$header.$payload", $chave, true));


$token_valido = False;


if($signature == $validar_assinatura){
    
    $dados_token = json_decode(base64_decode($payload));
    $exp = $dados_token->exp;
    $login = $dados_token->login;
    $senha = $dados_token->password;
    $tipo = $dados_token->tipo;
 
    // verifica se o token ainda é válido
    if( $exp > time()){
        
        if($tipo === 1){
            $dao = $factory->getElaboradorDao();
        }
        elseif($tipo === 2){
            $dao = $factory->getRespondenteDao();
        }
        $usuario = $dao->buscaPorLogin($login);
        if ($usuario){
            if($usuario->getSenha() == $senha){
                $token_valido = true;
            }
        }
    
    }
}

?>