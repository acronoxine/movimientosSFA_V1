<?php require_once('Connections/conexion.php'); ?>
<?php

if (! function_exists ( "GetSQLValueString" )) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
		if (PHP_VERSION < 6) {
			$theValue = get_magic_quotes_gpc () ? stripslashes ( $theValue ) : $theValue;
		}
		
		$theValue = function_exists ( "mysql_real_escape_string" ) ? mysql_real_escape_string ( $theValue ) : mysql_escape_string ( $theValue );
		
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

if ((isset ( $_POST ["MM_insert"] )) && ($_POST ["MM_insert"] == "form1")) {
	$insertSQL = sprintf ( "INSERT INTO cat_plazas (plaza_clave,subprograma,categoria) VALUES (%s, %s, %s)", GetSQLValueString ( $_POST ['clave'], "text" ), GetSQLValueString ( $_POST ['subprograma'], "int" ), GetSQLValueString ( $_POST ['categoria'], "int" ) );
	
	mysql_select_db ( $database_conexion, $conexion );
	$Result1 = mysql_query ( $insertSQL, $conexion ) or die ( mysql_error () );
	$select = "SELECT MAX(plaza_id) as maximo FROM cat_plazas";
	$res_mi = mysql_query ( $select, $conexion );
	$ren_mi = mysql_fetch_array ( $res_mi );
	$maximo_id = $ren_mi ['maximo'];
	$insertSQL = sprintf ( "INSERT INTO empleado_plaza (idnominaemp,plaza_id,estado) values ('0','$maximo_id','VACANTE')" );
	$Result1 = mysql_query ( $insertSQL, $conexion ) or die ( mysql_error () );
}

if ((isset ( $_GET ['idplaza'] )) && ($_GET ['idplaza'] != "")) {
	$deleteSQL = sprintf ( "UPDATE empleado_plaza set estado='INACTIVO' WHERE plaza_id=%s", GetSQLValueString ( $_GET ['idplaza'], "int" ) );
	
	mysql_select_db ( $database_conexion, $conexion );
	$Result1 = mysql_query ( $deleteSQL, $conexion ) or die ( mysql_error () );
}

mysql_select_db ( $database_conexion, $conexion );
/*
 * $query_plazas = "SELECT CONCAT(nemp.paterno,' ',nemp.materno,' ',nemp.nombres) AS nombre,ep.estado AS plaza_estado, ep.fecha_inicial, ep.fecha_final, a.descripcion as ur_desc, pr.clave as prog_clave, pz.plaza_id AS plaza_id,pz.plaza_clave AS plaza_clave, pz.titular, sp.idsubprograma AS subp_id, sp.descripcion AS subp_descripcion, ct.descripcion AS cat_descripcion,ct.clave FROM cat_plazas pz INNER JOIN empleado_plaza ep ON ep.plaza_id=pz.plaza_id INNER JOIN nominaemp nemp ON nemp.idnominaemp=ep.idnominaemp INNER JOIN cat_categoria ct ON ct.clave=pz.categoria LEFT JOIN cat_subprograma sp ON sp.idsubprograma=pz.subprograma LEFT JOIN cat_programa pr ON pr.idprograma=pz.programa LEFT JOIN cat_area a ON a.idarea=pz.ur";
 */
$query_plazas = "SELECT 
    cat_plazas.plaza_id,
    cat_plazas.plaza_clave,
    ur AS ur_desc,
    programa AS prog_clave,
    subprograma AS subp_descripcion,
    categoria AS cat_descripcion,
    empleado_plaza.estado,
    nombres as nombre,
    empleado_plaza.fecha_inicial,
    empleado_plaza.fecha_final,
    cat_plazas.titular
FROM
    cat_plazas
        INNER JOIN
    empleado_plaza ON empleado_plaza.idnominaemp = cat_plazas.plaza_id
        INNER JOIN
    nominaemp ON nominaemp.idnominaemp = cat_plazas.plaza_id
        INNER JOIN
    empleado_plaza ep ON ep.plaza_id = cat_plazas.plaza_id";

