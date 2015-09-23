<head>
<link rel="stylesheet" type="text/css" href="css/estilos.css">
<link rel="stylesheet" type="text/css" href="controles_jquery/css/overcast/jquery-ui-1.10.3.custom.css">
<link rel="stylesheet" type="text/css" href="controles_jquery/js/jquery.fancybox.css">
<script src="controles_jquery/js/jquery-1.9.1.js"></script>
<script src="controles_jquery/js/jquery-ui-1.10.3.custom.js"></script>
<script src="controles_jquery/js/jquery.fancybox.js"></script>
</head>

<script type="text/javascript">
		var z = jQuery.noConflict();
		z(document).ready(function(){ // Script del Navegador 
				z("ul.subnavegador").not('.selected').hide();
				z("a.desplegable").click(function(e){
				var desplegable = z(this).parent().find("ul.subnavegador");
				z('.desplegable').parent().find("ul.subnavegador").not(desplegable).slideUp('slow');
				desplegable.slideToggle('slow');
				e.preventDefault();
			});
		});
</script>

<div id="menu">
	<ul id="menuprin">
		<!--<li><a href="cat_isr.php">TABLA ISR MENSUAL</a></li>-->
		<li><a class="desplegable" href="#">+ CATALOGOS</a>
			<ul class="subnavegador" id="submenu">
				<li><a href="empresas.php">Dependencia</a></li>
				<li><a href="cat_areas.php">Unidades Responsables</a></li>
				<!--
                <li>
                    <a href="salariom.php">Salario m�nimo</a>
                </li>
                -->
				<li><a href="cat_categorias.php">Categor&iacuteas</a></li>
				<li><a href="cat_conceptos.php">Conceptos</a></li>
				<li><a href="cat_programas.php">Programas</a></li>
				<li><a href="cat_subprogramas.php">Subprogramas</a></li>
				<!--<li>
                    <a href="cat_proyectos.php">Proyectos</a>
                </li>-->
				<li><a href="cat_plazas.php">Plazas</a></li>

				<!--<li>
                    <a href="usuarios.php">Usuarios</a>
                </li>-->
			</ul></li>
		<li><a href="empleados.php">ALTA</a></li>
		<li><a class="desplegable" href="#">+ MOVIMIENTOS</a>
			<ul class="subnavegador" id="submenu">
				<li><a href="movimientos.php">Movimiento Trabajador</a></li>
				<li><a href="movimientos_historial.php">Historial</a></li>
				<li><a href="movimientos_titulares.php">Titulares</a></li>
			</ul></li>

		<!--<li><a href="emision.php">EMISION DE NOMINA</a></li>-->
		<!--<li><a href="cat_beneficiarios.php">PENSIONADOS</a></li>
        <ul id="submenu">
            <li>
                <a href="emisionpensiones.php">Emisi�n de pensiones</a>
            </li>
<!--            <li>
                <a href="emisioncheques_pen.php">Emisi�n de cheques</a>
            </li>
        </ul>-->
		<!-- <li><a class="desplegable" href="#">+ HERRAMIENTAS</a>
            <ul class = "subnavegador" id="submenu">
                <li>
                    <a href="prenomina.php">Prenomina</a>
                </li>
                <li>
                    <a href="estadistica.php">Estad�stica</a>
                </li>
                <li>
                    <a href="buscarecibo.php">Consulta de recibos</a>
                </li>
                <li>
                    <a href="cfdi.php">Generar de CFDI</a>
                </li>
                <li>
                    <a href="cfdi_ind.php">CFDI individual</a>
                </li>
               <!-- <li>
                    <a href="incidencias.php">Incidencias</a>
                </li>
            </ul>
        </li> 
        <li><a href="liquidaciones.php">LIQUIDACIONES</a>
-->
		<li><a href="salir.php">SALIR</a></li>
	</ul>
</div>
