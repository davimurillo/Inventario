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
		<h3><?php echo $_GET['reporte']; ?></h3><hr>
		<div align="LEFT" style="font-size:14px; margin-left:10px; margin-bottom:10px ">
			AL <?php echo date('d/m/Y'); ?>
		</div>
	</div>

	<div style="margin-top:30px"> STOCK EN ALMACEN 
		<table class="mitabla table table-bordered table-striped" style="font-size:12px; color:#000; width:100%" >
		  <thead>
		  <tr style="background-color:#69add8">
			<th width="80%" style="text-align:center" >TIPO DE EQUIPO Y/O ACCESORIO</th>
			<th width="10%" style="text-align:center" >N° DE EQUIPO</th>
			<th width="10%" style="text-align:center" >STOCK</th>
		  </tr>
		  </thead>
		  <tbody>
		  <?php 
			$e=0;
			$sql="SELECT b.tx_nombre_tipo, COUNT(id_producto) AS n_equipos,  sum(n_stock) as existencias, (nx_min) as min, nx_orden FROM mod_producto a, cfg_tipo_producto b WHERE b.id_tipo_producto=a.id_tipo_producto AND ultimo_movimiento=1 ANd estatus=5 ";
		  
			if (isset($_GET['tipo']) && $_GET['tipo']!='') $sql.=" AND b.id_tipo_producto='".$_GET['tipo']."'";
			if (isset($_GET['proveedor']) && $_GET['proveedor']!='') $sql.=" AND a.id_proveedor='".$_GET['proveedor']."'";
			if (isset($_GET['origen']) && $_GET['origen']!='') $sql.=" AND a.id_tienda='".$_GET['origen']."'";
			if (isset($_GET['un']) && $_GET['un']!='') $sql.=" AND id_tienda=(SELECT id_tienda FROM mod_tienda b, mod_empresa c WHERE b.id_empresa=c.id_empresa AND c.id_empresa=".$_GET['un']." LIMIT 1)";
			
			$sql.=" GROUP BY b.tx_nombre_tipo, nx_min, nx_orden ORDER BY b.tx_nombre_tipo";
		  $c=0;
		  $res=abredatabase(g_BaseDatos,$sql);
		  while ($row=dregistro($res)){
			$c+=1;  
			?>
		  <tr>
			<td style="text-align:left" ><?php echo $row['tx_nombre_tipo']; ?> </td>
			<td style="text-align:center"><?php echo $row['n_equipos']; $e+=$row['n_equipos']; ?></td>
			<td style="text-align:center"><?php echo $row['existencias']; ?></td>
		  </tr>
		  <?php } ?>
		  </tbody>
		</table>
	</div>
	
	<div style="margin-top:30px"> STOCK EN TIENDAS O AREAS 
		<table class="mitabla2 table table-bordered table-striped" style="font-size:12px; color:#000; width:100%" >
		  <thead>
		  <tr style="background-color:#69add8">
			<th width="80%" style="text-align:center" >TIPO DE EQUIPO Y/O ACCESORIO</th>
			<th width="10%" style="text-align:center" >N° DE EQUIPO</th>
			<th width="10%" style="text-align:center" >STOCK</th>
		  </tr>
		  </thead>
		  <tbody>
		  <?php 
			
			$sql="SELECT b.tx_nombre_tipo, COUNT(id_producto) AS n_equipos,sum(n_stock) as existencias, (nx_min) as min, nx_orden FROM mod_producto a, cfg_tipo_producto b WHERE b.id_tipo_producto=a.id_tipo_producto AND id_tienda!=1    ";
		  
			if (isset($_GET['tipo']) && $_GET['tipo']!='') $sql.=" AND b.id_tipo_producto='".$_GET['tipo']."'";
			if (isset($_GET['proveedor']) && $_GET['proveedor']!='') $sql.=" AND a.id_proveedor='".$_GET['proveedor']."'";
			if (isset($_GET['origen']) && $_GET['origen']!='') $sql.=" AND a.id_tienda='".$_GET['origen']."'";
			if (isset($_GET['un']) && $_GET['un']!='') $sql.=" AND id_tienda=(SELECT id_tienda FROM mod_tienda b, mod_empresa c WHERE b.id_empresa=c.id_empresa AND c.id_empresa=".$_GET['un']." LIMIT 1)";
			
			$sql.=" GROUP BY b.tx_nombre_tipo, nx_min, nx_orden ORDER BY b.tx_nombre_tipo";
		  $c=0;
		  $res=abredatabase(g_BaseDatos,$sql);
		  while ($row=dregistro($res)){
			$c+=1;  
			?>
		  <tr>
			<td style="text-align:left" ><?php echo $row['tx_nombre_tipo']; ?> </td>
			<td style="text-align:center"><?php echo $row['n_equipos']; $e+=$row['n_equipos']; ?></td>
			<td style="text-align:center"><?php echo $row['existencias']; ?></td>
		  </tr>
		  <?php } ?>
		  </tbody>
		</table>
	</div>
	
	<div style="margin-top:30px"> STOCK EN UBICACIONES DESCONOCIDAS 
		<table class="mitabla2 table table-bordered table-striped" style="font-size:12px; color:#000; width:100%" >
		  <thead>
		  <tr style="background-color:#69add8">
			<th width="80%" style="text-align:center" >TIPO DE EQUIPO Y/O ACCESORIO</th>
			<th width="10%" style="text-align:center" >N° DE EQUIPO</th>
			<th width="10%" style="text-align:center" >STOCK</th>
		  </tr>
		  </thead>
		  <tbody>
		  <?php 
			
			$sql="SELECT b.tx_nombre_tipo, COUNT(id_producto) AS n_equipos, sum(n_stock) as existencias, (nx_min) as min, nx_orden FROM mod_producto a, cfg_tipo_producto b WHERE b.id_tipo_producto=a.id_tipo_producto AND id_tienda IS NULL    ";
		  
			if (isset($_GET['tipo']) && $_GET['tipo']!='') $sql.=" AND b.id_tipo_producto='".$_GET['tipo']."'";
			if (isset($_GET['proveedor']) && $_GET['proveedor']!='') $sql.=" AND a.id_proveedor='".$_GET['proveedor']."'";
			if (isset($_GET['origen']) && $_GET['origen']!='') $sql.=" AND a.id_tienda='".$_GET['origen']."'";
			if (isset($_GET['un']) && $_GET['un']!='') $sql.=" AND id_tienda=(SELECT id_tienda FROM mod_tienda b, mod_empresa c WHERE b.id_empresa=c.id_empresa AND c.id_empresa=".$_GET['un']." LIMIT 1)";
			
			$sql.=" GROUP BY b.tx_nombre_tipo, nx_min, nx_orden ORDER BY b.tx_nombre_tipo";
		  $c=0;
		  $res=abredatabase(g_BaseDatos,$sql);
		  while ($row=dregistro($res)){
			$c+=1;  
			?>
		  <tr>
			<td style="text-align:left" ><?php echo $row['tx_nombre_tipo']; ?> </td>
			<td style="text-align:center"><?php echo $row['n_equipos']; $e+=$row['n_equipos']; ?></td>
			<td style="text-align:center"><?php echo $row['existencias']; ?></td>
		  </tr>
		  <?php } ?>
		  </tbody>
		</table>
	</div>

	<div style="margin-top:30px"> </div>
		<table class="mitabla2 table table-bordered table-striped" style="font-size:12px; color:#000; width:100%" >
			  <tr style="background-color:#69add8">
				<th width="80%" style="text-align:center" >TOTAL</th>
				<th width="10%" style="text-align:center" ><?php echo $e; ?></th>
				<th width="10%" style="text-align:center" ></th>
			  </tr>
		 </table>
	</div>
</div>

	
</div>
<script type="text/javascript" src="../../lib/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../lib/js/jquery.thead-1.1.min.js"></script>

</body>
</html>