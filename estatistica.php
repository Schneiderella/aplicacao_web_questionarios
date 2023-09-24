<?php
include_once "fachada.php";
include_once "comum.php";
$titulo_pagina = "Estatística";
include_once "header.php";

if (is_session_started() === FALSE) {
  session_start();
}

$usuarioId = null;
$tipoUsuario = null;
if (isset($_SESSION['id'])) {
  $usuarioId = $_SESSION['id'];
  $tipoUsuario = $_SESSION['tipo'];
}

$ofertaDao = $factory->getOfertaDao();
echo "<input type='hidden' id='usuario-id' value='" . $usuarioId . "' />";
echo "<input type='hidden' id='tipo-usuario' value='" . $tipoUsuario . "' />";
$dao = $factory->getEstatisticaDao();
$totalQuestionarios = $dao->getTotal("questionario");
$totalRespondentes = $dao->getTotal("respondente");
$totalOfertas = $dao->getTotal("oferta");
$totalQuestionariosRespondidos = $dao->getTotal("submissao");
$totalAprovacao = $dao->getTotalAprovacao();
$dadosOfertas = $dao->getDadosOfertas();
$dadosQuestionariosRespondidos = $dao->getDadosQuestionariosRespondidos();
$dadosQuestionariosAprovados = $dao->getDadosQuestionariosAprovados();
$ofertados = $dadosOfertas;
$aprovados = $dadosQuestionariosAprovados;
$porcentagemAprovacao = $dao->getCalculaPorcentagemPorOferta($ofertados, $aprovados);

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<section class='px-5'>
  <div class='row justify-content-between'>
    <div class='pt-3'>
      <h3>Estatísticas</h3><br>
    </div>
    </br>
    <div class id='lista-estatisticas'>
      <div class='row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3'>
        <div>
          <article class='card text-center mx-2 mb-2'>
            <div class='card-body'>
              <h5 class='card-title'>Questionários Respondidos</h5>
              <p class='d-block small opacity-50'><?= $totalQuestionariosRespondidos ?> questionários respondidos -
                <?= number_format($dao->getCalculaPorcentagem($totalOfertas, $totalQuestionariosRespondidos), 2, '.', '') ?>%
              </p>
              <div class="accordion" id="accordionExample">
                <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#accordionParentt">
                  <br />
                  <div class="d-grid gap-2 d-md-flex justify-content-md-center" style="width:100%">
                    <canvas class="chartjs-render-monitor" id="myChart2" style="width:90%"></canvas>
                  </div>
                  <br />
                </div>
              </div>
              <button id='grafico2' type="button" class="btn btn-primary" data-bs-toggle="collapse"
                data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2" onclick="collapseBtn(2)">
                <i class="bi bi-graph-up"></i>
              </button>
            </div>
          </article>
        </div>
        <div>
          <article class='card text-center mx-2 mb-2'>
            <div class='card-body'>
              <h5 class='card-title'>Ofertas por Questionário</h5>
              <p class='d-block small opacity-50'><?= $totalQuestionarios ?> questionários</p>
              <div class="accordion" id="accordionExample">
                <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#accordionParentt">
                  <br />
                  <div class="d-grid gap-2 d-md-flex justify-content-md-center" style="width:100%">
                    <canvas class="chartjs-render-monitor" id="myChart1" style="width:90%"></canvas>
                  </div>
                  <br />
                </div>
              </div>
              <button id='grafico1' type="button" class="btn btn-primary" data-bs-toggle="collapse"
                data-bs-target="#collapse1" aria-expanded="false" aria-controls="collapse1" onclick="collapseBtn(1)">
                <i class="bi bi-graph-up"></i>
              </button>
            </div>
          </article>
        </div>
        <div>
          <article class='card text-center mx-2 mb-2'>
            <div class='card-body'>
              <h5 class='card-title'>Qtd de Aprovações por Questionário</h5>
              <p class='d-block small opacity-50'>Total <?= $totalAprovacao ?> aprovações</p>
              <div class="accordion" id="accordionExample">
                <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#accordionParentt">
                  <br />
                  <div class="d-grid gap-2 d-md-flex justify-content-md-center" style="width:100%">
                    <canvas class="chartjs-render-monitor" id="myChart3" style="width:90%"></canvas>
                  </div>
                  <br />
                </div>
              </div>
              <button id='grafico3' type="button" class="btn btn-primary" data-bs-toggle="collapse"
                data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3" onclick="collapseBtn(3)">
                <i class="bi bi-graph-up"></i>
              </button>
            </div>
          </article>
        </div>
        <div>
          <article class='card text-center mx-2 mb-2'>
            <div class='card-body'>
              <h5 class='card-title'>Ofertas por Questionário</h5>
              <p class='d-block small opacity-50'><?= $totalOfertas ?> ofertas em <?= $totalQuestionarios ?>
                questionários</p>
              <div class="accordion" id="accordionExample">
                <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#accordionParentt">
                  <br />
                  <div class="d-grid gap-2 d-md-flex justify-content-md-center" style="width:100%">
                    <canvas class="chartjs-render-monitor" id="myChart4" style="width:90%"></canvas>
                  </div>
                  <br />
                </div>
              </div>
              <button id='grafico4' type="button" class="btn btn-primary" data-bs-toggle="collapse"
                data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4" onclick="collapseBtn(4)">
                <i class="bi bi-graph-up"></i>
              </button>
            </div>
          </article>
        </div>
        <div>
          <article class='card text-center mx-2 mb-2'>
            <div class='card-body'>
              <h5 class='card-title'>Aprovações por Oferta</h5>
              <p class='d-block small opacity-50'><?= $totalOfertas ?> ofertas</p>
              <div class="accordion" id="accordionExample">
                <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#accordionParentt">
                  <br />
                  <div class="d-grid gap-2 d-md-flex justify-content-md-center" style="width:100%">
                    <canvas class="chartjs-render-monitor" id="myChart5" style="width:90%"></canvas>
                  </div>
                  <br />
                </div>
              </div>
              <button id='grafico5' type="button" class="btn btn-primary" data-bs-toggle="collapse"
                data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5" onclick="collapseBtn(5)">
                <i class="bi bi-graph-up"></i>
              </button>
            </div>
          </article>
        </div>
        <div>
          <article class='card text-center mx-2 mb-2'>
            <div class='card-body'>
              <h5 class='card-title'>% Aprovação por Questionário</h5>
              <p class='d-block small opacity-50'>
                <?= number_format($dao->getCalculaPorcentagem($totalQuestionariosRespondidos, $totalAprovacao), 2, '.', '') ?>%
                de aprovação para <?= $totalRespondentes ?> respondentes</p>
              <div class="accordion" id="accordionExample">
                <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#accordionParentt">
                  <br />
                  <div class="d-grid gap-2 d-md-flex justify-content-md-center" style="width:100%">
                    <canvas class="chartjs-render-monitor" id="myChart6" style="width:90%"></canvas>
                  </div>
                  <br />
                </div>
              </div>
              <button id='grafico6' type="button" class="btn btn-primary" data-bs-toggle="collapse"
                data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6" onclick="collapseBtn(6)">
                <i class="bi bi-graph-up"></i>
              </button>
            </div>
          </article>
        </div>
      </div>
      <script>
        function collapseBtn(grafico) {
          $('#collapse' + grafico).toggleClass('show')
        }
      </script>
    </div>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
      <a class="btn btn-secondary" href="index.php">Voltar</a>
    </div><br />
