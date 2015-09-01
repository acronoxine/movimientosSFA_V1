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
<?
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);


function campo($texto, $long, $align)
{
   $texto = trim($texto);
   $n = strlen($texto);

   if($align == 1) // alineaci�n izquierda
   {
        $espacios = $long - $n;
        for($i = 1; $i <= $espacios; $i++)
        {
           $texto .= " ";
        }
   }

   if($align == 2) // alineaci�n derecha
   {
        $espacios = $long - $n;
        for($i = 1; $i <= $espacios; $i++)
        {
           $texto = " " . $texto;
        }
   }

   return $texto;
}



$sql = "Select clave, descripcion from cat_area where idarea = '$_POST[ur]'";
$res = mysql_query($sql, $conexion);
$ren = mysql_fetch_array($res);
$claveur = $ren["clave"];
$descripcionur = $ren["descripcion"];
mysql_free_result($res);

$quin = explode("|", $_POST["quincena"]); 
$quincena = $quin[0];

if($quincena < 25)
{
	$sql = "Delete from nominapago where quincena = '$quincena' and anio = '" . date("Y") . "'";
	$res = mysql_query($sql, $conexion);
	
	$sql = "insert into nominapago(idnominaemp, cve_concepto, importe, tipo, quincena, anio, fechaemision, usuario, cve_sat, idarea)";
	$sql .= " select n.idnominaemp, n.concepto, n.importe, n.tipo, '$quincena', '" . date("Y") . "', curdate(), '$_SESSION[m_idregistro]', c.cve_sat, e.area";
	$sql .= " from nomina n"; 
	$sql .= " left join cat_conceptos c on n.concepto = c.clave";
	$sql .= " left join nominaemp e on n.idnominaemp = e.idnominaemp ";
	$sql .= " left join nominapago p on p.quincena = '$quincena' and p.anio = '" . date("Y") . "'";
	$sql .= " where p.quincena is null and p.anio is null and e.activo = '1'";
	$res = mysql_query($sql, $conexion);
}


$sql = "Select importe from salmin";
$res_sm = mysql_query($sql, $conexion);
$ren_sm = mysql_fetch_array($res_sm);
$sm = $ren_sm["importe"];
mysql_free_result($res_sm);



if(isset($_POST["aguinaldo"]) && $_POST["aguinaldo"] == "C")
{
	$sql = "insert into nominapago(idnominaemp, cve_concepto, importe, tipo, quincena, anio, fechaemision, fechasistema, usuario, cve_sat, idarea)";
	$sql .= " select n.idnominaemp, '115' as cve_concepto,";
	$sql .= " case";
	$sql .= " when c.uso = 'IMP' then";
	$sql .= "     c.importe";
	$sql .= " when c.uso = 'DIA' then";
	$sql .= "     cast(((n.sueldobase * 2) / 30) * c.dias as decimal(10,2))";
	$sql .= " when c.uso = 'POR' then";
	$sql .= "     cast(((n.sueldobase * 2) * (c.porcentaje/100)) as decimal(10,2))";
	$sql .= " else";
	$sql .= "     0";
	$sql .= " end as importe,";
	$sql .= " 'P' as tipo,";
	$sql .= " '$quincena' as quincena, '" . date("Y") . "' as anio, curdate() as fechaemision, curdate() as fechasistema, '$_SESSION[m_idregistro]' as usuario,";
	$sql .= " c.cve_sat, n.area";
	$sql .= " from nominaemp n";
	$sql .= " left join cat_conceptos c on c.clave = '115'";
	$sql .= " where n.activo = '1'";
	$res = mysql_query($sql, $conexion);
	
	
	$sql = " insert into nominapago(idnominaemp, cve_concepto, importe, tipo, quincena, anio, fechaemision, fechasistema, usuario, cve_sat, idarea)";
	$sql .= " select z.idnominaemp, z.cve_concepto, z.isr, z.tipo, z.quincena, z.anio, z.fechaemision, z.fechasistema, z.usuario, z.cve_sat, z.area from";
	$sql .= " (select y.idnominaemp, y.cve_concepto,"; 
	$sql .= " case when y.salmin < y.importe then"; 
	$sql .= "     cast(((((y.importe - y.salmin) - i.limiteinferior) * (i.porciento/100)) + i.cuotafija) as decimal(10,2))";
	$sql .= " else ";
	$sql .= "     0";
	$sql .= " end as isr, ";
	$sql .= " y.tipo, y.quincena, y.anio, y.fechaemision, y.fechasistema, y.usuario, y.cve_sat, y.area from";
	$sql .= " (select idnominaemp, cve_concepto, $sm * 30 as salmin, importe, tipo, quincena, anio, fechaemision, fechasistema, usuario, cve_sat, area from";
	$sql .= " (select n.idnominaemp, '251' as cve_concepto, ";
	$sql .= " case ";
	$sql .= " when c.uso = 'IMP' then";
	$sql .= "     c.importe";
	$sql .= " when c.uso = 'DIA' then";
	$sql .= "     cast(((n.sueldobase * 2) / 30) * c.dias as decimal(10,2))";
	$sql .= " when c.uso = 'POR' then";
	$sql .= "     cast(((n.sueldobase * 2) * (c.porcentaje/100)) as decimal(10,2))";
	$sql .= " else";
	$sql .= "     0";
	$sql .= " end as importe, ";
	$sql .= " 'D' as tipo,";
	$sql .= " '$quincena' as quincena, '" . date("Y") . "' as anio, curdate() as fechaemision, curdate() as fechasistema, '$_SESSION[m_idregistro]' as usuario,";
	$sql .= " c.cve_sat, n.area";
	$sql .= " from nominaemp n";
	$sql .= " left join cat_conceptos c on c.clave = '115' where n.activo = '1') x) y";
	$sql .= " left join isr i on ((y.importe - y.salmin) between i.limiteinferior and i.limitesuperior) or ((y.importe - y.salmin) >= i.limiteinferior and i.limitesuperior = 0)) z";
	$res = mysql_query($sql, $conexion);
}


