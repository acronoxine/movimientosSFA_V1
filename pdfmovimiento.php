<?
session_start ();
if ($_SESSION ["m_sesion"] != 1) {
	echo "<script>";
	echo "location.replace('index.php');";
	echo "</script>";
	exit ();
}
?>
<?

include ("Connections/conexion.php");
mysql_select_db ( $database_conexion, $conexion );
function campo($texto, $long, $align) {
	$texto = trim ( $texto );
	$n = strlen ( $texto );
	
	if ($align == 1) 	// alineacion izquierda
	{
		$espacios = $long - $n;
		for($i = 1; $i <= $espacios; $i ++) {
			$texto .= " ";
		}
	}
	
	if ($align == 2) 	// alineacion derecha
	{
		$espacios = $long - $n;
		for($i = 1; $i <= $espacios; $i ++) {
			$texto = " " . $texto;
		}
	}
	
	return $texto;
}
// $queryEmpresa="SELECT director, titular from empresa";
$queryEmpresa = "SELECT titular from empresa";
$resqe = mysql_query ( $queryEmpresa, $conexion );
$renqe = mysql_fetch_array ( $resqe );
// $director_qe=$renqe['director'];
$titularupp_qe = $renqe ['titular'];

$idmovimeinto = " SELECT MAX(idmovimiento) as idmh FROM movimiento_historial";
$resid = mysql_query ( $idmovimeinto );
$renid = mysql_fetch_array ( $resid );
$maxid = $renid ['idmh'] + 1;
$fecha_mov = date ( 'Y-m-d h:i:s' );
$query_empleado = "SELECT " . $maxid . " as idmovimiento, '" . $fecha_mov . "' as fecha_movimiento, n.contrato as contrato_emp,mov.clave as movimiento,epz.fecha_inicial,epz.fecha_final,pz.plaza_id,pz.plaza_clave,n.idnominaemp, case when n.activo = '1' then 'SI' else 'NO' end as activo, concat(ifnull(rfc_iniciales, ''), ifnull(rfc_fechanac, ''), ifnull(rfc_homoclave, '')) as rfc, curp, folio, paterno, materno, nombres, calle, numint, numext, colonia, cp, mun.municipio as ciudad, est.estado";
$query_empleado .= " , a.descripcion as area, a.clave as cvearea, c.descripcion as categoria, c.clave as cvecategoria, n.sueldobase, concat(lpad(day(fechanacimiento), 2, '0'), '/', lpad(month(fechanacimiento), 2, '0'), '/', year(fechanacimiento)) as fechanacimiento, case when sexo = 'M' then 'MASCULINO' when sexo = 'F' then 'FEMENINO' else '' end as sexo,";
$query_empleado .= " case when ecivil = '1' then 'SOLTERO' when ecivil = '2' then 'CASADO' when ecivil = '3' then 'DIVORCIADO' when ecivil = '4' then 'VIUDO' when ecivil = '5' then 'UNION LIBRE' else '' end as ecivil, p.descripcion as programa, p.clave as cveprograma, s.descripcion as subprograma, s.clave as cvesubprograma,";
$query_empleado .= " concat(lpad(day(fechaingr), 2, '0'), '/', lpad(month(fechaingr), 2, '0'), '/', year(fechaingr)) as fechaingr,";
$query_empleado .= "  case when escolaridad = 'P' then 'PRIMARIA' when escolaridad = 'S' then 'SECUNDARIA' when escolaridad = 'B' then 'BACHILLERATO' when escolaridad = 'L' then 'LICENCIATURA' when escolaridad = 'I' then 'INGENIER�A' when escolaridad = 'M' then 'MAESTR�A' when escolaridad = 'D' then 'DOCTORADO' else '' end as escolaridad";
$query_empleado .= " , case when nacionalidad = '1' then 'NACIONAL' when nacionalidad = '2' then 'EXTRANJERA' else '' end as nacionalidad, nafiliacionissste, oficinadepago, cartillaSMN";
$query_empleado .= " , nafiliacion, ";
$query_empleado .= " case when salariofv = 'F' then 'FIJO' else 'VARIABLE' end as salariofv, ";
$query_empleado .= " case when contrato = 'P' then 'PERMANENTE' when contrato = 'A' then 'ASIMILADO' when contrato = '11' then 'EVENTUAL' end as contrato";
$query_empleado .= " , case when nomina = 'Q' then 'QUINCENAL' else 'SEMANAL' end as nomina, case when jornada = '1' then 'DIURNA' when jornada = '2' then 'NOCTURNA' when jornada = '3' then 'MIXTA' when jornada = '4' then 'ESPECIAL' else '' end as jornada, ";
$query_empleado .= " case when formapago = 'DP' then 'DEPOSITO' else 'CHEQUE' end as formapago , ncuenta, ";
$query_empleado .= " mov.descripcion as estatus";
$query_empleado .= " , concat(lpad(day(fechainicio), 2, '0'), '/', lpad(month(fechainicio), 2, '0'), '/', year(fechainicio)) as fechainicio, fechabaja, clabe";

