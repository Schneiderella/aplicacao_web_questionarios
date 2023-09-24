<?php

$nome = $_POST['query'];

include_once('../fachada.php');

$dao = $factory->getQuestaoDao();

$limit = '5';
$page = 1;
$id = isset($_POST['id']) ? $_POST['id'] : null;
if ($_POST['page'] > 1) {
    $start = (($_POST['page'] - 1) * $limit);
    $page = $_POST['page'];
} else {
    $start = 0;
}

$questoes = $dao->buscaPorDesc($nome, $limit, $start, $id);
$total_data = $dao->contaRegistros($nome);

$output = "
<table class='table table-striped table-bordered'>
  <tr>
    <th></th>
    <th>Questão</th>
    <th>Tipo</th>
  </tr>
";
if ($total_data > 0) {
    foreach ($questoes as $questao) {
        $output .= "
    <tr id='questao-" . $questao->getId() . "' class='row-selection'>";
      
      $output .= "
        <td>
            <input class='form-check-input' type='radio' name='questao_id' " . ($questao->getId() == $id ? "checked" : "") ." id='questao_id" . $questao->getId() . "' value=" . $questao->getId() .">
        </td>";

    $output .= "<td class='id-questao'><label for='questao_id" . $questao->getId() . "' class='form-check-label'>" . $questao->getDescricao() . "</label>
      <td><label for='questao_id" . $questao->getId() . "' class='form-check-label'>" . $questao->getTipo() . "</label></td>
    </td></tr>
    ";
    }
} else {
    $output .= "
    <tr>
        <td colspan='2' align='center'>Nenhuma questão encontrada</td>
    </tr>
  ";
}

$output .= "
</table>
";

include_once('paginacao.php');

echo $output;
