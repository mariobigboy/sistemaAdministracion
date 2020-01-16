<?php 
	//include("inc/config.php");

	$fecha = date_create('2019-06-14 01:25:33');
	echo date_format($fecha, 'd/m/Y H:i:s');
	
	
	//session_start();
	//print_r($_SESSION['user']);
	//echo random_int(0, sizeof($arr)-1);
	//$phones = explode(",", "3875123");
	//print_r($phones);


	#SELECT * FROM `tbl_stock` where sk_id_producto = 106;

	#Ejemplo:
	#tengo el siguente stock:
	#sucursal 4 = 9;
	#sucursal 1 = 9;
	#sucursal 3 = 7;

	#total = 25;

	#necesito 10 (debería sacar de la sucursal 4 (9) y de la sucursal 1 (1))


	/*
	//Algoritmo para quitar stock de las sucursales de manera eficiente:
	$id_producto = isset($_GET['id_producto'])? $_GET['id_producto']: 106;
	$arr_sucursal_out = array();

	$stat = $pdo->prepare("SELECT * FROM `tbl_stock` where sk_id_producto = ? order by sk_stock DESC");
	$stat->execute(array($id_producto));
	$results = $stat->fetchAll(PDO::FETCH_ASSOC);

	$i_need = isset($_GET['necesito'])? $_GET['necesito'] : 0;

	$i = 0;
	while($i_need > 0 && $i<sizeof($results)){
		if($i_need >= $results[$i]['sk_stock']){
			$i_need -= $results[$i]['sk_stock'];
			array_push($arr_sucursal_out, $results[$i]);
			$arr_sucursal_out[$i]['cantidad'] = $results[$i]['sk_stock'];
			$i++;
		}else{
			if($i_need < $results[$i]['sk_stock']){
				//$arr_sucursal_out[$i]
				if(isset($arr_sucursal_out[$i]['cantidad'])){
					$arr_sucursal_out[$i]['cantidad'] = isset($arr_sucursal_out[$i]['cantidad'])? $arr_sucursal_out[$i]['cantidad'] + 1 : 1;
					$i_need -= 1;
				}else{
					array_push($arr_sucursal_out, $results[$i]);
					$arr_sucursal_out[$i]['cantidad'] = isset($arr_sucursal_out[$i]['cantidad'])? $arr_sucursal_out[$i]['cantidad'] + 1 : 1;
					$i_need -= 1;
				}
			}else{
				$i++;
			}
		}
	}
	if($i_need == 0){
		print_r($arr_sucursal_out);
	}else{
		echo "No hay stock";
	}
	*/
	//fin algoritmo quitar stock eficiente.
?>