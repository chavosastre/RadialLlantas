<?php
    include("abrir_conexion.php");
    //Hacemos la consulta de los id y api keys
    $resultado = $conexion->query("SELECT SiteId, ApiKey, Nombre FROM `consumos` where ClaveEmpresa = 'radial'");
    for ($num_fila = $resultado->num_rows - 1; $num_fila >= o; $num_fila--)
    {
        $resultado->data_seek($num_fila);
        $fila = $resultado->fetch_assoc();
        $url =  "https://monitoringapi.solaredge.com/site/".$fila['SiteId']."/energyDetails?meters=Production&timeUnit=MONTH&startTime=2019-03-01%2011:00:00&endTime=2019-03-30%2013:00:00&api_key=".$fila['ApiKey']."";
        $json = file_get_contents($url);
        $obj = json_decode($json, true);

        // echo($url."</br>");
        $Production =  $obj['energyDetails']['meters'][0]['values'][0]['value'];
        echo ($fila['Nombre']."->".$Production."</br>");
    }
?>