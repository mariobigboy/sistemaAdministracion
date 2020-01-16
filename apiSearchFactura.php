<?php

     include('inc/configMario.php');
	
	$q = (isset($_POST['idFactura']))? $_POST['idFactura'] : '';
	//echo $q;
	$arrOut = array();
	if($q != ""){
		$sql="SELECT f.num_factura, f.id_cliente, c.c_id, c.c_nombre, c.c_apellido FROM `factura` AS f INNER JOIN `tbl_cliente` as c ON f.id_cliente = c.c_id WHERE f.num_factura REGEXP '$q' LIMIT 1;";

		$cons = mysqli_query($con,$sql) or die (mysqli_error($con));
		//$results = mysqli_fetch_array($cons);
		while ($f3= mysqli_fetch_array($cons)) {
			array_push($arrOut, $f3);
		}
      
	}
    
    echo json_encode($arrOut);
	header('Content-type: text/json'); 
?>