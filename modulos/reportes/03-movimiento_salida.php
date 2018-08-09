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
	
	

</head>

<body>
<div class="container-fluid">

<div class="col-lg-12" id="dvData" style="margin-top:50px">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<div id="print" align="left" >
<div><img src="../repositorio/logos_cintillos/<?php echo $row['tx_logo']; ?>"  ></div>
<h3><?php echo $_GET['reporte']; ?></h3><hr>
<div class="col-lg-12" align="LEFT" style="font-size:14px; margin-left:10px; margin-bottom:10px;  ">DESDE <?php echo $_GET['fe_entrada']; ?> HASTA <?php echo $_GET['fe_hasta']; ?> </div>
</div>
<div class="col-lg-12" >
<table class="table table-bordered table-striped" style="font-size:12px; color:#000; width:100% " >
  <thead>
  <tr style="background-color:#999">
    <th width="1%" style="text-align:center" >N°</th>
	<th width="2%" style="text-align:center" >UN</th>
	<th width="30%" style="text-align:center" >UBICACION</th>
	<th width="13%" style="text-align:center" >RESPONSABLE DESTINO</th>
	<th width="5%" style="text-align:center" >TICKET</th>
    <th width="5%" style="text-align:center" >GUIA DE REMISIÓN</th>
    <th width="5%" >FECHA DE INGRESO</th>
    <th width="8%" >TIPO</th>
    <th width="5%" style="text-align:center">MARCA</th>
    <th width="5%" style="text-align:center" >MODELO</th>
    <th width="5%" style="text-align:center" >SERIAL</th>
    <th width="5%" style="text-align:center" >ACCESORIOS</th>
    <th width="5%" style="text-align:center" >CANTIDAD</th>
    <th width="5%" style="text-align:center" >N° PLACA NGR</th>
    <th width="5%" style="text-align:center" >N° PLACA TI</th>
  </tr>
  </thead>
  <tbody>
  <?php 
	
    //$sql="SELECT (SELECT tx_abreviatura FROM mod_empresa WHERE mod_empresa.tx_empresa=a.origen) as unidad, tx_nombre_tipo, a.tx_descripcion, a.tx_marca, a.tx_modelo, a.tx_serial, a.tx_ngr, (SELECT tx_placati FROM mod_producto WHERE id_producto=a.id_producto) as tx_placati, a.estatus_producto, a.nx_cantidad, a.nombre_origen_destino, a.tx_guia, (SELECT tx_ticket FROM mod_movimiento WHERE id_movimiento=a.id_movimiento) as tx_ticket, a.fe_movimiento, a.nombre_proveedor, (b.tx_descripcion || ' ' || b.tx_apellido_paterno|| ' ' ||b.tx_apellido_materno|| ' ' || b.tx_dni|| ' ' || b.tx_puesto) as tx_responsable, a.tx_accesorios, a.tx_observacion FROM vie_tbl_movimiento a LEFT JOIN mod_movimiento c ON c.id_movimiento=a.id_movimiento LEFT JOIN vie_tbl_tiendas_destino b ON b.id_tienda=c.id_responsable_destino  WHERE a.id_tipo_movimiento=2";
	
	$sql="SELECT (SELECT tx_abreviatura FROM mod_empresa x, mod_tienda y WHERE x.id_empresa=y.id_empresa and y.id_tienda= c.id_tienda) as unidad,(SELECT tx_nombre_tipo FROM cfg_tipo_producto WHERE id_tipo_producto=h.id_tipo_producto) as tx_nombre_tipo, (SELECT tx_marca FROM cfg_tipo_marcas WHERE id_marca=h.id_tipo_marca) as tx_marca, h.tx_modelo, h.tx_serial, h.tx_ngr, h.tx_placati,  c.nx_cantidad,  c.tx_guia, c.tx_ticket, c.fe_movimiento, (b.tx_descripcion || ' ' || b.tx_apellido_paterno|| ' ' ||b.tx_apellido_materno|| ' ' || b.tx_dni|| ' ' || b.tx_puesto) as tx_responsable, c.tx_accesorios, u.tx_descripcion as nombre_origen_destino FROM  mod_movimiento c LEFT JOIN  mod_producto h ON c.id_producto=h.id_producto LEFT JOIN  mod_responsable_destino b ON b.id_tienda=c.id_responsable_destino LEFT JOIN vie_tbl_tiendas u ON c.id_tienda=u.id_tienda WHERE c.id_tipo_movimiento=2";

if ($_GET['motivo']>0){
	$sql.=" AND c.id_motivo=".$_GET['motivo'];

}
if ($_GET['responsable_destino']>0){
	$sql.=" AND c.id_responsable_destino=".$_GET['responsable_destino'];

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
	$sql.=" AND b.id_tienda=".$_GET['origen']."";

}

   $sql.=" ORDER BY fe_movimiento DESC, tx_guia";
  $c=0;
  $res=abredatabase(g_BaseDatos,$sql);
  while ($row=dregistro($res)){
	$c+=1;  
	?>
  <tr Style="font-size:10px">
    <td style="text-align:center" ><?php echo $c; ?> </td>
	 <td style="text-align:center"><?php echo $row['unidad']; ?></td>
	 <td style="text-align:left"><?php echo $row['nombre_origen_destino']; ?></td>
	 <td style="text-align:center"><?php echo $row['tx_responsable']; ?></td>
	 <td style="text-align:center"><?php echo $row['tx_ticket']; ?></td>
    <td style="text-align:center"><?php echo $row['tx_guia']; ?></td>
    <td style="text-align:left"><?php echo $row['fe_movimiento']; ?></td>
    <td style="text-align:left"><?php echo $row['tx_nombre_tipo']; ?></td>
    <td style="text-align:center"><?php echo $row['tx_marca']; ?></td>
    <td style="text-align:center"><?php echo $row['tx_modelo']; ?></td>
    <td style="text-align:center"><?php echo $row['tx_serial']; ?></td>
    <td style="text-align:center"><?php echo $row['tx_accesorios']; ?></td>
    <td style="text-align:center"><?php echo $row['nx_cantidad']; ?></td>
    <td style="text-align:center"><?php echo $row['tx_ngr']; ?></td>
	<td style="text-align:center"><?php echo $row['tx_placati']; ?></td>
  </tr>
  <?php } ?>
  </tbody>
</table>
</div>
</div>
</div>


</body>
</html>