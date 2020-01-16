<?php
ob_start();
session_start();
include("inc/config.php");
include("inc/functions.php");
include("inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();
$error_message = '';
$success_message = '';
$error_message1 = '';
$success_message1 = '';

// Check if the user is logged in or not
if(!isset($_SESSION['user'])) {
	header('location: login.php');
	exit;
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Administración - HomeDesign</title>

	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/ionicons.min.css">
	<link rel="stylesheet" href="css/datepicker3.css">
	<link rel="stylesheet" href="css/all.css">
	<link rel="stylesheet" href="css/select2.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.css">
	<link rel="stylesheet" href="css/jquery.fancybox.css">
	<link rel="stylesheet" href="css/AdminLTE.min.css">
	<link rel="stylesheet" href="css/_all-skins.min.css">
	<link rel="stylesheet" href="css/on-off-switch.css"/>
	<link rel="stylesheet" href="css/summernote.css">
	<link rel="stylesheet" href="style.css">
	<!-- CSS Alertify-->
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/css/alertify.min.css"/>
	<!-- Default theme -->
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/css/themes/default.min.css"/>
	<!-- Semantic UI theme -->
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/css/themes/semantic.min.css"/>
	<!-- Bootstrap theme -->
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/css/themes/bootstrap.min.css"/>
	<!-- own style -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link href="lightb/css/lightbox.css" rel="stylesheet" />
	<style type="text/css">
		.hr-dark{
			margin-top: 20px;
		    margin-bottom: 20px;
		    border: 0;
		    border-top: 1px solid rgba(0,0,0,0.2);
		}
		.alerta-amarillo{
			background-color: rgb(255, 220, 76) !important;	
		}
		.dt-buttons{
			text-align: center;
		}
		@-webkit-keyframes spaceboots {
			0% { -webkit-transform: translate(2px, 1px) rotate(0deg); }
			10% { -webkit-transform: translate(-1px, -2px) rotate(-1deg); }
			20% { -webkit-transform: translate(-3px, 0px) rotate(1deg); }
			30% { -webkit-transform: translate(0px, 2px) rotate(0deg); }
			40% { -webkit-transform: translate(1px, -1px) rotate(1deg); }
			50% { -webkit-transform: translate(-1px, 2px) rotate(-1deg); }
			60% { -webkit-transform: translate(-3px, 1px) rotate(0deg); }
			70% { -webkit-transform: translate(2px, 1px) rotate(-1deg); }
			80% { -webkit-transform: translate(-1px, -1px) rotate(1deg); }
			90% { -webkit-transform: translate(2px, 2px) rotate(0deg); }
			100% { -webkit-transform: translate(1px, -2px) rotate(-1deg); }
		}
		.shake {
			-webkit-animation-name: spaceboots;
			-webkit-animation-duration: 0.8s;
			-webkit-transform-origin:50% 50%;
			-webkit-animation-iteration-count: infinite;
			-webkit-animation-timing-function: linear;
		}
		#resultadosProductos li:hover{
			background-color: #fef600;
			font-size: 20px;
		}
		#resultadosProductos{
			/*border: 3px solid #000000;*/
			margin: 3em;
			padding: 2em;
			border-radius: 3px;
			box-shadow: 1px 1px 3px #fef600;

		}
	</style>
</head>
<div id="cover_top" style="display: none; position: absolute; top: 0px; left: 0px; background-color: rgba(0,0,0,0.7); z-index: 9999999; width: 2900px; height: 2200px;"></div>

