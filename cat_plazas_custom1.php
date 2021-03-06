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
$query_programa = "SELECT * FROM cat_programa";
// ------------ Catalogo de Programas
$res_prog = mysqli_query ( $conexion,$query_programa );
$query_subpy = "SELECT * FROM cat_proyecto";
// ------------ Catalaogo de Subprogramas
$res_subpy = mysql_query ( $query_subpy, $conexion );
//mysql_select_db ( $database_conexion, $conexion );
$query_categorias = "SELECT idcategoria, nivel, clave, descripcion, (sueldobase+hom) as sueldobase FROM cat_categoria order by descripcion";
// ------------ Catalogo de Categorias
$categorias = mysqli_query ( $conexion,$query_categorias ) or die ( mysql_error () );
$row_categorias = mysqli_fetch_assoc ( $categorias );
$totalRows_categorias = mysqli_num_rows ( $categorias );
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link type="image/x-icon" href="imagenes/logomich200.ico" rel="icon" />
<link type="image/x-icon" href="imagenes/logomich200.ico"
	rel="shortcut icon" />
<title>Sistema de Movimientos</title>
<link rel="stylesheet" type="text/css" href="css/estilos.css">

<script>
	function valida(form) {
		if (form.clave.value == "") {
			alert("Indique la clave del programa");
			form.clave.focus();
			return false;
		}
		if (form.programa.value == "") {
			alert("Indique el programa");
			form.clave.focus();
			return false;
		}
		if (form.subprograma.value == "") {
			alert("Indique el subprograma");
			form.clave.focus();
			return false;
		}
		if (form.proyecto.value == "") {
			alert("Indique el  proyecto");
			form.clave.focus();
			return false;
		}
		if (form.categoria.value == "") {
			alert("Indique la categoria");
			form.clave.focus();
			return false;
		}
		return true;
	}

	function sololetras(form, e) {
		if (e.keyCode != 0)
			letra = e.keyCode;
		else
			letra = e.which;

		if (letra >= 48 && letra <= 57)
			return false;
		else
			return true;
	}

	function solonumeros(form, e) {
		if (e.keyCode != 0)
			letra = e.keyCode;
		else
			letra = e.which;

		if ((letra < 48 || letra > 57) && letra != 37 && letra != 38 && letra != 39 && letra != 40 && letra != 8 && letra != 46)
			return false;
		else
			return true;
	}
