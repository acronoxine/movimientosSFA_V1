<?php
require_once('Connections/conexion.php'); 
include 'funcionesJL.php';
mysql_select_db($database_conexion, $conexion);
$query_empleado="SELECT DISTINCT rfc_iniciales,rfc_fechanac,rfc_homoclave,
						CONCAT(IFNULL(paterno, ''),' ',IFNULL(materno, ''),' ',IFNULL(nombres, '')) AS nombreAU FROM nominaemp ORDER BY nombreAU";
$empleado = mysql_query($query_empleado, $conexion) or die(mysql_error());
while($row_empleado = mysql_fetch_assoc($empleado)){
	$nombresAU[]=utf8_encode($row_empleado['nombreAU']);
	$RFC[]=$row_empleado['rfc_iniciales']."-".$row_empleado['rfc_fechanac']."-".$row_empleado['rfc_homoclave'];
}

$texto = strtolower(trim($_POST["nombreUS"]));

$sugerencias = array();
foreach($nombresAU as $indice => $nombre) {
  if(preg_match('/^('.$texto.')/i',$nombre)) {
    $sugerencias[] = $nombre." RFC:".$RFC[$indice]."";
    if(count($sugerencias)>20) { break; }
  }
}

if(isset($_GET["modo"]) && $_GET["modo"] != null) {
	$modo = $_GET["modo"];
}
else {
	$modo = "json";
}

if($modo == "ul") {
	if(count($sugerencias)>0) {
	  echo "<ul>\n<li>";
	  echo implode($sugerencias, "</li>\n<li>");
	  echo "</li>\n</ul>";
	}
	else {
	  echo "<ul></ul>";
	}
}
else {
	if(count($sugerencias)>0) {
	  echo "[ \"";
	  echo implode($sugerencias, "\", \"");
	  echo "\"]";
	}
	else {
	  echo "[]";
	}
}
?>