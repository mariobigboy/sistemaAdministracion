<?php 
include('inc/config.php');
$id = isset($_POST['id']) ? $_POST['id'] : "";
//echo $id;

if ($id != "") {

		//tbl: cuentasProovedores:
		$statement = $pdo->prepare("SELECT *, DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(fecha),'+00:00','-03:00'), '%d/%m/%Y %H:%i') fecha_format FROM `cuentasProveedores` WHERE id = ?;");


		$statement->execute(array($id));
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		$arrOut = array();
		foreach($results as $row){
			array_push($arrOut, $row);
			//tbl: detallesProovedores:
			$arrayDetallesProveedores = array();
			$statementDetalles = $pdo->prepare("SELECT *, DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(fecha),'+00:00','-03:00'), '%d/%m/%Y %H:%i') fecha_format FROM `detalleProveedores` WHERE idCuenta = ?;");

			$statementDetalles->execute(array($row['id']));
			$resultsDetalles = $statementDetalles->fetchAll(PDO::FETCH_ASSOC);
			foreach($resultsDetalles as $rowDetalles){
				array_push($arrayDetallesProveedores, $rowDetalles);
			}
			array_push($arrOut, $arrayDetallesProveedores);
		}

		
		
		
		

		echo json_encode($arrOut);
	

	
}
	header('Content-type: text/json');

 ?>