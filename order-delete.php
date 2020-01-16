<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	} else {
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
		foreach ($result as $row) {
			$payment_id = $row['payment_id'];
			$payment_status = $row['payment_status'];
			$shipping_status = $row['shipping_status'];
		}
	}
}
?>

<?php
	
	if( ($payment_status == 'Completed') && ($shipping_status == 'Completed') ):
		// No return to stock
	else:
		// Return the stock
		$statement = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
		$statement->execute(array($payment_id));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
		foreach ($result as $row) {
			$statement1 = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
			$statement1->execute(array($row['product_id']));
			$result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);							
			foreach ($result1 as $row1) {
				$p_qty = $row1['p_qty'];
			}
			$final = $p_qty + $row['quantity'];
			$statement1 = $pdo->prepare("UPDATE tbl_product SET p_qty=? WHERE p_id=?");
			$statement1->execute(array($final,$row['product_id']));
		}	

		//return stock to sucursal
		$statPedidos = $pdo->prepare("SELECT * FROM tbl_pedidos WHERE id_payment=?");
		$statPedidos->execute(array($payment_id));
		$resultPedidos = $statPedidos->fetchAll(PDO::FETCH_ASSOC);
		foreach($resultPedidos as $pedido){
			
			$pedido_id_sucursal = $pedido['id_sucursal'];
			$pedido_stock = $pedido['stock'];
			$pedido_id_producto = $pedido['id_producto'];

			$statStock = $pdo->prepare("SELECT * FROM tbl_stock WHERE sk_id_producto = ? AND sk_id_sucursal = ?");
			$statStock->execute(array($pedido_id_producto, $pedido_id_sucursal));
			$resultStock = $statStock->fetchAll(PDO::FETCH_ASSOC);

			foreach($resultStock as $stk){
				$newStock = $stk['sk_stock'] + $pedido_stock;
				//aqui continuar con el update
				//UPDATE `tbl_stock` SET `sk_id`=[value-1],`sk_id_producto`=[value-2],`sk_id_sucursal`=[value-3],`sk_stock`=[value-4] WHERE 1
				$statUpdate = $pdo->prepare("UPDATE tbl_stock SET sk_stock = ? WHERE sk_id_producto = ? AND sk_id_sucursal = ?");
				$statUpdate->execute(array($newStock, $pedido_id_producto, $pedido_id_sucursal));

			}

		}

	endif;	

	// Delete from tbl_order
	$statement = $pdo->prepare("DELETE FROM tbl_order WHERE payment_id=?");
	$statement->execute(array($payment_id));

	// Delete from tbl_payment
	$statement = $pdo->prepare("DELETE FROM tbl_payment WHERE id=?");
	$statement->execute(array($_REQUEST['id']));

	header('location: order.php');
?>