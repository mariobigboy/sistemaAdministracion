<?php require_once('header.php'); 
include('inc/config.php');
?>



<section class="content-header">
	<div class="content-header-left">
		<h1>Nota de Credito</h1>
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
									<p style="background-color: #ffdc4c; padding: 1em; font-size: 14px;">Seleccione Items a devolver:</p>
								</div>
							</div>
							<table class="table " id="tablaProductosFabrica">
								<thead>
									<th>#</th>
									<th>Descripción</th>
									
									<th>Cantidad</th>
									<th>Precio Unit</th>
									
								</thead>
								<tbody>

								</tbody>


							</table>

							<hr>
							<section id="productosDevueltos" style="background-color: #f0f0f0;">

								<table class="table" id="tablaProductosDevueltos">
									<thead class="thead-dark">
										<tr>
											<th scope="col">#</th>
											<th scope="col">Código</th>
											<th scope="col">Articulo</th>
											<th scope="col">Precio Unitario</th>
											<th scope="col">Cantidad</th>
											<th scope="col">Importe</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>

								</table>

								<div class="row">
									<div class="col-md-4">
										<p style="background-color: #ffdc4c; padding: 1em; font-size: 14px;">Total Crédito:  $ <span id="idTotal">0</span></p>
									</div>
								</div>
								
							</section>

							<div class="form-group">


								<label for="" class="col-sm-3 control-label"><i class="fa fa-pencil"></i> Observaciones </label>
								<div class="col-sm-4">
									<textarea class="form-control" name="obs" id="obs"></textarea>
									<input type="hidden" name="factura" id="factura">
									<input type="hidden" name="datos" id="datos">
								</div>
							</div>

							<!-- <div class="form-group">
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
							</div> -->




							<div class="form-group">
								<label for="" class="col-sm-3 control-label"></label>
								<div class="col-sm-6">
									<button id="btnGuardarPedido" class="btn btn-success pull-left" name="form1">Guardar</button>
								</div>
							</div>
						</div>
					</div> <!-- row -->

				</form>
			</div> <!-- pedidosGral -->



		</div>
	</div>

</section>

<div class="modal fade" id="cantidadModal" tabindex="-1" role="dialog" aria-labelledby="myLabelModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myLabelModal" style="color: #000;"><i class="fa fa-number"></i> Confirmar devolución</h4>
			</div>
			<div class="modal-body">
				<p>¿Cantidad de producto a devolver?</p>
				<div class="col-12 text-center">
					<input type="hidden" id="prodIdLinea" value="">
					<input type="hidden" id="idProducto" value="">
					<input type="hidden" id="prodPrecioU" value="">
					<input type="hidden" id="prodIdNom" value="">
					<input type="number" id="cantProductoAdevolver" name="cantProductoAdevolver" min="0" step="1" class="text-center">

				</div>


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"> Cancelar</button>
				<a id="btnDescontarProducto" class="btn btn-warning"> Descontar</a>
			</div>
		</div>
	</div>
