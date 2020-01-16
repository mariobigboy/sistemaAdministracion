<?php 
	require_once('header.php');
	$s_id = isset($_GET['id'])? $_GET['id'] : 0;
	$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_id=?;");
	$statement->execute(array($s_id));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	//$total_top_category = $statement->rowCount();
 ?>



<section class="content-header">
	<h1>Sucursal: <?php echo $result[0]['s_name']; ?></h1>
</section>
<hr class="hr-dark">
<?php
	//total de productos:
	$statement = $pdo->prepare("SELECT SUM(t1.sk_stock) as total FROM tbl_stock as t1 WHERE t1.sk_id_sucursal = ?");
	$statement->execute(array($s_id));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	$total_productos = ($result[0]['total']=='')? 0 : $result[0]['total'];

	//ventas del día:
	$statement = $pdo->prepare("SELECT SUM(total) as suma_total FROM factura WHERE DATE(FROM_UNIXTIME(fecha)) = CURDATE() AND sucursal = ?;");
	$statement->execute(array($s_id));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	$total_venta_del_dia = ($result[0]['suma_total']=='')? "0.00" : $result[0]['suma_total'];

	//total productos sin stock:
	$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_qty = ?");
	$statement->execute(array(0));
	$productos_sin_stock = $statement->rowCount();
?>

<section class="content">
	<div class="row">
		<!--<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-yellow"><i class="fa fa-shopping-cart"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Productos: </span>
					<span class="info-box-number"><?php echo $total_productos; ?></span>
				</div>
			</div>
		</div>-->

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Ventas del día: </span>
					<span class="info-box-number"><?php echo "$".number_format(floatval($total_venta_del_dia), 2); ?></span>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-red"><i class="fa fa-exclamation-circle"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Productos sin Stock: </span>
					<span class="info-box-number"><?php echo $productos_sin_stock; ?></span>
				</div>
			</div>
		</div>
		
	</div>
	<hr class="hr-dark">
</section>

<?php require_once('footer.php'); ?>