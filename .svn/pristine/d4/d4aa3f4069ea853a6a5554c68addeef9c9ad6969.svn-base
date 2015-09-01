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

   if($align == 1) // alineación izquierda
   {
        $espacios = $long - $n;
        for($i = 1; $i <= $espacios; $i++)
        {
           $texto .= " ";
        }
   }

   if($align == 2) // alineación derecha
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


$sql = "Select importe from salmin";
$res_sm = mysql_query($sql, $conexion);
$ren_sm = mysql_fetch_array($res_sm);
$sm = $ren_sm["importe"];
mysql_free_result($res_sm);



$sql = "CREATE temporary TABLE `nominapago_temp` (";
$sql .= " `idnominapago` int(11) NOT NULL AUTO_INCREMENT,";
$sql .= " `idnominaemp` int(11) DEFAULT NULL,";
$sql .= " `cve_concepto` varchar(5) DEFAULT NULL,";
$sql .= " `importe` decimal(10,2) DEFAULT '0.00',";
$sql .= " `tipo` varchar(1) DEFAULT NULL,";
$sql .= " `quincena` int(11) DEFAULT NULL,";
$sql .= " `anio` char(4) DEFAULT NULL,";
$sql .= " `fechaemision` date DEFAULT NULL,";
$sql .= " `fechasistema` datetime DEFAULT NULL,";
$sql .= " `usuario` int(11) DEFAULT NULL,";
$sql .= " `cve_sat` varchar(5) DEFAULT NULL,";
$sql .= " `idarea` int(11) DEFAULT NULL,";
$sql .= " PRIMARY KEY (`idnominapago`)";
$sql .= "  ) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;";
$res = @mysql_query($sql, $conexion);


if($quincena < 25)
{
	$sql = "Delete from nominapago_temp where quincena = '$quincena' and anio = '" . date("Y") . "'";
	$res = mysql_query($sql, $conexion);
	
	$sql = "insert into nominapago_temp(idnominaemp, cve_concepto, importe, tipo, quincena, anio, fechaemision, usuario, cve_sat, idarea)";
	$sql .= " select n.idnominaemp, n.concepto, n.importe, n.tipo, '$quincena', '" . date("Y") . "', curdate(), '1', c.cve_sat, e.area";
	$sql .= " from nomina n"; 
	$sql .= " left join cat_conceptos c on n.concepto = c.clave";
	$sql .= " left join nominaemp e on n.idnominaemp = e.idnominaemp and e.activo = '1'";
	$res = mysql_query($sql, $conexion);
}


if(isset($_POST["aguinaldo"]) && $_POST["aguinaldo"] == "C")
{
	$sql = "insert into nominapago_temp(idnominaemp, cve_concepto, importe, tipo, quincena, anio, fechaemision, fechasistema, usuario, cve_sat, idarea)";
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
	
	
	$sql = " insert into nominapago_temp(idnominaemp, cve_concepto, importe, tipo, quincena, anio, fechaemision, fechasistema, usuario, cve_sat, idarea)";
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
		$sql = "Delete from nominapago_temp where quincena = '$quincena' and anio = '" . date("Y") . "'";
		$res = mysql_query($sql, $conexion);
	}
	
	$sql = "insert into nominapago_temp(idnominaemp, cve_concepto, importe, tipo, quincena, anio, fechaemision, fechasistema, usuario, cve_sat, idarea)";
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
	
	
	$sql = " insert into nominapago_temp(idnominaemp, cve_concepto, importe, tipo, quincena, anio, fechaemision, fechasistema, usuario, cve_sat, idarea)";
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
	$sql = "insert into nominapago_temp(idnominaemp, cve_concepto, importe, tipo, quincena, anio, fechaemision, fechasistema, usuario, cve_sat, idarea)";
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
	$sql .= " where n.activo = '1'";
	$res = mysql_query($sql, $conexion);
	
	
	$sql = " insert into nominapago_temp(idnominaemp, cve_concepto, importe, tipo, quincena, anio, fechaemision, fechasistema, usuario, cve_sat, idarea)";
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
	$sql .= " left join cat_conceptos c on c.clave = '119' where n.activo = '1') x) y";
	$sql .= " left join isr i on ((y.importe - y.salmin) between i.limiteinferior and i.limitesuperior) or ((y.importe - y.salmin) >= i.limiteinferior and i.limitesuperior = 0)) z";
	$res = mysql_query($sql, $conexion);
}


$sql = "select p.idnominaemp, e.curp, concat(ifnull(e.rfc_iniciales, ''), ifnull(e.rfc_fechanac, ''), ifnull(e.rfc_homoclave, '')) as rfc, concat(e.paterno, ' ', e.materno, ' ', e.nombres) as nombre, concat(a.clave, ' ', a.descripcion) as ur, c.descripcion as categoria,";
$sql .= " concat(lpad(day(e.fechaingr), 2, '0'), '/', lpad(month(e.fechaingr), 2, '0'), '/', year(e.fechaingr)) as fechaingr, case when e.salariofv = 'F' then 'FIJO' else 'VARIABLE' end as salariofv, day(curdate()) as dia, e.nafiliacion, case when e.jornada = '1' then 'DIURNA' when e.jornada = '2' then 'NOCTURNA' when e.jornada = '3' then 'MIXTA' when e.jornada = '4' then 'ESPECIAL' else '' end as jornada";
$sql .= " from nominapago_temp p";
$sql .= " left join nominaemp e on p.idnominaemp = e.idnominaemp";
$sql .= " left join cat_area a on e.area = a.idarea";
$sql .= " left join cat_categoria c on e.categoria = c.idcategoria";
$sql .= " where p.quincena = '$quincena' and p.anio = '" . date("Y") . "' and p.idarea = '$_POST[ur]' and e.activo = '1'";
$sql .= " group by p.idnominaemp, e.curp, concat(ifnull(e.rfc_iniciales, ''), ifnull(e.rfc_fechanac, ''), ifnull(e.rfc_homoclave, '')), e.paterno, e.materno, e.nombres, a.clave, a.descripcion, c.descripcion, e.fechaingr, e.salariofv, e.nafiliacion, e.jornada";
$res_emp = mysql_query($sql, $conexion);

