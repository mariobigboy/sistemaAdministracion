<?php 
include('inc/config.php');
$idFactura = isset($_POST['id']) ? $_POST['id'] : "";
//echo $idFactura;

if ($idFactura != "") {

	
		$statement = $pdo->prepare("SELECT *, DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(fecha),'+00:00','-03:00'), '%d/%m/%Y %H:%i') fecha_format FROM `cuenta_corriente` WHERE id_factura = ?;");
		$statement->execute(array($idFactura));
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		$arrOut = array();
		foreach($results as $row){
			array_push($arrOut, $row);
		}

		echo json_encode($arrOut);
	

	
}
	header('Content-type: text/json');

 ?>