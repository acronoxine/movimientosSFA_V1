<?
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);

$query_subp= "SELECT plaza_id, plaza_clave, ur, programa, subprograma FROM cat_plazas=02";
$res_subp=mysql_query($query_subp,$conexion);
print_r($mysql_query."Debug");
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
</head>
<body>


                <select name="subprograma" style="width:180px;">
                	<option value="">Selecciona</option>
                  <?php 
do {  
?>
                  <option value="<?php echo $row_subprograma['programa']?>" ><?php echo $row_subprograma['programa']?></option>
                  <?php
} while ($row_subprograma = mysql_fetch_assoc($res_subp));
?>
                </select>
</body>
</html>