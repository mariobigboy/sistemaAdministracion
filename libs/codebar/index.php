<?php 
	ob_start();
	session_start();
	include("../../inc/config.php");
	include("../../inc/functions.php");
	include("../../inc/CSRF_Protect.php");
	//$csrf = new CSRF_Protect();
	$error_message = '';
	$success_message = '';
	$error_message1 = '';
	$success_message1 = '';

	// Check if the user is logged in or not
	if(!isset($_SESSION['user'])) {
		header('location: ../../login.php');
		exit;
	}
		
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Codebar</title>
</head>
<body>
	<div id="barcodes">
		<?php 

			$id = isset($_GET['id']) ? $_GET['id'] : '';
			$cant = isset($_GET['cant'])? $_GET['cant'] : 1;
			if($id!=''){
				//$statement = $pdo->prepare("SELECT `p_code`, `p_name` FROM `tbl_product` WHERE LENGTH(`p_code`) > 0 AND p_is_active = 1 AND p_id = ?;");
				$statement = $pdo->prepare("SELECT `p_code`, `p_name` FROM `tbl_product` WHERE LENGTH(`p_code`) > 0 AND p_id = ?;");
				$statement->execute(array($id));
			}else{
				//$statement = $pdo->prepare("SELECT `p_code`, `p_name` FROM `tbl_product` WHERE LENGTH(`p_code`) > 0 AND p_is_active = 1;");
				$statement = $pdo->prepare("SELECT `p_code`, `p_name` FROM `tbl_product` WHERE LENGTH(`p_code`) > 0;");
				$statement->execute();
			}
			$results = $statement->fetchAll(PDO::FETCH_ASSOC);
			$i = 0;

			foreach($results as $row){
				for ($j=0; $j < $cant; $j++) { 
					# code...
					echo '<ul style="display: inline-block; list-style: none;text-align: center;line-height:11px;">';
					$linea = '<li style="font-size:12px;">'.$row['p_name'].'</li><li><svg id="code'.$i.'" class="barcode" data-text="'.(($row['p_code']=='')? 'sin texto' : $row['p_code'] ).'"></svg></li>';
					echo $linea;
					echo '</ul>';
					$i++;

				}
			}
		?>

			<!--<svg id="code#" class="barcode" data-text=""></svg>-->
	</div>
	

	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script src="JsBarcode.code128.min.js"></script>
	<script>
		$(document).ready(function(){

			$('.barcode').each(function(){
				var $este = $(this);
				console.log($este.data('text'));
				//vertical, sin márgenes, escala 90
				//defecto: {width: 1.5, height:40}
				JsBarcode('#'+$este.attr('id'), $este.data('text'), {
					width: 1.5,
					text: $este.data('text') + ' Home Design',
					height: 40,
					fontSize: 12,
				});
			});
		});
	</script>
</body>
</html>