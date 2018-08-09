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

<div style="margin-top:30px"> RESUMEN DE PLACAS TI </div>
<table class="mitabla table table-bordered table-striped" style="font-size:12px; color:#000; width:100%" >
  <thead>
  <tr style="background-color:#999">
	<th width="5%" style="text-align:center" >N°</th>
	<th width="10%" style="text-align:center" >PLACA TI</th>
	<th width="5%" style="text-align:center" >SERIAL</th>
	<th width="10%" style="text-align:center" >TIPO</th>
	<th width="10%" style="text-align:center" >MARCA</th>
	<th width="10%" style="text-align:center" >MODELO</th>
	<th width="40%" style="text-align:center" >UBICACION</th>
	<th width="5%" style="text-align:center" >ESTATUS</th>
	<th width="5%" style="text-align:center" >REPETIDO</th>
  </tr>
  </thead>
  <tbody>
  <?php 
	
    $sql="SELECT tx_placati, count(tx_placati) as n_veces FROM mod_producto WHERE tx_placati is not null and tx_placati NOT IN ('N/T', 'N/A', '0') GROUP BY tx_placati ORDER BY tx_placati ASC";
  
  $c=0;
  $n=0;
  $n_guia_f="000000";
  $res=abredatabase(g_BaseDatos,$sql);
  while ($row=dregistro($res)){
	  $sql2="SELECT (SELECT tx_nombre_tipo FROM cfg_tipo_producto WHERE a.id_tipo_producto=id_tipo_producto) as tipo,
		     (SELECT tx_marca FROM cfg_tipo_marcas WHERE a.id_tipo_marca=id_marca) as marca, tx_serial, tx_modelo, 
			 (SELECT CONCAT(tx_descripcion, ' ', tx_apellido_paterno, ' ', tx_apellido_materno, ' ', tx_puesto, ' ' , tx_dni) FROM mod_tienda WHERE a.id_tienda=id_tienda) as tienda
			 FROM mod_producto a WHERE tx_placati='".$row['tx_placati']."'";
		 $res2=abredatabase(g_BaseDatos,$sql2);
	$row2=dregistro($res2);
	$n+=1;
	$guia=intval($row['tx_placati']);

	if ($guia==$n){
	?>
  <tr>
    <td style="text-align:left" ><?php echo $n; ?> </td>
    <td style="text-align:left" ><?php echo $row['tx_placati']; ?> </td> 
    <td style="text-align:left" ><?php echo $row2['tx_serial']; ?> </td> 
    <td style="text-align:left" ><?php echo $row2['tipo']; ?> </td> 
    <td style="text-align:left" ><?php echo $row2['marca']; ?> </td> 
    <td style="text-align:left" ><?php echo $row2['tx_modelo']; ?> </td> 
    <td style="text-align:left" ><?php echo $row2['tienda']; ?> </td> 
	<td style="text-align:left" ><?php echo "REGISTRADA" ?></td>  
	<td style="text-align:left" ><?php echo $row['n_veces']==1? 'NO' : 'SI'; ?> </td>  
  </tr>
	<?php 
	}else{ 
	
	
	for ($i=$n; $i<=$guia-1; $i++){
		$n_guia_t= substr($n_guia_f,1,strlen($n_guia_f)-strlen($n)).$n;
	?>
	<tr>
		<td style="text-align:left" ><?php  echo $i; $n+=1; ?> </td>
		<td style="text-align:left" colspan="6"><?php echo $n_guia_t; ?> </td> 
		<td style="text-align:left" >FALTA </td>  
		<td style="text-align:left" >NO </td>  
	</tr>
	<?php } ?>
  <tr>
    <td style="text-align:left" ><?php echo $n; ?> </td>
    <td style="text-align:left" ><?php echo $row['tx_placati']; ?> </td> 
    <td style="text-align:left" ><?php echo $row2['tx_serial']; ?> </td> 
    <td style="text-align:left" ><?php echo $row2['tipo']; ?> </td> 
    <td style="text-align:left" ><?php echo $row2['marca']; ?> </td> 
	 <td style="text-align:left" ><?php echo $row2['tx_modelo']; ?> </td> 
    <td style="text-align:left" ><?php echo $row2['tienda']; ?> </td> 
	<td style="text-align:left" ><?php echo "REGISTRADA" ?></td>
	<td style="text-align:left" ><?php echo $row['n_veces']==1? 'NO' : 'SI'; ?> </td>  
  </tr>
  <?php } }?>
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