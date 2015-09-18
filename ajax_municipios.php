<?
session_start();
include("Connections/conexion.php");
//mysql_select_db($database_conexion, $conexion);

$sql = "Select * from cat_municipios where idestado = '$_POST[idestado]' order by municipio asc";
$ciudad = mysqli_query($conexion, $sql);
?>
				<select name="ciudad" class="lista" style="width:200px;">
                	<option value="">Seleccione</option>
                  <?php 
while($row_ciudad = mysqli_fetch_array($ciudad)) {  
?>
                  <option value="<?php echo $row_ciudad['idmunicipios']?>" ><?php echo $row_ciudad['municipio']?></option>
                  <?php
}
?>
                </select><label class="label">*</label>
<?
	mysqli_free_result($ciudad);
?>