<?php require_once('header.php'); ?>

<section class="content-header">
	<h1>Ventas</h1>
	<br>
	<a href="nuevaVenta.php" class="btn btn-success">Nueva Venta</a href="nuevaVenta.php">
</section>

<?php
	//$statement = $pdo->prepare("SELECT * FROM tbl_top_category");
	//$statement->execute();
	//$total_top_category = $statement->rowCount();
?>

<section class="content">
	<div class="row">
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-aqua"><i class="fa fa-hand-o-right"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Presupuestos</span>
					<span class="info-box-number"><?php #echo $total_top_category; ?></span>
				</div>
			</div>
		</div>

		<!-- contenedor de tabla -->
		<div class="col-lg-12">
			<div class="box-body table-responsive">
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th width="25">N°</th>
							<th width="25">N° Presupuesto</th>
							<th width="200">Cliente</th>
							<th width="200">Fecha</th>
							<th width="200">Importe</th>
							<!--<th width="60">Precio Anterior</th>-->
							<th width="60">Acciones</th>
							
						</tr>
					</thead>
					<tbody>
						<?php
						$i=0;
						$statement = $pdo->prepare("SELECT t1.*, FROM_UNIXTIME(fecha, '%d/%m/%Y %k:%i') fecha_format, t2.c_nombre, t2.c_apellido FROM `presupuesto` as t1 INNER JOIN tbl_cliente as t2 ON t1.id_cliente = t2.c_id WHERE 1 ORDER BY fecha DESC;");
						$statement->execute();
						$result = $statement->fetchAll(PDO::FETCH_ASSOC);
						foreach ($result as $row) {
							$i++;
							?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $row['id_presupuesto']; ?></td>
								<td><?php echo $row['c_apellido']." ".$row['c_nombre']; ?></td>
								<td><?php echo $row['fecha_format']; ?></td>
								<td><?php echo $row['total']; ?></td>
								
								<td>
									<a href="imprimir_factura.php?id=<?php echo $row['id_presupuesto']; ?>" target="_blank" class="btn btn-danger btn-xs col-lg-12" ><i class="fa fa-file-pdf-o"></i> Factura </a>						
								</td>
							</tr>
							<?php
						}
						?>							
					</tbody>
				</table>
			</div>
		</div>
		
		
	</div>
</section>

<?php require_once('footer.php'); ?>