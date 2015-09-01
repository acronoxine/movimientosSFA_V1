<?
session_start();
if($_SESSION["m_sesion"] != 1)
{
	echo "<script>";
	echo "location.replace('index.php');";
	echo "</script>";
	exit();
}
?>
<?
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);

$sql = "select idnominaemp, nombre, ncuenta, sueldobase, compensacion, prima, aguinaldo, otrasper, isr, imss, otrasded,"; 
$sql .= " (sueldobase + compensacion + prima + aguinaldo + otrasper) - (isr + imss + otrasded) as neto from";
$sql .= " (select idnominaemp, nombre, sueldobase,"; 
$sql .= " sum(case when cve_concepto = '114' then importe else 0 end) as compensacion,";
$sql .= " sum(case when cve_concepto = '119' then importe else 0 end) as prima,";
$sql .= " sum(case when cve_concepto = '115' then importe else 0 end) as aguinaldo,";
$sql .= " sum(case when cve_concepto not in ('114', '119', '115', '101') and tipo = 'P' then importe else 0 end) as otrasper,";
$sql .= " sum(case when cve_concepto = '251' then importe else 0 end) as isr,";
$sql .= " sum(case when cve_concepto = '274' then importe else 0 end) as imss,";
$sql .= " sum(case when cve_concepto not in ('251', '274') and tipo = 'D' then importe else 0 end) as otrasded,";
$sql .= " ncuenta";
$sql .= " from";
$sql .= " (select ncuenta, e.idnominaemp, concat(e.paterno, ' ', e.materno, ' ', e.nombres) as nombre, e.sueldobase, p.cve_concepto, c.descripcion as concepto, p.tipo, p.importe"; 
$sql .= " from nominapago p";
$sql .= " left join nominaemp e on p.idnominaemp = e.idnominaemp";
$sql .= " left join cat_conceptos c on p.cve_concepto = c.clave";

if($_GET["ur"] == -1)
	$sql .= " where quincena = '$_GET[quincena]' and anio = '" . date("Y") . "' and e.activo = '1') x group by nombre, sueldobase) y";
else
	$sql .= " where quincena = '$_GET[quincena]' and anio = '" . date("Y") . "' and idarea = '$_GET[ur]' and e.activo = '1') x group by nombre, sueldobase) y";
$res = mysql_query($sql, $conexion);

?>

<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">

<link rel="stylesheet" type="text/css" href="css/estilos.css">

</head>

<body>

<?

$num = mysql_num_rows($res);
$num = str_pad($num, 5, "0", STR_PAD_LEFT);

$lineas = "";
$importetotal = 0;
while($ren = mysql_fetch_array($res))
{
	$ntarjeta = str_pad($ren["ncuenta"], 16, "0", STR_PAD_LEFT);
	$ncuenta = str_pad($ren["ncuenta"], 9, "0", STR_PAD_LEFT);
	$importeneto = str_pad(number_format($ren["neto"], 2, ".", ""), 13, "0", STR_PAD_LEFT);
	
	$lineas .= "$ntarjeta$ncuenta$importeneto$ren[idnominaemp]$ren[nombre]" . PHP_EOL;
	
	$importetotal += $ren["neto"];
}

$constantec = "C";
$ncliente = str_pad("", 9, "0", STR_PAD_LEFT);
$divisionreg = "00";
$fechaaplica = date("dmY");
$totalregistros = $num;
$importetot = str_pad(number_format($importetotal, 2, ".", ""), 16, "0", STR_PAD_LEFT);
$cuentacliente = str_pad("", 9, "0", STR_PAD_LEFT);

$sql = "Select razonsocial from empresa limit 1";
$res = mysql_query($sql, $conexion);
$ren = mysql_fetch_array($res);
mysql_free_result($res);

$nombreempresa = $ren["razonsocial"];

$lineaprimera = "$constantec$ncliente$divisionreg$fechaaplica$totalregistros$importetot$cuentacliente$nombreempresa". PHP_EOL;

$pt = fopen("layouts/emision_$_GET[quincena]_" . date("Y") . ".txt", "w+");

fputs($pt, $lineaprimera);
fputs($pt, $lineas);


$zip = new ZipArchive();

$filename = "layouts/emision_$_GET[quincena]_" . date("Y") . ".zip";

if($zip->open($filename,ZIPARCHIVE::CREATE)===true) {
        $zip->addFile("layouts/emision_$_GET[quincena]_" . date("Y") . ".txt");
        $zip->close();
}
else {
        echo 'Error creando '.$filename;
}

?>

<center>
<br>
<div class="boton3" onClick="location.href='<? echo "layouts/emision_$_GET[quincena]_" . date("Y") . ".zip"; ?>';">
<? echo "emision_$_GET[quincena]_" . date("Y") . ".zip"; ?>
</div>
</center>
</body>
</html>