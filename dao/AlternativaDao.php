<?php
interface AlternativaDao {

    public function insere($alternativa, $questao_id);
    public function altera($alternativa);
    public function buscaPorId($id);
    public function remove($alternativa);
}
?>