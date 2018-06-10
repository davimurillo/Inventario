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
<div class="container">

<div id="dvData" style="margin-top:50px">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<div id="print" align="left" >
<div ><img src="../repositorio/logos_cintillos/<?php echo $row['tx_logo']; ?>"  ></div>
<h3><?php echo $_GET['reporte']; ?></h3><hr>
<div align="LEFT" style="font-size:14px; margin-left:10px; margin-bottom:10px ">AL <?php echo date('d/m/Y'); ?></div>
</div>

<div style="margin-top:30px"> RESUMEN DE GUIAS DE SALIDA POR MOTIVO </div>
<table class="mitabla table table-bordered table-striped" style="font-size:12px; color:#000; width:100%" >
  <thead>
  <tr style="background-color:#999">
	<th width="5%" style="text-align:center" >N°</th>
	<th width="60%" style="text-align:center" >MOTIVO</th>
    <th width="35%" style="text-align:center" >TOTAL GUIAS</th>
  </tr>
  </thead>
  <tbody>
  <?php 
	
    $sql="SELECT (SELECT tx_tipo FROM cfg_tipo_objeto where id_tipo_objeto=tabla.id_motivo) AS motivo, count(tabla.tx_guia) as n FROM ( SELECT id_motivo, tx_guia, count(id_motivo) as n FROM mod_movimiento WHERE id_tipo_movimiento=2 AND tx_guia like('007%') GROUP BY id_motivo, tx_guia ORDER BY id_motivo DESC) AS TABLA GROUP BY  tabla.id_motivo ORDER BY tabla.id_motivo ";
  
	if (isset($_GET['tipo']) && $_GET['tipo']!='') $sql.=" AND id_tipo_producto=".$_GET['tipo']."";
	

  $c=0;
  $res=abredatabase(g_BaseDatos,$sql);
  while ($row=dregistro($res)){
	$c+=1;  
	?>
  <tr>
    <td style="text-align:left" ><?php echo $c; ?> </td>
    <td style="text-align:left" ><?php echo $row['motivo']; ?> </td>
    <td style="text-align:left" ><?php echo $row['n']; ?> </td>
   
  </tr>
  <?php } ?>
  </tbody>
</table>

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