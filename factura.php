<?
session_start();

include("conecta.php");
include("numeros.php");

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

$consulta = "Select concat(cast(fecha as char), 'T', cast(hora as char)) as fechaiso, rsocial, direccion, calle, colonia, numero, extint, cp, ciudad, estado, cast(concat(lpad(day(fecha), 2, '0'), '/', lpad(month(fecha), 2, '0'), '/', year(fecha)) as char) as fecha, serie, folio, rfc from facturas where idfactura = $_GET[idfactura]";
$resultado = mysql_query($consulta, $link);
$lafila=mysql_fetch_array($resultado);

    $pdf=new FPDF();
    $pdf->Open();
    $pdf->AddPage();

    $pdf->Ln(4);
    
    $pdf->Image("logo.jpg", 150, 5);
    
    $pdf->Image("logomagua1.jpg", 17, 85);
    
    
    $sql = "Select rfc, curp, nombre, calle, num as numero, extint, colonia, cp, ciudad, estado, telefono1 from cat_almacenes where idalmacen = '$_SESSION[m_idalmacen]'";
    $res_almacen = mysql_query($sql, $link);
    $almacen = mysql_fetch_array($res_almacen);
    
    $f_rfc = $almacen["rfc"];
    $f_curp = $almacen["curp"];
    $f_nombre = $almacen["nombre"];
    $f_calle = $almacen["calle"];
    $f_numero = $almacen["numero"];
    $f_interior = $almacen["extint"];
    $f_colonia = $almacen["colonia"];
    $f_cp = $almacen["cp"];
    $f_ciudad = $almacen["ciudad"];
    $f_estado = $almacen["estado"];
    $f_telefono = $almacen["telefono1"];
    
    mysql_free_result($res_almacen);
    
	$pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',25);
    $pdf->Cell(140,1,$f_nombre,'',0,'C',1);
    $pdf->Ln(6);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(140,4,"Rfc: $f_rfc",'',0,'C',1);
    $pdf->Ln(5);
    $pdf->Cell(140,4,"$f_calle #$f_numero $f_interior Col. $f_colonia",'',0,'C',1);
    $pdf->Ln(5);
    $pdf->Cell(140,4,"$f_ciudad, $f_estado",'',0,'C',1);
    $pdf->Ln(5);
    $pdf->Cell(140,4,"C.p. $f_cp Tel. $f_telefono",'',0,'C',1);
    $pdf->Ln(5);
    
	$pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',10);
	
    $pdf->Cell(134);
	$pdf->Cell(30,4,"Fecha",1,0,'C',1);	
	$pdf->Cell(30,4,"Factura",1,0,'C',1);
	$pdf->Ln(4);
	
	$pdf->Cell(134);
	$pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',10);
	
	$fecha = $lafila["fecha"];
	
	$pdf->Cell(30,7,$lafila["fecha"],1,0,'C',1);	
	$pdf->Cell(30,7,$lafila["serie"] . str_pad($lafila["folio"], 10, "0", STR_PAD_LEFT),1,0,'C',1);		
    
    $pdf->Ln(4);		
    
    $pdf->Ln(10);					
	
	$pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',10);
	
    $pdf->Cell(40,65,'');
	$pdf->SetX(10);	
	
	$pdf->Rect(11, 53, 193, 23);

	$pdf->Cell(8);
    $pdf->Cell(19,4,"Nombre: ",'',0,'L',1);
    $pdf->SetFillColor(220,220,220);
    $pdf->Cell(156,4,"$lafila[rsocial]",'',0,'L',1);
    $pdf->SetFillColor(255,255,255);
    $pdf->Ln(5);
	
    $pdf->Cell(8);
    $pdf->Cell(22,4,"Domicilio: ",'',0,'L',1);
    $pdf->SetFillColor(220,220,220);
    $pdf->Cell(153,4,"$lafila[direccion]",'',0,'L',1);
    $pdf->SetFillColor(255,255,255);
    $pdf->Ln(5);		
	
    $pdf->Cell(8);
    $pdf->Cell(17,4,"Ciudad: ",'',0,'L',1);
    $pdf->SetFillColor(220,220,220);
    $pdf->Cell(80,4,"$lafila[ciudad], $lafila[estado]",'',0,'L',1);
    
    $pdf->SetFillColor(255,255,255);
    $pdf->Cell(10,4,"C.P.: ",'',0,'L',1);
    $pdf->SetFillColor(220,220,220);
    $pdf->Cell(15,4,"$lafila[cp]",'',0,'L',1);
    
    $pdf->SetFillColor(255,255,255);
    $pdf->Cell(13,4,"R.F.C.: ",'',0,'L',1);
    $pdf->SetFillColor(220,220,220);
    $pdf->Cell(40,4,"$lafila[rfc]",'',0,'L',1);
    
    $pdf->SetFillColor(255,255,255);
	
	$pdf->Ln(10);		
	$pdf->Cell(1);
	
	//***************** FACTURA ELECTRONICA ***********************************
	
    // set User vars
    $password = 'a0123456789';
    $cer_path = 'aaa010101aaa_CSD_10.cer';
    $key_path = 'aaa010101aaa_CSD_10.key';
    
    // set CDF array
    $array['version'] = '2.0';
    $array['serie'] = $lafila["serie"]; //'AAAAAAAAAA'
    $array['noAprobacion'] = "00000000000000"; 
    $array['anoAprobacion'] = "2010"; 
    $array['folio'] = str_pad($lafila["folio"], 20, "0", STR_PAD_LEFT);
    $array['fecha'] = $lafila["fechaiso"]; //'2010-11-25T16:30:00' // ISO 8601 aaaa-mm-ddThh:mm:ss
    $array['formaDePago'] = 'Una sola exhibicion'; // Pago en una sola exibiciÃ³n | Parcialidad 1 de X.
    $array['tipoDeComprobante'] = 'ingreso'; // ingreso | egreso | traslado
    
    $array['Emisor']['rfc'] = $f_rfc; //'FDTL000000XXX'
    $array['Emisor']['nombre'] = $f_nombre; //'Nombre del Proveedor'
    $array['DomicilioFiscal']['calle'] = $f_calle . " " . $f_interior; //'Calle del Proveedor'
    $array['DomicilioFiscal']['noExterior'] = $f_numero; //100
    $array['DomicilioFiscal']['noInterior'] = $f_interior; //'A'
    $array['DomicilioFiscal']['colonia'] = $f_colonia; //'De los proveedores'
    $array['DomicilioFiscal']['municipio'] = $f_ciudad; //'Monterrey'
    $array['DomicilioFiscal']['estado'] = $f_estado; //'Nuevo Leon'
    $array['DomicilioFiscal']['pais'] = 'Mexico'; //'Mexico'
    $array['DomicilioFiscal']['codigoPostal'] = $f_cp; //64000
    
    //$array['ExpedidoEn'] = $array['DomicilioFiscal'];
    
    $array['Receptor']['rfc'] = $lafila["rfc"]; //'AAAA000000XXX'
    $array['Receptor']['nombre'] = $lafila["rsocial"]; //'Nombre del Cliente'
    $array['Domicilio']['calle'] = $lafila["rfc"]; //'Esperanza'
    $array['Domicilio']['noExterior'] = $lafila["numero"] . " " . $lafila["extint"]; //100
    $array['Domicilio']['noInterior'] = $lafila["extint"]; //'A'
    $array['Domicilio']['colonia'] = $lafila["colonia"]; //'Del Pueblo'
    $array['Domicilio']['municipio'] = $lafila["ciudad"]; //'Tampico'
    $array['Domicilio']['estado'] = $lafila["estado"]; //'Tamaulipas'
    $array['Domicilio']['pais'] = 'Mexico'; //'Mexico'
    $array['Domicilio']['codigoPostal'] = $lafila["cp"]; //89000
	
	
	//***************** FIN DE FACTURA ELECTRONICA ****************************
	
	$pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',10);
	
    $pdf->Cell(25,7,"Código",1,0,'C',1);
	$pdf->Cell(100,7,"Descripción",1,0,'C',1);
	$pdf->Cell(18,7,"Cantidad",1,0,'C',1);	
	$pdf->Cell(23,7,"Precio",1,0,'C',1);
	//$pdf->Cell(15,7,"% Desc.",1,0,'C',1);
	$pdf->Cell(27,7,"Importe",1,0,'C',1);
	$pdf->Ln(4);
			
			
	$pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',9);

	$consulta2 = "Select v.cve_articulo, a.descripcion, v.unidades, v.ivauni, v.preciouni, v.descuentouni, v.importe, u.descripcion as unidad from facturas f";
	$consulta2 .= " inner join ventas_det v on f.idventa = v.idventa";
	$consulta2 .= " left join cat_precios a on a.id = v.idarticulo";
	$consulta2 .= " left join cat_unidades u on a.idunidad = u.idunidad";
	$consulta2 .= " where idfactura = '$_GET[idfactura]'";
 
    $resultado2 = mysql_query($consulta2, $link);

    $importe=0;
    $contador=1;
    $iva = 0;
    $subtotal = 0;
    $total = 0;
    
    $pdf->Cell(1);
    $pdf->Cell(25,4,"",'LR',0,'C');
    $pdf->Cell(100,4,"",'LR',0,'L');
	$pdf->Cell(18,4,"",'LR',0,'C');	
	$pdf->Cell(23,4,"",'LR',0,'R');
   // $pdf->Cell(15,4,"",'LR',0,'C');  
	$pdf->Cell(27,4,"",'LR',0,'R'); 
        
    $pdf->Ln(4);
    
    
    $i = 0;
    while ($lafila2=mysql_fetch_array($resultado2)) 
	{ 

	     $pdf->Cell(1);
 
		     $pdf->Cell(25,4,$lafila2["cve_articulo"],'LR',0,'C');
	     
		     $acotado = substr($lafila2["descripcion"], 0, 55);
		     $pdf->Cell(100,4,$acotado,'LR',0,'L');
			   
			 $cantidad=$lafila2["unidades"];
			 $pdf->Cell(18,4,$cantidad,'LR',0,'C');	
			   
			 $precio2= number_format($lafila2["preciouni"] - $lafila2["ivauni"],2,".",",");
			 $pdf->Cell(23,4,$precio2,'LR',0,'R');
			 
	        // $pdf->Cell(15,4,$lafila2["descuentouni"] . " %",'LR',0,'C');  
	         
             $importe2= number_format(($lafila2["preciouni"] - $lafila2["ivauni"]) * $lafila2["unidades"],2,".",",");						  
			 $pdf->Cell(27,4,$importe2,'LR',0,'R');
			  
			 $contador++;
			 $iva += $lafila2["ivauni"] * $lafila2["unidades"];
			 $subtotal += ($lafila2["preciouni"] - $lafila2["ivauni"]) * $lafila2["unidades"];
			 $total += $lafila2["importe"];
			 	  
             $pdf->Ln(4);

             //******************** FACTURA ELECTRONICA *****************************
             
             $array['Concepto'][$i]['cantidad'] = $lafila2["unidades"];
             $array['Concepto'][$i]['unidad'] = $lafila2["unidad"];
             $array['Concepto'][$i]['descripcion'] = $lafila2["descripcion"];
             $array['Concepto'][$i]['valorUnitario'] = $lafila2["preciouni"] - $lafila2["ivauni"];
             $array['Concepto'][$i]['importe'] = ($lafila2["preciouni"] - $lafila2["ivauni"]) * $lafila2["unidades"];
             
             $i++;
             
             //******************* FIN DE FACTURA ELECTRONICA ***********************

	} 

	while ($contador<30)
	{
	  $pdf->Cell(1);
      $pdf->Cell(25,4,"",'LR',0,'C');
      $pdf->Cell(100,4,"",'LR',0,'C');
	  $pdf->Cell(18,4,"",'LR',0,'C');	
	  $pdf->Cell(23,4,"",'LR',0,'C');
	  //$pdf->Cell(15,4,"",'LR',0,'C');
	  $pdf->Cell(27,4,"",'LR',0,'C');
	  $pdf->Ln(4);	
	  $contador=$contador +1;
	}	

	$pdf->Cell(1);
    $pdf->Cell(25,4,"",'LRB',0,'C');
    $pdf->Cell(100,4,"",'LRB',0,'C');
	$pdf->Cell(18,4,"",'LRB',0,'C');	
	$pdf->Cell(23,4,"",'LRB',0,'C');
	//$pdf->Cell(15,4,"",'LRB',0,'C');
	$pdf->Cell(27,4,"",'LRB',0,'C');
	$pdf->Ln(4);	
	
	
	$miimporte = explode(".", $total);	
	
	$dinero = $miimporte[0];
	
	if(count($miimporte) > 1)
	    $centavos = $miimporte[1];
	else
	    $centavos = "00";
	
	if(strlen($centavos) == 1)
	    $centavos .= "0";
	
	$cantidad_letra = strtoupper(trim(docenumeros($dinero))) . " PESOS $centavos/100 M.N.";
	
	$cantidad_letra = wordwrap($cantidad_letra, 60, "|");
	$cantidad_letra = explode("|", $cantidad_letra);

	$pdf->Ln(7);	
	$pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',10);
		
	$pdf->Cell(35,4,"Cantidad con letra: ","",0,'L');
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(95,4,"",0,0,'L',1);
    $pdf->Cell(4);
    
	$pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',10);
	
    $pdf->Cell(30,4,"Sub-Total $",1,0,'R',1);
    
    $pdf->SetFillColor(255,255,255);
    
	$importe5= number_format($subtotal,2,".",",");	
    $pdf->Cell(30,4,"$importe5",1,0,'R',1);    
    
    $pdf->Ln(4);
	$pdf->SetFillColor(220,220,220);
	$pdf->Cell(130,4,"$cantidad_letra[0]",0,0,'L',1);
    $pdf->Cell(4);
    
    $pdf->SetFillColor(200,200,200);
    
	$pdf->Cell(30,4,"I.V.A. $",1,0,'R',1);	
	
	$pdf->SetFillColor(255,255,255);
	
	$impo= number_format($iva,2,".",",");	
	$pdf->Cell(30,4,"$impo",1,0,'R',1);		
	
	$pdf->Ln(4);
	$pdf->SetFillColor(220,220,220);
	
	if(count($cantidad_letra) > 1)
	    $pdf->Cell(130,4,"$cantidad_letra[1]",0,0,'L',1);
	else
	    $pdf->Cell(130,4,"",0,0,'L',1);
	    
    $pdf->Cell(4);
	
	$pdf->SetFillColor(200,200,200);
	
	$pdf->Cell(30,4,"Total $",1,0,'R',1);
	
	$pdf->SetFillColor(255,255,255);
	
    $total=sprintf("%01.2f", $total);
	$total2= number_format($total,2,".",",");
	$pdf->Cell(30,4,"$total2",1,0,'R',1);	
	
	$pdf->Ln(4);

	//***************************** FACTURA ELECTRONICA *******************************
		
    //$array['Retencion'][0]['impuesto'] = 'IVA';
    //$array['Retencion'][0]['importe'] = 112;
    //$array['Retencion'][1]['impuesto'] = 'ISR';
    //$array['Retencion'][1]['importe'] = 70;
    
    $array['Traslado'][0]['impuesto'] = 'IVA';
    $array['Traslado'][0]['tasa'] = '16.00';
    $array['Traslado'][0]['importe'] = $subtotal;
    
    //$array['descuento'] = '';
    
    
    $array['total'] = $total;
    
    
    // calls SimpleCDF methods
    require_once './factura/SimpleCFD.php';
    
    $array['sello'] = SimpleCFD::signData( SimpleCFD::getPrivateKey( $key_path, $password ),
                                           SimpleCFD::getOriginalString( $array ) );
    $array['noCertificado'] = SimpleCFD::getSerialFromCertificate( $cer_path );
    $array['certificado'] = SimpleCFD::getCertificate( $cer_path, false );
    $array['cadenaOriginal'] = SimpleCFD::getOriginalString( $array );
    
    // return the CDF as XML
    $facturae = SimpleCFD::getXML( $array );
    
    $cadenaoriginal = $array['cadenaOriginal'];
    $sello = $array['sello'];
       
    //*************************** FIN DE FACTURA ELECTRONICA *************************************
    
    $pdf->SetFont('Arial','B',8);
    
    $cadenaoriginal = wordwrap($cadenaoriginal, 100, "{NuevaLinea}", 1);
    $cadenaoriginal = explode("{NuevaLinea}", $cadenaoriginal);
    $trozos = count($cadenaoriginal);
    
    $pdf->Ln(4);
    $pdf->Cell(50,4,"CADENA ORIGINAL: ",0,0,'L',1);	
	$pdf->Ln(4);
	
	for($i = 0; $i < $trozos; $i++)
	{
	    $pdf->Cell(180,4,$cadenaoriginal[$i],0,0,'L',1);	
	    $pdf->Ln(4);
    }
    
    $sello = wordwrap($sello, 100, "{NuevaLinea}", 1);
    $sello = explode("{NuevaLinea}", $sello);
    $trozos = count($sello);
	
    $pdf->Ln(4);    
	$pdf->Cell(50,4,"SELLO DIGITAL: ",0,0,'L',1);	
	$pdf->Ln(4);
	
	for($i = 0; $i < $trozos; $i++)
	{
	    $pdf->Cell(180,4,$sello[$i],0,0,'L',1);	
	    $pdf->Ln(4);
    }
    
    
    $sql = "Update facturas set xml = '$facturae' where  idfactura = $_GET[idfactura]";
    mysql_query($sql, $link);

$pdf->Output();
?>
