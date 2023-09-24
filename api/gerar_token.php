<?php

function getToken($login, $senha, $tipo) {
        $token = false;

        // Cria header
        $header = base64_encode(json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT'
        ]));

        // Duração do token
        $duracao = time() + (30 * 60);

        // Cria payload
        $payload = base64_encode(json_encode([
            'login' => $login,
            'exp' => $duracao,
            'password' => $senha,
            'tipo' => $tipo
        ]));

        // Cria signature
        $chave = 'eyJsb2dpbiI6ImJlYSIsImV4cCI6MTY4ODMyMzk4OH0';
       
        $signature = base64_encode(
            hash_hmac('sha256', "$header.$payload", $chave, true));

        // Gera Token
        $token = json_encode(['token'=>"$header.$payload.$signature"]);

        return $token;
    }
?>

