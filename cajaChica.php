<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Caja Chica</h1>
	</div>
	<div class="content-header-right">
		<a  class="btn btn-primary btn-sm" id="btnRegitrarModal"> <i class="fa fa-plus"></i> Registrar Movimiento</a>
	</div>
</section>
<section class="content">
	<div class="row">
		<?php 
		if($_SESSION['user']['role']=='Super Admin' || $_SESSION['user']['role']=='Admin'){
			$statement = $pdo->prepare("SELECT * FROM tbl_sucursales;");
			$statement->execute();
			$result1 = $statement->fetchAll(PDO::FETCH_ASSOC);

			for ($i=0; $i < sizeof($result1); $i++) { 
				$total=0;
				$suc= $result1[$i]['s_id'];
				if ($suc!= 5) { 
					$statement = $pdo->prepare("SELECT SUM(monto) as suma_total FROM cajaChica WHERE idSucursal=$suc AND (movimiento = 1 OR movimiento=2);");
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					$total_sumar = ($result[0]['suma_total']=='')? 0 : $result[0]['suma_total'];

					$statement = $pdo->prepare("SELECT SUM(monto) as suma_total FROM cajaChica WHERE idSucursal=$suc AND (movimiento = 3 OR movimiento=4);");
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					$total_restar = ($result[0]['suma_total']=='')? 0 : $result[0]['suma_total'];
					$total = $total_sumar - $total_restar;
					?>

					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="info-box">
							<span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>
							<div class="info-box-content">
								<span class="info-box-text"><?php echo $result1[$i]['s_name'];; ?> </span>
								<span class="info-box-number"><?php echo "$".number_format(floatval($total), 2); ?></span>
							</div>
						</div>
					</div>
				<?php } 
			}
			
		}else{

			$suc= $_SESSION['user']['sucursal'];
			$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_id='$suc';");
			$statement->execute();
			$result1 = $statement->fetchAll(PDO::FETCH_ASSOC);
			$statement = $pdo->prepare("SELECT SUM(monto) as suma_total FROM cajaChica WHERE idSucursal=$suc AND (movimiento = 1 OR movimiento=2);");
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			$total_sumar = ($result[0]['suma_total']=='')? 0 : $result[0]['suma_total'];

			$statement = $pdo->prepare("SELECT SUM(monto) as suma_total FROM cajaChica WHERE idSucursal=$suc AND (movimiento = 3 OR movimiento=4);");
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			$total_restar = ($result[0]['suma_total']=='')? 0 : $result[0]['suma_total'];
			$total = $total_sumar - $total_restar; ?>

			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>
					<div class="info-box-content">
						<span class="info-box-text"><?php echo $result1[0]['s_name'];; ?> </span>
						<span class="info-box-number"><?php echo "$".number_format(floatval($total), 2); ?></span>
					</div>
				</div>
			</div>
		<?php 	}
		?>

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
								<th width="30">#</th>
								<th>Monto</th>
								<th width="200">Movimiento</th>
								<!--<th width="60">Precio Anterior</th>-->
								<th width="60">Usuario</th>
								<th>Sucursal</th>
								<!--<th>¿Destacado?</th>-->
								<th>Fecha</th>
								<!--<th>Categoría</th>-->
								<th>Observ</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							//print_r($_SESSION['user']);
							if ($_SESSION['user']['role'] == 'Super Admin' || $_SESSION['user']['role']== 'Admin') {
								$statement = $pdo->prepare("SELECT *, s.s_name FROM cajaChica as c INNER JOIN tbl_sucursales as s ON s.s_id= c.idSucursal ORDER BY id DESC;");
								$statement->execute();
								$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
								for ($i=0; $i < sizeof($result); $i++) { 
									switch ($result[$i]['movimiento']) {
										case '1':
										$movimiento="APERTURA";
										break;
										case '2':
										$movimiento="INGRESO";
										break;
										case '3':
										$movimiento="EGRESO";
										break;
										case '4':
										$movimiento="CIERRE";
										break;
										
										default:
											# code...
										break;
									}
									echo "<tr><td>".$i."</td><td>".$result[$i]['monto']."</td><td>".$movimiento."</td><td>".$result[$i]['idUsuario']."</td><td>".$result[$i]['s_name']."</td><td>".date('d/m/Y H:i:s',$result[$i]['fecha'])."</td><td>".$result[$i]['obs']."</td></tr>";
								}

							}else{
								
								$sucursal= $_SESSION['user']['sucursal'];
								$statement = $pdo->prepare("SELECT *, s.s_name FROM cajaChica as c INNER JOIN tbl_sucursales as s ON s.s_id= c.idSucursal WHERE s.s_id='$sucursal';");
								$statement->execute();
								$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
								for ($i=0; $i < sizeof($result); $i++) { 
									switch ($result[$i]['movimiento']) {
										case '1':
										$movimiento="APERTURA";
										break;
										case '2':
										$movimiento="INGRESO";
										break;
										case '3':
										$movimiento="EGRESO";
										break;
										case '4':
										$movimiento="CIERRE";
										break;

										default:
												# code...
										break;
									}
									echo "<tr><td>".$i."</td><td>".$result[$i]['monto']."</td><td>".$movimiento."</td><td>".$result[$i]['idUsuario']."</td><td>".$result[$i]['s_name']."</td><td>".date('d/m/Y H:i:s',$result[$i]['fecha'])."</td><td>".$result[$i]['obs']."</td></tr>";
								}
							}			


							?>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>


