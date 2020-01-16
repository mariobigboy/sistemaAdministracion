<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Sucursales</h1>
	</div>
	<div class="content-header-right">
		<a href="sucursales-add.php" class="btn btn-primary btn-sm">Nueva Sucursal</a>
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
                    <th>Nombre</th>
			        <th>Dirección</th>
			        <th>Acción</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * FROM tbl_sucursales ORDER BY s_address ASC;");
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
            	foreach ($result as $row) {
            		$i++;
            		?>
					<tr>
	                    <td><?php echo $i; ?></td>
                        <td><?php echo $row['s_name']; ?></td>
	                    <td><?php echo $row['s_address']; ?></td>
	                    <td>
	                        <a href="sucursales-edit.php?id=<?php echo $row['s_id']; ?>" class="btn btn-primary btn-xs">Editar</a>
	                        <a href="#" class="btn btn-danger btn-xs" data-href="sucursales-delete.php?id=<?php echo $row['s_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Eliminar</a>
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
                ¿Está seguro de borrar esta sucursal?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-danger btn-ok">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>