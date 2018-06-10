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
<h3>INVENTARIO POR TIENDAS</h3><hr>
<div class="col-lg-12" align="LEFT" style="font-size:14px; margin-left:10px; margin-bottom:10px;  ">AL <?php echo date('d/m/Y'); ?></div>
</div>
<div class="col-lg-12" >

<table class="table table-bordered table-striped" style="font-size:12px; color:#000; width:100%" >
								<tr style="background-color:#999">
									<th width="70%" style="text-align:center" >UBICACION</th>
									<th width="20%" style="text-align:center" >TOTAL</th>
									<th width="10%" style="text-align:center" >%</th>
								</tr>
							<?php 
							
								$sql="SELECT CASE WHEN id_tienda=1 THEN '1. ALMACEN' WHEN id_tienda>1 THEN '2. TIENDAS' ELSE '3. OTRAS' END AS ubicacion , count(id_producto) as existencias, (SELECT count(id_producto) FROM mod_producto) as total FROM mod_producto ";
								

								if ($_GET['origen']>0){
									$sql.=" WHERE id_tienda=".$_GET['origen']."";

								}
								$sql.=" group by  ubicacion ORDER BY ubicacion"; 
								$c=0;
								$res=abredatabase(g_BaseDatos,$sql);
								while ($row=dregistro($res)){
									$c+=$row['existencias'];  
							?>
								<tr>
									<td style="text-align:left" ><?php echo $row['ubicacion']; ?> </td>
									<td style="text-align:center"><?php echo $row['existencias']; ?></td>
									<td style="text-align:center"><?php echo number_format(($row['existencias']/$row['total'])*100,2); ?></td>
								</tr>
							<?php } ?>
								  <tr>
									<td >
										TOTAL
									</td>
									<td align="center">
										<?php echo $c; ?>
									</td>
									<td >
										
									</td>
								  </tr>
							</table>
							
<table class="table table-bordered table-striped" style="font-size:12px; color:#000; width:50% " >
  
  <tr style="background-color:#999">
    <th width="80%" style="text-align:center" >UBICACION</th>
    <th width="20%" style="text-align:center">EXISTENCIA</th>
    <th width="20%" style="text-align:center">TOTAL EQUIPOS</th>
  </tr>
  
 
  <?php 
	$c=1;
    $sql="SELECT (b.tx_descripcion) as tienda, SUM(n_stock) AS existencia, count(id_producto) AS n_productos FROM mod_producto a, mod_tienda b WHERE a.id_tienda=b.id_tienda  ";


if ($_GET['guia']>0){
	$sql.=" AND tx_guia='".$_GET['guia']."'";

}

if ($_GET['origen']>0){
	$sql.=" AND a.id_tienda=".$_GET['origen']."";

}


$sql.=" GROUP BY tienda ORDER BY tienda";
  
  $c=0;
  $c2=0;
  $res=abredatabase(g_BaseDatos,$sql);
  while ($row=dregistro($res)){
	 
	?>
  <tr>
    <td style="text-align:left" ><?php echo $row['tienda']; ?> </td>
    <td style="text-align:center"><?php $c+=$row['existencia']; echo $row['existencia']; ?></td>
    <td style="text-align:center"><?php $c2+=$row['n_productos']; echo $row['n_productos']; ?></td>
    
  </tr>
  <?php  } ?>
   <tr>
    <td style="text-align:left" >TOTAL </td>
    <td style="text-align:center"><?php echo $c; ?></td>
    <td style="text-align:center"><?php echo $c2; ?></td>
    
  </tr>
</table>
</div>
</div>
</div>


</body>
</html>