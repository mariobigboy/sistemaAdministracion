<?php require_once('header.php'); ?>


<section class="content-header">
	<div class="content-header-left">
		<h1>Ver Productos</h1>
	</div>
	<div class="content-header-right">
		<a href="product-add.php" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Nuevo Producto</a>
		<a href="libs/codebar/index.php" class="btn btn-warning btn-sm" target="_blank"> <i class="fa fa-barcode"></i> Imprimir codebars</a>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<table id="tablaProductos" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th width="30">N°</th>
								<th>Foto</th>
								<th width="200">Nombre del Producto</th>
								<!--<th width="60">Precio Anterior</th>-->
								<th width="60">Precio Actual</th>
								<th width="60">Cantidad Total</th>
								<th>Sucursal</th>
								<!--<th>¿Destacado?</th>-->
								<th>¿Activo?</th>
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
							                           	ON t5.s_id = t1.p_sucursal_id
							                           	WHERE t1.p_id > -1 AND t1.p_is_deleted = 0
							                           	ORDER BY t1.p_id DESC
							                           	");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							foreach ($result as $row) {
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td style="width:130px;"><img src="../assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="<?php echo $row['p_name']; ?>" style="width:100px;"></td>
									<td><?php echo $row['p_name']; ?></td>
									<!--<td><?php #echo $row['p_old_price']; ?></td>-->
									<td>$<?php echo $row['p_current_price']; ?></td>
									<td><?php echo $row['p_qty']; ?></td>
									<td><?php 
										#echo $row['s_name'];
										$product_id = $row['p_id'];
										$consulta = $pdo->prepare("SELECT t1.sk_stock, t2.s_name FROM `tbl_stock` as t1 JOIN tbl_sucursales as t2 ON t1.sk_id_sucursal = t2.s_id WHERE t2.s_address<>? AND t1.sk_id_producto=? AND t2.s_active = ?");
										$consulta->execute(array('Internet', $product_id, 1));
										$res = $consulta->fetchAll(PDO::FETCH_ASSOC);
										$text = "";
										foreach ($res as $sucursal) {
											$text .= "- ".$sucursal['s_name'].": ".$sucursal['sk_stock']."<br>";
										}
										echo ($text=="")? "- Sin Sucursal" : $text;
									 ?></td>
									<!--<td>
										<?php #if($row['p_is_featured'] == 1) {echo 'Sí';} else {echo 'No';} ?>
									</td>-->
									<td>
										<?php if($row['p_is_active'] == 1) {echo 'Sí';} else {echo 'No';} ?>
									</td>
									<!--<td><?php #echo $row['tcat_name']; ?><br><?php #echo $row['mcat_name']; ?><br><?php #echo $row['ecat_name']; ?></td>-->
									<td>
										<a href="#" class="btn btn-success btn-xs col-lg-12" data-toggle="modal" data-target="#verProductoModal" data-id="<?php echo $row['p_id']; ?>"><i class="fa fa-eye"></i> Ver </a>
										<a href="product-history.php?id=<?php echo $row['p_id']; ?>" class="btn btn-warning btn-xs col-lg-12" data-id="<?php echo $row['p_id']; ?>"><i class="fa fa-history"></i> Historial </a>							
										<?php 
											if(!($_SESSION['user']['role']=='Empleado' || $_SESSION['user']['role']=='Publisher')){

												?>
												<a href="stock-add.php?id=<?php echo $row['p_id']; ?>" class="btn btn-danger btn-xs col-lg-12" ><i class="fa fa-cart-plus"></i> Stock </a>
												<?php
											}

										 ?>

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


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirmación de Eliminación</h4>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de eliminar este item?</p>
                <p style="color:red;">¡Ten cuidado! Este producto se eliminará de la tabla de pedidos, tabla de pagos, tabla de tamaños, tabla de colores y tabla de clasificación también.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-danger btn-ok">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="printCodeModal" tabindex="-1" role="dialog" aria-labelledby="myLabelModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myLabelModal" style="color: #000;"><i class="fa fa-print"></i> Imprimir </h4>
            </div>
            <div class="modal-body">
                <p>¿Cuántas copias del mismo código desea imprimir?</p>
       			<div class="col-12 text-center">
       				<input type="hidden" class="url" value="0">
       				<input type="hidden" id="prodId" value="0">
       				<button class="btn btn-default btnLess">-</button><input type="number" class="inpTotal" style="width:2.5em; margin-right: 1em; margin-left: 1em;" value="1" min="1" ><!--<label for="" class="lblTotal" style="margin-right: 1em; margin-left: 1em;">1</label>--><button class="btn btn-default btnAdd">+</button>
       			</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"> Cancelar</button>
                <a id="btnPrintCodeBar" class="btn btn-warning btn-print" target="_blank"><i class="fa fa-print"></i> Imprimir</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="verProductoModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" style="color: #000;"><i class="fa fa-bookmark"></i> Producto: <span><i id="loaderModalProduct" class="fa fa-spinner fa-spin"></i> </span></h4>
      </div>
      <div class="modal-body">

        <div class="row">
        	<div class="col-lg-6">
        		<label for=""><i class="fa fa-hashtag"></i> ID: </label><span id="spanID"> </span> <br>
        		<label for=""><i class="fa fa-tag"></i> Nombre: </label><span id="spanNombre"> </span> <br>
        		<label for=""><i class="fa fa-tags"></i> Marca: </label><span id="spanMarca"> </span> <br>
        		<label for=""><i class="fa fa-barcode"></i> Código: </label><span id="spanCode"> </span> <br>
        		
        		<!--<label for=""><i class="fa fa-building-o"></i> Sucursal: </label><span id="spanSucursalName"> </span> <br>-->
        		<label for=""><i class="fa fa-list-ol"></i> Cantidad Total: </label><span id="spanCantidad"> </span> <br>
        		<label for=""><i class="fa fa-eye"></i> Total Visitas: </label><span id="spanTotalVisitas"> </span> <br>
				
				<label for=""><i class="fa fa-toggle-on"></i> Destacado: </label><span id="spanPublicado"> </span> <br>
        		<label for=""><i class="fa fa-toggle-on"></i> Publicado en Web: </label><span id="spanActivo"> </span> <br>
        	</div>
        	<div class="col-lg-6">
        		<label for=""><i class="fa fa-usd"></i> Precio: </label><span id="spanPrecio"> </span> <br>
        		<label for=""><i class="fa fa-usd"></i> Precio de Lista: </label><span id="spanPrecioLista"> </span> <br>
        		<label for=""><i class="fa fa-usd"></i> Precio de Costo: </label><span id="spanPrecioCosto"> </span> <br>
        		<label for=""><i class="fa fa-usd"></i> Utilidad: </label><span id="spanUtilidad"> </span>% <br>
        		<label for=""><i class="fa fa-usd"></i> Gastos Extras: </label><span id="spanGastosExtras"> </span> <br>
        	</div>
        	<div class="col-lg-12">
        		<hr>
        		<h4><i class="fa fa-building-o"></i> Stock:</h4>
				<ul id="listSucursal" style="list-style: none;">
					<li >
						<i class="fa fa-building-o"></i>
						<span id="spanSucursalName"></span>: 

					</li>
				</ul>
        	</div>
        	<!--<div class="col-lg-12">
        		<h4><i class="fa fa-tags"></i> Descripción: </h4>
        		<p id="p_descrip"></p>
        	</div>
        	<div class="col-lg-12">
        		<h4><i class="fa fa-tags"></i> Descripción corta: </h4>
        		<p id="p_descrip_short"></p>
        	</div>-->
        </div>
      </div>
      <div class="modal-footer">
        <a id="btnEditar" href="product-edit.php?id=" class="btn btn-primary "><i class="fa fa-pencil-square-o"></i> Editar </a>
        <?php 
        	if($_SESSION['user']['role']=='Super Admin' || $_SESSION['user']['role']=='Admin'){
        		?>
					<a id="btnEliminar" href="#" class="btn btn-danger " data-href="product-delete.php?id=" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i> Eliminar </a>  
					<a id="btnTraspaso" href="traspaso.php" class="btn btn-default "><i class="fa fa-building-o"></i><i class="fa fa-arrow-right"></i><i class="fa fa-building-o"></i> Traspasar </a>
					<a id="btnCodeBar" href="#" class="btn btn-warning " data-href="libs/codebar/index.php?id=" data-toggle="modal" data-target="#printCodeModal"><i class="fa fa-barcode"></i> Código de barra </a>	
        		<?php
        	}
        	if($_SESSION['user']['role']== 'Empleado'){
        		?>
					<a id="btnCodeBar" href="#" class="btn btn-warning " data-href="libs/codebar/index.php?id=" data-toggle="modal" data-target="#printCodeModal"><i class="fa fa-barcode"></i> Código de barra </a>
					<a id="btnTraspaso" href="traspaso.php" class="btn btn-default "><i class="fa fa-building-o"></i><i class="fa fa-arrow-right"></i><i class="fa fa-building-o"></i> Traspasar </a>
        		<?php
        	}
         ?>
		
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php require_once('footer.php'); ?>