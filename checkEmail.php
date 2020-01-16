<?php 
	
	include 'inc/config.php';
	header('Content-type: text/json');
	
	$email = isset($_GET['email'])? $_GET['email'] : '';

	if($email!=''){
		$statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?;");	
		$statement->execute(array($email));
		$cant = $statement->rowCount();
		if($cant>0){
			echo '{"existe": 1}';
		}else{
			echo '{"existe": 0}';
		}
	}else{
		echo '{"error" : "no ingresó email"}';
	}
	//475093
	//754169


 ?>
