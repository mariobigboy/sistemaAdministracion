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

		?>
	
<h1>Caja Diaria Por Turno/Usuarios: </h1>
</section>	
<hr class="hr-dark">

<section class="content">
	

	
	<?php 
		 $statement = $pdo->prepare("SELECT * FROM tbl_user ORDER BY full_name;");
		$statement->execute();
		$result1 = $statement->fetchAll(PDO::FETCH_ASSOC);
		for ($i=0; $i < sizeof($result1); $i++) { 
			$user= $result1[$i]['role'];
			//echo '$usuario';
			if ($user == 'Empleado' || $user == 'Admin') { 
				//venta totales del dia por sucursal
				$usuario= $result1[$i]['full_name'];
				$statement = $pdo->prepare("SELECT SUM(total) as suma_total FROM factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND usuario = '$usuario';");
				$statement->execute();
				$result2 = $statement->fetchAll(PDO::FETCH_ASSOC);
				//echo $result2[0]['suma_total'];
				$total_venta_del_dia1 = ($result2[0]['suma_total']=='')? "0.00" : $result2[0]['suma_total'];
				//echo $total_venta_del_dia1;

				?>
			<h4>Ventas por empleado: <strong><?php echo $usuario; ?></strong> </h4>
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
					// $statement = $pdo->prepare("SELECT SUM(f.total) as suma_total, SUM(p.subt1) as pago1, SUM(p.subt2) as pago2, SUM(p.subt3) as pago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND f.usuario = '$usuario';");
					//Efectivo
					$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 1)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleEfectivo1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 1)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleEfectivo2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 1)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleEfectivo3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleEfectivo = $detalleEfectivo1 + $detalleEfectivo2 + $detalleEfectivo3; 


					//Tarjeta
					$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 2)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleTarjeta1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 2)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleTarjeta2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 2)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleTarjeta3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleTarjeta = $detalleTarjeta1 + $detalleTarjeta2 + $detalleTarjeta3;  


					//Cheques
					$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 3)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCheque1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 3)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCheque2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 3)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCheque3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleCheque = $detalleCheque1 + $detalleCheque2 + $detalleCheque3;

					//Cheques
					$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 4)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCuenta1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 4)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCuenta2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 4)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCuenta3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleCuenta = $detalleCuenta1 + $detalleCuenta2 + $detalleCuenta3;

					$statement = $pdo->prepare("SELECT SUM(pago) as pagos FROM cuenta_corriente  WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND usuario = '$usuario';");
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
							<span class="info-box-text">Cobros de Cta Cte</span>
							<span class="info-box-number"><?php echo "$".number_format(floatval($totalPagosCuenta), 2); ?></span>
						</div>
					</div>
				</div> 


			</div>
				<hr class="hr-dark">



				
		<?php 	}

		 } //end for
	 ?>
	



</section>

	<?php }else {

		$usuario= $_SESSION['user']['full_name'];
		//venta totales del dia por sucursal
				$statement = $pdo->prepare("SELECT SUM(total) as suma_total FROM factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND usuario = '$usuario';");
				$statement->execute();
				$result2 = $statement->fetchAll(PDO::FETCH_ASSOC);
				//echo $result2[0]['suma_total'];
				$total_venta_del_dia1 = ($result2[0]['suma_total']=='')? "0.00" : $result2[0]['suma_total']; ?>
				

<section>

<h1>Caja Diaria : <?php echo $_SESSION['user']['full_name']; ?></h1>

</section>

<section class="content">
	<div class="row">


		

		<?php 	$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 1)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleEfectivo1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 1)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleEfectivo2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 1)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleEfectivo3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleEfectivo = $detalleEfectivo1 + $detalleEfectivo2 + $detalleEfectivo3; 


					//Tarjeta
					$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 2)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleTarjeta1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 2)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleTarjeta2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 2)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleTarjeta3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleTarjeta = $detalleTarjeta1 + $detalleTarjeta2 + $detalleTarjeta3;  


					//Cheques
					$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 3)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCheque1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 3)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCheque2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 3)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCheque3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleCheque = $detalleCheque1 + $detalleCheque2 + $detalleCheque3;

					//Cheques
					$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo1 = 4)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCuenta1 = ($result3[0]['metodoPago1']=='')? "0" : $result3[0]['metodoPago1'];

					$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo2 = 4)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCuenta2 = ($result3[0]['metodoPago2']=='')? "0" : $result3[0]['metodoPago2'];

					$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND (metodo3 = 4)  AND f.usuario = '$usuario';");
					$statement->execute();
					$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
					$detalleCuenta3 = ($result3[0]['metodoPago3']=='')? "0" : $result3[0]['metodoPago3'];

					$total_detalleCuenta = $detalleCuenta1 + $detalleCuenta2 + $detalleCuenta3; 

					$statement = $pdo->prepare("SELECT SUM(pago) as pagos FROM cuenta_corriente  WHERE DATE(FROM_UNIXTIME(fecha)) = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 hour), '%Y-%m-%d') AND usuario = '$usuario';");
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
							<span class="info-box-text">Cobros de Cta Cte</span>
							<span class="info-box-number"><?php echo "$".number_format(floatval($totalPagosCuenta), 2); ?></span>
						</div>
					</div>
				</div> 

			</div>
		
	<hr class="hr-dark">

<?php  } ?> <!-- vista de Empleado -->
	

</div>

			</div>
</section>

<?php require_once('footer.php'); ?>