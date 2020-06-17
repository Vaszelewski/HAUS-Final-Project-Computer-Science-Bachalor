<div class="row align-items-center marginMenu">
	<div class="col-6 col-xl-3" data-aos="fade-down">
		<h1 class="mb-0">
			<a href="index.php" class="text-black h2 mb-0">HAUS</a>
		</h1>
	</div>
<?php
	if(!isset($_SESSION['user_info']))
	{
?>
	<div class="col-10 col-md-6 d-none d-xl-block" data-aos="fade-down">
		<nav class="site-navigation position-relative text-right text-lg-center" role="navigation">
			<ul class="site-menu js-clone-nav mx-auto d-none d-lg-block">
				<li <?= isset($_GET['pag']) && $_GET['pag'] == "index" ? 'class="active"' : ''; ?> >
					<a href="?pag=index">Início</a>
				</li>
				<li <?= isset($_GET['pag']) && $_GET['pag'] == "categoria" ? 'class="active"' : ''; ?>>
					<a href="?pag=categoria">Categorias</a>
				</li>
				<li <?= isset($_GET['pag']) && $_GET['pag'] == "sobre" ? 'class="active"' : ''; ?>>
					<a href="?pag=sobre">Sobre</a>
				</li>
			</ul>
		</nav>
	</div>

	<div class="col-3 col-md-3 d-none d-xl-block" data-aos="fade-down">
		<nav class="site-navigation position-relative text-right text-lg-center" role="navigation">
			<ul class="site-menu js-clone-nav mx-auto d-none d-lg-block">
				<li class="botaoCadastro">
					<a href="?pag=cadastro">Cadastre-se</a>
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

<div class="col-9 col-md-5 d-none d-xl-block" data-aos="fade-down">
		<nav class="site-navigation position-relative text-right text-lg-center" role="navigation">
			<ul class="site-menu js-clone-nav mx-auto d-none d-lg-block">
				<li <?= isset($_GET['pag']) && $_GET['pag'] == "index" ? 'class="active"' : ''; ?>>
					<a href="?pag=index">Início</a>
				</li>
				<li <?= isset($_GET['pag']) && $_GET['pag'] == "colecao" ? 'class="active"' : ''; ?>>
					<a href="?pag=colecao">Coleção</a>
				</li>
				<li <?= isset($_GET['pag']) && $_GET['pag'] == "categoria" ? 'class="active"' : ''; ?>>
					<a href="?pag=categoria">Categorias</a>
				</li>
				<li>
					<a href="#">Favoritos</a>
				</li>
			</ul>
		</nav>
	</div>

	<div class="col-4 col-md-4 d-none d-xl-block" data-aos="fade-down">
		<nav class="site-navigation position-relative text-right text-lg-right" role="navigation">
			<ul class="site-menu js-clone-nav mx-auto d-none d-lg-block">
				<li class="displayName">
					<a><?= htmlentities($displayName) ?></a>
				</li>
				<li class="has-children">
					<div>
						<img id="miniaturaUsuario" src="<?= htmlentities($_SESSION['user_info']['imagem']) ?>">
					</div>
					<ul class="dropdown dropdown-menu-right" role="menu">
						<li>
							<a href="?pag=atualizarPerfil">Atualizar Perfil</a>
						</li>
						<li>
							<a href="?pag=suporte">Suporte</a>
						</li>
						<li>
							<a id="deslogar">Sair</a>
						</li>
					</ul>
				</li>
				
			</ul>
		</nav>
	</div>
<?php
	}
?>
	<div class="col-6 col-xl-2 text-right" data-aos="fade-down">
		<div class="d-inline-block d-xl-none ml-md-0 mr-auto py-3" style="position: relative; top: 3px;">
			<a href="#" class="site-menu-toggle js-menu-toggle text-black">
				<span class="icon-menu h3"></span>
			</a>
		</div>
	</div>
</div>
