<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Ver Categorías Blog</h1>
	</div>
	<div class="content-header-right">
		<a href="category-add.php" class="btn btn-primary btn-sm">Agregar Nuevo</a>
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
			        <th>SL</th>
			        <th>Nombre Categoría</th>
			        <th>Categoría Ficha</th>
			        <th>Acción</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * FROM tbl_category ORDER BY category_id ASC");
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
            	foreach ($result as $row) {
            		$i++;
            		?>
					<tr>
	                    <td><?php echo $i; ?></td>
	                    <td><?php echo $row['category_name']; ?></td>
	                    <td><?php echo $row['category_slug']; ?></td>
	                    <td>
	                        <a href="category-edit.php?id=<?php echo $row['category_id']; ?>" class="btn btn-primary btn-xs">Edit</a>
	                        <a href="#" class="btn btn-danger btn-xs" data-href="category-delete.php?id=<?php echo $row['category_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Eliminar</a>
	                    </td>
	                </tr>
            		<?php
            	}
            	?>
            </tbody>
          </table>
        </div>
      </div>
  

</section>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirmar Eliminación</h4>
            </div>
            <div class="modal-body">
                Está seguro que desea eliminar este item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-danger btn-ok">Eliminar</a>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>