<?php require_once('header.php'); ?>

<?php

$role = $_SESSION['user']['role'];

if(isset($_POST['form1'])) {
	$valid = 1;

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

    // if(empty($_POST['p_sucursal_id'])) {
    //     $valid = 0;
    //     $error_message .= "Debe elegir una Sucursal.<br>";
    // }

    if(empty($_POST['p_name'])) {
        $valid = 0;
        $error_message .= "El nombre del producto no puede estar vacío.<br>";
    }

    if(empty($_POST['p_current_price'])) {
        $valid = 0;
        $error_message .= "El precio actual no puede estar vacío.<br>";
    }

    // if(empty($_POST['p_qty'])) {
    //     $valid = 0;
    //     $error_message .= "La cantidad no puede estar vacía.<br>";
    // }

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
            $error_message .= 'Debe elegir un archivo de formato jpg, jpeg, gif o png<br>';
        }
    }


    if($valid == 1) {

    	if( isset($_FILES['photo']["name"]) && isset($_FILES['photo']["tmp_name"]) )
        {

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
		        	$statement = $pdo->prepare("INSERT INTO tbl_product_photo (photo,p_id) VALUES (?,?)");
		        	$statement->execute(array($final_name1[$i],$_REQUEST['id']));
		        }
            }            
        }

        if($path == '') {
        	$statement = $pdo->prepare("UPDATE tbl_product SET 
        							p_name=?, 
        							p_old_price=?, 
        							p_current_price=?, 
        							p_description=?,
        							p_short_description=?,
        							p_feature=?,
        							p_condition=?,
        							p_return_policy=?,
        							p_is_featured=?,
        							p_is_active=?,
        							ecat_id=?,
        							p_codebar=?,
									p_code=?,
									p_brand=?,
									p_list_price=?,
									p_cost_price=?,
									p_utilidad=?,
									p_extra_expensives=?

        							WHERE p_id=?");
        	$statement->execute(array(
        							$_POST['p_name'],
        							$_POST['p_old_price'],
        							$_POST['p_current_price'],
        							$_POST['p_description'],
        							$_POST['p_short_description'],
        							$_POST['p_feature'],
        							$_POST['p_condition'],
        							$_POST['p_return_policy'],
        							$_POST['p_is_featured'],
        							$_POST['p_is_active'],
        							$_POST['ecat_id'],
        							$_POST['p_codebar'],
									$_POST['p_code'],
									$_POST['p_brand'],
									$_POST['p_list_price'],
									$_POST['p_cost_price'],
									$_POST['p_utilidad'],
									$_POST['p_extra_expensives'],
        							$_REQUEST['id']
        						));
        } else {

        	unlink('../assets/uploads/'.$_POST['current_photo']);

			$final_name = 'product-featured-'.$_REQUEST['id'].'.'.$ext;
        	move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );


        	$statement = $pdo->prepare("UPDATE tbl_product SET 
        							p_name=?, 
        							p_old_price=?, 
        							p_current_price=?, 
        							p_featured_photo=?,
        							p_description=?,
        							p_short_description=?,
        							p_feature=?,
        							p_condition=?,
        							p_return_policy=?,
        							p_is_featured=?,
        							p_is_active=?,
        							ecat_id=?,
        							p_codebar=?,
									p_code=?,
									p_brand=?,
									p_list_price=?,
									p_cost_price=?,
									p_utilidad=?,
									p_extra_expensives=?

        							WHERE p_id=?");
        	$statement->execute(array(
        							$_POST['p_name'],
        							$_POST['p_old_price'],
        							$_POST['p_current_price'],
        							$final_name,
        							$_POST['p_description'],
        							$_POST['p_short_description'],
        							$_POST['p_feature'],
        							$_POST['p_condition'],
        							$_POST['p_return_policy'],
        							$_POST['p_is_featured'],
        							$_POST['p_is_active'],
        							$_POST['ecat_id'],
        							$_POST['p_codebar'],
									$_POST['p_code'],
									$_POST['p_brand'],
									$_POST['p_list_price'],
									$_POST['p_cost_price'],
									$_POST['p_utilidad'],
									$_POST['p_extra_expensives'],
        							$_REQUEST['id']
        						));
        }
		

        if(isset($_POST['size'])) {

        	$statement = $pdo->prepare("DELETE FROM tbl_product_size WHERE p_id=?");
        	$statement->execute(array($_REQUEST['id']));

			foreach($_POST['size'] as $value) {
				$statement = $pdo->prepare("INSERT INTO tbl_product_size (size_id,p_id) VALUES (?,?)");
				$statement->execute(array($value,$_REQUEST['id']));
			}
		} else {
			$statement = $pdo->prepare("DELETE FROM tbl_product_size WHERE p_id=?");
        	$statement->execute(array($_REQUEST['id']));
		}

		if(isset($_POST['color'])) {
			
			$statement = $pdo->prepare("DELETE FROM tbl_product_color WHERE p_id=?");
        	$statement->execute(array($_REQUEST['id']));

			foreach($_POST['color'] as $value) {
				$statement = $pdo->prepare("INSERT INTO tbl_product_color (color_id,p_id) VALUES (?,?)");
				$statement->execute(array($value,$_REQUEST['id']));
			}
		} else {
			$statement = $pdo->prepare("DELETE FROM tbl_product_color WHERE p_id=?");
        	$statement->execute(array($_REQUEST['id']));
		}
	
    	
		
		/*
			-ALTA
			-BAJA
			-MODIFICACION
			-ELIMINACION
			-TRASPASO
			-LOGIN
			-LOGOUT
			-ABRIR CAJA
			-CERRAR CAJA
		*/
		
		//Guardo Log:
		/*$id_usuario = $_SESSION['user']['id'];
		$id_sucursal = $_SESSION['user']['sucursal'];
		$detalle = 'MODIFICACION PRODUCTO';
		$fecha = time();
		$idProducto = $_REQUEST['id'];
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
    	$success_message = '¡El producto fue actualizado correctamente!';
    }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Editar Producto</h1>
	</div>
	<div class="content-header-right">
		<a href="product.php" class="btn btn-primary btn-sm">Ver Todo</a>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$p_name = $row['p_name'];
	$p_old_price = $row['p_old_price'];
	$p_current_price = $row['p_current_price'];
	$p_qty = $row['p_qty'];
	$p_featured_photo = $row['p_featured_photo'];
	$p_description = $row['p_description'];
	$p_short_description = $row['p_short_description'];
	$p_feature = $row['p_feature'];
	$p_condition = $row['p_condition'];
	$p_return_policy = $row['p_return_policy'];
	$p_is_featured = $row['p_is_featured'];
	$p_is_active = $row['p_is_active'];
	$ecat_id = $row['ecat_id'];
	$p_sucursal_id = $row['p_sucursal_id'];
	$p_codebar = $row['p_codebar'];
	$p_code = $row['p_code'];
	$p_brand = $row['p_brand'];
	$p_list_price = $row['p_list_price'];
	$p_cost_price = $row['p_cost_price'];
	$p_utilidad = $row['p_utilidad'];
	$p_extra_expensives = $row['p_extra_expensives'];
}

$statement = $pdo->prepare("SELECT * 
                        FROM tbl_end_category t1
                        JOIN tbl_mid_category t2
                        ON t1.mcat_id = t2.mcat_id
                        JOIN tbl_top_category t3
                        ON t2.tcat_id = t3.tcat_id
                        WHERE t1.ecat_id=?");
$statement->execute(array($ecat_id));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$ecat_name = $row['ecat_name'];
    $mcat_id = $row['mcat_id'];
    $tcat_id = $row['tcat_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_product_size WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$size_id[] = $row['size_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_product_color WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$color_id[] = $row['color_id'];
}
?>


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
						<div id="containerAdmin" <?php if($role=="Publisher"){echo "hidden";} ?>>
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-tag"></i> Sección <span>*</span></label>
								<div class="col-sm-4">
									<select name="tcat_id" class="form-control select2 top-cat" >
			                            <option value="">Seleccione Sección</option>
			                            <?php
			                            $statement = $pdo->prepare("SELECT * FROM tbl_top_category ORDER BY tcat_name ASC");
			                            $statement->execute();
			                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);   
			                            foreach ($result as $row) {
			                                ?>
			                                <option value="<?php echo $row['tcat_id']; ?>" <?php if($row['tcat_id'] == $tcat_id){echo 'selected';} ?>><?php echo $row['tcat_name']; ?></option>
			                                <?php
			                            }
			                            ?>
			                        </select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"> Rubro <span>*</span></label>
								<div class="col-sm-4">
									<select name="mcat_id" class="form-control select2 mid-cat" >
			                            <option value="">Seleccione Rubro</option>
			                            <?php
			                            $statement = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE tcat_id = ? ORDER BY mcat_name ASC");
			                            $statement->execute(array($tcat_id));
			                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);   
			                            foreach ($result as $row) {
			                                ?>
			                                <option value="<?php echo $row['mcat_id']; ?>" <?php if($row['mcat_id'] == $mcat_id){echo 'selected';} ?>><?php echo $row['mcat_name']; ?></option>
			                                <?php
			                            }
			                            ?>
			                        </select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-tag"></i>Sub-Rubro <span>*</span></label>
								<div class="col-sm-4">
									<select name="ecat_id" class="form-control select2 end-cat" >
			                            <option value="">Seleccione Sub-Rubro</option>
			                            <?php
			                            $statement = $pdo->prepare("SELECT * FROM tbl_end_category WHERE mcat_id = ? ORDER BY ecat_name ASC");
			                            $statement->execute(array($mcat_id));
			                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);   
			                            foreach ($result as $row) {
			                                ?>
			                                <option value="<?php echo $row['ecat_id']; ?>" <?php if($row['ecat_id'] == $ecat_id){echo 'selected';} ?>><?php echo $row['ecat_name']; ?></option>
			                                <?php
			                            }
			                            ?>
			                        </select>
								</div>
							</div>

							<!-- <div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-building"></i> Sucursal <span>*</span></label>
								<div class="col-sm-4">
									<select name="p_sucursal_id" class="form-control select2">
										<option value="">Seleccione la Sucursal </option>
										<?php
										$statement = $pdo->prepare("SELECT * FROM tbl_sucursales ORDER BY s_name ASC;");
										$statement->execute();
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
										foreach ($result as $row) {
			                                ?>
			                                <option value="<?php echo $row['s_id']; ?>" <?php if($row['s_id'] == $p_sucursal_id){echo 'selected';} ?>><?php echo $row['s_name']; ?></option>
			                                <?php
			                            }
										?>
									</select>
								</div>
							</div> -->

							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-tag"></i> Nombre del producto <span>*</span></label>
								<div class="col-sm-4">
									<input type="text" name="p_name" class="form-control" value="<?php echo $p_name; ?>" >
								</div>
							</div>

							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-key"></i> Código <span>*</span></label>
								<div class="col-sm-4">
									<input type="text" name="p_code" class="form-control" value="<?php echo $p_code; ?>" >
								</div>
							</div>	
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-barcode"></i> Código de barra <span></span></label>
								<div class="col-sm-4">
									<input type="text" name="p_codebar" class="form-control" value="<?php echo $p_codebar; ?>" >
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-tags"></i> Marca <span></span></label>
								<div class="col-sm-4">
									<select name="p_brand" class="form-control select2" required  >
										<option value="">Seleccione Marca</option>
										<?php
										$statement = $pdo->prepare("SELECT * FROM tbl_marcas ORDER BY marca ASC");
										$statement->execute();
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
										foreach ($result as $row) {
											?>
											<option value="<?php echo $row['id']; ?>" <?php if($row['id']==$p_brand){ echo ' selected';} ?>><?php echo $row['marca']; ?></option>
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
									<input type="text" name="p_utilidad" class="form-control" value="<?php echo $p_utilidad; ?>" disabled>
								</div>
							</div>	

							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-usd"></i> Gastos Extras <br><span style="font-size:10px;font-weight:normal;">(ARS)</span></label>
								<div class="col-sm-4">
									<input type="text" name="p_extra_expensives" class="form-control" value="<?php echo $p_extra_expensives; ?>" >
								</div>
							</div>
								
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-usd"></i> Precio de Costo <span>*</span><br><span style="font-size:10px;font-weight:normal;">(ARS)</span></label>
								<div class="col-sm-4">
									<input type="text" name="p_cost_price" class="form-control" value="<?php echo $p_cost_price; ?>" >
								</div>
							</div>

							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-usd"></i> Precio de lista <span>*</span><br><span style="font-size:10px;font-weight:normal;">(ARS)</span></label>
								<div class="col-sm-4">
									<input type="text" name="p_list_price" class="form-control" value="<?php echo $p_list_price; ?>" >
								</div>
							</div>		
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-usd"></i> Precio anterior <br><span style="font-size:10px;font-weight:normal;">(ARS)</span></label>
								<div class="col-sm-4">
									<input type="text" name="p_old_price" class="form-control" value="0" >
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-usd"></i> Precio actual <span>*</span><br><span style="font-size:10px;font-weight:normal;" >(ARS)</span></label>
								<div class="col-sm-4">
									<input type="text" name="p_current_price" class="form-control" value="<?php echo $p_current_price; ?>" >
								</div>
							</div>	
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-list-ol"></i> Cantidad <span>*</span></label>
								<div class="col-sm-4">
									<input type="text" name="p_qty" class="form-control" value="<?php echo $p_qty; ?>" disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-tags"></i> Seleccione Talle / Tamaño / Medida</label>
								<div class="col-sm-4">
									<select name="size[]" class="form-control select2" multiple="multiple">
										<?php
										$is_select = '';
										$statement = $pdo->prepare("SELECT * FROM tbl_size ORDER BY size_id ASC");
										$statement->execute();
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);			
										foreach ($result as $row) {
											if(isset($size_id)) {
												if(in_array($row['size_id'],$size_id)) {
													$is_select = 'selected';
												} else {
													$is_select = '';
												}
											}
											?>
											<option value="<?php echo $row['size_id']; ?>" <?php echo $is_select; ?>><?php echo $row['size_name']; ?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-dashboard"></i> Seleccione Color</label>
							<div class="col-sm-4">
								<select name="color[]" class="form-control select2" multiple="multiple">
									<?php
									$is_select = '';
									$statement = $pdo->prepare("SELECT * FROM tbl_color ORDER BY color_id ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);			
									foreach ($result as $row) {
										if(isset($color_id)) {
											if(in_array($row['color_id'],$color_id)) {
												$is_select = 'selected';
											} else {
												$is_select = '';
											}
										}
										?>
										<option value="<?php echo $row['color_id']; ?>" <?php echo $is_select; ?>><?php echo $row['color_name']; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-camera"></i> Foto Destacada Existente </label>
							<div class="col-sm-4" style="padding-top:4px;">
								<img src="../assets/uploads/<?php echo $p_featured_photo; ?>" alt="" style="width:150px;">
								<input type="hidden" name="current_photo" value="<?php echo $p_featured_photo; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-camera"></i> Cambiar Foto Destacada </label>
							<div class="col-sm-4" style="padding-top:4px;">
								<input type="file" name="p_featured_photo">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-camera"></i> Otras Fotos </label>
							<div class="col-sm-4" style="padding-top:4px;">
								<table id="ProductTable" style="width:100%;">
			                        <tbody>
			                        	<?php
			                        	$statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
			                        	$statement->execute(array($_REQUEST['id']));
			                        	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			                        	foreach ($result as $row) {
			                        		?>
											<tr>
				                                <td>
				                                    <img src="../assets/uploads/product_photos/<?php echo $row['photo']; ?>" alt="" style="width:150px;margin-bottom:5px;">
				                                </td>
				                                <td style="width:28px;">
				                                	<a onclick="return confirmDelete();" href="product-other-photo-delete.php?id=<?php echo $row['pp_id']; ?>&id1=<?php echo $_REQUEST['id']; ?>" class="btn btn-danger btn-xs">X</a>
				                                </td>
				                            </tr>
			                        		<?php
			                        	}
			                        	?>
			                        </tbody>
			                    </table>
							</div>
							<div class="col-sm-2">
			                    <input type="button" id="btnAddNew" value="Add Item" style="margin-top: 5px;margin-bottom:10px;border:0;color: #fff;font-size: 14px;border-radius:3px;" class="btn btn-warning btn-xs">
			                </div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Descripción </label>
							<div class="col-sm-8">
								<textarea name="p_description" class="form-control" cols="30" rows="10" id="editor1"><?php echo $p_description; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Descripción Corta </label>
							<div class="col-sm-8">
								<textarea name="p_short_description" class="form-control" cols="30" rows="10" id="editor1"><?php echo $p_short_description; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Características </label>
							<div class="col-sm-8">
								<textarea name="p_feature" class="form-control" cols="30" rows="10" id="editor3"><?php echo $p_feature; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Condiciones </label>
							<div class="col-sm-8">
								<textarea name="p_condition" class="form-control" cols="30" rows="10" id="editor4"><?php echo $p_condition; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Políticas de Devolución </label>
							<div class="col-sm-8">
								<textarea name="p_return_policy" class="form-control" cols="30" rows="10" id="editor5"><?php echo $p_return_policy; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Producto Destacado</label>
							<div class="col-sm-8">
								<select name="p_is_featured" class="form-control" style="width:auto;">
									<option value="0" <?php if($p_is_featured == '0'){echo 'selected';} ?>>No</option>
									<option value="1" <?php if($p_is_featured == '1'){echo 'selected';} ?>>Sí</option>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">¿Publicar en Web?</label>
							<div class="col-sm-8">
								<select name="p_is_active" class="form-control" style="width:auto;">
									<option value="0" <?php if($p_is_active == '0'){echo 'selected';} ?>>No</option>
									<option value="1" <?php if($p_is_active == '1'){echo 'selected';} ?>>Sí</option>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Actualizar</button>
							</div>
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>