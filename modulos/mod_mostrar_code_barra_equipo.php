<?php 
require_once('common.php'); checkUser(); 
date_default_timezone_set($_SESSION['zona_horario']);
			
			$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
			$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 
			 
			
				$sql="SELECT tx_serial,tx_tipo_producto, tx_marca, tx_modelo, TO_CHAR(fe_ingreso,'DD/MM/YYYY') AS fe_ingreso, fe_vencimiento, tx_nu_motivo FROM vie_tbl_equipos WHERE id_producto=".$_GET['id_producto'];
				$res=abredatabase(g_BaseDatos,$sql);
				$row=dregistro($res);
				
			
			$html='
			<style>
			.barcode {
 padding: 1.5mm;
 margin: 0;
 vertical-align: top;
 color: #000000;
}</style>
<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; "><tr>

<td width="100%" align="center" style="font-weight: bold;  vertical-align:middle; font-size:1.5em; border:1px solid #000;" >
<barcode code="'.$row['tx_serial'].'" type="C39"  class="barcode" 
text="1" /> <br>

S/N: '.$row['tx_serial'].' <br>
O.C: '.$row['tx_nu_motivo'].' <br>
F.I: '.$row['fe_ingreso'].' <br>
GARANTIA: '.$row['fe_vencimiento'].' <br>
TIPO: '.$row['tx_tipo_producto'].' <br>
MARCA: '.$row['tx_marca'].' <br>
MODELO: '.$row['tx_modelo'].'
</td>

<td width="58%" style="text-align: right; "></td>

</tr></table>
			
				';
				
				cierradatabase(); 
				

include("../lib/mdpdf/mpdf.php");


//$mpdf = new mPDF();

$mpdf = new mPDF(   'utf-8',    // mode - default ''
                [100, 50],    // format - A4, for example, default ''
                10,     // font size - default 0
                '',    // default font family
                10,    // margin_left
                0,    // margin right
                10,     // margin top
                0,    // margin bottom
                0,     // margin header
                0,     // margin footer
                'P' );  // L - landscape, P - portrait

// Define the Header/Footer before writing anything so they appear on the first page


$mpdf->WriteHTML($html);

$mpdf->Output();

?>







