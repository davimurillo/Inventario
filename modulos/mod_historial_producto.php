<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>APICES|Control de Inventarios</title>
  <link href="../img/logos/apices.png" rel="shortcut icon" type="image/x-icon" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <style>
	.titulos{
		background-color: #ccc;
		color:#000;
	}
  </style>
</head>
<body>
<?php
require_once('common.php'); checkUser();
  $sql="SELECT id_producto,  movimiento, fe_movimiento, nombre_origen_destino FROM vie_historial_equipo WHERE id_producto=".$_POST['id_producto'];
  $res=abredatabase(g_BaseDatos,$sql);
  $n_numero=dnumerofilas($res);
  $row2=dregistro($res);
  
    $sql_inventario="SELECT fe_vencimiento, (SELECT tx_logo FROM mod_empresa WHERE id_empresa=a.id_unidad_negocio) as logo, TO_CHAR(fe_ingreso,'DD/MM/YYYY') AS fe_ingreso, tx_nombre, tx_tipo_producto, tx_estatus, tx_descripcion, tx_marca, tx_modelo, tx_accesorios, tx_tipo_motivo, tx_nu_motivo, tx_nu_cotizacion, tx_guia_remision, tx_garantia, tx_ngr, tx_placati, tx_serial, costo, (SELECT n_stock FROM mod_producto WHERE id_producto=a.id_producto) as n_stock  FROM vie_tbl_equipos a WHERE id_producto=".$_POST['id_producto'];
  $res_inventario=abredatabase(g_BaseDatos,$sql_inventario);
  $row_inventario=dregistro($res_inventario);
  $ubicacion='ALMACEN';
  

?>
<div class="container-fluid">
<div class="col-xs-12">
<table class="table table-bordered" style="font-size:12px; color:#000">
<tr class="titulos">
	<th width="10%">UN</th>
	<th width="10%">TIPO</th>
	<th width="20%">MARCA</th>
	<th width="20%">MODELO</th>
	<th width="15%">SERIAL</th>
	<th width="15%">N° PLACA (NGR)</th>
	<th width="20%">N° PLACA (TI)</th>
</tr>
<tr>
	<td><img src="repositorio/logos/<?php echo $row_inventario['logo']; ?>"  style="width:55%"/></td>
	<td><?php echo $row_inventario['tx_tipo_producto']; ?></td>
	<td><?php echo $row_inventario['tx_marca']; ?></td>
	<td><?php echo $row_inventario['tx_modelo']; ?></td>
	<td><?php echo $row_inventario['tx_serial']; ?></td>
	<td><?php echo $row_inventario['tx_ngr']; ?></td>
	<td><?php echo $row_inventario['tx_placati']; ?></td>
</tr>
<tr class="titulos">
	<th colspan=2 >FECHA DE INGRESO</th>
	<th>FECHA VENCIMIENTO GARANTIA</th>
	<th>TIPO DE COMPRA</th>
	<th>GUIA DE INGRESO</th>
	<th>PROVEEDOR</th>
	<th>COSTO (S/. - $)</th>
</tr>
<tr>
	<td colspan=2 ><?php echo $row_inventario['fe_ingreso']; ?></td>
	<td><?php echo $row_inventario['fe_vencimiento']." (".$row_inventario['tx_garantia'].")"; ?></td>
	<td><?php echo $row_inventario['tx_tipo_motivo']." N°:".$row_inventario['tx_nu_motivo']; ?></td>
	<td><?php echo $row_inventario['tx_guia_remision']; ?></td>
	<td><?php echo $row_inventario['tx_nombre']; ?></td>
	<td><?php echo $row_inventario['costo']; ?></td>
</tr>
<tr class="titulos">
	<th colspan="4">ULTIMA UBICACIÓN</th>
	<th>ESTATUS</th>
	<th>STOCK</th>
	<th>MOVIMIENTO(S)</th>
</tr>
<tr >
	<td colspan="4"><span id="ubicacion_actual"><?php echo $ubicacion; ?></span></td>
	<td><?php echo $row_inventario['tx_estatus']; ?></td>
	<td><?php echo $row_inventario['n_stock']; ?></td>
	<td><?php echo $n_numero; ?></span></td>
</tr>
</div>

<table id="resultado_busqueda" class="table table-bordered table-hover" style="margin-top:40px; font-size:10px; color:#000">
                <thead>
                <tr style="background-color:#ccc">
                  
                  <th>FECHA</th>
				  <th>TICKET</th>
				  <th>GUIA</th>
				  <th>ORIGEN O DESTINO</th>
				  <th>ACCESORIOS</th>
				  <th>CANTIDAD</th>
                  <th>MOVIMIENTO</th>
                  <th>ESTATUS</th>
                  
                </tr>
                </thead>
                <tbody>
				<?php 
				  $sql="SELECT id_producto, nx_cantidad, tx_accesorios, tx_descripcion, movimiento, (SELECT tx_guia FROM mod_movimiento WHERE vie_historial_equipo.id_movimiento=id_movimiento) as tx_guia, (SELECT CASE WHEN id_estatus_movimiento=1 THEN 'ACTIVA' ELSE 'ANULADA' END AS MOV FROM mod_movimiento WHERE vie_historial_equipo.id_movimiento=id_movimiento) as estatus, (SELECT tx_ticket FROM mod_movimiento WHERE vie_historial_equipo.id_movimiento=id_movimiento) as tx_ticket,  to_char(vie_historial_equipo.fe_movimiento::timestamp with time zone, 'DD-Mon-YYYY'::text) AS fe_movimiento2, nombre_origen_destino, (SELECT fe_actualizada FROM mod_movimiento WHERE vie_historial_equipo.id_movimiento=id_movimiento) as fe_actualizada,( SELECT CONCAT(d.tx_descripcion || ' ' || d.tx_apellido_paterno || ' ' || d.tx_apellido_materno || ' ' || d.tx_puesto || ' ' || d.tx_dni ) FROM  mod_responsable_destino d, mod_movimiento g WHERE vie_historial_equipo.id_movimiento=g.id_movimiento and g.id_responsable_destino=d.id_tienda ) as responsable FROM vie_historial_equipo WHERE id_producto=".$_POST['id_producto']." ORDER BY fe_movimiento DESC, fe_actualizada DESC";
				$res=abredatabase(g_BaseDatos,$sql);
				$c=0;
				if (dnumerofilas($res)>0){
				while($row2=dregistro($res)){ 
				if ($c==0) { $c=1; if (trim($row2['movimiento'])!='ENTRADA') {  $ubicacion=$row2['nombre_origen_destino']."<br>".$row2['responsable'];  }}
				?>
                <tr>
				  <td><?php echo $row2['fe_movimiento2']; ?></td>
                  <td><?php echo $row2['tx_ticket']; ?></td>
                  <td><?php echo $row2['tx_guia']; ?></td>
                  <td><?php echo $row2['nombre_origen_destino']."<br>".$row2['responsable']; ?></td>
                  <td ><?php echo $row2['tx_accesorios']; ?></td>
                  <td align="center"><?php echo $row2['nx_cantidad']; ?></td>
                  <td><?php echo $row2['movimiento']; ?></td>
                  <td><?php echo $row2['estatus']; ?></td>
                  
                </tr>
				<?php } }cierradatabase(); ?>
				</tbody>
	</table>
</div>
</body>
<!-- jQuery 2.2.3 -->

<script> 

$('#ubicacion_actual').html('<?php echo $ubicacion; ?>');
</script> 

</html>
