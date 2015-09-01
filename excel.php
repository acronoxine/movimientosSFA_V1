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


$sql = "select nombre, ncuenta, sueldobase, compensacion, prima, aguinaldo, otrasper, isr, imss, otrasded,"; 
$sql .= " (sueldobase + compensacion + prima + aguinaldo + otrasper) - (isr + imss + otrasded) as neto from";
$sql .= " (select nombre, sueldobase,"; 
$sql .= " sum(case when cve_concepto = '114' then importe else 0 end) as compensacion,";
$sql .= " sum(case when cve_concepto = '119' then importe else 0 end) as prima,";
$sql .= " sum(case when cve_concepto = '115' then importe else 0 end) as aguinaldo,";
$sql .= " sum(case when cve_concepto not in ('114', '119', '115', '101') and tipo = 'P' then importe else 0 end) as otrasper,";
$sql .= " sum(case when cve_concepto = '251' then importe else 0 end) as isr,";
$sql .= " sum(case when cve_concepto = '274' then importe else 0 end) as imss,";
$sql .= " sum(case when cve_concepto not in ('251', '274') and tipo = 'D' then importe else 0 end) as otrasded,";
$sql .= " ncuenta";
$sql .= " from";
$sql .= " (select ncuenta, concat(e.paterno, ' ', e.materno, ' ', e.nombres) as nombre, e.sueldobase, p.cve_concepto, c.descripcion as concepto, p.tipo, p.importe"; 
$sql .= " from nominapago p";
$sql .= " left join nominaemp e on p.idnominaemp = e.idnominaemp";
$sql .= " left join cat_conceptos c on p.cve_concepto = c.clave";

if($_GET["ur"] == -1)
	$sql .= " where quincena = '$_GET[quincena]' and anio = '" . date("Y") . "' and e.activo = '1') x group by nombre, sueldobase) y";
else
	$sql .= " where quincena = '$_GET[quincena]' and anio = '" . date("Y") . "' and idarea = '$_GET[ur]' and e.activo = '1') x group by nombre, sueldobase) y";
	
$res = mysql_query($sql, $conexion);
?>

<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">

<link rel="stylesheet" type="text/css" href="css/estilos.css">

</head>

<body>

<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/PHPExcel_1.8.0_doc/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

// Add some data

$renglon = 1;
while($ren = mysql_fetch_array($res))
{
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A' . $renglon, utf8_encode($ren["nombre"]))
				->setCellValue('B' . $renglon, $ren["neto"])
				->setCellValueExplicit('C' . $renglon, $ren["ncuenta"], PHPExcel_Cell_DataType::TYPE_STRING);
				$renglon++;
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Layout');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Save Excel 2007 file
$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', "emisionesExcel/emision_$_GET[quincena]_" . date("Y") . ".xlsx"));
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;


// Save Excel 95 file
$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', "emisionesExcel/emision_$_GET[quincena]_" . date("Y") . ".xls"));
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;

?>

<center>
<br>
<div class="boton3" onClick="location.href='<? echo "emisionesExcel/emision_$_GET[quincena]_" . date("Y") . ".xlsx"; ?>';">
<? echo "emision_$_GET[quincena]_" . date("Y") . ".xlsx"; ?>
</div>
<br><br>
<div class="boton3" onClick="location.href='<? echo "emisionesExcel/emision_$_GET[quincena]_" . date("Y") . ".xls"; ?>';">
<? echo "emision_$_GET[quincena]_" . date("Y") . ".xls"; ?>
</div>
</center>
</body>
</html>

