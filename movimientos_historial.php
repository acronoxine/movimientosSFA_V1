<?
session_start();
if($_SESSION["m_sesion"] != 1)
{
	echo "<script>";
	echo "location.replace('index.php');";
	echo "</script>";
	exit();
}
require_once('Connections/conexion.php'); 
//mysql_select_db($database_conexion, $conexion);
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link rel="shortcut icon" type="image/x-icon" href="http://www.michoacan.gob.mx/wp-content/themes/mich2015/img/favicon.ico">

<title>Sistema de Movimientos</title>
<link rel="stylesheet" type="text/css" href="css/estilos.css">

<script src="js_popup/jquery.js"></script>
        <script src="js_popup/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
        <link rel="stylesheet" type="text/css" href="js_popup/fancybox/jquery.fancybox-1.3.4.css">

        <script type="text/javascript">

                function formato(datos)
                {
                    $.fancybox({
                        'href': 'pdfmovimiento_hist.php?idmh=' + datos,
                        'autoScale': false,
                        'transitionIn': 'none',
                        'transitionOut': 'none',
                        'width': 970,
                        'height': 700,
                        'modal': false,
                        'type': 'iframe'
                    });
                }
 
 		function buscaemp(dato)
		{
			lista.document.location.replace("movimientos_historial_lista.php?consulta="+dato);
		}

        </script>


</head>
<body topmargin="0" leftmargin="0">
<div id="todo">
	<div id="cabeza_prin"></div>
    <div id="cuerpo">
    <div id="tituloarriba">
   		<div id="titulosup">Historial de Plazas</div>    
    </div>
    <div id="panelizq">
		<? include("menu.php"); ?>
    </div>
    <div id="centro_prin">
    	<form method="post" name="form1" action="#" target="lista">
            <table align="center">
                <tr>
                	<td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left" width="200"><label class="label">Fecha � Nombre del empleado:</label></td>
                    <td align="left" width="200"><input id="busca" name="busca"   class="campo" value="" onKeyUp="buscaemp(this.value);"></td>
                    <td align="left">
                    	<label class="label">Altas</label>
                        <input type="radio" value="1" name="r1" style="vertical-align:middle" onFocus="buscaemp('alta')" />
                        <label class="label">Bajas</label>
                        <input type="radio" value="1" name="r1" onFocus="buscaemp('baja')" style="vertical-align:middle" />
                        <label class="label">otros</label>
                        <input type="radio" value="1" name="r1" onFocus="buscaemp('modificacion')" style="vertical-align:middle" />
                        <label class="label">Todos</label>
                        <input type="radio" value="1" name="r1" onFocus="buscaemp('')" style="vertical-align:middle" />
                    </td>
                    <td align="right">
                    	<label class="label">Numero de entradas:</label>
                    	<input type="text" style="border:hidden;" id="numeroEntradas" size="2" />
                    </td>
                </tr>
              <tr>
              	<td colspan="4">
                	<iframe name="lista" id="lista" src="movimientos_historial_lista.php" style="width:950px; height:400px;"></iframe>
                </td>
              </tr>
            </table>
            
          </form>
          <p>&nbsp;</p>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Historial de Plazas</div>    
    </div>
</div>
</body>
</html>
