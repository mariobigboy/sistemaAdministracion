<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Proveedores</h1>
	</div>
	<div class="content-header-right">
		<a href="proveedores-add.php" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Nuevo Proveedor</a>
		<!--<a href="libs/codebar/index.php" class="btn btn-warning btn-sm" target="_blank"> <i class="fa fa-barcode"></i> Imprimir codebars</a>-->
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<!-- code -->
					<table id="tablaClientes" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>N°</th>
								<th>ID</th>
								<th width="200">Nombre o Razón Social</th>
								<th>CUIT-CUIL</th>
								<!--<th>Provincia</th>
								<th>Localidad</th>-->
								<!--<th width="60">Cantidad</th>-->
								<!--<th>Sucursal</th>-->
								<!--<th>¿Destacado?</th>-->
								<!-- <th>¿Activo?</th> -->
								<th>Estado</th>
								<th width="80">Cuentas</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							
							$statement = $pdo->prepare("SELECT * FROM proveedores ORDER BY nombre;");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							foreach ($result as $row) {
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $row['id']; ?></td>
									<td><?php echo $row['nombre']; ?></td>
									<td><?php echo $row['cuil']; ?></td>
								
									<td>
										<?php 
										
											$statement1 = $pdo->prepare("SELECT * FROM cuentasProveedores WHERE estado = 0 AND idProveedor = ?;");
											$statement1->execute(array(
												$row['id']
											));
											$resultadoCuentas = $statement1->rowCount();
											if ($resultadoCuentas >0) {
												?>
												<img src="img/200/red.png" alt="">
												<?php 
											}else{
												?>
												<img src="img/200/green.png" alt="">
												<?php 

											}
										?>
									</td>
									<td>										
										<a href="cuentaProveedor.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs">Ver</a>
										
									  
										
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
	</div>
</section>




<?php require_once('footer.php'); ?>