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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE cat_beneficiarios SET paterno=%s, materno=%s, nombres=%s, porcentaje=%s, importe=%s, idnominaemp=%s WHERE idbeneficiarios=%s",
                       GetSQLValueString($_POST['paterno'], "text"),
                       GetSQLValueString($_POST['materno'], "text"),
                       GetSQLValueString($_POST['nombres'], "text"),
					   GetSQLValueString($_POST['porcentaje'], "int"),
					   GetSQLValueString($_POST['importe'], "int"),
					   GetSQLValueString($_POST['idnominaemp'], "int"),
                       GetSQLValueString($_POST['idbeneficiarios'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
  
  if($Result1)
  {
	  $sql = "Update nomina set importe = '$_POST[importe]' where idbeneficiarios = '$_POST[idbeneficiarios]'";
	  $res = mysql_query($sql, $conexion);
  }
  

  $updateGoTo = "cat_beneficiarios.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_beneficiarios = "-1";
if (isset($_GET['idbeneficiarios'])) {
  $colname_beneficiarios = $_GET['idbeneficiarios'];
}
mysql_select_db($database_conexion, $conexion);
$query_beneficiarios = sprintf("SELECT b.idbeneficiarios, b.paterno, b.materno, b.nombres, b.porcentaje, b.importe, b.idnominaemp, concat(n.paterno, ' ', n.materno, ' ', n.nombres) as nombre FROM cat_beneficiarios b left join nominaemp n on b.idnominaemp = n.idnominaemp WHERE idbeneficiarios = %s", GetSQLValueString($colname_beneficiarios, "int"));
$beneficiarios = mysql_query($query_beneficiarios, $conexion) or die(mysql_error());
$row_beneficiarios = mysql_fetch_assoc($beneficiarios);
$totalRows_beneficiarios = mysql_num_rows($beneficiarios);
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link type="image/x-icon" href="imagenes/logomich200.ico" rel="icon" />
<link type="image/x-icon" href="imagenes/logomich200.ico" rel="shortcut icon" />
<title>Sistema de N&oacute;mina de Empleados</title>

<link rel="stylesheet" type="text/css" href="css/estilos.css">

<script>

function valida(form)
{
	if(form.paterno.value == "")
	{
		alert("Indique el apellido paterno");
		form.paterno.focus();
		return false;
	}
	
	if(form.materno.value == "")
	{
		alert("Indique el apellido materno");
		form.materno.focus();
		return false;
	}
	
	if(form.nombres.value == "")
	{
		alert("Indique el nombre");
		form.nombres.focus();
		return false;
	}
	
	return true;
}

function objetoAjax() 
{
  var xmlHttp=null;
  if (window.ActiveXObject) 
    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
  else 
    if (window.XMLHttpRequest) 
      xmlHttp = new XMLHttpRequest();
  return xmlHttp;
}

function carga(Resultado,ajax)
{
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) 
		{
			Resultado.innerHTML = ajax.responseText.tratarResponseText();
		}
	}
}


String.prototype.tratarResponseText=function(){
	var pat=/<script[^>]*>([\S\s]*?)<\/script[^>]*>/ig;
	var pat2=/\bsrc=[^>\s]+\b/g;
	var elementos = this.match(pat) || [];
	for(i=0;i<elementos.length;i++) {
		var nuevoScript = document.createElement('script');
		nuevoScript.type = 'text/javascript';
		var tienesrc=elementos[i].match(pat2) || [];
		if(tienesrc.length){
			nuevoScript.src=tienesrc[0].split("'").join('').split('"').join('').split('src=').join('').split(' ').join('');
		}else{
			var elemento = elementos[i].replace(pat,'$1','');
			nuevoScript.text = elemento;
		}
		document.getElementsByTagName('body')[0].appendChild(nuevoScript);
	}
	return this.replace(pat,'');
}


function buscaemp(consulta)
{
	Resultado = document.getElementById('ajax_buscaemp');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_buscaemp.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("consulta="+consulta);
	document.getElementById("ajax_buscaemp").style.display = "inline";
}

function seleccionaemp(idnominaemp, empleado)
{
	form1.idnominaemp.value = idnominaemp;
	form1.empleado.value = empleado;
	document.getElementById("ajax_buscaemp").style.display = "none";
	imp2por('0');
	por2imp('0');
}

function por2imp(porcentaje)
{
	Resultado = document.getElementById('ajax_imp2por');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_imp2por.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("porcentaje="+porcentaje+"&idnominaemp="+form1.idnominaemp.value);
}

function imp2por(importe)
{
	Resultado = document.getElementById('ajax_por2imp');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_por2imp.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("importe="+importe+"&idnominaemp="+form1.idnominaemp.value);
}

</script>

</head>
<body topmargin="0" leftmargin="0">
<div id="todo">
	<div id="cabeza_prin">
    </div>
    <div id="tituloarriba">
   		<div id="titulosup">Pensionados</div>    
    </div>
    <div id="cuerpo">
    	<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
        <div id="centro_prin">
          <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Paterno:</label></td>
                <td><input class="campo" type="text" name="paterno" value="<?php echo htmlentities($row_beneficiarios['paterno'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Materno:</label></td>
                <td><input class="campo" type="text" name="materno" value="<?php echo htmlentities($row_beneficiarios['materno'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Nombres:</label></td>
                <td><input class="campo" type="text" name="nombres" value="<?php echo htmlentities($row_beneficiarios['nombres'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Empleado:</label></td>
                <td>
                	<input class="campo" type="text" name="empleado" value="<? echo $row_beneficiarios["nombre"]; ?>" size="45" onKeyup="buscaemp(this.value);"><br>
                    <div id="ajax_buscaemp"></div>
                </td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Porcentaje:</label></td>
                <td><div id="ajax_por2imp"></div></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Importe:</label></td>
                <td><div id="ajax_imp2por"></div></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><input class="boton" type="button" name="regresar" id="regresar" value="REGRESAR" onClick="location.replace('cat_beneficiarios.php');"></td>
                <td><input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();"></td>
              </tr>
            </table>
            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="idbeneficiarios" value="<?php echo $row_beneficiarios['idbeneficiarios']; ?>">
            <input type="hidden" name="idnominaemp" value="<? echo $row_beneficiarios["idnominaemp"]; ?>">
          </form>
          <p>&nbsp;</p>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Pensionados</div>    
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($beneficiarios);
?>
<script>
	imp2por('0');
	form1.porcentaje.value = "<?php echo htmlentities($row_beneficiarios['porcentaje'], ENT_COMPAT, 'iso-8859-1'); ?>";

	por2imp('0');
	form1.importe.value = "<?php echo htmlentities($row_beneficiarios['importe'], ENT_COMPAT, 'iso-8859-1'); ?>";
</script>