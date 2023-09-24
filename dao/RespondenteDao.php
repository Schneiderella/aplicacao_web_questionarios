<?php
interface RespondenteDao {
    public function insere($respondente);
    public function remove($respondente);
    public function removePorID($id);
    public function altera($respondente);
    public function buscaPorID($id);
    public function buscaPorLogin($login);
    public function buscaTodos();
    public function buscaPorCpf($cpf);
    public function buscaPorNome($nome, $limite, $pagina, $id);
    public function contaRegistros($nome);
}
?>