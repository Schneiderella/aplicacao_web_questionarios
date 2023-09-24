<?php
interface QuestaoDao {

    public function insere($questao);
    public function altera($questionario);
    public function buscaTodos();
    public function buscaPorID($id);
    public function buscaPorDesc($desc, $limite, $pagina, $id);
    public function remove($questionario);
}
?>