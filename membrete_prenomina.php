<?
	$pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',10);
    $pdf->Image("imagenes/logo.jpg", 1, 1, 40);
   
   
	$pdf->Ln(30);
  
	$pdf->cell(290,8,"NOMINA QUINCENAL No. $quincena DEL $_POST[fdesde] AL $_POST[fhasta]", 0, 0, 'C');
	
	$pdf->Ln(5);
	
	$pdf->cell(290,8,"UR-$claveur $descripcionur", 0, 0, 'C');
	
	$pdf->Ln(12);
	
	$pdf->SetFont('Arial','B',6);
	

	$pdf->cell(57,7,"", 1, 0, 'C');	
	$pdf->cell(108,7,"PERCEPCIONES", 1, 0, 'C');
	$pdf->cell(56,7,"DEDUCCIONES", 1, 0, 'C');
	$pdf->cell(22,14,"NETO A PAGAR", 1, 0, 'C');

	$pdf->Ln(7);
	
	$pdf->cell(57,7,"TRABAJADOR", 1, 0, 'C');
	$pdf->cell(22,7,"SUELDO", 1, 0, 'C');
	$pdf->cell(22,7,"COMPENSACION", 1, 0, 'C');
	$pdf->cell(22,7,"PRIMA VAC.", 1, 0, 'C');	
	$pdf->cell(20,7,"AGUINALDO", 1, 0, 'C');
	$pdf->cell(22,7,"OTRAS PERCEP.", 1, 0, 'C');
	$pdf->cell(17,7,"ISR", 1, 0, 'C');
	$pdf->cell(17,7,"IMSS", 1, 0, 'C');
	$pdf->cell(22,7,"OTRAS DEDUC.", 1, 0, 'C');
	
	$pdf->Ln(8);

	$pdf->SetFont('Arial','B',7);
	
    switch(date("m"))
    {
	    case "01": $mesnombre = "Enero"; break;
	    case "02": $mesnombre = "Febrero"; break;
	    case "03": $mesnombre = "Marzo"; break;
	    case "04": $mesnombre = "Abril"; break;
	    case "05": $mesnombre = "Mayo"; break;
	    case "06": $mesnombre = "Junio"; break;
	    case "07": $mesnombre = "Julio"; break;
	    case "08": $mesnombre = "Agosto"; break;
	    case "09": $mesnombre = "Septiembre"; break;
	    case "10": $mesnombre = "Octubre"; break;
	    case "11": $mesnombre = "Noviembre"; break;
	    case "12": $mesnombre = "Diciembre"; break;
    }
    
	switch(date("w"))
	{
		case '0': $dianombre = "Domingo"; break;
		case '1': $dianombre = "Lunes"; break;
		case '2': $dianombre = "Martes"; break;
		case '3': $dianombre = utf8_decode("Miércoles"); break;
		case '4': $dianombre = "Jueves"; break;
		case '5': $dianombre = "Viernes"; break;
		case '6': $dianombre = utf8_decode("Sábado"); break;
	}

    $pdf->Text(4,205, $dianombre . ", " . date("d") . " de $mesnombre de " . date("Y") . " " . date("H:i:s"));
	
    $pdf->AliasNbPages();
    $pdf->Text(260,205, "Página " . $pdf->PageNo() . " de {nb}");
	
   
    $pdf->SetFont('Arial','',6);
	$pdf->SetFillColor(220,220,220);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(220,220,220);
	$pdf->SetLineWidth(.2);
	
?>