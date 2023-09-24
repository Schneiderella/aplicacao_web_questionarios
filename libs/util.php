<?php

function verificaParametros ($dados, $parametros) {

    foreach($parametros as $parametro) {
        if (!array_key_exists($parametro, $dados) || empty($dados[$parametro])){
            echo "Não enviou " . $parametro . " na requisição";
            return false;
        }
    }

    return true;
}

function toJSON($dados) {

    if (!isset($dados)) {
        return;
    }

    $dadosJSON = array();

    if (is_array($dados)) { //Verifica se é array

        foreach($dados as $dado) {
            $dadosJSON[] = $dado->getDadosParaJSON(); //Precisa ter o metodo implementada na classe do dado
        }
    } else {
        $dadosJSON[] = $dados->getDadosParaJSON(); //Precisa ter o metodo implementado na classe do dado
    }

    return stripslashes(json_encode($dadosJSON,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}


?>