include("./fpdf/fpdf.php");

    $pdf=new FPDF('P', 'mm', 'A4');
	$pdf->SetMargins(4, 4, 4);
    $pdf->Open();

	while($ren_emp = mysql_fetch_array($res_emp))
	{
		$pdf->AddPage();
	
		include("membrete_recibos.php");
		
		$nombre = $ren_emp["nombre"];
		$dia = $ren_emp["dia"];
		$ur = $ren_emp["ur"];
		$rfc = $ren_emp["rfc"];
		$fechaingr = $ren_emp["fechaingr"];
		$nafiliacion = $ren_emp["nafiliacion"];
		$curp = $ren_emp["curp"];
		$jornada = $ren_emp["jornada"];
		$salariofv = $ren_emp["salariofv"];
	
	
		$pdf->cell(15,5,"Nombre: ", 0, 0, 'L');
		$pdf->cell(85,5,$nombre, 0, 0, 'L');
		$pdf->cell(25,5,"Días del período: ", 0, 0, 'L');
		$pdf->cell(75,5,$dia, 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"UR: ", 0, 0, 'L');
		$pdf->cell(85,5,$ur, 0, 0, 'L');
		$pdf->cell(25,5,"Período del: ", 0, 0, 'L');
		$pdf->cell(75,5,$_POST["fdesde"], 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"R.F.C.:", 0, 0, 'L');
		$pdf->cell(85,5,$rfc, 0, 0, 'L');
		$pdf->cell(25,5,"Al: ", 0, 0, 'L');
		$pdf->cell(75,5,$_POST["fhasta"], 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"Ingreso: ", 0, 0, 'L');
		$pdf->cell(85,5,$fechaingr, 0, 0, 'L');
		$pdf->cell(25,5,"Afiliación: ", 0, 0, 'L');
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
		
		$sql = "select n.tipo, n.cve_concepto, c.descripcion as concepto, sum(n.importe) as importe  from nominapago_temp n";
		$sql .= " left join cat_conceptos c on n.cve_concepto = c.clave";
		$sql .= " where n.quincena = '$quincena' and n.anio = '" . date("Y") . "' and n.idarea = '$_POST[ur]' and n.idnominaemp = '$ren_emp[idnominaemp]'";
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
		
		$pdf->Text(107, 108, "Recibí la cantidad indicada que cubre a la fecha todas las percepciones que tengo");
		$pdf->Text(107, 111, "derecho sin que se me adeude cantidad alguna.");
		$pdf->Rect(104, 104, 100, 9);
		
		$pdf->Line(65, 125, 145, 125);
		
		$pdf->Text(99, 130, "R E C I B Í");
		
		$pdf->Ln(69);	
		$pdf->cell(202,5,$nombre, 0, 0, 'C');
		
	/********************************** copia ***********************************************************************/	
		$pdf->Line(5, 139, 202, 139);
		
		$pdf->Ln(35);
		$pdf->cell(15,5,"Nombre: ", 0, 0, 'L');
		$pdf->cell(85,5,$nombre, 0, 0, 'L');
		$pdf->cell(25,5,"Días del período: ", 0, 0, 'L');
		$pdf->cell(75,5,$dia, 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"UR: ", 0, 0, 'L');
		$pdf->cell(85,5,$ur, 0, 0, 'L');
		$pdf->cell(25,5,"Período del: ", 0, 0, 'L');
		$pdf->cell(75,5,$_POST["fdesde"], 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"R.F.C.:", 0, 0, 'L');
		$pdf->cell(85,5,$rfc, 0, 0, 'L');
		$pdf->cell(25,5,"Al: ", 0, 0, 'L');
		$pdf->cell(75,5,$_POST["fhasta"], 0, 0, 'L');
		$pdf->Ln(5);
		$pdf->cell(15,5,"Ingreso: ", 0, 0, 'L');
		$pdf->cell(85,5,$fechaingr, 0, 0, 'L');
		$pdf->cell(25,5,"Afiliación: ", 0, 0, 'L');
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
		
		$sql = "select n.tipo, n.cve_concepto, c.descripcion as concepto, sum(n.importe) as importe  from nominapago_temp n";
		$sql .= " left join cat_conceptos c on n.cve_concepto = c.clave";
		$sql .= " where n.quincena = '$quincena' and n.anio = '" . date("Y") . "' and n.idarea = '$_POST[ur]' and n.idnominaemp = '$ren_emp[idnominaemp]'";
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
		
		$pdf->Text(107, 248, "Recibí la cantidad indicada que cubre a la fecha todas las percepciones que tengo");
		$pdf->Text(107, 251, "derecho sin que se me adeude cantidad alguna.");
		$pdf->Rect(104, 244, 100, 9);
		
		$pdf->Line(65, 265, 145, 265);
		
		$pdf->Text(99, 270, "R E C I B Í");
		
		$pdf->Ln(70);	
		$pdf->cell(202,5,$nombre, 0, 0, 'C');
	}


$sql = "Drop table nominapago_temp";
$res = @mysql_query($sql, $conexion);
	
$pdf->Output("pdfs/doc.pdf", "F");

echo "<script>";
echo "parent.nominapdf.location.replace('pdfs/doc.pdf');";
echo "</script>";
?>
