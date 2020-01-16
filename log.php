<?php require_once('header.php'); ?>

<section class="content-header">
	<h1>Logs</h1>
</section>

<?php
	//$statement = $pdo->prepare("SELECT * FROM tbl_top_category");
	//$statement->execute();
	//$total_top_category = $statement->rowCount();
?>

<section class="content">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="box box-info">
				<div class="box-body">
					<div class="box-body table-responsive">
						<!-- code -->
						<table id="tablaLogs" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th width="30">N°</th>
									<th>Detalle</th>
									<th>IP</th>
									<th width="150">Fecha</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i=0;
								
								$statement = $pdo->prepare("SELECT * FROM `tbl_logs` ORDER BY fecha DESC;");
								$statement->execute();
								$result = $statement->fetchAll(PDO::FETCH_ASSOC);
								foreach ($result as $row) {
									$i++;
									if ($row['id_usuario']!="1" && $row['id_usuario']!="17" && $row['id_usuario']!="29") {
										# code...
									
									?>
									<tr>
										<td><?php echo $i; ?></td>
										<td><?php echo $row['detalle']; ?></td>
										<td><?php echo $row['ip']; ?></td>
										<td><?php echo date('d/m/Y H:i:s',$row['fecha']); ?></td>
										
									</tr>
									<?php
									}
								}
								?>							
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		
		
	</div><!-- .row -->
</section>

<?php require_once('footer.php'); ?>