</div>

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
					//console.log(data);
					for(var i = 0; i < data.length; i++){
						var o = data[i];
						$('#factura').val(q);
						var nom = o.c_apellido+',  '+o.c_nombre;
						var linea = '<li data-id="'+o.num_factura+'" data-nombre="'+nom+'">Fac Nro:'+o.num_factura+' | '+nom+' </li>';
						lista.append(linea);
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
			var tabla = $('#tablaProductosFabrica > tbody');
			$.ajax({
				url: 'apiFacturaNC.php',
				data: [{name: 'idFactura', value: num}],
				method: 'POST',
				success: function(data){
					//tabla.empty();
					if(data.length > 0){
						//console.log(data);
						for(var i = 0; i < data.length; i++){
							var o = data[i];
							if (o.precio < 0) {
								var linea = '<tr data-id="'+o.id_detalle+'" data-idproducto="'+o.id_producto+'" data-nombre="'+o.nombre+'" data-precio="'+o.precio+'" data-cant="'+o.cantidad+'" style="background-color: red;"><td>'+(i+1)+'</td><td>'+o.nombre+' (TIENE PROMOCIONES!!!)</td><td>'+o.cantidad+'</td> <td>'+o.precio+'</td> </tr>';
							}else{
								var linea = '<tr data-id="'+o.id_detalle+'" data-idproducto="'+o.id_producto+'" data-nombre="'+o.nombre+'" data-precio="'+o.precio+'" data-cant="'+o.cantidad+'"><td>'+(i+1)+'</td><td>'+o.nombre+'</td><td>'+o.cantidad+'</td> <td>'+o.precio+'</td> </tr>';
							}
							tabla.append(linea);
							//$('#obs').val(o.obs);
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

		$('#tablaProductosFabrica > tbody').on('click', 'tr', function(){
			var tabla = $('#tablaProductosDevueltos tbody');
			var idDet = $(this).data('id');
			var idProducto = $(this).data('idproducto');
			var cant = parseInt($(this).data('cant'));
			var nombre = $(this).data('nombre');
			var precio = parseFloat($(this).data('precio'));
			var linea = $(this);
			if (precio > 0) {
				if (cant > 0) {
					$('#cantProductoAdevolver').val(cant);
					$('#prodIdLinea').val(idDet);
					$('#prodIdNom').val(nombre);
					$('#prodPrecioU').val(precio);
					$('#idProducto').val(idProducto);

					$('#cantidadModal').modal('show');
					//console.log('abriendo modal...');
				}
				/*if (linea.css('background-color')=='rgb(255, 220, 76)') {
					linea.css('background-color','#ffffff');
				}else{
					linea.css('background-color','#ffdc4c');
				}*/
				cargaDetalles();
			}
			
		});
		$('#btnDescontarProducto').on('click', function(){
			$('#cantidadModal').modal('hide');

			var tabla = $('#tablaProductosDevueltos tbody');
			var cant= parseInt($('#cantProductoAdevolver').val());
			var idLineaDetalles = $('#prodIdLinea').val();
			var nombre= $('#prodIdNom').val();
			var precio = parseFloat($('#prodPrecioU').val());
			var idProducto = $('#idProducto').val();
			var lineaTabla = "";
			var numerosTr= $('#tablaProductosDevueltos tr').length;

			if($('#tablaProductosDevueltos tbody tr[data-idproducto="'+idProducto+'"][data-nombre="'+nombre+'"]').length == 0){
				lineaTabla += "<tr data-id='"+idLineaDetalles+"' data-idProducto='"+idProducto+"' data-nombre='"+nombre+"' data-precio='"+precio+"' data-cant='"+cant+"'><td>"+numerosTr+"</td><td>"+idProducto+"</td><td>"+nombre+"</td><td>"+precio+"</td><td>"+cant+"</td><td>"+((precio*cant)*-1).toFixed(2)+"</td></tr>";
				/*var total = parseFloat($('#idTotal').html());
				total += parseFloat(precio*cant).toFixed(2);
				$('#idTotal').html(total);*/
				tabla.append(lineaTabla);
			}else{
				$($('#tablaProductosDevueltos tbody tr[data-idproducto="'+idProducto+'"][data-nombre="'+nombre+'"]').data('cant', cant));
				$($('#tablaProductosDevueltos tbody tr[data-idproducto="'+idProducto+'"][data-nombre="'+nombre+'"]').children()[4]).text(cant);
				$($('#tablaProductosDevueltos tbody tr[data-idproducto="'+idProducto+'"][data-nombre="'+nombre+'"]').children()[5]).text(parseFloat(precio * cant).toFixed(2));
			}

			//console.log(nombre);

			
			//le quitamos el amarillo a todos:
			$('#tablaProductosFabrica tbody tr').each(function(){
				$(this).removeClass('alerta-amarillo');
			});

			//le ponemos el amarillo solo a los que estan en la tabla productos devueltos:
			var total = 0;
			$('#tablaProductosDevueltos tbody tr').each(function(){
				if($(this).data('cant')==0){
					$(this).remove();
				}else{
					total += parseInt($(this).data('cant')) * parseFloat($(this).data('precio'));
					$('#tablaProductosFabrica tbody tr[data-idproducto='+$(this).data('idproducto')+'][data-nombre="'+$(this).data('nombre')+'"]').addClass('alerta-amarillo');
				}
			});
			$('#idTotal').html(total.toFixed(2));
		});

		$('#btnGuardarPedido').on('click', function(e){
			e.preventDefault();

			var sucursal = $('#sucursal').val();
			if (sucursal == "") {
				alertify.warning("Seleccione sucursal de devolucion de producto...");
				$('#sucursal').focus();
			}else{
				if($('#tablaProductosDevueltos tbody tr').length > 0){
					var factura = $('#facturaSelec').val();
					var usuario = $('#usuario').val();
					var obs = $('#obs').val();


					var datos = [];
					$('#tablaProductosDevueltos tbody tr').each(function(){
					var $this = $(this);
					datos.push({
							id: $this.data('id'),
							precio: $this.data('precio'),
							cantidad: $this.data('cant'),
							nombre: $this.data('nombre'),
							idProducto: $this.data('idproducto')
						});
						//console.log(datos);
					});

					//console.log(datos);
					var data_to_send = [
							{
								name: 'acc',
								value: 'addNotaCredito'
							},
							{
								name: 'nota_credito', 
								value: JSON.stringify(datos)
							},
							{
								name: 'usuario',
								value: usuario
							},
							{
								name: 'sucursal',
								value: sucursal
							},
							{
								name: 'factura',
								value: factura
							},
							{
								name: 'obs',
								value: obs
							}
							];
					//console.log(data_to_send);

					$.ajax({
						data: data_to_send,
						method: 'POST',
						url: 'apiComprobantes.php',
						success: function(data){
							if (data.success) {
								alertify.success('Documento generado correctamente');
								setTimeout(function(){window.location.href="notaCredito.php"},2000);
							}else{
								alertify.error('Algo salió mal...');
							}
						},
						error: function(err){
							alertify.error(err);
						}
					});
				}else{
					alertify.warning("Ingrese al menos un producto para continuar");
				}
				
			}
		});

		function cargaDetalles(){
			var arr =[];
			$("#tablaProductosFabrica tbody tr").each(function (ind) {
				if ($(this).css('background-color')=='rgb(255, 220, 76)') {
					arr.push($(this).data('id'));
				}
			});
			$('#datos').val(arr);
			//console.log(arr);
		}

		

	</script>