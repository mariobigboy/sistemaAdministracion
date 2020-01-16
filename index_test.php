<?php require_once('header.php'); ?>

<section class="content-header">
	<h1> Tablero </h1>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_top_category");
$statement->execute();
$total_top_category = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_mid_category");
$statement->execute();
$total_mid_category = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_end_category");
$statement->execute();
$total_end_category = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_product");
$statement->execute();
$total_product = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=?");
$statement->execute(array('Completed'));
$total_order_completed = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE shipping_status=?");
$statement->execute(array('Completed'));
$total_shipping_completed = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=?");
$statement->execute(array('Pending'));
$total_order_pending = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=? AND shipping_status=?");
$statement->execute(array('Completed','Pending'));
$total_order_complete_shipping_pending = $statement->rowCount();
?>

<section class="content">
	<div class="row">
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Precio Dolar</span>
					<span id="precioDolar" class="info-box-number"><?php 
						$amount = 1;
						//precio del dolar:
						$file = 'http://data.fixer.io/api/latest?access_key=32eacd7af4483118cc230e8b78eea4f5&symbols=EUR,ARS,USD&base=EUR';
						$response = json_decode(file_get_contents($file));
						
						$base = $response->base;
						$ars = floatval($response->rates->ARS);
						$usd = floatval($response->rates->USD);
						
						//conversión
						$eurToUsd = $amount / $usd;
						$usdToArs = $eurToUsd * $ars;
						echo ' USD '.number_format($usdToArs, 2, ',', '.');
					 ?></span>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-green"><i class="fa fa-shopping-basket"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Pedidos Completados</span>
					<span class="info-box-number"><?php echo $total_order_completed; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-green"><i class="fa fa-truck"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Envíos Completados</span>
					<span class="info-box-number"><?php echo $total_shipping_completed; ?></span>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-aqua"><i class="fa fa-tags"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Secciones</span>
					<span class="info-box-number"><?php echo $total_top_category; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-aqua"><i class="fa fa-tags"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Rubros</span>
					<span class="info-box-number"><?php echo $total_mid_category; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-aqua"><i class="fa fa-tags"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Sub-Rubros</span>
					<span class="info-box-number"><?php echo $total_end_category; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-yellow"><i class="fa fa-shopping-basket"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Productos</span>
					<span class="info-box-number"><?php echo $total_product; ?></span>
				</div>
			</div>
		</div>
		
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-red"><i class="fa fa-shopping-basket <?php if($total_order_pending>0){echo ' shake';} ?>"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Pedidos Pendientes</span>
					<span class="info-box-number"><?php echo $total_order_pending; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-red"><i class="fa fa-truck <?php if($total_order_complete_shipping_pending>0){echo ' shake';} ?>"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Envíos pendientes (Pago completado)</span>
					<span class="info-box-number"><?php echo $total_order_complete_shipping_pending; ?></span>
				</div>
			</div>
		</div>
		
		<!--<div class="col-lg-12 col-md-12 col-xs-12">
			<div class="box box-info">
				<div class="box-body">
					<h3> <i class="fa fa-exclamation-circle"></i> ¡Importante!</h3>
					<div class="alert alert-danger" role="alert">
						<strong>Debe</strong> dar de baja x productos de las sucursales. <a href="stock.php?id=">Ver</a>
					</div>
				</div>
			</div>
		</div>-->
	</div>
</section>

<?php require_once('footer.php'); ?>