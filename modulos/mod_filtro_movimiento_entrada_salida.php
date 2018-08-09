<?php require_once('common.php'); checkUser();
date_default_timezone_set($_SESSION['zona_horario']);

//Busca Parametros generales del sistema
	$sql="SELECT tx_nombre_empresa,tx_logo FROM cfg_configuracion_general";
	$res=abredatabase(g_BaseDatos,$sql);
	$row=dregistro($res);
	$nombre_empresa=$row['tx_nombre_empresa'];
	$logo_empresa=$row['tx_logo'];
	cierradatabase();	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Apices | Inventario</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	 <?php include('libreriaCSS.php');  ?>
	 <style>
	"," :after, *:before{
		margin: 0;
		padding: 0;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;		
	}
	#contenedor_carga{
			background-color: rgba(255,255,255, 0.95);
			height: 100%;
			width: 100%;
			position: fixed;
			-webkit-transition: all 1s ease;
			-o-transition: all 1s ease;
			transition: all 1s ease;
			z-index: 10000; 
	}
	#carga{
		border: 15px solid #ccc;
		border-top-color: #006699;
		border-top-style: groove;
		height: 100px;
		width: 100px;
		border-radius: 100%;
		
		position: absolute;
		top:0;
		left:0;
		right:0;
		bottom:0;
		margin:auto;
		-webkit-animation: girar 1.5s linear infinite;
		-o-animation: girar 1.5s linear infinite;
		animation: girar 1.5s linear infinite;
		
	}
	
	@keyframes girar{
			from { transform: rotate(0deg); }
			to { transform: rotate(360deg); }
	}
	    
	// input {
		// text-transform: uppercase;
	// }
	
  </style>
</head>

<body>
	
