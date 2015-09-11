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

if ((isset($_GET['idempresa'])) && ($_GET['idempresa'] != "")) {
  $deleteSQL = sprintf("DELETE FROM empresa WHERE idempresa=%s",
                       GetSQLValueString($_GET['idempresa'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO empresa (
  		razonsocial,  
  		titular, 
  		rfc, 
  		clavepatronal, 
  		calle, 
  		numeroint, 
  		numeroext, 
  		colonia, 
  		cp, 
  		ciudad, 
  		estado, 
  		upp, 
  		idbancos) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['razonsocial'], "text"),
					   GetSQLValueString($_POST['titular'], "text"),
                       GetSQLValueString($_POST['rfc'], "text"),
                       GetSQLValueString($_POST['clavepatronal'], "text"),
                       GetSQLValueString($_POST['calle'], "text"),
                       GetSQLValueString($_POST['numeroint'], "text"),
                       GetSQLValueString($_POST['numeroext'], "text"),
                       GetSQLValueString($_POST['colonia'], "text"),
                       GetSQLValueString($_POST['cp'], "text"),
                       GetSQLValueString($_POST['ciudad'], "text"),
                       GetSQLValueString($_POST['estado'], "text"),
                       GetSQLValueString($_POST['upp'], "text"),
                       GetSQLValueString($_POST['idbancos'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  
  echo "<script>";
  echo "parent.document.form1.reset();";
  echo "</script>";
}

//mysql_select_db($database_conexion, $conexion);
$query_empresas = "SELECT idempresa, razonsocial, titular, rfc, clavepatronal, calle, numeroint, numeroext, colonia, cp, ciudad, estado, urlcertificado, nocertificado, sello, upp, b.banco FROM empresa e left join bancos b on e.idbancos = b.idbancos";
$empresas = mysqli_query($conexion,$query_empresas) or die(mysql_error());
$row_empresas = mysqli_fetch_assoc($empresas);
$totalRows_empresas = mysqli_num_rows($empresas);
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>

<body topmargin="0" leftmargin="0">
<table class="tablagrid" border="0" cellpadding="0" cellspacing="1" width="1819">
  <tr class="tablahead">
    <td width="38" align="center">&nbsp;</td>
    <td width="38" align="center">&nbsp;</td>
    <td width="299" align="center">RAZ�N SOCIAL</td>
    <td width="300" align="center">TITULAR</td>
    <td width="75" align="center">RFC</td>
    <td width="126" align="center">CLAVE PATRONAL</td>
    <td width="83" align="center">CALLE</td>
    <td width="108" align="center">N�MERO INT.</td>
    <td width="112" align="center">N�MERO EXT.</td>
    <td width="95" align="center">COLONIA</td>
    <td width="73" align="center">CP</td>
    <td width="93" align="center">CIUDAD</td>
    <td width="94" align="center">ESTADO</td>
    <td width="79" align="center">UPP</td>
    <td width="91" align="center">BANCO</td>
  </tr>
  <?php do { ?>
    <tr class="tablaregistros">
      <td height="62"><a href="empresas_lista.php?idempresa=<?php echo $row_empresas['idempresa']; ?>"><img src="imagenes/borrar.png" width="36" height="36"></a></td>
      <td><a target="_top" href="empresas_md.php?idempresa=<?php echo $row_empresas['idempresa']; ?>"><img src="imagenes/editar.png" width="36" height="36"></a></td>
      <td><?php echo $row_empresas['razonsocial']; ?></td>
      <td><?php echo $row_empresas['titular']; ?></td>
      <td align="center"><?php echo $row_empresas['rfc']; ?></td>
      <td align="center"><?php echo $row_empresas['clavepatronal']; ?></td>
      <td><?php echo $row_empresas['calle']; ?></td>
      <td align="center"><?php echo $row_empresas['numeroint']; ?></td>
      <td align="center"><?php echo $row_empresas['numeroext']; ?></td>
      <td align="center"><?php echo $row_empresas['colonia']; ?></td>
      <td align="center"><?php echo $row_empresas['cp']; ?></td>
      <td align="center"><?php echo $row_empresas['ciudad']; ?></td>
      <td align="center"><?php echo $row_empresas['estado']; ?></td>
      <td align="center"><?php echo $row_empresas['upp']; ?></td>
      <td align="center"><?php echo $row_empresas['banco']; ?></td>
    </tr>
    <?php } while ($row_empresas = mysqli_fetch_assoc($empresas)); ?>
</table>
</body>
</html>
<?php
mysqli_free_result($empresas);
?>
