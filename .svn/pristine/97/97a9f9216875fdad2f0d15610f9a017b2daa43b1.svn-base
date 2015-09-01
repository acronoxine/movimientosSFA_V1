<?

	/* calculo del sueldo base  */
	
	$sql = "Select sueldobase as importe from nominaemp where idnominaemp = '$idempleado'";
	$res = mysql_query($sql, $conexion);
	$ren = mysql_fetch_array($res);
	mysql_free_result($res);
	
	$importe = $ren["importe"];
	
	$sql = "Delete from nomina where idnominaemp = '$idempleado' and concepto = '101'";
	$ressb = mysql_query($sql, $conexion);
	
	if($ressb)
	{
		$sql = "insert into nomina(idnominaemp, concepto, importe, tipo)";
		$sql .= " values($idempleado, '101', $importe, 'P')";
		mysql_query($sql, $conexion);
	}
	
	$importe = 0;
	
	
	/* calculo del isr */
	
	$sql = "Select sueldobase from nominaemp where idnominaemp = '$idempleado'";
	$res = mysql_query($sql, $conexion);
	$ren = mysql_fetch_array($res);
	mysql_free_result($res);	
	
	$sueldobase = $ren["sueldobase"] * 2;
	
	$sql = "select limiteinferior, cuotafija, porciento";
	$sql .= " from isr";
	$sql .= " where ($sueldobase between limiteinferior and limitesuperior) or ($sueldobase >= limiteinferior and limitesuperior=0)";
	$res = mysql_query($sql, $conexion);
	$ren = mysql_fetch_array($res);
	mysql_free_result($res);
	
	$resultado = (($sueldobase - $ren["limiteinferior"]) * ($ren["porciento"]/100)) + $ren["cuotafija"];
	
	$resultado /= 2;
	
	$importe = number_format($resultado, 2, ".", "");
	
	$sql = "Delete from nomina where idnominaemp = '$idempleado' and concepto = '251'";
	$resisr = mysql_query($sql, $conexion);
	
	if($resisr)
	{
		$sql = "insert into nomina(idnominaemp, concepto, importe, tipo)";
		$sql .= " values($idempleado, '251', $importe, 'D')";
		mysql_query($sql, $conexion);
	}
	
	$importe = 0;
	
	
	/* calculo del imss */
	
	$sql = "Select importe from cat_conceptos where clave = '274'";
	$res = mysql_query($sql, $conexion);
	$ren = mysql_fetch_array($res);
	mysql_free_result($res);
	
	$importe = $ren["importe"];
	
	$sql = "Delete from nomina where idnominaemp = '$idempleado' and concepto = '274'";
	$resimss = mysql_query($sql, $conexion);
	
	if($resimss)
	{
		$sql = "insert into nomina(idnominaemp, concepto, importe, tipo)";
		$sql .= " values($idempleado, '274', $importe, 'D')";
		mysql_query($sql, $conexion);
	}
	
	$importe = 0;
	

	/* cálculo de la compensacion */
	
	$importe = $_POST["compensacion"];
	
	$sql = "Delete from nomina where idnominaemp = '$idempleado' and concepto = '114'";
	$rescomp = mysql_query($sql, $conexion);
	
	if($rescomp)
	{
		$sql = "insert into nomina(idnominaemp, concepto, importe, tipo)";
		$sql .= " values($idempleado, '114', $importe, 'P')";
		mysql_query($sql, $conexion);
		
		$sueldobase = $importe * 2;
		
		$sql = "select limiteinferior, cuotafija, porciento";
		$sql .= " from isr";
		$sql .= " where ($sueldobase between limiteinferior and limitesuperior) or ($sueldobase >= limiteinferior and limitesuperior=0)";
		$res = mysql_query($sql, $conexion);
		$ren = mysql_fetch_array($res);
		mysql_free_result($res);
		
		$resultado = (($sueldobase - $ren["limiteinferior"]) * ($ren["porciento"]/100)) + $ren["cuotafija"];
		
		$resultado /= 2;
		
		$isr = number_format($resultado, 2, ".", "");
		
		$sql = "insert into nomina(idnominaemp, concepto, importe, tipo)";
		$sql .= " values($idempleado, '251', $isr, 'D')";
		mysql_query($sql, $conexion);	
	}
	
	$importe = 0;
?>