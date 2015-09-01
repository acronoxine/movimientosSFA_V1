<?
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);

if(isset($_POST[idplaza])){
	$sql = "SELECT cc.idcategoria,cc.descripcion,cc.sueldobase FROM cat_plazas cp INNER JOIN cat_categoria cc ON cc.idcategoria=cp.categoria
WHERE cp.plaza_id='$_POST[idplaza]'";
}
$res = mysql_query($sql, $conexion);
$ren = mysql_fetch_array($res);
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
</head>
<body>

<input class="campo" type="text" name="sueldobase"  value="<? echo $ren["sueldobase"]; ?>" size="15" maxlength="15" style="text-align:right;" onKeypress="return solonumeros(this.form, event);"></td>

</html>