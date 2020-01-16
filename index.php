<?php require_once('header.php'); ?>

<section class="content-header">
	<h1> Dashboard</h1>
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

		<?php if ($_SESSION['user']['role']=='Admin' || $_SESSION['user']['role']=='Super Admin' || $_SESSION['user']['role']=='Publisher') {
			# code...
		 ?>

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
					<?php if($total_order_pending>0){
						?>
							<a class="info-box-text" href="order.php">Pedidos Pendientes</a>
						<?php
					}else{
						?>
							<span class="info-box-text">Pedidos Pendientes</span>
						<?php
					} ?>
					<span class="info-box-number"><?php echo $total_order_pending; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-red"><i class="fa fa-truck <?php if($total_order_complete_shipping_pending>0){echo ' shake';} ?>"></i></span>
				<div class="info-box-content">
					<?php if($total_order_complete_shipping_pending>0){
						?>
							<a class="info-box-text" href="order.php">Envíos pendientes (Pago completado)</a>
						<?php
					}else{
						?>
							<span class="info-box-text">Envíos pendientes (Pago completado)</span>
						<?php
					} ?>
					<span class="info-box-number"><?php echo $total_order_complete_shipping_pending; ?></span>
				</div>
			</div>
		</div>
			<?php } ?>

			<?php if ($_SESSION['user']['role']=='Super Admin' || $_SESSION['user']['role']=='Admin') { 
		//ventas del día:
	$statement = $pdo->prepare("SELECT SUM(total) as suma_total FROM factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d');");
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	$total_venta_del_dia = ($result[0]['suma_total']=='')? "0.00" : $result[0]['suma_total'];

	$statement = $pdo->prepare("SELECT SUM(monto) as suma_gastos FROM cajaChica WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND movimiento = 3;");
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	$total_gastos = ($result[0]['suma_total']=='')? "0.00" : $result[0]['suma_total'];

	//SELECT SUM(deuda), SUM(pago) FROM cuenta_corriente
$statement = $pdo->prepare("SELECT SUM(deuda) as deudas, SUM(pago) as pagos FROM cuenta_corriente");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
$total_deudas_cuentas= $result[0]['deudas'] - $result[0]['pagos'];

		?>

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Ventas del día (Todas): </span>
					<span class="info-box-number"><?php echo "$".number_format(floatval($total_venta_del_dia), 2); ?></span>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-red"><i class="fa fa-arrow-down"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Gastos Caja del dia (Todas): </span>
					<span class="info-box-number"><?php echo "$".number_format(floatval($total_gastos), 2); ?></span>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-red"><i class="fa fa-arrow-down"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Deudas Pendientes Cta Cte: </span>
					<span class="info-box-number"><?php echo "$".number_format(floatval($total_deudas_cuentas), 2); ?></span>
				</div>
			</div>
		</div>

	<?php } ?>
		
	</div>
</section>

<?php require_once('footer.php'); ?>