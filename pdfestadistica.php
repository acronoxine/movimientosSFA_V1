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


$quin = explode("|", $_POST["quincena"]); 
$quincena = $quin[0];


$sql = "Select importe from salmin";
$res_sm = mysql_query($sql, $conexion);
$ren_sm = mysql_fetch_array($res_sm);
$sm = $ren_sm["importe"];
mysql_free_result($res_sm);



$sql = "CREATE temporary TABLE `nominapago_temp` (";
$sql .= " `idnominapago_temp` int(11) NOT NULL AUTO_INCREMENT,";
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
$sql .= " PRIMARY KEY (`idnominapago_temp`)";
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
	$sql .= " where n.activo = '1' and datediff(curdate(),n.fechainicio) > 181";
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
	$sql .= " left join cat_conceptos c on c.clave = '119' where n.activo = '1' and datediff(curdate(),n.fechainicio) > 181) x) y";
	$sql .= " left join isr i on ((y.importe - y.salmin) between i.limiteinferior and i.limitesuperior) or ((y.importe - y.salmin) >= i.limiteinferior and i.limitesuperior = 0)) z";
	$res = mysql_query($sql, $conexion);
}


if($_POST["ur"] == "")
	$ur = "";
else
	$ur = "and p.idarea = '$_POST[ur]'";

$sql = "select * from";
$sql .= " (select idnominaemp, ur, nombre, otrasper as perquinact, otrasded as dedquinact,";
$sql .= " (otrasper) - (otrasded) as netoquinact from";
$sql .= " (select idnominaemp, ur, nombre, sueldobase,";
$sql .= " sum(case when tipo = 'P' then importe else 0 end) as otrasper,";
$sql .= " sum(case when tipo = 'D' then importe else 0 end) as otrasded";
$sql .= " from";
$sql .= " (select p.idnominaemp, a.idarea as ur, concat(e.paterno, ' ', e.materno, ' ', e.nombres) as nombre, e.sueldobase, p.cve_concepto, c.descripcion as concepto, p.tipo, p.importe ";
$sql .= " from nominapago_temp p";
$sql .= " left join nominaemp e on p.idnominaemp = e.idnominaemp";
$sql .= " left join cat_conceptos c on p.cve_concepto = c.clave";
$sql .= " left join cat_area a on p.idarea = a.idarea";
$sql .= " where quincena = '$quincena' and anio = '" . date("Y") . "' $ur and e.activo = '1') x group by ur, nombre, sueldobase) y) qact";
$sql .= " left join";
$sql .= " (select idnominaemp, otrasper as perquinhis, otrasded as dedquinhis,";
$sql .= " (otrasper) - (otrasded) as netoquinhis from";
$sql .= " (select idnominaemp, ur, nombre, sueldobase,";
$sql .= " sum(case when tipo = 'P' then importe else 0 end) as otrasper,";
$sql .= " sum(case when tipo = 'D' then importe else 0 end) as otrasded";
$sql .= " from";
$sql .= " (select p.idnominaemp, a.idarea as ur, concat(e.paterno, ' ', e.materno, ' ', e.nombres) as nombre, e.sueldobase, p.cve_concepto, c.descripcion as concepto, p.tipo, p.importe";
$sql .= " from nominapagohist p";
$sql .= " left join nominaemp e on p.idnominaemp = e.idnominaemp";
$sql .= " left join cat_conceptos c on p.cve_concepto = c.clave";
$sql .= " left join cat_area a on p.idarea = a.idarea";
$sql .= " where quincena = '$_POST[quincenacom]' and anio = '$_POST[aniocom]' $ur and e.activo = '1') x group by ur, nombre, sueldobase) y) qhis";
$sql .= " on qact.idnominaemp = qhis.idnominaemp order by qact.ur, qact.nombre";


$res = mysql_query($sql, $conexion);

$num = mysql_num_rows($res);


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

include("./fpdf/fpdf.php");

    $pdf=new FPDF('L', 'mm', 'A4');
	$pdf->SetMargins(4, 4, 4);
    $pdf->Open();


	$perquinact = 0;
	$dedquinact = 0;
	$netoquinact = 0;
	$perquinhis = 0;
	$dedquinhis = 0;
	$netoquinhis = 0;
	
	$otraur = "";
	$renglon = 1;
	while($ren = mysql_fetch_array($res))
	{
		
		if($otraur != $ren["ur"])
		{
			$pdf->AddPage();
			
			$sql_ur = "Select clave, descripcion from cat_area where idarea = '$ren[ur]'";
			$res_ur = mysql_query($sql_ur, $conexion);
			$ren_ur = mysql_fetch_array($res_ur);
			$claveur = $ren_ur["clave"];
			$descripcionur = $ren_ur["descripcion"];
			mysql_free_result($res_ur);
			
			include("membrete_estadistica.php");
			
			$otraur = $ren["ur"];
			$renglon = 1;
		}
		
		if($renglon == 13)
		{
			$pdf->AddPage();
			include("membrete_estadistica.php");
			$renglon = 1;
		}
		
		
		$pdf->cell(57,10,$ren["nombre"], 1, 0, 'C');
		$pdf->cell(22,10,number_format($ren["perquinact"], 2, ".", ","), 1, 0, 'R');
		$pdf->cell(22,10,number_format($ren["dedquinact"], 2, ".", ","), 1, 0, 'R');
		$pdf->cell(22,10,number_format($ren["netoquinact"], 2, ".", ","), 1, 0, 'R');
		$pdf->cell(22,10,number_format($ren["perquinhis"], 2, ".", ","), 1, 0, 'R');
		$pdf->cell(22,10,number_format($ren["dedquinhis"], 2, ".", ","), 1, 0, 'R');
		$pdf->cell(22,10,number_format($ren["netoquinhis"], 2, ".", ","), 1, 0, 'R');

		
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(220,220,220);
		$pdf->SetLineWidth(.2);
		
		$perquinact += $ren["perquinact"];
		$dedquinact += $ren["dedquinact"];
		$netoquinact += $ren["netoquinact"];
		$perquinhis += $ren["perquinhis"];
		$dedquinhis += $ren["dedquinhis"];
		$netoquinhis += $ren["netoquinhis"];
		
		$pdf->Ln(10);
		
		$renglon++;
	}
	
	$pdf->Ln(1);
	
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
 
	$pdf->cell(57,7,"TOTALES ($num)", 1, 0, 'C');
	$pdf->cell(22,7,number_format($perquinact, 2, ".", ","), 1, 0, 'R');
	$pdf->cell(22,7,number_format($dedquinact, 2, ".", ","), 1, 0, 'R');
	$pdf->cell(22,7,number_format($netoquinact, 2, ".", ","), 1, 0, 'R');
	$pdf->cell(22,7,number_format($perquinhis, 2, ".", ","), 1, 0, 'R');
	$pdf->cell(22,7,number_format($dedquinhis, 2, ".", ","), 1, 0, 'R');
	$pdf->cell(22,7,number_format($netoquinhis, 2, ".", ","), 1, 0, 'R');

$sql = "Drop table nominapago_temp";
$res = @mysql_query($sql, $conexion);	
   
$pdf->Output("pdfs/doc.pdf", "F");

echo "<script>";
echo "parent.nominapdf.location.replace('pdfs/doc.pdf');";
echo "</script>";
?>
