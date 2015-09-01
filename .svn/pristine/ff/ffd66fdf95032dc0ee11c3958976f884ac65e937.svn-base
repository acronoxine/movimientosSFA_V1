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

if($quincena < 25)
{
	$sql = "Delete from nominapension where quincena = '$quincena' and anio = '" . date("Y") . "'";
	$res = mysql_query($sql, $conexion);
	
	$sql = "insert into nominapension(idnominaemp, concepto, importe, quincena, anio, fechaemision, usuario, idarea)";
	$sql .= " select b.idnominaemp, '252' as concepto, b.importe, '$quincena', '" . date("Y") . "', curdate(), '$_SESSION[m_idregistro]', e.area";
	$sql .= " from cat_beneficiarios b"; 
	$sql .= " left join nominaemp e on b.idnominaemp = e.idnominaemp and e.activo = '1'";
	$sql .= " left join nominapension p on p.quincena = '$quincena' and p.anio = '" . date("Y") . "'";
	$sql .= " where p.quincena is null and p.anio is null";
	$res = mysql_query($sql, $conexion);
}


$sql = "Select importe from salmin";
$res_sm = mysql_query($sql, $conexion);
$ren_sm = mysql_fetch_array($res_sm);
$sm = $ren_sm["importe"];
mysql_free_result($res_sm);



if(isset($_POST["aguinaldo"]) && $_POST["aguinaldo"] == "C")
{
	$sql = "insert into nominapension(idnominaemp, concepto, importe, quincena, anio, fechaemision, fechasistema, usuario, idarea)";
	$sql .= "select n.idnominaemp, '115' as concepto,";
	$sql .= " case";
	$sql .= " when c.uso = 'IMP' then";
	$sql .= "     c.importe";
	$sql .= " when c.uso = 'DIA' then";
	$sql .= "     cast(((n.sueldobase * 2) / 30) * c.dias as decimal(10,2))";
	$sql .= " when c.uso = 'POR' then";
	$sql .= "     cast(((n.sueldobase * 2) * (c.porcentaje/100)) as decimal(10,2))";
	$sql .= " else";
	$sql .= "     0";
	$sql .= " end * (b.porcentaje / 100) as importe,";
	$sql .= " '$quincena' as quincena, '" . date("Y") . "' as anio, curdate() as fechaemision, curdate() as fechasistema, '$_SESSION[m_idregistro]' as usuario, n.area";
	$sql .= " from nominaemp n inner join cat_beneficiarios b on n.idnominaemp = b.idnominaemp";
	$sql .= " left join cat_conceptos c on c.clave = '115'";
	$sql .= " where n.activo = '1'";
	
	$res = mysql_query($sql, $conexion);
}


if((isset($_POST["aguinaldo"]) && $_POST["aguinaldo"] == "1P") || $quincena == 25)
{
	if($quincena == 25)
	{
		$sql = "Delete from nominapension where quincena = '$quincena' and anio = '" . date("Y") . "'";
		$res = mysql_query($sql, $conexion);
	}
	
	$sql = "insert into nominapension(idnominaemp, concepto, importe, quincena, anio, fechaemision, fechasistema, usuario, idarea)";
	$sql .= " select n.idnominaemp, '115' as concepto,";
	$sql .= " ((case";
	$sql .= " when c.uso = 'IMP' then";
	$sql .= "     c.importe";
	$sql .= " when c.uso = 'DIA' then";
	$sql .= "     cast(((n.sueldobase * 2) / 30) * c.dias as decimal(10,2))";
	$sql .= " when c.uso = 'POR' then";
	$sql .= "     cast(((n.sueldobase * 2) * (c.porcentaje/100)) as decimal(10,2))";
	$sql .= " else";
	$sql .= "     0";
	$sql .= " end)/2) * (b.porcentaje / 100) as importe,";
	$sql .= " '$quincena' as quincena, '" . date("Y") . "' as anio, curdate() as fechaemision, curdate() as fechasistema, '$_SESSION[m_idregistro]' as usuario, n.area";
	$sql .= " from nominaemp n inner join cat_beneficiarios b on n.idnominaemp = b.idnominaemp";
	$sql .= " left join cat_conceptos c on c.clave = '115'";
	$sql .= " where n.activo = '1'";
	$res = mysql_query($sql, $conexion);
	
}


