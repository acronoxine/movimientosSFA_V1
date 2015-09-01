<?
if(!isset($_POST["anio"]))
	$anio = date("Y");
else
	$anio = $_POST["anio"];

$sql = "Select idquincenas from cat_quincenas where year(fhasta) = '$anio'";
$res = mysql_query($sql, $conexion);

if(mysql_num_rows($res) == 0)
{	
	$quincena = 0;
	
	for($i = 1; $i <= 12; $i++){
		
		$mes = str_pad($i, 2, "0", STR_PAD_LEFT);  
		
		$quincena++;
		
		$afechaini = $anio . "/" . $mes . "/01";
		$afechafin = $anio . "/" . $mes . "/15";
		
		$sql = "insert into cat_quincenas(nomina, quincena, fdesde, fhasta, mes)";
		$sql .= " values('Q', $quincena, '$afechaini', '$afechafin', $mes)";
		mysql_query($sql, $conexion);
		
		$quincena++;
		
		$bfechaini = $anio . "/" . $mes . "/16";
		$bfechafin = $anio . "/" . $mes . "/" . date("t", mktime(0, 0, 0, $mes, 1, $anio));
		
		$sql = "insert into cat_quincenas(nomina, quincena, fdesde, fhasta, mes)";
		$sql .= " values('Q', $quincena, '$bfechaini', '$bfechafin', $mes)";
		mysql_query($sql, $conexion);
	}
}


?>