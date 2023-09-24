<?php
interface SubmissaoRespostaDao {

    public function insere($submissao);
    public function buscaPorId($submissaoId);
    public function buscaPorRespondente($respondenteId);
    public function buscaPorResultados($respondenteId, $questionarioId);
    public function totalPorRespondente($respondenteId);
    public function buscaRespostas($submissao);
    public function buscaRespostasPorId($submissaoId);
}
?>