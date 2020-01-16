<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Ver Ratings</h1>
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
			        <th>Producto</th>
			        <th>Cliente</th>
			        <th>Rating</th>
			        <th>Commentario</th>
			        <th>Acción</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * 
            						FROM tbl_rating t1
            						JOIN tbl_product t2
            						ON t1.p_id = t2.p_id
            						JOIN tbl_customer t3
            						ON t1.cust_id = t3.cust_id
            						ORDER BY t1.rt_id DESC
            						");
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
            	foreach ($result as $row) {
            		$i++;
            		?>
					<tr>
	                    <td><?php echo $i; ?></td>
	                    <td>
	                    	<?php echo $row['p_name']; ?>
	                    </td>
	                    <td>
	                    	Nombre: <?php echo $row['cust_name']; ?><br>
	                    	Email: <?php echo $row['cust_email']; ?>
	                    </td>
	                    <td><?php echo $row['comment']; ?></td>
	                    <td><?php echo $row['rating']; ?></td>
	                    <td>
	                        <a href="#" class="btn btn-danger btn-xs" data-href="rating-delete.php?id=<?php echo $row['rt_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Eliminar</a>
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
                <h4 class="modal-title" id="myModalLabel">Confirmación</h4>
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