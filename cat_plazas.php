<?
session_start ();
if ($_SESSION ["m_sesion"] != 1) {
	echo "<script>";
	echo "location.replace('index.php');";
	echo "</script>";
	exit ();
}
require_once ('Connections/conexion.php');
//mysql_select_db ( $database_conexion, $conexion );

/*$query_programa = "SELECT * FROM cat_programa"; // ------------ Catalogo de Programas
$res_prog = mysql_query ( $query_programa, $conexion );
$query_subpy = "SELECT * FROM cat_proyecto"; // ------------ Catalaogo de Subprogramas
$res_subpy = mysql_query ( $query_subpy, $conexion );
mysql_select_db ( $database_conexion, $conexion );
*/
$query_categorias = "SELECT idcategoria, nivel, clave, descripcion, (sueldobase+hom) as sueldobase FROM cat_categoria order by descripcion"; // ------------ Catalogo de Categorias
$categorias = mysqli_query ( $conexion,$query_categorias ) or die ( mysqli_error () );
$row_categorias = mysqli_fetch_assoc ( $categorias );
$totalRows_categorias = mysqli_num_rows ( $categorias );
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">



<link rel="shortcut icon" type="image/x-icon" href="http://www.michoacan.gob.mx/wp-content/themes/estaenti/img/favicon.ico">
<link rel="stylesheet" type="text/css" href="css/estilos.css">
<link rel="stylesheet" type="text/css"
	href="controles_jquery/css/overcast/jquery-ui-1.10.3.custom.css">
<link rel="stylesheet" type="text/css"
	href="controles_jquery/js/jquery.fancybox.css">
<!--<script src="https://code.jquery.com/jquery-1.10.2.js"></script>-->
<script src="controles_jquery/js/jquery-1.11.3.min.js"></script>
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
	/**
		Execute event when press up a key from keyboard
	*/
function busca(sql){
	var boton=document.getElementById('consultap');
	boton.checked=false;
	parent.lista.document.location.replace('cat_plazas_lista.php?consultap='+boton.value);
}
function buscaVacante()
{
	var boton=document.getElementById('r1');
	boton.checked=false;
	//parent.lista.document.location.replace('cat_plazas_lista.php?consultap='+dato2.value+'&consulta='+dato);
	parent.lista.document.location.replace('cat_plazas_lista.php?consultap=VACANTE');
}
/*Search from imput througth input*/
function buscaVencidas(dato,dato2){
	var boton=document.getElementById('r1');
	boton.checked=false;
	parent.lista.document.location.replace('cat_plazas_lista.php?fecha='+dato+'&consulta='+dato2.value);
	
}
function buscaOcupadas()
{
	document.getElementById('r1').value=false;
	parent.lista.document.location.replace('cat_plazas_lista.php?consultap=OCUPADO');
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
			jQuery.ajax({
			      type		: "POST",
			      dataType	: "json",
			      url		: "ajax_programas.php", //Relative or absolute path to response.php file
			      success	: function(data) {
						jQuery.each(data,function(i,val){
							jQuery( "#programas" ).append("<option value="+val['idprograma']+">"+ val['clave'] +"</option>");
						});
				        
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
						jQuery.each(data,function(i,val){
							jQuery( "#subprogramas" ).append("<option value="+val['idsubprograma']+">"+ val['clave'] +"</option>");
						});
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
		var plaza_clave 	= jQuery( "#plaza_clave ").val();
		var ur 				= jQuery( "#getUR" ).val();
		var programa 		= jQuery( "#programas" ).val();
		var subprograma 	= jQuery( "#subprogramas" ).val();
		var categoria 		= jQuery( "#categoria" ).val();

		/*var nprogramas 	=	jQuery("#programas option").length;
		var nsubprogramas 	=	jQuery("#subprogramas option").length;
		var ncategoria		=	jQuery("#categoria option").length;*/
		
		var nprogramas 		= 	jQuery(" #programas ").val();
		var nsubprogramas	=	jQuery(" #subprogramas ").val();
		var ncategoria		=	jQuery(" #categoria ").val();
		/*Validate data before send if any element is unselect request focus is activate*/
		if(plaza_clave == '')	{document.getElementById("plaza_clave").focus();}
		if(nprogramas== -1)  	{document.getElementById("programas").focus();}
		if(nsubprogramas == -1)	{document.getElementById("subprogramas").focus();}
		if(ncategoria == -1)	{document.getElementById("categoria").focus();}
		
		if(plaza_clave != '' && nprogramas > -1 && nsubprogramas > -1 && ncategoria > -1){
			jQuery.ajax({
				type		: "POST",
				dataType	: "json",
				url 		: "jQuery_save_plaza.php",
				data		: 
							{	plaza_clave	:	plaza_clave,
								ur			: 	ur,
								programa	: 	programa,
								subprograma	: 	subprograma,
								categoria	:	categoria
							},
				success		: function (data) {
						alert(data['status']);					
					/*jQuery.each(data,function(i,val){
					});*/
				}
			});
		}
		else{
			alert("Debes de completar en el orden Clave, UR, programas, subprograma y categoria!");
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
							<td><input class="campo" name="plaza_clave" id="plaza_clave" placeholder="Introduce la clave" /></td>
						</tr>
						<tr valign="baseline">
							<!-- Start UR -->
							<td nowrap align="left"><label class="label">UR:</label></td>
							<td><select class="lista" id="getUR"
								onfocus="get_programas()">
									<option value=-1>selecciona</option>
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
								<select class="lista" id="programas"
								onfocus="get_subprogramas()">
									<option value="-1">Seleccione</option>
								</select>
							</td>
						</tr>
						<!-- Start Subprogramas -->
						<tr valign="baseline">
							<td nowrap align="left"><label class="label">Subprograma:</label></td>
							<td colspan="3">
								<select class="lista" id="subprogramas">
									<option value="-1">Seleccione</option>
								</select>
							</td>
							<!-- Block end after -->
						</tr>
						<!-- Start Categoria -->
						<tr valign="baseline">
							<td nowrap align="left"><label class="label">Categoria:</label></td>
							<td><select class="lista" name="categoria" id="categoria"
								onChange="cargasueldo(this.value);">
									<option value="-1">Seleccione</option>
                  			<?php
																		do {
																			?>
                  			<option
										value="<?php echo $row_categorias['idcategoria']; ?>"><?php echo $row_categorias['clave'], " ", $row_categorias['descripcion']?></option>
                  				<?php
																		} while ( $row_categorias = mysqli_fetch_assoc ( $categorias ) );
																		?>
                			</select><label class="label">*</label></td>

							
						</tr>
						<tr valign="baseline">
							<td align="left"></td>
								<td><div class="campo" id="ajax_sueldos" style="margin: -3px auto; width: 267px;"></div></td>
							
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
								type="radio" value="1" name="r1" id="r1" style="vertical-align: middle"
								onFocus="buscaVacante();" />
                    			
                    			<label class="label">Vencidas</label> 
                    			<?php $fecha=date("Y")."-".date("m")."-".date('d');?>
                    		<input type="radio" value="1" name="r1" id="r1"
								onFocus="buscaVencidas('<? echo $fecha?>',document.getElementById('consultap'))"
								style="vertical-align: middle" /> <label class="label">Ocupadas</label>
							<input type="radio" value="1" name="r1" id="r1"
								onFocus="buscaOcupadas()"
								style="vertical-align: middle" /> <label class="label">Todas</label>
							<input type="radio" value="-1" name="r1"
								onFocus="buscaVacante('',this)" style="vertical-align: middle" /></td>
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