<?php
// Sessão
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="apple-touch-icon" sizes="57x57" href="dist/icons/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="dist/icons/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="dist/icons/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="dist/icons/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="dist/icons/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="dist/icons/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="dist/icons/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="dist/icons/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="dist/icons/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="dist/icons/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="dist/icons/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="dist/icons/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="dist/icons/favicon/favicon-16x16.png">
	<link rel="manifest" href="dist/icons/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="dist/icons/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<!-- Bootstrap -->
	<link rel="stylesheet" type="text/css" href="plugins/bootstrap/css/bootstrap.min.css">

	<!-- Theme -->
	<link rel="stylesheet" type="text/css" href="dist/login/css/util.min.css">
	<link rel="stylesheet" type="text/css" href="dist/login/css/main.min.css">
</head>

<body style="background-color: #666666;">

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" method="POST" action="php_action/actLogin">
					<!-- Aviso -->
					<?php if (isset($_SESSION['mensagem'])) : ?>
						<div class="row">
							<div class="col-lg-12">
								<div class="alert alert-danger alert-dismissible fade show" role="alert">
									<?php echo $_SESSION['mensagem'];	?>
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
							</div>
						</div>
					<?php
						unset($_SESSION['mensagem']);
					endif; ?>
					<!-- ./ Aviso -->

					<span class="login100-form-title p-b-43">
						Login
					</span>

					<div class="wrap-input100">
						<input class="input100" type="text" name="login" value="">
						<span class="focus-input100"></span>
						<span class="label-input100">Login</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<input class="input100" type="password" name="senha" value="<?php echo isset($_COOKIE['senha']) ? $_COOKIE['senha'] : '' ?>">
						<span class="focus-input100"></span>
						<span class="label-input100">Senha</span>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit" name="btnLogin" id="btnLogin">
							Login
						</button>
					</div>
				</form>

				<div class="login100-more" style="background-image: url('dist/login/images/bg-01.jpg');">
				</div>
			</div>
		</div>
	</div>

	<!-- JQuery -->
	<script src="plugins/jquery/jquery.min.js"></script>
	<!-- Bootsrap -->
	<script src="plugins/bootstrap/js/bootstrap.min.js"></script>
	<!-- Login -->
	<script src="dist/login/js/main.js"></script>

	<?php
	// Sessão
	session_unset();
	session_destroy();
	?>
</body>

</html>