<?
/**
 * @author zirangua mejia jose luis
 * @category view and controller throught jQuery library.
 * @copyright 2015-2018
 * @license GPLV3
 * @version 1.0
 */
session_start ();
if ($_SESSION ["m_sesion"] != 1) {
	echo "<script>";
	echo "location.replace('index.php');";
	echo "</script>";
	exit ();
}
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<title>Sistema de Nomina de Empleados</title>
<link rel="shortcut icon" type="image/x-icon"
	href="http://www.michoacan.gob.mx/wp-content/themes/mich2015/img/favicon.ico">

<link rel="stylesheet" type="text/css" href="css/estilos.css">
<link rel="stylesheet" type="text/css"
	href="controles_jquery/css/overcast/jquery-ui-1.10.3.custom.css">
<script src="controles_jquery/js/jquery-1.9.1.js"></script>
<script src="controles_jquery/js/jquery-ui-1.10.3.custom.js"></script>

<style type="text/css">
fieldset {
	float: left;
	width: 95%;
	heigth: 50%;
	border: solid gray 0.25em;
	border-radius: 5px;
}

fieldset:hover {
	background-color: #E6E6FA;
}

.btn-form {
	position: relative;
	top: 15px;
	left: 248px;
	width: 90px;
	height: 45px;
	border: solid gray 0.25em;
	border-top-left-radius: 5px;
	border-top-right-radius: 5px;
}

.btn-save-all {
	position: relative;
	top: 12px;
	left: 50px;
	width: 150px;
	height: 45px;
	border: solid gray 0.25em;
	border-top-left-radius: 5px;
	border-top-right-radius: 5px;
}
</style>
<script>
	/**
	*
	*Save into table cat_titular
	* WHERE colum firma
	*		1 = UPP
	* 		2 = UR
	*		3 = RH
	*/

	function set_upp(){
		var upp 		= jQuery( "#upp_titular" ).val();
		var upp_nombre 	= jQuery( "#upp_nombre" ).val();
		var upp_puesto 	= jQuery( "#upp_puesto" ).val();
		if(upp_nombre != '' && upp_puesto != ''){
			jQuery.ajax({
			      type		: "POST",
			      dataType	: "json",
			      url		: "jQuery_save_titulales.php", //Relative or absolute path to response.php file
			      data		: {
				      			upp 		: 	upp,
				      			upp_nombre	:	upp_nombre,
				      			upp_puesto	:	upp_puesto 
				      			},
			      success	: 
				  function(data) {
									jQuery.each(data,function(i,val){
									jQuery( "#programas" ).append("<option value="+val+">"+ val +"</option>");
								});
			      			}
			    })
		}
	}
	function set_ur(){
		var ur = jQuery( "#ur_titular" ).val();
		var ur_nombre = jQuery( "#ur_nombre" ).val();
		var ur_puesto = jQuery( "#ur_puesto" ).val();
		if(ur_nombre != '' && ur_puesto != ''){
		jQuery.ajax({
			      type		: "POST",
			      dataType	: "json",
			      url		: "jQuery_save_titulales.php", //Relative or absolute path to response.php file
			      data		: {
				      			ur 			: 	ur,
				      			ur_nombre	:	ur_nombre,
				      			ur_puesto	:	ur_puesto 
				      			},
			      success	: 
				  function(data) {
									jQuery.each(data,function(i,val){
									jQuery( "#programas" ).append("<option value="+val+">"+ val +"</option>");
								});
			      			}
			    })
			}
		}
		function set_rh(){
			var rh = jQuery( "#rh_titular" ).val();
			var rh_nombre = jQuery( "#rh_nombre" ).val();
			var rh_puesto = jQuery( "#rh_puesto" ).val();
			if(rh_nombre != '' && rh_puesto != ''){
				jQuery.ajax({
				      type		: "POST",
				      dataType	: "json",
				      url		: "jQuery_save_titulales.php", //Relative or absolute path to response.php file
				      data		: {
					      			rh 			: 	rh,
					      			rh_nombre	:	rh_nombre,
					      			rh_puesto	:	rh_puesto 
					      			},
				      success	: 
					  function(data) {
										jQuery.each(data,function(i,val){
										jQuery( "#programas" ).append("<option value="+val+">"+ val +"</option>");
									});
				      			}
				    })
				}
			}
		function save_all(){
			/**
			* Call to save all data incoming forms.
			**/
			var upp_nombre 	= jQuery( "#upp_nombre" ).val();
			var upp_puesto 	= jQuery( "#upp_puesto" ).val();
			var ur_nombre = jQuery( "#ur_nombre" ).val();
			var ur_puesto = jQuery( "#ur_puesto" ).val();
			var rh_nombre = jQuery( "#rh_nombre" ).val();
			var rh_puesto = jQuery( "#rh_puesto" ).val();
			if(upp_nombre != '' && upp_puesto != '' && ur_nombre != '' && ur_puesto != '' && rh_nombre != '' && rh_puesto != '' ){
				set_upp();
				set_ur();
				set_rh();
			}
			else{
				alert('Para usar esta funcionalidad debes de llenar todos los campos!');
			}
		}
