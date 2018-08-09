<?php 
require_once('common.php'); checkUser(); 
date_default_timezone_set($_SESSION['zona_horario']);
			
			$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
			$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 
				//Busca Parametros generales del sistema
				$sql="SELECT tx_nombre_empresa,tx_logo FROM cfg_configuracion_general";
				$res=abredatabase(g_BaseDatos,$sql);
				$row=dregistro($res);
				$nombre_empresa=$row['tx_nombre_empresa'];
				$logo_empresa=$row['tx_logo'];
				cierradatabase();	
	
	
				$sql2="SELECT tx_direccion,tx_descripcion, tx_ruc, tx_empresa FROM mod_tienda b, mod_empresa c WHERE   c.id_empresa=b.id_empresa and b.id_tienda=1";
				$res2=abredatabase(g_BaseDatos,$sql2);
				$row2=dregistro($res2);
				
			
				$sql="SELECT tx_direccion,tx_guia,tx_descripcion, tx_ruc, tx_empresa, tx_ticket,  tx_proposito, (SELECT (tx_descripcion) from  vie_tbl_tiendas_destino WHERE id_tienda=a.id_responsable_destino LIMIT 1) as tx_responsable, tx_representacion, (d.tx_responsable) as tecnico, fe_movimiento FROM mod_movimiento a, mod_tienda b, mod_empresa c, mod_responsable d WHERE a.tx_guia='".$_GET['guia']."' and id_tipo_guia=96  and a.id_tienda=b.id_tienda and c.id_empresa=b.id_empresa AND a.id_responsable_enviado=d.id_responsable";
				$res=abredatabase(g_BaseDatos,$sql);
				$row=dregistro($res);
				$proposito=$row['tx_proposito'];
				$guia=$row['tx_guia'];
				$ticket=$row['tx_ticket'];
				$responsable=$row['tx_responsable'];
				$tecnico=$row['tx_representacion']." - ".$row['tecnico'];
				$date = new DateTime($row['fe_movimiento']);
			
			$cabecera='
			

			<div id="contenido" class="col-lg-12" >
				<table width="100%" border="0" style="font-family:ariel; font-size: 10pt; color: #000000; " >

					<tr>
					  <td  align="left"><img src="../img/logos/logo_sistema.png" width="200px" height="80px"></td>
					   <td colspan="7" align="left">'.$nombre_empresa.'<br>CALLE CAMINO REAL 1801 INT. A4 - Z.I. SAN PEDRITO<br>R.U.C. N°20545697697<br><strong style="font-size:14px">GUIA DE REMISIÓN INTERNA: <span style="text-decoration: underline; font-style: italic; ">'.$_GET['guia'].'</span></strong></td>
					 
					</tr>
					<tr>
					
					  <td height="10" align="left" colspan="5"  style="vertical-align: bottom;">'.$dias[$date->format('w')].", ".$date->format('d')." de ".$meses[$date->format('m')-1]. " de ".$date->format('Y').'</td>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
					  <td align="center">&nbsp;</td>
  </tr>
					
					<tr >
					  
					  <td colspan="5" align="left" height="60">'.strtoupper($row['tx_empresa']).'<br>
				'.strtoupper($row['tx_ruc']).'</td>
					  <td colspan="3" align="center">
					  '.strtoupper($row['tx_descripcion']).' <br>
				'.strtoupper($responsable).' <br>
				'.strtoupper($row['tx_direccion']).' </td>
  </tr>
					
</table>
			</div>
';
			
			
$html.='			<div id="contenido" class="col-lg-12" style="margin-top:100px;  ">
				<table width="100%" border="0" style=" font-size: 10pt; color: #000000;" >
				 <thead>
					<tr>
						<td align="center" width="5%" style="font-weight:bold"> # </td>
						<td align="center" width="20%" style="font-weight:bold"> TIPO </td>
						<td align="center" width="5%" style="font-weight:bold"> MARCA </td>
						<td align="center" width="15%" style="font-weight:bold"> MODELO </td>
						<td align="center" width="10%" style="font-weight:bold"> SERIAL </td>
						<td align="center" width="25%" style="font-weight:bold"> ACCESORIOS </td>
						<td align="center" width="5%" style="font-weight:bold"> PLACA NGR </td>
						<td align="center" width="5%" style="font-weight:bold"> PLACA TI </td>
						<td align="center" width="10%" style="font-weight:bold"> CANTIDAD </td>
					</tr>
				</thead>
				<tbody>
			';
				$c=0;
				$sql_h="SELECT nx_cantidad,tx_nombre_tipo, tx_marca, tx_modelo, tx_serial, tx_accesorios, tx_ngr, (SELECT tx_placati FROM mod_producto WHERE id_producto=a.id_producto) as placa_ti FROM vie_tbl_movimiento a WHERE a.tx_guia='".$_GET['guia']."' and id_tipo_movimiento=2";
				$res_h=abredatabase(g_BaseDatos,$sql_h);
				while($row_h=dregistro($res_h)){
			        $c=$c+1;
					
					$html.='	<tr >
						<td align="center" style="font-size:10px">'.$c.'</td>
						<td align="center" style="font-size:10px">'.strtoupper($row_h['tx_nombre_tipo']).'</td>
						<td align="center" style="font-size:10px">'.strtoupper($row_h['tx_marca']).'</td>
						<td align="center" style="font-size:10px">'.strtoupper($row_h['tx_modelo']).'</td>
						<td align="center" style="font-size:10px">'.strtoupper($row_h['tx_serial']).'</td>
						<td align="left" style="font-size:10px">'.strtoupper($row_h['tx_accesorios']).'</td>
						<td align="center" style="font-size:10px">'.strtoupper($row_h['tx_ngr']).'</td>
						<td align="center" style="font-size:10px">'.strtoupper($row_h['placa_ti']).'</td>
						<td align="center" style="font-size:10px">'.$row_h['nx_cantidad'].'</td>
					</tr>';
					}
				
				
				$html.='
			
				</tbody>
				</table>
				</div>
				
				';
				$pie.='<table width="100%" style="vertical-align: bottom;  color: #000000; margin-top:30px ">
					<tr>
						<td width="10%">TICKET: '.$ticket.'</td>
						<td width="90%" align="left" >
							ENTREGADO A: '.strtoupper($tecnico).'
						</td>
						</tr></table>
				';
			

include("../lib/mdpdf/mpdf.php");

//$mpdf = new mPDF();

$mpdf = new mPDF(   '',    // mode - default ''
                'letter',    // format - A4, for example, default ''
                0,     // font size - default 0
                'ariel',    // default font family
                5,    // margin_left
                5,    // margin right
                55,     // margin top
                30,    // margin bottom
                6,     // margin header
                20,     // margin footer
                'L'  );  // L - landscape, P - portrait

// Define the Header/Footer before writing anything so they appear on the first page

$mpdf->SetHTMLHeader($cabecera);
$mpdf->SetHTMLFooter($pie);
$mpdf->WriteHTML($html);

$mpdf->Output();

?>