if((isset($_POST["aguinaldo"]) && $_POST["aguinaldo"] == "1P") || $quincena == 25)
{
	if($quincena == 25)
	{
		$sql = "Delete from nominapago where quincena = '$quincena' and anio = '" . date("Y") . "'";
		$res = mysql_query($sql, $conexion);
	}
	
	$sql = "insert into nominapago(idnominaemp, cve_concepto, importe, tipo, quincena, anio, fechaemision, fechasistema, usuario, cve_sat, idarea)";
	$sql .= " select n.idnominaemp, '115' as cve_concepto,";
	$sql .= " (case";
	$sql .= " when c.uso = 'IMP' then";
	$sql .= "     c.importe";
	$sql .= " when c.uso = 'DIA' then";
	$sql .= "     cast(((n.sueldobase * 2) / 30) * c.dias as decimal(10,2))";
	$sql .= " when c.uso = 'POR' then";
	$sql .= "     cast(((n.sueldobase * 2) * (c.porcentaje/100)) as decimal(10,2))";
	$sql .= " else";
	$sql .= "     0";
	$sql .= " end)/2 as importe,";
	$sql .= " 'P' as tipo,";
	$sql .= " '$quincena' as quincena, '" . date("Y") . "' as anio, curdate() as fechaemision, curdate() as fechasistema, '$_SESSION[m_idregistro]' as usuario,";
	$sql .= " c.cve_sat, n.area";
	$sql .= " from nominaemp n";
	$sql .= " left join cat_conceptos c on c.clave = '115'";
	$sql .= " where n.activo = '1'";
	$res = mysql_query($sql, $conexion);
	
	
	$sql = " insert into nominapago(idnominaemp, cve_concepto, importe, tipo, quincena, anio, fechaemision, fechasistema, usuario, cve_sat, idarea)";
	$sql .= " select z.idnominaemp, z.cve_concepto, (z.isr)/2, z.tipo, z.quincena, z.anio, z.fechaemision, z.fechasistema, z.usuario, z.cve_sat, z.area from";
	$sql .= " (select y.idnominaemp, y.cve_concepto,"; 
	$sql .= " case when y.salmin < y.importe then"; 
	$sql .= "     cast(((((y.importe - y.salmin) - i.limiteinferior) * (i.porciento/100)) + i.cuotafija) as decimal(10,2))";
	$sql .= " else ";
	$sql .= "     0";
	$sql .= " end as isr, ";
	$sql .= " y.tipo, y.quincena, y.anio, y.fechaemision, y.fechasistema, y.usuario, y.cve_sat, y.area from";
	$sql .= " (select idnominaemp, cve_concepto, $sm * 30 as salmin, importe, tipo, quincena, anio, fechaemision, fechasistema, usuario, cve_sat, area from";
	$sql .= " (select n.idnominaemp, '251' as cve_concepto, ";
	$sql .= " case ";
	$sql .= " when c.uso = 'IMP' then";
	$sql .= "     c.importe";
	$sql .= " when c.uso = 'DIA' then";
	$sql .= "     cast(((n.sueldobase * 2) / 30) * c.dias as decimal(10,2))";
	$sql .= " when c.uso = 'POR' then";
	$sql .= "     cast(((n.sueldobase * 2) * (c.porcentaje/100)) as decimal(10,2))";
	$sql .= " else";
	$sql .= "     0";
	$sql .= " end as importe, ";
	$sql .= " 'D' as tipo,";
	$sql .= " '$quincena' as quincena, '" . date("Y") . "' as anio, curdate() as fechaemision, curdate() as fechasistema, '$_SESSION[m_idregistro]' as usuario,";
	$sql .= " c.cve_sat, n.area";
	$sql .= " from nominaemp n";
	$sql .= " left join cat_conceptos c on c.clave = '115' where n.activo = '1') x) y";
	$sql .= " left join isr i on ((y.importe - y.salmin) between i.limiteinferior and i.limitesuperior) or ((y.importe - y.salmin) >= i.limiteinferior and i.limitesuperior = 0)) z";
	$res = mysql_query($sql, $conexion);
	
}


