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
<?php require_once('Connections/conexion.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  //$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
  if(function_exist){
  	$obj = new database();
  	$link = $obj->connect();
  	$theValue = mysqli_real_escape_string($link,$theValue);
  }
  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if ((isset($_GET['idnomina'])) && ($_GET['idnomina'] != "")) {
  $deleteSQL = sprintf("DELETE FROM nomina WHERE idnomina=%s",
                       GetSQLValueString($_GET['idnomina'], "int"));

  //mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query( $conexion, $deleteSQL ) or die( mysqli_error() );
}

if ((isset($_POST["importep"])) && ($_POST["importep"] > 0)) {
  $insertSQL = sprintf("INSERT INTO nomina (idnominaemp, concepto, importe, tipo) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['idnominaemp'], "int"),
                       GetSQLValueString($_POST['percepciones'], "text"),
                       GetSQLValueString($_POST['importep'], "double"),
                       GetSQLValueString('P', "text"));

  //mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query( $conexion, $insertSQL ) or die(mysqli_error());
  
  echo "<script>";
  echo "parent.document.form1.percepciones.value='';";
  echo "parent.document.form1.importep.value='0';";
  echo "</script>";
}


$colname_nomina = "-1";
if (isset($_GET['idnominaemp'])) {
  $colname_nomina = $_GET['idnominaemp'];
}else{
  $colname_nomina = $_POST['idnominaemp'];
}
//mysql_select_db($database_conexion, $conexion);
$query_nomina = sprintf("SELECT n.idnomina, n.idnominaemp, n.concepto, c.descripcion, n.importe, n.tipo FROM nomina n left join cat_conceptos c on n.concepto = c.clave WHERE n.idnominaemp = %s and n.tipo='P'", GetSQLValueString($colname_nomina, "int"));
$nomina = mysqli_query( $conexion, $query_nomina ) or die( mysqli_error() );
$row_nomina = mysqli_fetch_assoc( $nomina );
$totalRows_nomina = mysqli_num_rows( $nomina );
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/estilos.css">

</head>

<body topmargin="0" leftmargin="0">
<table class="tablagrid" border="0" cellpadding="1" cellspacing="0">
  <tr class="tablahead">
    <td width="40" align="center">&nbsp;</td>
    <td width="50" align="center">CLAVE</td>
    <td width="210" align="center">CONCEPTO</td>
    <td width="150" align="center">IMPORTE</td>
  </tr>
  <?php $sueldobruto = 0; do { ?>
    <tr class="tablaregistros">
      <td height="51" align="center">
      	<a href="calculaimportes_lista_p.php?idnomina=<?php echo $row_nomina['idnomina']; ?>&idnominaemp=<? echo $colname_nomina; ?>"><img src="imagenes/borrar.png" width="35" height="35"></a>
      </td>
      <td align="center"><?php echo $row_nomina['concepto']; ?></td>
      <td align="center"><?php echo $row_nomina['descripcion']; ?></td>
      <td align="right"><?php echo number_format($row_nomina['importe'], 2, ".", ","); ?></td>
    </tr>
    <?
    	$sueldobruto += $row_nomina['importe'];
	?>
    <?php } while ($row_nomina = mysqli_fetch_assoc( $nomina)); ?>
</table>
</body>
</html>
<?php
mysqli_free_result( $nomina );
?>
<script>

parent.document.getElementById('sueldobruto').value="<? echo $sueldobruto; ?>";

setTimeout(function(){
	
	parent.document.getElementById('sueldoneto').value = parseFloat(parent.document.getElementById('sueldobruto').value) - parseFloat(parent.document.getElementById('totaldeducciones').value);
	
	parent.document.getElementById('sueldoneto').value = Math.round(parent.document.getElementById('sueldoneto').value*100)/100 ;
	
},3000);

</script>