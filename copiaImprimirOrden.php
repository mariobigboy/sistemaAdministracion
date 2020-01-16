<?php 

include("inc/config.php");
$id = isset($_GET['id'])? $_GET['id'] : 0;

if($id!=0){
		//$statement = $pdo->prepare("SELECT t1.*, t2.*, t3.p_name, t3.p_code FROM `detalle` AS t1 INNER JOIN factura AS t2 ON t2.num_factura = t1.id_factura INNER JOIN tbl_product AS t3 ON t1.id_producto = t3.p_id WHERE t2.num_factura = ?");

		//obtenemos orden:
	$statement = $pdo->prepare("SELECT p.*, REPLACE(REPLACE(p.obs,CHAR(10),' '),CHAR(13),' - ') as reemplazo, c.c_apellido, c.c_nombre, FROM_UNIXTIME(p.fecha, '%d/%m/%Y %H:%i') fecha_format FROM `pedido` AS p INNER JOIN factura AS f ON f.num_factura = p.idFactura INNER JOIN tbl_cliente AS c ON c.c_id = f.id_cliente WHERE p.id = ? ;");
	$statement->execute(array($id));
	$resultado = $statement->fetchAll(PDO::FETCH_ASSOC);

	foreach($resultado as $row){
		$id_orden = $row['id'];
		$nombre = $row['c_nombre'];
		$apellido = $row['c_apellido'];
		$fecha = $row['fecha_format'];
		$usuario = $row['usuarioSucursal'];
		$obs = str_replace('-', '\n', $row['reemplazo']);
		$detalles = $row['detalles'];
		$suc = $row['sucursal'];
		$factura = $row['idFactura'];

	}

	switch ($suc) {
		case '1':
		$sucursal= "Home Design Salta";
		break;
		case '2':
		$sucursal="Muebles & Deco";
		break;
		case '3':
		$sucursal = "Home de la Av";
		break;
		case '4':
		$sucursal = "Infantil Salta";
		break;
		case '5':
		$sucursal= "Home Online";
		break;

		default:
											# code...
		break;
	}


}


