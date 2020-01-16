<?php 

	include("inc/config.php");
	$id = isset($_GET['id'])? $_GET['id'] : 0;
	
	if($id!=0){
		//$statement = $pdo->prepare("SELECT t1.*, t2.*, t3.p_name, t3.p_code FROM `detalle` AS t1 INNER JOIN factura AS t2 ON t2.num_factura = t1.id_factura INNER JOIN tbl_product AS t3 ON t1.id_producto = t3.p_id WHERE t2.num_factura = ?");
		
		//obtenemos factura:
		$statement = $pdo->prepare("SELECT t1.*, t2.*, t3.p_name, t3.p_code, t4.*, DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(t2.fecha),'+00:00','-03:00'), '%d/%m/%Y %H:%i') fecha_format, REPLACE(REPLACE(t2.obs,CHAR(10),' '),CHAR(13),' - ') as reemplazo FROM `detalle` AS t1 INNER JOIN factura AS t2 ON t2.num_factura = t1.id_factura INNER JOIN tbl_product AS t3 ON t1.id_producto = t3.p_id INNER JOIN tbl_cliente as t4 ON t2.id_cliente = t4.c_id WHERE t2.num_factura = ?;");
		$statement->execute(array($id));
		$resultado = $statement->fetchAll(PDO::FETCH_ASSOC);

		foreach($resultado as $row){
			$num_factura = $row['num_factura'];
			$id_cliente = $row['id_cliente'];
			$id_sucursal = $row['sucursal'];
			$fac_fecha = $row['fecha'];
			$fac_fecha_format = $row['fecha_format'];
			$descuento_gral = $row['descuento_gral'];

			$c_email = $row['c_email'];
			$c_cuit = $row['c_cuit'];
			$c_razon_social = $row['c_razon_social'];
			$c_nombre = $row['c_nombre'];
			$c_tel = $row['c_tel'];
			$c_cel = $row['c_cel'];
			$c_apellido = $row['c_apellido'];
			$c_nro_doc = $row['c_nro_doc'];
			$c_calle = $row['c_calle'];
			$c_calle_nro = $row['c_calle_nro'];
			$c_barrio = $row['c_barrio'];

			$obs = str_replace('-', '\n', $row['reemplazo']);
		}

		//obtengos datos de la sucursal:
		$statementSucursal = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_id = ?;");
		$statementSucursal->execute(array($id_sucursal));
		$resultadoSucursal = $statementSucursal->fetchAll(PDO::FETCH_ASSOC);
		foreach($resultadoSucursal as $row){
			$s_id = $row['s_id'];
			$s_name = $row['s_name'];
			$s_address = $row['s_address'];
			$s_phones = $row['s_phones'];
			$s_condicion_iva = $row['s_condicion_iva'];
			$s_cuit_cuil = $row['s_cuit_cuil'];
		}

		$sucursal_phones = explode(",", $s_phones);

		//Obtengo los detalles de pago:
		$statPagos = $pdo->prepare("SELECT * FROM pagos WHERE id_factura = ?;");
		$statPagos->execute(array($id));
		$resultPagos = $statPagos->fetchAll(PDO::FETCH_ASSOC);
		foreach($resultPagos as $row){
			$arrayPagos = $row;
		}
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Generando Factura... | HomeDesign </title>
</head>
<body>

	<h3>Generando pdf...</h3>
	<img src="<?php echo 'img/200/'.$s_id.'_200x200.png'; ?>" id="img1" style="display: none;">

	<iframe src="" id="framePdf" frameborder="0" style="width: 100%; height: 800px;position: absolute; top: 0px; left:0px;"></iframe>

	<!-- jQuery -->
	<script src="js/jquery-2.2.4.min.js"></script>


	
	
	<!-- pdf tables-->
	<!--<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<!-- pdf tables -->



	<script type="text/javascript">
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
						text: '<?php echo $s_name; ?>',
						//style: 'subheader',
						absolutePosition: {x: 110, y: 43},
						alignment: 'justify',
					},
					{
						text: '<?php echo "Comprobante N°: ".$num_factura; ?>',
						//style: 'subheader',
						absolutePosition: {x: 410, y: 43},
						bold: true,
						alignment: 'justify',
					},
					{
						text: '<?php echo "Fecha: ".$fac_fecha_format; ?>',
						//style: 'subheader',
						absolutePosition: {x: 410, y: 57},
						bold: true,
						alignment: 'justify',
					},
					{
						text: '<?php echo "Cuit/Cuil: ".$s_cuit_cuil; ?>',
						//style: 'subheader',
						absolutePosition: {x: 410, y: 70},
						bold: true,
						alignment: 'justify',
					},
					{
						text: '<?php echo $s_address; ?>',
						//style: 'subheader',
						absolutePosition: {x: 110, y: 56},
						fontSize: 10,
						alignment: 'justify',
					},
					{
						text: '<?php echo "Tel/Cel: ".$sucursal_phones[0]; ?>',
						//style: 'subheader',
						absolutePosition: {x: 110, y: 69},
						fontSize: 10,
						alignment: 'justify',
					},
					{
					    text: '<?php echo $s_condicion_iva; ?>',
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
					    text: '<?php echo $c_nombre." ".$c_apellido; ?>',
					    style: 'valor',
					    absolutePosition: {x: 109, y: 112},   
					},
					{
					    text: 'DOMICILIO:', 
					    style: 'subheader',
					    absolutePosition: {x: 40, y: 130},
					},
					{
					    text: '<?php echo $c_calle." N° ".$c_calle_nro." - ".$c_barrio."Tel: ".$c_tel." ".$c_cel; ?>',
					    style: 'valor',
					    absolutePosition: {x: 120, y: 132},   
					},
					{
					    text: 'IVA:', 
					    style: 'subheader',
					    absolutePosition: {x: 40, y: 150},
					},
					{
					    text: 'CONSUMIDOR FINAL',
					    style: 'valor',
					    absolutePosition: {x: 75, y: 152},   
					},
					{
					    text:'LOCALIDAD:', 
					    style: 'subheader',
					    absolutePosition: {x: 320, y: 110},
					},
					{
					    text: 'SALTA - SALTA',
					    style: 'valor',
					    absolutePosition: {x: 410, y: 112},   
					},
					{
					    text:'C.U.I.T.:', 
					    style: 'subheader',
					    absolutePosition: {x: 320, y: 150},
					},
					{
					    text: '<?php echo $c_cuit; ?>',
					    style: 'valor',
					    absolutePosition: {x: 380, y: 150},   
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
					//aqui va la tabla:
					{
						style: 'tableExample',
						table: {
							headerRows: 1,
							widths:[100, 170, 40, 40, 50, 50],
							body: [
								[
								    {text: 'Código', style: 'tableHeader'},
								    {text: 'Descripción', style: 'tableHeader'},
								    {text: 'Cant.', style: 'tableHeader'},
								    {text: 'Desc.', style: 'tableHeader'},
								    {text: 'Precio', style:'tableHeader'},
								    {text: 'SubTotal', style:'tableHeader'}
								 ],
								 <?php 
									foreach($resultado as $row){
								 		$total_total = $row['total'];

										#	| Código | Descripción | Cant. | Desc. | Precio | SubTotal |

										$sub_total = floatval($row['cantidad']) * floatval($row['precio']);
										//$total_total += $sub_total;
										/*echo '["'.$row['p_code'].'","'.$row['p_name'].'","'.$row['cantidad'].'","'.$row['descuento'].'%","$'.$row['precio'].'","$'.$sub_total.'"],';*/
										$descrip = ($row['id_producto']==-1)? $row['nombre'] : $row['p_name'];
										echo '["'.$row['p_code'].'","'.str_replace('"',"''",$descrip).'","'.$row['cantidad'].'","'.$row['descuento'].'%","$'.$row['precio'].'","$'.$sub_total.'"],';
									}

								 ?>
								 [
								    {text: ' '},
								    {text: 'Descuento gral.'},
								    {text: ' '},
								    {text: '<?php echo $descuento_gral; ?>'},
								    {text: ' '},
								    {text: ' '}
								 ],
								 [
								    {text: ' ', style: 'tableFooter'},
								    {text: ' ', style: 'tableFooter'},
								    {text: '', style: 'tableFooter'},
								    {text: '', style: 'tableFooter'},
								    {text: 'Total: ', style:'tableFooter'},
								    {text: '<?php echo "$".$total_total; ?>', style:'tableFooter'}
								 ],
								//['Sample value 1', 'Sample value 2', 'Sample value 3'],
								//['Sample value 1', 'Sample value 2', 'Sample value 3'],
								//['Sample value 1', 'Sample value 2', 'Sample value 3'],
								//['Sample value 1', 'Sample value 2', 'Sample value 3'],
							]
						},
						layout: 'headerLineOnly'
					}
					
				],
				footer:[
			        //tabla:
			        {
    				    style: 'tablePayment',
    				    table: {
    				        headerRows: 1,
    				        widths: [30, 430, 50],
    				        body: [
    				            [
    				                {text: 'N°'},
    				                {text: 'Método de Pago'},
    				                {text: 'Importe'},
    				                ],
    				            /*[
    				                {text: '1'},
    				                {text: 'row 1'},
    				                {text: 'row 1'},
    				                ],*/
    				            <?php 
    				            	$i = 1;
    				            	switch ($arrayPagos['metodo1']) {
    				            		case '1':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Efectivo"}, {text: "$'.$arrayPagos['subt1'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '2':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Tarjeta de Crédito/Débito"}, {text: "$'.$arrayPagos['subt1'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '3':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Cheque a Terceros"}, {text: "$'.$arrayPagos['subt1'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '4':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Cuenta Corriente"}, {text: "$'.$arrayPagos['subt1'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		default:
    				            			$linea = '';
    				            			break;
    				            	}
    				            	echo $linea;
    				            	switch ($arrayPagos['metodo2']) {
    				            		case '1':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Efectivo"}, {text: "$'.$arrayPagos['subt2'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '2':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Tarjeta de Crédito/Débito"}, {text: "$'.$arrayPagos['subt2'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '3':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Cheque a Terceros"}, {text: "$'.$arrayPagos['subt2'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '4':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Cuenta Corriente"}, {text: "$'.$arrayPagos['subt2'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		default:
    				            			$linea = '';
    				            			break;
    				            	}
    				            	echo $linea;
    				            	switch ($arrayPagos['metodo3']) {
    				            		case '1':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Efectivo"}, {text: "$'.$arrayPagos['subt3'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '2':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Tarjeta de Crédito/Débito"}, {text: "$'.$arrayPagos['subt3'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '3':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Cheque a Terceros"}, {text: "$'.$arrayPagos['subt3'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '4':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Cuenta Corriente"}, {text: "$'.$arrayPagos['subt3'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		default:
    				            			$linea = '';
    				            			break;
    				            	}
    				            	echo $linea;
    				            ?>
    				        ]
    				    },
    				    layout: 'headerLineOnly'
    				    
    				},
    				{
			            style: 'observacion', 
			            text: "OBSERVACIONES:  <?php echo str_replace('"',"''",$obs); ?>",
			            margin:[30,5,0,0],
			            
			            
			        },
			        {
			            style: 'observacion', 
			            text: "===== DOCUMENTO NO VALIDO COMO FACTURA =====",
			            margin:[200,5,0,0],
			            
			            
			        },
			        /*{
			            style: '', 
			            text: "", 
			            absolutePosition: {x: 120, y:-10}
			            
			        }*/
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
						fontSize: 9,
					},
					tablePayment:{
					    margin: [30, -100, 0, 0],
					    fontSize: 9,
					},
					tableFooter:{
					    fontSize: 10,
					    bold: true,
					},
					observacion: {
						fontSize: 8,

					},
					defaultStyle: {
						fontSize: 9
					},
				}
				
			};

			var pdf = pdfMake.createPdf(dd);
			//pdf.open(); //.print() abre ventana de impresión.
			pdf.getDataUrl(function(dataurl){$('#framePdf').attr('src', dataurl); $('#framePdf').fadeIn();});
		});
	</script>
</body>
</html>