</script>
<script>
	/**
	 * through Ajax
	 * busca
	 * busca2
	 * sueldo
	 */
	function busca(dato, dato2) {
		var boton = document.getElementById('r1');
		boton.checked = false;
		parent.lista.document.location.replace('cat_plazas_lista.php?consultap=' + dato2.value + '&consulta=' + dato);

	}
	function busca(dato)
	{
		parent.lista.document.location.replace('cat_plazas_lista.php?consultap='+dato;
	}

	function busca2(dato, dato2) {
		document.getElementById('r1').value = false;
		parent.lista.document.location.replace('cat_plazas_lista.php?fecha=' + dato + '&consulta=' + dato2.value);

	}

	/**
	 *Check salary
	 */
	function sueldo(dato) {
		if (dato != "") {
			var id = dato.substr(0, dato.indexOf("|", dato));
			dato = dato.substr(dato.indexOf("|", dato) + 1);

			var sueldob = dato.substr(0, dato.indexOf("|", dato));
			dato = dato.substr(dato.indexOf("|", dato) + 1);

			var nivel = dato.substr(0);

			form1.sueldobase.value = sueldob;
			form1.plaza.value = nivel;
		}
	}

	function objetoAjax() {
		var xmlHttp = null;
		if (window.ActiveXObject)
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		else if (window.XMLHttpRequest)
			xmlHttp = new XMLHttpRequest();
		return xmlHttp;
	}

	function carga(Resultado, ajax) {
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4) {
				Resultado.innerHTML = ajax.responseText.tratarResponseText();
			}
		}
	}


	String.prototype.tratarResponseText = function() {
		var pat = /<script[^>]*>([\S\s]*?)<\/script[^>]*>/ig;
		var pat2 = /\bsrc=[^>\s]+\b/g;
		var elementos = this.match(pat) || [];
		for ( i = 0; i < elementos.length; i++) {
			var nuevoScript = document.createElement('script');
			nuevoScript.type = 'text/javascript';
			var tienesrc = elementos[i].match(pat2) || [];
			if (tienesrc.length) {
				nuevoScript.src = tienesrc[0].split("'").join('').split('"').join('').split('src=').join('').split(' ').join('');
			} else {
				var elemento = elementos[i].replace(pat, '$1', '');
				nuevoScript.text = elemento;
			}
			document.getElementsByTagName('body')[0].appendChild(nuevoScript);
		}
		return this.replace(pat, '');
	}
	//--------------------------  Carga el sueldo correspondiente a la categoria solo para revisar
	function cargasueldo(idcategoria) {
		Resultado = document.getElementById('ajax_sueldos');
		ajax = objetoAjax();
		ajax.open("POST", "ajax_sueldos.php", false);
		carga(Resultado, ajax);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("idcategoria=" + idcategoria);
	}

	//--------------------------  Carga el sueldo correspondiente a la categoria por medio de la plaza elegida

	/* Carga los subprogramas a partir de un id de programa*/
	function programas(id) {
		Resultado = document.getElementById('ajax_programas');
		ajax = objetoAjax();
		ajax.open("POST", "ajax_programas.php", false);
		carga(Resultado, ajax);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("idprograma=" + id);
	}

	/* Carga los proyectos a partir de un id de subprograma*/
	function proyecto(id) {
		Resultado = document.getElementById('ajax_proyecto');
		ajax = objetoAjax();
		ajax.open("POST", "ajax_proyectos.php", false);
		carga(Resultado, ajax);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("idsprograma=" + id);
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
		<?
		include ("menu.php");
		?>
    </div>
			<div id="centro_prin">
				<form method="post" name="form1" action="cat_plazas_lista.php"
					target="lista">
					<table>
						<tr valign="baseline">
							<td nowrap align="left"><label class="label">Clave:</label></td>
							<td colspan="3"><input class="campo" type="text" name="clave"
								value="" size="4" maxlength="4"></td>
						</tr>
						<tr>
							<td nowrap align="left"><label class="label">Programa:</label></td>
							<td colspan="3"><select name="programa" class="lista"
								style="width: 180px;" onChange="programas(this.value);">
									<option value="">Seleccione</option>
                  			<?php
																					do {
																						?>
                  			<option
										value="<?php echo $row_prog['idprograma']?>"><?php echo $row_prog['descripcion']?></option>
                  			<?php
																					} while ( $row_prog = mysqli_fetch_assoc ( $res_prog ) );
																					?>
                	</select></td>
                	</tr>
						<!-- 
						start block sub-programa 
						-->
					<tr valign="baseline">
							<td nowrap align="left"><label class="label">Subprograma:</label></td>
							<td colspan="3"><select name="subprograma" class="lista"
								style="width: 180px;" onChange="proyecto(this.value);">
									<option value="">Seleccione</option>
                  			<?php
																					do {
																						?>
                  			<option
										value="<?php echo $row_prog['idprograma']?>"><?php echo $row_prog['descripcion']?></option>
                  			<?php
																					} while ( $row_prog = mysqli_fetch_assoc ( $res_prog ) );
																					?>
                	</select></td>
									
                			
					</tr>

						<!-- 
						Start block Categoria 
						-->
						<tr valign="baseline">
							<td nowrap align="left"><label class="label">Categor&#205;a:</label></td>
							<td><select name="categoria" class="lista" style="width: 186px;"
								onChange="cargasueldo(this.value);">
									<option value="">Seleccione</option>
                  <?php
						do {
				  ?>
                  <option value="<?php echo $row_categorias['idcategoria']; ?>"><?php echo $row_categorias['clave'], " ", $row_categorias['descripcion']?>
                  </option>
                  <?php
					} while ( $row_categorias = mysqli_fetch_assoc ( $categorias ) );
				  ?>
                </select><label class="label">*</label></td>
                

							<!--<td align="left">
								<div id="ajax_sueldos" style="margin: -3px auto; width: 267px;"></div>
							</td>-->
						</tr>

				<script>
					//cargasueldo(' ');
				</script>
						<tr valign="baseline">
							<td colspan="3" align="left"><input type="hidden"
								name="MM_insert" value="form1"> <input class="boton"
								type="button" name="guardar" id="guardar" value="GUARDAR"
								onClick="if(valida(this.form)) submit();"></td>
							<td>&nbsp;</td>
						
						
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						<!-- Start block table -->
						<tr valign="baseline">
							<td colspan="2"><label class="label">Consulta por Plaza:</label>
								<input class="campo" type="text" name="consultap" id="consultap"
								value="" onKeyup="busca(this.value,this);"></td>
							<td><label class="label">Vacantes</label> <input type="radio"
								value="1" name="r1" id="r1" style="vertical-align: middle"
								onFocus="busca('VACANTE',document.getElementById('consultap'))" />
                    <? $fecha = date("Y") . "-" . date("m") . "-" . date('d'); ?>
                    <label class="label">Vencidas</label> <input
								type="radio" value="1" name="r1" id="r1"
								onFocus="busca2('<? echo $fecha?>',document.getElementById('consultap'))"
								style="vertical-align: middle" /> <label class="label">Ocupadas</label>
								<input type="radio" value="1" name="r1" id="r1"
								onFocus="busca('OCUPADO',document.getElementById('consultap'))"
								style="vertical-align: middle" /> <label class="label">Todas</label>
								<input type="radio" value="-1" name="r1"
								onFocus="busca('',this)" style="vertical-align: middle" /></td>
							<td align="right"><label class="label">Numero de entradas:</label>
								<input type="text" style="border: hidden;" id="numeroEntradas"
								size="2" /></td>
						</tr>
						<tr>
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