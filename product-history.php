<?php require_once('header.php'); ?>
<?php
	
?>


<section class="content-header">
	<h1>Últimos Movimientos</h1>
</section>

<section class="content">
	<div class="row">
			
		<div class="col-md-12">
			
			<?php if($error_message): ?>
			<div class="callout callout-danger">
			
			<p>
			<?php echo $error_message; ?>
			</p>
			</div>
			<?php endif; ?>

			<?php if($success_message): ?>
			<div class="callout callout-success">
			
			<p><?php echo $success_message; ?></p>
			</div>
			<?php endif; ?>

			<form class="form-horizontal" action="" method="post" >
				<!--<input type="hidden" name="prodId" value="<?php echo $idProducto; ?>">-->
				<div class="box box-info">
					<div class="box-body" >
						<div class="table-responsive">
							<table id="tablaHistoria" class="table table-condensed table-striped">
								<thead>
									<tr>
										<th>N°</th>
										<th>Producto</th>
										<th>Detalle</th>
										<th>Sucursal</th>
										<th>Cantidad</th>
										<th>Usuario</th>
										<th>Motivo</th>
										<th>Fecha</th>
									</tr>
								</thead>
								<tbody>
									<!-- 
										success (alta)
										danger (baja)
										warning (traspaso)
									-->
									
									<?php 
										if(isset($_GET['id'])){
											$id = $_GET['id'];
											//$statement = $pdo->prepare("SELECT t1.*,  t2.p_name  FROM tbl_historia as t1 INNER JOIN tbl_product AS t2 ON t1.h_id_producto = t2.p_id WHERE t2.p_id = ? ORDER BY t1.h_fecha DESC;");
											$statement = $pdo->prepare("SELECT t1.*, t2.p_name, t3.s_name, t4.full_name FROM tbl_historia as t1 INNER JOIN tbl_product AS t2 ON t1.h_id_producto = t2.p_id INNER JOIN tbl_sucursales AS t3 ON t1.h_id_sucursal = t3.s_id INNER JOIN tbl_user as t4 ON t1.h_id_user = t4.id WHERE t2.p_id = ? ORDER BY t1.h_fecha DESC;");
											$statement->execute(array($id));
											$results = $statement->fetchAll(PDO::FETCH_ASSOC);
											$totalRegs = $statement->rowCount();

										}else{
											//$statement = $pdo->prepare("SELECT t1.*, t2.p_name, t3.s_name FROM tbl_historia as t1 INNER JOIN tbl_product AS t2 ON t1.h_id_producto = t2.p_id INNER JOIN tbl_sucursales AS t3 ON t1.h_id_sucursal = t3.s_id ORDER BY t1.h_fecha DESC;");
											$statement = $pdo->prepare("SELECT t1.*, t2.p_name, t3.s_name, t4.full_name FROM tbl_historia as t1 INNER JOIN tbl_product AS t2 ON t1.h_id_producto = t2.p_id INNER JOIN tbl_sucursales AS t3 ON t1.h_id_sucursal = t3.s_id INNER JOIN tbl_user as t4 ON t1.h_id_user = t4.id ORDER BY t1.h_fecha DESC;");
											$statement->execute();
											$results = $statement->fetchAll(PDO::FETCH_ASSOC);
											$totalRegs = $statement->rowCount();											
										}
										$ind = 1;
										foreach($results as $row){
											$clase = '';
											switch ($row['h_detalle']) {
												case 'ALTA':
													$clase = 'success';
													break;
												case 'BAJA':
													$clase = 'danger';
													break;
												case 'TRASPASO':
													$clase = 'warning';
													break;
												default:
													# code...
													$clase = '';
													break;
											}
											$fecha = date('d/m/Y H:i:s', $row['h_fecha']);

											?>
											<tr class="<?php echo $clase; ?>">
												<td><?php echo $ind; ?></td>
												<td><?php echo $row['p_name']; ?></td>
												<th><?php echo $row['h_detalle']; ?></th>
												<th><?php echo $row['s_name']; ?></th>
												<th><?php echo abs($row['h_stock_actual'] - $row['h_stock_anterior']); ?></th>
												<th><?php echo $row['full_name']; ?></th>
												<th><?php echo $row['h_obs']; ?></th>
												<th><?php echo $fecha; ?></th>
											</tr>
											<?php
											$ind++;
										}
									 ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</form>
	</div>
</section>

<?php require_once('footer.php'); ?>