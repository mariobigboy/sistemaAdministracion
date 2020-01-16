<?php 
include('inc/config.php');
$obs = isset($_POST['obs']) ? addslashes($_POST['obs']) : "";
$factura = isset($_POST['factura']) ? addslashes($_POST['factura']) : "";
$datos = isset($_POST['datos']) ? addslashes($_POST['datos']) : "";
$usuario = isset($_POST['usuario']) ? addslashes($_POST['usuario']) : "";
$sucursal = isset($_POST['sucursal']) ? addslashes($_POST['sucursal']) : "";

$statement = $pdo->prepare("SELECT * FROM `carpinteria` WHERE idFactura = '$factura';");
$statement->execute();
$filas = $statement->rowCount();
//echo $filas;

if ($filas > 0) {
	
	echo '0';

}else{

	$statement = $pdo->prepare("INSERT INTO `carpinteria` (idFactura, fecha, sucursal, usuarioSucursal, obs, detalles) VALUES (?,UNIX_TIMESTAMP(),?,?,?,?);");
	$statement->execute(array(
		$factura,
		$sucursal,
		$usuario,
		$obs,
		$datos
	));
	$idPedido = $pdo->lastInsertId();
	$ruta="img/pedidos/";

	$statement = $pdo->prepare("INSERT INTO `estadoPedido` (idCarpinteria, estado, fecha, usuario) VALUES (?,0,UNIX_TIMESTAMP(),?);");
	$statement->execute(array(
		$idPedido,
		$usuario,
	));

	if ($_FILES['foto1']['error']==0) {
		$archivo=$_FILES['foto1']['tmp_name'];
		$nombreArchivo.= "img".date("dHis").".".pathinfo($_FILES['foto1']['name'], PATHINFO_EXTENSION);
		$rutauno=$ruta.$nombreArchivo;
		move_uploaded_file($archivo,$ruta.$nombreArchivo);
		$sql = "INSERT INTO `imgPedidos` (`idCarpinteria`, `ruta`, `fecha`) VALUES (?,?,?, UNIX_TIMESTAMP())";

		$statement = $pdo->prepare("INSERT INTO `imgPedidos` (`idCarpinteria`, `ruta`, `fecha`) VALUES (?,?,UNIX_TIMESTAMP())");
		$statement->execute(array(
			$idPedido,
			$rutauno
		));


	}

	if ($_FILES['foto2']['error']==0) {
		$archivo2=$_FILES['foto2']['tmp_name'];
		$nombreArchivo2= "img2".date("dHis").".".pathinfo($_FILES['foto2']['name'], PATHINFO_EXTENSION);
		$rutados = $ruta.$nombreArchivo2;
		move_uploaded_file($archivo2,$ruta.$nombreArchivo2);
		$sql = "INSERT INTO `imgPedidos` (`idCarpinteria`, `ruta`, `fecha`) VALUES (?,?,?, UNIX_TIMESTAMP())";

		$statement = $pdo->prepare("INSERT INTO `imgPedidos` (`idCarpinteria`, `ruta`, `fecha`) VALUES (?,?,UNIX_TIMESTAMP())");
		$statement->execute(array(
			$idPedido,
			$rutados
		));


	}
		echo '1';
}
//header('Content-type: text/json');

?>