?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Generando Presupuesto... | HomeDesign </title>
</head>
<body>

	<?php 
	$statement = $pdo->prepare("SELECT * FROM `imgPedidos` WHERE idPedido = '$id' ;");
	$statement->execute();
	$resultado = $statement->fetchAll(PDO::FETCH_ASSOC);
	$filas = $statement->rowCount();
	if ($filas > 0) {
		$cont=1;
		
		foreach ($resultado as $row) {
			$im = file_get_contents($row['ruta']);
			$imdata = base64_encode($im);
			$text = "data:image/jpg;base64,".$imdata;
			echo "<input type='hidden' id='imag".$cont."' value='".$text."'>";
		}
	}
	?>
	<!-- lo dejo con el mismo logo para todos -->
	<img src="<?php echo 'img/200/1_200x200.png'; ?>" id="img1" style="display: none;">

	<iframe src="" id="framePdf" frameborder="0" style="width: 100%; height: 800px;position: absolute; top: 0px; left:0px;"></iframe>


	<!-- jQuery -->
	<script src="js/jquery-2.2.4.min.js"></script>
	
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<!-- pdf tables -->



	<script type="text/javascript">
		var pdf;
		

		$(document).ready(function(){

			var c = document.createElement('canvas');
			var img = document.getElementById('img1');
			c.height = img.naturalHeight;
			c.width = img.naturalWidth;
			var ctx = c.getContext('2d');

			ctx.drawImage(img, 0, 0, c.width, c.height);
			var base64String = c.toDataURL();
			//console.log(base64String);

			var imgn = base64String;

			
			var dd = {
				pageSize: 'A4',
				content: [
				{
					image: imgn,
					fit: [50,50],
				},
				{
					text: '<?php echo "FÁBRICA" ; ?>',
						//style: 'subheader',
						absolutePosition: {x: 110, y: 43},
						alignment: 'justify',
					},
					{
						text: '<?php echo "Orden de Trabajo N°: ".$id_orden; ?>',
						//style: 'subheader',
						absolutePosition: {x: 410, y: 43},
						bold: true,
						alignment: 'justify',
					},
					{
						text: '<?php echo "Fecha: ".$fecha; ?>',
						//style: 'subheader',
						absolutePosition: {x: 410, y: 59},
						bold: true,
						alignment: 'justify',
					},
					{
						text: 'Cuit: 30-71597745-8',
						//style: 'subheader',
						absolutePosition: {x: 410, y: 75},
						bold: true,
						alignment: 'justify',
					},
					{
						
						text: '<?php echo "Home Design"; ?>',
						//style: 'subheader',
						absolutePosition: {x: 110, y: 56},
						fontSize: 10,
						alignment: 'justify',
					},
					{
						text: 'I.V.A. Responsable Inscripto',
						absolutePosition: {x: 110, y: 80},
					},
					
					{
						canvas: [
						{
							type: 'line',
							x1: 0,
							y1: 10,
							x2: 535,
							y2: 10,
							lineWidth: 2.0
						}
						]

					},
					/*{
					    columns: [
					        {width: '*', text: 'columna 1', alignment: 'center', style: 'header'},
					        {width: '*', text: 'columna 2', alignment: 'center', style: 'header'}
					        ],
					    },*/


					    {
					    	text: 'CLIENTE:', 
					    	style: 'subheader',
					    	absolutePosition: {x: 40, y: 110},

					    },
					    {
					    	text: '<?php echo $nombre." ".$apellido; ?>',
					    	style: 'valor',
					    	absolutePosition: {x: 109, y: 112},   
					    },
					    {
					    	text: 'Solicitado:', 
					    	style: 'subheader',
					    	absolutePosition: {x: 40, y: 130},
					    },
					    {
					    	text: '<?php echo $usuario; ?>',
					    	style: 'valor',
					    	absolutePosition: {x: 120, y: 132},   
					    },
					    {
					    	text: 'Entregar en:', 
					    	style: 'subheader',
					    	absolutePosition: {x: 40, y: 150},
					    },
					    {
					    	text: '<?php echo $sucursal; ?>',
					    	style: 'valor',
					    	absolutePosition: {x: 119, y: 152},   
					    },

					    {
					    	text:'Factura N:', 
					    	style: 'subheader',
					    	absolutePosition: {x: 320, y: 150},
					    },
					    {
					    	text: '<?php echo $factura;?>',
					    	style: 'valor',
					    	absolutePosition: {x: 395, y: 152},   
					    },
					    {
					    	text:'', 
					    	style: 'subheader',
					    	absolutePosition: {x: 320, y: 150},
					    },
					    {
					    	text: '',
					    	style: 'valor',
					    	absolutePosition: {x: 380, y: 152},   
					    },

					    {
					    	canvas: [
					    	{
					    		type: 'line',
					    		x1: 0,
					    		y1: 70,
					    		x2: 535,
					    		y2: 70,
					    		lineWidth: 2.0
					    	}
					    	]

					    },

					    {text: '\n\nTrabajo/s a Realizar: \n', style: 'header'},

					//aqui va la tabla:
					{
						style: 'tableExample',
						table: {
							headerRows: 1,
							widths:[50, 240, 70],
							body: [
							[
							{text: '#', style: 'tableHeader'},
							{text: 'Descripción', style: 'tableHeader'},
							{text: 'Cantidad.', style: 'tableHeader'},
							],
							<?php 
							$vec = explode(",", $detalles);
							$cont=1;
							$cantidad = 0;
							for ( $i = 0; $i < sizeof($vec); $i++) {
								$id=$vec[$i];
								$statement = $pdo->prepare("SELECT nombre, cantidad FROM `detalle` WHERE id_detalle = '$id' ;");
								$statement->execute();
								$resultado = $statement->fetchAll(PDO::FETCH_ASSOC);
								$cantidad += $resultado[0]['cantidad'];
								echo '["'.$cont.'","'.$resultado[0]['nombre'].'","'.$resultado[0]['cantidad'].'"],';
								$cont++;
							}
							?>
							[
							{text: '', style: 'tableFooter'},
							{text: 'Total Unidades: ', style:'tableFooter'},
							{text: '<?php echo $cantidad; ?>', style:'tableFooter'}
							],
								//['Sample value 1', 'Sample value 2', 'Sample value 3'],
								//['Sample value 1', 'Sample value 2', 'Sample value 3'],
								//['Sample value 1', 'Sample value 2', 'Sample value 3'],
								//['Sample value 1', 'Sample value 2', 'Sample value 3'],
								]
							},
							layout: 'headerLineOnly'
						},

						{
							text: "\n\n\n\n\n\n=====================================================================================================",
							fontSize: 8,
							alignment: 'center',
						},
						{
							style: 'observacion', 
							text: "OBSERVACIONES: \n  <?php echo $obs; ?>",
							alignment: 'center',


						},
						{
							text: "=====================================================================================================\n\n\n\n\n\n",
							fontSize: 8,
							alignment: 'center',
						},
						{
							style: 'observacion', 
							text: "<?php echo "Imágenes"; ?>",
							alignment: 'left',


						}, 



						{
							canvas: [
							{
								type: 'line',
								x1: 0,
								y1: 10,
								x2: 535,
								y2: 10,
								lineWidth: 2.0
							}
							]

						},
						

						],
						
						footer: [
						


						],


						styles: {
							header: {
								fontSize: 18,
								bold: true
							},
							subheader:{
								fontSize: 14,
								bold: true,
					    //margin: [0,10,0,0]
					},
					bigger: {
						fontSize: 15,
						italics: true
					},
					valor: {
						fontSize: 12,
						bold: false,
						italics: true,
					},
					tableExample: {
						margin: [0, 0, 0, 0],
						fontSize: 12,
						
					},
					tableFooter:{
						fontSize: 12,
						bold: true,
						border: true,
					},
					observacion: {
						fontSize: 12,

					},
					defaultStyle: {
						fontSize: 9
					},
				}
                        
				
			};

			pdf = pdfMake.createPdf(dd);
			pdf.getDataUrl(function(dataurl){$('#framePdf').attr('src', dataurl); $('#framePdf').fadeIn();});
			//pdf.open();
		});
	</script>
</body>
</html>