<body class="hold-transition fixed skin-blue sidebar-mini">

	<div class="wrapper">

		<header class="main-header">

			<a href="index.php" class="logo">
				<span class="logo-lg">Sistema</span>
			</a>

			<nav class="navbar navbar-static-top">
				
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>
	
				<?php 
					
					$titleBar = "Admin";
					$role = $_SESSION['user']['role'];
					$user_id_sucursal = $_SESSION['user']['sucursal'];

					if($role == 'Empleado'){
						$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_id=?");
						$statement->execute(array($user_id_sucursal));
						$results = $statement->fetchAll(PDO::FETCH_ASSOC);
						foreach($results as $row){
							$s_name = $row['s_name'];
							$s_address = $row['s_address'];
						}
						$titleBar = $s_name." - ".$s_address;
					}
					if($role == 'Publisher'){
						$titleBar = "Diseñador";
					}
					if($role == 'Fabrica'){
						$titleBar = "Empleado Fábrica";
					}
				 ?>
				<span style="float:left;line-height:50px;color:#fff;padding-left:15px;font-size:18px;"><?php echo $titleBar; ?></span>

				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="../assets/uploads/<?php echo $_SESSION['user']['photo']; ?>" class="user-image" alt="User Image">
								<span class="hidden-xs"><?php echo $_SESSION['user']['full_name']; ?></span>
							</a>
							<ul class="dropdown-menu">
								<li class="user-footer">
									<div>
										<a href="profile-edit.php" class="btn btn-default btn-flat">Editar Perfíl</a>
									</div>
									<div>
										<a href="logout.php" class="btn btn-default btn-flat">Salir</a>
									</div>
								</li>
							</ul>
						</li>
					</ul>
				</div>

			</nav>
		</header>

  		<?php $cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); ?>

  		<aside class="main-sidebar">
    		<section class="sidebar">
      
      			<?php 
      				switch ($_SESSION['user']['role']) {
      					case 'Super Admin':
      						?>
							<!-- html for Super Admin -->
							<ul class="sidebar-menu">
      				
			      				<!--<li class="treeview <?php if( ($cur_page == 'blank.php') ) {echo 'active';} ?>">
						          <a href="blank.php">
						            <i class="fa fa-file"></i> <span>Blank (dev)</span>
						          </a>
						        </li>-->
						        <li class="treeview <?php if($cur_page == 'index.php') {echo 'active';} ?>">
						          <a href="index.php">
						            <i class="fa fa-tachometer"></i> <span>Inicio</span>
						          </a>
						        </li>

						         <li class="treeview <?php if($cur_page == 'verPrecios.php') {echo 'active';} ?>">
						          <a href="verPrecios.php">
						            <i class="fa fa-tachometer"></i> <span>Precios Rapidos</span>
						          </a>
						        </li>

						       
						        <li class="treeview <?php if( ($cur_page == 'sucursal.php') || ($cur_page == 'sucursales.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-building-o"></i>
										<span>Sucursales</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="sucursales.php"><i class="fa fa-building-o"></i> Administrar</a></li>
										<?php 
										$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_id<>'0' ORDER BY s_name ASC;");
										$statement->execute();
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);
										foreach ($result as $row) {
											?>
											<li><a href="<?php echo 'sucursal.php?id='.$row['s_id']; ?>"><i class="fa fa-building-o"></i> <?php echo $row['s_name']; ?></a></li>
											<?php 
										}
									 	?>

									</ul>
								</li>

								<li class="treeview <?php if( ($cur_page == 'ventas.php') || ($cur_page == 'clientes.php') || ($cur_page == 'presupuestos.php') || ($cur_page == 'notaCredito.php') || ($cur_page == 'recibos.php')|| ($cur_page == 'estado-cuenta.php') || ($cur_page == 'reporte.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-usd"></i>
										<span>Home</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="ventas.php"><i class="fa fa-usd"></i> Documentos</a></li>
										<li><a href="clientes.php"><i class="fa fa-users"></i> Clientes</a></li>
										<li><a href="presupuestos.php"><i class="fa fa-usd"></i> Presupuestos</a></li>
										<li><a href="notaCredito.php"><i class="fa fa-usd"></i> Notas de Crédito</a></li>
										<li><a href="recibos.php"><i class="fa fa-usd"></i> Recibos</a></li>
										<li><a href="estado-cuenta.php"><i class="fa fa-cart-plus"></i> Estado de Cuenta</a></li>
										<li><a href="reporte.php"><i class="fa fa-bar-chart"></i> Reportes</a></li>
									</ul>
								</li>

								<!-- Caja -->

								<li class="treeview <?php if( ($cur_page == 'caja.php') || ($cur_page == 'cajaDiaria.php') || ($cur_page == 'cajaChica.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-usd"></i>
										<span>Caja</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="caja.php"><i class="fa fa-usd"></i>Caja Por Turno</a></li>
										<li><a href="cajaDiaria.php"><i class="fa fa-usd"></i> Caja Diaria</a></li>
										<li><a href="cajaChica.php"><i class="fa fa-usd"></i>Caja Chica</a></li>
										<li><a href="cajaFecha.php"><i class="fa fa-usd"></i>Caja por Fecha</a></li>
										<li><a href="cajaMensual.php"><i class="fa fa-usd"></i>Caja Mensual</a></li>
										
									</ul>
								</li>

								<!-- Fabrica -->

								<li class="treeview <?php if( ($cur_page == 'pedidos.php') || ($cur_page == 'pedidoEstado.php') || ($cur_page == 'pedido-add.php') ) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-industry"></i>
										<span>Pedidos a Fábrica</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="pedidos.php"><i class="fa fa-sticky-note"></i>Pedidos</a></li>
									</ul>
									<ul class="treeview-menu">
										<li><a href="ordenes.php"><i class="fa fa-sticky-note"></i>Órdenes de Trabajo</a></li>
									</ul>
								</li>

									<!-- Carpinteria -->

								<li class="treeview <?php if( ($cur_page == 'carpinteria.php') || ($cur_page == 'pedidoEstado.php') || ($cur_page == 'carpinteria-add.php') ) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-gavel"></i>
										<span>Carpintería</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="carpinteria.php"><i class="fa fa-sticky-note"></i>Carpintería</a></li>
									</ul>
									<ul class="treeview-menu">
										<li><a href="ordenesCarpinteria.php"><i class="fa fa-sticky-note"></i>Órdenes de Carpintería</a></li>
									</ul>
								</li>
								
								<!-- Comodatos -->
								<li class="treeview <?php if($cur_page == 'comodato.php') {echo 'active';} ?>">
						          <a href="comodato.php">
						            <i class="fa fa-handshake-o"></i> <span>Comodato</span>
						          </a>
						        </li>

						        <!-- Proveedores -->

								<li class="treeview <?php if(($cur_page == 'proveedores.php') || ($cur_page == 'verCuentasProveedores.php')  || ($cur_page == 'proveedoresCuentas.php') || ($cur_page == 'cuentaProveedores.php') || ($cur_page == 'proveedores-add.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-cubes"></i>
										<span>Proveedores</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="proveedores.php"><i class="fa fa-sticky-note"></i>Proveedores</a></li>
									</ul>
									<ul class="treeview-menu">
										<li><a href="proveedoresCuentas.php"><i class="fa fa-sticky-note"></i>Proveedores Carpintería</a></li>
									</ul>
								</li>

						       
								
								
								<!--<li class="treeview <?php if( ($cur_page == 'comodato.php') || ($cur_page == 'addComodato.php') || ($cur_page == 'verComodato.php') ) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-industry"></i>
										<span>Comodatos</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="pedidos.php"><i class="fa fa-sticky-note"></i>Todos</a></li>
									</ul>
									<ul class="treeview-menu">
										<li><a href="ordenes.php"><i class="fa fa-sticky-note"></i>Órdenes de Trabajo</a></li>
									</ul>
								</li>-->

								<!-- Menu Productos -->
								<li class="treeview <?php if( ($cur_page == 'product.php') || ($cur_page == 'product-add.php') || ($cur_page == 'product-edit.php') || ($cur_page == 'stock.php') || ($cur_page == 'product-history.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-bookmark"></i>
										<span>Productos</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="product.php"><i class="fa fa-bookmark"></i> Productos</a></li>
										<li><a href="stock.php"><i class="fa fa-cart-plus"></i> Stock</a></li>
										<li><a href="product-history.php"><i class="fa fa-history"></i> Historial</a></li>
										
									</ul>
								</li>


						        <!--<li class="treeview <?php if( ($cur_page == 'product.php') || ($cur_page == 'product-add.php') || ($cur_page == 'product-edit.php') ) {echo 'active';} ?>">
						          <a href="product.php">
						            <i class="fa fa-bookmark"></i> <span>Productos</span>
						          </a>
						        </li>-->



								<li class="treeview <?php if( ($cur_page == 'size.php') || ($cur_page == 'size-add.php') || ($cur_page == 'size-edit.php') || ($cur_page == 'color.php') || ($cur_page == 'color-add.php') || ($cur_page == 'color-edit.php') || ($cur_page == 'country.php') || ($cur_page == 'country-add.php') || ($cur_page == 'country-edit.php') || ($cur_page == 'shipping-cost.php') || ($cur_page == 'shipping-cost-edit.php') || ($cur_page == 'settings.php') || ($cur_page == 'slider.php') || ($cur_page == 'service.php') || ($cur_page == 'photo.php') || ($cur_page == 'video.php') || ($cur_page == 'testimonial.php') || ($cur_page == 'post.php') ||($cur_page == 'post-add.php') ||($cur_page == 'post-edit.php') || ($cur_page == 'category.php') || ($cur_page == 'category-add.php') || ($cur_page == 'category-edit.php') || ($cur_page == 'faq.php') || ($cur_page == 'order.php') || ($cur_page == 'rating.php') || ($cur_page == 'social-media.php') || ($cur_page == 'customer.php') || ($cur_page == 'customer-add.php') || ($cur_page == 'customer-edit.php') || ($cur_page == 'advertisement.php') || ($cur_page == 'subscriber.php') || ($cur_page == 'customer-message.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-shopping-bag"></i>
										<span>E-Commerce</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="customer.php"><i class="fa fa-users"></i> Clientes E-Commerce</a></li>
										<li><a href="customer-message.php"><i class="fa fa-envelope-o"></i> Mensajes de Clientes</a></li>
										<li><a href="order.php"><i class="fa fa-truck"></i> Pedidos</a></li>
										<li><a href="slider.php"><i class="fa fa-camera-retro"></i> Portadas</a></li>
										<li><a href="size.php"><i class="fa fa-tags"></i> Talles / Tamaños</a></li>
										<li><a href="color.php"><i class="fa fa-dashboard"></i> Colores</a></li>
										<li><a href="country.php"><i class="fa fa-globe"></i> Países</a></li>
										<li><a href="shipping-cost.php"><i class="fa fa-truck"></i> Costos de Envío</a></li>
										<li><a href="service.php"><i class="fa fa-cog"></i> Servicios</a></li>
										<li><a href="testimonial.php"><i class="fa fa-comments"></i> Comentarios</a></li>
										<li><a href="settings.php"><i class="fa fa-cogs"></i> Configuraciones E-Commerce</a></li>
										<li class="treeview <?php if( ($cur_page == 'photo.php') || ($cur_page == 'video.php') ) {echo 'active';} ?>">
											<a href="#">
												<i class="fa fa-film"></i>
												<span>Galería</span>
												<span class="pull-right-container">
													<i class="fa fa-angle-left pull-right"></i>
												</span>
											</a>
											<ul class="treeview-menu">
												<li><a href="photo.php"><i class="fa fa-picture-o"></i> Galería de Fotos</a></li>
												<li><a href="video.php"><i class="fa fa-video-camera"></i> Galería de Videos</a></li>
											</ul>
										</li>
										<li class="treeview <?php if( ($cur_page == 'post.php') ||($cur_page == 'post-add.php') ||($cur_page == 'post-edit.php') || ($cur_page == 'category.php') || ($cur_page == 'category-add.php') || ($cur_page == 'category-edit.php') ) {echo 'active';} ?>">
											<a href="#">
												<i class="fa fa-newspaper-o"></i>
												<span>Blog</span>
												<span class="pull-right-container">
													<i class="fa fa-angle-left pull-right"></i>
												</span>
											</a>
											<ul class="treeview-menu">
												<li><a href="category.php"><i class="fa fa-newspaper-o"></i> Categorías</a></li>
												<li><a href="post.php"><i class="fa fa-newspaper-o"></i> Posteos</a></li>
											</ul>
										</li>
										<li><a href="page.php"><i class="fa fa-file"></i> Página</a></li>
										<li><a href="rating.php"><i class="fa fa-star"></i> Rating</a></li>
										<li><a href="social-media.php"><i class="fa fa-share-alt"></i> Redes Sociales</a></li>
										<li><a href="faq.php"><i class="fa fa-question"></i> Preguntas Frecuentes</a></li>
										<li><a href="advertisement.php"><i class="fa fa-bullhorn"></i> Promos</a></li>
										<li><a href="subscriber.php"><i class="fa fa-rss"></i> Suscriptores</a></li>
									</ul>
								</li>


								 <li class="treeview <?php if( ($cur_page == 'usuarios.php') || ($cur_page == 'top-category.php') || ($cur_page == 'top-category-add.php') || ($cur_page == 'top-category-edit.php') || ($cur_page == 'mid-category.php') || ($cur_page == 'mid-category-add.php') || ($cur_page == 'mid-category-edit.php') || ($cur_page == 'end-category.php') || ($cur_page == 'end-category-add.php') || ($cur_page == 'end-category-edit.php') || ($cur_page == 'language.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-key"></i>
										<span>Configuraciones</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="usuarios.php"><i class="fa fa-users"></i> Usuarios</a></li>
										<li><a href="top-category.php"><i class="fa fa-tags"></i> Secciones</a></li>
										<li><a href="mid-category.php"><i class="fa fa-tags"></i> Rubros</a></li>
										<li><a href="end-category.php"><i class="fa fa-tags"></i> Sub-Rubros</a></li>
										<li><a href="language.php"><i class="fa fa-language"></i> Configuración de Idioma</a></li>
									</ul>
								</li>

								<li class="treeview <?php if($cur_page == 'log.php') {echo 'active';} ?>">
						          <a href="log.php">
						            <i class="fa fa-eye"></i> <span>Logs</span>
						          </a>
						        </li>

			      			</ul>
      						<?php
      						break;
      					case 'Admin':
      					?>
							<!-- html for Admin -->
							<ul class="sidebar-menu">
      				
			      				<!--<li class="treeview <?php if( ($cur_page == 'blank.php') ) {echo 'active';} ?>">
						          <a href="blank.php">
						            <i class="fa fa-file"></i> <span>Blank (dev)</span>
						          </a>
						        </li>-->
						        <li class="treeview <?php if($cur_page == 'index.php') {echo 'active';} ?>">
						          <a href="index.php">
						            <i class="fa fa-tachometer"></i> <span>Inicio</span>
						          </a>
						        </li>
						         <li class="treeview <?php if($cur_page == 'verPrecios.php') {echo 'active';} ?>">
						          <a href="verPrecios.php">
						            <i class="fa fa-tachometer"></i> <span>Precios Rapidos</span>
						          </a>
						        </li>

						       
						        <li class="treeview <?php if( ($cur_page == 'sucursal.php') || ($cur_page == 'sucursales.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-building-o"></i>
										<span>Sucursales</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="sucursales.php"><i class="fa fa-building-o"></i> Administrar</a></li>
										<?php 
										$statement = $pdo->prepare("SELECT * FROM tbl_sucursales ORDER BY s_name ASC;");
										$statement->execute();
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);
										foreach ($result as $row) {
											?>
											<li><a href="<?php echo 'sucursal.php?id='.$row['s_id']; ?>"><i class="fa fa-building-o"></i> <?php echo $row['s_name']; ?></a></li>
											<?php 
										}
									 	?>

									</ul>
								</li>

									<!-- Fabrica -->

								<li class="treeview <?php if( ($cur_page == 'pedidos.php') || ($cur_page == 'pedidoEstado.php') || ($cur_page == 'pedido-add.php') ) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-industry"></i>
										<span>Pedidos a Fábrica</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="pedidos.php"><i class="fa fa-sticky-note"></i>Pedidos</a></li>
									</ul>
									<ul class="treeview-menu">
										<li><a href="ordenes.php"><i class="fa fa-sticky-note"></i>Órdenes de Trabajo</a></li>
									</ul>
								</li>

								<!-- Carpinteria -->

								<li class="treeview <?php if( ($cur_page == 'carpinteria.php') || ($cur_page == 'pedidoEstado.php') || ($cur_page == 'carpinteria-add.php') ) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-gavel"></i>
										<span>Carpintería</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="carpinteria.php"><i class="fa fa-sticky-note"></i>Carpintería</a></li>
									</ul>
									<ul class="treeview-menu">
										<li><a href="ordenesCarpinteria.php"><i class="fa fa-sticky-note"></i>Órdenes de Carpintería</a></li>
									</ul>
								</li>

								<li class="treeview <?php if( ($cur_page == 'ventas.php') || ($cur_page == 'clientes.php') || ($cur_page == 'presupuestos.php') || ($cur_page == 'notaCredito.php') || ($cur_page == 'recibos.php')|| ($cur_page == 'estado-cuenta.php') || ($cur_page == 'reporte.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-usd"></i>
										<span>Home</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="ventas.php"><i class="fa fa-usd"></i> Documentos</a></li>
										<li><a href="clientes.php"><i class="fa fa-users"></i> Clientes</a></li>
										<li><a href="presupuestos.php"><i class="fa fa-usd"></i> Presupuestos</a></li>
										<li><a href="notaCredito.php"><i class="fa fa-usd"></i> Notas de Crédito</a></li>
										<li><a href="recibos.php"><i class="fa fa-usd"></i> Recibos</a></li>
										<li><a href="estado-cuenta.php"><i class="fa fa-cart-plus"></i> Estado de Cuenta</a></li>
										<li><a href="reporte.php"><i class="fa fa-bar-chart"></i> Reportes</a></li>
									</ul>
								</li>

								<!-- Comodatos -->
								<li class="treeview <?php if($cur_page == 'comodato.php') {echo 'active';} ?>">
						          <a href="comodato.php">
						            <i class="fa fa-handshake-o"></i> <span>Comodato</span>
						          </a>
						        </li>

						         <!-- Proveedores -->

								<li class="treeview <?php if(($cur_page == 'proveedores.php') || ($cur_page == 'verCuentasProveedores.php')  || ($cur_page == 'proveedoresCuentas.php') || ($cur_page == 'cuentaProveedores.php') || ($cur_page == 'proveedores-add.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-cubes"></i>
										<span>Proveedores</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="proveedores.php"><i class="fa fa-sticky-note"></i>Proveedores</a></li>
									</ul>
									<ul class="treeview-menu">
										<li><a href="proveedoresCuentas.php"><i class="fa fa-sticky-note"></i>Proveedores Carpintería</a></li>
									</ul>
								</li>


						       <!-- Menu Productos -->
								<li class="treeview <?php if( ($cur_page == 'product.php') || ($cur_page == 'product-add.php') || ($cur_page == 'product-edit.php') || ($cur_page == 'stock.php') || ($cur_page == 'product-history.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-bookmark"></i>
										<span>Productos</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="product.php"><i class="fa fa-bookmark"></i> Productos</a></li>
										<li><a href="stock.php"><i class="fa fa-cart-plus"></i> Stock</a></li>
										<li><a href="product-history.php"><i class="fa fa-history"></i> Historial</a></li>
										
									</ul>
								</li>

								<!-- Caja -->

								<li class="treeview <?php if( ($cur_page == 'caja.php') || ($cur_page == 'cajaDiaria.php') || ($cur_page == 'cajaChica.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-usd"></i>
										<span>Caja</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="caja.php"><i class="fa fa-usd"></i>Caja Por Turno</a></li>
										<li><a href="cajaDiaria.php"><i class="fa fa-usd"></i> Caja Diaria</a></li>
										<li><a href="cajaChica.php"><i class="fa fa-usd"></i>Caja Chica</a></li>
										<li><a href="cajaFecha.php"><i class="fa fa-usd"></i>Caja por Fecha</a></li>
										<li><a href="cajaMensual.php"><i class="fa fa-usd"></i>Caja Mensual</a></li>
										
									</ul>
								</li>

								<li class="treeview <?php if( ($cur_page == 'size.php') || ($cur_page == 'size-add.php') || ($cur_page == 'size-edit.php') || ($cur_page == 'color.php') || ($cur_page == 'color-add.php') || ($cur_page == 'color-edit.php') || ($cur_page == 'country.php') || ($cur_page == 'country-add.php') || ($cur_page == 'country-edit.php') || ($cur_page == 'shipping-cost.php') || ($cur_page == 'shipping-cost-edit.php') || ($cur_page == 'settings.php') || ($cur_page == 'slider.php') || ($cur_page == 'service.php') || ($cur_page == 'photo.php') || ($cur_page == 'video.php') || ($cur_page == 'testimonial.php') || ($cur_page == 'post.php') ||($cur_page == 'post-add.php') ||($cur_page == 'post-edit.php') || ($cur_page == 'category.php') || ($cur_page == 'category-add.php') || ($cur_page == 'category-edit.php') || ($cur_page == 'faq.php') || ($cur_page == 'order.php') || ($cur_page == 'rating.php') || ($cur_page == 'social-media.php') || ($cur_page == 'customer.php') || ($cur_page == 'customer-add.php') || ($cur_page == 'customer-edit.php') || ($cur_page == 'advertisement.php') || ($cur_page == 'subscriber.php') || ($cur_page == 'customer-message.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-shopping-bag"></i>
										<span>E-Commerce</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="customer.php"><i class="fa fa-users"></i> Clientes E-Commerce</a></li>
										<li><a href="customer-message.php"><i class="fa fa-envelope-o"></i> Mensajes de Clientes</a></li>
										<li><a href="order.php"><i class="fa fa-truck"></i> Pedidos</a></li>
										<li><a href="slider.php"><i class="fa fa-camera-retro"></i> Portadas</a></li>
										<li><a href="size.php"><i class="fa fa-tags"></i> Talles / Tamaños</a></li>
										<li><a href="color.php"><i class="fa fa-dashboard"></i> Colores</a></li>
										<li><a href="country.php"><i class="fa fa-globe"></i> Países</a></li>
										<li><a href="shipping-cost.php"><i class="fa fa-truck"></i> Costos de Envío</a></li>
										<li><a href="service.php"><i class="fa fa-cog"></i> Servicios</a></li>
										<li><a href="testimonial.php"><i class="fa fa-comments"></i> Comentarios</a></li>
										<li><a href="settings.php"><i class="fa fa-cogs"></i> Configuraciones E-Commerce</a></li>
										<li class="treeview <?php if( ($cur_page == 'photo.php') || ($cur_page == 'video.php') ) {echo 'active';} ?>">
											<a href="#">
												<i class="fa fa-film"></i>
												<span>Galería</span>
												<span class="pull-right-container">
													<i class="fa fa-angle-left pull-right"></i>
												</span>
											</a>
											<ul class="treeview-menu">
												<li><a href="photo.php"><i class="fa fa-picture-o"></i> Galería de Fotos</a></li>
												<li><a href="video.php"><i class="fa fa-video-camera"></i> Galería de Videos</a></li>
											</ul>
										</li>
										<li class="treeview <?php if( ($cur_page == 'post.php') ||($cur_page == 'post-add.php') ||($cur_page == 'post-edit.php') || ($cur_page == 'category.php') || ($cur_page == 'category-add.php') || ($cur_page == 'category-edit.php') ) {echo 'active';} ?>">
											<a href="#">
												<i class="fa fa-newspaper-o"></i>
												<span>Blog</span>
												<span class="pull-right-container">
													<i class="fa fa-angle-left pull-right"></i>
												</span>
											</a>
											<ul class="treeview-menu">
												<li><a href="category.php"><i class="fa fa-newspaper-o"></i> Categorías</a></li>
												<li><a href="post.php"><i class="fa fa-newspaper-o"></i> Posteos</a></li>
											</ul>
										</li>
										<li><a href="page.php"><i class="fa fa-file"></i> Página</a></li>
										<li><a href="rating.php"><i class="fa fa-star"></i> Rating</a></li>
										<li><a href="social-media.php"><i class="fa fa-share-alt"></i> Redes Sociales</a></li>
										<li><a href="faq.php"><i class="fa fa-question"></i> Preguntas Frecuentes</a></li>
										<li><a href="advertisement.php"><i class="fa fa-bullhorn"></i> Promos</a></li>
										<li><a href="subscriber.php"><i class="fa fa-rss"></i> Suscriptores</a></li>
									</ul>
								</li>


								 <li class="treeview <?php if( ($cur_page == 'usuarios.php') || ($cur_page == 'top-category.php') || ($cur_page == 'top-category-add.php') || ($cur_page == 'top-category-edit.php') || ($cur_page == 'mid-category.php') || ($cur_page == 'mid-category-add.php') || ($cur_page == 'mid-category-edit.php') || ($cur_page == 'end-category.php') || ($cur_page == 'end-category-add.php') || ($cur_page == 'end-category-edit.php') || ($cur_page == 'language.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-key"></i>
										<span>Configuraciones</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<!--<li><a href="usuarios.php"><i class="fa fa-users"></i> Usuarios</a></li>-->
										<li><a href="usuarios.php"><i class="fa fa-users"></i> Usuarios</a></li>
										<li><a href="top-category.php"><i class="fa fa-tags"></i> Secciones</a></li>
										<li><a href="mid-category.php"><i class="fa fa-tags"></i> Rubros</a></li>
										<li><a href="end-category.php"><i class="fa fa-tags"></i> Sub-Rubros</a></li>
										<!--<li><a href="language.php"><i class="fa fa-language"></i> Configuración de Idioma</a></li>-->
									</ul>
								</li>

								<li class="treeview <?php if($cur_page == 'log.php') {echo 'active';} ?>">
						          <a href="log.php">
						            <i class="fa fa-eye"></i> <span>Logs</span>
						          </a>
						        </li>

								

			      			</ul>
      					<?php
      						break;
      					case 'Empleado':
      						?>
							<!-- html for Empleado-->
							<ul class="sidebar-menu">
      				
						        <li class="treeview <?php if($cur_page == 'index.php') {echo 'active';} ?>">
						          <a href="index.php">
						            <i class="fa fa-tachometer"></i> <span>Inicio</span>
						          </a>
						        </li>
						         <li class="treeview <?php if($cur_page == 'verPrecios.php') {echo 'active';} ?>">
						          <a href="verPrecios.php">
						            <i class="fa fa-tachometer"></i> <span>Precios Rapidos</span>
						          </a>
						        </li>

						        	<!-- Fabrica -->

								<li class="treeview <?php if( ($cur_page == 'pedidos.php') || ($cur_page == 'pedidoEstado.php') || ($cur_page == 'pedido-add.php') ) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-industry"></i>
										<span>Pedidos a Fábrica</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="pedidos.php"><i class="fa fa-sticky-note"></i>Pedidos</a></li>
									</ul>
									
								</li>

								<!-- Carpinteria -->

								<li class="treeview <?php if( ($cur_page == 'carpinteria.php') || ($cur_page == 'pedidoEstado.php') || ($cur_page == 'carpinteria-add.php') ) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-gavel"></i>
										<span>Carpintería</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="carpinteria.php"><i class="fa fa-sticky-note"></i>Carpintería</a></li>
									</ul>
									
								</li>

								 <!-- Proveedores -->

								<li class="treeview <?php if(($cur_page == 'proveedores.php') || ($cur_page == 'verCuentasProveedores.php')  || ($cur_page == 'proveedoresCuentas.php') || ($cur_page == 'cuentaProveedores.php') || ($cur_page == 'proveedores-add.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-cubes"></i>
										<span>Proveedores</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="proveedores.php"><i class="fa fa-sticky-note"></i>Proveedores</a></li>
									</ul>
									<ul class="treeview-menu">
										<li><a href="proveedoresCuentas.php"><i class="fa fa-sticky-note"></i>Proveedores Carpintería</a></li>
									</ul>
								</li>
						       

								<li class="treeview <?php if( ($cur_page == 'ventas.php') || ($cur_page == 'clientes.php') || ($cur_page == 'presupuestos.php') || ($cur_page == 'notaCredito.php') || ($cur_page == 'recibos.php')|| ($cur_page == 'estado-cuenta.php') || ($cur_page == 'reporte.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-usd"></i>
										<span>Home</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="ventas.php"><i class="fa fa-usd"></i>Documentos</a></li>
										<li><a href="clientes.php"><i class="fa fa-users"></i> Clientes</a></li>
										<li><a href="presupuestos.php"><i class="fa fa-usd"></i> Presupuestos</a></li>
										<li><a href="notaCredito.php"><i class="fa fa-usd"></i> Notas de Crédito</a></li>
										<li><a href="recibos.php"><i class="fa fa-usd"></i> Recibos</a></li>
										<li><a href="estado-cuenta.php"><i class="fa fa-cart-plus"></i> Estado de Cuenta</a></li>
										<!-- <li><a href="reporte.php"><i class="fa fa-bar-chart"></i> Reportes</a></li> -->
									</ul>
								</li>

								<!-- Comodatos -->
								<li class="treeview <?php if($cur_page == 'comodato.php') {echo 'active';} ?>">
						          <a href="comodato.php">
						            <i class="fa fa-handshake-o"></i> <span>Comodato</span>
						          </a>
						        </li>

								<!-- Caja -->

								<li class="treeview <?php if( ($cur_page == 'caja.php') || ($cur_page == 'cajaDiaria.php') || ($cur_page == 'cajaChica.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-usd"></i>
										<span>Caja</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="caja.php"><i class="fa fa-usd"></i>Caja Por Turno</a></li>
										<li><a href="cajaChica.php"><i class="fa fa-usd"></i>Caja Chica</a></li>
										
									</ul>
								</li>


						        <li class="treeview <?php if( ($cur_page == 'product.php') || ($cur_page == 'product-add.php') || ($cur_page == 'product-edit.php') ) {echo 'active';} ?>">
						          <a href="product.php">
						            <i class="fa fa-bookmark"></i> <span>Productos</span>
						          </a>
						        </li>

			      			</ul>
      						<?php
      						break;
      					case 'Publisher':
      					?>
      						<!-- html for Publisher -->
      						<ul class="sidebar-menu">
      				
						        <li class="treeview <?php if($cur_page == 'index.php') {echo 'active';} ?>">
						          <a href="index.php">
						            <i class="fa fa-tachometer"></i> <span>Inicio</span>
						          </a>
						        </li>

								<!-- Menu Producto-->
								<li class="treeview <?php if( ($cur_page == 'product.php') || ($cur_page == 'product-add.php') || ($cur_page == 'product-edit.php') ) {echo 'active';} ?>">
						          <a href="product.php">
						            <i class="fa fa-bookmark"></i> <span>Productos</span>
						          </a>
						        </li>

								<!--<li class="treeview <?php if( ($cur_page == 'ventas.php') || ($cur_page == 'clientes.php') || ($cur_page == 'presupuestos.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-usd"></i>
										<span>Ventas</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="ventas.php"><i class="fa fa-usd"></i> Ventas</a></li>
										<li><a href="clientes.php"><i class="fa fa-users"></i> Clientes</a></li>
										<li><a href="presupuestos.php"><i class="fa fa-usd"></i> Presupuestos</a></li>
										<li><a href="estado-cuenta.php"><i class="fa fa-cart-plus"></i> Estado de Cuenta</a></li>
										<li><a href="reporte.php"><i class="fa fa-bar-chart"></i> Reportes</a></li>
									</ul>
								</li>-->


						        <!--<li class="treeview <?php if( ($cur_page == 'product.php') || ($cur_page == 'product-add.php') || ($cur_page == 'product-edit.php') ) {echo 'active';} ?>">
						          <a href="product.php">
						            <i class="fa fa-bookmark"></i> <span>Productos</span>
						          </a>
						        </li>-->



								<li class="treeview <?php if( ($cur_page == 'size.php') || ($cur_page == 'size-add.php') || ($cur_page == 'size-edit.php') || ($cur_page == 'color.php') || ($cur_page == 'color-add.php') || ($cur_page == 'color-edit.php') || ($cur_page == 'country.php') || ($cur_page == 'country-add.php') || ($cur_page == 'country-edit.php') || ($cur_page == 'shipping-cost.php') || ($cur_page == 'shipping-cost-edit.php') || ($cur_page == 'settings.php') || ($cur_page == 'slider.php') || ($cur_page == 'service.php') || ($cur_page == 'photo.php') || ($cur_page == 'video.php') || ($cur_page == 'testimonial.php') || ($cur_page == 'post.php') ||($cur_page == 'post-add.php') ||($cur_page == 'post-edit.php') || ($cur_page == 'category.php') || ($cur_page == 'category-add.php') || ($cur_page == 'category-edit.php') || ($cur_page == 'faq.php') || ($cur_page == 'order.php') || ($cur_page == 'rating.php') || ($cur_page == 'social-media.php') || ($cur_page == 'customer.php') || ($cur_page == 'customer-add.php') || ($cur_page == 'customer-edit.php') || ($cur_page == 'advertisement.php') || ($cur_page == 'subscriber.php') || ($cur_page == 'customer-message.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-shopping-bag"></i>
										<span>E-Commerce</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="customer.php"><i class="fa fa-users"></i> Clientes E-Commerce</a></li>
										<li><a href="customer-message.php"><i class="fa fa-envelope-o"></i> Mensajes de Clientes</a></li>
										<li><a href="order.php"><i class="fa fa-truck"></i> Pedidos</a></li>
										<li><a href="slider.php"><i class="fa fa-camera-retro"></i> Portadas</a></li>
										<li><a href="size.php"><i class="fa fa-tags"></i> Talles / Tamaños</a></li>
										<li><a href="color.php"><i class="fa fa-dashboard"></i> Colores</a></li>
										<li><a href="country.php"><i class="fa fa-globe"></i> Países</a></li>
										<li><a href="shipping-cost.php"><i class="fa fa-truck"></i> Costos de Envío</a></li>
										<li><a href="service.php"><i class="fa fa-cog"></i> Servicios</a></li>
										<li><a href="testimonial.php"><i class="fa fa-comments"></i> Comentarios</a></li>
										<li><a href="settings.php"><i class="fa fa-cogs"></i> Configuraciones E-Commerce</a></li>
										<li class="treeview <?php if( ($cur_page == 'photo.php') || ($cur_page == 'video.php') ) {echo 'active';} ?>">
											<a href="#">
												<i class="fa fa-film"></i>
												<span>Galería</span>
												<span class="pull-right-container">
													<i class="fa fa-angle-left pull-right"></i>
												</span>
											</a>
											<ul class="treeview-menu">
												<li><a href="photo.php"><i class="fa fa-picture-o"></i> Galería de Fotos</a></li>
												<li><a href="video.php"><i class="fa fa-video-camera"></i> Galería de Videos</a></li>
											</ul>
										</li>
										<li class="treeview <?php if( ($cur_page == 'post.php') ||($cur_page == 'post-add.php') ||($cur_page == 'post-edit.php') || ($cur_page == 'category.php') || ($cur_page == 'category-add.php') || ($cur_page == 'category-edit.php') ) {echo 'active';} ?>">
											<a href="#">
												<i class="fa fa-newspaper-o"></i>
												<span>Blog</span>
												<span class="pull-right-container">
													<i class="fa fa-angle-left pull-right"></i>
												</span>
											</a>
											<ul class="treeview-menu">
												<li><a href="category.php"><i class="fa fa-newspaper-o"></i> Categorías</a></li>
												<li><a href="post.php"><i class="fa fa-newspaper-o"></i> Posteos</a></li>
											</ul>
										</li>
										<li><a href="page.php"><i class="fa fa-file"></i> Página</a></li>
										<li><a href="rating.php"><i class="fa fa-star"></i> Rating</a></li>
										<li><a href="social-media.php"><i class="fa fa-share-alt"></i> Redes Sociales</a></li>
										<li><a href="faq.php"><i class="fa fa-question"></i> Preguntas Frecuentes</a></li>
										<li><a href="advertisement.php"><i class="fa fa-bullhorn"></i> Promos</a></li>
										<li><a href="subscriber.php"><i class="fa fa-rss"></i> Suscriptores</a></li>
									</ul>
								</li>


								 <!--<li class="treeview <?php if( ($cur_page == 'usuarios.php') || ($cur_page == 'top-category.php') || ($cur_page == 'top-category-add.php') || ($cur_page == 'top-category-edit.php') || ($cur_page == 'mid-category.php') || ($cur_page == 'mid-category-add.php') || ($cur_page == 'mid-category-edit.php') || ($cur_page == 'end-category.php') || ($cur_page == 'end-category-add.php') || ($cur_page == 'end-category-edit.php') || ($cur_page == 'language.php')) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-key"></i>
										<span>Configuraciones</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									<ul class="treeview-menu">
										<li><a href="top-category.php"><i class="fa fa-tags"></i> Secciones</a></li>
										<li><a href="mid-category.php"><i class="fa fa-tags"></i> Rubros</a></li>
										<li><a href="end-category.php"><i class="fa fa-tags"></i> Sub-Rubros</a></li>
									</ul>
								</li>-->

			      			</ul>
      					<?php
      					break;
      					case 'Fabrica':
      					?>
      						<!-- html for Publisher -->
      						<ul class="sidebar-menu">
      				
						        <li class="treeview <?php if($cur_page == 'index.php') {echo 'active';} ?>">
						          <a href="index.php">
						            <i class="fa fa-tachometer"></i> <span>Inicio</span>
						          </a>
						        </li>
	<!-- Fabrica -->

								<li class="treeview <?php if( ($cur_page == 'pedidos.php') || ($cur_page == 'pedidoEstado.php') ) {echo 'active';} ?>">
									<a href="#">
										<i class="fa fa-industry"></i>
										<span>Pedidos a Fábrica</span>
										<span class="pull-right-container">
											<i class="fa fa-angle-left pull-right"></i>
										</span>
									</a>
									
									<ul class="treeview-menu">
										<li><a href="ordenes.php"><i class="fa fa-sticky-note"></i>Órdenes de Trabajo</a></li>
									</ul>
								</li>

			      			</ul>
      					<?php
      					break;
      					default:
      						# code...
      						break;
      				}
      			?>
    		</section>
  		</aside>

  		<div class="content-wrapper">
