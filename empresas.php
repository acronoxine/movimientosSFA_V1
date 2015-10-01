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

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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

//mysql_select_db($database_conexion, $conexion);
$query_bancos = "SELECT * FROM bancos";
$bancos = mysqli_query( $conexion,$query_bancos) or die(mysql_error());
$row_bancos = mysqli_fetch_assoc($bancos);
$totalRows_bancos = mysqli_num_rows($bancos);
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link rel="shortcut icon" type="image/x-icon" href="http://www.michoacan.gob.mx/wp-content/themes/estaenti/img/favicon.ico">


<title>Sistema de N&oacute;mina de Empleados</title>

<link rel="stylesheet" type="text/css" href="css/estilos.css">

<script>

function valida(form)
{
	if(form.razonsocial.value == "")
	{
		alert("Indique el nombre o razón social de la dependencia");
		form.razonsocial.focus();
		return false;
	}
	
	if(form.titular.value == "")
	{
		alert("Indique el nombre del titular de la dependencia");
		form.titular.focus();
		return false;
	}
	
	if(form.rfc.value == "")
	{
		alert("Indique el rfc de la dependencia");
		form.rfc.focus();
		return false;
	}
	
	if(form.clavepatronal.value == "")
	{
		alert("Indique la clave patronal de la dependencia");
		form.clavepatronal.focus();
		return false;
	}
	
	if(form.calle.value == "")
	{
		alert("Indique la calle del domicilio de la dependencia");
		form.calle.focus();
		return false;
	}
	
	if(form.numeroint.value == "" && form.numeroext.value == "")
	{
		alert("Indique el n�mero del domicilio");
		form.numeroint.focus();
		return false;
	}
	
	if(form.colonia.value == "")
	{
		alert("Indique la colonia del domicilio");
		form.colonia.focus();
		return false;
	}
	
	if(form.cp.value == "")
	{
		alert("Indique el c�digo postal del domicilio");
		form.cp.focus();
		return false;
	}
	
	if(form.ciudad.value == "")
	{
		alert("Indique la ciudad donde se localiza la dependencia");
		form.ciudad.focus();
		return false;
	}
	
	if(form.estado.value == "")
	{
		alert("Indique el estado o entidad federativa");
		form.estado.focus();
		return false;
	}
	
	if(form.upp.value == "")
	{
		alert("Indique la unidad programatica presupuestal(UPP)");
		form.upp.focus();
		return false;
	}
	
	if(form.idbancos.value == "")
	{
		alert("Indique el nombre del banco donde se les deposita a los empleados");
		form.idbancos.focus();
		return false;
	}
	
	return true;
}

function sololetras(form, e)
{
    if(e.keyCode != 0)
      letra = e.keyCode;
    else
      letra = e.which;
    
    if(letra >= 48 && letra <= 57)
       return false;
    else
       return true;
}

function solonumeros(form, e)
{
    if(e.keyCode != 0)
      letra = e.keyCode;
    else
      letra = e.which;
    
    if((letra < 48 || letra > 57) && letra != 37 && letra != 38 && letra != 39 && letra != 40 && letra != 8 && letra != 46)
       return false;
    else
       return true;
}

</script>

</head>
<body topmargin="0" leftmargin="0">
	<div id="todo">
		<div id="cabeza_prin"></div>
		<div id="cuerpo">
			<div id="tituloarriba">
				<div id="titulosup">Dependencia</div>
			</div>
			<div id="panelizq">
			<? include("menu.php"); ?>
      </div>
			<div id="centro_prin">
				<form method="post" name="form1" action="empresas_lista.php"
					target="lista">
					<table align="center">
						<tr>
							<td>
								<table align="center">
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Razon social:</label></td>
										<td><input class="campo" type="text" name="razonsocial"
											value="" size="32" maxlength="150"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Director:</label></td>
										<td><input class="campo" type="text" name="director" value=""
											size="32" maxlength="90"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Titular:</label></td>
										<td><input class="campo" type="text" name="titular" value=""
											size="32" maxlength="90"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">RFC:</label></td>
										<td><input class="campo" type="text" name="rfc" value=""
											size="32" maxlength="13"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Clave patronal:</label></td>
										<td><input class="campo" type="text" name="clavepatronal"
											value="" size="32" maxlength="15"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Calle:</label></td>
										<td><input class="campo" type="text" name="calle" value=""
											size="32" maxlength="60"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Numero int:</label></td>
										<td><input class="campo" type="text" name="numeroint" value=""
											size="32" maxlength="7"
											onKeyPress="return solonumeros(this.form, event)"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Numero ext:</label></td>
										<td><input class="campo" type="text" name="numeroext" value=""
											size="32" maxlength="7"
											onKeyPress="return solonumeros(this.form, event)"></td>
									</tr>
								</table>
							</td>
							<td valign="top">
								<table align="center">
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Colonia:</label></td>
										<td><input class="campo" type="text" name="colonia" value=""
											size="32" maxlength="60"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Cp:</label></td>
										<td><input class="campo" type="text" name="cp" value=""
											size="32" maxlength="5"
											onKeyPress="return solonumeros(this.form, event)"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Ciudad:</label></td>
										<td><input class="campo" type="text" name="ciudad"
											value="Morelia" size="32" maxlength="60"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Estado:</label></td>
										<td><input class="campo" type="text" name="estado"
											value="Michoac&aacute;n" size="32" maxlength="60"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">UPP:</label></td>
										<td><input class="campo" type="text" name="upp" value=""
											size="32" maxlength="2"
											onKeyPress="return solonumeros(this.form, event)"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Banco:</label></td>
										<td><select class="lista" name="idbancos">
												<option value="">Seleccione</option>
                  <?php 
do {  
?>
                  <option value="<?php echo $row_bancos['idbancos']?>"><?php echo $row_bancos['banco']?></option>
                  <?php
} while ($row_bancos = mysqli_fetch_assoc($bancos));
?>
                </select></td>
								
								</table>
							</td>
						
						
						<tr valign="baseline">
							<td colspan="2"><input class="boton" type="button" name="guardar"
								id="guardar" value="GUARDAR"
								onClick="if(valida(this.form)) submit();"></td>
						</tr>
						<tr>
							<td colspan="2"><iframe name="lista" id="lista"
									src="empresas_lista.php" style="width: 950px; height: 200px;"></iframe>
							</td>
						</tr>
					</table>
					<input type="hidden" name="MM_insert" value="form1">
				</form>
				<p>&nbsp;</p>
			</div>
		</div>
		<div id="tituloabajo">
			<div id="tituloinf">Dependencia</div>
		</div>
	</div>
</body>
</html>
<?php
mysqli_free_result($bancos);
?>
