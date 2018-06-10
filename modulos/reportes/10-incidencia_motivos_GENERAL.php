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

<div ><img src="../repositorio/logos_cintillos/<?php echo $row['tx_logo']; ?>"  ></div>
<h3><?php echo $_GET['reporte']; ?></h3><hr>
<div class="col-lg-12" align="LEFT" style="font-size:14px; margin-left:10px; margin-bottom:10px;  ">AL <?php echo date('d/m/Y'); ?></div>
</div>
<div class="col-lg-12" >
<?php 
	
    $sql="SELECT CASE WHEN (SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=id_motivo) ISNULL THEN 'NULOS' ELSE (SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=id_motivo) END as tx_motivo, COUNT(id_movimiento) as n_veces  FROM vie_tbl_movimiento WHERE id_tipo_movimiento=1 ";

if ($_GET['motivo']>0){
	$sql.=" AND id_motivo=".$_GET['motivo'];

}
if ($_GET['guia']>0){
	$sql.=" AND tx_guia='".$_GET['guia']."'";

}
if ($_GET['fe_entrada']>0){
	$sql.=" AND fe_movimiento='".$_GET['fe_entrada']."'";

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
 $sql.="GROUP BY id_motivo ORDER BY tx_motivo ";
  
   
	?>
<table class="table table-bordered table-striped" style="font-size:12px; color:#000; width:100% " >
   <tr style="background-color:#999">
    <th colspan="3" width="2%" style="text-align:center" >MOTIVOS ENTRADAS</th>
  </tr>
  <tr style="background-color:#999">
    <th width="2%" style="text-align:center" >N°</th>
    <th width="20%">MOTIVO</th>
    <th width="3%" style="text-align:center">MOVIMIENTO</th>
  </tr>
  
 
  <?php 
	$c=0;
	$res=abredatabase(g_BaseDatos,$sql);
	while ($row=dregistro($res)){
	$c+=1; 
	?>
  <tr>
    <td style="text-align:center" ><?php echo $c; ?> </td>
    <td style="text-align:left"><?php echo $row['tx_motivo']; ?></td>
    <td style="text-align:center"><?php echo $row['n_veces']; ?></td>
    
  </tr>
  <?php } ?>
  
</table>
</div>

<div class="col-lg-12" >
<?php 
	
    $sql="SELECT CASE WHEN (SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=id_motivo) ISNULL THEN 'NULOS' ELSE (SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=id_motivo) END as tx_motivo, COUNT(id_movimiento) as n_veces  FROM vie_tbl_movimiento WHERE id_tipo_movimiento=2 ";

if ($_GET['motivo']>0){
	$sql.=" AND id_motivo=".$_GET['motivo'];

}
if ($_GET['guia']>0){
	$sql.=" AND tx_guia='".$_GET['guia']."'";

}
if ($_GET['fe_entrada']>0){
	$sql.=" AND fe_movimiento='".$_GET['fe_entrada']."'";

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
$sql.="GROUP BY id_motivo ORDER BY tx_motivo ";
  
   
	?>
<table class="table table-bordered table-striped" style="font-size:12px; color:#000; width:100% " >
   <tr style="background-color:#999">
    <th colspan="3" width="2%" style="text-align:center" >MOTIVOS SALIDAS</th>
  </tr>
  <tr style="background-color:#999">
    <th width="2%" style="text-align:center" >N°</th>
    <th width="20%">MOTIVO</th>
    <th width="3%" style="text-align:center">MOVIMIENTO</th>
  </tr>
  
 
  <?php 
	$c=0;
	$res=abredatabase(g_BaseDatos,$sql);
	while ($row=dregistro($res)){
	$c+=1; 
	?>
  <tr>
    <td style="text-align:center" ><?php echo $c; ?> </td>
    <td style="text-align:left"><?php echo $row['tx_motivo']; ?></td>
    <td style="text-align:center"><?php echo $row['n_veces']; ?></td>
    
  </tr>
  <?php } ?>
  
</table>
</div>

</div>
</div>


</body>
</html>