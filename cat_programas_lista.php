<?
session_start ();
if ($_SESSION ["m_sesion"] != 1) {
	echo "<script>";
	echo "location.replace('index.php');";
	echo "</script>";
	exit ();
}
?>
<?php require_once('Connections/conexion.php'); ?>
<?php
print_r($_GET ."Parameters");

if (! function_exists ( "GetSQLValueString" )) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
		if (PHP_VERSION < 6) {
			$theValue = get_magic_quotes_gpc () ? stripslashes ( $theValue ) : $theValue;
		}
		
		$theValue = function_exists ( "mysql_real_escape_string" ) ? mysqli_real_escape_string ( $theValue ) : mysql_escape_string ( $theValue );
		
		switch ($theType) {
			case "text" :
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "long" :
			case "int" :
				$theValue = ($theValue != "") ? intval ( $theValue ) : "NULL";
				break;
			case "double" :
				$theValue = ($theValue != "") ? doubleval ( $theValue ) : "NULL";
				break;
			case "date" :
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "defined" :
				$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
				break;
		}
		return $theValue;
	}
}

if ((isset ( $_GET ['idprograma'] )) && ($_GET ['idprograma'] != "")) {
	$deleteSQL = sprintf ( "DELETE FROM cat_programa WHERE idprograma=%s", GetSQLValueString ( $_GET ['idprograma'], "int" ) );
	
	//mysql_select_db ( $database_conexion, $conexion );
	$Result1 = mysqli_query ( $conexion,$deleteSQL ) or die ( mysqli_error () );
}

if ((isset ( $_POST ["MM_insert"] )) && ($_POST ["MM_insert"] == "form1")) {
	$insertSQL = sprintf ( "INSERT INTO cat_programa (clave, descripcion,idarea) VALUES (%s, %s, %s)", GetSQLValueString ( $_POST ['clave'], "text" ), GetSQLValueString ( strtoupper ( $_POST ['descripcion'] ), "text" ), GetSQLValueString ( strtoupper ( $_POST ['ur'] ), "int" ) );
	
	//mysql_select_db ( $database_conexion, $conexion );
	$Result1 = mysqli_query ( $conexion,$insertSQL ) or die ( mysqli_error () );
	
	echo "<script>";
	echo "parent.document.form1.reset();";
	echo "</script>";
}

//mysql_select_db ( $database_conexion, $conexion );
$query_programas = "SELECT a.clave as area_clave,p.idprograma,p.idarea,p.descripcion,p.clave FROM cat_programa p inner join cat_area a on a.idarea=p.idarea ";

if (isset ( $_GET ["consulta"] )) {
	$query_programas .= " where p.descripcion like '%$_GET[consulta]%'";
}

$programas = mysqli_query ( $conexion,$query_programas ) or die ( mysqli_error () );
$row_programas = mysqli_fetch_assoc ( $programas );
$totalRows_programas = mysqli_num_rows ( $programas );
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">

<link rel="stylesheet" type="text/css" href="css/estilos.css">

<style>
#encabezado {
	position: fixed;
	top: 0px;
}
</style>

<script src="encabezadofijo/mootools-yui-compressed.js"></script>
<script src="encabezadofijo/funciones.js" type="text/javascript"></script>

<script text="text/javascript">
	function loadDemo(){
			
		  // nice one David Walsh	
		  var demoTwo = new ScrollSpy({
			 min: 0, // acts as position-x: absolute; left: 50px;
			 mode: 'horizontal',
			 onEnter: function(position,enters) {
			
			 },
			 onLeave: function(position,leaves) {
			
			 },
			 onTick: function(position,state,enters,leaves) {
				$("encabezado").style.left = -position.x+"px";
			 },
			 container: window
			}); 

	}
</script>

</head>

<body topmargin="0" leftmargin="0">

	<div id="encabezado">
		<table class="tablagrid" border="0" cellpadding="0" cellspacing="0"
			width="567">
			<tr class="tablahead">
				<td width="40">&nbsp;</td>
				<td width="40">&nbsp;</td>
				<td width="100" align="center">UR</td>
				<td width="100" align="center">CLAVE</td>
				<td>DESCRIPCI&#211;N</td>
			</tr>
		</table>
	</div>
	<table class="tablagrid" border="0" cellpadding="0" cellspacing="0"
		width="567" style="padding-top: 32px;">
  <?php do { ?>
    <tr class="tablaregistros">
			<td width="40"><a
				onClick="if(confirm('Confirma que desea eliminar el registro?')) location.href='cat_programas_lista.php?idprograma=<?php echo $row_programas['idprograma']; ?>';"
				href="#"><img src="imagenes/borrar.png" width="35" height="35"></a></td>
			<td width="40">
			<a target="_top"
				href="cat_programas_md.php?idprograma=<?php echo $row_programas['idprograma']; ?>"><img
					src="imagenes/editar.png" width="35" height="35"></a></td>
			<td width="100" align="center"><?php echo $row_programas['area_clave']; ?></td>
			<td width="100" align="center"><?php echo $row_programas['clave']; ?></td>
			<td><?php echo $row_programas['descripcion']; ?></td>
		</tr>
    <?php } while ($row_programas = mysqli_fetch_assoc($programas)); ?>
</table>
</body>
</html>
<?php
mysqli_free_result ( $programas);
?>
