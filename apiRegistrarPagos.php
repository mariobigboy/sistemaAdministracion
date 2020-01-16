<?php 
include('inc/config.php');
$idFactura = isset($_POST['id']) ? $_POST['id'] : "";
$pago = isset($_POST['pago']) ? $_POST['pago'] : "";
$idCliente = isset($_POST['idCliente']) ? $_POST['idCliente'] : "";
$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : "";
//echo $idFactura;

if ($idFactura != "") {

		$fecha= time();
		$statement = $pdo->prepare("INSERT INTO `cuenta_corriente` (id_cliente, id_factura, pago, usuario, fecha) VALUES (?,?,?,?,?);");
		$statement->execute(array(
			$idCliente,
			$idFactura,
			$pago,
			$usuario,
			$fecha
		));
		// $results = $statement->fetchAll(PDO::FETCH_ASSOC);
		// $arrOut = array();
		// foreach($results as $row){
		// 	array_push($arrOut, $row);
		// }

		// echo json_encode($arrOut);

		$statement = $pdo->prepare("SELECT SUM(pago) sumaPagos, SUM(deuda) sumaDeuda FROM `cuenta_corriente` WHERE id_factura = ?;");
		$statement->execute(array($idFactura));
		$results1 = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		if ($results1[0]['sumaPagos'] == $results1[0]['sumaDeuda']) {
			$statement = $pdo->prepare("UPDATE `cuenta_corriente` SET tipo = 1 WHERE id_factura = ?;");
			$statement->execute(array($idFactura));
		}
		//print_r($results1);


		// echo json_encode($arrOut);

		echo "{success: true}";
	

	
}
	//header('Content-type: text/json');
	header('Location: clientes-cuenta.php?id='.idCliente);

 ?>