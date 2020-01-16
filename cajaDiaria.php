<?php 
	require_once('header.php');
	// $s_id = isset($_GET['id'])? $_GET['id'] : 0;
	// $statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_id=?;");
	// $statement->execute(array($s_id));
	// $result = $statement->fetchAll(PDO::FETCH_ASSOC);
	//$total_top_category = $statement->rowCount();
	//print_r($_SESSION['user']);
 ?>



<section class="content-header">
	<?php if ($_SESSION['user']['role']=='Super Admin' || $_SESSION['user']['role']=='Admin') { 
		//ventas del día:
	$statement = $pdo->prepare("SELECT SUM(total) as suma_total FROM factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d');");
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	$total_venta_del_dia = ($result[0]['suma_total']=='')? "0.00" : $result[0]['suma_total'];

	$statement = $pdo->prepare("SELECT SUM(pago) as pagos FROM cuenta_corriente  WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d');");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$totalPagosCuenta = ($result3[0]['pagos']=='')? "0" : $result3[0]['pagos'];

		?>
	
<h1>Caja Diaria: </h1>
</section>	
<hr class="hr-dark">

<section class="content">
	<div class="row">


		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Ventas del día (Todas): </span>
					<span class="info-box-number"><?php echo "$".number_format(floatval($total_venta_del_dia), 2); ?></span>
				</div>
			</div>

			<div class="info-box">
				<span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Cobros en efectivo ctas ctes (Todas): </span>
					<span class="info-box-number"><?php echo "$".number_format(floatval($totalPagosCuenta), 2); ?></span>
				</div>
			</div>
		</div>
	</div>
		<!-- <div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-red"><i class="fa fa-exclamation-circle"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Productos sin Stock: </span>
					<span class="info-box-number"><?php // echo $productos_sin_stock; ?></span>
				</div>
			</div>
		</div> -->
		
	<hr class="hr-dark">
	<?php 
		 $statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_active=1;");
		$statement->execute();
		$result1 = $statement->fetchAll(PDO::FETCH_ASSOC);
		for ($i=0; $i < sizeof($result1); $i++) { 
			$suc= $result1[$i]['s_id'];
			//echo $suc;
			if ($suc!= 5) { 
				//venta totales del dia por sucursal
				$statement = $pdo->prepare("SELECT SUM(total) as suma_total FROM factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND sucursal = $suc;");
				$statement->execute();
				$result2 = $statement->fetchAll(PDO::FETCH_ASSOC);
				//echo $result2[0]['suma_total'];
				$total_venta_del_dia1 = ($result2[0]['suma_total']=='')? "0.00" : $result2[0]['suma_total'];
				//echo $total_venta_del_dia1;

				?>
			<h4><?php echo $result1[$i]['s_name']; ?></h4>
			<div class="row">
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Ventas del día : </span>
							<span class="info-box-number"><?php echo "$".number_format(floatval($total_venta_del_dia1), 2); ?></span>
						</div>
					</div>
				</div>

				<?php 
					// $statement = $pdo->prepare("SELECT SUM(f.total) as suma_total, SUM(p.subt1) as pago1, SUM(p.subt2) as pago2, SUM(p.subt3) as pago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND f.sucursal = $suc;");
					//Efectivo
					$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 1)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleEfectivo1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 1)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleEfectivo2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 1)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleEfectivo3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleEfectivo = $detalleEfectivo1 + $detalleEfectivo2 + $detalleEfectivo3; 


					//Tarjeta
					$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 2)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleTarjeta1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 2)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleTarjeta2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 2)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleTarjeta3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleTarjeta = $detalleTarjeta1 + $detalleTarjeta2 + $detalleTarjeta3;  


					//Cheques
					$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 3)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCheque1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 3)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCheque2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 3)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCheque3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleCheque = $detalleCheque1 + $detalleCheque2 + $detalleCheque3;

					//Cta cte
					$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 4)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCuenta1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 4)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCuenta2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 4)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCuenta3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleCuenta = $detalleCuenta1 + $detalleCuenta2 + $detalleCuenta3;

					$statement = $pdo->prepare("SELECT SUM(cc.pago) as pagos FROM cuenta_corriente as cc INNER JOIN tbl_user as u ON u.full_name= cc.usuario  INNER JOIN tbl_sucursales as s ON s.s_id = u.sucursal  WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND s.s_id= $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$totalPagosCuenta = ($result3[0]['pagos']=='')? "0" : $result3[0]['pagos'];

					

				 ?>

				 <div class="col-md-4 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Efectivo </span>
							<span class="info-box-number"><?php echo "$".number_format(floatval($total_detalleEfectivo), 2); ?></span>
						</div>
					</div>
				</div>
				 <div class="col-md-4 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-aqua"><i class="fa fa-credit-card"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Tarjetas</span>
							<span class="info-box-number"><?php echo "$".number_format(floatval($total_detalleTarjeta), 2); ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-aqua"><i class="fa fa-clone"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Cheques</span>
							<span class="info-box-number"><?php echo "$".number_format(floatval($total_detalleCheque), 2); ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-aqua"><i class="fa fa-address-card"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Cuenta Corriente</span>
							<span class="info-box-number"><?php echo "$".number_format(floatval($total_detalleCuenta), 2); ?></span>
						</div>
					</div>
				</div> 

				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-aqua"><i class="fa fa-address-card"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Cobros Cta Cte</span>
							<span class="info-box-number"><?php echo "$".number_format(floatval($totalPagosCuenta), 2); ?></span>
						</div>
					</div>
				</div> 


			</div>
				<hr class="hr-dark">



				
		<?php 	}

		 } //end for
	 ?>
	