if(isset($_POST["prima"]))
{
	$sql = "insert into nominapago(idnominaemp, cve_concepto, importe, tipo, quincena, anio, fechaemision, fechasistema, usuario, cve_sat, idarea)";
	$sql .= " select n.idnominaemp, '119' as cve_concepto,";
	$sql .= " case";
	$sql .= " when c.uso = 'IMP' then";
	$sql .= "     c.importe";
	$sql .= " when c.uso = 'DIA' then";
	$sql .= "     cast((((n.sueldobase * 2) / 30) * 0.25) * c.dias as decimal(10,2))";
	$sql .= " when c.uso = 'POR' then";
	$sql .= "     cast(((n.sueldobase * 2) * (c.porcentaje/100)) as decimal(10,2))";
	$sql .= " else";
	$sql .= "     0";
	$sql .= " end as importe,";
	$sql .= " 'P' as tipo,";
	$sql .= " '$quincena' as quincena, '" . date("Y") . "' as anio, curdate() as fechaemision, curdate() as fechasistema, '$_SESSION[m_idregistro]' as usuario,";
	$sql .= " c.cve_sat, n.area";
	$sql .= " from nominaemp n";
	$sql .= " left join cat_conceptos c on c.clave = '119'";
	$sql .= " where n.activo = '1' and datediff(curdate(),n.fechainicio) > 181";
	$res = mysql_query($sql, $conexion);
	
	
	$sql = " insert into nominapago(idnominaemp, cve_concepto, importe, tipo, quincena, anio, fechaemision, fechasistema, usuario, cve_sat, idarea)";
	$sql .= " select z.idnominaemp, z.cve_concepto, z.isr, z.tipo, z.quincena, z.anio, z.fechaemision, z.fechasistema, z.usuario, z.cve_sat, z.area from";
	$sql .= " (select y.idnominaemp, y.cve_concepto,"; 
	$sql .= " case when y.salmin < y.importe then"; 
	$sql .= "     cast(((((y.importe - y.salmin) - i.limiteinferior) * (i.porciento/100)) + i.cuotafija) as decimal(10,2))";
	$sql .= " else ";
	$sql .= "     0";
	$sql .= " end as isr, ";
	$sql .= " y.tipo, y.quincena, y.anio, y.fechaemision, y.fechasistema, y.usuario, y.cve_sat, y.area from";
	$sql .= " (select idnominaemp, cve_concepto, $sm * 15 as salmin, importe, tipo, quincena, anio, fechaemision, fechasistema, usuario, cve_sat, area from";
	$sql .= " (select n.idnominaemp, '251' as cve_concepto, ";
	$sql .= " case";
	$sql .= " when c.uso = 'IMP' then";
	$sql .= "     c.importe";
	$sql .= " when c.uso = 'DIA' then";
	$sql .= "     cast((((n.sueldobase * 2) / 30) * 0.25) * c.dias as decimal(10,2))";
	$sql .= " when c.uso = 'POR' then";
	$sql .= "     cast(((n.sueldobase * 2) * (c.porcentaje/100)) as decimal(10,2))";
	$sql .= " else";
	$sql .= "     0";
	$sql .= " end as importe,";
	$sql .= " 'D' as tipo,";
	$sql .= " '$quincena' as quincena, '" . date("Y") . "' as anio, curdate() as fechaemision, curdate() as fechasistema, '$_SESSION[m_idregistro]' as usuario,";
	$sql .= " c.cve_sat, n.area";
	$sql .= " from nominaemp n";
	$sql .= " left join cat_conceptos c on c.clave = '119' where n.activo = '1' and datediff(curdate(),n.fechainicio) > 181) x) y";
	$sql .= " left join isr i on ((y.importe - y.salmin) between i.limiteinferior and i.limitesuperior) or ((y.importe - y.salmin) >= i.limiteinferior and i.limitesuperior = 0)) z";
	$res = mysql_query($sql, $conexion);
}

//----- PERSONAL EVENTUAL
$sql = "select a.idarea, p.idnominaemp, e.curp, concat(ifnull(e.rfc_iniciales, ''), ifnull(e.rfc_fechanac, ''), ifnull(e.rfc_homoclave, '')) as rfc, concat(e.paterno, ' ', e.materno, ' ', e.nombres) as nombre, concat(a.clave, ' ', a.descripcion) as ur, c.descripcion as categoria,";
$sql .= " concat(lpad(day(e.fechaingr), 2, '0'), '/', lpad(month(e.fechaingr), 2, '0'), '/', year(e.fechaingr)) as fechaingr, case when e.salariofv = 'F' then 'FIJO' else 'VARIABLE' end as salariofv, day(curdate()) as dia, e.nafiliacion, case when e.jornada = '1' then 'DIURNA' when e.jornada = '2' then 'NOCTURNA' when e.jornada = '3' then 'MIXTA' when e.jornada = '4' then 'ESPECIAL' else '' end as jornada";
$sql .= " from nominapago p";
$sql .= " left join nominaemp e on p.idnominaemp = e.idnominaemp";
$sql .= " left join cat_area a on e.area = a.idarea";
$sql .= " left join cat_categoria c on e.categoria = c.idcategoria";

if($_POST["ur"] == -1)
	$sql .= " where p.quincena = '$quincena' and p.anio = '" . date("Y") . "' and e.activo = '1' AND e.area!=8 ";
else
	$sql .= " where p.quincena = '$quincena' and p.anio = '" . date("Y") . "' and p.idarea = '$_POST[ur]' and e.activo = '1' AND e.area!=8 ";

$sql .= " group by a.idarea, p.idnominaemp, e.curp, concat(ifnull(e.rfc_iniciales, ''), ifnull(e.rfc_fechanac, ''), ifnull(e.rfc_homoclave, '')), e.paterno, e.materno, e.nombres, a.clave, a.descripcion, c.descripcion, e.fechaingr, e.salariofv, e.nafiliacion, e.jornada";
$res_emp = mysql_query($sql, $conexion);

//-------------- ASIMILADOS A SUELDOS Y SALARIOS  ---------------------

$sql = "select a.idarea, p.idnominaemp,e.fechaingr, e.curp, CONCAT(e.calle,' #',e.numext,', ',e.colonia,', ',mn.municipio,' ',st.estado,', CP:',e.cp) AS domicilio, concat(ifnull(e.rfc_iniciales, ''), ifnull(e.rfc_fechanac, ''), ifnull(e.rfc_homoclave, '')) as rfc, concat(e.paterno, ' ', e.materno, ' ', e.nombres) as nombre, concat(a.clave, ' ', a.descripcion) as ur, c.descripcion as categoria,";
$sql .= " concat(lpad(day(e.fechaingr), 2, '0'), '/', lpad(month(e.fechaingr), 2, '0'), '/', year(e.fechaingr)) as fechaingr, case when e.salariofv = 'F' then 'FIJO' else 'VARIABLE' end as salariofv, day(curdate()) as dia, e.nafiliacion, case when e.jornada = '1' then 'DIURNA' when e.jornada = '2' then 'NOCTURNA' when e.jornada = '3' then 'MIXTA' when e.jornada = '4' then 'ESPECIAL' else '' end as jornada";
$sql .= " from nominapago p";
$sql .= " left join nominaemp e on p.idnominaemp = e.idnominaemp";
$sql .= " left join cat_area a on e.area = a.idarea";
$sql .= " LEFT JOIN cat_municipios mn ON e.ciudad=mn.idmunicipios";
$sql .= " LEFT JOIN cat_estados st ON e.estado=st.idestados";
$sql .= " left join cat_categoria c on e.categoria = c.idcategoria";