<div class="container-fluid">
	<div ><img src="repositorio/logos_cintillos/<?php echo $row['tx_logo']; ?>"  ></div>
	<div class="col-xs-12" align="LEFT" style="font-size:14px; margin-bottom:10px "><h3>REPORTE DINAMICO</h3></div>
	<div class="col-xs-12" align="LEFT" style="margin-top:-20px" ><hr></div>
	<div class="col-xs-12" align="LEFT" style="font-size:14px; margin-bottom:10px; font-weight:bold ">
		
	</div>
	<?php 
		$where="";
		$c=0;
		$tipo_reporte=0;
		
		foreach ($_POST['array'] as $clave => $valor) {
			if ($valor['id']=="tipo_reporte") { echo $tipo_reporte=$valor['elemento'];   }else{
			if ($c==0) {
				$where .= " WHERE {$valor['id']}"." = "."'{$valor['elemento']}'";
				$c=1;
			}else{
				$where .= " AND {$valor['id']}"." = "."'{$valor['elemento']}'";
			}
	}}
	?>
	<div class="col-xs-12" >
	<table id="imprimible"  name="imprimible" class="table table-bordered table-striped" style="font-size:12px; color:#000; width:100% " >
	  <tr style="background-color:#999">
		<th colspan="23"  style="text-align:center"> DATOS DEL EQUIPO Y/O ACCESORIO </th>
		<th colspan="21"  style="text-align:center"> DATOS DEL MOVIMIENTO </th>
	  </tr>
	  <tr style="background-color:#ccc">
		<th width="2%" style="text-align:center">N°</th>
		<th width="3%" style="text-align:center" >UN</th>
		<th width="3%" style="text-align:center" >N° DE MOTIVO</th>
		<th width="15%" style="text-align:center">PROVEEDOR</th>
		<th width="15%" style="text-align:center">N° DE COTIZACION</th>
		<th width="15%" style="text-align:center">N° DE GUIA REMISION</th>
		<th width="15%" style="text-align:center">TIPO</th>
		<th width="3%" style="text-align:center">MARCA</th>
		<th width="3%" style="text-align:center" >MODELO</th>
		<th width="2%" style="text-align:center" >SERIAL</th>
		<th width="2%" style="text-align:center" >N° PLACA NGR</th>
		<th width="2%" style="text-align:center" >N° PLACA TI</th>
		<th width="2%" style="text-align:center" >UNIDAD DE MEDIDA</th>
		<th width="2%" style="text-align:center" >FECHA INGRESO</th>
		<th width="2%" style="text-align:center" >GARANTIA</th>
		<th width="2%" style="text-align:center" >FECHA DE VENCIMIENTO</th>
		<th width="2%" style="text-align:center" >COSTO USD.</th>
		<th width="2%" style="text-align:center" >CONDICIÓN</th>
		<th width="2%" style="text-align:center" >ESTATUS</th>
		<th width="2%" style="text-align:center" >PROPOSITO</th>
		<th width="2%" style="text-align:center" >STOCK</th>
		<th width="2%" style="text-align:center" >ULTIMO MOVIMIENTO</th>
		<th width="2%" style="text-align:center" >ULTIMA UBICACION</th>
		
		<th width="3%" style="text-align:center" >UN</th>
		<th width="20%" style="text-align:center" >UBICACION</th>
		<th width="3%" style="text-align:center" >MOVIMIENTO</th>
		<th width="3%" style="text-align:center" >MOTIVO</th>
		<th width="4%" style="text-align:center" >FECHA</th>
		<th width="4%" style="text-align:center" >PROPOSITO</th>
		<th width="6%" style="text-align:center" >TICKET</th>
		<th width="4%" style="text-align:center" >TIPO DE GUIA </th>
		<th width="4%" style="text-align:center" >N° GUIA </th>
		<th width="3%" style="text-align:center" >RESPONSABLE ENTRADA</th>
		<th width="3%" style="text-align:center" >RESPONSABLE DESTINO</th>
		<th width="3%" style="text-align:center" >PREPARACION DE EQUIPO</th>
		<th width="3%" style="text-align:center" >ENVIADO A</th>
		<th width="3%" style="text-align:center" >ENCARGADO DE TRASLADO</th>
		<th width="3%" style="text-align:center" >CANTIDAD</th>
		<th width="3%" style="text-align:center" >ESTATUS</th>
		<th width="3%" style="text-align:center" >CONDICIÓN</th>
		<th width="2%" style="text-align:center" >ACCESORIOS</th>
		<th width="2%" style="text-align:center" >OBSERVACIONES</th>
		<th width="5%" style="text-align:center" >USUARIO</th>
	  </tr>
	  
	 
	  <?php 
		$total_articulos=0;
		$total_inventario=0;
		$sql="SELECT (SELECT tx_nombre FROM mod_proveedor WHERE id_proveedor=a.id_proveedor) as proveedor,
		(SELECT tx_abreviatura FROM mod_empresa WHERE id_empresa=a.id_unidad_negocio) as un_producto,
		tx_nu_motivo,
		tx_nu_cotizacion,
		tx_guia_remision,
		(SELECT tx_nombre_tipo FROM cfg_tipo_producto WHERE id_tipo_producto=a.id_tipo_producto) as tipo_producto,
		(SELECT tx_marca FROM cfg_tipo_marcas WHERE id_marca=a.id_tipo_marca) as tipo_marca,
		a.tx_modelo,
		a.tx_serial,
		tx_placati,
		tx_ngr,
		(SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=a.id_unidad_medida) as unidad_medida,
		fe_ingreso,
		(SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=a.id_garantia) as garantia,
		fe_vencimiento,
		costo,
		(SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=a.id_condicion) as condicion,
		(SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=a.estatus) as estatus,
		a.tx_proposito,
		n_stock,
		CASE WHEN ultimo_movimiento=1 THEN 'ENTRADA' ELSE 'SALIDA' END as movimiento,
		(SELECT tx_descripcion FROM mod_tienda WHERE id_tienda=a.id_tienda) as tienda,
		 tx_abreviatura as un,
		(SELECT tx_descripcion FROM mod_tienda WHERE id_tienda=b.id_tienda) as ubicacion,
		CASE WHEN id_tipo_movimiento=1 THEN 'ENTRADA' ELSE 'SALIDA' END as tipo_movimiento,
		(SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=b.id_motivo) as motivo,
		fe_movimiento,
		b.tx_proposito as proposito,
		tx_ticket,
		(SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=b.id_tipo_guia) as tipo_guia,
		tx_guia,
		tx_responsable,
		(SELECT tx_descripcion FROM mod_responsable_destino WHERE id_tienda=b.id_responsable_destino) as responsable_destino,
		(SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=b.id_tipo_preparacion) as id_tipo_preparacion,
		(SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=b.id_tipo_enviado_a) as id_tipo_enviado_a,
		(SELECT tx_responsable FROM mod_responsable WHERE id_responsable=b.id_responsable_enviado) as id_responsable_enviado,
		nx_cantidad,
		(SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=b.estatus_producto) as estatus_producto,
		(SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=b.id_condicion_producto) as id_condicion_producto,
		b.tx_accesorios as accesorios,
		b.tx_observacion as observacion,
		(SELECT tx_nombre_apellido FROM cfg_usuario WHERE id_usuario=b.id_usuario) as usuario
		FROM mod_producto a LEFT JOIN mod_movimiento b ON a.id_producto=b.id_producto 
		LEFT JOIN mod_tienda c ON a.id_tienda=c.id_tienda LEFT JOIN mod_empresa d ON a.id_unidad_negocio=d.id_empresa  ";
		$sql.=$where;
		//$sql.=" LIMIT 10 ";
	  $c=0;
	  $res=abredatabase(g_BaseDatos,$sql);
	  while ($row=dregistro($res)){
		$c+=1;  
		?>
	  <tr style="font-size:11px">
		<td style="text-align:center" ><?php echo $c; $total_articulos=$c; ?> </td>
		<td style="text-align:left"><?php echo $row['un_producto']; ?></td>
		<td style="text-align:left"><?php echo $row['tx_nu_motivo']; ?></td>
		<td style="text-align:left"><?php echo $row['proveedor']; ?></td>
		<td style="text-align:left"><?php echo $row['tx_nu_cotizacion']; ?></td>
		<td style="text-align:left"><?php echo $row['tx_guia_remision']; ?></td>
		<td style="text-align:left"><?php echo $row['tipo_producto']; ?></td>
		<td style="text-align:left"><?php echo $row['tipo_marca']; ?></td>
		<td style="text-align:left"><?php echo $row['tx_modelo']; ?></td>
		<td style="text-align:left"><?php echo $row['tx_serial']; ?></td>
		<td style="text-align:left"><?php echo $row['tx_ngr']; ?></td>
		<td style="text-align:left"><?php echo $row['tx_placati']; ?></td>
		<td style="text-align:left"><?php echo $row['unidad_medida']; ?></td>
		<td style="text-align:left"><?php echo $row['fe_ingreso']; ?></td>
		<td style="text-align:left"><?php echo $row['garantia']; ?></td>
		<td style="text-align:left"><?php echo $row['fe_vencimiento']; ?></td>
		<td style="text-align:left"><?php echo $row['costo']; ?></td>
		<td style="text-align:left"><?php echo $row['condicion']; ?></td>
		<td style="text-align:left"><?php echo $row['estatus']; ?></td>
		<td style="text-align:left"><?php echo $row['tx_proposito']; ?></td>
		<td style="text-align:left"><?php echo $row['n_stock']; ?></td>
		<td style="text-align:left"><?php echo $row['movimiento']; ?></td>
		<td style="text-align:left"><?php echo $row['tienda']; ?></td>
		
		<td style="text-align:left"><?php echo $row['un']; ?></td>
		<td style="text-align:left"><?php echo $row['ubicacion']; ?></td>
		<td style="text-align:left"><?php echo $row['tipo_movimiento']; ?></td>
		<td style="text-align:left"><?php echo $row['motivo']; ?></td>
		<td style="text-align:left"><?php echo $row['fe_movimiento']; ?></td>
		<td style="text-align:left"><?php echo $row['proposito']; ?></td>
		<td style="text-align:left"><?php echo $row['tx_ticket']; ?></td>
		<td style="text-align:left"><?php echo $row['tipo_guia']; ?></td>
		<td style="text-align:left"><?php echo $row['tx_guia']; ?></td>
		<td style="text-align:left"><?php echo $row['tx_responsable']; ?></td>
		<td style="text-align:left"><?php echo $row['responsable_destino']; ?></td>
		<td style="text-align:left"><?php echo $row['id_tipo_preparacion']; ?></td>
		<td style="text-align:left"><?php echo $row['id_tipo_enviado_a']; ?></td>
		<td style="text-align:left"><?php echo $row['id_responsable_enviado']; ?></td>
		<td style="text-align:left"><?php echo $row['nx_cantidad']; ?></td>
		<td style="text-align:left"><?php echo $row['estatus_producto']; ?></td>
		<td style="text-align:left"><?php echo $row['id_condicion_producto']; ?></td>
		<td style="text-align:left"><?php echo $row['accesorios']; ?></td>
		<td style="text-align:left"><?php echo $row['observacion']; ?></td>
		<td style="text-align:left"><?php echo $row['usuario']; ?></td>
		
	  </tr>
	  <?php } ?>
	  
	</table>
	<div class="col-xs-4">TOTAL EQUIPOS Y/O ACCESORIOS: <span id="articulos" ><?php echo $c; ?></span></div> 
		</div>
	</div>

</div>




</body>
</html>