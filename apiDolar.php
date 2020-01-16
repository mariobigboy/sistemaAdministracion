<?php 
	$amount = ($_GET['amount'])? $_GET['amount'] : 0;
	//precio del dolar:
	$file = 'http://data.fixer.io/api/latest?access_key=32eacd7af4483118cc230e8b78eea4f5&symbols=EUR,ARS,USD&base=EUR';
	$response = json_decode(file_get_contents($file));
	
	$base = $response->base;
	$ars = floatval($response->rates->ARS);
	$usd = floatval($response->rates->USD);
	
	//conversión
	$eurToUsd = $amount / $usd;
	$usdToArs = $eurToUsd * $ars;

	header('Content-type: text/json');
	echo '{"ARS" : '.$usdToArs.'}';
 ?>