<?php 
require_once('common.php'); checkUser(); 
date_default_timezone_set($_SESSION['zona_horario']);
			
			$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
			$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 
				$sql2="SELECT tx_direccion,tx_descripcion, tx_ruc, tx_empresa FROM mod_tienda b, mod_empresa c WHERE   c.id_empresa=b.id_empresa and b.id_tienda=1";
				$res2=abredatabase(g_BaseDatos,$sql2);
				$row2=dregistro($res2);
				
			
				$sql="SELECT tx_direccion,tx_guia,tx_descripcion, tx_ruc, tx_empresa, a.tx_marca, tx_ticket,  tx_proposito, (SELECT (tx_descripcion || ' ' || tx_url || ' ' || tx_observacion) from  mod_responsable_destino WHERE id_tienda=a.id_responsable_destino LIMIT 1) as  tx_responsable, tx_representacion, (d.tx_responsable) as tecnico, fe_movimiento FROM mod_movimiento a LEFT JOIN mod_responsable d ON a.id_responsable_enviado=d.id_responsable, mod_tienda b, mod_empresa c  WHERE a.tx_guia='".$_GET['guia']."'  and a.id_tienda=b.id_tienda and c.id_empresa=b.id_empresa ";
				$res=abredatabase(g_BaseDatos,$sql);
				$row=dregistro($res);
				$proposito=$row['tx_proposito'];
				$guia=$row['tx_guia'];
				$ticket=$row['tx_ticket'];
				$responsable=$row['tx_descripcion'].'<br>'.$row['tx_responsable'];
				$tecnico=$row['tx_representacion']."<br>".$row['tecnico'];
				$date =  $row['fe_movimiento'];
				//$date=DateTime::createFromFormat("l dS F Y", $row['fe_movimiento']);
				//$date = $date->format('d/m/Y');
			$html='
			<style>
			.barcode {
 padding: 1.5mm;
 margin: 0;
 vertical-align: top;
 color: #000000;
}</style>

			<div id="contenido" class="col-lg-12" >
				
				<table width="100%" border="0" style="font-family:ariel; font-size: 10pt; color: #000000; " >
					<tr>
					  <td width="9%" height="108" align="center">&nbsp;</td>
					  <td width="9%" align="center">&nbsp;</td>
					  <td width="9%" align="center">&nbsp;</td>
					  <td width="9%" align="center">&nbsp;</td>
					  <td width="9%" align="center">&nbsp;</td>
					  <td width="27%" align="center">&nbsp;</td>
					  <td colspan="2" align="center"><div align="left"></div></td>
  </tr>
					<tr>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
					  <td width="6%" align="center">&nbsp;</td>
					  <td width="22%" align="center"></td>
  </tr>
					<tr>
					
					  <td height="100" align="left" colspan="5"  style="vertical-align: bottom;">'.$dias[date('w', strtotime($date))].", ".date('d', strtotime($date))." de ".$meses[(date('m', strtotime($date)))-1]. " de ".date('Y', strtotime($date)).'</td>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
  </tr>
					<tr>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
  </tr>
					<tr >
					  
					  <td colspan="5" align="left" height="124">'.strtoupper($row2['tx_descripcion']).'<br>
				'.strtoupper($row2['tx_ruc']).'<br>'.strtoupper($row2['tx_direccion']).'</td>
					  <td colspan="3" align="center">
				'.strtoupper($responsable).' <br>
				'.strtoupper($row['tx_direccion']).' </td>
  </tr>
					<tr >
					   <td height="40" align="center">&nbsp;</td>
					  <td colspan="4" align="left">'.strtoupper($row['tx_empresa']).'<br>
				'.strtoupper($row['tx_ruc']).'</td>
					  <td colspan="3" align="center"> </td>
  </tr>
</table>
			</div>
			
			
			<div id="contenido" class="col-lg-12" style="margin-top:60px;  ">
				<table width="100%" border="0" style=" font-size: 10pt; color: #000000;" >
					<tr>
						<td align="center" style="font-family:ariel; font-size: 12px"> # </td>
						<td align="center" style="font-family:ariel; font-size: 12px"> TIPO </td>
						<td align="center" style="font-family:ariel; font-size: 12px"> MARCA </td>
						<td align="center" style="font-family:ariel; font-size: 12px"> MODELO </td>
						<td align="center" style="font-family:ariel; font-size: 12px"> SERIAL </td>
						<td align="center" style="font-family:ariel; font-size: 12px"> ACCESORIOS </td>
						<td align="center" style="font-family:ariel; font-size: 12px"> PLACA </td>
						<td align="center" style="font-family:ariel; font-size: 12px"> CANTIDAD </td>
					</tr>
			';
				$c=0;
				$sql="SELECT nx_cantidad,tx_nombre_tipo, tx_marca, tx_modelo, tx_serial, tx_accesorios, tx_ngr FROM vie_tbl_movimiento a WHERE a.tx_guia='".$_GET['guia']."' and id_tipo_movimiento=2";
				$res=abredatabase(g_BaseDatos,$sql);
				while($row=dregistro($res)){
			        $c=$c+1;
					$html.='	<tr>
						<td align="center" style="font-family:ariel; font-size: 12px">'.$c.'</td>
						<td align="center" style="font-family:ariel; font-size: 12px">'.strtoupper($row['tx_nombre_tipo']).'</td>
						<td align="center" style="font-family:ariel; font-size: 12px">'.strtoupper($row['tx_marca']).'</td>
						<td align="center" style="font-family:ariel; font-size: 12px">'.strtoupper($row['tx_modelo']).'</td>
						<td align="center" style="font-family:ariel; font-size: 12px">'.strtoupper($row['tx_serial']).'</td>
						<td align="left" style="font-family:ariel; font-size: 12px">'.strtoupper($row['tx_accesorios']).'</td>
						<td align="center" style="font-family:ariel; font-size: 12px">'.strtoupper($row['tx_ngr']).'</td>
						<td align="center" style="font-family:ariel; font-size: 12px">'.$row['nx_cantidad'].'</td>
					</tr>';
					
				} cierradatabase();
				
				$html.='
			

				</table>
				</div>
				
				';
							

include("../lib/mdpdf/mpdf.php");

$mpdf = new mPDF(   '',    // mode - default ''
                'letter',    // format - A4, for example, default ''
                0,     // font size - default 0
                '',    // default font family
                15,    // margin_left
                15,    // margin right
                10,     // margin top
                60,    // margin bottom
                6,     // margin header
                30,     // margin footer
                'L'  );  // L - landscape, P - portrait

$mpdf->SetHTMLFooter('

<table width="100%" style="vertical-align: bottom;  color: #000000; "><tr>

<td width="33%"><barcode code="'.$guia.'" type="c39" text="1" class="barcode" height="0.66"
text="1" /></td>

<td width="33%" align="left" >
TICKET: '.$ticket.' <br>
ENTREGADO A: '.strtoupper($tecnico).'
</td>

<td width="33%" style="text-align: right; "></td>

</tr></table>

');

$mpdf->WriteHTML($html);

$mpdf->Output();

?>