<div class="modal fade" id="agregarMoviminetoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Agregar Movimientos</h4>
			</div>
			<div class="modal-body">
				<?php  
				if ($_SESSION['user']['role'] == 'Super Admin' || $_SESSION['user']['role']== 'Admin') {


					?>
					<form>
						<label for="idSucursal">Sucursal</label>
						<select name="idSucursal" id="idSucursal" required class="form-control">
							<option value="">-Selecciones una sucursal-</option>
							<?php 

							$statement = $pdo->prepare("SELECT * FROM tbl_sucursales;");
							$statement->execute();
							$result2 = $statement->fetchAll(PDO::FETCH_ASSOC);

							for ($i=0; $i < sizeof($result2); $i++) { 
								if ($result2[$i]['s_id'] != 5) {
									echo "<option value=".$result2[$i]['s_id'].">".$result2[$i]['s_name']."</option>";
								}
								
							}
							?>

						</select>


					<?php }else{?>
						<input type="hidden" name="idSucursal" id="idSucursal" value="<?php echo $_SESSION['user']['sucursal']; ?>">

					<?php } ?>
					<input type="hidden" name="idUsuario" id="idUsuario" value="<?php echo $_SESSION['user']['full_name']; ?>">
					<label for="monto">Monto</label>
					<input type="number" name="monto" id="monto" class="form-control" min="0" placeholder="$" required>
					<label for="movimiento">Tipo de Movimiento</label>
					<select name="movimiento" id="movimiento" class="form-control" required>
						<option value="">-Seleccione Movimiento-</option>
						<option value="1">APERTURA</option>
						<option value="2">INGRESO</option>
						<option value="3">EGRESO</option>
						<option value="4">CIERRE</option>
						
					</select>
					<label for="obs">Observaciones</label>
					<textarea name="obs" id="obs" class="form-control" required></textarea>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<a class="btn btn-info btn-ok" id="btnActualizar">Actualizar</a>
			</div>
		</div>
	</div>
</div>


<?php require_once('footer.php'); ?>

<script type="text/javascript">
	$('#btnRegitrarModal').on('click', function(){
		$('#agregarMoviminetoModal').modal('show');
	});
	$('#btnActualizar').on('click', function(){
		$('#btnActualizar').prop('disabled',true);
		if ($('#idUsuario').val()!= "" && $('#idSucursal').val()!="" && $('#monto').val() != "" && $('#movimiento').val() != "" && $('#obs').val() !="") {
			var usuario = $('#idUsuario').val();
			var sucursal = $('#idSucursal').val();
			var monto = $('#monto').val();
			var movimiento = $('#movimiento').val();
			var obs = $('#obs').val();
			$.ajax({
			url: 'registrarMovientosCaja.php',
			method: 'POST',
			data: [{'name': 'idUsuario', 'value' : usuario }, {'name': 'sucursal', 'value' : sucursal }, {'name': 'monto', 'value' : monto}, {'name': 'movimiento', 'value' : movimiento }, {'name': 'obs', 'value' : obs}],
			success: function(data){
				if (data.success) {
					alertify.success('Datos ingresados correctamente',2);
					setTimeout(function(){
						window.location.href="";
					},2000);
				}else{
						alertify.error('Algo salio mal... intentelo nuevamente');
					}
			},
			error: function(error){
				//$('#cover_top').fadeOut(500);
				console.log(error);
				alertify.error('Ops algo salió mal. comprueba la conexión!');
			},

		});
		}else{
			alertify.warning('Complete todos los campos!');
		}
		
		
	});
	
</script>