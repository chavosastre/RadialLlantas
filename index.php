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

<?php
/* 
* days_in_month($month, $year) 
* Returns the number of days in a given month and year, taking into account leap years. 
* 
* $month: numeric month (integers 1-12) 
* $year: numeric year (any integer) 
* 
* Prec: $month is an integer between 1 and 12, inclusive, and $year is an integer. 
* Post: none 
*/
// corrected by ben at sparkyb dot net 
function days_in_month($month, $year)
{
	// calculate number of days in a month 
	return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
}
?>

<body>
	<!-- Page Content -->
	<?php
	$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre");
	$mes = date("m");
	$year = date("Y");
	$mes = $mes - 1;
	if ($mes < 10) {
		$mes1 = "0" . $mes;
	}

	if ($mes == 0) {
		$mes1 = 12;
		$mes = 12;
		$year = $year - 1;
	}

	$diasMes = days_in_month($mes, $year);
	// echo ("</br> mes ". $mes1);
	?>
	<div class="container-fluid">
		<div class="container text-center">
			<img class="logotipo" src="img/radial-logo.png" alt="">
			<p class="titulo">Comparativa de Costos</p>
		</div>

		<?php
		include("abrir_conexion.php");

		//Hacemos la consulta de los id y api keys
		$resultado = $conexion->query("SELECT SiteId, ApiKey, Nombre FROM `sucursales_radial` where ClaveEmpresa = 'radial'");
		for ($num_fila = $resultado->num_rows - 1; $num_fila >= 0; $num_fila--) {
			//echo ("SELECT * FROM produccion_radial where Fecha = '" . $year . "-" . $mes1 . "-01'");
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			$url =  "https://monitoringapi.solaredge.com/site/" . $fila['SiteId'] . "/energyDetails?meters=Production&timeUnit=MONTH&startTime=" . $year . "-" . $mes . "-01%2011:00:00&endTime=" . $year . "-" . $mes . "-" . $diasMes . "%2013:00:00&api_key=" . $fila['ApiKey'] . "";
			$json = file_get_contents($url);
			$obj = json_decode($json, true);

			$Production[$num_fila] =  round($obj['energyDetails']['meters'][0]['values'][0]['value'] / 1000);

			$resultado2 = $conexion->query("SELECT * FROM produccion_radial where Fecha = '" . $year . "-" . $mes1 . "-01' and IdSucursal = '" . $fila['SiteId'] . "'");
			$num_fila2 = $resultado2->num_rows;
			// echo ("</br>SELECT * FROM produccion_radial where Fecha = '" . $year . "-" . $mes1 . "-01' and IdSucursal = '" .$fila['SiteId']. "'</br>");
			if ($num_fila2 == 0) {
				$conexion->query("Insert into produccion_radial (IdSucursal, Consumo, Fecha) values ('" . $fila['SiteId'] . "', '" . $Production[$num_fila] . "', '" . $year . "-" . $mes1 . "-01')");
				//  echo ("</br>Insert into produccion_radial (IdSucursal, Consumo, Fecha) values ('".$fila['SiteId']."', '".$Production[$num_fila]."', '".$year."-".$mes1."-01')</br>");
			} else {
				// nada que hacer
			}
			// echo ($fila['SiteId']."->".$fila['Nombre']."->".$Production[$num_fila]."</br>");
		}

		$kwQROO = 3.988;
		$iva = 1.16;
		// Consultas de los valores 
		// echo ("select Consumo, IdSucursal from produccion_radial where Fecha >= '".($year - 1)."-".($mes + 1)."-01' and Fecha <= '".($year)."-".($mes)."-01' ORDER BY Fecha, IdSucursal ASC");
		$result = $conexion->query("select Consumo, IdSucursal, Fecha from produccion_radial where Fecha >= '" . ($year - 1) . "-" . ($mes + 1) . "-01' and Fecha <= '" . ($year) . "-" . ($mes) . "-01' ORDER BY Fecha, IdSucursal ASC");
		$totalresult = $result->num_rows;
		// echo("</br>".$totalresult);
		// for ($x = 1; $x < $totalresult; $x++) {
		// 	// echo("</br> entra al for </br>");
		// 	$result->data_seek($x);
		// 	$miFila = $result->fetch_assoc();
		// 	$valorProduccion[$x - 1] = $miFila['Consumo'];
		// 	echo ("</br>" .$x." - ". $valorProduccion[$x - 1]);
		// }
		$totalresult = $totalresult - 1;
		for (($totalresult); $totalresult >= 0; $totalresult--) {
			$result->data_seek($totalresult);
			$miFila = $result->fetch_assoc();
			$valorProduccion[$totalresult] = $miFila['Consumo'];
			// echo ("</br>" .$totalresult." - ". $valorProduccion[$totalresult]."  Sucursal : ".$miFila['IdSucursal']. " Fecha: ".$miFila['Fecha']);
		}
		?>

		<div class="col-lg-12 col-md-12 col-sm-12 espaciotop">
			<table class="table table-hover centrar">
				<thead>
					<tr>
						<th class="borde" colspan="4"><?php echo $meses[$mes] ?></th>
						<th class="borde" colspan="3"><?php echo $meses[$mes + 1] ?></th>
						<th class="borde" colspan="3"><?php echo $meses[$mes + 2] ?></th>
						<th colspan="3"><?php echo $meses[$mes + 3] ?></th>
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
						<td><?php echo round($valorProduccion[3]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[3] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php
							echo number_format((($valorProduccion[3] * $kwQROO) - ($valorProduccion[3] * $kwQROO * .15)) * 1.16);
							?>
						</td>
						<td><?php echo round($valorProduccion[8]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[8] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[8] * $kwQROO) - ($valorProduccion[8] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[13]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[13] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[13] * $kwQROO) - ($valorProduccion[13] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[18]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[18] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[18] * $kwQROO) - ($valorProduccion[18] * $kwQROO * .15)) * 1.16); ?></td>
					</tr>
					<tr>
						<td>Av. Patria</td>
						<td><?php echo round($valorProduccion[4]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[4] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[4] * $kwQROO) - ($valorProduccion[4] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[9]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[9] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[9] * $kwQROO) - ($valorProduccion[9] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[14]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[14] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[14] * $kwQROO) - ($valorProduccion[14] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[19]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[19] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[19] * $kwQROO) - ($valorProduccion[19] * $kwQROO * .15)) * 1.16); ?></td>
					</tr>
					<tr>
						<td>Tránsito</td>
						<td><?php echo round($valorProduccion[2]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[2] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[2] * $kwQROO) - ($valorProduccion[2] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[7]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[7] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[7] * $kwQROO) - ($valorProduccion[7] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[12]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[12] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[12] * $kwQROO) - ($valorProduccion[12] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[17]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[17] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[17] * $kwQROO) - ($valorProduccion[17] * $kwQROO * .15)) * 1.16); ?></td>
					</tr>
					<tr>
						<td>Fco. de Montejo</td>
						<td><?php echo round($valorProduccion[1]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[1] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[1] * $kwQROO) - ($valorProduccion[1] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[6]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[6] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[6] * $kwQROO) - ($valorProduccion[6] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[11]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[11] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[11] * $kwQROO) - ($valorProduccion[11] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[16]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[16] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[16] * $kwQROO) - ($valorProduccion[16] * $kwQROO * .15)) * 1.16); ?></td>
					</tr>
					<tr>
						<td>Playa del Carmen</td>
						<td><?php echo round($valorProduccion[0]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[0] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[0] * $kwQROO) - ($valorProduccion[0] * $kwQROO * .15)) * 1.16); 
								$ahorroPlaya[1] = ($valorProduccion[0] * $kwQROO * $iva) - ((($valorProduccion[0] * $kwQROO) - ($valorProduccion[0] * $kwQROO * .15)) * 1.16);
								$ahorroMes[1] = $ahorroMes[1] + $ahorroPlaya[1];
							?>	
						</td>
						<td><?php echo round($valorProduccion[5]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[5] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[5] * $kwQROO) - ($valorProduccion[5] * $kwQROO * .15)) * 1.16); 
								$ahorroPlaya[2] = ($valorProduccion[5] * $kwQROO * $iva) - ((($valorProduccion[5] * $kwQROO) - ($valorProduccion[5] * $kwQROO * .15)) * 1.16);
								$ahorroMes[2] = $ahorroMes[2] + $ahorroPlaya[2];
							?>	
						</td>
						<td><?php echo round($valorProduccion[10]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[10] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[10] * $kwQROO) - ($valorProduccion[10] * $kwQROO * .15)) * 1.16); 
								$ahorroPlaya[3] = ($valorProduccion[10] * $kwQROO * $iva) - ((($valorProduccion[10] * $kwQROO) - ($valorProduccion[10] * $kwQROO * .15)) * 1.16);
								$ahorroMes[3] = $ahorroMes[3] + $ahorroPlaya[3];
							?>	
						</td>
						<td><?php echo round($valorProduccion[15]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[15] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[15] * $kwQROO) - ($valorProduccion[15] * $kwQROO * .15)) * 1.16); 
								$ahorroPlaya[4] = ($valorProduccion[15] * $kwQROO * $iva) - ((($valorProduccion[15] * $kwQROO) - ($valorProduccion[15] * $kwQROO * .15)) * 1.16);
								$ahorroMes[4] = $ahorroMes[4] + $ahorroPlaya[4];
							?>	
						</td>
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
						<th class="borde" colspan="4"><?php echo $meses[$mes + 4] ?></th>
						<th class="borde" colspan="3"><?php echo $meses[$mes + 5] ?></th>
						<th class="borde" colspan="3"><?php echo $meses[$mes + 6] ?></th>
						<th colspan="3"><?php echo $meses[$mes + 7] ?></th>
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
						<td><?php echo round($valorProduccion[23]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[23] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[23] * $kwQROO) - ($valorProduccion[23] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[28]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[28] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[28] * $kwQROO) - ($valorProduccion[28] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[33]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[33] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[33] * $kwQROO) - ($valorProduccion[33] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[38]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[38] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[38] * $kwQROO) - ($valorProduccion[38] * $kwQROO * .15)) * 1.16); ?></td>
					</tr>
					<tr>
						<td>Av. Patria</td>
						<td><?php echo round($valorProduccion[24]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[24] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[24] * $kwQROO) - ($valorProduccion[24] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[29]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[29] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[29] * $kwQROO) - ($valorProduccion[29] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[34]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[34] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[34] * $kwQROO) - ($valorProduccion[34] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[39]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[39] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[39] * $kwQROO) - ($valorProduccion[39] * $kwQROO * .15)) * 1.16); ?></td>
					</tr>
					<tr>
						<td>Tránsito</td>
						<td><?php echo round($valorProduccion[22]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[22] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[22] * $kwQROO) - ($valorProduccion[22] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[27]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[27] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[27] * $kwQROO) - ($valorProduccion[27] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[32]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[32] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[32] * $kwQROO) - ($valorProduccion[32] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[37]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[37] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[37] * $kwQROO) - ($valorProduccion[37] * $kwQROO * .15)) * 1.16); ?></td>
					</tr>
					<tr>
						<td>Fco. de Montejo</td>
						<td><?php echo round($valorProduccion[21]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[21] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[21] * $kwQROO) - ($valorProduccion[21] * $kwQROO * .15)) * 1.16); 
								$ahorroMontejo[5] = ($valorProduccion[21] * $kwQROO * $iva) - ((($valorProduccion[21] * $kwQROO) - ($valorProduccion[21] * $kwQROO * .15)) * 1.16);
								$ahorroMes[5] = $ahorroMes[5] + $ahorroMontejo[5];
							?>
						</td>
						<td><?php echo round($valorProduccion[26]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[26] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[26] * $kwQROO) - ($valorProduccion[26] * $kwQROO * .15)) * 1.16); 
								$ahorroMontejo[6] = ($valorProduccion[26] * $kwQROO * $iva) - ((($valorProduccion[26] * $kwQROO) - ($valorProduccion[26] * $kwQROO * .15)) * 1.16);
								$ahorroMes[6] = $ahorroMes[6] + $ahorroMontejo[6];
							?>
						</td>
						<td><?php echo round($valorProduccion[31]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[31] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[31] * $kwQROO) - ($valorProduccion[31] * $kwQROO * .15)) * 1.16); 
								$ahorroMontejo[7] = ($valorProduccion[31] * $kwQROO * $iva) - ((($valorProduccion[31] * $kwQROO) - ($valorProduccion[31] * $kwQROO * .15)) * 1.16);
								$ahorroMes[7] = $ahorroMes[7] + $ahorroMontejo[7];
							?>
						</td>
						<td><?php echo round($valorProduccion[36]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[36] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[36] * $kwQROO) - ($valorProduccion[36] * $kwQROO * .15)) * 1.16); 
								$ahorroMontejo[8] = ($valorProduccion[36] * $kwQROO * $iva) - ((($valorProduccion[36] * $kwQROO) - ($valorProduccion[36] * $kwQROO * .15)) * 1.16);
								$ahorroMes[8] = $ahorroMes[8] + $ahorroMontejo[8];
							?>
						</td>
					</tr>
					<tr>
						<td>Playa del Carmen</td>
						<td><?php echo round($valorProduccion[20]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[20] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[20] * $kwQROO) - ($valorProduccion[20] * $kwQROO * .15)) * 1.16); 
								$ahorroPlaya[5] = ($valorProduccion[25] * $kwQROO * $iva) - ((($valorProduccion[25] * $kwQROO) - ($valorProduccion[25] * $kwQROO * .15)) * 1.16);
								$ahorroMes[5] = $ahorroMes[5] + $ahorroPlaya[5];
							?>						
						</td>
						<td><?php echo round($valorProduccion[25]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[25] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[25] * $kwQROO) - ($valorProduccion[25] * $kwQROO * .15)) * 1.16); 
								$ahorroPlaya[6] = ($valorProduccion[25] * $kwQROO * $iva) - ((($valorProduccion[25] * $kwQROO) - ($valorProduccion[25] * $kwQROO * .15)) * 1.16);
								$ahorroMes[6] = $ahorroMes[6] + $ahorroPlaya[6];

							?>						</td>
						<td><?php echo round($valorProduccion[30]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[30] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[30] * $kwQROO) - ($valorProduccion[30] * $kwQROO * .15)) * 1.16); 
								$ahorroPlaya[7] = ($valorProduccion[30] * $kwQROO * $iva) - ((($valorProduccion[30] * $kwQROO) - ($valorProduccion[30] * $kwQROO * .15)) * 1.16);
								$ahorroMes[7] = $ahorroMes[7] + $ahorroPlaya[7];
							?>
						</td>
						<td><?php echo round($valorProduccion[35]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[35] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[35] * $kwQROO) - ($valorProduccion[35] * $kwQROO * .15)) * 1.16); 
								$ahorroPlaya[8] = ($valorProduccion[35] * $kwQROO * $iva) - ((($valorProduccion[35] * $kwQROO) - ($valorProduccion[35] * $kwQROO * .15)) * 1.16);
								$ahorroMes[8] = $ahorroMes[8] + $ahorroPlaya[8];
							?>
						</td>
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
						<th class="borde" colspan="4"><?php echo $meses[$mes + 8] ?></th>
						<th class="borde" colspan="3"><?php echo $meses[$mes + 9] ?></th>
						<th class="borde" colspan="3"><?php echo $meses[$mes + 10] ?></th>
						<th colspan="3"><?php echo $meses[$mes + 11] ?></th>
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
						<td><?php echo round($valorProduccion[43]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[43] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[43] * $kwQROO) - ($valorProduccion[43] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[48]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[48] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[48] * $kwQROO) - ($valorProduccion[48] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[53]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[53] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[53] * $kwQROO) - ($valorProduccion[53] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[58]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[58] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[58] * $kwQROO) - ($valorProduccion[58] * $kwQROO * .15)) * 1.16); ?></td>
					</tr>
					<tr>
						<td>Av. Patria</td>
						<td><?php echo round($valorProduccion[44]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[44] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[44] * $kwQROO) - ($valorProduccion[44] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[49]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[49] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[49] * $kwQROO) - ($valorProduccion[49] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[54]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[54] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[54] * $kwQROO) - ($valorProduccion[54] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[59]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[59] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[59] * $kwQROO) - ($valorProduccion[59] * $kwQROO * .15)) * 1.16); ?></td>
					</tr>
					<tr>
						<td>Tránsito</td>
						<td><?php echo round($valorProduccion[42]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[42] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[42] * $kwQROO) - ($valorProduccion[42] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[47]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[47] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[47] * $kwQROO) - ($valorProduccion[47] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[52]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[52] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[52] * $kwQROO) - ($valorProduccion[52] * $kwQROO * .15)) * 1.16); ?></td>
						<td><?php echo round($valorProduccion[57]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[57] * $kwQROO * $iva, 2)) ?></td>
						<td>$<?php echo number_format((($valorProduccion[57] * $kwQROO) - ($valorProduccion[57] * $kwQROO * .15)) * 1.16); ?></td>
					</tr>
					<tr>
						<td>Fco. de Montejo</td>
						<td><?php echo round($valorProduccion[41]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[41] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[41] * $kwQROO) - ($valorProduccion[41] * $kwQROO * .15)) * 1.16); 
								$ahorroMontejo[9] = ($valorProduccion[41] * $kwQROO * $iva) - ((($valorProduccion[41] * $kwQROO) - ($valorProduccion[41] * $kwQROO * .15)) * 1.16);
								$ahorroMes[9] = $ahorroMes[9] + $ahorroMontejo[9];
							?>							</td>
						<td><?php echo round($valorProduccion[46]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[46] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[46] * $kwQROO) - ($valorProduccion[46] * $kwQROO * .15)) * 1.16); 
								$ahorroMontejo[10] = ($valorProduccion[46] * $kwQROO * $iva) - ((($valorProduccion[46] * $kwQROO) - ($valorProduccion[46] * $kwQROO * .15)) * 1.16);
								$ahorroMes[10] = $ahorroMes[10] + $ahorroMontejo[10];
							?>						</td>
						<td><?php echo round($valorProduccion[51]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[51] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[51] * $kwQROO) - ($valorProduccion[51] * $kwQROO * .15)) * 1.16); 
								$ahorroMontejo[11] = ($valorProduccion[51] * $kwQROO * $iva) - ((($valorProduccion[51] * $kwQROO) - ($valorProduccion[51] * $kwQROO * .15)) * 1.16);
								$ahorroMes[11] = $ahorroMes[11] + $ahorroMontejo[11];
							?>
						</td>						
						<td><?php echo round($valorProduccion[56]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[56] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[56] * $kwQROO) - ($valorProduccion[56] * $kwQROO * .15)) * 1.16); 
								$ahorroMontejo[12] = ($valorProduccion[56] * $kwQROO * $iva) - ((($valorProduccion[56] * $kwQROO) - ($valorProduccion[56] * $kwQROO * .15)) * 1.16);
								$ahorroMes[12] = $ahorroMes[12] + $ahorroMontejo[12];
							?>
						</td>
					</tr>
					<tr>
						<td>Playa del Carmen</td>
						<td><?php echo round($valorProduccion[40]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[40] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[40] * $kwQROO) - ($valorProduccion[40] * $kwQROO * .15)) * 1.16); 
								$ahorroPlaya[9] = ($valorProduccion[40] * $kwQROO * $iva) - ((($valorProduccion[40] * $kwQROO) - ($valorProduccion[40] * $kwQROO * .15)) * 1.16);
								$ahorroMes[9] = $ahorroMes[9] + $ahorroPlaya[9];
							?>
						</td>
						<td><?php echo round($valorProduccion[45]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[45] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[45] * $kwQROO) - ($valorProduccion[45] * $kwQROO * .15)) * 1.16);
								$ahorroPlaya[10] = ($valorProduccion[45] * $kwQROO * $iva) - ((($valorProduccion[45] * $kwQROO) - ($valorProduccion[45] * $kwQROO * .15)) * 1.16);
								$ahorroMes[10] = $ahorroMes[10] + $ahorroPlaya[10];
							?>
						</td>
						<td><?php echo round($valorProduccion[50]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[50] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php 
								echo number_format((($valorProduccion[50] * $kwQROO) - ($valorProduccion[50] * $kwQROO * .15)) * 1.16); 
								$ahorroPlaya[11] = ($valorProduccion[50] * $kwQROO * $iva) - ((($valorProduccion[50] * $kwQROO) - ($valorProduccion[50] * $kwQROO * .15)) * 1.16);
								$ahorroMes[11] = $ahorroMes[11] + $ahorroPlaya[11];
							?>
						</td>
						<td><?php echo round($valorProduccion[55]) ?></td>
						<td>$<?php echo number_format(round($valorProduccion[55] * $kwQROO * $iva, 2)) ?></td>
						<td>$
							<?php
								echo number_format((($valorProduccion[55] * $kwQROO) - ($valorProduccion[55] * $kwQROO * .15)) * 1.16);
								$ahorroPlaya[12] = ($valorProduccion[55] * $kwQROO * $iva) - ((($valorProduccion[55] * $kwQROO) - ($valorProduccion[55] * $kwQROO * .15)) * 1.16);
								$ahorroMes[12] = $ahorroMes[12] + $ahorroPlaya[12];
								// echo("</br>".number_format(round($ahorroMes12."</br>"), 2)) ;
							?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div><!-- /.container -->

	<?php
		for ($z = 1; $z <= 12; $z++)
		{
			$ahorroTotal = $ahorroTotal + $ahorroMes[$z];
			// echo ("</br>". $ahorroTotal);
		}
	?>
	
	<!-- Tabla de Ahorro -->
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
						for ($i = $mes + 1; $i <= $mes + 12; $i++) {
							echo ("<th>" . $meses[$i - 1] . "</th>");
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
						$x = 1;
						for ($i = $mes; $i <= $mes + 11; $i++) {
							echo ("<td>$ " . number_format($ahorroMontejo[$x]) . "</td>");
							$x++;
						}
						?>
					</tr>
					<tr>
						<td>Playa del Carmen</td>
						<?php
						$x = 1;
						for ($i = $mes; $i <= $mes + 11; $i++) {
							echo ("<td>$ " . number_format($ahorroPlaya[$x]) . "</td>");
							$x++;
						}
						?>
					</tr>
					<tr>
					<td>Totales</td>
					<?php
						$x = 1;
						for ($i = $mes + 1; $i <= $mes + 12; $i++) {
							echo ("<td>$ " . number_format($ahorroMes[$x]) . "</td>");
							$x++;
						}
					?>
					<td>$<?php echo number_format($ahorroTotal); ?></td>
					</tr>
				</tbody>
			</table>
		</div>

	</div> <!-- /.container -->

	<!-- Inicia gráfica -->
	<div class="container espaciotop" width="400" height="400">
		<canvas id="myChart" width="200"></canvas>

		<script>
			var actual = '<?php echo round($ahorroTotal);?>'
			var ctx = document.getElementById('myChart');
			Chart.defaults.global.defaultFontColor = 'white';
			Chart.defaults.global.defaultFontSize = 16;
			var myChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: ['', 'Proyectado', 'Actual', ''],
					datasets: [{
						label: '',
						data: [0, 52497, actual, 0],
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