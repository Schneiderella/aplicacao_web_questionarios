<?php
interface OfertaDao {

    public function insere($oferta);
    public function altera($oferta);
    public function buscaTodos();
    public function buscaPorId($id);
    public function buscaPorQuestionario($questionario);
    public function buscaPorRespondente($respondente, $limit, $start, $query);
    public function contaRegistros($respondente, $descricao);
    public function remove($oferta);
}
?>