<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	$_POST['p_utilidad'] = isset($_POST['p_utilidad'])? $_POST['p_utilidad'] : 0;

    if(empty($_POST['tcat_id'])) {
        $valid = 0;
        $error_message .= "Debe elegir una Sección.<br>";
    }

    if(empty($_POST['mcat_id'])) {
        $valid = 0;
        $error_message .= "Debe elegir un Rubro.<br>";
    }

    if(empty($_POST['ecat_id'])) {
        $valid = 0;
        $error_message .= "Debe elegir un Sub-Rubro.<br>";
    }

    if(empty($_POST['p_sucursal_id'])) {
        $valid = 0;
        $error_message .= "Debe elegir una sucursal.<br>";
    }

    if(empty($_POST['p_name'])) {
        $valid = 0;
        $error_message .= "El nombre del producto no puede estar vacío.<br>";
    }

    if(empty($_POST['p_current_price'])) {
        $valid = 0;
        $error_message .= "El precio actual no puede estar vacío.<br>";
    }

    if(empty($_POST['p_cost_price'])) {
        $valid = 0;
        $error_message .= "El precio de Costo no puede estar vacío.<br>";
    }


    if($_POST['p_qty']==''){
    	$_POST['p_qty']	= 0;
    }
    if(!isset($_POST['p_qty'])) {
        $valid = 0;
        $error_message .= "La cantidad no puede estar vacía.<br>";
    }


    /*if(empty($_POST['p_codebar'])) {
        $valid = 0;
        $error_message .= "Debe ingresar el código de barra.<br>";
    }*/

    if(empty($_POST['p_code'])) {
        $valid = 0;
        $error_message .= "Debe ingresar un código del producto.<br>";
    }

    if(empty($_POST['p_brand'])) {
        $valid = 0;
        $error_message .= "Debe ingresar una marca.<br>";
    }

    if(empty($_POST['p_list_price'])) {
        $valid = 0;
        $error_message .= "Debe ingresar precio de lista.<br>";
    }

    $path = $_FILES['p_featured_photo']['name'];
    $path_tmp = $_FILES['p_featured_photo']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'Debe subir un archivo jpg, jpeg, gif or png.<br>';
        }
    } //else {
    	//$valid = 0;
        //$error_message .= 'Debe elegir una foto Principal.<br>';

    //}


    if($valid == 1) {

    	$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row) {
			$ai_id=$row[10];
		}
		
		if ($path != '') {

	    	if( isset($_FILES['photo']["name"]) && isset($_FILES['photo']["tmp_name"]) ){
	        	$photo = array();
	            $photo = $_FILES['photo']["name"];
	            $photo = array_values(array_filter($photo));

	        	$photo_temp = array();
	            $photo_temp = $_FILES['photo']["tmp_name"];
	            $photo_temp = array_values(array_filter($photo_temp));

	        	$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product_photo'");
				$statement->execute();
				$result = $statement->fetchAll();
				foreach($result as $row) {
					$next_id1=$row[10];
				}
				$z = $next_id1;

	            $m=0;
	            for($i=0;$i<count($photo);$i++)
	            {
	                $my_ext1 = pathinfo( $photo[$i], PATHINFO_EXTENSION );
			        if( $my_ext1=='jpg' || $my_ext1=='png' || $my_ext1=='jpeg' || $my_ext1=='gif' ) {
			            $final_name1[$m] = $z.'.'.$my_ext1;
	                    move_uploaded_file($photo_temp[$i],"../assets/uploads/product_photos/".$final_name1[$m]);
	                    $m++;
	                    $z++;
			        }
	            }

	            if(isset($final_name1)) {
	            	for($i=0;$i<count($final_name1);$i++)
			        {
			        	$statement = $pdo->prepare("INSERT INTO tbl_product_photo (photo, p_id) VALUES (?,?)");
			        	$statement->execute(array($final_name1[$i], $ai_id));
			        }
	            }            
	        }

			$final_name = 'product-featured-'.$ai_id.'.'.$ext;
	        move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );
			
		}else{
			$final_name="product-default.png";
		}

		//Saving data into the main table tbl_product
		$statement = $pdo->prepare("INSERT INTO tbl_product(
										p_name,
										p_old_price,
										p_current_price,
										p_qty,
										p_featured_photo,
										p_description,
										p_short_description,
										p_feature,
										p_condition,
										p_return_policy,
										p_total_view,
										p_is_featured,
										p_is_active,
										ecat_id,
										p_sucursal_id,
										p_codebar,
										p_code,
										p_brand,
										p_list_price,
										p_cost_price,
										p_utilidad,
										p_extra_expensives
									) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$statement->execute(array(
										$_POST['p_name'],
										$_POST['p_old_price'],
										$_POST['p_current_price'],
										$_POST['p_qty'],
										$final_name,
										$_POST['p_description'],
										$_POST['p_short_description'],
										$_POST['p_feature'],
										$_POST['p_condition'],
										$_POST['p_return_policy'],
										0,
										$_POST['p_is_featured'],
										$_POST['p_is_active'],
										$_POST['ecat_id'],
										$_POST['p_sucursal_id'],
										$_POST['p_codebar'],
										$_POST['p_code'],
										$_POST['p_brand'],
										$_POST['p_list_price'],
										$_POST['p_cost_price'],
										$_POST['p_utilidad'],
										$_POST['p_extra_expensives']
									));

		$idProducto = $pdo->lastInsertId();


		//Guarda Stock actual en la sucursal
		$statement = $pdo->prepare("INSERT INTO tbl_stock (
										sk_id_producto,
										sk_id_sucursal,
										sk_stock ) VALUES (?,?,?)");
		$statement->execute(array(
										$idProducto,
										$_POST['p_sucursal_id'],
										$_POST['p_qty']
									));

		//guardamos registro en la historia del producto (tbl_historia)
		//guardo en historia:
		$h_id_user = $_SESSION['user']['id'];
		$h_id_sucursal = $_POST['p_sucursal_id'];
		$h_detalle = 'ALTA';//($s_detalle==1)? 'ALTA' : 'BAJA';
		$h_fecha = time();
		$h_stock_anterior = 0;
		$stock_total = $_POST['p_qty'];
		$statHistory = $pdo->prepare("INSERT INTO tbl_historia (
																h_id_producto,
																h_id_user,
																h_id_sucursal,
																h_stock_anterior,
																h_stock_actual,
																h_detalle,
																h_obs,
																h_fecha
															) VALUES (?,?,?,?,?,?,?,?);");
		$statHistory->execute(array(
									$idProducto,
									$h_id_user,
									$h_id_sucursal,
									$h_stock_anterior,
									$stock_total,
									'ALTA',
									'NUEVO PRODUCTO',
									$h_fecha));

		

        if(isset($_POST['size'])) {
			foreach($_POST['size'] as $value) {
				$statement = $pdo->prepare("INSERT INTO tbl_product_size (size_id, p_id) VALUES (?, ?)");
				$statement->execute(array($value,$ai_id));
			}
		}

		if(isset($_POST['color'])) {
			foreach($_POST['color'] as $value) {
				$statement = $pdo->prepare("INSERT INTO tbl_product_color (color_id, p_id) VALUES (?, ?)");
				$statement->execute(array($value,$ai_id));
			}
		}
	
    	//Guardo Log:
    	
		
		//ALTA
		//BAJA
		//MODIFICACION
		//TRASPASO
		//LOGIN
		//LOGOUT
		//ABRIR CAJA
		//CERRAR CAJA
		
		/*$id_usuario = $_SESSION['user']['id'];
		$id_sucursal = $_SESSION['user']['sucursal'];
		$detalle = 'ALTA PRODUCTO';
		$fecha = time();
		$statement = $pdo->prepare("INSERT INTO tbl_logs (
										id_usuario,
										id_sucursal,
										id_producto,
										detalle,
										fecha) VALUES (?,?,?,?,?)");
		$statement->execute(array(
										$id_usuario,
										$id_sucursal,
										$idProducto,
										$detalle,
										$fecha
										
									));*/
    	$success_message = 'Producto agregado correctamente.';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Nuevo producto: </h1>
	</div>
	<div class="content-header-right">
		<a href="product.php" class="btn btn-primary btn-sm">Ver Todos</a>
	</div>
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

			<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-tag"></i> Sección <span>*</span></label>
							<div class="col-sm-4">
								<select name="tcat_id" class="form-control select2 top-cat" required>
									<option value="">Seleccione Sección</option>
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_top_category ORDER BY tcat_name ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
									foreach ($result as $row) {
										?>
										<option value="<?php echo $row['tcat_id']; ?>"><?php echo $row['tcat_name']; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-tag"></i> Rubro <span>*</span></label>
							<div class="col-sm-4">
								<select name="mcat_id" class="form-control select2 mid-cat" required>
									<option value="">Seleccione Rubro</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-tag"></i> Sub-Rubro <span>*</span></label>
							<div class="col-sm-4">
								<select name="ecat_id" class="form-control select2 end-cat" required>
									<option value="">Seleccione Sub-Rubro</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-building"></i> Sucursal <span>*</span></label>
							<div class="col-sm-4">
								<select name="p_sucursal_id" class="form-control select2" required>
									
									<?php
									if($_SESSION['user']['role']=='Empleado'){
										$idSuc = $_SESSION['user']['sucursal'];
										$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_online=? AND s_id=?;");
										$statement->execute(array(0, $idSuc));
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
										foreach ($result as $row) {
											?>
											<option value="<?php echo $row['s_id']; ?>" selected><?php echo $row['s_name']; ?></option>
											<?php
										}
									}else{
										$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_online=? ORDER BY s_name ASC;");
										$statement->execute(array(0));
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
										echo '<option value="">Seleccione la sucursal </option>';
										foreach ($result as $row) {
											?>
											<option value="<?php echo $row['s_id']; ?>"><?php echo $row['s_name']; ?></option>
											<?php
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-tag"></i> Nombre del producto <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_name" class="form-control">
							</div>
						</div>	
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-barcode"></i> Código (en sistema) <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_code" class="form-control generateCode" readonly>
							</div>
						</div>	
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-barcode"></i> Código de barra (de fábrica)<span></span></label>
							<div class="col-sm-4">
								<input type="text" name="p_codebar" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-tags"></i> Marca <span></span></label>
							<div class="col-sm-4">
								<select name="p_brand" class="form-control select2" required>
									<option value="">Seleccione Marca</option>
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_marcas ORDER BY marca ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
									foreach ($result as $row) {
										?>
										<option value="<?php echo $row['id']; ?>"><?php echo $row['marca']; ?></option>
										<?php
									}
									?>
								</select>
								
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-plus"></i> I.V.A. (%) <span>*</span><!--<br><span style="font-size:10px;font-weight:normal;">(ARS)</span>--></label>
							<div class="col-sm-4">
								<?php 
									$statement = $pdo->prepare('SELECT IVA FROM tbl_settings;');
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);
									foreach ($result as $row) {
										$IVA = $row['IVA'];
									}
								?>
								<input type="text" name="p_iva" class="form-control" value="<?php echo $IVA; ?>" disabled>
							</div>
						</div>	

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-usd"></i>Utilidad (%)<span>*</span><br><span style="font-size:10px;font-weight:normal;">(ARS)</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_utilidad" class="form-control" value="100" disabled>
							</div>
						</div>	

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-usd"></i> Gastos Extras <br><span style="font-size:10px;font-weight:normal;">(ARS)</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_extra_expensives" class="form-control" value="0">
							</div>
						</div>
							
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-usd"></i> Precio de Costo <span>*</span><br><span style="font-size:10px;font-weight:normal;">(ARS)</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_cost_price" class="form-control">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-usd"></i> Precio de lista <span>*</span><br><span style="font-size:10px;font-weight:normal;">(ARS)</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_list_price" class="form-control">
							</div>
						</div>		
						<div class="form-group" >
							<label for="" class="col-sm-3 control-label"><i class="fa fa-usd"></i> Precio anterior <br><span style="font-size:10px;font-weight:normal;">(Saldrá tachado en la publicación)</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_old_price" class="form-control" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-usd"></i> Precio actual <span>*</span><br><span style="font-size:10px;font-weight:normal;">(ARS)</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_current_price" class="form-control">
							</div>
						</div>	
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-list-ol"></i> Cantidad <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_qty" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-tags"></i> Seleccione tamaño</label>
							<div class="col-sm-4">
								<select name="size[]" class="form-control select2" multiple="multiple">
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_size ORDER BY size_id ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);			
									foreach ($result as $row) {
										?>
										<option value="<?php echo $row['size_id']; ?>"><?php echo $row['size_name']; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-dashboard"></i> Seleccione color</label>
							<div class="col-sm-4">
								<select name="color[]" class="form-control select2" multiple="multiple">
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_color ORDER BY color_name ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);			
									foreach ($result as $row) {
										?>
										<option value="<?php echo $row['color_id']; ?>"><?php echo $row['color_name']; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-camera"></i> Foto Principal <span>*</span></label>
							<div class="col-sm-4" style="padding-top:4px;">
								<input type="file" name="p_featured_photo">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-camera"></i> Otras Fotos</label>
							<div class="col-sm-4" style="padding-top:4px;">
								<table id="ProductTable" style="width:100%;">
			                        <tbody>
			                            <tr>
			                                <td>
			                                    <div class="upload-btn">
			                                        <input type="file" name="photo[]" style="margin-bottom:5px;">
			                                    </div>
			                                </td>
			                                <td style="width:28px;"><a href="javascript:void()" class="Delete btn btn-danger btn-xs">X</a></td>
			                            </tr>
			                        </tbody>
			                    </table>
							</div>
							<div class="col-sm-2">
			                    <input type="button" id="btnAddNew" value="Add Item" style="margin-top: 5px;margin-bottom:10px;border:0;color: #fff;font-size: 14px;border-radius:3px;" class="btn btn-warning btn-xs">
			                </div>

						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Descripción</label>
							<div class="col-sm-8">
								<textarea name="p_description" class="form-control" cols="30" rows="10" id="editor1"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Descripción corta</label>
							<div class="col-sm-8">
								<textarea name="p_short_description" class="form-control" cols="30" rows="10" id="editor2"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Características</label>
							<div class="col-sm-8">
								<textarea name="p_feature" class="form-control" cols="30" rows="10" id="editor3"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Condiciones</label>
							<div class="col-sm-8">
								<textarea name="p_condition" class="form-control" cols="30" rows="10" id="editor4"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Política de devolución</label>
							<div class="col-sm-8">
								<textarea name="p_return_policy" class="form-control" cols="30" rows="10" id="editor5"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Producto Destacado</label>
							<div class="col-sm-8">
								<select name="p_is_featured" class="form-control" style="width:auto;">
									<option value="1">Sí</option>
									<option value="0" selected>No</option>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">¿Publicar en Web?</label>
							<div class="col-sm-8">
								<select name="p_is_active" class="form-control" style="width:auto;">
									<option value="1" >Sí</option>
									<option value="0" selected>No</option>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Guardar</button>
							</div>
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>
<script type="text/javascript">
	
</script>