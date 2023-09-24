<?php
interface ElaboradorDao {
    
    public function insere($elaborador);
    public function altera($elaborador);
    public function removePorId($id);
    public function buscaTodos();
    public function buscaPorID($id);
    public function buscaPorLogin($login);
    public function buscaPorCpf($cpf);
}
?>