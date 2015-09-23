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
<?php require_once('Connections/conexion.php'); 
	  include 'funcionesJL.php';
?>
<?php


if ((isset($_GET['idnomina'])) && ($_GET['idnomina'] != "")) {
 
  $sql = "Select idbeneficiarios from nomina where idnomina = '$_GET[idnomina]'";
  $res = mysqli_query( $conexion, $sql );
  $ren = mysqli_fetch_array($res);

  $deleteSQL = sprintf("DELETE FROM cat_beneficiarios WHERE idbeneficiarios=%s",
                       GetSQLValueString($ren['idbeneficiarios'], "int"));

 //mysql_select_db($database_conexion, $conexion);
  $Result1 = mysqli_query( $conexion,$deleteSQL ) or die( mysqli_error() );	
	
	
  $deleteSQL = sprintf("DELETE FROM nomina WHERE idnomina=%s",
                       GetSQLValueString($_GET['idnomina'], "int"));

  //mysql_select_db($database_conexion, $conexion);
  $Result1 = mysqli_query( $conexion, $deleteSQL ) or die(mysqli_error());
    //------- Para obligar al recalculo del ISR en funcionesJL-----------------------------
   if($_GET['concepto']=='258'){
		recalculoISR($_GET['idnominaemp']);
   }
   //--------------------------------------------------------------------------------------
		
}

if ((isset($_POST["imported"])) && ($_POST["imported"] > 0)) {
	
	$idbeneficiarios = 0;
	
  	if(isset($_POST["pen_porcentaje"]) && $_POST["pen_porcentaje"] != "")
	{
		$insertSQL = sprintf("INSERT INTO cat_beneficiarios (paterno, materno, nombres, idnominaemp, porcentaje, importe) VALUES (%s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['pen_paterno'], "text"),
						   GetSQLValueString($_POST['pen_materno'], "text"),
						   GetSQLValueString($_POST['pen_nombres'], "text"),
						   GetSQLValueString($_POST['idnominaemp'], "int"),
						   GetSQLValueString($_POST['pen_porcentaje'], "double"),
						   GetSQLValueString($_POST['pen_importe'], "double"));
	
		//mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query( $conexion, $insertSQL ) or die(mysqli_error() );

		echo "<script>";		
		echo "parent.document.form1.pen_paterno.value='';";
		echo "parent.document.form1.pen_materno.value='';";
		echo "parent.document.form1.pen_nombres.value='';";
		echo "parent.document.form1.pen_porcentaje.value='';";
		echo "parent.document.form1.pen_importe.value='';";
		echo "</script>";
		
		$idbeneficiarios = mysql_insert_id(); /* Check this */
	}

	$insertSQL = sprintf("INSERT INTO nomina (idnominaemp, concepto, importe, tipo, idbeneficiarios) VALUES (%s, %s, %s, %s, %s)",
					   GetSQLValueString($_POST['idnominaemp'], "int"),
					   GetSQLValueString($_POST['deducciones'], "text"),
					   GetSQLValueString($_POST['imported'], "double"),
					   GetSQLValueString('D', "text"),
					   GetSQLValueString($idbeneficiarios, "double"));

	//mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query( $conexion, $insertSQL ) or die( mysqli_error() );
  //------- Para obligar al recalculo del ISR en funcionesJL-----------------------------
	if($_POST['deducciones']==258){
		recalculoISR($_POST['idnominaemp']);
	}
  //--------------------------------------------------------------------------------------
  echo "<script>";
  echo "parent.document.form1.deducciones.value='';";
  echo "parent.document.form1.imported.value='0';";
  echo "</script>";
}

$colname_nomina = "-1";
if (isset($_GET['idnominaemp'])) {
  $colname_nomina = $_GET['idnominaemp'];
}else{
  $colname_nomina = $_POST['idnominaemp'];
}
//mysql_select_db($database_conexion, $conexion);
$query_nomina = sprintf("SELECT n.idnomina, n.idnominaemp, n.concepto, c.descripcion, n.importe, n.tipo FROM nomina n left join cat_conceptos c on n.concepto = c.clave WHERE n.idnominaemp = %s and n.tipo='D'", GetSQLValueString($colname_nomina, "int"));
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
    <td align="center" width="40">&nbsp;</td>
    <td align="center" width="50">CLAVE</td>
    <td align="center" width="260">CONCEPTO</td>
    <td width="151">IMPORTE</td>
  </tr>

  <?php 
  
  //para recalcular el isr modifique los datos que se envian (concepto,importe) en la etiqueta <a> a continuación:
  $totaldeducciones = 0; do { ?>
    <tr class="tablaregistros">
      <td align="center">
      
      	<a href="calculaimportes_lista_d.php?idnomina=<?php echo $row_nomina['idnomina']; ?>&idnominaemp=<? echo $colname_nomina; ?>&concepto=<? echo $row_nomina['concepto']?>&importe=<? echo $row_nomina['importe']?>"><img src="imagenes/borrar.png" width="35" height="35"></a>
      </td>
      <td align="center"><?php echo $row_nomina['concepto']; ?></td>
      <td align="center"><?php echo $row_nomina['descripcion']; ?></td>
      <td align="right"><?php echo number_format($row_nomina['importe'], 2, ".", ","); ?></td>
    </tr>
    <?
    	$totaldeducciones += $row_nomina['importe'];
	?>
    <?php } while ($row_nomina = mysqli_fetch_assoc( $nomina )); ?>
</table>
</body>
</html>
<?php
mysqli_free_result($nomina);
?>
<script>
	parent.document.getElementById('totaldeducciones').value="<? echo $totaldeducciones; ?>";

setTimeout(function(){
	
	parent.document.getElementById('sueldoneto').value = parseFloat(parent.document.getElementById('sueldobruto').value) - parseFloat(parent.document.getElementById('totaldeducciones').value);
	
	parent.document.getElementById('sueldoneto').value = Math.round(parent.document.getElementById('sueldoneto').value*100)/100 ;
	
},3000);
</script>