$query_empleado .= " , b.banco, a.titular as titular_ur, '" . $titularupp_qe . "' as titular_upp, '" . $director_qe . "' as titular_director  FROM nominaemp n";
$query_empleado .= " LEFT JOIN empleado_plaza epz ON n.idnominaemp = epz.idnominaemp ";
$query_empleado .= " LEFT JOIN cat_plazas pz ON epz.plaza_id=pz.plaza_id";
$query_empleado .= " LEFT JOIN cat_subprograma s ON pz.subprograma = s.idsubprograma ";
$query_empleado .= " left join cat_programa p ON s.idprograma = p.idprograma";
$query_empleado .= " left join cat_area a ON p.idarea = a.idarea ";
$query_empleado .= " LEFT JOIN cat_categoria c ON pz.categoria=c.clave";
$query_empleado .= " left join bancos b on n.idbancos = b.idbancos";
$query_empleado .= " left join cat_estados est on n.estado = est.idestados";
$query_empleado .= " left join cat_municipios mun on n.ciudad = mun.idmunicipios";
$query_empleado .= " left join cat_movimientos mov on n.estatus = mov.idmovimiento";

$query_empleado .= " where n.idnominaemp='$_GET[idnominaemp]'";
$res = mysql_query ( $query_empleado, $conexion );
$ren = mysql_fetch_array ( $res );

if ($_GET [ver] != 1) {
	$insertMH = "INSERT INTO movimiento_historial (" . $query_empleado . ")";
	// insercion en movimiento historial
	mysql_query ( $insertMH, $conexion );
}
/* define('FPDF_FONTPATH','./fpdf/font/'); */
include ("./fpdf/fpdf.php");

$pdf = new FPDF ( 'P', 'mm', 'A4' );
$pdf->SetMargins ( 1, 1, 1 );
$pdf->Open ();

$pdf->AddPage ();

$pdf->Image ( "imagenes/formato.jpg", 1, 1, 202 );

$pdf->SetFillColor ( 255, 255, 255 );
$pdf->SetTextColor ( 0 );
$pdf->SetDrawColor ( 0, 0, 0 );
$pdf->SetLineWidth ( .2 );
// $pdf->SetFont('Arial','B',9);

$pdf->Text ( 30, 43, $ren ["rfc"] );
$pdf->Text ( 88, 43, $ren ["curp"] );
$pdf->Text ( 174, 41, campo ( $ren ["folio"], 10, 2 ) );

// $pdf->SetFont('Arial','B',8);

$sql = "select upp as cveupp, razonsocial as upp, titular from empresa limit 1";
$res_emp = mysql_query ( $sql, $conexion );
$ren_emp = mysql_fetch_array ( $res_emp );

$titularupp = $ren_emp ["titular"];

$pdf->Text ( 17, 56, $ren_emp ["upp"] );
mysql_free_result ( $res_emp );

