<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Usuarios</h1>
	</div>
	<div class="content-header-right">
		<a href="usuarios-add.php" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Nuevo Usuario</a>
		<!--<a href="libs/codebar/index.php" class="btn btn-warning btn-sm" target="_blank"> <i class="fa fa-barcode"></i> Imprimir codebars</a>-->
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<!-- code -->
					<table id="tablaUsuarios" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>N°</th>
								<th>ID</th>
								<th width="200">Usuario</th>
								<th>email</th>
								<th>Sucursal</th>
								<th>Rol</th>								
								<th>¿Activo?</th>
								<!--<th>Categoría</th>-->
								<th width="80">Acción</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							
							$statement = $pdo->prepare("SELECT * FROM tbl_user");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							foreach ($result as $row) {
								$i++;
								if ($_SESSION['user']['role']=='Super Admin') {
									//verifico si es el super admin
								
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $row['id']; ?></td>
									<td><?php echo $row['full_name']; ?></td>
									<!--<td><?php #echo $row['p_old_price']; ?></td>-->
									<td><?php echo $row['email']; ?></td>
									<td><?php
										 	if ($row['sucursal'] != '99' && $row['sucursal'] != '98' && $row['sucursal'] !='97') {
										 		$statement1 = $pdo->prepare("SELECT s_name FROM tbl_sucursales WHERE s_id = ".$row['sucursal']);
												$statement1->execute();
												$resulta = $statement1->fetchAll(PDO::FETCH_ASSOC);
												foreach ($resulta as $fila) {
													echo $fila['s_name'];
												}
										 	}else{
										 		echo "Todas";
										 	}
										 ?>
											
									</td>
									<td><?php echo $row['role']; ?></td>
									<!--<td>
										<?php #if($row['p_is_featured'] == 1) {echo 'Sí';} else {echo 'No';} ?>
									</td>-->
									<td>
										<?php if($row['status'] == 'Active') {echo 'Sí';} else {echo 'No';} ?>
									</td>
									<!--<td><?php #echo $row['tcat_name']; ?><br><?php #echo $row['mcat_name']; ?><br><?php #echo $row['ecat_name']; ?></td>-->
									<td>										
										<?php
                                        if($row['role']!= 'Super Admin'){ ?>
                                        <a href="usuarios-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs">Editar</a>
										<a href="#" class="btn btn-danger btn-xs" data-href="usuarios-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete">Eliminar</a> 
                                            
                                    <?php    }
                                    ?>
                                        
									</td>
								</tr>
							<?php }else{ 
										if ($row['role'] != "Super Admin") {
										
								?>

										<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $row['id']; ?></td>
									<td><?php echo $row['full_name']; ?></td>
									<!--<td><?php #echo $row['p_old_price']; ?></td>-->
									<td><?php echo $row['email']; ?></td>
									<td><?php
										 	if ($row['sucursal'] != '99' && $row['sucursal'] != '98' && $row['sucursal'] !='97') {
										 		$statement1 = $pdo->prepare("SELECT s_name FROM tbl_sucursales WHERE s_id = ".$row['sucursal']);
												$statement1->execute();
												$resulta = $statement1->fetchAll(PDO::FETCH_ASSOC);
												foreach ($resulta as $fila) {
													echo $fila['s_name'];
												}
										 	}else{
										 		echo "Todas";
										 	}
										 ?>
											
									</td>
									<td><?php echo $row['role']; ?></td>
									<!--<td>
										<?php #if($row['p_is_featured'] == 1) {echo 'Sí';} else {echo 'No';} ?>
									</td>-->
									<td>
										<?php if($row['status'] == 'Active') {echo 'Sí';} else {echo 'No';} ?>
									</td>
									<!--<td><?php #echo $row['tcat_name']; ?><br><?php #echo $row['mcat_name']; ?><br><?php #echo $row['ecat_name']; ?></td>-->
									<td>										
										<a href="usuarios-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs">Editar</a>
										<a href="#" class="btn btn-danger btn-xs" data-href="usuarios-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete">Eliminar</a>  
									</td>
								</tr>

								<?php
									}
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


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirmación de eliminación</h4>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de eliminar este Usuario?</p>
                <p style="color:red;">¡Ten cuidado! Este usuario se eliminará de la base de datos y no podrá ser recuperado.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-danger btn-ok">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>