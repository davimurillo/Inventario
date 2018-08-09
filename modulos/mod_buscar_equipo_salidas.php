<?php
	require_once('common.php');
	checkUser();
if ($_POST['id']==0){	
?>
		<div class="col-xs-2">
							<label>Serial</label>
							<input id="serial" class="form-control" type="text" placeholder="Serial" value="" required="required" autofocus>
						</div>
						
						<div class="col-xs-10">
							<div class="callout callout-info">
								<h4>Información Importante!</h4>

								<p>Presione ENTER para Buscar el código del Producto!</p>
							</div>
							
						</div>
<?php 
}else{
	$sql="SELECT id_producto,
  id_tipo_producto,
  tx_nombre,
  id_unidad_medida,
  tx_marca,
  tx_modelo,
  tx_descripcion,
  costo,
  estatus,
  id_producto_padre,
  tx_serial,
  tx_ngr,
  id_garantia,
  id_condicion,
  TO_CHAR(fe_vencimiento,'DD/MM/YYYY') AS fe_vencimiento,
  id_proveedor,
(( SELECT sum(mod_movimiento.nx_cantidad) AS sum
           FROM mod_movimiento
          WHERE mod_movimiento.id_tipo_movimiento = 1 AND mod_movimiento.id_producto = d.id_producto) - 
		  CASE WHEN ( SELECT sum(mod_movimiento.nx_cantidad) AS sum
           FROM mod_movimiento
          WHERE mod_movimiento.id_tipo_movimiento = 2 AND mod_movimiento.id_producto = d.id_producto)>0 THEN  ( SELECT sum(mod_movimiento.nx_cantidad) AS sum
           FROM mod_movimiento
          WHERE mod_movimiento.id_tipo_movimiento = 2 AND mod_movimiento.id_producto = d.id_producto) ELSE 0 END) AS existencia FROM mod_producto d WHERE tx_serial='".$_POST['id']."'";
  $res=abredatabase(g_BaseDatos,$sql);
  $row2=dregistro($res);
  if (dnumerofilas($res)>0) {
?>
						<div class="col-xs-2">
							<label>Serial</label>
							<input id="serial" class="form-control" type="text" placeholder="serial" value="<?php echo $row2['tx_serial']; ?>" required="required">
						</div>
						
						<div class="col-xs-4">
							<label>Descripción</label>
							<input id="descripcion" class="form-control" type="text" placeholder="Descripción" required="required" value="<?php echo $row2['tx_descripcion']; ?>">
						</div>
						
						<div class="col-xs-2">
						<label>Tipo</label>
						<select id="tipo" class="form-control select2" style="width: 100%;" required="required">
						  <?php 
						   $sql="SELECT id_tipo_producto,tx_nombre_tipo FROM cfg_tipo_producto";
						   $res=abredatabase(g_BaseDatos,$sql);
						   WHILE ($row=dregistro($res)){
						   ?>
						  <option value="<?php echo $row['id_tipo_producto']; ?>" <?php if($row['id_tipo_producto']==$row2['id_tipo_producto']){ echo "selected='selected'";} ?>><?php echo $row['tx_nombre_tipo']; ?></option>
						  
						   <?php } cierradatabase(); ?>
						</select>
						</div>
						
						<div class="col-xs-1">
							<label>Stock</label>
							<input id="stop" class="form-control" type="text" placeholder="" value="<?php echo $row2['existencia']; ?>" required="required" >
						</div>
						
						<div class="col-xs-1">
							<label>Cantidad</label>
							<input id="cantidad" class="form-control" type="text" placeholder="Cantidad" value="0" required="required" autofocus>
						</div>
						
						<div class="col-xs-2">
							<label>Costo</label>
							<input id="costo" class="form-control" type="text" placeholder="costo" value="<?php echo $row2['costo']; ?>" required="required">
						</div>
						
						<div class="col-xs-2">
							<label>Ngr</label>
							<input id="ngr" class="form-control" type="text" placeholder="ngr" value="<?php echo $row2['tx_ngr']; ?>" required="required">
						</div>
						
						
						
						<div class="col-xs-2">
							<label>Marca</label>
							<input id="marca" class="form-control" type="text" placeholder="Marca" value="<?php echo $row2['tx_marca']; ?>" required="required">
						</div>
						
						<div class="col-xs-2">
							<label>Modelo</label>
							<input id="modelo" class="form-control" type="text" placeholder="Modelo" value="<?php echo $row2['tx_modelo']; ?>" required="required">
						</div>
						
						
						<div class="col-xs-2">
						<label>Garantía</label>
						<select id="garantia" class="form-control select2" style="width: 100%;" required="required">
						  <?php 
						   $sql="SELECT id_tipo_objeto,tx_tipo FROM vie_tbl_tipo_garantia";
						   $res=abredatabase(g_BaseDatos,$sql);
						   WHILE ($row=dregistro($res)){
						   ?>
						  <option value="<?php echo $row['id_tipo_objeto']; ?>" <?php if($row['id_tipo_objeto']==$row2['id_garantia']){ echo "selected='selected'";} ?> ><?php echo $row['tx_tipo']; ?></option>
						  
						   <?php } cierradatabase(); ?>
						</select>
						</div>
						
						<div class="col-xs-2">
							<!-- Date -->
							<label>Fecha Vence:</label>

							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input id="fe_vence" type="text" class="form-control pull-right" placeholder="Fecha de Vencimiento" required="required" value="<?php echo $row2['fe_vencimiento']; ?>">
							</div>
					
						</div>
						
						<div class="col-xs-2">
						<label>Estatus</label>
						<select id="estatus_equipo" class="form-control select2" style="width: 100%;" required="required">
						  <?php 
						   $sql="SELECT id_tipo_objeto,tx_tipo FROM vie_tbl_estatus_equipo";
						   $res=abredatabase(g_BaseDatos,$sql);
						   WHILE ($row=dregistro($res)){
						   ?>
						  <option value="<?php echo $row['id_tipo_objeto']; ?>" <?php if($row['id_tipo_objeto']==$row2['estatus']){ echo "selected='selected'";} ?> ><?php echo $row['tx_tipo']; ?></option>
						  
						   <?php } cierradatabase(); ?>
						</select>
						</div>
						
						<div class="col-xs-2">
						<label>Condición</label>
						<select id="condicion_equipo" class="form-control select2" style="width: 100%;" required="required">
						  <?php 
						   $sql="SELECT id_tipo_objeto,tx_tipo FROM vie_tbl_condicion_equipo";
						   $res=abredatabase(g_BaseDatos,$sql);
						   WHILE ($row=dregistro($res)){
						   ?>
						  <option value="<?php echo $row['id_tipo_objeto']; ?>" <?php if($row['id_tipo_objeto']==$row2['id_condicion']){ echo "selected='selected'";} ?> ><?php echo $row['tx_tipo']; ?></option>
						  
						   <?php } cierradatabase(); ?>
						</select>
						</div>
						
						<div class="col-xs-2">
						<label>Und. de Medida</label>
						<select id="unidad_medida_equipo" class="form-control select2" style="width: 100%;" required="required">
						  <?php 
						   $sql="SELECT id_tipo_objeto,tx_tipo FROM vie_tbl_unidad_medida";
						   $res=abredatabase(g_BaseDatos,$sql);
						   WHILE ($row=dregistro($res)){
						   ?>
						  <option value="<?php echo $row['id_tipo_objeto']; ?>" <?php if($row['id_tipo_objeto']==$row2['id_unidad_medida']){ echo "selected='selected'";} ?> ><?php echo $row['tx_tipo']; ?></option>
						  
						   <?php } cierradatabase(); ?>
						</select>
						</div>
						
						<div class="col-xs-2">
						<label>Proveedor</label>
						<select id="id_proveedor" class="form-control select2" style="width: 100%;" required="required">
						  <?php 
						   $sql="SELECT id_proveedor,tx_nombre FROM mod_proveedor";
						   $res=abredatabase(g_BaseDatos,$sql);
						   WHILE ($row=dregistro($res)){
						   ?>
						  <option value="<?php echo $row['id_proveedor']; ?>" <?php if($row['id_proveedor']==$row2['id_proveedor']){ echo "selected='selected'";} ?> ><?php echo $row['tx_nombre']; ?></option>
						  
						   <?php } cierradatabase(); ?>
						</select>
						</div>
						
						<div class="col-xs-6">
							<label>Observaciones</label>
							<input id="observacion" class="form-control" type="text" placeholder="Observaciones">
							
							<input id="validacion" class="form-control" type="hidden" value=0>
						</div>


<?php
  } else{
	  
  ?>
				
						<div class="col-xs-2">
							<label>Serial</label>
							<input id="serial" class="form-control" type="text" placeholder="Serial" value="<?php echo $_POST['id']; ?>" required="required">
						</div>
						
						<div class="col-xs-10">
							<div class="callout callout-danger">
								<h4>Información Importante!</h4>

								<p>Codigo de Producto no encontrado, Presione ENTER para Buscar el código del Producto!</p>
							</div>
							
						</div>
<?php  }}
?>
<script>
  
	$( "#serial" ).focus();
	$( "#serial" ).keypress(function( event ) {
	  if ( event.which == 13 ) {
		 $('#form_equipo').load('mod_buscar_equipo_salidas.php',{id:$('#serial').val()});
	  }
	});
</script>