if($_POST["ur"] == -1)
	$sql .= " where p.quincena = '$quincena' and p.anio = '" . date("Y") . "' and e.activo = '1' AND e.area=8 ";
else
	$sql .= " where p.quincena = '$quincena' and p.anio = '" . date("Y") . "' and p.idarea = '$_POST[ur]' and e.activo = '1' AND e.area=8 ";

$sql .= " group by a.idarea, p.idnominaemp, e.curp, concat(ifnull(e.rfc_iniciales, ''), ifnull(e.rfc_fechanac, ''), ifnull(e.rfc_homoclave, '')), e.paterno, e.materno, e.nombres, a.clave, a.descripcion, c.descripcion, e.fechaingr, e.salariofv, e.nafiliacion, e.jornada";
$res_asimilados = mysql_query($sql, $conexion);

$fecha1 =explode("/",$_POST["fdesde"]);
$fecha_desde = $fecha1[2]."-".$fecha1[1]."-".$fecha1[0];

$fecha2=explode("/",$_POST["fhasta"]);
$fecha_hasta = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0];
$query_dia="SELECT DATEDIFF(fhasta, fdesde) + 1 as dia FROM cat_quincenas WHERE '$fecha_desde' >= fdesde AND '$fecha_hasta' <= fhasta";
$res_dia = mysql_query($query_dia, $conexion);
$row_dia=mysql_fetch_array($res_dia);
include("./fpdf/fpdf.php");

    $pdf=new FPDF('P', 'mm', 'A4');
	$pdf->SetMargins(4, 4, 4);
    $pdf->Open();

	while($ren_emp = mysql_fetch_array($res_emp))
	{
		$pdf->AddPage();
	
		include("membrete_recibos.php");
		
		$nombre = $ren_emp["nombre"];
		$dia = $row_dia['dia'];
		$ur = $ren_emp["ur"];
		$idarea = $ren_emp["idarea"];
		$rfc = $ren_emp["rfc"];
		$fechaingr = $ren_emp["fechaingr"];
		$nafiliacion = $ren_emp["nafiliacion"];
		$curp = $ren_emp["curp"];
		$jornada = $ren_emp["jornada"];
		$salariofv = $ren_emp["salariofv"];
	
	
		$pdf->cell(15,5,"Nombre: ", 0, 0, 'L');
		$pdf->cell(85,5,$nombre, 0, 0, 'L');
		$pdf->cell(25,5,"D�as del per�odo: ", 0, 0, 'L');
		$pdf->cell(75,5,$dia, 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"UR: ", 0, 0, 'L');
		$pdf->cell(85,5,$ur, 0, 0, 'L');
		$pdf->cell(25,5,"Per�odo del: ", 0, 0, 'L');
		$pdf->cell(75,5,$_POST["fdesde"], 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"R.F.C.:", 0, 0, 'L');
		$pdf->cell(85,5,$rfc, 0, 0, 'L');
		$pdf->cell(25,5,"Al: ", 0, 0, 'L');
		$pdf->cell(75,5,$_POST["fhasta"], 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"Ingreso: ", 0, 0, 'L');
		$pdf->cell(85,5,$fechaingr, 0, 0, 'L');
		$pdf->cell(25,5,"Afiliaci�n: ", 0, 0, 'L');
		$pdf->cell(75,5,$nafiliacion, 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"C.U.R.P.: ", 0, 0, 'L');
		$pdf->cell(85,5,$curp, 0, 0, 'L');
		$pdf->cell(25,5,"Jornada: ", 0, 0, 'L');
		$pdf->cell(75,5,$jornada, 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"Salario: ", 0, 0, 'L');
		$pdf->cell(85,5,$salariofv, 0, 0, 'L');
		$pdf->Ln(10);	
		
		$sql = "select n.tipo, n.cve_concepto, c.descripcion as concepto, sum(n.importe) as importe  from nominapago n";
		$sql .= " left join cat_conceptos c on n.cve_concepto = c.clave";
		$sql .= " where n.quincena = '$quincena' and n.anio = '" . date("Y") . "' and n.idarea = '$idarea' and n.idnominaemp = '$ren_emp[idnominaemp]'";
		$sql .= " group by n.tipo, n.cve_concepto, c.descripcion";
		$res = mysql_query($sql, $conexion);
		
		$pdf->SetFont('courier','B',7);
		
		$totalper = 0;
		$linea = 65;
		while($ren = mysql_fetch_array($res))
		{
			if($ren["tipo"] == "P")
			{
				$pdf->Text(5, $linea, $ren["cve_concepto"] . " " . $ren["concepto"]);
				$pdf->Text(72, $linea, campo(number_format($ren["importe"], 2, ".", ","), 20, 2));
				$totalper += $ren["importe"];
				$linea += 3;
			}
		}
		
		mysql_data_seek($res, 0);
		
		$totalded = 0;
		$linea = 65;
		while($ren = mysql_fetch_array($res))
		{
			if($ren["tipo"] == "D")
			{
				$pdf->Text(107, $linea, $ren["cve_concepto"] . " " . $ren["concepto"]);
				$pdf->Text(173, $linea, campo(number_format($ren["importe"], 2, ".", ","), 20, 2));
				$totalded += $ren["importe"];
				$linea += 3;
			}
		}
		
		$pdf->SetDrawColor(0,0,0);
		$pdf->Rect(4, 60, 100, 30);
		$pdf->Rect(104, 60, 100, 30);
		
		$pdf->Text(5, 95, "TOTAL DE PERCEPCIONES:");
		$pdf->Text(72, 95, campo(number_format($totalper, 2, ".", ","), 20, 2));
		$pdf->Text(107, 95, "TOTAL DE DEDUCCIONES:");
		$pdf->Text(173, 95, campo(number_format($totalded, 2, ".", ","), 20, 2));
		
		$pdf->Text(107, 100, "NETO A PAGAR:");
		$pdf->Text(173, 100, campo(number_format($totalper - $totalded, 2, ".", ","), 20, 2));
		
		$pdf->SetFont('Arial','',7);
		
		$pdf->Text(107, 108, "Recib� la cantidad indicada que cubre a la fecha todas las percepciones que tengo");
		$pdf->Text(107, 111, "derecho sin que se me adeude cantidad alguna.");
		$pdf->Rect(104, 104, 100, 9);
		
		$pdf->Line(65, 125, 145, 125);
		
		$pdf->Text(99, 130, "R E C I B �");
		
		$pdf->Ln(69);
		$pdf->cell(202,5,$nombre, 0, 0, 'C');
		
	/********************************** copia ***********************************************************************/	
		$pdf->Line(5, 139, 202, 139);
		
		$pdf->Ln(35);
		$pdf->cell(15,5,"Nombre: ", 0, 0, 'L');
		$pdf->cell(85,5,$nombre, 0, 0, 'L');
		$pdf->cell(25,5,"D�as del per�odo: ", 0, 0, 'L');
		$pdf->cell(75,5,$dia, 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"UR: ", 0, 0, 'L');
		$pdf->cell(85,5,$ur, 0, 0, 'L');
		$pdf->cell(25,5,"Per�odo del: ", 0, 0, 'L');
		$pdf->cell(75,5,$_POST["fdesde"], 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"R.F.C.:", 0, 0, 'L');
		$pdf->cell(85,5,$rfc, 0, 0, 'L');
		$pdf->cell(25,5,"Al: ", 0, 0, 'L');
		$pdf->cell(75,5,$_POST["fhasta"], 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"Ingreso: ", 0, 0, 'L');
		$pdf->cell(85,5,$fechaingr, 0, 0, 'L');
		$pdf->cell(25,5,"Afiliaci�n: ", 0, 0, 'L');
		$pdf->cell(75,5,$nafiliacion, 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"C.U.R.P.: ", 0, 0, 'L');
		$pdf->cell(85,5,$curp, 0, 0, 'L');
		$pdf->cell(25,5,"Jornada: ", 0, 0, 'L');
		$pdf->cell(75,5,$jornada, 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"Salario: ", 0, 0, 'L');
		$pdf->cell(85,5,$salariofv, 0, 0, 'L');
		$pdf->Ln(10);	
		
		$sql = "select n.tipo, n.cve_concepto, c.descripcion as concepto, sum(n.importe) as importe  from nominapago n";
		$sql .= " left join cat_conceptos c on n.cve_concepto = c.clave";
		$sql .= " where n.quincena = '$quincena' and n.anio = '" . date("Y") . "' and n.idarea = '$idarea' and n.idnominaemp = '$ren_emp[idnominaemp]'";
		$sql .= " group by n.tipo, n.cve_concepto, c.descripcion";
		$res = mysql_query($sql, $conexion);
		
		$pdf->SetFont('courier','B',7);
		
		$totalper = 0;
		$linea = 205;
		while($ren = mysql_fetch_array($res))
		{
			if($ren["tipo"] == "P")
			{
				$pdf->Text(5, $linea, $ren["cve_concepto"] . " " . $ren["concepto"]);
				$pdf->Text(72, $linea, campo(number_format($ren["importe"], 2, ".", ","), 20, 2));
				$totalper += $ren["importe"];
				$linea += 3;
			}
		}
		
		mysql_data_seek($res, 0);
		
		$totalded = 0;
		$linea = 205;
		while($ren = mysql_fetch_array($res))
		{
			if($ren["tipo"] == "D")
			{
				$pdf->Text(107, $linea, $ren["cve_concepto"] . " " . $ren["concepto"]);
				$pdf->Text(173, $linea, campo(number_format($ren["importe"], 2, ".", ","), 20, 2));
				$totalded += $ren["importe"];
				$linea += 3;
			}
		}
		
		$pdf->SetDrawColor(0,0,0);
		$pdf->Rect(4, 200, 100, 30);
		$pdf->Rect(104, 200, 100, 30);
		
		$pdf->Text(5, 235, "TOTAL DE PERCEPCIONES:");
		$pdf->Text(72, 235, campo(number_format($totalper, 2, ".", ","), 20, 2));
		$pdf->Text(107, 235, "TOTAL DE DEDUCCIONES:");
		$pdf->Text(173, 235, campo(number_format($totalded, 2, ".", ","), 20, 2));
		
		$pdf->Text(107, 240, "NETO A PAGAR:");
		$pdf->Text(173, 240, campo(number_format($totalper - $totalded, 2, ".", ","), 20, 2));
		
		$pdf->SetFont('Arial','',7);
		
		$pdf->Text(107, 248, "Recib� la cantidad indicada que cubre a la fecha todas las percepciones que tengo");
		$pdf->Text(107, 251, "derecho sin que se me adeude cantidad alguna.");
		$pdf->Rect(104, 244, 100, 9);
		
		$pdf->Line(65, 265, 145, 265);
		
		$pdf->Text(99, 270, "R E C I B �");
		
		$pdf->Ln(70);	
		$pdf->cell(202,5,$nombre, 0, 0, 'C');
	}
	
	//-------- ASIMILADOS A SUELDOS Y SALARIOS modificacion del 26 de febrero --------
	$CONTADOR=0;
	while($ren_asimilados = mysql_fetch_array($res_asimilados)){
		$CONTADOR++;
		$fecha_ingreso=explode("/",$ren_asimilados['fechaingr']);
		$fechaIngr = $fecha_ingreso[2]."-".$fecha_ingreso[1]."-".$fecha_ingreso[0];
		$fechaInicio=formato_fecha($fechaIngr);
		$fechaFinal=formato_fecha($fecha_hasta);
		$sql = "select n.tipo, n.cve_concepto, c.descripcion as concepto, sum(n.importe) as importe  from nominapago n";
		$sql .= " left join cat_conceptos c on n.cve_concepto = c.clave";
		$sql .= " where n.quincena = '$quincena' and n.anio = '" . date("Y") . "' and n.idarea = '$ren_asimilados[idarea]' and n.idnominaemp = '$ren_asimilados[idnominaemp]'";
		$sql .= " group by n.tipo, n.cve_concepto, c.descripcion";
		$res = mysql_query($sql, $conexion);
		//echo $sql;
		$totalper = 0;
		$linea = 205;
		while($ren = mysql_fetch_array($res))
		{
			if($ren["tipo"] == "P")
			{
				$totalper += $ren["importe"];
			}
		}
		
		mysql_data_seek($res, 0);
		
		$totalded = 0;
		$linea = 205;
		while($ren = mysql_fetch_array($res))
		{
			if($ren["tipo"] == "D")
			{
				$totalded += $ren["importe"];

			}
		}
		
		
			$pdf->AddPage();
			$pdf->SetFillColor(255,255,255);
    		$pdf->SetTextColor(0);
   			$pdf->SetDrawColor(0,0,0);
    		$pdf->SetLineWidth(.2);
    		$pdf->SetFont('Arial','B',12);
			$pdf->SetXY(10,10);
			$pdf->SetFillColor(153,153,153);
			$pdf->cell(190,10,"RECIBO DE PAGO DE HONORARIOS ASIMILABLES A SALARIOS", 1, 1, 'C',true);
			$pdf->SetXY(10,22);
			$pdf->MultiCell(190,50,"",1,'C',false);
			$pdf->SetFont('Arial','',10);
			$posx=12;
			$posy=26;
			$pdf->SetXY($posx+150,$posy-1);
			$pdf->Cell(10,4,"Recibo No. RC00".$CONTADOR."-2015",0,0,'C');
			
			$pdf->SetXY($posx,$posy+2);
			$pdf->MultiCell(40,5,"Lugar y fecha de expedici�n:",0,'L',false);
			$pdf->SetXY($posx+40,$posy+3);
			$pdf->MultiCell(100,5,"MORELIA, MICHOAC�N A ".$fechaFinal,0,'L',false);
			$pdf->SetXY($posx,$posy+13);
			$pdf->MultiCell(40,5,"Recib� de:",0,'L',false);
			$pdf->SetXY($posx+40,$posy+13);
			$pdf->MultiCell(100,5,"CENTRO ESTATAL DE TECNOLOG�AS DE INFORMACI�N Y COMUNICACIONES",0,'L',false);
			$pdf->SetXY($posx,$posy+25);
			$pdf->MultiCell(40,5,"R.F.C.",0,'L',false);
			$pdf->SetXY($posx+40,$posy+25);
			$pdf->MultiCell(100,5,"CET021108GS8",0,'L',false);
			$pdf->SetXY($posx,$posy+34);
			$pdf->MultiCell(40,5,"Domicilio",0,'L',false);
			$pdf->SetXY($posx+40,$posy+34);
			$pdf->MultiCell(100,5,"AV. LAS CA�ADAS NO. 501 INT 400, COL. CD TRES MAR�AS, CP 58254, MORELIA MICHOAC�N",0,'L',false);
			$posx=10;
			$posy+=50;
			$pdf->SetXY($posx,$posy);
			$pdf->MultiCell(190,195,"",1,'C',false);
			$pdf->SetFont('Arial','',11);
			$pdf->SetXY($posx,$posy+4);
			$pdf->Cell(190,4,"EL PRESTADOR DE SERVICIOS",0,0,'C');
			$pdf->SetXY($posx,$posy+11);
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(190,4,$ren_asimilados[nombre],0,0,'C');
			$pdf->SetFont('Arial','',11);
			$pdf->SetXY($posx,$posy+15);
			$pdf->Cell(190,4,"RFC:".$ren_asimilados[rfc],0,0,'C');
			$pdf->SetXY($posx,$posy+19);
			$pdf->Cell(190,4,"CURP:".$ren_asimilados[curp],0,0,'C');
			$pdf->SetXY($posx+2,$posy+33);
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(30,4,"Domicilio:",0,0,'L');
			$pdf->SetXY($posx+40,$posy+33);
			$pdf->MultiCell(140,5,$ren_asimilados[domicilio],0,'L',false);
			$pdf->SetXY($posx+2,$posy+50);
			$pdf->MultiCell(100,52,"",1,'L',false);
			$pdf->SetXY($posx+2,$posy+50);
			$pdf->SetFont('Arial','B',10);
			$pdf->MultiCell(30,5,"CONCEPTO Y PERIODO DE PAGO:",0,'C',false);
			$pdf->SetXY($posx+2+30,$posy+51);
			$pdf->SetFillColor(200,200,200);
			$pdf->MultiCell(70,5,"Desarrollo de actividades inherentes a la digitalizaci�n, indexaci�n y captura de bases de datos para actas de estado civil de las personas, las cuales obran en los archivos de la Direcci�n de Registro Civil.\nServicio Prestado:\n".$ren_asimilados['categoria']."\n".$fechaInicio." al ".$fechaFinal,0,'L',true);

			$pdf->SetXY($posx+2+120,$posy+50);
			$pdf->MultiCell(60,52,"",1,'L',false);
			$pdf->SetXY($posx+2+120,$posy+52);
			$pdf->MultiCell(30,5,"IMPORTE DE HONORARIOS:",0,'L',false);
			$pdf->SetXY($posx+2+150,$posy+53);
			$pdf->MultiCell(30,5,"$".campo(number_format($totalper, 2, ".", ",")),0,'R',false);
			$posy+=5;
			$pdf->SetXY($posx+2+120,$posy+66);
			$pdf->MultiCell(30,5,"ISR RETENIDO:",0,'L',false);
			$pdf->SetXY($posx+2+150,$posy+66);
			$pdf->MultiCell(30,5,"$".campo(number_format($totalded, 2, ".", ",")),0,'R',false);
			$neto=$totalper-$totalded;
			$pdf->SetXY($posx+2+150,$posy+72);
			$pdf->MultiCell(30,5,"________",0,'R',false);
			$pdf->SetXY($posx+2+120,$posy+76);
			$pdf->MultiCell(30,5,"IMPORTE NETO",0,'L',false);
			$pdf->SetXY($posx+2+150,$posy+76);
			$pdf->MultiCell(30,5,"$".campo(number_format($neto, 2, ".", ",")),0,'R',false);
			$nletra=texto_numero($neto);
			$tdecimales=explode(".",$neto);
			if($tdecimales[1]!=""){
				if($tdecimales[1]<9)
					$tdecimales[1].="0";
			}
			else
				$tdecimales[1].="00";
			$pdf->SetXY($posx+2,$posy+100);
			$pdf->MultiCell(180,10,"",1,'L',false);
			$pdf->SetXY($posx+2,$posy+100);
			$pdf->SetFont('Arial','B',9);
			$pdf->MultiCell(30,5,"Importe Neto con letra",0,'L',false);
			$pdf->SetXY($posx+2+25,$posy+101);
			$pdf->MultiCell(150,5,$nletra." PESOS ".$tdecimales[1]."/100 M.N.",0,'C',false);
			$pdf->SetXY($posx+2,$posy+130);
			$pdf->Cell(180,4,"RECIB�",0,0,'C');
			$pdf->SetXY($posx+2,$posy+160);
			$pdf->Cell(180,4,$ren_asimilados[nombre],0,0,'C');
			$pdf->SetXY($posx+2,$posy+165);
			$pdf->Cell(180,4,"PRESTADOR DEL SERVICIO",0,0,'C');
		
	}