</section>
<br />


<script>
  function mostrarGrafico(idCanvas, xValues, yValues, tensaoLinha, barColors, modeloGrafico, labels, borderColors) {
    new Chart(idCanvas, {
      type: modeloGrafico,
      data: {
        labels: xValues,
        datasets: [{
          label: labels,
          backgroundColor: barColors,
          data: yValues,
          lineTension: tensaoLinha,
          borderColor: borderColors,
          borderWidth: 2,
          pointBackgroundColor: 'rgba(254, 228, 83, 0.2)'
        }]
      },
      options: {
        animation: {
          duration: 2000
        },
        title: {
          display: true,
          text: 'Questionários'
        },
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          }
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }],
        }
      },
    });
  };
  var xValues = ["Respondidos", "Não Respondidos"];
  var yValues = [<?php echo json_encode($totalQuestionariosRespondidos) ?>, <?php echo json_encode($totalOfertas - $totalQuestionariosRespondidos) ?>];
  var barColors = ['rgba(0, 128, 128, 0.7)', 'rgba(128, 128, 255, 0.7)'];
  var borderColors = ['rgb(255, 255, 255)', 'rgb(255, 255, 255)'];
  new Chart("myChart2", {
    type: "doughnut",
    data: {
      labels: xValues,
      datasets: [{
        backgroundColor: barColors,
        borderColor: borderColors,
        data: yValues
      }]
    },
    options: {
      title: {
        display: true,
        text: "Questionários"
      }
    }
  });
  $(document).ready(function () {
    var ctx = $("#myChart1");
    var xValues = <?php echo json_encode($dadosOfertas[0]) ?>;
    var yValues = <?php echo json_encode($dadosOfertas[1]) ?>;
    var barColors = [
      'rgba(0, 255, 255, 0.7)',
      'rgba(128, 128, 255, 0.7)',
      'rgba(0, 128, 128, 0.7)',
      'rgba(0, 128, 255, 0.7)',
      'rgba(128, 0, 128, 0.7)'
    ];
    var borderColors = [
      'rgb(0, 255, 255)',
      'rgb(128, 128, 255)',
      'rgb(0, 128, 128)',
      'rgb(0, 128, 255)',
      'rgb(128, 0, 128)'
    ];
    var modeloGrafico = "bar";
    var tensaoLinha = 0;
    var labels = "Ofertas por questionário";
    //var modeloGrafico = "line";
    //var modeloGrafico = "";
    mostrarGrafico(ctx, xValues, yValues, tensaoLinha, barColors, modeloGrafico, labels, borderColors);
  });
  $(document).ready(function () {
    var ctx = $("#myChart5");
    var xValues = <?php echo json_encode($dadosOfertas[0]) ?>;
    var yValues = <?php echo json_encode($dadosOfertas[1]) ?>;
    var barColors = [
      'rgba(0, 255, 255, 0.7)',
      'rgba(128, 128, 255, 0.7)',
      'rgba(0, 128, 128, 0.7)',
      'rgba(0, 128, 255, 0.7)',
      'rgba(128, 0, 128, 0.7)'
    ];
    var borderColors = [
      'rgb(0, 255, 255)',
      'rgb(128, 128, 255)',
      'rgb(0, 128, 128)',
      'rgb(0, 128, 255)',
      'rgb(128, 0, 128)'
    ];
    var modeloGrafico = "pie";
    var tensaoLinha = 0.2;
    var labels = <?php echo json_encode($dadosOfertas[0]) ?>;
    //var modeloGrafico = "line";
    //var modeloGrafico = "";
    mostrarGrafico(ctx, xValues, yValues, tensaoLinha, barColors, modeloGrafico, labels, borderColors);
  });
  $(document).ready(function () {
    var ctx = $("#myChart4");
    var xValues = <?php echo json_encode($dadosOfertas[0]) ?>;
    var yValues = <?php echo json_encode($dadosOfertas[1]) ?>;
    var barColors = 'rgba(128, 0, 128, 0.7)';
    var borderColors = 'rgb(128, 0, 128)';
    var modeloGrafico = "line";
    var tensaoLinha = 0.2;
    var labels = "Ofertas";
    //var modeloGrafico = "line";
    //var modeloGrafico = "";
    mostrarGrafico(ctx, xValues, yValues, tensaoLinha, barColors, modeloGrafico, labels, borderColors);
  });
  $(document).ready(function () {
    var ctx = $("#myChart3");
    var xValues = <?php echo json_encode($dadosQuestionariosAprovados[0]) ?>;
    var yValues = <?php echo json_encode($dadosQuestionariosAprovados[1]) ?>;
    var barColors = 'rgba(128, 128, 255, 0.7)';
    var borderColors = 'rgb(128, 128, 255)';
    var modeloGrafico = "line";
    var tensaoLinha = 0.2;
    var labels = "Aprovados";
    mostrarGrafico(ctx, xValues, yValues, tensaoLinha, barColors, modeloGrafico, labels, borderColors);
  });
  $(document).ready(function () {
    var ctx = $("#myChart6");
    var xValues = <?php echo json_encode($dadosOfertas[0]) ?>;
    var yValues = <?php echo json_encode($porcentagemAprovacao) ?>;
    var barColors = [
      'rgba(0, 255, 255, 0.7)',
      'rgba(128, 128, 255, 0.7)',
      'rgba(0, 128, 128, 0.7)',
      'rgba(0, 128, 255, 0.7)',
      'rgba(128, 0, 128, 0.7)'
    ];
    var borderColors = [
      'rgb(0, 255, 255)',
      'rgb(128, 128, 255)',
      'rgb(0, 128, 128)',
      'rgb(0, 128, 255)',
      'rgb(128, 0, 128)'
    ];
    var modeloGrafico = "polarArea";
    var tensaoLinha = 0.2;
    var labels = xValues;
    mostrarGrafico(ctx, xValues, yValues, tensaoLinha, barColors, modeloGrafico, labels, borderColors);
  });
</script>

<?php
include_once "footer.php"
  ?>