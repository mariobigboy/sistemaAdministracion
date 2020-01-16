<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Categorías superiores</h1>
	</div>
	<div class="content-header-right">
		<a href="top-category-add.php" class="btn btn-primary btn-sm">Añadir Nueva</a>
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
			        <th>N°</th>
			        <th>Nombre de categoría superior</th>
                    <th>¿Mostrar en Menu?</th>
			        <th>Acción</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * FROM tbl_top_category ORDER BY tcat_id DESC");
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
            	foreach ($result as $row) {
            		$i++;
            		?>
					<tr>
	                    <td><?php echo $i; ?></td>
	                    <td><?php echo $row['tcat_name']; ?></td>
                        <td>
                            <?php 
                                if($row['show_on_menu'] == 1) {
                                    echo 'Si';
                                } else {
                                    echo 'No';
                                }
                            ?>
                        </td>
	                    <td>
	                        <a href="top-category-edit.php?id=<?php echo $row['tcat_id']; ?>" class="btn btn-primary btn-xs">Editar</a>
                            <?php 
                                if ($_SESSION['user']['role']=="Super Admin") {
                                
                             ?>
	                        <a href="#" class="btn btn-danger btn-xs" data-href="top-category-delete.php?id=<?php echo $row['tcat_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Eliminar</a>
                            <?php   
                                } ?>
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
                <h4 class="modal-title" id="myModalLabel">Confirmación de eliminación</h4>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de eliminar este item?</p>
                <p style="color:red;">¡Ten cuidado! Todos los productos, las categorías de nivel medio y las categorías de nivel final de esta categoría de nivel superior se eliminarán de todas las tablas, como la tabla de pedidos, la tabla de pagos, la tabla de tamaños, la tabla de colores, la tabla de clasificación, etc.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-danger btn-ok">Eliminar</a>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>