$pdf->Output("pdfs/doc.pdf", "F");

echo "<script>";
echo "parent.nominapdf.location.replace('pdfs/doc.pdf');";
echo "</script>";

function formato_fecha($fechabaja){
	$fecha_Obtenida=explode('-',$fechabaja);
	$dia=$fecha_Obtenida[2];
	$mes_int=$fecha_Obtenida[1];
	switch($mes_int){
	case 1:
		$mes="Enero";
	break;
	case 2:
		$mes="Febrero";
	break;
	case 3:
		$mes="Marzo";
	break;
	case 4:
		$mes="Abril";
	break;
	case 5:
		$mes="Mayo";
	break;
	case 6:
		$mes="Junio";
	break;
	case 7:
		$mes="Julio";
	break;
	case 8:
		$mes="Agosto";
	break;
	case 9:
		$mes="Septiembre";
	break;
	case 10:
		$mes="Octubre";
	break;
	case 11:
		$mes="Noviembre";
	break;
	case 12:
		$mes="Diciembre";
	break;
	}
	$ano=$fecha_Obtenida[0];
	$fecha_Entregada=$dia." de ".$mes." de ".$ano;
	return $fecha_Entregada;
}

//--- funcion d enumero a letra
function texto_numero($cantidad){
$cantidad=(string)$cantidad;
$cantidad_p=explode(".",$cantidad);
$texto=letra_prin($cantidad_p[0]);

return $texto;
}
function letra_prin($cantidad){
	$longitud=strlen($cantidad);
	$texto="";
	if($longitud>=4 && $longitud<=5){
		if($longitud==4){
			$cantidad_enviada=$cantidad[0];
			$cantidad=$cantidad[1].$cantidad[2].$cantidad[3];
		}
		if($longitud==5){
			$cantidad_enviada=$cantidad[0].$cantidad[1];
			$cantidad=$cantidad[2].$cantidad[3].$cantidad[4];
			
		}
		$texto.=letra($cantidad_enviada,$longitud);
		$texto.=" MIL ";
	}
	$longitud=strlen($cantidad);
	if($longitud==3){
		$cantidad_enviada=$cantidad[0];
		$cantidad=$cantidad[1].$cantidad[2];
		$texto.=letra($cantidad_enviada,$longitud);
		if($cantidad_enviada=="5")
			$texto.="QUINICENTOS ";
		else if($cantidad_enviada=="0")
			$texto.="";
		else
			$texto.="CIENTOS ";
	}
	$longitud=strlen($cantidad);
	if($longitud==2){
		$texto.=letra($cantidad);
	}
	return $texto;
}
function letra($cantidad,$longitud_1){
	$longitud=strlen($cantidad);
	$letra="";
		switch($cantidad){
			case 1: 
				if($longitud_1>=3)
					$letra.="";
				else
					$letra.="UNO"; 
			break;
			case 2: $letra.="DOS"; break;
			case 3: $letra.="TRES"; break;
			case 4: $letra.="CUATRO"; break;
			case 5: 
				if($longitud_1==3)
					$letra.="";
				else
					$letra.="CINCO"; 
				break;
			case 6: $letra.="SEIS"; break;
			case 7: 
				if($longitud_1==3)
					$letra.="SETE";
				else
					$letra.="SIETE"; 
			break;
			case 8: $letra.="OCHO"; break;
			case 9:
				if($longitud_1==3)
					 $letra.="NOVE";
				else
					$letra.="NUEVE"; 
			break;
			case 10: $letra.="DIEZ "; break;
			case 11: $letra.="ONCE "; break;
			case 12: $letra.="DOCE "; break;
			case 13: $letra.="TRECE "; break;
			case 14: $letra.="CATORCE "; break;
			case 15: $letra.="QUINCE "; break;
			case 16: $letra.="DIECIS�IS "; break;
			case 17: $letra.="DIECISIETE "; break;
			case 18: $letra.="DIECIOCHO "; break;
			case 19: $letra.="DICECINUEVE "; break;
			case 20: $letra.="VEINTE "; break;
			case 21: $letra.="VEINTIUNO "; break;
			case 22: $letra.="VEINTIDOS "; break;
			case 23: $letra.="VEINTITRES "; break;
			case 24: $letra.="VEINTICUATRO "; break;
			case 25: $letra.="VEINTICINCO "; break;
			case 26: $letra.="VEINTISEIS "; break;
			case 27: $letra.="VEINTISIETE "; break;
			case 28: $letra.="VEINTIOCHO "; break;
			case 29: $letra.="VEINTINUEVE "; break;
			case 30: $letra.="TREINTA "; break;
			case 31: $letra.="TREINTA Y UNO "; break;
			case 32: $letra.="TREINTA Y DOS "; break;
			case 33: $letra.="TREINTA Y TRES "; break;
			case 34: $letra.="TREINTA Y CUATRO "; break;
			case 35: $letra.="TREINTA Y CINCO "; break;
			case 36: $letra.="TREINTA Y SEIS "; break;
			case 37: $letra.="TREINTA Y SIETE "; break;
			case 38: $letra.="TREINTA Y OCHO "; break;
			case 39: $letra.="TREINTA Y NUEVE "; break;
			case 40: $letra.="CUARENTA "; break;
			case 41: $letra.="CUARENTA Y UNO "; break;
			case 42: $letra.="CUARENTA Y DOS "; break;
			case 43: $letra.="CUARENTA Y TRES "; break;
			case 44: $letra.="CUARENTA Y CUATRO "; break;
			case 45: $letra.="CUARENTA Y CINCO "; break;
			case 46: $letra.="CUARENTA Y SEIS "; break;
			case 47: $letra.="CUARENTA Y SIETE "; break;
			case 48: $letra.="CUARENTA Y OCHO "; break;
			case 49: $letra.="CUARENTA Y NUEVE "; break;
			case 50: $letra.="CINCUENTA "; break;
			case 51: $letra.="CINCUENTA Y UNO "; break;
			case 52: $letra.="CINCUENTA Y DOS "; break;
			case 53: $letra.="CINCUENTA Y TRES "; break;
			case 54: $letra.="CINCUENTA Y CUATRO "; break;
			case 55: $letra.="CINCUENTA Y CINCO "; break;
			case 56: $letra.="CINCUENTA Y SEIS "; break;
			case 57: $letra.="CINCUENTA Y SIETE "; break;
			case 58: $letra.="CINCUENTA Y OCHO "; break;
			case 59: $letra.="CINCUENTA Y NUEVE "; break;
			case 60: $letra.="SESENTA "; break;
			case 61: $letra.="SESENTA Y UNO "; break;
			case 62: $letra.="SESENTA Y DOS "; break;
			case 63: $letra.="SESENTA Y TRES "; break;
			case 64: $letra.="SESENTA Y CUATRO "; break;
			case 65: $letra.="SESENTA Y CINCO "; break;
			case 66: $letra.="SESENTA Y SEIS "; break;
			case 67: $letra.="SESENTA Y SIETE "; break;
			case 68: $letra.="SESENTA Y OCHO "; break;
			case 69: $letra.="SESENTA Y NUEVE "; break;
			case 70: $letra.="SETENTA "; break;
			case 71: $letra.="SETENTA Y UNO "; break;
			case 72: $letra.="SETENTA Y DOS "; break;
			case 73: $letra.="SETENTA Y TRES "; break;
			case 74: $letra.="SETENTA Y CUATRO "; break;
			case 75: $letra.="SETENTA Y CINCO "; break;
			case 76: $letra.="SETENTA Y SEIS "; break;
			case 77: $letra.="SETENTA Y SIETE "; break;
			case 78: $letra.="SETENTA Y OCHO "; break;
			case 79: $letra.="SETENTA Y NUEVE "; break;
			case 80: $letra.="OCHENTA "; break;
			case 81: $letra.="OCHENTA Y UNO "; break;
			case 82: $letra.="OCHENTA Y DOS "; break;
			case 83: $letra.="OCHENTA Y TRES "; break;
			case 84: $letra.="OCHENTA Y CUATRO "; break;
			case 85: $letra.="OCHENTA Y CINCO "; break;
			case 86: $letra.="OCHENTA Y SEIS "; break;
			case 87: $letra.="OCHENTA Y SIETE "; break;
			case 88: $letra.="OCHENTA Y OCHO "; break;
			case 89: $letra.="OCHENTA Y NUEVE "; break;
			case 90: $letra.="NOVENTA "; break;
			case 91: $letra.="NOVENTA Y UNO "; break;
			case 92: $letra.="NOVENTA Y DOS "; break;
			case 93: $letra.="NOVENTA Y TRES "; break;
			case 94: $letra.="NOVENTA Y CUATRO "; break;
			case 95: $letra.="NOVENTA Y CINCO "; break;
			case 96: $letra.="NOVENTA Y SEIS "; break;
			case 97: $letra.="NOVENTA Y SIETE "; break;
			case 98: $letra.="NOVENTA Y OCHO "; break;
			case 99: $letra.="NOVENTA Y NUEVE "; break;

	}
	return($letra);
}
?>
