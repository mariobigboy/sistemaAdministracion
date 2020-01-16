<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Pedidos de Carpintería</h1>
	</div>
	<div class="content-header-right">
		<a href="carpinteria-add.php" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Nuevo Pedido</a>
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
								<th>IdPedido</th>
								<th >Nro Factura</th>
								<th>Cliente</th>
								<th>Alta</th>
								
								<th>Estado</th>
								<th>Entrega</th>
								<th>Fecha</th>
								<!--<th>Categoría</th>-->
								<th width="80">Acción</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
						
							$statement = $pdo->prepare("SELECT *, p.estado as estadoPedido FROM carpinteria as p INNER JOIN factura as f ON f.num_factura = p.idFactura INNER JOIN tbl_cliente as c ON c.c_id = f.id_cliente ORDER BY p.fecha DESC;"); //WHERE cc.tipo = 0
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							foreach ($result as $row) {
								$i++;
								?>
								<tr >
									<td><?php echo $i; ?></td>
									<td><?php echo $row['id']; ?></td>
									<td><?php echo $row['idFactura']; ?></td>
									<td><?php echo $row['c_apellido'].' '.$row['c_nombre']; ?></td>
									<td><?php echo $row['usuarioSucursal']; ?></td>
									
									
									<?php 
									$est = $row['estadoPedido'];
										switch ($est) {
											case '0':
												$estado = "Encargado";
												break;
											case '1':
												$estado = "Pedido Recibido";
												break;
											case '2':
												$estado = "En producción";
												break;
											case '3':
												$estado = "Terminado";
												break;
											case '4':
												$estado = "Enviado";
												break;
											default:
												# code...
												break;
										}
									?>
									
									<td>
										<?php echo $estado; ?>
									</td>
									<?php switch ($row['sucursal']) {
										case '1':
											$suc= "Home Design Salta";
											break;
										case '2':
											$suc="Muebles & Deco";
											break;
										case '3':
											$suc = "Home de la Av";
											break;
										case '4':
											$suc = "Infantil Salta";
											break;
										case '5':
											$suc= "Home Online";
											break;
										
										default:
											# code...
											break;
									} ?>
									<td><?php echo $suc; ?></td>
									<td><?php echo date('d-m-Y',$row['fecha']); ?></td>
									<td>										
										<a href="imprimirOrdenCarpinteria.php?id=<?php echo $row['id']; ?>" target="_blank"class="btn btn-primary btn-xs">Ver</a>
										
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