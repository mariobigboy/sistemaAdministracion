<?php require_once('header.php'); 
include('inc/config.php');
?>



<section class="content-header">
	<div class="content-header-left">
		<h1>Nuevo Pedido a Carpintería</h1>
	</div>
	<div class="content-header-right">
		<a href="pedidos.php" class="btn btn-primary btn-sm">Ver Todos</a>
		
	</div>
</section>


<section class="content">

	<div class="row">
		<div class="col-md-12">

			<form class="form-horizontal" id="formBusqueda" autocomplete="off">

				<div class="box box-info">
					<div class="box-body">
						<div id="busqueda">
							<h3>Datos Factura:</h3>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-arrow-right"></i> Ingrese número Factura <span>*</span></label>
								<div class="col-sm-4">
									<input type="number" name="idFacturaBuscar" id="idFacturaBuscar" class="form-control" placeholder="Nro factura">
								</div>
							</div>
							<ul id="resultados" style="margin: 2em; padding-left: 2em; background-color: #ffdc4c; cursor: pointer; list-style: none;font-size: 18px;"></ul>

						</div>
					</form>			

					<hr>

					<div id="pedidosGral">
						<h3>Detalles de Pedidos:</h3>
						<form class="form-horizontal" id="formPedido" autocomplete="off" enctype="multipart/form-data">

							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-sticky-note"></i> Factura Seleccionada</label>
								<div class="col-sm-4">
									<input name="facturaSelec" id="facturaSelec" class="form-control" value="" disabled>
									

								</div>
							</div>

							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-user"></i> Cliente</label>
								<div class="col-sm-4">
									<input name="idCliente" id="idCliente" class="form-control" value="" disabled>


								</div>
							</div>
							<?php 

							$rol= $_SESSION['user']['role'];
							$usuario = $_SESSION['user']['full_name'];
							$sucursal = $_SESSION['user']['sucursal'];
							
							if ($rol == "Super Admin" || $rol == "Admin") {?>
								<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario; ?>">
								<div class="form-group">
									<label for="" class="col-sm-3 control-label"><i class="fa fa-user"></i> Sucursal de Entrega</label>
									<div class="col-sm-4">
										<select name="sucursal" id="sucursal" class="form-control"  required>
											<option value="">-Seleccione-</option>
											<?php 
											$statement = $pdo->prepare("SELECT s_id, s_name FROM `tbl_sucursales` WHERE s_active=1");
											$statement->execute();
											$results1 = $statement->fetchAll(PDO::FETCH_ASSOC);
											foreach ($results1 as $row) {
												echo "<option value='".$row['s_id']."'>".$row['s_name']."</option>";
											}
											?>
										</select>


									</div>
								</div>

								<?php 
							}else{?>
								<div class="form-group">
									<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario; ?>">
									<label for="" class="col-sm-3 control-label"><i class="fa fa-user"></i> Sucursal de Entrega</label>
									<div class="col-sm-4">
										<select name="sucursal" id="sucursal" class="form-control"  required>
											
											<?php 
											$statement = $pdo->prepare("SELECT s_id, s_name FROM `tbl_sucursales` WHERE s_id = '$sucursal' AND s_active=1");
											$statement->execute();
											$results1 = $statement->fetchAll(PDO::FETCH_ASSOC);
											foreach ($results1 as $row) {
												echo "<option value='".$row['s_id']."'>".$row['s_name']."</option>";
											}
											?>
										</select>


									</div>
								</div>

						<?php } ?>
							<br>
							<hr>
							<div class="row">
								<div class="col-md-4">
									<p style="background-color: #ffdc4c; padding: 1em; font-size: 14px;">Seleccione Items para pedido:</p>
								</div>
							</div>
							<table class="table " id="tablaProductosFabrica">
								<thead>
									<th>#</th>
									<th>Descripción</th>
									
									<th>Cantidad</th>
									
								</thead>
								<tbody>

								</tbody>


							</table>

							<hr>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-pencil"></i> Observaciones </label>
								<div class="col-sm-4">
									<textarea class="form-control" name="obs" id="obs"></textarea>
									<input type="hidden" name="factura" id="factura">
									<input type="hidden" name="datos" id="datos">
								</div>
							</div>

							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-photo"></i> Foto 1</label>
								<div class="col-sm-4">
									<input type="file" name="foto1" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-photo"></i> Foto 2</label>
								<div class="col-sm-4">
									<input type="file" name="foto2" class="form-control">
								</div>
							</div>


							<div class="form-group">
								<label for="" class="col-sm-3 control-label"></label>
								<div class="col-sm-6">
									<button id="btnGuardarPedido" class="btn btn-success pull-left" name="form1">Guardar</button>
								</div>
							</div>
						</div>
					</div>

				</form>
			</div> <!-- pedidosGral -->



		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>

