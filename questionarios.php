<?php
include_once "fachada.php";
$titulo_pagina = "Questionário";
include_once "header.php";
?>
<section class='px-5'>
	<div class="row justify-content-between">
    	<div class='pt-3'>
			<h3>Questionário</h3><br>
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

	<div id="lista-resultado" class="table-responsive">
		
	</div>
	<br>
	<div class="d-grid gap-2 d-md-flex justify-content-md-end">
		<a class="btn btn-primary" href="editaQuestionario.php">Novo</a>
		<br>
		<a class="btn btn-secondary" href="index.php">Voltar</a>
	</div>
</section>
<?php 
include_once "footer.php"
?>

<script>
  $(document).ready(function(){

    let page = 1;
    let limite = $('#limite').val();
    const URL = "./ajax/fech_questionario_lista.php";

    load_data(page, limite);

    function load_data(page, limite, query='', ) {
        $.ajax({
        url: URL,
        method:"POST",
        data:{
            page:page, 
            query:query,
            limit: limite,
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
      load_data(page, limite, query);
    });

    $('#busca').keyup(function(){
        let query = $('#busca').val();
        let limite = $('#limite').val();
        if(query.length > 1){
          load_data(page, limite, query);
        }
    });

    $('#botao-busca').click(function(){
        let query = $('#busca').val();
        let limite = $('#limite').val();
        load_data(page, limite, query);
    });

    $('#limite').change(function(){
        let query = $('#busca').val();
        let limite = $('#limite').val();
        load_data(page, limite, query);
    });

  });
</script>

