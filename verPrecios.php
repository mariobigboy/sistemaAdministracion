<?php require_once('header.php'); ?>

<section class="content-header">
	<h1> Precios de Productos</h1>
</section>


<section class="content">
	<div class="row">
		<div class="form-group">

			<label for="" class="col-sm-3 control-label"><i class="fa fa-search"></i> Seleccione Productos <br> <span id="msjModo"> Modo Manual o escanear producto </span></label>
			<div class="col-sm-6">
				<select id="idSucursal" name="idSucursal" class="form-control" required>
					<?php 

						$statement = $pdo->prepare("SELECT * FROM tbl_sucursales");
						$statement->execute();
						$result = $statement->fetchAll(PDO::FETCH_ASSOC);
						echo "<option value=''>Seleccione Sucursal</option>";
						foreach($result as $row){
							if ($row['s_name'] != "Home Online" && $row['s_name'] != "Fabrica Home") {
								$ide= $_SESSION['user']['sucursal'];
								if ($row['s_id']== $ide) { 
									?>

							<option value="<?php echo $row['s_id']; ?>" selected><?php echo $row['s_name']; ?></option>
							<?php 
								}else{

									?>

							<option value="<?php echo $row['s_id']; ?>"><?php echo $row['s_name']; ?></option>
							<?php 

								}  ?>
								
						
							<?php
						}
						} 
						echo "<option value='1000'>Buscar en Todas</option>";

						?>
								
					</select>
					<input type="text" name="qtxt" id="txtBuscadorProductos" class="form-control" autocomplete="off">
					
					<div id="resultsProductos"></div>
					<div class="btn-group" role="group" >
						<a  class="btn btn-danger" id="btnEliminarLista">Limpiar Detalles</a>
						
					</div>
				</div>
			</div>

		</div>
		<div id="resultadosProductos"></div> <br>
		<div id="cuadro" style="background-color: #ffdc4c; border-radius: 5px; padding: 1em; box-shadow: 1px 1px 5px #000;">
			<h2>Producto: <span id="nombreProducto"></span> </h2>
			<div id="cuadro1" style="background-color: #000000; border-radius: 5px; padding: 2em; box-shadow: 1px 1px 5px #000;">
				<div class="row" >
					<div class="col-md-4">

						<h5 style="color: #ffdc4c">Codigo: <span id="codigoP"></span></h5>
						<h5 style="color: #ffdc4c">Stock Total: <span id="stockP"></span></h5>
						<div style="background-color: #ffdc4c; border-radius: 5px; padding: 1em; box-shadow: 1px 1px 5px #000; ">
							<h5 style="color: #000000; font-size: 1.5em;">PRECIO: $ <span id="precio"></span> </h5>
						</div>
					</div>
					<div class="col-md-4">
						
						<table class="table" id="localesP" style="cursor: pointer; color: #ffdc4c; ">
							<thead>
								<th>#</th>
								<th>Sucursal</th>
								<th>Existencias</th>

							</thead>
							<tbody>

							</tbody>

						</table>
					</div>
					<div class="col-md-4">
						<img id="imgProducto" src="../assets/uploads/product-default.png" style="width: 100%; border-radius: 5px;">
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php require_once('footer.php'); ?>

	<script>
		$('#txtBuscadorProductos').focus();
		$('#idSucursal').on('change', function(){
			$('#txtBuscadorProductos').focus();
		});
		$('#resultadosProductos').on('click', 'li', function(){
			var id = $(this).data('id');
			console.log(id);
			
			$.ajax({
				url: 'apiSearchProductosRapido.php',
				data: [{name: 'id', value: id}],
				method: 'POST',
				success: function(data){
					$("#resultadosProductos").hide();
					if(data.length > 0){
						console.log(data);
						var tabla = $('#localesP > tbody');
						tabla.empty();
						var nom= data[0];
						$('#nombreProducto').html(nom.p_name);
						$('#codigoP').html(nom.p_code);
						$('#stockP').html(nom.p_qty);
						$('#precio').html(nom.p_current_price);
						$('#imgProducto').attr('src', '../assets/uploads/'+nom.p_featured_photo);
						for(var i = 0; i < data.length; i++){
							var o = data[i];
							var linea = '<tr><td>-</td><td>'+o.s_name+'</td><td>'+o.sk_stock+' uds</td></tr>';
							tabla.append(linea);
						}
						
					}else{
						tabla.empty();
						$('#resultadosProductos').hide();
						
					}
				},
				error: function(error){
					alertify.error("Error al cargar lista");
					console.log(error.statusText);
				}
			});
		});
		$('#resultadosProductos').hide();
		$('#btnEliminarLista').on('click',function(){
			var tabla = $('#resultadosProductos');
			tabla.empty();
			$('#localesP > tbody').empty();
			$('#nombreProducto').html("");
			$('#codigoP').html("");
			$('#stockP').html("");
			$('#precio').html("");
			$('#imgProducto').attr('src',"../assets/uploads/product-default.png");
			$('#txtBuscadorProductos').focus();
			$('#resultadosProductos').hide();
			$('#resultsProd').empty();
			$('#resultadosProductos').empty();
			$('#resultadosProductos').hide();
			$('#txtBuscadorProductos').val('');
		});
		$('#btnScan').on('click', function(){
			var idSucursal = $('#idSucursal').val();
			if (idSucursal != "") {
				$('html, body').animate({
					scrollTop: $(".tablaProductosVenta").offset()
				}, 1500);

				if ( $( this ).is( ".btn-warning" ) ) {
					$(this).removeClass('btn-warning');
					$(this).addClass('btn-success');
					$('#entrada').attr('disabled',false);
					$('#entrada').focus();
					$('#txtBuscadorProductos').attr('disabled',true);
					$('#msjModo').text('Utilice el Escaner');
				} else {
					$(this).removeClass('btn-success');
					$(this).addClass('btn-warning');
					$('#entrada').attr('disabled',true);
					$('#txtBuscadorProductos').attr('disabled',false);
					$('#txtBuscadorProductos').focus();
					$('#msjModo').text('Modo Manual');
				}
			}else{
				alertify.warning('Seleccione sucursal');
			}

		});


	//txtBuscadorProductos
	var cargarTablaProductosVenta = function(q){
		var tabla = $('#resultadosProductos');
		var idSucursal = $("#idSucursal").val();
		if (idSucursal != "") {
			$.ajax({
				url: 'apiPreciosRapidos.php',
				data: [{name: 's', value: idSucursal},{name: 'q', value: q}],
				method: 'POST',
				success: function(data){
					tabla.empty();
					if(data.length > 0){
						$("#resultadosProductos").show();
						for(var i = 0; i < data.length; i++){
							var o = data[i];
							var linea = '<li data-id="'+o.p_id+'">'+o.p_code+' '+o.p_name+' | $ '+o.p_current_price+'</li>';
							tabla.append(linea);
						}
						
					}else{
						$("#resultsProd").text("No se encontraron resultados");
						tabla.empty();
						$('#resultadosProductos').hide();
						//$('#noUserMsj').show();
					}
				},
				error: function(error){
					alertify.error("Error al cargar lista");
					console.log(error.statusText);
				}
			});
		}else{
			alertify.warning("Primero seleccione una sucursal");
		}
	}

	//busqueda de productos para seleccionar
	$('#txtBuscadorProductos').keyup(function(){
		$este = $(this);
		//console.log($este.val());
		if($este.val().length > 2){
			//console.log("buscar q: " + $este.val());
			cargarTablaProductosVenta($este.val());
		}else{
			$('#resultsProd').empty();
			$('#resultadosProductos').empty();
			$('#resultadosProductos').hide();
		}
	});
</script>