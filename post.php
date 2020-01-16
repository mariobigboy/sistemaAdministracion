<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Ver Posteos</h1>
	</div>
	<div class="content-header-right">
		<a href="post-add.php" class="btn btn-primary btn-sm">Agregar Nuevo</a>
	</div>
</section>


<section class="content">

	<div class="row">
		<div class="col-md-12">


			<div class="box box-info">

				<div class="box-body table-responsive">
					<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>Imagen</th>
								<th>Título</th>
								<th>Categoría</th>
								<th>Acción</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$statement = $pdo->prepare("SELECT

														t1.post_id,
														t1.post_title,
														t1.post_content,
														t1.photo,
														t1.category_id,

														t2.category_id,
														t2.category_name

							                           	FROM tbl_post t1
							                           	JOIN tbl_category t2
							                           	ON t1.category_id = t2.category_id

							                           	ORDER BY t1.post_id DESC
							                           	");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
							foreach ($result as $row) {
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td>
										<?php
										if($row['photo'] == '')
										{
											echo '<img src="../assets/uploads/no-photo1.jpg" alt="" style="width:180px;">';
										}
										else
										{
											echo '<img src="../assets/uploads/'.$row['photo'].'" alt="'.$row['post_title'].'" style="width:180px;">';
										}
										?>
									</td>
									<td><?php echo $row['post_title']; ?></td>
									<td>
										<?php echo $row['category_name']; ?>
									</td>
									<td>										
										<a href="post-edit.php?id=<?php echo $row['post_id']; ?>" class="btn btn-primary btn-xs">Editar</a>
										<a href="#" class="btn btn-danger btn-xs" data-href="post-delete.php?id=<?php echo $row['post_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Eliminar</a>  
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


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirmación</h4>
            </div>
            <div class="modal-body">
                <p>Está seguro que desea eliminar éste item?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-danger btn-ok">Eliminar</a>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>