if(isset($_POST["prima"]))
{
	$sql = "insert into nominapension(idnominaemp, concepto, importe, quincena, anio, fechaemision, fechasistema, usuario, idarea)";
	$sql .= " select n.idnominaemp, '119' as concepto,";
	$sql .= " case";
	$sql .= " when c.uso = 'IMP' then";
	$sql .= "     c.importe";
	$sql .= " when c.uso = 'DIA' then";
	$sql .= "     cast((((n.sueldobase * 2) / 30) * 0.25) * c.dias as decimal(10,2))";
	$sql .= " when c.uso = 'POR' then";
	$sql .= "     cast(((n.sueldobase * 2) * (c.porcentaje/100)) as decimal(10,2))";
	$sql .= " else";
	$sql .= "     0";
	$sql .= " end * (b.porcentaje / 100) as importe,";
	$sql .= " '$quincena' as quincena, '" . date("Y") . "' as anio, curdate() as fechaemision, curdate() as fechasistema, '$_SESSION[m_idregistro]' as usuario, n.area";
	$sql .= " from nominaemp n inner join cat_beneficiarios b on n.idnominaemp = b.idnominaemp";
	$sql .= " left join cat_conceptos c on c.clave = '119'";
	$sql .= " where n.activo = '1'";
	$res = mysql_query($sql, $conexion);
	
}


$sql = "select p.idnominaemp, concat(b.paterno, ' ', b.materno, ' ', b.nombres) as nombre, concat(a.clave, ' ', a.descripcion) as ur,";
$sql .= " day(curdate()) as dia";
$sql .= " from nominapension p";
$sql .= " left join cat_beneficiarios b on p.idnominaemp = b.idnominaemp";
$sql .= " left join nominaemp e on p.idnominaemp = e.idnominaemp";
$sql .= " left join cat_area a on e.area = a.idarea";
$sql .= " where p.quincena = '$quincena' and p.anio = '" . date("Y") . "' and a.idarea = '$_POST[ur]' and e.activo = '1'";
$sql .= " group by p.idnominaemp, e.paterno, e.materno, e.nombres, a.clave, a.descripcion";
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
		$rfc = "";
		$fechaingr = "";
		$nafiliacion = "";
		$curp = "";
		$jornada = "";
		$salariofv = "";
	
	
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
		
		$sql = "select 'P' as tipo, n.concepto as cve_concepto, c.descripcion as concepto, sum(n.importe) as importe  from nominapension n";
		$sql .= " left join cat_conceptos c on n.concepto = c.clave";
		$sql .= " where n.quincena = '$quincena' and n.anio = '" . date("Y") . "' and n.idarea = '$_POST[ur]' and n.idnominaemp = '$ren_emp[idnominaemp]'";
		$sql .= " group by n.concepto, c.descripcion";
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
		
		$sql = "select 'P' as tipo, n.concepto as cve_concepto, c.descripcion as concepto, sum(n.importe) as importe  from nominapension n";
		$sql .= " left join cat_conceptos c on n.concepto = c.clave";
		$sql .= " where n.quincena = '$quincena' and n.anio = '" . date("Y") . "' and n.idarea = '$_POST[ur]' and n.idnominaemp = '$ren_emp[idnominaemp]'";
		$sql .= " group by n.concepto, c.descripcion";
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

   
$pdf->Output("pdfs/doc.pdf", "F");

echo "<script>";
echo "parent.nominapdf.location.replace('pdfs/doc.pdf');";
echo "</script>";
?>
