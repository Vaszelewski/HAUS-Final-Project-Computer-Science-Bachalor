<div class="row align-items-center marginMenu">
	<div class="col-6 col-xl-3" data-aos="fade-down">
		<h1 class="mb-0">
			<a href="index.php" class="text-black h2 mb-0">Haus</a>
		</h1>
	</div>

	<div class="col-10 col-md-6 d-none d-xl-block" data-aos="fade-down">
		<nav class="site-navigation position-relative text-right text-lg-center" role="navigation">
			<ul class="site-menu js-clone-nav mx-auto d-none d-lg-block">
				<li class="active">
					<a href="?pag=index">Home</a>
				</li>
				<li class="has-children">
					<a href="?pag=single">Gallery</a>
					<ul class="dropdown">
						<li>
							<a href="#">Nature</a>
						</li>
						<li>
							<a href="#">Portrait</a>
						</li>
						<li>
							<a href="#">People</a>
						</li>
						<li>
							<a href="#">Architecture</a>
						</li>
						<li>
							<a href="#">Animals</a>
						</li>
						<li>
							<a href="#">Sports</a>
						</li>
						<li>
							<a href="#">Travel</a>
						</li>
						<li class="has-children">
							<a href="#">Sub Menu</a>
							<ul class="dropdown">
								<li>
									<a href="#">Menu One</a>
								</li>
								<li>
									<a href="#">Menu Two</a>
								</li>
								<li>
									<a href="#">Menu Three</a>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li>
					<a href="?pag=services">Services</a>
				</li>
				<li>
					<a href="?pag=sobre">Sobre</a>
				</li>
				<li class="<?= !isset($_SESSION['user_info'])? "none" : "" ?>">
					<a href="?pag=suporte">Suporte</a>
				</li>
			</ul>
		</nav>
	</div>
<?php
	if(!isset($_SESSION['user_info']))
	{
?>
	<div class="col-3 col-md-3 d-none d-xl-block" data-aos="fade-down">
		<nav class="site-navigation position-relative text-right text-lg-center" role="navigation">
			<ul class="site-menu js-clone-nav mx-auto d-none d-lg-block">
				<li class="botaoCadastro">
					<a href="?pag=cadastro">Cadastrar</a>
				</li>
				<li class="botaoEntrar">
					<a href="?pag=login">Entrar</a>
				</li>
			</ul>
		</nav>
	</div>
<?php
	}
	else
	{
		$displayName = strlen($_SESSION['user_info']['display_name']) > 0 ? $_SESSION['user_info']['display_name'] : $_SESSION['user_info']['nome'];
?>
	<div class="col-3 col-md-3 d-none d-xl-block" data-aos="fade-down">
		<nav class="site-navigation position-relative text-right text-lg-center" role="navigation">
			<ul class="site-menu js-clone-nav mx-auto d-none d-lg-block">
				<li>
					<a href="?pag=atualizarPerfil"><?= htmlentities($displayName) ?></a>
				</li>
				<li>
					<div>
						<img id="miniaturaUsuario" src="<?= htmlentities($_SESSION['user_info']['imagem']) ?>">
					</div>
				</li>
			</ul>
		</nav>
	</div>
<?php
	}
?>
	<div class="col-6 col-xl-2 text-right" data-aos="fade-down">
		<!-- <div class="d-none d-xl-inline-block">
			<ul class="site-menu js-clone-nav ml-auto list-unstyled d-flex text-right mb-0" data-class="social">
				<li>
					<a href="#" class="pl-0 pr-3"><span class="icon-facebook"></span></a>
				</li>
				<li>
					<a href="#" class="pl-3 pr-3"><span class="icon-twitter"></span></a>
				</li>
				<li>
					<a href="#" class="pl-3 pr-3"><span class="icon-instagram"></span></a>
				</li>
				<li>
					<a href="#" class="pl-3 pr-3"><span class="icon-youtube-play"></span></a>
				</li>
			</ul>
		</div> -->

		<div class="d-inline-block d-xl-none ml-md-0 mr-auto py-3" style="position: relative; top: 3px;">
			<a href="#" class="site-menu-toggle js-menu-toggle text-black">
				<span class="icon-menu h3"></span>
			</a>
		</div>
	</div>
</div>