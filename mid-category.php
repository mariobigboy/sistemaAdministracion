<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Rubros</h1> <!-- View Mid Level Categories -->
	</div>
	<div class="content-header-right">
		<a href="mid-category-add.php" class="btn btn-primary btn-sm">Agregar Nuevo</a>
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
			        <th>Rubro</th>
                    <th>Sección</th>
			        <th>Acción</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * 
                                    FROM tbl_mid_category t1
                                    JOIN tbl_top_category t2
                                    ON t1.tcat_id = t2.tcat_id
                                    ORDER BY t1.mcat_id DESC");
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
            	foreach ($result as $row) {
            		$i++;
            		?>
					<tr>
	                    <td><?php echo $i; ?></td>
	                    <td><?php echo $row['mcat_name']; ?></td>
                        <td><?php echo $row['tcat_name']; ?></td>
	                    <td>
	                        <a href="mid-category-edit.php?id=<?php echo $row['mcat_id']; ?>" class="btn btn-primary btn-xs">Editar</a>
                            <?php 
                                if ($_SESSION['user']['role']=="Super Admin") {
                                
                             ?>
	                        <a href="#" class="btn btn-danger btn-xs" data-href="mid-category-delete.php?id=<?php echo $row['mcat_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Eliminar</a>
                        <?php } ?>
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
                <p>Está seguro que quiere borrar este Item?</p>
                <p style="color:red;">Cuidado!!! Todos los productos en éste Rubro y los niveles inferiores a éste serán eliminados, así como también las tablas de pedido, pagos, tamaños, color, etc. </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-danger btn-ok">Eliminar</a>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>