<script type="text/javascript">
	$('#formBusqueda').submit(function(e){
		e.preventDefault();
	});

	$('#pedidosGral').hide();

	//txtBuscadorProductos
	var cargarResultados = function(q){
		var lista = $('#resultados');
		$.ajax({
			url: 'apiSearchFactura.php',
			data: [{name: 'idFactura', value: q}],
			method: 'POST',
			success: function(data){
				lista.empty();
				if(data.length > 0){
					console.log(data);
					for(var i = 0; i < data.length; i++){
						var o = data[i];
						if (o.id_cliente==21) {
							alertify.warning("No se puede hacer pedido con factura a Consumidor Final")
						}else{
							$('#factura').val(q);
							var nom = o.c_apellido+',  '+o.c_nombre;
							var linea = '<li data-id="'+o.num_factura+'" data-nombre="'+nom+'">Fac Nro:'+o.num_factura+' | '+nom+' </li>';
							lista.append(linea);
						}
					}
				}else{

					lista.empty();
				}
			},
			error: function(error){
				alertify.error("Error al cargar factura");
				console.log(error);
			}
		});
	}


	//busco factura
	$('#idFacturaBuscar').keyup(function(){
		$este = $(this);
		//console.log($este.val());
		if($este.val().length > 1){
			//console.log("buscar q: " + $este.val());
			cargarResultados($este.val());
		}else{
			$('#resultados').empty();
			
		}
	});

		//txtBuscadorProductos
		var cargarResultadosFactura = function(num){
			var tabla = $('#tablaProductosFabrica > thead');
			$.ajax({
				url: 'apiFactura.php',
				data: [{name: 'idFactura', value: num}],
				method: 'POST',
				success: function(data){
					//tabla.empty();
					if(data.length > 0){
						console.log(data);
						for(var i = 0; i < data.length; i++){
							var o = data[i];
							var linea = '<tr data-id="'+o.id_detalle+'"><td>'+(i+1)+'</td><td>'+o.nombre+'</td><td>'+o.cantidad+'</td> </tr>';
							tabla.append(linea);
							$('#obs').val(o.obs);
						}
					}else{
						
						tabla.empty();
					}
				},
				error: function(error){
					alertify.error("Error al cargar tabla");
					console.log(error);
				}
			});
		}
		$('#resultados').on('click','li', function(){
			var idFac = $(this).data('id');
			var cliente = $(this).data('nombre');
			cargarResultadosFactura(idFac);
			$('#busqueda').hide();
			$('#facturaSelec').val(idFac);
			$('#idCliente').val(cliente);
			$('#resultados').empty();
			$('#resultados').hide();
			$('#pedidosGral').show();
		});

		$('#tablaProductosFabrica > thead').on('click', 'tr', function(){
			var idDet = $(this).data('id');
			var linea = $(this);
			
			if (linea.css('background-color')=='rgb(255, 220, 76)') {
				linea.css('background-color','#ffffff');
			}else{
				linea.css('background-color','#ffdc4c');
			}
			cargaDetalles();
			
		});

		function cargaDetalles(){
			var arr =[];
			$("#tablaProductosFabrica thead tr").each(function (ind) {
				if ($(this).css('background-color')=='rgb(255, 220, 76)') {
					arr.push($(this).data('id'));
				}
			});
			$('#datos').val(arr);
			console.log(arr);
		}

		$("form#formPedido").submit(function(e) {
			e.preventDefault();    
			if ($('#sucursal').val()!="" && $('#datos').val() != "") {
				var formData = new FormData(this);

			$.ajax({
				url: 'guardarDatosCarpinteria.php',
				type: 'POST',
				data: formData,
				success: function (data) {
					alertify.warning('Un momento por favor...');
					setTimeout(function(){
						if (data==0) {
						alertify.warning("La factura ingresada ya tiene una orden generada!");
						setTimeout(function(){window.location.href=""},1000);
					}else{
						if (data==1) {
							alertify.success("Orden generada correctamente!!");
							setTimeout(function(){window.location.href="carpinteria.php"},1000);
						}else{
							alertify.error("Algo salio mal...");
						}
					}
					},2000);
				},
				cache: false,
				contentType: false,
				processData: false
			});
		}else{
			if ($('#sucursal').val()=="") {

			}else{
				alertify.warning('Debe seleccionar al menos un pedido.');

			}
		}
		});

	</script>