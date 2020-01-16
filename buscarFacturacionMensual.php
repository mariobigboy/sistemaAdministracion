<?php 
require_once('header.php'); 
$fecha = isset($_GET['datepicker']) ? $_GET['datepicker'] : "";
$date= date_create($fecha);
$fechaFormateada= date_format($date, 'Y-m-d');
//echo $fechaFormateada;

$fecha3 = isset($_GET['datepicker1']) ? $_GET['datepicker1'] : "";
$date3= date_create($fecha3);
$fechaFormateada3= date_format($date3, 'Y-m-d');

?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Facturación del día: <?php echo $fecha; ?></h1>
	</div>
	<div class="content-header-right">
		<a href="javascript:history.back()" class="btn btn-primary btn-sm"> Volver Atrás</a>
		<!-- <a href="clientes-add.php" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Nuevo Cliente</a> -->
		<!--<a href="libs/codebar/index.php" class="btn btn-warning btn-sm" target="_blank"> <i class="fa fa-barcode"></i> Imprimir codebars</a>-->
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<!-- code -->
					<?php if ($_GET['tipo']==1) {
						$suc= $_GET['sucursal'];

						?>
						<table id="tablaClientesCuentas" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>ID</th>
									<th width="200">Sucursal</th>
									<th>Efectivo</th>
									<th>Tarjetas</th>
									<th>Cheques</th>
									<th>Cta Cte</th>
									<th>Cobros Cta Cte</th>
									<th>Total</th>

								</tr>
							</thead>
							<tbody>
								<?php
								$fecha1 = $fechaFormateada." 00:00:00";
								$fecha2 = $fechaFormateada3." 23:59:59";

								// total cobro cuenta corriente
								$statement = $pdo->prepare("SELECT SUM(cc.pago) as pagos FROM cuenta_corriente as cc INNER JOIN tbl_user as u ON u.full_name= cc.usuario  INNER JOIN tbl_sucursales as s ON s.s_id = u.sucursal  WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND s.s_id= '$suc';");
								$statement->execute();
								$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
								$totalPagosCuenta = ($result3[0]['pagos']=='')? 0 : $result3[0]['pagos'];

								//cuento totales
								$statement = $pdo->prepare("SELECT SUM(total) as suma_total FROM factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND sucursal= '$suc';");
								$statement->execute();
								$result = $statement->fetchAll(PDO::FETCH_ASSOC);
								$total_venta_del_dia = ($result[0]['suma_total']=='')? 0 : $result[0]['suma_total'];
								$total_venta_del_dia += $totalPagosCuenta;

								//EFECTIVO

								$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo1 = 1)  AND f.sucursal = $suc;");
								$statement->execute();
								$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
								$detalleEfectivo1 = ($result3[0]['metodoPago1']=='')? 0 : $result3[0]['metodoPago1'];

								$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo2 = 1)  AND f.sucursal = $suc;");
								$statement->execute();
								$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
								$detalleEfectivo2 = ($result3[0]['metodoPago2']=='')? 0 : $result3[0]['metodoPago2'];

								$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo1 = 1)  AND f.sucursal = $suc;");
								$statement->execute();
								$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
								$detalleEfectivo3 = ($result3[0]['metodoPago3']=='')? 0 : $result3[0]['metodoPago3'];

								$total_detalleEfectivo = $detalleEfectivo1 + $detalleEfectivo2 + $detalleEfectivo3; 

								//Tarjeta
								$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo1 = 2)  AND f.sucursal = $suc;");
								$statement->execute();
								$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
								$detalleTarjeta1 = ($result3[0]['metodoPago1']=='')? 0 : $result3[0]['metodoPago1'];

								$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo2 = 2)  AND f.sucursal = $suc;");
								$statement->execute();
								$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
								$detalleTarjeta2 = ($result2[0]['metodoPago2']=='')? 0 : $result2[0]['metodoPago2'];

								$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo3 = 2)  AND f.sucursal = $suc;");
								$statement->execute();
								$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
								$detalleTarjeta3 = ($result3[0]['metodoPago3']=='')? 0 : $result3[0]['metodoPago3'];

								$total_detalleTarjeta = $detalleTarjeta1 + $detalleTarjeta2 + $detalleTarjeta3;  

								//Cheques
								$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo1 = 3)  AND f.sucursal = $suc;");
								$statement->execute();
								$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
								$detalleCheque1 = ($result3[0]['metodoPago1']=='')? 0 : $result3[0]['metodoPago1'];

								$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo2 = 3)  AND f.sucursal = $suc;");
								$statement->execute();
								$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
								$detalleCheque2 = ($result3[0]['metodoPago2']=='')? 0 : $result3[0]['metodoPago2'];

								$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo3 = 3)  AND f.sucursal = $suc;");
								$statement->execute();
								$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
								$detalleCheque3 = ($result3[0]['metodoPago3']=='')? 0 : $result3[0]['metodoPago3'];

								$total_detalleCheque = $detalleCheque1 + $detalleCheque2 + $detalleCheque3;

					            //Ctas ctes
								$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo1 = 4)  AND f.sucursal = $suc;");
								$statement->execute();
								$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
								$detalleCuenta1 = ($result3[0]['metodoPago1']=='')? 0 : $result3[0]['metodoPago1'];

								$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo2 = 4)  AND f.sucursal = $suc;");
								$statement->execute();
								$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
								$detalleCuenta2 = ($result3[0]['metodoPago2']=='')? 0 : $result3[0]['metodoPago2'];

								$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo3 = 4)  AND f.sucursal = $suc;");
								$statement->execute();
								$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
								$detalleCuenta3 = ($result3[0]['metodoPago3']=='')? 0 : $result3[0]['metodoPago3'];

								$total_detalleCuenta = $detalleCuenta1 + $detalleCuenta2 + $detalleCuenta3;

								//cuando haya ganas y tiempo esta funciona de 10
								//SELECT SUM(p.subt1) as metodoPago1,SUM(p.subt2) as metodoPago2, SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('2019-06-07 00:00:00') AND UNIX_TIMESTAMP('2019-06-07 23:59:59') AND (metodo2 = 4 OR metodo1 = 4 OR metodo3 = 4)  AND f.sucursal = 2

								//nombre local

								$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_id= '$suc';");
								$statement->execute();
								$result1 = $statement->fetchAll(PDO::FETCH_ASSOC);
								?>
								<tr><td>
									<?php echo $result1[0]['s_id']; ?>
								</td><td>
									<?php echo $result1[0]['s_name']; ?>
								</td>
								<td><?php echo $total_detalleEfectivo; ?></td> <!-- efectivo -->
								<td><?php echo $total_detalleTarjeta; ?></td> <!-- tarjeta -->
								<td><?php echo $total_detalleCheque; ?></td> <!-- Cheque -->
								<td><?php echo $total_detalleCuenta; ?></td> <!-- Cuenta -->
								<td><?php echo $totalPagosCuenta; ?></td> <!-- cobros cuenta corriente -->
								<td><?php echo $total_venta_del_dia; ?></td> <!-- total -->
							</tr>

						</tbody>

					</table>
				<?php }else{ ?>

					<table id="tablaClientesCuentas" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th width="200">Usuario</th>
								<th>Efectivo</th>
								<th>Tarjetas</th>
								<th>Cheques</th>
								<th>Cta Cte</th>
								<th>Cobros Cta Cte</th>
								<th>Total</th>

							</tr>
						</thead>
						<tbody>
							<?php
							$idusuario = $_GET['usuario'];
							$statement = $pdo->prepare("SELECT full_name FROM tbl_user WHERE id='$idusuario';");
							$statement->execute();
							$result5 = $statement->fetchAll(PDO::FETCH_ASSOC);
							$usuario = $result5[0]['full_name'];
								//echo "$usuario";
							$fecha1 = $fechaFormateada." 00:00:00";
							$fecha2 = $fechaFormateada3." 23:59:59";

							// total cobro cuenta corriente
								// $statement = $pdo->prepare("SELECT SUM(pago) as pagos FROM cuenta_corriente WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND usuario= '$usuario';");
								// $statement->execute();
								// $result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
								// $totalPagosCuenta = ($result3[0]['pagos']=='')? 0 : $result3[0]['pagos'];

							$statement = $pdo->prepare("SELECT SUM(total) as suma_total FROM factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND usuario= '$usuario';");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							$total_venta_del_dia = ($result[0]['suma_total']=='')? 0 : $result[0]['suma_total'];
							//$total_venta_del_dia += $totalPagosCuenta;

								//EFECTIVO

							$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo1 = 1)  AND f.usuario = '$usuario';");
							$statement->execute();
							$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
							$detalleEfectivo1 = ($result3[0]['metodoPago1']=='')? 0 : $result3[0]['metodoPago1'];

							$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo2 = 1)  AND f.usuario = '$usuario';");
							$statement->execute();
							$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
							$detalleEfectivo2 = ($result3[0]['metodoPago2']=='')? 0 : $result3[0]['metodoPago2'];

							$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo3 = 1)  AND f.usuario = '$usuario';");
							$statement->execute();
							$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
							$detalleEfectivo3 = ($result3[0]['metodoPago3']=='')? 0 : $result3[0]['metodoPago3'];

							$total_detalleEfectivo = $detalleEfectivo1 + $detalleEfectivo2 + $detalleEfectivo3; 

								//Tarjeta
							$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo1 = 2)  AND f.usuario = '$usuario';");
							$statement->execute();
							$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
							$detalleTarjeta1 = ($result3[0]['metodoPago1']=='')? 0 : $result3[0]['metodoPago1'];

							$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo2 = 2)  AND f.usuario = '$usuario';");
							$statement->execute();
							$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
							$detalleTarjeta2 = ($result3[0]['metodoPago2']=='')? 0 : $result3[0]['metodoPago2'];

							$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo3 = 2)  AND f.usuario = '$usuario';");
							$statement->execute();
							$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
							$detalleTarjeta3 = ($result3[0]['metodoPago3']=='')? 0 : $result3[0]['metodoPago3'];

							$total_detalleTarjeta = $detalleTarjeta1 + $detalleTarjeta2 + $detalleTarjeta3;  

								//Cheques
							$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo1 = 3)  AND f.usuario = '$usuario';");
							$statement->execute();
							$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
							$detalleCheque1 = ($result3[0]['metodoPago1']=='')? 0 : $result3[0]['metodoPago1'];

							$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo2 = 3)  AND f.usuario = '$usuario';");
							$statement->execute();
							$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
							$detalleCheque2 = ($result3[0]['metodoPago2']=='')? 0 : $result3[0]['metodoPago2'];

							$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo3 = 3)  AND f.usuario = '$usuario';");
							$statement->execute();
							$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
							$detalleCheque3 = ($result3[0]['metodoPago3']=='')? 0 : $result3[0]['metodoPago3'];

							$total_detalleCheque = $detalleCheque1 + $detalleCheque2 + $detalleCheque3;

					            //Ctas ctes
							$statement = $pdo->prepare("SELECT SUM(p.subt1) as metodoPago1 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo1 = 4)  AND f.usuario = '$usuario';");
							$statement->execute();
							$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
							$detalleCuenta1 = ($result3[0]['metodoPago1']=='')? 0 : $result3[0]['metodoPago1'];

							$statement = $pdo->prepare("SELECT SUM(p.subt2) as metodoPago2 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo2 = 4)  AND f.usuario = '$usuario';");
							$statement->execute();
							$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
							$detalleCuenta2 = ($result3[0]['metodoPago2']=='')? 0 : $result3[0]['metodoPago2'];

							$statement = $pdo->prepare("SELECT SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('$fecha1') AND UNIX_TIMESTAMP('$fecha2') AND (metodo3 = 4)  AND f.usuario = '$usuario';");
							$statement->execute();
							$result3 = $statement->fetchAll(PDO::FETCH_ASSOC);
							$detalleCuenta3 = ($result3[0]['metodoPago3']=='')? 0 : $result3[0]['metodoPago3'];

							$total_detalleCuenta = $detalleCuenta1 + $detalleCuenta2 + $detalleCuenta3;

								//cuando haya ganas y tiempo esta funciona de 10
								//SELECT SUM(p.subt1) as metodoPago1,SUM(p.subt2) as metodoPago2, SUM(p.subt3) as metodoPago3 FROM factura AS f INNER JOIN pagos as p ON f.num_factura=p.id_factura WHERE fecha BETWEEN UNIX_TIMESTAMP('2019-06-07 00:00:00') AND UNIX_TIMESTAMP('2019-06-07 23:59:59') AND (metodo2 = 4 OR metodo1 = 4 OR metodo3 = 4)  AND f.sucursal = 2

								//nombre local


							?>
							<tr><td>
								<?php echo $idusuario; ?>
							</td><td>
								<?php echo $usuario; ?>
							</td>
							<td><?php echo $total_detalleEfectivo; ?></td> <!-- efectivo -->
							<td><?php echo $total_detalleTarjeta; ?></td> <!-- tarjeta -->
							<td><?php echo $total_detalleCheque; ?></td> <!-- Cheque -->
							<td><?php echo $total_detalleCuenta; ?></td> <!-- Cuenta -->
							<td>No Computa</td> <!-- Cobros -->
							<td><?php echo $total_venta_del_dia; ?></td> <!-- total -->
						</tr>

					</tbody>

				</table>


			</tbody>

		</table>

	<?php } ?>
</div>
</div>
</div>
</div>
</section>


<?php require_once('footer.php'); ?>

<script type="text/javascript">
	
</script>