<?
session_start ();
if ($_SESSION ["m_sesion"] != 1) {
	echo "<script>";
	echo "location.replace('index.php');";
	echo "</script>";
	exit ();
}
require_once ('Connections/conexion.php');
mysql_select_db ( $database_conexion, $conexion );

/*$query_programa = "SELECT * FROM cat_programa"; // ------------ Catalogo de Programas
$res_prog = mysql_query ( $query_programa, $conexion );
$query_subpy = "SELECT * FROM cat_proyecto"; // ------------ Catalaogo de Subprogramas
$res_subpy = mysql_query ( $query_subpy, $conexion );
mysql_select_db ( $database_conexion, $conexion );
*/
$query_categorias = "SELECT idcategoria, nivel, clave, descripcion, (sueldobase+hom) as sueldobase FROM cat_categoria order by descripcion"; // ------------ Catalogo de Categorias
$categorias = mysql_query ( $query_categorias, $conexion ) or die ( mysql_error () );
$row_categorias = mysql_fetch_assoc ( $categorias );
$totalRows_categorias = mysql_num_rows ( $categorias );
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">



<link rel="shortcut icon" type="image/x-icon"
	href="http://www.michoacan.gob.mx/wp-content/themes/mich2015/img/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/estilos.css">
<link rel="stylesheet" type="text/css"
	href="controles_jquery/css/overcast/jquery-ui-1.10.3.custom.css">
<link rel="stylesheet" type="text/css"
	href="controles_jquery/js/jquery.fancybox.css">
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="controles_jquery/js/jquery-ui-1.10.3.custom.js"></script>
<script src="controles_jquery/js/jquery.fancybox.js"></script>
<title>Sistema de Movimientos</title>
<link rel="stylesheet" type="text/css" href="css/estilos.css">

<script>
function valida(form)
{
	if(form.clave.value == "")
	{
		alert("Indique la clave del programa");
		form.clave.focus();
		return false;
	}
	if(form.programa.value == "")
	{
		alert("Indique el programa");
		form.clave.focus();
		return false;
	}
	if(form.subprograma.value == "")
	{
		alert("Indique el subprograma");
		form.clave.focus();
		return false;
	}
	if(form.proyecto.value == "")
	{
		alert("Indique el  proyecto");
		form.clave.focus();
		return false;
	}
	if(form.categoria.value == "")
	{
		alert("Indique la categoria");
		form.clave.focus();
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
      var letra = e.keyCode;
    else
      var letra = e.which;
    if((letra < 48 || letra > 57 && letra != 37 && letra != 38 && letra != 39 && letra != 40 && letra != 8 && letra != 46))
       return false;
    else
       return true;
}
</script>
<script>
function busca(dato,dato2)
{
	var boton=document.getElementById('r1');
	alert(dato2.value);
	boton.checked=false;
	//parent.lista.document.location.replace('cat_plazas_lista.php?consultap='+dato2.value+'&consulta='+dato);
	parent.lista.document.location.replace('iframe-vacantes.php?consultap='+dato2.value+'&consulta='+dato);
	
}
function busca(sql){
	var boton=document.getElementById('consultap');
	//alert(boton.value);
	boton.checked=false;
	parent.lista.document.location.replace('cat_plazas_lista.php?consultap='+boton.value);
}
function busca2(dato,dato2)
{
	document.getElementById('r1').value=false;
	parent.lista.document.location.replace('cat_plazas_lista.php?fecha='+dato+'&consulta='+dato2.value);

}

function sueldo(dato)
{
	if(dato != "")
	{
		var id = dato.substr(0, dato.indexOf("|", dato));
		dato = dato.substr(dato.indexOf("|", dato) + 1);
		
		var sueldob = dato.substr(0, dato.indexOf("|", dato));
		dato = dato.substr(dato.indexOf("|", dato) + 1);
		
		var nivel = dato.substr(0);

		form1.sueldobase.value = sueldob;
		form1.plaza.value = nivel;
	}
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

/**
 * This function place the results in the selected ID
 */
function carga(Resultado,ajax)
{
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) 
		{
			Resultado.innerHTML = ajax.responseText.tratarResponseText();
		}
	};
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
//--------------------------  Carga el sueldo correspondiente a la categoria solo para revisar
function cargasueldo(idcategoria)
{
	Resultado = document.getElementById('ajax_sueldos');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_sueldos.php",true);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idcategoria="+idcategoria);
}

</script>
<script>
/**
 * Load values into comboBox on cascade
 * Made by Jose Luis Zirangua Mejia
 */
	function get_programas(){
		var ur = $( "#getUR" ).val();
			$.ajax({
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
							$( "#programas" ).empty();
							$( "#subprogramas" ).empty();
								jQuery.each(data,function(i,val){
									$( "#programas" ).append("<option value="+val+">"+ val +"</option>");
								});
				        }
						else{
							$( "#programas" ).empty();
							$( "#programas" ).append('<option value="0">Seleccione</option>');
						}
			      }
			    });
	}
	function get_subprogramas(){
			var programa = $( "#programas" ).val();
			$.ajax({
				type		: "POST",
				dataType	: "json",
				url 		: "jQuery_suprogramas.php",
				data		: {id_programa: programa},
				success		: function (data) {
					if(programa != 0){
						$( "#subprogramas" ).empty();
						jQuery.each(data,function(i,val){
							$( "#subprogramas" ).append("<option value="+val+">"+ val +"</option>");
						});
				}
				else{
						$( "#subprogramas" ).empty();
						$( "#subprogramas" ).append('<option value="0">Seleccione</option>');
					}
				}
			});		
	}
