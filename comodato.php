<?php 
	require_once('header.php'); 
	//$usuario = $_SESSION['user']['full_name'];
	//$sucursal = $_SESSION['user']['sucursal'];
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Préstamos</h1>
	</div>
	<div class="content-header-right">
		<a  class="btn btn-primary btn-sm" href="addComodato.php"> <i class="fa fa-truck"></i> Nuevo Préstamo </a>
		<!-- <a href="clientes-add.php" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Nuevo Cliente</a> -->
		<!--<a href="libs/codebar/index.php" class="btn btn-warning btn-sm" target="_blank"> <i class="fa fa-barcode"></i> Imprimir codebars</a>-->

	</div>
</section>

<section class="content">
	<div class="row">
		<?php //echo $usuario; ?>
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<!-- code -->
					<table id="tablaComodatos" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>N° Orden</th>
								<th>Cliente</th>
								<th>Sucursal</th>
								<!--<th>Producto</th>
								<th>Cantidad</th>
								-->
								<th>Usuario</th>
								<th>Fecha de Emisión</th>
								<th>Fecha Límite</th>
								<th>Fecha de Devolución</th>
								<th>Estado</th>
								<th>Acción</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;

							$statement = $pdo->prepare("SELECT 
															tcli.c_nombre nombre, 
															tcli.c_apellido apellido, 
															
															tprod.p_name nombre_producto,
															
															tcom.id id,
															tcom.id_cliente id_cliente,
															tcom.cantidad cantidad,
															tcom.fecha_emision fecha_desde_unix,
															tcom.fecha_devolucion fecha_hasta_unix,
															tcom.fecha_limite fecha_limite_unix,
															DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(tcom.fecha_emision),'+00:00','-03:00'), '%d/%m/%Y') fecha_desde_format,
															DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(tcom.fecha_devolucion),'+00:00','-03:00'), '%d/%m/%Y') fecha_hasta_format,
															DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(tcom.fecha_limite),'+00:00','-03:00'), '%d/%m/%Y') fecha_limite_format,
															tcom.estado estado,
															tcom.orden orden,

															tusr.full_name user_fullname,

															tsuc.s_name s_name

															FROM tbl_comodato tcom 
															INNER JOIN tbl_cliente tcli ON tcli.c_id = tcom.id_cliente
															INNER JOIN tbl_product tprod ON tprod.p_id = tcom.id_producto
															INNER JOIN tbl_user tusr ON tusr.id = tcom.id_user 
															INNER JOIN tbl_sucursales tsuc ON tsuc.s_id = tcom.id_sucursal GROUP BY tcom.orden
															ORDER BY tcom.id DESC;");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				
							foreach ($result as $row) {
								$i++;
								//id	id_cliente	id_sucursal	id_producto	cantidad	id_user	observaciones	fecha_emision	fecha_devolucion	estado

								$estadoImg = 'img/200/';
								switch ($row['estado']) {
									case 'Prestado':
										$estadoImg .= 'orange.png';
										break;
									case 'Demorado':
										$estadoImg .= 'red.png';
										break;
									case 'Devuelto':
										$estadoImg .= 'green.png';
										break;
									default:
										# code...
										$estadoImg .= 'green.png';
										break;
								}
								?>
								<tr >

									<td><?php echo $i; ?></td>
									<td><?php echo $row['orden']; ?></td>
									<td><?php echo $row['nombre'].' '.$row['apellido']; ?></td>
									<td><?php echo $row['s_name']; ?></td>
									<!--<td><?php echo $row['nombre_producto']; ?></td>
									<td><?php echo $row['cantidad']; ?></td>
									-->
									<td><?php echo $row['user_fullname']; ?></td>
									<td><?php echo (is_null($row['fecha_desde_unix']))? '-' : $row['fecha_desde_format']; ?></td>
									<td><?php echo (is_null($row['fecha_limite_unix']))? '-' : $row['fecha_limite_format'];  ?></td>
									<td><?php echo (is_null($row['fecha_hasta_unix']))? '-' : $row['fecha_hasta_format']; ?></td>
									<td><img src="<?php echo $estadoImg; ?>"></td>
									<td>
										<!--<a class="btn btn-info" target="_blank" href="#?id=<?php echo $row['id']; ?>"><i class="fa fa-refresh"></i> Devolver</a>--> 
										<!--<a class="btn btn-danger" target="_blank" href="#?id=<?php echo $row['id']; ?>"> <i class="fa fa-trash"></i> Eliminar</a>-->
										<a href="verComodato.php?id=<?php echo $row['orden']; ?>" class="btn btn-primary btn-xs">Ver</a>
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

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nuevo Recibo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formRecibo">
        	<input type="hidden" name="acc" value="guardarRecibo">
        	<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario; ?>">
        	<input type="hidden" name="sucursal" id="sucursal" value="<?php echo $sucursal; ?>">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Recibí de:</label>
            <input type="text" class="form-control" name="cliente" id="cliente" placeholder="Nombre y Apellido" required>
          </div>
          <div class="form-group">
            <label for="concepto" class="col-form-label">En concepto de:</label>
            <textarea class="form-control" name="concepto" id="concepto" placeholder="Descripción" required></textarea>
          </div>
          <div class="form-group">
            <label for="monto" class="col-form-label">La cantidad de:</label>
            <input type="number" min="0" class="form-control" name="monto" id="monto" placeholder="$" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarRecibo">Guardar</button>
      </div>
    </div>
   </div>
</div>

<?php require_once('footer.php'); ?>

<script type="text/javascript">
	
	$(document).ready(function(){

	});
	
</script>