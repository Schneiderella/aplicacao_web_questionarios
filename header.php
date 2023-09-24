<?php
	include_once "head.php";
?>
<body>
	<header>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom py-0 mb-3">
			<div class="container-fluid">
				<a href="index.php">
					<h1><img src="../aplicacao_web_questionarios/libs/img/Qlogo.png" style="max-width:60px" alt="Quest logo"></h1>
				</a>
				<a class="navbar-brand" href="#"><h4><?=$titulo_pagina?></h4></a>
				<div class="collapse navbar-collapse" id="navbarScroll">
					<ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll mb-2" style="--bs-scroll-height: 100px;">
						<li><a class="nav-link" href="index.php"></a></li>
						<div class="nav-scroller py-1 mb-2">					
					</ul>
					<!-- botões -->
					<?php
						include_once "navbotoes.php";
					?>
				</div>				
			</div>
			<hr>
		</nav>		
	</header>
	
<script>
	$("#btnNavbar").on("click", function() {
		$("#navbarScroll").toggle();
	});
</script>