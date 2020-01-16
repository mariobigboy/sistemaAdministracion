<?php require_once('header.php'); ?>


<section class="content-header">
	<h1>Caja por Fecha</h1>
</section>

<?php
	//$statement = $pdo->prepare("SELECT * FROM tbl_top_category");
	//$statement->execute();
	//$total_top_category = $statement->rowCount();
?>

<section class="content">
	<div class="row">
		<div class="col-md-4 col-sm-6 col-xs-12">
			<span class="info-box-text">Seleccione Datos</span>
			<form id="formFecha" autocomplete="off">
				<label for="datepicker">Seleccione Fecha</label> <br>

					<label>Desde</label>
				<div class="input-group date" data-provide="datepicker">
					<input type="text" id="datepicker" name="datepicker" class="form-control">
					<div class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</div>
				</div> <br>
					<label>Hasta</label>
				<div class="input-group date" data-provide="datepicker1">
					<input type="text" id="datepicker1" name="datepicker1" class="form-control">
					<div class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</div>
				</div> <br>
				<div class="form-group">
					<div class="col-sm-6">
						<label for="tipo">Por sucursal</label>
						<input type="radio" name="tipo" id="tipoSucursal" value="1" checked> 
					</div>
					<div class="col-sm-6">
						<label>Por usuario</label>
					<input type="radio" name="tipo" id="tipoUsuario" value="2">
					</div>
					
				</div>
				<br>
				 <br>
				<select name="sucursal" id="sucursal" class="form-control">
					<option value="-1">-Seleccione sucursal-</option>
					<?php 
					$statement = $pdo->prepare("SELECT s_id, s_name FROM tbl_sucursales;");
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					for($i=0;$i<sizeof($result);$i++){
						echo "<option value=".$result[$i]['s_id'].">".$result[$i]['s_name']."</option>";
					}

				 ?>
					
				</select> <br>

				<select name="usuario" id="usuario" class="form-control">
					<option value="-1">-Seleccione usuario-</option>
					<?php 
					$statement = $pdo->prepare("SELECT id, full_name FROM tbl_user WHERE role= 'Empleado' OR role = 'Admin' ORDER BY full_name;");
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					for($i=0;$i<sizeof($result);$i++){
						echo "<option value=".$result[$i]['id'].">".$result[$i]['full_name']."</option>";
					}

				 ?>
					
				</select>

				

			<br></form>
			<button class="btn btn-info" id="btnBuscar">Buscar</button>
		</div>
		
		
	</div>
</section>

<?php require_once('footer.php'); ?>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	$('#usuario').hide();
	$( "#datepicker" ).datepicker({
		dateFormat: "dd-mm-yy",
		dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ]

	});
	$( "#datepicker1" ).datepicker({
		dateFormat: "dd-mm-yy",
		dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ]

	});
	// $("input[name=tipo]").each(function(){
 //                    if(this.checked)
 //                    {
 //                        sw=true;
 //                    }
 //                });
	$('input[name=tipo]').on('click', function(){
		if ($(this).val()==1) {
			$('#usuario').hide();
			$('#usuario').val('-1');
			$('#sucursal').show();

		}else{
			$('#sucursal').hide();
			$('#sucursal').val('-1');
			$('#usuario').show();
		}
	});
	$('#btnBuscar').on('click', function(){
		if (($('#usuario').val()=="-1" && $('#sucursal').val()=="-1") || $('#datepicker').val()=="" || $('#datepicker1').val()=="") {
			alertify.error('Debe seleccionar un parámetro de búsqueda y la Fecha!');
		}else{
			var param = $('#formFecha').serialize();
			alertify.warning('Un momento por favor',2);
			setTimeout(function(){window.location.href= "buscarFacturacionMensual.php?"+param;}, 2000);
		}
	});
</script>