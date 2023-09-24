<?php
interface QuestionarioDao {

    public function insere($questionario);
    public function altera($questionario);
    public function remove($questionario);
    public function buscaTodos();
    public function buscaPorID($id);
    public function buscaPorNome($nome, $limite, $pagina, $id);
    public function contaPorNome($nome);
    public function buscaPorElaborador($elaboradorId, $nome, $limite, $pagina);
    public function contaPorElaborador($elaboradorId, $nome);

}
?>