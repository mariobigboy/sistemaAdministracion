<?php

    include('inc/configMario.php');
	
	$q = (isset($_POST['idFactura']))? $_POST['idFactura'] : '';
	//echo $q;
	$arrOut = array();
	if($q != ""){
		$sql="SELECT d.id_detalle, d.id_producto, d.nombre, d.cantidad, d.precio FROM `detalle` AS d
		INNER JOIN factura as f ON f.num_factura = d.id_factura WHERE d.id_factura = '$q';";

		$cons = mysqli_query($con,$sql) or die (mysqli_error($con));
		//$results = mysqli_fetch_array($cons);
		while ($f3= mysqli_fetch_array($cons)) {
			array_push($arrOut, $f3);
		}
      
	}
    
    echo json_encode($arrOut);
	header('Content-type: text/json'); 
?>