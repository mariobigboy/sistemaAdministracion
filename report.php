<?php require_once('header.php'); ?>

<section class="content-header">
	<h1>Reportes</h1>
</section>

<?php
	//$statement = $pdo->prepare("SELECT * FROM tbl_top_category");
	//$statement->execute();
	//$total_top_category = $statement->rowCount();
	$anio = isset($_GET['anio'])? $_GET['anio'] : date('Y');
	$cant_anio = 3; //5 años atras y 5 años después. Por defecto: 3
?>

<section class="content">
	<div class="row">
		<!-- <div class="col-md-12 col-sm-12 col-xs-12">
			<div class="box box-info">
				<div class="box-body">
					<h4>Reporte Anual</h4>

				</div>
			</div>
		</div> -->

		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="box box-info">
				<div class="box-body">
					<h4>Reporte Anual de Ventas <?php echo $anio; ?></h4>
					<form action="" method="GET">
						<div class="form-group col-lg-6">
							<label for="" class="control-label col-md-3">Seleccione año</label>
							<div class="col-md-4">
								<select name="anio" id="" class="form-control">
									<?php 
										for ($i=($anio-3); $i < $anio; $i++) { 
											?>
												<option value="<?php echo $i; ?>"><?php echo $i; ?></option>		
											<?php
										}
									 ?>
									<!--<option value="<?php echo $anio; ?>" selected><?php echo $anio; ?></option>-->
									<?php 
										for ($i=$anio; $i <= ($anio+3); $i++) { 
											?>
												<option value="<?php echo $i; ?>" <?php if($anio==$i){echo ' selected';} ?>><?php echo $i; ?></option>		
											<?php
										}
									 ?>
									<!--<option value="2019" selected>2019</option>-->
								</select>
							</div>
							<button type="submit" class="btn btn-success col-md-2">Ver</button>

						</div>
					</form>

					<div id="contenedor" style="width: 100%;">
						<canvas id="grafico"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php require_once('footer.php'); ?>
<script type="text/javascript">

	<?php 

		$statSucursales = $pdo->prepare("SELECT * FROM tbl_sucursales;");
		$statSucursales->execute();
		$resultSucursales = $statSucursales->fetchAll(PDO::FETCH_ASSOC);
		$cantSucursales = $statSucursales->rowCount();

		
	?>

	$(document).ready(function(){
		/*var datos = {
			type: "pie",
			data: {
				datasets: [{
					data : [5, 10, 40, 12, 23],
					backgroundColor: [
						"#f39c12",
						"#e67e22",
						"#2980b9",
						"#9b59b6",
						"#2ecc71"
					],
				}],
				labels: [
					"datos 1",
					"datos 2",
					"datos 3",
					"datos 4",
					"datos 5"
				]
			},
			options: {
				responsive: true,
			}
		};*/ //datos {}

		//colores esquema pallete defo [https://flatuicolors.com]
		

		//var datasets = [];
		<?php 
			//setlocale(LC_NUMERIC, 'es');
			$colores = [
				"#f39c12",
				"#d35400",
				"#f1c40f",
				"#2ecc71",
				"#3498db",
				"#9b59b6",
				"#7f8c8d"
				];
			$colores_temp = $colores;
			$datasets = '';

			foreach($resultSucursales as $sucursal){
				$ind_color = random_int(0, sizeof($colores_temp)-1);
				$color = $colores_temp[$ind_color];
				array_splice($colores_temp, $ind_color, 1);

				$arrayDatos = array();
				for ($i=0; $i < 12; $i++) { 
					$mes = '0'.($i+1);
					$stat = $pdo->prepare("SELECT SUM(total) as suma_total FROM `factura` WHERE (DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(fecha),'+00:00','-03:00'), '%m') = '$mes') AND (DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(fecha),'+00:00','-03:00'), '%Y') = '$anio') AND (sucursal = ?);");
					$stat->execute(array($sucursal['s_id']));
					$result = $stat->fetchAll(PDO::FETCH_ASSOC);

					foreach($result as $row){
						$total = $row['suma_total'];
						array_push($arrayDatos, $total);
					}
				}
				$datasets .= '{label: "'.$sucursal['s_name'].'", backgroundColor: "'.$color.'", borderColor: "'.$color.'", borderWidth: 1, data: ['.implode(", ", $arrayDatos).']},';
			}
		 ?>

		var barChartData = {
			labels: [
				'Enero', 
				'Febrero', 
				'Marzo', 
				'Abril', 
				'Mayo', 
				'Junio', 
				'Julio',
				'Agosto',
				'Septiembre',
				'Octubre',
				'Noviembre',
				'Diciembre'
				],
			datasets: [/*{
				label: 'Datos <?php echo $anio; ?>',
				backgroundColor: '#9b59b6',
				borderColor: '#000000',
				borderWidth: 1,
				data: [<?php echo implode(", ", $arrayDatos); ?>]
			}*/
			<?php echo $datasets; ?>]

		};



		var canvas = document.getElementById('grafico').getContext('2d');
		window.bar = new Chart(canvas, {
			type: "bar",
			data: barChartData,
			options: {
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data){
							return "$"+(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]).toLocaleString('es-ar', {
								minimumFractionDigits: 2,
  								maximumFractionDigits: 2
							});
						}
					}
				},
				elements: {
					rectangle: {
						borderWidth: 1,
						borderColor: "rgb(0,255,0)",
						borderSkipped: 'bottom'
					}
				},
				responsive: true,
				title: {
					display: true,
					text: "Reporte Anual."
				},
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							callback: function(value, index, values){
								return '$' +  value.toLocaleString();
							}
						}
					}]
				}
			}
		});
	});
</script>