<div class="row">


</section>

	<?php }else {

		$suc= $_SESSION['user']['sucursal'];
		//venta totales del dia por sucursal
				$statement = $pdo->prepare("SELECT SUM(total) as suma_total FROM factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND sucursal = $suc;");
				$statement->execute();
				$result2 = $statement->fetchAll(PDO::FETCH_ASSOC);
				//echo $result2[0]['suma_total'];
				$total_venta_del_dia1 = ($result2[0]['suma_total']=='')? "0.00" : $result2[0]['suma_total']; 



				?>
				



<h1>Caja Diaria: </h1>
</section>	
<hr class="hr-dark">
<?php  
	$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_id='$suc';");
	$statement->execute(array($s_id));
	$resultName = $statement->fetchAll(PDO::FETCH_ASSOC); 


?>
<h4><?php echo $resultName[0]['s_name']; ?></h4>
<section class="content">
	<div class="row">


		

		<?php 	$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 1)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleEfectivo1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 1)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleEfectivo2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 1)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleEfectivo3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleEfectivo = $detalleEfectivo1 + $detalleEfectivo2 + $detalleEfectivo3; 


					//Tarjeta
					$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 2)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleTarjeta1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 2)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleTarjeta2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 2)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleTarjeta3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleTarjeta = $detalleTarjeta1 + $detalleTarjeta2 + $detalleTarjeta3;  


					//Cheques
					$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 3)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCheque1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 3)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCheque2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 3)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCheque3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleCheque = $detalleCheque1 + $detalleCheque2 + $detalleCheque3;

					//Cheques
					$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 4)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCuenta1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 4)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCuenta2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 4)  AND f.sucursal = $suc;");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCuenta3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleCuenta = $detalleCuenta1 + $detalleCuenta2 + $detalleCuenta3; ?>

					 <div class="col-md-4 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Efectivo </span>
							<span class="info-box-number"><?php echo "$".number_format(floatval($total_detalleEfectivo), 2); ?></span>
						</div>
					</div>
				</div>
				 <div class="col-md-4 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-aqua"><i class="fa fa-credit-card"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Tarjetas</span>
							<span class="info-box-number"><?php echo "$".number_format(floatval($total_detalleTarjeta), 2); ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-aqua"><i class="fa fa-clone"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Cheques</span>
							<span class="info-box-number"><?php echo "$".number_format(floatval($total_detalleCheque), 2); ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-aqua"><i class="fa fa-address-card"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Cuenta Corriente</span>
							<span class="info-box-number"><?php echo "$".number_format(floatval($total_detalleCuenta), 2); ?></span>
						</div>
					</div>
				</div>

			</div>
		
	<hr class="hr-dark">

<?php  } ?> <!-- vista de Empleado -->
	



<?php require_once('footer.php'); ?>