<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Radial Llantas</title>
	<!-- Bootstrap core CSS-->
	<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.js"></script>

</head>

<body>
	<!-- Page Content -->
	<?php
	$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre");
	$mes = date("n");
	?>
	<div class="container-fluid">
		<div class="container text-center">
			<img class="logotipo" src="img/radial-logo.png" alt="">
			<p class="titulo">Comparativa de Costos</p>
		</div>

		<div class="col-lg-12 col-md-12 col-sm-12">
			<table class="table table-hover centrar">
				<thead>
					<tr>
						<th class="borde" colspan="4"><?php echo $meses[$mes - 1] ?></th>
						<th class="borde" colspan="3"><?php echo $meses[$mes] ?></th>
						<th class="borde" colspan="3"><?php echo $meses[$mes + 1] ?></th>
						<th colspan="3"><?php echo $meses[$mes + 2] ?></th>
					</tr>
				</thead>
				<thead>
					<tr>
						<th></th>
						<th>Generación</th>
						<th>Precio CFE</th>
						<th class="borde">Precio EC</th>
						<th>Generación</th>
						<th>Precio CFE</th>
						<th class="borde">Precio EC</th>
						<th>Generación</th>
						<th>Precio CFE</th>
						<th class="borde">Precio EC</th>
						<th>Generación</th>
						<th>Precio CFE</th>
						<th>Precio EC</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Edwiges</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
					</tr>
					<tr>
						<td>Av. Patria</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
					</tr>
					<tr>
						<td>Tránsito</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
					</tr>
					<tr>
						<td>Fco. de Montejo</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
					</tr>
					<tr>
						<td>Playa del Carmen</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
					</tr>
				</tbody>
			</table>
		</div>

	</div> <!-- /.container -->

	<div class="container-fluid">
		<div class="col-lg-12 col-md-12 col-sm-12 espaciotop">
			<table class="table table-hover centrar">
				<thead>
					<tr>
						<th class="borde" colspan="4"><?php echo $meses[$mes + 3] ?></th>
						<th class="borde" colspan="3"><?php echo $meses[$mes + 4] ?></th>
						<th class="borde" colspan="3"><?php echo $meses[$mes + 5] ?></th>
						<th colspan="3"><?php echo $meses[$mes + 6] ?></th>
					</tr>
				</thead>
				<thead>
					<tr>
						<th></th>
						<th>Generación</th>
						<th>Precio CFE</th>
						<th class="borde">Precio EC</th>
						<th>Generación</th>
						<th>Precio CFE</th>
						<th class="borde">Precio EC</th>
						<th>Generación</th>
						<th>Precio CFE</th>
						<th class="borde">Precio EC</th>
						<th>Generación</th>
						<th>Precio CFE</th>
						<th>Precio EC</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Edwiges</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
					</tr>
					<tr>
						<td>Av. Patria</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
					</tr>
					<tr>
						<td>Tránsito</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
					</tr>
					<tr>
						<td>Fco. de Montejo</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
					</tr>
					<tr>
						<td>Playa del Carmen</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div><!-- /.container -->

	<div class="container-fluid">
		<div class="col-lg-12 col-md-12 col-sm-12 espaciotop">
			<table class="table table-hover centrar">
				<thead>
					<tr>
						<th class="borde" colspan="4"><?php echo $meses[$mes + 7] ?></th>
						<th class="borde" colspan="3"><?php echo $meses[$mes + 8] ?></th>
						<th class="borde" colspan="3"><?php echo $meses[$mes + 9] ?></th>
						<th colspan="3"><?php echo $meses[$mes + 10] ?></th>
					</tr>
				</thead>
				<thead>
					<tr>
						<th></th>
						<th>Generación</th>
						<th>Precio CFE</th>
						<th class="borde">Precio EC</th>
						<th>Generación</th>
						<th>Precio CFE</th>
						<th class="borde">Precio EC</th>
						<th>Generación</th>
						<th>Precio CFE</th>
						<th class="borde">Precio EC</th>
						<th>Generación</th>
						<th>Precio CFE</th>
						<th>Precio EC</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Edwiges</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
					</tr>
					<tr>
						<td>Av. Patria</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
					</tr>
					<tr>
						<td>Tránsito</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
					</tr>
					<tr>
						<td>Fco. de Montejo</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
					</tr>
					<tr>
						<td>Playa del Carmen</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
						<td>01</td>
						<td>02</td>
						<td>03</td>
						<td>04</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div><!-- /.container -->

	<div class="container-fluid espaciotop">
		<div class="container text-center">
			<p class="titulo">Ahorro</p>
		</div>

		<div class="col-lg-12 col-md-12 col-sm-12">
			<table class="table table-hover centrar">
				<thead>
					<tr>
						<th></th>
						<?php
						for ($i = $mes; $i <= $mes + 11; $i++) {
							echo ("<th>" . $meses[$i-1] . "</th>");
						}
						?>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Edwiges</td>
						<?php
						$number = 1234;
						for ($i = $mes; $i <= $mes + 11; $i++) {
							echo ("<td>$ " . number_format($number + $mes) . "</td>");
						}
						?>
					</tr>
					<tr>
						<td>Av. Patria</td>
						<?php
						$number = 1234;
						for ($i = $mes; $i <= $mes + 11; $i++) {
							echo ("<td>$ " . number_format($number + $mes) . "</td>");
						}
						?>
					</tr>
					<tr>
						<td>Tránsito</td>
						<?php
						$number = 1234;
						for ($i = $mes; $i <= $mes + 11; $i++) {
							echo ("<td>$ " . number_format($number + $mes) . "</td>");
						}
						?>
					</tr>
					<tr>
						<td>Fco. de Montejo</td>
						<?php
						$number = 1234;
						for ($i = $mes; $i <= $mes + 11; $i++) {
							echo ("<td>$ " . number_format($number + $mes) . "</td>");
						}
						?>
					</tr>
					<tr>
						<td>Playa del Carmen</td>
						<?php
						$number = 1234;
						for ($i = $mes; $i <= $mes + 11; $i++) {
							echo ("<td>$ " . number_format($number + $mes) . "</td>");
						}
						?>
					</tr>
					<tr>
						<td></td>
						<?php
						$number = 1234;
						for ($i = $mes; $i <= $mes + 11; $i++) {
							echo ("<td>$ " . number_format($number + $mes + 100) . "</td>");
						}
						?>
						<td>totales</td>
					</tr>
				</tbody>
			</table>
		</div>

	</div> <!-- /.container -->
<!-- Inicia gráfica -->
	<div class="container espaciotop" width="400" height="400">
		<canvas id="myChart" width="200"></canvas>

		<script>
			var ctx = document.getElementById('myChart');
			Chart.defaults.global.defaultFontColor = 'white';
			Chart.defaults.global.defaultFontSize = 16;
			var myChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: ['', 'Proyectado', 'Actual', ''],
					datasets: [{
						label: '',
						data: [0, 52497, 15198, 0],
						backgroundColor: [
							'#green',
							'#FFDE00',
							'#F45131'
						]
					}]
				},
				options: {
					title: {
						display: true,
						text: 'Ahorro',
						fontSize: 40,
					},
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero: true
							}
						}]
					},
					legend: {
            display: false,
            
        }
				}
			});
		</script>
	</div> <!-- /.container de la gráfica-->



	<!-- Footer -->
	<footer class="py-5">
		<div class="row">
			<div class="col-5">
				<!-- nada aquí -->
			</div>
			<div class="col-2 text-center">
				<div class="text-center">
					<img class="logotipo" src="img/Logotipolargo-01.png">
				</div>
			</div>
			<div class="col-5">
				<!-- nada aquí -->
			</div>
		</div>
	</footer>
</body>

</html>