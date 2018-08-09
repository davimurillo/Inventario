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
	
	  
	   <!-- Main Style -->
   
	
	

</head>

<body>
<div style="margin-left:10px; margin-right:10px">

<div id="dvData" style="margin-top:50px">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<div id="print" align="left" >
		<div><img src="../repositorio/logos_cintillos/<?php echo $row['tx_logo']; ?>"  ></div>
		<h3><?php echo $_GET['reporte']; ?></h3><hr>
		<div align="LEFT" style="font-size:14px; margin-left:10px; margin-bottom:10px ">
			AL <?php echo date('d/m/Y'); ?>
		</div>
	</div>

	<div style="margin-top:30px"> LISTADO DE EQUIPOS SEGUN TIPO DE COMPRA 
		<table class="mitabla table table-bordered table-striped" style="font-size:12px; color:#000; width:100%" >
		  <thead>
		  <tr style="background-color:#69add8">
			<th width="5%" style="text-align:center" >Nª</th>
			<th width="5%" style="text-align:center" >UN</th>
			<th width="5%" style="text-align:center" >PROVEEDOR</th>
			<th width="5%" style="text-align:center" >COTIZACION</th>
			<th width="5%" style="text-align:center" >GUIA DE REMISION</th>
			<th width="5%" style="text-align:center" >FECHA DE INGRESO</th>
			<th width="5%" style="text-align:center" >GARANTIA</th>
			<th width="5%" style="text-align:center" >FECHA DE VENCIMIENTO</th>
			<th width="5%" style="text-align:center" >TIPO</th>
			<th width="5%" style="text-align:center" >MARCA</th>
			<th width="5%" style="text-align:center" >MODELO</th>
			<th width="5%" style="text-align:center" >SERIAL</th>
			<th width="5%" style="text-align:center" >TIPO DE COMPRA</th>
			<th width="10%" style="text-align:center" >N° LEASING / O.C. / PAGARE</th>
			<th width="20%" style="text-align:center" >ULTIMA UBICACION</th>
			<th width="5%" style="text-align:center" >ESTATUS</th>
		  </tr>
		  </thead>
		  <tbody>
		  <?php 
			$e=0;
			$sql="SELECT id_producto, tx_serial, fe_ingreso, tx_nu_cotizacion, tx_nu_motivo, tx_guia_remision, tx_descripcion, tx_ngr, fe_vencimiento, (SELECT tx_nombre_tipo FROM cfg_tipo_producto WHERE id_tipo_producto=a.id_tipo_producto) as tx_tipo_equipo, (SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=a.id_garantia) as tx_garantia, (SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=a.id_tipo_motivo) as tx_motivo, (SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=a.estatus) as tx_estatus, (SELECT tx_nombre FROM mod_proveedor WHERE id_proveedor=a.id_proveedor) as proveedor,  (SELECT tx_marca FROM cfg_tipo_marcas WHERE id_marca=a.id_tipo_marca) as tx_marca, tx_modelo, tx_placati, (SELECT tx_descripcion FROM mod_tienda WHERE id_tienda=a.id_tienda) as tienda, (SELECT tx_abreviatura FROM mod_empresa WHERE id_empresa=a.id_unidad_negocio) as un  FROM mod_producto a WHERE id_tipo_motivo=28 ";
		  
			if (isset($_GET['motivo']) && $_GET['motivo']!='') $sql.=" AND a.id_tipo_motivo=".$_GET['motivo'];
			if (isset($_GET['tipo']) && $_GET['tipo']!='') $sql.=" AND a.id_tipo_producto='".$_GET['tipo']."'";
			if (isset($_GET['proveedor']) && $_GET['proveedor']!='') $sql.=" AND a.id_proveedor='".$_GET['proveedor']."'";
			if (isset($_GET['origen']) && $_GET['origen']!='') $sql.=" AND a.id_tienda='".$_GET['origen']."'";
			if (isset($_GET['un']) && $_GET['un']!='') $sql.=" AND a.id_unidad_negocio='".$_GET['un']."'";		
			if (isset($_GET['nmotivo']) && $_GET['nmotivo']!='') $sql.=" AND a.tx_nu_motivo='".$_GET['nmotivo']."'";					
			
		 $sql.=" ORDER BY tienda ";
		  $c=0;
		  $res=abredatabase(g_BaseDatos,$sql);
		  while ($row=dregistro($res)){
			$c+=1;  
		  $sql_movimiento="SELECT  id_tipo_movimiento, movimiento, nombre_origen_destino,(SELECT CONCAT(tx_descripcion, ' ', tx_apellido_paterno, ' ', tx_apellido_materno, ' ', tx_puesto, ' ' , tx_dni) FROM mod_movimiento, mod_responsable_destino WHERE vie_historial_equipo.id_movimiento=id_movimiento and id_responsable_destino=mod_responsable_destino.id_tienda) as responsable,  (SELECT CONCAT(tx_descripcion, ' ', tx_apellido_paterno, ' ', tx_apellido_materno, ' ', tx_puesto, ' ' , tx_dni) FROM mod_movimiento, mod_responsable_destino WHERE vie_historial_equipo.id_movimiento=id_movimiento and id_responsable_destino=mod_responsable_destino.id_tienda) as responsable, (SELECT fe_actualizada FROM mod_movimiento WHERE vie_historial_equipo.id_movimiento=id_movimiento) as fe_actualizada FROM vie_historial_equipo  WHERE id_producto=".$row['id_producto']."  ORDER BY fe_movimiento DESC, fe_actualizada DESC LIMIT 1";
			$res_movimiento=abredatabase(g_BaseDatos,$sql_movimiento);
			$row2=dregistro($res_movimiento);
			?>
		  <tr>
			<td style="text-align:left" ><?php echo $c; ?> </td>
			<td style="text-align:left" ><?php echo $row['un']; ?> </td>
			<td style="text-align:left" ><?php echo $row['proveedor']; ?> </td>
			<td style="text-align:left" ><?php echo $row['tx_nu_cotizacion']; ?> </td>
			<td style="text-align:left" ><?php echo $row['tx_guia_remision']; ?> </td>
			<td style="text-align:left" ><?php echo $row['fe_ingreso']; ?> </td>
			<td style="text-align:left" ><?php echo $row['tx_garantia']; ?> </td>
			<td style="text-align:left" ><?php echo $row['fe_vencimiento']; ?> </td>
			<td style="text-align:left" ><?php echo $row['tx_tipo_equipo']; ?> </td>
			<td style="text-align:left" ><?php echo $row['tx_marca'] ?> </td>
			<td style="text-align:left" ><?php echo $row['tx_modelo']; ?> </td>
			<td style="text-align:left" ><?php echo $row['tx_serial']; ?> </td>
			<td style="text-align:center"><?php echo $row['tx_motivo']; ?></td>
			<td style="text-align:center"><?php echo $row['tx_nu_motivo']; ?></td>
			<td style="text-align:center"><?php echo !$row2['id_tipo_movimiento']? 'UBICACIÓN DESCONOCIDA' : $row2['id_tipo_movimiento']==1? $row['tienda'] : $row2['nombre_origen_destino']."<br>".$row2['responsable']; ?></td>
			
			<td style="text-align:center"><?php echo $row['tx_estatus']; ?></td>
		  </tr>
		  <?php } ?>
		  </tbody>
		</table>
	</div>

</div>

	
</div>


</body>
</html>