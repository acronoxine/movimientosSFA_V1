<?php
$texto=texto_numero(14000.73);
echo $texto;
function texto_numero($cantidad){
$cantidad=(string)$cantidad;
$cantidad_p=explode(".",$cantidad);
$texto=letra_prin($cantidad_p[0]);
if($cantidad_p[1]!=""){
	if($cantidad_p[1]<9)
		$cantidad_p[1].="0";
	$texto2=letra_prin($cantidad_p[1]);
	$texto.=" PUNTO ".$texto2;
}
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
			$texto.=" CIENTOS ";
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
					$letra.="UNO "; 
			break;
			case 2: $letra.="DOS "; break;
			case 3: $letra.="TRES "; break;
			case 4: $letra.="CUATRO "; break;
			case 5: 
				if($longitud_1==3)
					$letra.="";
				else
					$letra.="CINCO "; 
				break;
			case 6: $letra.="SEIS "; break;
			case 7: 
				if($longitud_1==3)
					$letra.="SETE";
				else
					$letra.="SIETE "; 
			break;
			case 8: $letra.="OCHO "; break;
			case 9: $letra.="NUEVE "; break;
			case 10: $letra.="DIEZ "; break;
			case 11: $letra.="ONCE "; break;
			case 12: $letra.="DOCE "; break;
			case 13: $letra.="TRECE "; break;
			case 14: $letra.="CATORCE "; break;
			case 15: $letra.="QUINCE "; break;
			case 16: $letra.="DIECISÉIS "; break;
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