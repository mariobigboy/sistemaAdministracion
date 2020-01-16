<?php 
header('Content-type: text/json');
include('inc/config.php');
$acc = isset($_POST['acc'])? $_POST['acc'] : '';

switch ($acc) {
	case 'eliminarFactura':

	$codFactura = isset($_POST['codFactura'])? $_POST['codFactura'] : -1;
	if($codFactura != -1){

		$statement = $pdo->prepare("SELECT * FROM factura WHERE num_factura= '$codFactura'");
		$statement->execute();
				//obtengo datos factura
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($results as $row){
			$sucursal= $row['sucursal'];
			$cliente = $row['id_cliente'];

		}
				// hasta aca pasa. vemos los productos que hay que devolverlos al stock
		$statement = $pdo->prepare("SELECT * FROM detalle WHERE id_factura = '$codFactura'");
		$statement->execute();
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($results as $row){

			if ($row['id_producto']!=-1) {
				$producto= $row['id_producto'];
				$cantidad = $row['cantidad'];


				$statement = $pdo->prepare("UPDATE tbl_product SET p_qty = p_qty + ? WHERE p_id= ?");
				$statement->execute(array(
					$cantidad,
					$producto
				));

				$statement = $pdo->prepare("UPDATE tbl_stock SET sk_stock = sk_stock + ? WHERE sk_id_producto= ? AND sk_id_sucursal=?");
				$statement->execute(array(
					$cantidad,
					$producto,
					$sucursal));
			}
		}
				//elimino detalles
		$statement = $pdo->prepare("DELETE FROM detalle WHERE id_factura= '$codFactura'");
		$statement->execute();
				//eliminamos los pago de la factura
		$statement = $pdo->prepare("SELECT * FROM pagos WHERE id_factura = '$codFactura'");
		$statement->execute();
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($results as $row){
			if ($row['metodo1']==4 || $row['metodo2']==4 || $row['metodo3']==4) {
				$statement = $pdo->prepare("DELETE FROM cuenta_corriente WHERE id_factura = '$codFactura'");
				$statement->execute();
			}
			$idPago = $row['id'];
			$statement = $pdo->prepare("DELETE FROM pagos WHERE id = '$idPago'");
			$statement->execute();
		}
				//elimino la factura
		$statement = $pdo->prepare("DELETE FROM factura WHERE num_factura= '$codFactura'");
		$statement->execute();

		echo '{"eliminarFactura": 1}';
	}else{
		echo '{"eliminarFactura": 0}';
	}


	break;

	case 'eliminarPresupuesto':

	$codFactura = isset($_POST['codFactura'])? $_POST['codFactura'] : -1;
	if($codFactura != -1){

				//elimino detalles
		$statement = $pdo->prepare("DELETE FROM detalle WHERE id_presupuesto= '$codFactura'");
		$statement->execute();

				//elimino el presupuesto
		$statement = $pdo->prepare("DELETE FROM presupuesto WHERE id_presupuesto= '$codFactura'");
		$statement->execute();

		echo '{"eliminarFactura": 1}';
	}else{
		echo '{"eliminarFactura": 0}';
	}


	break;
	case 'guardarRecibo':

	$usuario = isset($_POST['usuario'])? $_POST['usuario'] : -1;
	$sucursal = isset($_POST['sucursal'])? $_POST['sucursal'] : -1;
	$cliente = isset($_POST['cliente'])? $_POST['cliente'] : -1;
	$concepto = isset($_POST['concepto'])? $_POST['concepto'] : -1;
	$monto = isset($_POST['monto'])? $_POST['monto'] : -1;
	if($cliente != -1){

				//elimino detalles
		$statement = $pdo->prepare("INSERT INTO recibos (cliente, fecha, concepto, usuario, monto, sucursal) VALUES ('$cliente', UNIX_TIMESTAMP(),'$concepto','$usuario','$monto','$sucursal')");
		$statement->execute();


		echo '{"success": 1}';
	}else{
		echo '{"success": 0}';
	}


	break;
	case 'addNotaCredito':


		try {
		    $datos = isset($_POST['nota_credito'])? json_decode($_POST['nota_credito'],true) : [];
			$usuario = isset($_POST['usuario'])? $_POST['usuario'] : -1;
			$sucursal = isset($_POST['sucursal'])? $_POST['sucursal'] : -1;
			$factura = isset($_POST['factura'])? $_POST['factura'] : -1;
			$obs = isset($_POST['obs'])? $_POST['obs'] : "";
			$b=false;
			$tot = 0;

			for($i=0; $i<sizeof($datos); $i++){
				$cant = $datos[$i]['cantidad'];
				$precio = $datos[$i]['precio'];
				$tot += $precio * $cant;
			}	

			$formato = number_format($tot,2);

			$statement = $pdo->prepare("INSERT INTO notaCredito (idFactura, fecha, sucursal, usuarioSucursal, obs, monto) VALUES ('$factura', UNIX_TIMESTAMP(), '$sucursal', '$usuario', '$obs', '$formato')");
			$statement->execute();
			$idNC = $pdo->lastInsertId();

			$statement4 = $pdo->prepare("UPDATE factura SET estado = 1, NC = '$idNC' WHERE num_factura= '$factura'");
			$statement4->execute();
			
			for ($i=0; $i < sizeof($datos); $i++) { 
				$idProducto = $datos[$i]['idProducto'];
				$cant = $datos[$i]['cantidad'];
				$nombre = $datos[$i]['nombre'];
				$precio = $datos[$i]['precio'];
				//$tot += $precio * $cant;
			
				
				if ($idProducto != -1) {
								//Devolver al stock producto
					$statement1 = $pdo->prepare("UPDATE tbl_product SET p_qty = p_qty + '$cant' WHERE p_id= '$idProducto'");
					$statement1->execute();
								//devuelvo a la sucursal

					$statement2 = $pdo->prepare("UPDATE tbl_stock SET sk_stock = sk_stock + '$cant' WHERE sk_id_producto= '$idProducto' AND sk_id_sucursal = '$sucursal'");
					$statement2->execute();

				}

				$statement3 = $pdo->prepare("INSERT INTO detalle (id_producto, nombre, cantidad, precio, nota_credito) VALUES ('$idProducto', '$nombre', '$cant', '$precio', '$idNC')");
				$statement3->execute();
			}

			

			
			echo '{"success": 1}';
		} catch (PDOException $e) {
		    //echo 'Error de conexión: ' . $e->getMessage();
		    echo '{"success": 0}';
		    exit;
		}



	break;



	default:
	echo '{"error": "ninguna seleccion"}';
	break;
}


?>

