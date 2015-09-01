<?
session_start ();
if ($_SESSION ["m_sesion"] != 1) {
	echo "<script>";
	echo "location.replace('index.php');";
	echo "</script>";
	exit ();
}
?>
<?php

// print_r ( $_GETimp );

require_once ('Connections/conexion.php');
include ("funcionesJL.php");

$editFormAction = $_SERVER ['PHP_SELF'];
if (isset ( $_SERVER ['QUERY_STRING'] )) {
	$editFormAction .= "?" . htmlentities ( $_SERVER ['QUERY_STRING'] );
}
// ----------------- Update de la plaza -----------------------
if ((isset ( $_POST ["MM_update"] )) && ($_POST ["MM_update"] == "form1")) {
	$updateSQL = "UPDATE cat_plazas SET plaza_clave='$_POST[clave]', ";
	if ($_POST ['subprograma'] != "") {
		$updateSQL .= "subprograma='$_POST[subprograma]',";
	}
	if ($_POST ['proyecto'] != "") {
		$updateSQL .= "proyecto='$_POST[proyecto]',";
	}
	$updateSQL .= "categoria='$_POST[categoria]' WHERE plaza_id='$_POST[idplaza]'";
	mysql_select_db ( $database_conexion, $conexion );
	$Result1 = mysql_query ( $updateSQL, $conexion ) or die ( mysql_error () );
	if ($_POST [idnominaemp] != 0) {
		$update_empleadosb = "UPDATE nominaemp SET sueldobase='$_POST[sueldobase]' where idnominaemp='$_POST[idnominaemp]'";
		mysql_query ( $update_empleadosb, $conexion );
	}
}

$colname_plaza = "-1";
if (isset ( $_GET ['idplaza'] )) {
	$colname_plaza = $_GET ['idplaza'];
}
// -------------------------------------- INFORMACION DE LA PLAZA -----------------------------------
mysql_select_db ( $database_conexion, $conexion );
$query_plazas = sprintf ( "SELECT CONCAT(nemp.paterno,' ',nemp.materno,' ',nemp.nombres) AS nombre, nemp.idnominaemp AS empleado_id, nemp.sueldobase as sueldobase, 
				 ep.estado AS plaza_estado,ep.fecha_inicial, ep.fecha_final, pz.plaza_clave AS plaza_clave, 
				 cpr.idprograma AS programa_id,cpr.descripcion AS programa_desc,
				 sp.idsubprograma AS subprograma_id, sp.descripcion AS subprograma_desc, ct.descripcion AS categoria_desc,ct.clave AS categoria_clave, 
				 ct.idcategoria AS categoria_id, pz.titular 
				 FROM cat_plazas pz 
				 INNER JOIN empleado_plaza ep ON ep.plaza_id=pz.plaza_id 
				 INNER JOIN nominaemp nemp ON nemp.idnominaemp=ep.idnominaemp 
				 INNER JOIN cat_categoria ct ON ct.clave=pz.categoria 
				 LEFT JOIN cat_subprograma sp ON sp.idsubprograma=pz.subprograma 
				 LEFT JOIN cat_programa cpr ON cpr.idprograma=sp.idprograma
			  	 WHERE pz.plaza_id = %s", GetSQLValueString ( $colname_plaza, "int" ) );

$plazas = mysql_query ( $query_plazas, $conexion ) or die ( mysql_error () );
$row_plazas = mysql_fetch_assoc ( $plazas );
$totalRows_plazas = mysql_num_rows ( $plazas );

$query_programa = "SELECT * FROM cat_programa"; // ------------ Catalogo de Programas
$res_prog = mysql_query ( $query_programa, $conexion );
$query_subpy = "SELECT * FROM cat_proyecto"; // ------------ Catalaogo de Subprogramas
$res_subpy = mysql_query ( $query_subpy, $conexion );
mysql_select_db ( $database_conexion, $conexion );
$query_categorias = "SELECT idcategoria, nivel, clave, descripcion, (sueldobase+hom) as sueldobase FROM cat_categoria order by descripcion"; // ------------ Catalogo de Categorias
$categorias = mysql_query ( $query_categorias, $conexion ) or die ( mysql_error () );
$row_categorias = mysql_fetch_assoc ( $categorias );
$totalRows_categorias = mysql_num_rows ( $categorias );
?>
<!doctype html>
<html>
<head>
<title>Movimientos Personal</title>
<meta charset="iso-8859-1">
<link rel="shortcut icon" type="image/x-icon"
	href="http://www.michoacan.gob.mx/wp-content/themes/mich2015/img/favicon.ico">
	

<link rel="stylesheet" type="text/css" href="css/estilos.css">
<link rel="stylesheet" type="text/css" href="controles_jquery/css/overcast/jquery-ui-1.10.3.custom.css">
<link rel="stylesheet" type="text/css" href="controles_jquery/js/jquery.fancybox.css">
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="controles_jquery/js/jquery-ui-1.10.3.custom.js"></script>
<script src="controles_jquery/js/jquery.fancybox.js"></script>
<script>

var a = jQuery.noConflict();
a(document).ready(function(){
	
	a(function() {
		a("#fecha").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-90:+0"
		});
		a("#fecha").datepicker( "option", "showAnim", "show");
		a("#fecha").datepicker( "option", "dateFormat", "yy-mm-dd" );

		a("#fecha2").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-90:+0"
		});
		a("#fecha2").datepicker( "option", "showAnim", "show");
		a("#fecha2").datepicker( "option", "dateFormat", "yy-mm-dd" );
		
		a("#fechab").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-90:+0"
		});
		a("#fechab").datepicker( "option", "showAnim", "show");
		a("#fechab").datepicker( "option", "dateFormat", "yy-mm-dd" );
	});
	$("#tipoContrato").change(function() {
		var tipoc=document.getElementById('tipoContrato').value;
		if( tipoc== 2 || tipoc==5 || tipoc==6 || tipoc==4)
		{
			document.getElementById('fi').style.display="block";
			document.getElementById('ff').style.display="block";
		}
		if( tipoc== 1 || tipoc==3)
		{
			document.getElementById('fi').style.display="block";
			document.getElementById('ff').style.display="none";
		}
	});	
});

