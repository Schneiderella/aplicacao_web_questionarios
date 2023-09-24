
<div class="col-sm-12 col-md-4 col-lg-4 navbar">
	<div id="login_info" class="d-grid gap-2 d-md-flex justify-content-md-end container">
	<?php	
		include_once "comum.php";
		
		if ( is_session_started() === FALSE ) {
			session_start();
			echo "<alert></alert>";
		}	
		
		if(isset($_SESSION['nome'])) {
			// Informações de login
			echo "<span><i class='bi bi-person-circle'></i> " . $_SESSION['nome'] . "</span>";		
			echo "<a class='btn btn-primary' href='executa_logout.php'> Logout </a></span>";
		} else {	
			echo
				"<a class='btn btn-outline-primary' data-bs-toggle='modal' data-bs-target='#modalSignin' href='login.php'>Login</a>
				<a class='btn btn-primary' href='cadastroRespondente.php'>Cadastre-se</a>
				</div></a></span>";
		}
		?>	
	
</div>