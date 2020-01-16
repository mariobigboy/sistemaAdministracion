<?php 

	include("inc/config.php");
	$id = isset($_GET['id'])? $_GET['id'] : 0;
	
	if($id!=0){
		//$statement = $pdo->prepare("SELECT t1.*, t2.*, t3.p_name, t3.p_code FROM `detalle` AS t1 INNER JOIN factura AS t2 ON t2.num_factura = t1.id_factura INNER JOIN tbl_product AS t3 ON t1.id_producto = t3.p_id WHERE t2.num_factura = ?");
		
		//obtenemos presupuesto:
		$statement = $pdo->prepare("SELECT t1.*, t2.*, t3.p_name, t3.p_code, t4.*, DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(t2.fecha),'+00:00','-03:00'), '%d/%m/%Y %H:%i') fecha_format, REPLACE(REPLACE(t2.obs,CHAR(10),' '),CHAR(13),' - ') as reemplazo FROM `detalle` AS t1 INNER JOIN presupuesto AS t2 ON t2.id_presupuesto = t1.id_presupuesto INNER JOIN tbl_product AS t3 ON t1.id_producto = t3.p_id INNER JOIN tbl_cliente as t4 ON t2.id_cliente = t4.c_id WHERE t2.id_presupuesto = ? ;");
		$statement->execute(array($id));
		$resultado = $statement->fetchAll(PDO::FETCH_ASSOC);

		foreach($resultado as $row){
			$id_presupuesto = $row['id_presupuesto'];
			$id_cliente = $row['id_cliente'];
			$id_sucursal = $row['sucursal'];
			$fac_fecha = $row['fecha'];
			$fac_fecha_format = $row['fecha_format'];
			$obs = str_replace('-', '\n', $row['reemplazo']);
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
		}

		//Obtengo los detalles de pago:
		$statPagos = $pdo->prepare("SELECT * FROM pagos WHERE id_presupuesto = ?;");
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
	<title>Generando Presupuesto... | HomeDesign </title>
</head>
<body>
	<img src="<?php echo 'img/200/'.$id_sucursal.'_200x200.png'; ?>" id="img1" style="display: none;">

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
						text: '<?php echo $s_name; ?>',
						//style: 'subheader',
						absolutePosition: {x: 110, y: 43},
						alignment: 'justify',
					},
					{
						text: '<?php echo "PRESUPUESTO N°: ".$id_presupuesto; ?>',
						//style: 'subheader',
						absolutePosition: {x: 410, y: 43},
						bold: true,
						alignment: 'justify',
					},
					{
						text: '<?php echo "Fecha: ".$fac_fecha_format; ?>',
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
						text: '<?php echo $s_address; ?>',
						//style: 'subheader',
						absolutePosition: {x: 110, y: 56},
						fontSize: 10,
						alignment: 'justify',
					},
					{
						text: '<?php echo "Tel: ".$s_phones; ?>',
						//style: 'subheader',
						absolutePosition: {x: 110, y: 69},
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
					//aqui va la tabla:
					{
						style: 'tableExample',
						table: {
							headerRows: 1,
							widths:[100, 170, 30, 40, 50, 60],
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
								 	$total_calculado = 0;
									foreach($resultado as $row){
								 		$total_total = $row['total'];

										#	| Código | Descripción | Cant. | Desc. | Precio | SubTotal |

										$sub_total = floatval($row['cantidad']) * floatval($row['precio']);
										$total_calculado += $sub_total;
										//$total_total += $sub_total;
										$descrip = ($row['id_producto']==-1)? $row['nombre'] : $row['p_name'];
										echo '["'.$row['p_code'].'","'.str_replace('"',"''",$descrip).'","'.$row['cantidad'].'","'.$row['descuento'].'","'.$row['precio'].'","'.$sub_total.'"],';
									}
								 ?>
								 [
								    {text: ' ', style: 'tableFooter'},
								    {text: ' ', style: 'tableFooter'},
								    {text: '', style: 'tableFooter'},
								    {text: '', style: 'tableFooter'},
								    {text: 'Total: ', style:'tableFooter'},
								    {text: '<?php echo '$'.number_format($total_calculado, 2); ?>', style:'tableFooter'}
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
    				    style: 'tablePayment',
    				    table: {
    				        headerRows: 1,
    				        widths: [30, 370, 50, 50], //widths:[100, 170, 40, 40, 50, 50],
    				        body: [
    				            [
    				                {text: 'N°'},
    				                {text: 'Método de Pago'},
    				                {text: 'Interés %'},
    				                {text: 'Importe'},
    				                ],
    				           
    				            <?php 
    				            	$i = 1;
    				            	switch ($arrayPagos['metodo1']) {
    				            		case '1':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Efectivo/Débito"}, {text: "0"}, {text: "$'.$arrayPagos['subt1'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '2':
    				            			$texto = ($arrayPagos['interes1']==0)? "Tarjeta Débito" : "Tarjeta Crédito";
    				            			$linea = '[{text: "'.$i.'"}, {text: "'.$texto.'"}, {text: "'.$arrayPagos['interes1'].'"}, {text: "$'.$arrayPagos['subt1'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '3':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Cheque a Terceros"}, {text: "0"}, {text: "$'.$arrayPagos['subt1'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '4':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Cuenta Corriente"}, {text: "0"}, {text: "$'.$arrayPagos['subt1'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		default:
    				            			$linea = '';
    				            			break;
    				            	}
    				            	echo $linea;
    				            	switch ($arrayPagos['metodo2']) {
    				            		case '1':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Efectivo/Débito"}, {text: "0"}, {text: "$'.$arrayPagos['subt2'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '2':
    				            			$texto = ($arrayPagos['interes2']==0)? "Tarjeta Débito" : "Tarjeta Crédito";
    				            			$linea = '[{text: "'.$i.'"}, {text: "'.$texto.'"}, {text: "'.$arrayPagos['interes2'].'"}, {text: "$'.$arrayPagos['subt2'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '3':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Cheque a Terceros"}, {text: "0"}, {text: "$'.$arrayPagos['subt2'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '4':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Cuenta Corriente"}, {text: "0"}, {text: "$'.$arrayPagos['subt2'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		default:
    				            			$linea = '';
    				            			break;
    				            	}
    				            	echo $linea;
    				            	switch ($arrayPagos['metodo3']) {
    				            		case '1':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Efectivo/Débito"}, {text: "0"}, {text: "$'.$arrayPagos['subt3'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '2':
    				            			$texto = ($arrayPagos['interes3']==0)? "Tarjeta Débito" : "Tarjeta Crédito";
    				            			$linea = '[{text: "'.$i.'"}, {text: "'.$texto.'"}, {text: "'.$arrayPagos['interes3'].'"}, {text: "$'.$arrayPagos['subt3'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '3':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Cheque a Terceros"}, {text: "0"}, {text: "$'.$arrayPagos['subt3'].'"}, ],';
    				            			$i+=1;
    				            			break;
    				            		case '4':
    				            			$linea = '[{text: "'.$i.'"}, {text: "Cuenta Corriente"}, {text: "0"}, {text: "$'.$arrayPagos['subt3'].'"}, ],';
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
					
				],
				footer: [
					 {
						text: "=====================================================================================================",
						fontSize: 8,
						alignment: 'center',
					},
					{
			            style: 'observacion', 
			            text: "OBSERVACIONES:  <?php echo $obs; ?>",
			            alignment: 'center',
			            
			            
			        },
			        {
						text: "=====================================================================================================",
						fontSize: 8,
						alignment: 'center',
					},
					{
						text: "Presupuesto válido por 5 días a partir de la fecha de emisión.",
						fontSize: 8,
						alignment: 'center',
					}

				],

				/*footer:[
			        //tabla:
			        
    				{
			            style: 'observacion', 
			            text: "OBSERVACIONES:  <?php echo $obs; ?>",
			            margin:[30,5,0,0],
			            
			            
			        },
			        //{
			        //    style: '', 
			        //    text: "", 
			        //    absolutePosition: {x: 120, y:-10}
			        //}
			    ], */
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
					    margin: [0, 60, 0, 0], //[30, -100, 0, 0],
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

			pdf = pdfMake.createPdf(dd);
			pdf.getDataUrl(function(dataurl){$('#framePdf').attr('src', dataurl); $('#framePdf').fadeIn();});
			//pdf.open();
		});
	</script>
</body>
</html>
