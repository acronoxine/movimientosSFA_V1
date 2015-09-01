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

$mh=$_GET['idmh'];

$query_empleado = "SELECT * from movimiento_historial where idmovimiento=".$mh;
$res = mysql_query($query_empleado, $conexion);
$ren = mysql_fetch_array($res);

include("./fpdf/fpdf.php");

    $pdf=new FPDF('P', 'mm', 'A4');
	$pdf->SetMargins(1, 1, 1);
    $pdf->Open();

	$pdf->AddPage();
	
	$pdf->Image("imagenes/formato.jpg", 1, 1, 202);

	$pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    /*$pdf->SetFont('Arial','B',9);*/
    $pdf->SetFont('courier','B',7);
	
	$pdf->Text(30, 43, $ren["rfc"]);
	$pdf->Text(88, 43, $ren["curp"]);
	$pdf->Text(174, 41, campo($ren["folio"], 10, 2));

	$pdf->SetFont('Arial','B',8);
	
	$sql = "select upp as cveupp, razonsocial as upp, titular from empresa limit 1";
	$res_emp = mysql_query($sql, $conexion);
	$ren_emp = mysql_fetch_array($res_emp);
	
	$titularupp = $ren_emp["titular"];
	
	$pdf->Text(17, 56, $ren_emp["upp"]);
	mysql_free_result($res_emp);
	
	$pdf->Text(17, 63.5, $ren["area"]);
	$pdf->Text(17, 71, $ren["programa"]);
	$pdf->Text(17, 78.3, $ren["subprograma"]);
	$pdf->Text(17, 86, '');
	$pdf->Text(17, 93.3, $ren["categoria"]);
	$pdf->Text(17, 100.3, $ren["plaza_id"]);
	$pdf->Ln(52);	
	
	$pdf->Cell(182.2);
	$pdf->Cell(11, 4.5, $ren_emp["cveupp"], 0, 0, 'C');
	
	$pdf->Ln(7.5);
	$pdf->Cell(182.2);
	$pdf->Cell(11, 4.5, $ren["cvearea"], 0, 0, 'C');
	
	$pdf->Ln(7.3);
	$pdf->Cell(182.2);
	$pdf->Cell(11, 4.5, $ren["cveprograma"], 0, 0, 'C');
	
	$pdf->Ln(7.5);
	$pdf->Cell(182.2);
	$pdf->Cell(11, 4.5, $ren["cvesubprograma"], 0, 0, 'C');
	
	$pdf->Ln(7.5);
	$pdf->Cell(182.2);
	$pdf->Cell(11, 4.5, '', 0, 0, 'C');
	
	$pdf->Ln(7.4);
	$pdf->Cell(182.2);
	$pdf->Cell(11, 4.5, $ren["cvecategoria"], 0, 0, 'C');
	
	$pdf->Ln(7.4);
	$pdf->Cell(182.2);
	$pdf->Cell(11, 4.5, $ren["plaza_clave"], 0, 0, 'C');

	$pdf->Text(107.7, 108.7, $ren["movimiento"]);
	$pdf->SetFont('Arial','B',5);
	$pdf->Text(184.0, 108.7, $ren["contrato_emp"]);
	$pdf->SetFont('Arial','B',8);
	$finicial=explode("-",$ren["fecha_inicial"]);
	$ffinal=explode("-",$ren["fecha_final"]);
	$pdf->Text(97.3, 120.5, $finicial[0]);
	$pdf->Text(107.7, 120.5,$finicial[1]);
	$pdf->Text(117.5, 120.5, $finicial[2]);
	
	$pdf->Text(166.5, 120.5, $ffinal[0]);
	$pdf->Text(177, 120.5, $ffinal[1]);
	$pdf->Text(187.5, 120.5, $ffinal[2]);
	
	$pdf->Ln(34.4);
	
	$pdf->Cell(13);
	$pdf->Cell(57.5, 5, $ren["paterno"], 0, 0, 'C');
	$pdf->Cell(52, 5, $ren["materno"], 0, 0, 'C');
	$pdf->Cell(71, 5, $ren["nombres"], 0, 0, 'C');
	
	$pdf->Ln(9);
	
	$pdf->Cell(13);
	$pdf->Cell(109, 5, $ren["calle"] . " " . $ren["numext"] . " " . $ren["numint"] . " " . $ren["colonia"], 0, 0, 'C');
	$pdf->Cell(39, 5, $ren["ciudad"], 0, 0, 'C');
	$pdf->Cell(32.5, 5, $ren["ciudad"], 0, 0, 'C');
	
	$pdf->Ln(16);
	
	$pdf->Cell(13);
	$pdf->Cell(10, 5, $ren["nacanio"], 0, 0, 'C');
	$pdf->Cell(6);
	$pdf->Cell(10, 5, $ren["nacmes"], 0, 0, 'C');
	$pdf->Cell(4);
	$pdf->Cell(10, 5, $ren["nacdia"], 0, 0, 'C');
	
	if($ren["sexo"] == "MASCULINO")
		$pdf->Text(76.5, 154.5, "X");
	else
		$pdf->Text(76.5, 157, "X");
		
	$pdf->Text(114, 153, $ren["jornada"]);
	
	$pdf->Text(113, 161.5, "$ren[de_hrs]:$ren[de_min] a $ren[a_hrs]:$ren[a_min] HRS.");
	
	$pdf->Text(146.5, 160, $ren["ecivil"]);
	
	$pdf->Text(167, 154, $ren["escolaridad"]);
	
	$pdf->Ln(10.8);
	
	$pdf->Cell(13);
	$pdf->Cell(39.5, 5, $ren["nafiliacion"], 0, 0, 'C');
	$pdf->Cell(40.5, 5, $ren["nafiliacionissste"], 0, 0, 'C');
	$pdf->Cell(50, 5, $ren["oficinadepago"], 0, 0, 'C');
	$pdf->Cell(18, 5, $ren["cartillaSMN"], 0, 0, 'C');
	$pdf->Cell(32.5, 5, $ren["nacionalidad"], 0, 0, 'C');
	
	$pdf->Ln(13);
	
	$sql = "select cveconcepto as concepto, importe from movimiento_hconcepto where idmovimiento =".$mh;
	$res_con = mysql_query($sql, $conexion);
	$c = 1;
	$r = 1;
	while($ren_con = mysql_fetch_array($res_con))
	{
		
		if($c == 1)
		{
			$pdf->Cell(13);
			$pdf->Cell(17.5, 4, $ren_con["concepto"], 0, 0, 'C');
			$pdf->Cell(22, 4, number_format($ren_con["importe"], 2, ".", ","), 0, 0, 'C');
		}
		
		if($c == 2)
		{
			$pdf->Cell(17.8, 4, $ren_con["concepto"], 0, 0, 'C');
			$pdf->Cell(22.5, 4, number_format($ren_con["importe"], 2, ".", ","), 0, 0, 'C');
		}
		
		if($c == 3)
		{
			$pdf->Cell(20.5, 4, $ren_con["concepto"], 0, 0, 'C');
			$pdf->Cell(30, 4, number_format($ren_con["importe"], 2, ".", ","), 0, 0, 'C');
		}
		
		if($c == 4)
		{
			$pdf->Cell(17.5, 4, $ren_con["concepto"], 0, 0, 'C');
			$pdf->Cell(32.5, 4, number_format($ren_con["importe"], 2, ".", ","), 0, 0, 'C');
			$pdf->Ln(7);
			$c = 0;
		}
		
		$c++;
		$r++;
		
	}
		
	if($r >= 6)
		$pdf->Ln(28);
	else
		$pdf->Ln(39);
		
	$pdf->Cell(85, 4, $ren["titular_ur"], 0, 0, 'C');
	$pdf->Cell(30, 4, "", 0, 0, 'C');
	$pdf->Cell(85, 4, $ren["titular_upp"], 0, 0, 'C');
	
	$pdf->Ln(13.5);
	$pdf->Cell(197, 4, $ren["titular_director"], 0, 0, 'C');
   
$pdf->Output("pdfs/historial/doc.pdf", "F");

echo "<script>";
echo "location.replace('pdfs/historial/doc.pdf');";

echo "</script>";

?>