</script>
<script>
function movimiento(){
	var empleado=document.getElementById('idempleado');
	var tipoContrato=document.getElementById('tipoContrato');
	var plaza=<? echo $colname_plaza?>;
	var fecha=document.getElementById('fecha');
	var fecha2=document.getElementById('fecha2');
	var tipoAsignacion=document.getElementById('tipoAsignacion')
	if(empleado.value==-1){
		alert('Elija a un empleado');
	}
	else if(tipoContrato.value==-1){
		alert('Elija a un tipo de Movimiento');
	}
	else{
		formato(empleado.value,tipoContrato.value,plaza,fecha.value,fecha2.value,'A',tipoAsignacion);
	}
}
function movimientob(){
	var empleado=<? echo $row_plazas['empleado_id']?>;
	var tipoContrato=document.getElementById('tipoBaja');
	var plaza=<? echo $colname_plaza?>;
	var fecha=document.getElementById('fechab');
	var fecha2=document.getElementById('fecha2');
	var estado=document.getElementById('estado');
	if(estado.value==-1){
		alert('Elija es estado para liberar la plaza');
	}
	else if(tipoContrato.value==-1){
		alert('Elija a un tipo de Movimiento');
	}
	else{
		formato(empleado,tipoContrato.value,plaza,fecha.value,fecha2.value,estado.value,'');
	}
}
</script>
<link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>
<body topmargin="0" leftmargin="0">
	<div id="todo">
		<div id="cabeza_prin"></div>
		<div id="cuerpo">
			<div id="tituloarriba">
				<div id="titulosup">Manejador de Plazas</div>
			</div>
			<div id="panelizq">
			<? include("menu.php"); ?>
      </div>
      	<?php
							/**
							 * Get information from table cat_plazas.
							 */
							$id = $_GET ['idplaza'];
							$query = " select * from cat_plazas where plaza_id=" . $id;
							$rs = mysql_query ( $query, $conexion );
							$row = mysql_fetch_assoc ( $rs );
							// print_r ( $row );
							
							?>
      	<script type="text/javascript">
			/**
			*	FancyBox.
			*	If some date isnt correct the user can search the right value
			*	<<<<<< The data will arrive from table cat_plazas>>>>>>>
			**/
			function getClave(){
				alert("All is working");
				jQuery.fancybox({
						'title'				: 'Selecciona Claves plazas',
						'href'				: 'http://www.google.com',
						'autoScale'			: true,
						'transitionIn'		: 'elastic',
						'transitionOut'		: 'elastic',
						'width'				: 950,
						'height'			: 688,
						'modal'				: false,
						'type'				: 'iframe'
					});
			
			}
			function getUR(){}
			function getPrograma(){}
			function getSubprograma(){
			}
			function getCategoria(){}
			function getTitular(){}
			

      	</script>
