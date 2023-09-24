<?php

$nome = $_POST['query'];

include_once('../fachada.php');

$dao = $factory->getRespondenteDao();

$limit = '5';
$page = 1;
$id = isset($_POST['id']) ? $_POST['id'] : null;
if ($_POST['page'] > 1) {
    $start = (($_POST['page'] - 1) * $limit);
    $page = $_POST['page'];
} else {
    $start = 0;
}

$respondentes = $dao->buscaPorNome($nome, $limit, $start, $id);
$total_data = $dao->contaRegistros($nome);

$output = "
<table class='table table-striped table-bordered'>
  <tr>
    <th></th>
    <th>Respondente</th>
  </tr>
";
if ($total_data > 0) {
    foreach ($respondentes as $respondente) {
        $output .= "
    <tr id='respondente-" . $respondente->getId() . "' class='row-selection'>";
      
      $output .= "
        <td>
            <input class='form-check-input' type='radio' name='respondente' " . ($respondente->getId() == $id ? "checked" : "") . " id='respondente" . $respondente->getId() . "' value=" . $respondente->getId() .">
        </td>";

    $output .= "<td class='id-respondente'><label for='respondente" . $respondente->getId() . "' class='form-check-label'>" . ucfirst($respondente->getNome()) . "</label></td></tr>
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
