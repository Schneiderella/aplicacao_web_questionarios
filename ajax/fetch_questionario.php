<?php

$nome = $_POST['query'];

include_once('../fachada.php');

$dao = $factory->getQuestionarioDao();

$limit = '5';
$page = 1;
$id = isset($_POST['id']) ? $_POST['id'] : null;
if ($_POST['page'] > 1) {
    $start = (($_POST['page'] - 1) * $limit);
    $page = $_POST['page'];
} else {
    $start = 0;
}

$questionarios = $dao->buscaPorNome($nome, $limit, $start, $id);
$total_data = $dao->contaPorNome($nome);

$output = "
<table class='table table-striped table-bordered'>
  <tr>
    <th></th>
    <th>Questionário</th>
  </tr>
";
if ($total_data > 0) {
    foreach ($questionarios as $questionario) {
        $output .= "
    <tr id='questionario-" . $questionario->getId() . "' class='row-selection'>";
      
      $output .= "
        <td>
            <input class='form-check-input' type='radio' name='questionario' " . ($questionario->getId() == $id ? "checked" : "") . " id='questionario" . $questionario->getId() . "' value=" . $questionario->getId() .">
        </td>";

    $output .= "<td class='id-questionario'><label for='questionario" . $questionario->getId() . "' class='form-check-label'>" . ucfirst($questionario->getNome()) . " - " . $questionario->getDescricao() . "</label></td></tr>
    ";
    }
} else {
    //Trocar para o template de erro
    $output .= "
    <tr>
        <td colspan='2' align='center'>Nenhum questionário encontrada</td>
    </tr>
  ";
}

$output .= "
</table>
";

include_once('paginacao.php');

echo $output;