$pdf->Text ( 17, 63.5, $ren ["area"] );
$pdf->Text ( 17, 71, $ren ["programa"] );
$pdf->Text ( 17, 78.3, $ren ["subprograma"] );
$pdf->Text ( 17, 86, $ren ["proyecto"] );
$pdf->Text ( 17, 93.3, $ren ["categoria"] );
$pdf->Text ( 17, 100.3, $ren ["plaza_id"] );
$pdf->Ln ( 52 );

$pdf->Cell ( 182.2 );
$pdf->Cell ( 11, 4.5, $ren_emp ["cveupp"], 0, 0, 'C' );

$pdf->Ln ( 7.5 );
$pdf->Cell ( 182.2 );
$pdf->Cell ( 11, 4.5, $ren ["cvearea"], 0, 0, 'C' );

$pdf->Ln ( 7.3 );
$pdf->Cell ( 182.2 );
$pdf->Cell ( 11, 4.5, $ren ["cveprograma"], 0, 0, 'C' );

$pdf->Ln ( 7.5 );
$pdf->Cell ( 182.2 );
$pdf->Cell ( 11, 4.5, $ren ["cvesubprograma"], 0, 0, 'C' );

$pdf->Ln ( 7.5 );
$pdf->Cell ( 182.2 );
$pdf->Cell ( 11, 4.5, $ren ["cveproyecto"], 0, 0, 'C' );

$pdf->Ln ( 7.4 );
$pdf->Cell ( 182.2 );
$pdf->Cell ( 11, 4.5, $ren ["cvecategoria"], 0, 0, 'C' );

$pdf->Ln ( 7.4 );
$pdf->Cell ( 182.2 );
$pdf->Cell ( 11, 4.5, $ren ["plaza_clave"], 0, 0, 'C' );

$pdf->Text ( 107.7, 108.7, $ren ["movimiento"] );
// $pdf->SetFont('Arial','B',5);
$pdf->Text ( 184.0, 108.7, $ren ["contrato_emp"] );
// $pdf->SetFont('Arial','B',8);
$finicial = explode ( "-", $ren ["fecha_inicial"] );
$ffinal = explode ( "-", $ren ["fecha_final"] );
$pdf->Text ( 97.3, 120.5, $finicial [0] );
$pdf->Text ( 107.7, 120.5, $finicial [1] );
$pdf->Text ( 117.5, 120.5, $finicial [2] );

$pdf->Text ( 166.5, 120.5, $ffinal [0] );
$pdf->Text ( 177, 120.5, $ffinal [1] );
$pdf->Text ( 187.5, 120.5, $ffinal [2] );

$pdf->Ln ( 34.4 );

$pdf->Cell ( 13 );
$pdf->Cell ( 57.5, 5, $ren ["paterno"], 0, 0, 'C' );
$pdf->Cell ( 52, 5, $ren ["materno"], 0, 0, 'C' );
$pdf->Cell ( 71, 5, $ren ["nombres"], 0, 0, 'C' );

$pdf->Ln ( 9 );

$pdf->Cell ( 13 );
$pdf->Cell ( 109, 5, $ren ["calle"] . " " . $ren ["numext"] . " " . $ren ["numint"] . " " . $ren ["colonia"], 0, 0, 'C' );
$pdf->Cell ( 39, 5, $ren ["ciudad"], 0, 0, 'C' );
$pdf->Cell ( 32.5, 5, $ren ["ciudad"], 0, 0, 'C' );

$pdf->Ln ( 16 );

$pdf->Cell ( 13 );
$pdf->Cell ( 10, 5, $ren ["nacanio"], 0, 0, 'C' );
$pdf->Cell ( 6 );
$pdf->Cell ( 10, 5, $ren ["nacmes"], 0, 0, 'C' );
$pdf->Cell ( 4 );
$pdf->Cell ( 10, 5, $ren ["nacdia"], 0, 0, 'C' );

if ($ren ["sexo"] == "MASCULINO")
	$pdf->Text ( 76.5, 154.5, "X" );
