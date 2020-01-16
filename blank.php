<?php require_once('header.php'); ?>

<section class="content-header">
	<h1>Blank title</h1>
</section>

<?php
	//$statement = $pdo->prepare("SELECT * FROM tbl_top_category");
	//$statement->execute();
	//$total_top_category = $statement->rowCount();
?>

<section class="content">
	<div class="row">
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-aqua"><i class="fa fa-hand-o-right"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Blank test</span>
					<span class="info-box-number"><?php #echo $total_top_category; ?></span>
				</div>
			</div>
		</div>
		
		
	</div>
</section>

<?php require_once('footer.php'); ?>