<?php 
require_once('header.php'); 
$idProveedor = isset($_GET['id']) ? $_GET['id'] : "";


$statement = $pdo->prepare("SELECT nombre FROM proveedores WHERE id = ?");
$statement->execute(array(
	$idProveedor
));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
if ($result[0]['estado']==0) {
	$statement11 = $pdo->prepare("UPDATE proveedores SET estado = 1 WHERE id = ?");
	$statement11->execute(array(
		$idProveedor
	));
}

$today_unix = time();
$today_format = date('d/m/Y', $today_unix);
?>

<style type="text/css">
.bg-amarillo{
	background-color: #FFFF01 !important;
}
.bg-verde{
	background-color: #dff0d8 !important;
}
</style>

<section class="content-header">
	<div class="content-header-left">
		<h1>Detalle de Cuenta: <?php echo $result[0]['nombre']; ?></h1>
	</div>
	<div class="content-header-right">
		<a href="javascript:history.back();" class="btn btn-primary btn-sm"> Volver Atrás</a>
		<a href="proveedoresCuentas.php" class="btn btn-primary btn-sm">Ver Todas Ctas</a>
		<!-- <a href="cuentaProveedor-add.php?id=<?php //echo $idProveedor; ?>" class="btn btn-primary btn-sm"> Nueva Cuenta</a> -->
		
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<!-- code -->
					<table id="tablaClientesCuentas" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Fecha</th>
								<th>Descripción</th>
								<th>Usuario</th>
								<th>Cantidad</th>
								<th>Precio Unitario</th>
								<th>Sub Total</th>
								<th>Saldo</th>
								
								
							</tr>
						</thead>
						<tbody>
							<?php
							$saldo_total = 0;

							/*$statement = $pdo->prepare("SELECT 
								cp.id as idCuenta,
								cp.fecha_factura, 
								cp.factura, 
								cp.monto, 
								
								dp.cant cantidad,
								DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(cp.fecha),'+00:00','-03:00'), '%d/%m/%Y %H:%i') fecha_format,
								SUM(dp.pago) as pagos
								FROM cuentasProveedores as cp 
								INNER JOIN detalleProveedores as dp ON cp.id = dp.idCuenta
								WHERE cp.idProveedor= ? 
								GROUP BY cp.id;");*/
								$statement = $pdo->prepare("SELECT 
									* 
									FROM detalleProveedores 
									WHERE idProveedor = ?;");

							//$statment = $pdo->prepare("SELECT * FROM cuentasProveedores WHERE idProveedor = ?");
								$statement->execute(array($idProveedor));
								$result2 = $statement->fetchAll(PDO::FETCH_ASSOC);
								foreach ($result2 as $row) {
									if($row['pago']==0){
										$saldo_total += ($row['precio'] * $row['cant']);
									}
									if($row['pago']>0){
										$saldo_total -= $row['pago'];
									}
									?>
									<tr data-id="<?php echo $row['idCuenta']; ?>" class="<?php echo ($row['pago']>0)? 'bg-verde': ''; ?>">
										<td><?php echo $row['fechaPicker']; ?></td>
										<td><?php echo ($row['pago']>0)? 'PAGO' : $row['descripcion']; ?></td>
										<td><?php echo $row['usuario']; ?></td>
										<td><?php echo ($row['cant']==0)? '-': $row['cant']; ?></td>
										<td><?php echo ($row['pago']>0)? '-' : $row['precio']; ?></td>
										<td><?php echo ($row['pago']>0)? $row['pago'] : $row['precio'] * $row['cant']; ?></td>
										<td><?php echo $saldo_total; ?></td>



									</tr>
									<?php
								}
								?>			
							<!-- <div>
								<?php print_r($result2); ?>
							</div> -->

						</tbody>

						<tfoot>
							<tr>
								<td colspan="3"></td>
								<td colspan="2" class="bg-amarillo"><strong>SALDO TOTAL</strong></td>
								<td colspan="2" class="bg-amarillo"><strong><?php echo "$".$saldo_total; ?></strong></td>
							</tr>
						</tfoot>

					</table>

					<!-- botones de abajo -->
					<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalDescripcion" style="background-color: #333333;border-color: #333333;">
						<i class="fa fa-plus"></i> Agregar Descripción
					</button>
				</div>
			</div>
		</div>
	</div>
</section>



<div class="modal fade" id="modalDescripcion" tabindex="-1" role="dialog" aria-labelledby="myLabelModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myLabelModal" style="color: #000;"><i class="fa fa-number"></i> Nueva Descripción</h4>
				
			</div>
			<div class="modal-body">
				
				<h3>Descripción</h3>
				
				<div class="col-12">
					<form id="formDetalles" action="#" class="form-horizontal">
						<!-- hidden values -->
						<input type="hidden" name="acc" value="set">
						<input type="hidden" name="idProveedor" id="idProveedor" value="<?php echo $idProveedor; ?>">

						<div class="form-group">
							<label for="fecha" class="label-control col-md-4">Fecha</label>
							<div class="col-md-4">
								<input type="text" class="form-control" value="<?php echo $today_format; ?>" placeholder="01/12/2020" name="fecha" autocomplete="off">
								<!--<input type="hidden" name="usuario" id="idUsuarioCobrador" value="<?php echo $_SESSION['user']['full_name']; ?>">
									<input type="hidden" name="idCuenta" id="idCuenta" value="">-->
									<!--<input class="form-control" type="number" name="pago" id="pago" min="0" placeholder="0">-->
								</div>

							</div>
<!-- 
						<div class="form-group">
							<label for="cuenta" class="label-control col-md-4">Cuenta</label>
							<div class="col-md-4">
								<select name="idCuenta" id="selCuenta" class="form-control">
									<option value="-1" selected>Seleccione</option>
									<?php 
										// $stat = $pdo->prepare("SELECT * FROM cuentasProveedores WHERE idProveedor = ?;");
										// $stat->execute(array($idProveedor));
										// $result3 = $stat->fetchAll(PDO::FETCH_ASSOC);
										// foreach ($result3 as $row) {
										// 	?>
										// 		<option value="<?php echo $row['id']; ?>"><?php echo $row['factura']; ?></option>
										// 	<?php
										// }
									 ?>
								</select>
							</div>
						</div> -->

						<!--<div class="form-group">
							<label for="selMetodoPago" class="label-control col-md-4">Método de Pago:</label>
							<div class="col-md-4">
								<select class="form-control" name="" id="selMetodoPago">
									<option value="-1">Seleccione Método de Pago</option>
									<option value="Efectivo">Efectivo</option>
									<option value="Tarjeta">Tarjeta</option>
									<option value="Cheque">Cheque</option>
								</select>
							</div>
						</div>-->

						<div class="form-group">
							<label for="" class="label-control col-md-4">Tipo de Descripción</label>
							<div class="col-md-4">
								<div class="radio">
									<label>
										<input type="radio" name="opcion" id="optionPago" value="pago">
										Pago
									</label>
								</div>

								<div class="radio">
									<label>
										<input type="radio" name="opcion" id="optionDescrip" value="descripcion">
										Descripción
									</label>
								</div>
							</div>
						</div>
						
						<div id="inputsDescripcion" style="display:none;">
							<div class="form-group">
								<label for="" class="label-control col-md-4">Descripción</label>
								<div class="col-md-12">
									<textarea name="descripcion" id="inputDesc" class="form-control" cols="30" rows="5"></textarea>
								</div>
							</div>

							<div class="form-group">
								<label for="" class="label-control col-md-4">Cantidad</label>
								<div class="col-md-4">
									<input type="number" name="cant" id="inputCant" class="form-control" value="0">
								</div>
							</div>

							<div class="form-group">
								<label for="" class="label-control col-md-4">Precio Unitario</label>
								<div class="col-md-4">
									<input type="text" name="precioUnitario" id="inputPrecio" class="form-control" value="0.00">
								</div>
							</div>
						</div>

						<div id="inputsPagos" style="display:none;">
							<div class="form-group">
								<label for="" class="label-control col-md-4">Pago</label>
								<div class="col-md-4">
									<input type="text" name="pago" id="inputPago" class="form-control" value="0.00">
								</div>
							</div>
						</div>


					</form>
					
					<!--<strong>Ingresar un Pago:    $</strong>--> <br><br>
					<!--<p style="color: red;">Se utilizará el usuario <?php echo $_SESSION['user']['full_name']; ?> para registrar el Detalle!</p>-->
				</div>
			</div>
			<div class="modal-footer">
				<button id="btnCerrar" type="button" class="btn btn-default" data-dismiss="modal" style="display:none;">Cerrar</button>
				<button id="btnCancelar" type="button" class="btn btn-default" data-dismiss="modal"> Cancelar</button>
				<a id="btnAgregarDetalle" class="btn btn-warning"> Agregar</a>
			</div>
		</div>
	</div>
</div>




<?php require_once('footer.php'); ?>

<script type="text/javascript">
	$(document).ready(function(){
		$('input[name=fecha]').datepicker({format: 'dd/mm/yyyy'});

		$('input[type=radio]').click(function(){
			//console.log($(this).val());

			if($(this).val()=="pago"){
				$('#inputsPagos').show();
				$('#inputsDescripcion').hide();
			}

			if($(this).val()=="descripcion"){
				$('#inputsDescripcion').show();	
				$('#inputsPagos').hide();
			}
		});

		$('#btnAgregarDetalle').click(function(){
			//(!($("#optionPago").is(':checked')  ||  $("#optionDescrip").is(':checked')) )
			//if ((! $("#optionPago").is(':checked') ) ||  (! $("#optionDescrip").is(':checked') )) {

			if (!($("#optionPago").is(':checked')  ||  $("#optionDescrip").is(':checked')) ) {
				alertify.warning('Seleccione una opción');
			}else{
				var b = false;
				//valido
				if ($("#optionDescrip").is(':checked')) {
					if ($('#inputDesc').val() != "" && $('#inputCant').val() != "0" && $('#inputPrecio').val() != "0.00") {
						b = true;
					}
				}

				if ($("#optionPago").is(':checked')) {
					if ($('#inputPago').val() != "0.00") {
						b = true;
					}
				}

				if (b) {
					$form = $('#formDetalles');
					datos = $form.serializeArray();
					$.ajax({
						url: 'apiDetalleProveedor.php',
						method: 'POST',
						data: datos,
						success: function(data){
							if(data.success){
								alertify.success("Guardado exitoso!");
								//refrescamos la página:
								document.location.href = '';
							}else{
								alertify.error("Error: " + data.error);
								console.error("Error: " + data.error);
							}
						},
						error: function(err){
							console.log(err);
							alertify.error("Error al guardar");
						}
					});
				}else{
					alertify.warning("Complete todos los campos para continuar!");
				}

				
			}
			
		});
	});

	
</script>