<?php 
include('inc/config.php');
$obs = isset($_POST['obs']) ? addslashes($_POST['obs']) : "";
$factura = isset($_POST['factura']) ? addslashes($_POST['factura']) : "";
$datos = isset($_POST['datos']) ? addslashes($_POST['datos']) : "";
$usuario = isset($_POST['usuario']) ? addslashes($_POST['usuario']) : "";
$sucursal = isset($_POST['sucursal']) ? addslashes($_POST['sucursal']) : "";

$statement = $pdo->prepare("SELECT * FROM `pedido` WHERE idFactura = '$factura';");
$statement->execute();
$filas = $statement->rowCount();
//echo $filas;

if ($filas > 0) {
	
	echo '0';

}else{

	$statement = $pdo->prepare("INSERT INTO `pedido` (idFactura, fecha, sucursal, usuarioSucursal, obs, detalles) VALUES (?,UNIX_TIMESTAMP(),?,?,?,?);");
	$statement->execute(array(
		$factura,
		$sucursal,
		$usuario,
		$obs,
		$datos
	));
	$idPedido = $pdo->lastInsertId();
	$ruta="img/pedidos/";

	$statement = $pdo->prepare("INSERT INTO `estadoPedido` (idPedido, estado, fecha, usuario) VALUES (?,0,UNIX_TIMESTAMP(),?);");
	$statement->execute(array(
		$idPedido,
		$usuario,
	));

	if ($_FILES['foto1']['error']==0) {
		$archivo=$_FILES['foto1']['tmp_name'];
		$nombreArchivo.= "img".date("dHis").".".pathinfo($_FILES['foto1']['name'], PATHINFO_EXTENSION);
		$rutauno=$ruta.$nombreArchivo;
		move_uploaded_file($archivo,$ruta.$nombreArchivo);
		$sql = "INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,?, UNIX_TIMESTAMP())";

		$statement = $pdo->prepare("INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,UNIX_TIMESTAMP())");
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
		$sql = "INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,?, UNIX_TIMESTAMP())";

		$statement = $pdo->prepare("INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,UNIX_TIMESTAMP())");
		$statement->execute(array(
			$idPedido,
			$rutados
		));


	}

	if ($_FILES['foto3']['error']==0) {
		$archivo2=$_FILES['foto3']['tmp_name'];
		$nombreArchivo2= "img3".date("dHis").".".pathinfo($_FILES['foto3']['name'], PATHINFO_EXTENSION);
		$rutados = $ruta.$nombreArchivo2;
		move_uploaded_file($archivo2,$ruta.$nombreArchivo2);
		$sql = "INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,?, UNIX_TIMESTAMP())";

		$statement = $pdo->prepare("INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,UNIX_TIMESTAMP())");
		$statement->execute(array(
			$idPedido,
			$rutados
		));


	}

	if ($_FILES['foto4']['error']==0) {
		$archivo2=$_FILES['foto4']['tmp_name'];
		$nombreArchivo2= "img4".date("dHis").".".pathinfo($_FILES['foto4']['name'], PATHINFO_EXTENSION);
		$rutados = $ruta.$nombreArchivo2;
		move_uploaded_file($archivo2,$ruta.$nombreArchivo2);
		$sql = "INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,?, UNIX_TIMESTAMP())";

		$statement = $pdo->prepare("INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,UNIX_TIMESTAMP())");
		$statement->execute(array(
			$idPedido,
			$rutados
		));


	}

	if ($_FILES['foto5']['error']==0) {
		$archivo2=$_FILES['foto5']['tmp_name'];
		$nombreArchivo2= "img5".date("dHis").".".pathinfo($_FILES['foto5']['name'], PATHINFO_EXTENSION);
		$rutados = $ruta.$nombreArchivo2;
		move_uploaded_file($archivo2,$ruta.$nombreArchivo2);
		$sql = "INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,?, UNIX_TIMESTAMP())";

		$statement = $pdo->prepare("INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,UNIX_TIMESTAMP())");
		$statement->execute(array(
			$idPedido,
			$rutados
		));


	}

	if ($_FILES['foto6']['error']==0) {
		$archivo2=$_FILES['foto6']['tmp_name'];
		$nombreArchivo2= "img6".date("dHis").".".pathinfo($_FILES['foto6']['name'], PATHINFO_EXTENSION);
		$rutados = $ruta.$nombreArchivo2;
		move_uploaded_file($archivo2,$ruta.$nombreArchivo2);
		$sql = "INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,?, UNIX_TIMESTAMP())";

		$statement = $pdo->prepare("INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,UNIX_TIMESTAMP())");
		$statement->execute(array(
			$idPedido,
			$rutados
		));


	}

	if ($_FILES['foto7']['error']==0) {
		$archivo2=$_FILES['foto7']['tmp_name'];
		$nombreArchivo2= "img7".date("dHis").".".pathinfo($_FILES['foto7']['name'], PATHINFO_EXTENSION);
		$rutados = $ruta.$nombreArchivo2;
		move_uploaded_file($archivo2,$ruta.$nombreArchivo2);
		$sql = "INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,?, UNIX_TIMESTAMP())";

		$statement = $pdo->prepare("INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,UNIX_TIMESTAMP())");
		$statement->execute(array(
			$idPedido,
			$rutados
		));


	}

	if ($_FILES['foto8']['error']==0) {
		$archivo2=$_FILES['foto8']['tmp_name'];
		$nombreArchivo2= "img8".date("dHis").".".pathinfo($_FILES['foto8']['name'], PATHINFO_EXTENSION);
		$rutados = $ruta.$nombreArchivo2;
		move_uploaded_file($archivo2,$ruta.$nombreArchivo2);
		$sql = "INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,?, UNIX_TIMESTAMP())";

		$statement = $pdo->prepare("INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,UNIX_TIMESTAMP())");
		$statement->execute(array(
			$idPedido,
			$rutados
		));


	}

	if ($_FILES['foto9']['error']==0) {
		$archivo2=$_FILES['foto9']['tmp_name'];
		$nombreArchivo2= "img9".date("dHis").".".pathinfo($_FILES['foto9']['name'], PATHINFO_EXTENSION);
		$rutados = $ruta.$nombreArchivo2;
		move_uploaded_file($archivo2,$ruta.$nombreArchivo2);
		$sql = "INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,?, UNIX_TIMESTAMP())";

		$statement = $pdo->prepare("INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,UNIX_TIMESTAMP())");
		$statement->execute(array(
			$idPedido,
			$rutados
		));


	}

	if ($_FILES['foto10']['error']==0) {
		$archivo2=$_FILES['foto10']['tmp_name'];
		$nombreArchivo2= "img10".date("dHis").".".pathinfo($_FILES['foto10']['name'], PATHINFO_EXTENSION);
		$rutados = $ruta.$nombreArchivo2;
		move_uploaded_file($archivo2,$ruta.$nombreArchivo2);
		$sql = "INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,?, UNIX_TIMESTAMP())";

		$statement = $pdo->prepare("INSERT INTO `imgPedidos` (`idPedido`, `ruta`, `fecha`) VALUES (?,?,UNIX_TIMESTAMP())");
		$statement->execute(array(
			$idPedido,
			$rutados
		));


	}

		echo '1';
}
//header('Content-type: text/json');

?>
