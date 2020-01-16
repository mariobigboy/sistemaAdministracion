<?php 

	include("inc/config.php");
	$id = isset($_GET['id'])? $_GET['id'] : 0;
	
	if($id!=0){
		//$statement = $pdo->prepare("SELECT t1.*, t2.*, t3.p_name, t3.p_code FROM `detalle` AS t1 INNER JOIN factura AS t2 ON t2.num_factura = t1.id_factura INNER JOIN tbl_product AS t3 ON t1.id_producto = t3.p_id WHERE t2.num_factura = ?");
		
		//obtenemos presupuesto:
		$statement = $pdo->prepare("SELECT *, DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(fecha),'+00:00','-03:00'), '%d/%m/%Y %H:%i') fecha_format, REPLACE(REPLACE(concepto,CHAR(10),' '),CHAR(13),' - ') as reemplazo FROM recibos WHERE id = '$id' ;");
		$statement->execute(array($id));
		$resultado = $statement->fetchAll(PDO::FETCH_ASSOC);

		foreach($resultado as $row){
			$id_recibo = $row['id'];
			$cliente = $row['cliente'];
			$sucursal = $row['sucursal'];
			$fac_fecha_format = $row['fecha_format'];
			$concepto = str_replace('-', '\n', $row['reemplazo']);
			$monto = $row['monto'];

		}
		if ($sucursal==99) {
			$id_sucursal = 1;
		}else{
			$id_sucursal = $sucursal;
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

		
	}

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Generando Presupuesto... | HomeDesign </title>
</head>
<body>
	<?php if ($id_sucursal == 98 || $id_sucursal == 99) {

	?>
	<img src="<?php echo 'img/200/1_200x200.png'; ?>" id="img1" style="display: none;">
	<?php  
	}else{ ?>
	<img src="<?php echo 'img/200/'.$id_sucursal.'_200x200.png'; ?>" id="img1" style="display: none;">

<?php } ?>

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
						text: '<?php echo "RECIBO N°: ".$id_recibo; ?>',
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
					    text: 'Recibí de:', 
					    style: 'subheader',
					    absolutePosition: {x: 40, y: 110},
					    
					},
					{
					    text: '<?php echo $cliente; ?>',
					    style: 'subheader',
					    absolutePosition: {x: 109, y: 110},   
					},
					
					
					
					
					{
					    canvas: [
			                {
			                    type: 'line',
			                    x1: 0,
			                    y1: 50,
			                    x2: 535,
			                    y2: 50,
			                    lineWidth: 1.0
			                }
			            ]
					    
					},
					{
					    text: ' ', 
					    style: 'subheader',
					   
					},
					{
					    text: 'En concepto de:', 
					    style: 'subheader',
					   
					},
					{
					    text: '<?php echo $concepto; ?>',
					    style: 'subheader',
					    
					},

					{
					    canvas: [
			                {
			                    type: 'line',
			                    x1: 0,
			                    y1: 150,
			                    x2: 535,
			                    y2: 150,
			                    lineWidth: 1.0
			                }
			            ]
					    
					},
					{
					    text: ' ', 
					    style: 'subheader',
					   
					},
					{
					    text: 'El monto de: ', 
					    style: 'valor',
					   
					},
					{
					    text: '<?php echo " $".$monto; ?>',
					    style: 'subheader',
					   
					},

					{
					    canvas: [
			                {
			                    type: 'line',
			                    x1: 0,
			                    y1: 20,
			                    x2: 535,
			                    y2: 20,
			                    lineWidth: 1.0
			                }
			            ]
					    
					},
					
					
				],
				footer: [
				

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
