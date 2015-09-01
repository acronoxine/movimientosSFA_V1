<?
        $numeros =    array("-", "uno", "dos", "tres", "cuatro", "cinco", "seis", "siete", "ocho", "nueve"); 
        $numerosX =   array("-", "un", "dos", "tres", "cuatro", "cinco", "seis", "siete", "ocho", "nueve"); 
        $numeros100 = array("-", "ciento", "doscientos", "trecientos", "cuatrocientos", "quinientos", "seicientos", "setecientos", "ochocientos", "novecientos"); 
        $numeros11 =  array("-", "once", "doce", "trece", "catorce", "quince", "dieciseis", "diecisiete", "dieciocho", "diecinueve"); 
        $numeros10 =  array("-", "-", "-", "treinta", "cuarenta", "cincuenta", "sesenta", "setenta", "ochenta", "noventa"); 

        function tresnumeros($n, $last) { 
            global $numeros100, $numeros10, $numeros11, $numeros, $numerosX; 
            if ($n == 100) return "cien "; 
            if ($n == 0) return "cero "; 
            $r = ""; 
            $cen = floor($n / 100); 
            $dec = floor(($n % 100) / 10); 
            $uni = $n % 10; 
            if ($cen > 0) $r .= $numeros100[$cen] . " "; 

            switch ($dec) { 
                case 0: $special = 0; break; 
                case 1: $special = 10; break; 
                case 2: $special = 20; break; 
                default: $r .= $numeros10[$dec] . " "; $special = 30; break; 
            } 
            if ($uni == 0) { 
                if ($special==30); 
                else if ($special==20) $r .= "veinte "; 
                else if ($special==10) $r .= "diez "; 
                else if ($special==0); 
            } else { 
                if ($special == 30 && !$last) $r .= "y " . $numerosX[$n%10] . " "; 
                else if ($special == 30) $r .= "y " . $numeros[$n%10] . " "; 
                else if ($special == 20) { 
                    if ($uni == 3) $r .= "veintitrés "; 
                    else if (!$last) $r .= "veinti" . $numerosX[$n%10] . " "; 
                    else $r .= "veinti" . $numeros[$n%10] . " "; 
                } else if ($special == 10) $r .= $numeros11[$n%10] . " "; 
                else if ($special == 0 && !$last) $r .= $numerosX[$n%10] . " "; 
                else if ($special == 0) $r .= $numeros[$n%10] . " "; 
            } 
            return $r; 
        } 

        function seisnumeros($n, $last) { 
            if ($n == 0) return "cero "; 
            $miles = floor($n / 1000); 
            $units = $n % 1000; 
            $r = ""; 
            if ($miles == 1) $r .= "mil "; 
            else if ($miles > 1) $r .= tresnumeros($miles, false) . "mil "; 
            if ($units > 0) $r .= tresnumeros($units, $last); 
            return $r; 
        } 

        function docenumeros($n) { 
            if ($n == 0) return "cero "; 
            $millo = floor($n / 1000000); 
            $units = $n % 1000000; 
            $r = ""; 
            if ($millo == 1) $r .= "un millón "; 
            else if ($millo > 1) $r .= seisnumeros($millo, false) . "millones "; 
            if ($units > 0) $r .= seisnumeros($units, true); 
            return $r; 
        }
?>