if (isset ( $_GET ["consultap"] )) {
	$query_plazas .= " where (plaza_clave like '%$_GET[consultap]%' and empleado_plaza.estado like '%$_GET[consulta]%'
	OR ur like '%$_GET[consultap]%' and empleado_plaza.estado like '%$_GET[consulta]%'
	OR programa like '%$_GET[consultap]%' and empleado_plaza.estado like '%$_GET[consulta]%'
	OR subprograma like '%$_GET[consultap]%' and empleado_plaza.estado like '%$_GET[consulta]%'
	OR categoria like '%$_GET[consultap]%' and empleado_plaza.estado like '%$_GET[consulta]%'
	OR nombres like '%$_GET[consultap]%' and empleado_plaza.estado like '%$_GET[consulta]%')";
	/*
	 * OR (ct.descripcion like '%$_GET[consultap]%' and ep.estado like '%$_GET[consulta]%') OR (sp.descripcion like '%$_GET[consultap]%' and ep.estado like '%$_GET[consulta]%') OR (nemp.paterno like '%$_GET[consultap]%' and ep.estado like '%$_GET[consulta]%')";
	 */
} else {
	$query_plazas .= " LIMIT 0,50";
}
// echo $query_plazas;
$plazas = mysql_query ( $query_plazas, $conexion ) or die ( mysql_error () );
$row_plazas = mysql_fetch_assoc ( $plazas );
$totalRows_plazas = mysql_num_rows ( $plazas );
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
			width="950">
			<tr class="tablahead">
				<td width="25">#</td>
				<td width="25">&nbsp;</td>
				<td width="50">CLAVE</td>
				<td width="150">UR</td>
				<td width="50">PROG</td>
				<td width="50">SPROG</td>
				<td width="125">CATEGORIA</td>
				<td width="75">ESTADO</td>
				<td width="150">EMPLEADO</td>
				<td width="150">PERIODO</td>
				<td width="100">TITULAR</td>
			</tr>
		</table>
	</div>
	<table class="tablagrid" border="0" cellpadding="0" cellspacing="0"
		width="950" style="padding-top: 32px;">
  <?php
		$cont = 1;
		do {
			if ($cont % 2 == 1)
				$class = "inactivo";
			else
				$class = "tablaregistros";
			?>
    <tr class="<? echo $class;?>">
			<td width="25" align="center"><?php echo $cont;$cont++;?></td>
			<td width="25"><a target="_top"
				href="cat_plazas_md.php?idplaza=<?php echo $row_plazas['plaza_id']; ?>"><img
					src="imagenes/editar.png" width="20" height="20"></a></td>
			<td width="50" align="center"><?php echo $row_plazas['plaza_clave']; ?></td>
			<td width="150" align="center" style="font-size: 10px"><?php echo $row_plazas['ur_desc']; ?></td>
			<td width="50" align="center"><?php echo $row_plazas['prog_clave']; ?></td>
			<td width="50" align="center"><?php echo $row_plazas['subp_descripcion']; ?></td>
			<td width="125"><?php echo $row_plazas['cat_descripcion']; ?></td>
			<td width="75" align="center" style="font-size: 10px"><?php  echo $row_plazas['estado']; ?></td>
			<td width="150"><?php echo $row_plazas['nombre']; ?></td>
      <?php
			if (! $row_plazas ['fecha_final'])
				$fecha_fin = " - Indefinida";
			else
				$fecha_fin = " - " . $row_plazas ['fecha_final'];
			?>
      <td width="150" align="center"><?php echo $row_plazas['fecha_inicial'].$fecha_fin?></td>
			<td width="100" align="center">
	 	<?php if($row_plazas['titular']!=""){ echo $row_plazas['titular'];} else echo "-"?>
      </td>
		</tr>
    <?php
		} while ( $row_plazas = mysql_fetch_assoc ( $plazas ) );
		$numeroEntradas = mysql_num_rows ( $plazas );
		?>
</table>
	<script>
	var numero=window.parent.document.getElementById('numeroEntradas'); 
	numero.value='<? echo $numeroEntradas?>';
</script>
</body>
</html>
<?php
mysql_free_result ( $plazas );
?>
