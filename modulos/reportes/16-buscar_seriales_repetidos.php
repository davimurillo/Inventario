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
<title>APICES|Inventario Ver 1.0.2</title>
<link href="../../img/logos/apices.png" rel="shortcut icon" type="image/x-icon" />
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
<div class="container">

<div id="dvData" style="margin-top:50px">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<div id="print" align="left" >
<div><img src="../repositorio/logos_cintillos/<?php echo $row['tx_logo']; ?>"  ></div>
<h3>REPORTE DE SERIALES REPETIDOS</h3><hr>
<div align="LEFT" style="font-size:14px; margin-left:10px; margin-bottom:10px ">AL <?php echo date('d/m/Y'); ?></div>
</div>

<div style="margin-top:30px"> Stock en Almacen </div>
<table class="mitabla table table-bordered table-striped" style="font-size:12px; color:#000; width:100%" >
  <thead>
  <tr style="background-color:#999">
    <th width="90%" style="text-align:center" >SERIAL</th>
    <th width="10%" style="text-align:center" >VECES</th>
  </tr>
  </thead>
  <tbody>
  <?php 
	
    $sql="SELECT tabla.tx_serial, tabla.numero FROM (select tx_serial, COUNT(tx_serial) as numero FROM mod_producto  GROUP BY tx_serial ORDER BY numero DESC) AS tabla WHERE tabla.numero >1 ";
  
  $c=0;
  $res=abredatabase(g_BaseDatos,$sql);
  while ($row=dregistro($res)){
	$c+=1;  
	?>
  <tr>
    <td style="text-align:left" ><?php echo $row['tx_serial']; ?> </td>
    <td style="text-align:center"><?php echo $row['numero']; ?></td>
  </tr>
  <?php } ?>
  </tbody>
</table>

</div>
</div>
<script type="text/javascript" src="../../lib/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../lib/js/jquery.thead-1.1.min.js"></script>
<script>
$(function() {
       $('.mitabla').thead();
	   $('.mitabla2').thead();
});
</script>
</body>
</html>