else
	$pdf->Text ( 76.5, 157, "X" );

$pdf->Text ( 114, 153, $ren ["jornada"] );

$pdf->Text ( 113, 161.5, "$ren[de_hrs]:$ren[de_min] a $ren[a_hrs]:$ren[a_min] HRS." );

$pdf->Text ( 146.5, 160, $ren ["ecivil"] );

$pdf->Text ( 167, 154, $ren ["escolaridad"] );

$pdf->Ln ( 10.8 );

$pdf->Cell ( 13 );
$pdf->Cell ( 39.5, 5, $ren ["nafiliacion"], 0, 0, 'C' );
$pdf->Cell ( 40.5, 5, $ren ["nafiliacionissste"], 0, 0, 'C' );
$pdf->Cell ( 50, 5, $ren ["oficinadepago"], 0, 0, 'C' );
$pdf->Cell ( 18, 5, $ren ["cartillaSMN"], 0, 0, 'C' );
$pdf->Cell ( 32.5, 5, $ren ["nacionalidad"], 0, 0, 'C' );

$pdf->Ln ( 13 );

$sql = "select " . $maxid . " as idmovimiento, concepto, importe from nomina where idnominaemp = '$_GET[idnominaemp]' and tipo = 'P'";
$res_con = mysql_query ( $sql, $conexion );
if ($_GET [ver] != 1) {
	$insertConptos = "INSERT INTO movimiento_hconcepto (" . $sql . ")";
	mysql_query ( $insertConptos, $conexion );
}
$c = 1;
$r = 1;
while ( $ren_con = mysql_fetch_array ( $res_con ) ) {
	
	if ($c == 1) {
		$pdf->Cell ( 13 );
		$pdf->Cell ( 17.5, 4, $ren_con ["concepto"], 0, 0, 'C' );
		$pdf->Cell ( 22, 4, number_format ( $ren_con ["importe"], 2, ".", "," ), 0, 0, 'C' );
	}
	
	if ($c == 2) {
		$pdf->Cell ( 17.8, 4, $ren_con ["concepto"], 0, 0, 'C' );
		$pdf->Cell ( 22.5, 4, number_format ( $ren_con ["importe"], 2, ".", "," ), 0, 0, 'C' );
	}
	
	if ($c == 3) {
		$pdf->Cell ( 20.5, 4, $ren_con ["concepto"], 0, 0, 'C' );
		$pdf->Cell ( 30, 4, number_format ( $ren_con ["importe"], 2, ".", "," ), 0, 0, 'C' );
	}
	
	if ($c == 4) {
		$pdf->Cell ( 17.5, 4, $ren_con ["concepto"], 0, 0, 'C' );
		$pdf->Cell ( 32.5, 4, number_format ( $ren_con ["importe"], 2, ".", "," ), 0, 0, 'C' );
		$pdf->Ln ( 7 );
		$c = 0;
	}
	
	$c ++;
	$r ++;
}
if ($r >= 6)
	$pdf->Ln ( 28 );
else
	$pdf->Ln ( 39 );

$pdf->Cell ( 85, 4, $ren ["titular_ur"], 0, 0, 'C' );
$pdf->Cell ( 30, 4, "", 0, 0, 'C' );
$pdf->Cell ( 85, 4, $titularupp_qe, 0, 0, 'C' );

$pdf->Ln ( 13.5 );
$pdf->Cell ( 197, 4, $director_qe, 0, 0, 'C' );

$pdf->Output ( "pdfs/doc.pdf", "F" );

if (isset ( $_GET [qvacante] )) {
	mysql_query ( $_GET [qvacante], $conexion );
}

if (isset ( $_SESSION ['BAJA'] )) {
	mysql_query ( $_SESSION ['BAJA'], $conexion );
	unset ( $_SESSION ['BAJA'] );
}
echo "<script>";
echo "parent.formato.location.replace('pdfs/doc.pdf');";
echo "</script>";
?>