<script>
/**
 * Load values into comboBox on cascade 
 * Made by Jose Luis Zirangua Mejia
 */
	function get_programas(){
		var ur = jQuery( "#getUR" ).val();
		jQuery.ajax({
			      type		: "POST",
			      dataType	: "json",
			      url		: "ajax_programas.php", //Relative or absolute path to response.php file
			      data		: {id_ur : ur },
			      success	: function(data) {
			        /*$(".the-return").html(
			          "Favorite beverage: " + data["favorite_beverage"] + "<br />Favorite restaurant: " + data["favorite_restaurant"] + "<br />Gender: " + data["gender"] + "<br />JSON: " + data["json"]
			        );*/
			        //alert("Form submitted successfully.\nReturned json: " + data["2"]);
				        if(ur != 0){
				        	jQuery( "#programas" ).empty();
							jQuery( "#subprogramas" ).empty();
								jQuery.each(data,function(i,val){
									jQuery( "#programas" ).append("<option value="+val+">"+ val +"</option>");
								});
				        }
						else{
							jQuery( "#programas" ).empty();
							jQuery( "#programas" ).append('<option value="0">Seleccione</option>');
						}
			      }
			    });
	}
	function get_subprogramas(){
			var programa = jQuery( "#programas" ).val();
			jQuery.ajax({
				type		: "POST",
				dataType	: "json",
				url 		: "jQuery_suprogramas.php",
				data		: {id_programa: programa},
				success		: function (data) {
					if(programa != 0){
						jQuery( "#subprogramas" ).empty();
						jQuery.each(data,function(i,val){
							jQuery( "#subprogramas" ).append("<option value="+val+">"+ val +"</option>");
						});
				}
				else{
					jQuery( "#subprogramas" ).empty();
					jQuery( "#subprogramas" ).append('<option value="0">Seleccione</option>');
					}
				}
			});		
	}
