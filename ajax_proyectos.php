<?
include ("Connections/conexion.php");
mysql_select_db ( $database_conexion, $conexion );

$query_subp = "SELECT py.idproyecto,py.descripcion FROM cat_proyecto py inner join cat_subprograma sup on py.idsubprograma=sup.idsubprograma where sup.idsubprograma='$_POST[idsprograma]' ";
$res_subp = mysql_query ( $query_subp, $conexion );
if (isset ( $_POST [desc] )) {
	$proyectop = $_POST [desc];
} else {
	$proyectop = "Seleccione";
}
?>
<!doctype html>
<html>
<head>
<script type="text/javascript">
function envia(proyecto,subprograma){
	plazas(proyecto,subprograma);
}
</script>
<meta charset="iso-8859-1">
</head>
<body>

	<select name="proyecto" class="lista" style="width: 180px;"
		onChange="envia(this.value,&lt;?php echo $_POST[idsprograma]?&gt;)">
		<option value=""><? echo $proyectop?></option>
                  <?php
																		do {
																			?>
                  <option
			value="&lt;?php echo $row_subp['idproyecto']?&gt;"><?php echo $row_subp['descripcion']?></option>
                  <?php
																		} while ( $row_subp = mysql_fetch_assoc ( $res_subp ) );
																		?>
                </select>
</body>
</html>