</script>
</head>
<body topmargin="0" leftmargin="0">
	<div id="todo">
		<div id="cabeza_prin"></div>
		<div id="tituloarriba">
			<div id="titulosup">Movimientos Titulares</div>
		</div>
		<div id="cuerpo">
			<div id="panelizq">
					<?
					include ("menu.php");
					?>
				</div>
			<div id="centro_prin">
				<fieldset>
					<table style="text-align: center; position: relative; left: 250px;">
						<form name="form_upp" action="">
							<th colspan="2"><h2>TITULAR UPP</h2></th>
							<tr>
								<input type="hidden" name="upp" id="upp_titular" value="1">
							</tr>
							<tr>
								<td><label>NOMBRE</label></td>
								<td><input name="upp_nombre" id="upp_nombre" size="35" required></td>
							</tr>
							<tr>
								<td><label>PUESTO</label></td>
								<td><input name="upp_puesto" id="upp_puesto" size="35" required></td>
							</tr>
							<tr>
								<td><input class="btn-form" type="submit" name="submit_upp"
									value="Guardar" onclick="set_upp()"></td>
							</tr>

						</form>
					</table>
				</fieldset>
				<fieldset style="float: left; width: 95%; heigth: 50%;">
					<table style="text-align: center; position: relative; left: 250px;">
						<form name="form_ur" action="">
							<th colspan="2"><h2>TITULAR UR</h2></th>
							<tr>
								<input type="hidden" name="ur" id="ur_titular" value="2">
							</tr>
							<tr>
								<td><label>NOMBRE</label></td>
								<td><input name="ur_nombre" id="ur_nombre" size="35" required></td>
							</tr>
							<tr>
								<td><label>PUESTO</label></td>
								<td><input name="ur_puesto" id="ur_puesto" size="35" required></td>
							</tr>
							<tr>
								<td><input class="btn-form" type="submit" name="submit_ur"
									value="Guardar" onclick="set_ur()"></td>
							</tr>
						</form>
					</table>
				</fieldset>
				<fieldset style="float: left; width: 95%; heigth: 50%;">
					<table style="text-align: center; position: relative; left: 250px;">
						<form name="form_rh" action="">
							<th colspan="2"><h2>TITULAR RH</h2></th>
							<tr>
								<input type="hidden" name="rh" id="rh_titular" value="3">
							</tr>
							<tr>
								<td><label>NOMBRE</label></td>
								<td><input name="rh_nombre" id="rh_nombre" size="35" required></td>
							</tr>
							<tr>
								<td><label>PUESTO</label></td>
								<td><input name="rh_puesto" id="rh_puesto" size="35" required></td>
							</tr>
							<tr>
								<td><input class="btn-form" type="submit" name="submit_rh"
									value="Guardar" onclick="set_rh()"></td>
							</tr>
						</form>
					</table>
					<input class="btn-save-all" type="submit" name="submit_rh"
						value="Guardar TODO" onclick="save_all()">
				</fieldset>
			</div>
		</div>
		<div id="tituloabajo">
			<div id="tituloinf">Movimiento Titular</div>
		</div>
	</div>
</body>
</html>