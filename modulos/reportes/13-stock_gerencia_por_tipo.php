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

<body style="font-size:12px">
<div class="container">

<div id="dvData" style="margin-top:50px">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<div id="print" align="left" >
<div ><img src="../repositorio/logos_cintillos/<?php echo $row['tx_logo']; ?>"  ></div>
<h3>STOCK DE INVENTARIO</h3><hr>
<div align="LEFT" style="font-size:14px; margin-left:10px; margin-bottom:10px ">AL <?php echo date('d/m/Y'); ?></div>
</div>

<div style="margin-top:30px"> INFORME GERENCIAL DE STOCK </div>
<table class="mitabla table table-bordered table-striped" style="font-size:12px; color:#000; width:100%" >
  <thead>
  <tr style="background-color:#999">
    <th width="5%" style="text-align:center" >N</th>
    <th width="85%" style="text-align:center" >TIPO DE EQUIPO</th>
    <th width="10%" style="text-align:center" >STOCK</th>
    <th width="10%" style="text-align:center" >ENTRADAS</th>
    <th width="10%" style="text-align:center" >SALIDAS</th>
    <th width="10%" style="text-align:center" >TOTAL</th>
    <th width="10%" style="text-align:center" >DIFERENCIA</th>
  </tr>
  </thead>
  <tbody>
  <?php 
	
    $sql="SELECT tabla.id_tipo_producto, tabla.proveedor,tabla.tienda,tabla.id_tienda,  tabla.tipo, sum(tabla.entradas) as entradas, sum (tabla.salidas) as salidas, SUM(tabla.n_producto) as total FROM (
select id_tipo_producto, a.estatus, a.id_tienda, (SELECT id_empresa FROM mod_tienda WHERE id_tienda=a.id_tienda) as proveedor,  (SELECT tx_descripcion FROM mod_tienda WHERE id_tienda=a.id_tienda) as tienda, (SELECT tx_nombre_tipo FROM cfg_tipo_producto WHERE id_tipo_producto=a.id_tipo_producto) as tipo, sum(n_stock) as n_producto, (select sum(n_stock) FROM mod_producto WHERE id_producto=a.id_producto and ultimo_movimiento=1) as entradas, (select sum(n_stock) FROM mod_producto WHERE id_producto=a.id_producto and ultimo_movimiento=2) as salidas FROM mod_producto a  WHERE ultimo_movimiento>0 GROUP BY id_tipo_producto, a.id_producto, a.estatus,  a.id_tienda) as tabla WHERE tabla.estatus=5 AND tabla.tienda!=''  ";
  
	if (isset($_GET['tipo']) && $_GET['tipo']!='') $sql.=" AND tabla.id_tipo_producto=".$_GET['tipo'];
	if (isset($_GET['origen']) && $_GET['origen']!='') $sql.=" AND tabla.id_tienda=".$_GET['origen'];
	if (isset($_GET['un']) && $_GET['un']!='') $sql.=" AND tabla.proveedor=".$_GET['un'];
	
	 $sql.=" GROUP BY tabla.tipo, tabla.id_tipo_producto, tabla.tienda, tabla.id_tienda, tabla.proveedor  ORDER BY  tabla.tipo, tabla.proveedor, tabla.tienda  ";
  $c=0;
  $c2=0;
  $c3=0;
  $c4=0;
  $c5=0;
  $c6=0;
  $st=0;
  $d=0;
  $titulo="";
  $res=abredatabase(g_BaseDatos,$sql);
  while ($row=dregistro($res)){
	$c+=1;  
	if ($titulo!=$row['tipo']){
		
		$st+=1;
		if ($st==2){ $st=1;?>
			 <tr>
				<td colspan="2" style="text-align:left" >TOTALES </td>
				<td style="text-align:center"><?php echo $c2; ?></td>
				<td style="text-align:center"><?php echo $c3; ?></td>
				<td style="text-align:center"><?php echo $c4; ?></td>
				<td style="text-align:center"><?php echo $c5; ?></td>
				<td style="text-align:center"><?php echo $c6; ?></td>
			</tr>
		
		<?php
		$d=0;
		$c=0;
		$c2=0;
		$c3=0;
		$c4=0;
		$c5=0;
		$c6=0;}
		echo "<tr><td colspan='7' style='font-size:20px;'><strong>".$row['tipo']."</strong></td></tr>"; 
		$titulo=$row['tipo'];
		
	}else{
	?>
  <tr>
    <td style="text-align:left" ><?php echo $d+=1; ?> </td>
    <td style="text-align:left" ><?php echo $row['tienda']; ?> </td>
    <td style="text-align:center"><?php echo $row['total']; $c2+=$row['total']; ?></td>
    <td style="text-align:center"><?php echo $row['entradas']; $c3+=$row['entradas']; ?></td>
    <td style="text-align:center"><?php echo $row['salidas']; $c4+=$row['salidas']; ?></td>
    <td style="text-align:center"><?php echo ($row['entradas']+$row['salidas']); $c5+=($row['entradas']+$row['salidas']); ?></td>
    <td style="text-align:center"><?php echo ($row['entradas']+$row['salidas'])-$row['total']; $c6+=($row['entradas']+$row['salidas'])-$row['total']; ?></td>
  </tr>
	<?php } } 
  if ($st==1){?>
   <tr>
				<td colspan="2" style="text-align:left" >TOTALES </td>
				<td style="text-align:center"><?php echo $c2; ?></td>
				<td style="text-align:center"><?php echo $c3; ?></td>
				<td style="text-align:center"><?php echo $c4; ?></td>
				<td style="text-align:center"><?php echo $c5; ?></td>
				<td style="text-align:center"><?php echo $c6; ?></td>
			</tr>
  <?php } ?>
  </tbody>
</table>

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