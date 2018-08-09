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
	<link rel="stylesheet" href="../../lib/css/bootstrap.min.css" >
	<link rel="stylesheet" href="../../lib/css/animate.css" >
	<link href="../../lib/fonts/css/font-awesome.min.css" rel="stylesheet">
	<link id="switcher" href="../../lib/css/theme-color/lite-blue-theme.css" rel="stylesheet">
</head>

<body>
<div class="container-fluid">
	<div ><img src="../repositorio/logos_cintillos/<?php echo $row['tx_logo']; ?>"  ></div>
	<div class="col-xs-6" align="LEFT" style="font-size:14px; margin-bottom:10px "><h3><?php echo $_GET['reporte']; ?></h3></div>
	<div class="col-xs-6" align="right" style="font-size:14px; margin-top:25px ">DESDE <?php echo $_GET['fe_entrada']; ?> - HASTA <?php echo $_GET['fe_hasta']; ?></div>
	<div class="col-xs-12" align="LEFT" style="margin-top:-20px" ><hr></div>
	<div class="col-xs-12" align="LEFT" style="font-size:14px; margin-bottom:10px; font-weight:bold ">
		<div class="col-xs-4">TOTAL EQUIPOS Y/O ACCESORIOS: <span id="articulos" ></span></div> 
		</div>
	</div>

	<div class="col-xs-12" >
	<table class="table table-bordered table-striped" style="font-size:12px; color:#000; width:100% " >
	  
	  <tr style="background-color:#999">
		<th width="2%" style="text-align:center" >N°</th>
		<th width="3%" style="text-align:center" >UN</th>
		<th width="3%" style="text-align:center" >MOVIMEINTO</th>
		<th width="20%" style="text-align:center" >UBICACION</th>
		<th width="4%" style="text-align:center" >N° GUIA </th>
		<th width="6%" style="text-align:center" >TICKET</th>
		<th width="4%" style="text-align:center" >FECHA</th>
		<th width="15%" >TIPO</th>
		<th width="3%" style="text-align:center">MARCA</th>
		<th width="3%" style="text-align:center" >MODELO</th>
		<th width="2%" style="text-align:center" >SERIAL</th>
		<th width="2%" style="text-align:center" >N° PLACA NGR</th>
		<th width="2%" style="text-align:center" >N° PLACA TI</th>
		<th width="2%" style="text-align:center" >ESTADO</th>
		<th width="3%" style="text-align:center" >CANTIDAD</th>
		<th width="3%" style="text-align:center" >RESPONSABLE</th>
		<th width="5%" style="text-align:center" >COSTO</th>
		<th width="5%" style="text-align:center" >VALOR INVENTARIO</th>
		<th width="5%" style="text-align:center" >USUARIO</th>
	  </tr>
	  
	 
	  <?php 
		$total_articulos=0;
		$total_inventario=0;
		$sql="SELECT 
	tx_nombre_tipo,
	tx_descripcion,
	tx_marca,
	tx_modelo,
	tx_serial,
	tx_ngr,
	(SELECT tx_placati FROM mod_producto WHERE id_producto=a.id_producto) as tx_placati,
	estatus_producto,
	nx_cantidad,
	(SELECT tx_abreviatura FROM mod_empresa WHERE tx_empresa=a.origen) as un,
	tx_guia,
	(SELECT tx_descripcion FROM mod_tienda WHERE id_tienda=a.id_tienda) as origen,
	tx_guia,
	(SELECT tx_ticket FROM mod_movimiento WHERE id_movimiento=a.id_movimiento) as tx_ticket,
	CASE WHEN id_tipo_movimiento=1 THEN 'ENTRADA' ELSE 'SALIDA' END movimiento,  
	(SELECT fe_actualizada FROM mod_movimiento WHERE id_movimiento=a.id_movimiento) as fecha,
	fe_movimiento,
	(SELECT tx_responsable FROM mod_movimiento WHERE id_movimiento=a.id_movimiento) as tx_responsable,
	fe_movimiento,
	nombre_proveedor,
	(SELECT tx_descripcion FROM mod_movimiento, mod_responsable_destino WHERE id_responsable_destino=mod_responsable_destino.id_tienda and  id_movimiento=a.id_movimiento) as tx_responsable,  
	tx_observacion,
	nx_costo,
	(SELECT tx_nombre_apellido FROM mod_movimiento, cfg_usuario WHERE mod_movimiento.id_usuario=cfg_usuario.id_usuario and id_movimiento=a.id_movimiento) as usuario
	FROM vie_tbl_movimiento a WHERE 1=1";

	if ($_GET['motivo']>0){
		$sql.=" AND id_motivo=".$_GET['motivo'];

	}
	if ($_GET['un']>0){
	$sql.=" AND (SELECT id_empresa FROM mod_empresa WHERE mod_empresa.tx_empresa=a.origen)=".$_GET['un'];

}
	if ($_GET['guia']>0){
		$sql.=" AND tx_guia='".$_GET['guia']."'";

	}
	if ($_GET['fe_entrada']>0){
		$sql.=" AND fe_movimiento>='".$_GET['fe_entrada']."'";

	}
	if ($_GET['fe_hasta']>0){
		$sql.=" AND fe_movimiento<='".$_GET['fe_hasta']."'";

	}
	if ($_GET['proveedor']>0){
		$sql.=" AND id_proveedor=".$_GET['proveedor']."";

	}
	if ($_GET['origen']>0){
		$sql.=" AND id_tienda=".$_GET['origen']."";

	}
	if ($_GET['ticket']>0){
		$sql.=" AND tx_ticket='".$_GET['ticket']."'";

	}
	$sql.=" ORDER BY fecha DESC, tx_guia";
	  
	  $c=0;
	  $res=abredatabase(g_BaseDatos,$sql);
	  while ($row=dregistro($res)){
		$c+=1;  
		?>
	  <tr style="font-size:11px">
		<td style="text-align:center" ><?php echo $c; $total_articulos=$c; ?> </td>
		<td style="text-align:left"><?php echo $row['un']; ?></td>
		<td style="text-align:center"><?php echo $row['movimiento']; ?></td>
		<td style="text-align:left"><?php echo $row['origen']; ?></td>
		<td style="text-align:center"><?php echo $row['tx_guia']; ?></td>
		<td style="text-align:center"><?php echo $row['tx_ticket']; ?></td>
		<td style="text-align:center"><?php echo $row['fe_movimiento']; ?></td>
		<td style="text-align:left"><?php echo $row['tx_nombre_tipo']; ?></td>
		<td style="text-align:center"><?php echo $row['tx_marca']; ?></td>
		<td style="text-align:center"><?php echo $row['tx_modelo']; ?></td>
		<td style="text-align:center"><?php echo $row['tx_serial']; ?></td>
		<td style="text-align:center"><?php echo $row['tx_ngr']; ?></td>
		<td style="text-align:center"><?php echo $row['tx_placati']; ?></td>
		<td style="text-align:center"><?php echo $row['estatus_producto']; ?></td>
		<td style="text-align:center"><?php echo $row['nx_cantidad']; ?></td>
		<td style="text-align:center"><?php echo $row['tx_responsable']; ?></td>
		<td style="text-align:right"><?php echo "S/. ".number_format($row['nx_costo']); $total_inventario+=$row['nx_cantidad']*$row['nx_costo']; ?></td>
		<td style="text-align:right"><?php echo "S/. ".number_format($row['nx_cantidad']*$row['nx_costo']); ?></td>
	    <td style="text-align:right"><?php echo $row['usuario']; ?></td>
	  </tr>
	  <?php } ?>
	  
	</table>
	</div>

</div>

<!-- jQuery 2.2.3 -->
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
$('#articulos').html('<?php echo $total_articulos; ?>');
</script>

</body>
</html>