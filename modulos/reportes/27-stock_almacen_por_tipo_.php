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
<h3>STOCK DE INVENTARIO</h3><hr>
<div align="LEFT" style="font-size:14px; margin-left:10px; margin-bottom:10px ">AL <?php echo date('d/m/Y'); ?></div>
</div>

<div style="margin-top:30px"> Stock en Almacen </div>
<table class="mitabla table table-bordered table-striped" style="font-size:12px; color:#000; width:100%" >
  <thead>
  <tr style="background-color:#999">
	<th width="5%" style="text-align:center" >N°</th>
	<th width="30%" style="text-align:center" >UBICACION</th>
	<th width="30%" style="text-align:center" >TIPO</th>
	<th width="15%" style="text-align:center" >MARCA</th>
    <th width="25%" style="text-align:center" >MODELO</th>
	<th width="15%" style="text-align:center" >SERIAL</th>
	<th width="15%" style="text-align:center" >N° PLACA TI</th>
	<th width="15%" style="text-align:center" >N° PLACA NGR</th>
    <th width="10%" style="text-align:center" >STOCK</th>
  </tr>
  </thead>
  <tbody>
  <?php 
	//(b.tx_descripcion || ' ' || d.tx_descripcion || ' ' || d.tx_apellido_paterno || ' ' || d.tx_apellido_materno || ' ' || d.tx_puesto || ' ' || d.tx_dni)
    $sql="SELECT id_producto,
	(SELECT  tx_guia  FROM mod_movimiento c, 
c.id_producto=a.id_producto ORDER BY c.fe_movimiento DESC, c.fe_actualizada DESC LIMIT 1) as descripcion,
    n_stock,
    tx_serial,
    tx_placati,
    tx_ngr,
	(SELECT tx_descripcion FROM mod_tienda WHERE a.id_tienda=id_tienda) as tx_tienda,
    (SELECT tx_marca FROM cfg_tipo_marcas WHERE a.id_tipo_marca=id_marca) as tx_marca,
    tx_modelo,
    (SELECT tx_nombre_tipo FROM cfg_tipo_producto WHERE id_tipo_producto=a.id_tipo_producto) as 

tx_tipo_producto, a.id_tienda  
	FROM mod_producto a
	  ";
  
	if (isset($_GET['tipo']) && $_GET['tipo']!='') $sql.=" AND id_tipo_producto=".$_GET['tipo']."";
	if ($_GET['tienda']=='1') $sql.=" WHERE id_tienda=1";
	if ($_GET['tienda']=='2') $sql.=" WHERE (id_tienda!=1 AND id_tienda!=579)";
	if ($_GET['tienda']=='3') $sql.=" WHERE id_tienda=579";
	echo $sql;
	$sql." LIMIT 500"

  $c=0;
  $res=abredatabase(g_BaseDatos,$sql);
  while ($row=dregistro($res)){
	$c+=1;  
	?>
  <tr>
    <td style="text-align:left" ><?php echo $c; ?> </td>
    <td style="text-align:left" ><?php echo $row['descripcion']; ?> </td>
    <td style="text-align:left" ><?php echo $row['tx_tipo_producto']; ?> </td>
    <td style="text-align:left" ><?php echo $row['tx_marca']; ?> </td>
    <td style="text-align:left" ><?php echo $row['tx_modelo']; ?> </td>
	<td style="text-align:left" ><?php echo $row['tx_serial']; ?> </td>
	<td style="text-align:left" ><?php echo $row['tx_placati']; ?> </td>
	<td style="text-align:left" ><?php echo $row['tx_ngr']; ?> </td>
    <td style="text-align:center"><?php echo $row['n_stock']; ?></td>
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