</script>

<script>
/**
 * Save plaza data.
 * Script Finished
 */
	function save_plaza(){
		var plaza_clave = jQuery( "#plaza_clave ").val();
		var ur = $( "#getUR" ).val();
		var programa = $( "#programas" ).val();
		var subprograma = $( "#subprogramas" ).val();
		var categoria = jQuery( "#categoria" ).val();
		if(plaza_clave === ''){document.getElementById("plaza_clave").focus();}
		if(plaza_clave != '' && ur != 0 && programa != null && subprograma != null ){
			jQuery.ajax({
				type		: "POST",
				dataType	: "json",
				url 		: "jQuery_save_plaza.php",
				data		: {	plaza_clave	:	plaza_clave,
								ur			: 	ur,
								programa	: 	programa,
								subprograma	: 	subprograma,
								categoria	:	categoria
								},
				success		: function (data) {
					
					jQuery.each(data,function(i,val){
						alert("Operacion Exitosa!"+val);
					});
				}
			});
		}
		else{
			alert("Debes de completar todos los campos");
		}
			
	}
</script>
</head>
<body topmargin="0" leftmargin="0">
	<div id="todo">
		<div id="cabeza_prin"></div>
		<div id="cuerpo">
			<div id="tituloarriba">
				<div id="titulosup">Plazas</div>
			</div>
			<div id="panelizq">
		<? include("menu.php"); ?>
    </div>
			<div id="centro_prin">
				<form method="post" name="form1" action="cat_plazas_lista.php"
					target="lista">
					<table align="center">
						<tr valign="baseline">
							<!-- Start Clave segment -->
							<td nowrap align="left"><label class="label">CLAVE:</label></td>
							<td><input name="plaza_clave" id="plaza_clave" placeholder="Introduce la clave"/></td>
						</tr>
						<tr valign="baseline">
							<!-- Start UR -->
							<td nowrap align="left"><label class="label">UR:</label></td>
							<td><select id="getUR" style="width: 180px;"
								onfocus="get_programas()">
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
								onfocus="get_subprogramas()">
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
							<td><select name="categoria" id="categoria" style="width: 180px;"
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
						<tr valign="baseline">
							<td align="left"></td>
								<td><div id="ajax_sueldos" style="margin: -3px auto; width: 267px;"></div></td>
							
							<script>
								cargasueldo('');
							</script>
						</tr>
						
						<tr valign="baseline">
							<!-- Check sql command insert -->
							<td colspan="3" align="left"><input type="hidden"
								name="MM_insert" value="form1"> <input class="boton"
								type="button" name="guardar" id="guardar" value="GUARDAR"
								onClick="save_plaza();"></td>
							<td>&nbsp;</td>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						
						<tr valign="baseline">
							<td colspan="2"><label class="label">Consulta por Plaza:</label>
								<input class="campo" type="text" name="consultap" id="consultap"
								onkeyup="busca(this.value);"></td>
						</tr>
						<tr>
							<td colspan="6">
								<hr>
							</td>
						</tr>
						<tr>
						<!-- Im here edit, right now -->
							<td colspan="3"><label class="label">Vacantes</label> 
							<input
								type="radio" value="1" name="r1" id="r1"
								style="vertical-align: middle"
								onFocus="busca('VACANTE',document.getElementById('consultap'))" />
                    			<? $fecha=date("Y")."-".date("m")."-".date('d');?>
                    			<label class="label">Vencidas</label> 
                    		<input
								type="radio" value="1" name="r1" id="r1"
								onFocus="busca2('<? echo $fecha?>',document.getElementById('consultap'))"
								style="vertical-align: middle" /> <label class="label">Ocupadas</label>
							<input type="radio" value="1" name="r1" id="r1"
								onFocus="busca('OCUPADO',document.getElementById('consultap'))"
								style="vertical-align: middle" /> <label class="label">Todas</label>
							<input type="radio" value="-1" name="r1"
								onFocus="busca('',this)" style="vertical-align: middle" /></td>
						</tr>
						<tr valign="baseline">
							<label class="label">Numero de entradas:</label>
							<input type="text" style="border: hidden;" id="numeroEntradas"
								size="2" />
							</td>
						</tr>
						<tr>
							<td colspan="6">
								<hr>
							</td>
						</tr>

						<tr valign="baseline">
							<td colspan="4"><iframe name="lista" id="lista"
									src="cat_plazas_lista.php" style="width: 950px; height: 400px;"></iframe>
							</td>
						</tr>
					</table>

				</form>
				<p>&nbsp;</p>
			</div>
		</div>
		<div id="tituloabajo">
			<div id="tituloinf">Programas</div>
		</div>
	</div>
</body>
</html>