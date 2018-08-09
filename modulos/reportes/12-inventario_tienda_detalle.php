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
<div ><img src="../repositorio/logos_cintillos/<?php echo $row['tx_logo']; ?>"  ></div>
<h3><?php echo $_GET['reporte']; ?></h3><hr>
<div class="col-lg-12" align="LEFT" style="font-size:14px; margin-left:10px; margin-bottom:10px;  ">AL <?php echo date('d/m/Y'); ?></div>
</div>
<div class="col-lg-12" >
<table class="table table-bordered table-striped" style="font-size:12px; color:#000; width:100% " >
  
  <tr style="background-color:#999">
    <th width="25%" style="text-align:center" >TIENDA</th>
 	<th width="5%" style="text-align:center" >FECHA SALIDA</th>
    <th width="10%" style="text-align:center">TIPO</th>
    <th width="10%" style="text-align:center">MARCA</th>
    <th width="15%"style="text-align:center" >MODELO</th>
    <th width="15%" style="text-align:center">SERIAL</th>
	<th width="5%" style="text-align:center">No. PLACA NGR</th>
	<th width="5%" style="text-align:center">No. PLACA TI</th>
    <th width="5%" style="text-align:center">CANTIDAD</th>
  
  </tr>
  
 
  <?php 
	$c=0;
    $sql="SELECT (SELECT tx_descripcion FROM mod_tienda WHERE id_tienda=a.id_tienda) as tienda ,(SELECT tx_nombre_tipo FROM  cfg_tipo_producto WHERE id_tipo_producto=a.id_tipo_producto ) AS tipo_equipo, (select tx_marca FROM cfg_tipo_marcas WHERE id_marca=a.id_tipo_marca) as tx_marca, tx_modelo, tx_serial, tx_placati, tx_ngr, COUNT(id_producto) as cantidad, (select fe_movimiento FROM mod_movimiento where id_producto=a.id_producto ORDER BY fe_movimiento DESC, fe_actualizada DESC LIMIT 1) as fe_movimiento   FROM  mod_producto a, mod_tienda c WHERE  c.id_tienda=a.id_tienda ";

if ($_GET['un']>0){
	$sql.=" AND id_empresa=".$_GET['un'];

}if ($_GET['motivo']>0){
	$sql.=" AND id_motivo=".$_GET['motivo'];

}
if ($_GET['guia']>0){
	$sql.=" AND tx_guia='".$_GET['guia']."'";

}

if ($_GET['proveedor']>0){
	$sql.=" AND id_proveedor=".$_GET['proveedor']."";

}
if ($_GET['origen']>0){
	$sql.=" AND a.id_tienda=".$_GET['origen']."";

}
if ($_GET['ticket']>0){
	$sql.=" AND tx_ticket='".$_GET['ticket']."'";

}
if ($_GET['tipo']>0){
	$sql.=" AND a.id_tipo_producto=".$_GET['tipo'];

}


	$sql.=" GROUP BY a.id_tienda, id_tipo_producto, id_producto ORDER BY a.id_tienda, id_tipo_producto, id_producto";

  $titulo="";
  $c=0;
  $res=abredatabase(g_BaseDatos,$sql);
  while ($row=dregistro($res)){
	$c+=1;  
	if ($titulo!=$row['tienda']) echo '<tr><td colspan="3" style="text-align:left; font-size:20px " ><strong>'.$row['tienda'].'</strong></td></tr>';  $titulo=$row['tienda']; 
	?>
  <tr>
    <td style="text-align:left" ><?php echo $row['tienda']; ?> </td>
<td style="text-align:left"><?php echo $row['fe_movimiento']; ?></td>
    <td style="text-align:left"><?php echo $row['tipo_equipo']; ?></td>
    <td style="text-align:center"><?php echo $row['tx_marca']; ?></td>
    <td style="text-align:center"><?php echo $row['tx_modelo']; ?></td>
    <td style="text-align:center"><?php echo $row['tx_serial']; ?></td>
	<td style="text-align:center"><?php echo $row['tx_ngr']; ?></td>
	<td style="text-align:center"><?php echo $row['tx_placati']; ?></td>
    <td style="text-align:center"><?php $c+=$row['cantidad']; echo $row['cantidad']; ?></td>
    
    
  </tr>
  <?php } ?>
   <tr>
    <td colspan="5 "style="text-align:left" >TOTAL </td>
    <td style="text-align:center"><?php echo $c; ?></td>
    
  </tr>
</table>
</div>
</div>
</div>


</body>
</html>