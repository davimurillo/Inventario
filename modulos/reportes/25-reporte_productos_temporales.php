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

	<div style="margin-top:30px"> LISTADO DE CARGA DE EQUIPOS EN TIENDAS 
		<table class="mitabla table table-bordered table-striped" style="font-size:12px; color:#000; width:100%" >
		  <thead>
		  <tr style="background-color:#69add8">
			<th width="10%" style="text-align:center" >ID</th>
			<th width="10%" style="text-align:center" >FECHA</th>
			<th width="10%" style="text-align:center" >TIPO</th>
			<th width="10%" style="text-align:center" >MARCA</th>
			<th width="10%" style="text-align:center" >MODELO</th>
			<th width="10%" style="text-align:center" >SERIAL</th>
			<th width="10%" style="text-align:center" >PLACA NGR</th>
			<th width="10%" style="text-align:center" >PLACA TI</th>
			<th width="10%" style="text-align:center" >TIENDA</th>
			<th width="10%" style="text-align:center" >ESTATUS</th>
		  </tr>
		  </thead>
		  <tbody>
		  <?php 
			$e=0;
			$sql="SELECT id_producto, tx_serial, fe_ingreso, tx_descripcion, tx_ngr, (SELECT tx_nombre_tipo FROM cfg_tipo_producto WHERE id_tipo_producto=a.id_tipo_producto) as tx_tipo_equipo,  (SELECT tx_marca FROM cfg_tipo_marcas WHERE id_marca=a.id_tipo_marca) as tx_marca, tx_modelo, tx_placati, (SELECT tx_descripcion FROM mod_tienda WHERE id_tienda=a.id_tienda) as tienda, tx_archivo, CASE WHEN id_tabla_producto=0 THEN 'Nuevo' ELSE 'Existe' END AS producto  FROM mod_producto_temp a ";
		  
			if (isset($_GET['tipo']) && $_GET['tipo']!='') $sql.=" AND b.id_tipo_producto='".$_GET['tipo']."'";
			if (isset($_GET['proveedor']) && $_GET['proveedor']!='') $sql.=" AND a.id_proveedor='".$_GET['proveedor']."'";
			if (isset($_GET['origen']) && $_GET['origen']!='') $sql.=" AND a.id_tienda='".$_GET['origen']."'";
			if (isset($_GET['un']) && $_GET['un']!='') $sql.=" AND id_tienda=(SELECT id_tienda FROM mod_tienda b, mod_empresa c WHERE b.id_empresa=c.id_empresa AND c.id_empresa=".$_GET['un']." LIMIT 1)";
			
			$sql.=" ORDER BY tienda ";
		  $c=0;
		  $res=abredatabase(g_BaseDatos,$sql);
		  while ($row=dregistro($res)){
			$c+=1;  
			?>
		  <tr>
			<td style="text-align:left" ><?php echo $row['id_producto']; ?> </td>
			<td style="text-align:left" ><?php echo $row['fe_ingreso']; ?> </td>
			<td style="text-align:left" ><?php echo $row['tx_tipo_equipo']; ?> </td>
			<td style="text-align:left" ><?php echo $row['tx_marca']; ?> </td>
			<td style="text-align:left" ><?php echo $row['tx_modelo']; ?> </td>
			<td style="text-align:left" ><?php echo $row['tx_serial']; ?> </td>
			<td style="text-align:left" ><?php echo $row['tx_ngr']; ?> </td>
			<td style="text-align:left" ><?php echo $row['tx_placati']; ?> </td>
			<td style="text-align:center"><?php echo $row['tienda']; ?></td>
			<td style="text-align:center"><?php echo $row['producto']; ?></td>
		  </tr>
		  <?php } ?>
		  </tbody>
		</table>
	</div>

</div>

	
</div>
<script type="text/javascript" src="../../lib/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../lib/js/jquery.thead-1.1.min.js"></script>

</body>
</html>