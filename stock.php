<?php require_once('header.php'); ?>

<section class="content-header">
	<h1>Stock</h1>
</section>

<?php
	$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_qty = 0;");
	$statement->execute();
	$total_Sin_Stock = $statement->rowCount();
?>

<section class="content">
	<div class="row">
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-red"><i class="fa fa-exclamation-circle"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Productos sin stock</span>
					<span class="info-box-number"><?php echo $total_Sin_Stock; ?></span>
				</div>
			</div>
		</div>
		
		<hr class="hr-dark col-lg-12">

		<!--tabla-->
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<table id="tablaSinStock" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th width="30">N°</th>
								<th>Foto</th>
								<th width="200">Nombre del Producto</th>
								<!--<th width="60">Precio Anterior</th>-->
								<!--<th width="60">Precio Actual</th>-->
								<th width="60">Cantidad Total</th>
								<th>Sucursal</th>
								<!--<th>¿Destacado?</th>-->
								<!--<th>¿Activo?</th>-->
								<!--<th>Categoría</th>-->
								<th width="80">Acción</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$statement = $pdo->prepare("SELECT
														
														t1.p_id,
														t1.p_name,
														t1.p_old_price,
														/*t1.p_current_price,*/
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

														t5.s_address,
														t5.s_name

							                           	FROM tbl_product t1
							                           	JOIN tbl_end_category t2
							                           	ON t1.ecat_id = t2.ecat_id
							                           	JOIN tbl_mid_category t3
							                           	ON t2.mcat_id = t3.mcat_id
							                           	JOIN tbl_top_category t4
							                           	ON t3.tcat_id = t4.tcat_id
							                           	JOIN tbl_sucursales t5
							                           	ON t5.s_id = t1.p_sucursal_id WHERE t1.p_qty = ?
							                           	ORDER BY t1.p_id DESC
							                           	");
							$statement->execute(array(0));
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							foreach ($result as $row) {
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td style="width:130px;"><img src="../assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="<?php echo $row['p_name']; ?>" style="width:100px;"></td>
									<td><?php echo $row['p_name']; ?></td>
									<!--<td><?php #echo $row['p_old_price']; ?></td>-->
									<!--<td>$<?php echo $row['p_current_price']; ?></td>-->
									<td><?php echo $row['p_qty']; ?></td>
									<td><?php 
										#echo $row['s_name'];
										$product_id = $row['p_id'];
										$consulta = $pdo->prepare("SELECT t1.sk_id, t1.sk_stock, t2.s_name FROM `tbl_stock` as t1 JOIN tbl_sucursales as t2 ON t1.sk_id_sucursal = t2.s_id WHERE t2.s_online=? AND t1.sk_id_producto=? AND t2.s_active = ?");
										$consulta->execute(array(0, $product_id, 1));
										$res = $consulta->fetchAll(PDO::FETCH_ASSOC);
										$text = "";
										foreach ($res as $sucursal) {
											$text .= "- ".$sucursal['s_name'].": ".$sucursal['sk_stock']."<br>";
										}
										echo ($text=="")? "- Sin Sucursal" : $text;
									 ?></td>
									
									<td>
										<!--<a href="#" class="btn btn-danger btn-xs col-lg-12" data-toggle="modal" data-target="#addStock" data-product-id="<?php echo $sucursal['sk_id_producto']; ?>"><i class="fa fa-cart-plus"></i> Añadir Stock </a>-->				
										<a href="<?php echo 'stock-add.php?id='.$row['p_id']; ?>" class="btn btn-danger btn-xs col-lg-12"><i class="fa fa-cart-plus"></i> Añadir Stock</a>
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

<!--MODALES-->
<div class="modal fade" id="addStock" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" style="color: #000;"><i class="fa fa-cart-plus"></i> Producto: <span><!--<i id="loaderModalStock" class="fa fa-spinner fa-spin"></i>--> </span></h4>
      </div>
      <div class="modal-body">

        <div class="row">
        	<div class="col-lg-12">
        		<input type="hidden" name="sk_producto_id">
        		<div class="form-group">
					<label for="" class="col-sm-3 control-label"><i class="fa fa-building"></i> Sucursal : </label>
					<div class="col-sm-4">
						<select name="sk_sucursal_id" class="form-control" required>
							<option value="">Seleccione Sucursal</option>
							<?php 
								$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_online=?");
								$statement->execute(array(0));
								$result = $statement->fetchAll(PDO::FETCH_ASSOC);
								foreach ($result as $row) {
									?>
										<option value="<?php echo $row['s_id'] ?>"><?php echo $row['s_name']; ?></option>
									<?php
								}
							 ?>
						</select>
					</div>
				</div>	
        	</div><br>

        	<div class="col-lg-12">
        		<div class="form-group">
					<label for="" class="col-sm-3 control-label"><i class="fa fa-list-ol"></i> Cantidad : </label>
					<div class="col-sm-4">
						<input type="number" name="sk_stock" class="form-control" min="1">
					</div>
				</div>
        	</div>
        	
        	<!--<div class="col-lg-12">
        		<div class="box-body table-responsive">
					<table id="tablaStockModal" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th width="30">N°</th>
								<th>Sucursal</th>
								<th width="200">Cantidad</th>
								<th width="80">Acción</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Test</td>
								<td>Test</td>
								<td>Test</td>
							</tr>
						</tbody>
					</table>
				</div>
        	</div>-->
        </div>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-primary">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php require_once('footer.php'); ?>