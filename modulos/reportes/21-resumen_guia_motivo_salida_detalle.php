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
<table class=" table table-bordered table-striped" style="font-size:12px; color:#000; width:100%" >
 
  <?php 
	
    $sql="SELECT tabla.id_motivo, (SELECT tx_tipo FROM cfg_tipo_objeto where id_tipo_objeto=tabla.id_motivo) AS motivo, count(tabla.tx_guia) as n FROM ( SELECT id_motivo, tx_guia, count(id_motivo) as n FROM mod_movimiento WHERE id_tipo_movimiento=2 AND tx_guia like('007%') GROUP BY id_motivo, tx_guia ORDER BY id_motivo DESC) AS TABLA  ";
  
	if (isset($_GET['motivo']) && $_GET['motivo']!='') $sql.=" WHERE tabla.id_motivo=".$_GET['motivo']."";
	
	 $sql.=" GROUP BY  tabla.id_motivo ORDER BY tabla.id_motivo";

  $c=0;
  $res=abredatabase(g_BaseDatos,$sql);
  while ($row=dregistro($res)){
	$c+=1;  
	?>
	<tr style="background-color:#999">
		<th width="5%" style="text-align:center" >N°</th>
		<th colspan="3" width="60%" style="text-align:center" >MOTIVO</th>
		<th width="35%" style="text-align:center" >TOTAL GUIAS</th>
	</tr>
  <tr>
    <th style="text-align:center" ><?php echo $c; ?> </th>
    <th colspan="3" style="text-align:left" ><?php echo $row['motivo']; ?> </th>
    <th style="text-align:center" ><?php echo $row['n']; ?> </th>
  </tr>
  <tr style="background-color:#999">
	<th width="5%" style="text-align:center" >N°</th>
	<th  width="20%" style="text-align:center" >N° GUIA</th>
	<th  width="20%" style="text-align:center" >FECHA</th>
	<th  width="20%" style="text-align:center" >TICKET</th>
    <th width="35%" style="text-align:center" >TOTAL EQUIPOS</th>
  </tr>
  <?php 
		 $sql2="SELECT id_motivo, tx_guia, fe_movimiento, tx_ticket, count(id_motivo) as n FROM mod_movimiento WHERE id_tipo_movimiento=2 AND tx_guia like('007%') and id_motivo=".$row['id_motivo']."  GROUP BY id_motivo, tx_guia, fe_movimiento, tx_ticket ORDER BY id_motivo DESC";
		$d=0;
		$res2=abredatabase(g_BaseDatos,$sql2);
		while ($row2=dregistro($res2)){
			$d+=1;
   ?>
		<tr >
			<th width="5%" style="text-align:center" ><?php echo $d; ?></th>
			<th  width="20%" style="text-align:center" ><?php echo $row2['tx_guia']; ?></th>
			<th  width="20%" style="text-align:center" ><?php echo $row2['fe_movimiento']; ?></th>
			<th  width="20%" style="text-align:center" ><?php echo $row2['tx_ticket']; ?></th>
			<th width="35%" style="text-align:center" ><?php echo $row2['n']; ?></th>
		</tr>
  
  <?php }} ?>
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