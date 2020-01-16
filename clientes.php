<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Clientes</h1>
	</div>
	<div class="content-header-right">
		<a href="clientes-add.php" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Nuevo Cliente</a>
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
								<th width="200">Cliente</th>
								<th>Documento</th>
								<!--<th>Provincia</th>
								<th>Localidad</th>-->
								<!--<th width="60">Cantidad</th>-->
								<!--<th>Sucursal</th>-->
								<!--<th>¿Destacado?</th>-->
								<th>¿Activo?</th>
								<!--<th>Categoría</th>-->
								<th width="80">Acción</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							/*$statement = $pdo->prepare("SELECT
														
														t1.p_id,
														t1.p_name,
														t1.p_old_price,
														t1.p_current_price,
														t1.p_qty,
														t1.p_featured_photo,
														t1.p_is_featured,
														t1.p_is_active,
														t1.ecat_id,

														t2.ecat_id,
														t2.ecat_name,

														t3.mcat_id,
														t3.mcat_name,

														t4.tcat_id,
														t4.tcat_name,

														t5.s_address

							                           	FROM tbl_product t1
							                           	JOIN tbl_end_category t2
							                           	ON t1.ecat_id = t2.ecat_id
							                           	JOIN tbl_mid_category t3
							                           	ON t2.mcat_id = t3.mcat_id
							                           	JOIN tbl_top_category t4
							                           	ON t3.tcat_id = t4.tcat_id
							                           	JOIN tbl_sucursales t5
							                           	ON t5.s_id = t1.p_sucursal_id
							                           	ORDER BY t1.p_id DESC
							                           	");*/
							/*$statement = $pdo->prepare("SELECT 
																t1.c_id, 
																t1.c_apellido, 
																t1.c_nombre, 
																t1.c_email,
																t1.c_nro_doc, 
																t1.c_tel, 
																t1.c_cel, 
																t1.c_cuit, 
																t1.c_id_provincia, 
																t1.c_id_localidad,
																t1.c_activo,
																
																t2.l_id,
																t2.l_name,

																t3.p_id,
																t3.p_name

																FROM tbl_cliente t1 
																JOIN tbl_localidad t2 
																ON t1.c_id_localidad = t2.l_id 
																JOIN tbl_provincia t3 
																ON t1.c_id_provincia = t3.p_id;"
																);*/
							$statement = $pdo->prepare("SELECT * FROM tbl_cliente ORDER BY c_apellido;");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							foreach ($result as $row) {
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $row['c_id']; ?></td>
									<td><?php echo $row['c_apellido'].' '.$row['c_nombre']; ?></td>
									<!--<td><?php #echo $row['p_old_price']; ?></td>-->
									<td><?php echo $row['c_nro_doc']; ?></td>
									<!--<td><?php #echo $row['p_name']; ?></td>-->
									<!--<td><?php #echo $row['l_name']; ?></td>-->
									<!--<td>
										<?php #if($row['p_is_featured'] == 1) {echo 'Sí';} else {echo 'No';} ?>
									</td>-->
									<td>
										<?php if($row['c_activo'] == 1) {echo 'Sí';} else {echo 'No';} ?>
									</td>
									<!--<td><?php #echo $row['tcat_name']; ?><br><?php #echo $row['mcat_name']; ?><br><?php #echo $row['ecat_name']; ?></td>-->
									<td>										
										<a href="clientes-edit.php?id=<?php echo $row['c_id']; ?>" class="btn btn-primary btn-xs">Editar</a>
										
										<a class="btn btn-warning btn-xs" href="nuevaVenta.php?id=<?php echo $row['c_id']; ?>" >Ventas</a>  
										
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