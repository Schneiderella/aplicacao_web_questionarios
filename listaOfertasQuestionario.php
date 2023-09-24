<?php
include_once "fachada.php";
include_once "comum.php";
$titulo_pagina = "Questionários disponíveis";
include_once "header.php";

if (is_session_started() === FALSE ) {
  session_start();
}

$usuarioId = null; // Obter id do usuario logado
$tipoUsuario = null; // Obter tipo do usuario logado

if(isset($_SESSION['id'])) {
    $usuarioId = $_SESSION['id'];
    $tipoUsuario = $_SESSION['tipo'];
}

$ofertaDao = $factory->getOfertaDao();
echo "<input type='hidden' id='usuario-id' value='" . $usuarioId . "' />";
echo "<input type='hidden' id='tipo-usuario' value='" . $tipoUsuario . "' />";
?>
<section class='px-5'>
  <div class="row justify-content-between">

    <div class='pt-3'>
      <h3>Questionários disponíveis</h3><br>
    </div>

      <!-- Select define tamanho da lista -->
      <div class='d-flex'>
        <label class='m-1' for="">Itens por página</label>
        <div class="col-1">
          <select class="form-select" id="limite" aria-label=".form-select-sm">
              <option class="dropdown-item" value="3">3</option>
              <option class="dropdown-item" value="6">6</option>
              <option class="dropdown-item" value="12">12</option>
          </select>
        </div>
          
      </div>
      <!-- Busca por questionario ofertados -->
      <div class="input-group justify-content-md-end">
          <div class="form-outline">
              <input type="search" class="form-control" name="busca" id="busca"  placeholder="Pesquisa" aria-label="Search" aria-describedby="search-addon" />
          </div>
          <button type="button" id="botao-busca" class="btn btn-primary">
          <i class="bi bi-search"></i>
          </button>
      </div>
  </div><br>

    <div class="" id="lista-resultado"></div>

  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
      <a class="btn btn-secondary" href="index.php">Voltar</a>
  </div><br>
</section>
<?php	
   include_once "footer.php";
?>

<script>
  $(document).ready(function(){
    let usuarioId = $("#usuario-id").val();
    let tipoUsuario = $("#tipo-usuario").val();
    let page = 1;
    let limite = $('#limite').val();

    const URL = "./ajax/fetch" + (tipoUsuario == 2 ? "_oferta_questionario.php" : "_questionario_elaborador.php");

    load_data(usuarioId, page, limite);

    function load_data(usuarioId, page, limite, query='', ) {
        $.ajax({
        url: URL,
        method:"POST",
        data:{
            page:page, 
            query:query,
            limit: limite,
            usuario:usuarioId
        },
        success:function(data) {
          $('#lista-resultado').html(data);
        }
      });
    }

    $(document).on('click', '.page-link', function(){
      let page = $(this).data('page_number');
      let query = $('#busca').val();
      let limite = $('#limite').val();
      load_data(usuarioId, page, limite, query);
    });

    $('#busca').keyup(function(){
        let query = $('#busca').val();
        let limite = $('#limite').val();
        if(query.length > 1){
          load_data(usuarioId, page, limite, query);
        }
    });

    $('#botao-busca').click(function(){
        let query = $('#busca').val();
        let limite = $('#limite').val();
        load_data(usuarioId, page, limite, query);
    });

    $('#limite').change(function(){
        let query = $('#busca').val();
        let limite = $('#limite').val();
        load_data(usuarioId, page, limite, query);
    });

  });
</script>
