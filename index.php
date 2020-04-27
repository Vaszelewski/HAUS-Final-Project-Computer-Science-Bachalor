<?php
	require_once('funcoes/funcoes.php');

	$pagina = isset($_GET['pag']) ? $_GET['pag'] : 'index';
?>

<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<title>Haus</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300i,400,700" rel="stylesheet">
		<link rel="stylesheet" href="fonts/icomoon/style.css">
		<link rel="stylesheet" href="vendors/estilos/bootstrap.min.css">
		<link rel="stylesheet" href="vendors/estilos/magnific-popup.css">
		<link rel="stylesheet" href="vendors/estilos/jquery-ui.css">
		<link rel="stylesheet" href="vendors/estilos/owl.carousel.min.css">
		<link rel="stylesheet" href="vendors/estilos/owl.theme.default.min.css">
		<link rel="stylesheet" href="vendors/estilos/lightgallery.min.css">
		<link rel="stylesheet" href="vendors/estilos/bootstrap-datepicker.css">
		<link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
		<link rel="stylesheet" href="vendors/estilos/swiper.css">
		<link rel="stylesheet" href="vendors/estilos/aos.css">
		<link rel="stylesheet" href="vendors/estilos/style.css">
		<link rel="stylesheet" href="vendors/estilos/alertify.min.css">
		<link rel="stylesheet" href="vendors/estilos/default.min.css">
		<link rel="stylesheet" href="estilos/estilo.css">

		<script src="vendors/javascript/jquery-3.3.1.min.js"></script>
		<script src="vendors/javascript/jquery-migrate-3.0.1.min.js"></script>
		<script src="vendors/javascript/jquery-ui.js"></script>
		<script src="vendors/javascript/alertify.min.js"></script>
	</head>

	<body>
		<div class="site-wrap">

			<div class="site-mobile-menu">
				<div class="site-mobile-menu-header">
					<div class="site-mobile-menu-close mt-3">
						<span class="icon-close2 js-menu-toggle"></span>
					</div>
				</div>
				<div class="site-mobile-menu-body"></div>
			</div>

			<header class="site-navbar py-3" role="banner">
				<div class="container-fluid">
					<?php
						require_once('funcoes/menu.php');
					?>
				</div>
			</header>

			<div class="container-fluid" data-aos="fade" data-aos-delay="500">
				<?php
					paginacao($pagina);
				?>
			</div>

			<div class="footer py-4">
				<div class="container-fluid">
					<p>
						<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
						Copyright &copy;<script data-cfasync="false" ></script><script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank" >Colorlib</a>
						<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
					</p>
				</div>
			</div>
		</div>

		<script src="vendors/javascript/popper.min.js"></script>
		<script src="vendors/javascript/bootstrap.min.js"></script>
		<script src="vendors/javascript/owl.carousel.min.js"></script>
		<script src="vendors/javascript/jquery.stellar.min.js"></script>
		<script src="vendors/javascript/jquery.countdown.min.js"></script>
		<script src="vendors/javascript/jquery.magnific-popup.min.js"></script>
		<script src="vendors/javascript/bootstrap-datepicker.min.js"></script>
		<script src="vendors/javascript/swiper.min.js"></script>
		<script src="vendors/javascript/aos.js"></script>
		<script src="vendors/javascript/picturefill.min.js"></script>
		<script src="vendors/javascript/lightgallery-all.min.js"></script>
		<script src="vendors/javascript/jquery.mousewheel.min.js"></script>
		<script src="vendors/javascript/main.js"></script>
		<script src="javascript/funcoes.js"></script>
		
		<script>
			$(document).ready(function(){
				$('#lightgallery').lightGallery();
			});
		</script>

	</body>
</html>