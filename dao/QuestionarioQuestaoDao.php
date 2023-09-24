<?php
interface QuestionarioQuestaoDao {

    public function insere($questionarioQuestao, $questionario_id);
    public function altera($questionarioQuestao, $questionario_id);
    public function remove($questionarioQuestao);
    public function buscaTodos();
    public function buscaPorID($id);
}
?>