</script>
			<div id="centro_prin">
				<h3 style="color: #666; margin-left: 50px">EDITAR LA PLAZA</h3>
				<form method="post" name="form1" action="#">
					<table align="left">
						<tr valign="baseline" colspan="-2">
							<td nowrap align="LEFT"><label class="label" name="plaza_id"></label></td>
							<td><input type="hidden" name="clave"
								value="<?php echo $row['plaza_id']; ?>" size="30" maxlength="4"></td>
						</tr>
						<tr valign="baseline">
							<td nowrap align="LEFT"><label class="label">CLAVE:</label></td>
							<td><input class="campo" type="text" name="clave"
								value="<?php echo $row['plaza_clave'];; ?>" size="30"
								maxlength="4" placeholder="Dato no capturado" required onclick="getClave()">*
							</td>
						</tr>
												<tr valign="baseline">
							<!-- Start UR -->
							<td nowrap align="left"><label class="label">UR:</label></td>
							<td><select id="getUR" style="width: 180px;"
								onchange="get_programas()">
									<option value="0">Seleccione</option>
									<option value="01">01</option>
									<option value="02">02</option>
									<option value="03">03</option>
									<option value="04">04</option>
									<option value="05">05</option>
									<option value="06">06</option>
									<option value="07">07</option>
									<option value="08">08</option>
									<option value="09">09</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19">19</option>
							</select></td>
						</tr>
						<!-- Start programas -->
						<tr valign="baseline">
							<td nowrap align="left"><label class="label">Programa:</label></td>
							<td colspan="3">
								<select id="programas" style="width: 180px;"
								onchange="get_subprogramas()">
									<option value="0">Seleccione</option>
								</select>
							</td>
						</tr>
						<!-- Start Subprogramas -->
						<tr valign="baseline">
							<td nowrap align="left"><label class="label">Subprograma:</label></td>
							<td colspan="3">
								<select id="subprogramas" style="width: 180px;">
									<option value="0">Seleccione</option>
								</select>
							</td>
							<!-- Block end after -->
						</tr>
						<!-- Start Categoria -->
						<tr valign="baseline">
							<td nowrap align="left"><label class="label">Categoria:</label></td>
							<td><select name="categoria" style="width: 180px;"
								onChange="cargasueldo(this.value);">
									<option value="">Seleccione</option>
                  			<?php
																		do {
																			?>
                  			<option
										value="<?php echo $row_categorias['idcategoria']; ?>"><?php echo $row_categorias['clave'], " ", $row_categorias['descripcion']?></option>
                  				<?php
																		} while ( $row_categorias = mysql_fetch_assoc ( $categorias ) );
																		?>
                			</select><label class="label">*</label></td>

							
						</tr>

						<tr>
							<td nowrap align="left"><label class="label">TITULAR:</label></td>
							<td colspan="2"><input type="text" class="campo" name="titular"
								value="<?php echo $row['titular'];?>" size="30"
								placeholder="Dato no capturado" required>*
						
						</tr>

						<tr valign="baseline">
							<td align="center" colspan="3">&nbsp;</td>
						</tr>
						<tr valign="baseline">
							<td align="right" colspan="3"><input type="hidden"
								name="MM_insert" value="form1"> <input class="boton"
								type="button" name="guardar" id="guardar" value="GUARDAR"
								onClick="submit();"> <input class="boton" type="button"
								name="Regresar" value="Regresar" align="left"
								onClick="window.location.href='cat_plazas.php'"></td>
						</tr>
						<tr valign="baseline">
							<td>
								<p style="size: 9px; font: cursive;">* Datos requeridos</p>
							</td>
						</tr>
					</table>
					<input type="hidden" name="MM_update" value="form1"> <input
						type="hidden" name="idplaza" value="<?php echo $colname_plaza; ?>">
				</form>

				<!--   -------------------------------   Asignacion de la plaza desde el catalogo ------------------------------>
          <?
										
										if ($row_plazas ['plaza_estado'] == "VACANTE") {
											$estilo = 'style="display:block"';
											$estilo2 = 'style="display:none"';
										} else {
											$estilo = 'style="display:none"';
											$estilo2 = 'style="display:block"';
										}
										?>
          <div <? echo $estilo?> style="width: 400px; position: fixed;">
					<hr />
					<h3 style="color: #666; margin-left: 50px">ASIGNAR LA PLAZA</h3>
          <?php
										$select_empelado = "SELECT idnominaemp,CONCAT(paterno,' ',materno,' ',nombres) as nombre FROM nominaemp";
										$res_empleado = mysql_query ( $select_empelado, $conexion );
										?>
          <form name="f1">
						<table align="right">
							<tr>
								<td nowrap align="left"><label class="label">Empleado:</label></td>
								<td><select name="idempleado" id="idempleado">
										<OPTION value="-1">Seleccione..</OPTION>
                <?
																while ( $ren_emp = mysql_fetch_array ( $res_empleado ) ) {
																	$select_disponible = "SELECT plaza_id FROM empleado_plaza WHERE idnominaemp=$ren_emp[idnominaemp]";
																	echo $select_disponible;
																	$respuesta = mysql_query ( $select_disponible, $conexion );
																	$numero = mysql_num_rows ( $respuesta );
																	if ($numero < 1) {
																		?>
                            	<option
											value="<? echo $ren_emp[idnominaemp]?>"><? echo $ren_emp[nombre]?></option>
							<?
																	}
																}
																?>
                </select></td>
							
							
							<tr>
								<td nowrap align="left"><label class="label">Tipo de
										Contratacion:</label></td>
								<td><select name="tipoContrato" id="tipoContrato">
										<option value="-1">Seleccione</option>
                    <?
																				$sql = "Select * from cat_movimientos where tipo='Alta' order by descripcion";
																				$res = mysql_query ( $sql, $conexion );
																				while ( $ren = mysql_fetch_array ( $res ) ) {
																					?>
                    	<option value="<? echo $ren[idmovimiento]?>"><? echo "(".$ren[clave].") ".$ren[descripcion]?></option>
                    <?
																				}
																				?>
                    	</select></td>
							</tr>
							<tr>
								<td nowrap align="left"><label class="label">Tipo de Asignaci�n:</label></td>
								<td><select name="tipoAsignacion" id="tipoAsignacion">
										<option value="ASIGNACION">ASIGNACION</option>
										<option value="PERMISO">PERMISO</option>
										<option value="TITULARIDAD">TITULARIDAD</option>
										<option value="OTRO">OTRO</option>
								</select></td>
							</tr>
							<tr>
								<td nowrap align="left"><label class="label">Fechas</label></td>
								<td>
									<table>
										<tr>
											<td>
												<div id="fi" style="display: none">
													<label class="label">Inicio:</label><br> <input
														class="campo" type="text" size="6" required name="fecha"
														id="fecha" value="">
												</div>
											</td>
											<td>
												<div id="ff" style="display: none">
													<label class="label">Fin:</label><br> <input class="campo"
														type="text" size="6" name="fecha2" id="fecha2" value="">
												</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							</tr>

							<tr>
								<td align="right" colspan="2"><input type="button" class="boton"
									onClick="movimiento();" value="Asignar"></td>

							</tr>
						</table>
					</form>
				</div>
				<!--   -------------------------   Liberacion de la plaza desde el catalogo ---------------------------------->
				<div <?php echo $estilo2?>>
				
					
					
          <?php
										$select_empelado = "SELECT idnominaemp,CONCAT(paterno,' ',materno,' ',nombres) as nombre FROM nominaemp";
										$res_empleado = mysql_query ( $select_empelado, $conexion );
										?>
           <form name="f1">
						<table align="right">
							<h3
								style="color: #666; margin-left: 50px; position: relative; top: -40px; right: -245px;">LIBERAR
								LA PLAZA</h3>
							<tr>
								<td nowrap align="left"><label class="label">Estado:</label></td>
								<td><select name="estado" id="estado">
										<option value="A">Seleccione..</option>
										<option value="VACANTE">VACANTE</option>
								</select></td>
							</tr>
							<tr>
								<td nowrap align="left"><label class="label">Tipo de Baja:</label></td>
								<td><select name="tipoBaja" id="tipoBaja" style="width: 300px">
										<option value="-1">Seleccione..</option>
                    <?
																				$sql = "Select * from cat_movimientos where tipo='Baja' order by descripcion";
																				$res = mysql_query ( $sql, $conexion );
																				while ( $ren = mysql_fetch_array ( $res ) ) {
																					?>
                    	<option value="<? echo $ren[idmovimiento]?>"><? echo "(".$ren[clave].") ".$ren[descripcion]?></option>
                    <?
																				}
																				?>
                    	</select></td>
							</tr>
							<tr>
								<td nowrap align="left"><label class="label">Fecha de Baja</label></td>
								<td><input class="campo" type="text" size="6" name="fechab"
									id="fechab" value=""></td>
							</tr>
							<tr>
								<td align="right" colspan="2"><input type="button" class="boton"
									onClick="movimientob();" value="Liberar"></td>

							</tr>
						</table>
					</form>
				</div>
			</div>
			<div id="tituloabajo">
				<div id="tituloinf">Plazas</div>
			</div>
		</div>

	</div>
</body>
</html>
<?php
// mysql_free_result($programas);
?>
