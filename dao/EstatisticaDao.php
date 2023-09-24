<?php
interface EstatisticaDao {

    public function getTotal($tabela);

    public function getTotalAprovacao();

    public function getCalculaPorcentagem($total, $valor);

    public function getDadosQuestionariosRespondidos();

    public function getDadosQuestionariosAprovados();

}
?>