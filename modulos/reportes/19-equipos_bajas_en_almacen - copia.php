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
	  
	   <!-- Main Style -->
    <link href="../../style.css" rel="stylesheet">
	<script>
		.mitabla {
				font-size:10px;
		}
	</script>
	

</head>

<body>
<div class="container-fluid">

<div class="col-lg-12" id="dvData" style="margin-top:50px">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<div id="print" align="left" >
<div><img src="../repositorio/logos_cintillos/<?php echo $row['tx_logo']; ?>"  ></div>
<h3><?php echo $_GET['reporte']; ?></h3><hr>
<div class="col-lg-12" align="LEFT" style="font-size:14px; margin-left:10px; margin-bottom:10px;  ">DESDE <?php echo $_GET['fe_entrada']; ?> HASTA <?php echo $_GET['fe_hasta']; ?></div>
</div>
<div class="col-lg-12" >
<table class="mitabla table table-bordered table-striped" style="font-size:12px; color:#000; width:100% " >
  <thead>
  <tr style="background-color:#999">
    <th width="1%" style="text-align:center" >N°</th>
	<th width="2%" style="text-align:center" >UN</th>
	<th width="30%" style="text-align:center" >DESTINO</th>
	<th width="13%" style="text-align:center" >GUÍA</th>
	<th width="5%" style="text-align:center" >FECHA</th>
    <th width="5%" style="text-align:center" >TICKET</th>
    <th width="8%" >TIPO</th>
    <th width="5%" style="text-align:center">MARCA</th>
    <th width="5%" style="text-align:center" >MODELO</th>
    <th width="5%" style="text-align:center" >SERIAL</th>
    <th width="5%" style="text-align:center" >ACCESORIOS</th>
    <th width="5%" style="text-align:center" >CODIGO NGR</th>
	<th width="5%" style="text-align:center" >CANTIDAD</th>
	<th width="5%" style="text-align:center" >PROPOSITO</th>
  </tr>
  </thead>
  <tbody>
  <?php 
	
    $sql="SELECT 
	(SELECT tx_abreviatura FROM mod_empresa WHERE mod_empresa.tx_empresa=a.origen) as unidad,
tx_nombre_tipo,
tx_descripcion,
tx_marca,
tx_modelo,
tx_serial,
tx_ngr,
estatus_producto,
nx_cantidad,
nombre_origen_destino,
tx_guia,
(SELECT tx_ticket FROM mod_movimiento WHERE id_movimiento=a.id_movimiento) as tx_ticket,
(SELECT tx_proposito FROM mod_movimiento WHERE id_movimiento=a.id_movimiento) as proposito, 
fe_movimiento,
nombre_proveedor,
tx_responsable, 
tx_accesorios,
tx_observacion
FROM vie_tbl_movimiento a WHERE id_tipo_movimiento=1 AND estatus_producto LIKE ('BAJA%')";

if ($_GET['motivo']>0){
	$sql.=" AND id_motivo=".$_GET['motivo'];

}
if ($_GET['guia']>0){
	$sql.=" AND tx_guia='".$_GET['guia']."'";

}
if ($_GET['tipo']!=''){
	
	$sql.=" AND tx_nombre_tipo=(SELECT tx_nombre_tipo FROM cfg_tipo_producto WHERE id_tipo_producto=".$_GET['tipo']." LIMIT 1)";

}
if ($_GET['fe_entrada']!=''){
	
	$sql.=" AND fe_movimiento>='".$_GET['fe_entrada']."'";

}
if ($_GET['fe_hasta']!=''){
	
	$sql.=" AND fe_movimiento<='".$_GET['fe_hasta']."'";

}
if ($_GET['proveedor']>0){
	$sql.=" AND id_proveedor=".$_GET['proveedor']."";

}
if ($_GET['origen']>0){
	$sql.=" AND id_tienda=".$_GET['origen']."";

}

	$sql.=" ORDER BY fe_movimiento DESC";
  
  $c=0;
  $res=abredatabase(g_BaseDatos,$sql);
  while ($row=dregistro($res)){
	$c+=1;  
	?>
  <tr>
    <td style="text-align:center" ><?php echo $c; ?> </td>
	 <td style="text-align:center"><?php echo $row['unidad']; ?></td>
	 <td style="text-align:center"><?php echo $row['nombre_origen_destino']; ?></td>
	 <td style="text-align:center"><?php echo $row['tx_guia']; ?></td>
	 <td style="text-align:left"><?php echo $row['fe_movimiento']; ?></td>
	 <td style="text-align:center"><?php echo $row['tx_ticket']; ?></td>
    <td style="text-align:left"><?php echo $row['tx_nombre_tipo']; ?></td>
    <td style="text-align:center"><?php echo $row['tx_marca']; ?></td>
    <td style="text-align:center"><?php echo $row['tx_modelo']; ?></td>
    <td style="text-align:center"><?php echo $row['tx_serial']; ?></td>
    <td style="text-align:center"><?php echo $row['tx_accesorios']; ?></td>
    <td style="text-align:center"><?php echo $row['tx_ngr']; ?></td>
	<td style="text-align:center"><?php echo $row['nx_cantidad']; ?></td>
	<td style="text-align:center"><?php echo $row['proposito']; ?></td>
  </tr>
  <?php } ?>
  </tbody>
</table>
</div>
</div>
</div>
<script type="text/javascript" src="../../lib/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../lib/js/jquery.thead-1.1.min.js"></script>
<script>
$(function() {
       $('.mitabla').thead();
});
</script>

</body>
</html>