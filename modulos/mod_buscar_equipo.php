<?php
	require_once('common.php');
	checkUser();
	
if ($_POST['id']=="" and $_POST['id_producto']==""){	
?>
		<div class="col-xs-2">
							<label>Serial</label>
							<input id="id_producto" class="form-control" type="hidden" placeholder="id" value="" required="required">
							<input id="serial" class="form-control" type="text" placeholder="serial" value="" required="required" autofocus>
						</div>
						
						<div class="col-xs-10">
							<div class="callout callout-info">
								<h4>Información Importante!</h4>

								<p>Presione ENTER para Buscar el Serial del Equipo a buscar o a incorporar!</p>
							</div>
							
						</div>
						<script>
							$("#serial").select();
							$("#serial").focus();
						</script>
<?php 
}else{

$serial = (isset($_POST['id'])) ? $_POST['id'] : "0"; 
$producto = isset($_POST['id_producto']) ? $_POST['id_producto'] : "0"; 
$motivo = isset($_POST['motivo']) ? $_POST['motivo'] : "0"; 
if ($producto>0){ $producto=$producto; }else{ $producto=0; } 
  $sql="SELECT id_producto,
  (SELECT tx_nombre_tipo FROM cfg_tipo_producto WHERE id_tipo_producto=d.id_tipo_producto) as id_tipo_producto,
  (SELECT tx_logo FROM mod_empresa WHERE id_empresa=d.id_unidad_negocio) as logo,
  tx_nombre,
  id_unidad_medida,
  h.tx_marca,
  tx_modelo,
  tx_descripcion,
  costo,
  estatus,
  id_producto_padre,
  tx_serial,
  tx_ngr,
  id_garantia,
  id_condicion,
  fe_ingreso,
  (SELECT tx_accesorios FROM mod_movimiento WHERE id_producto=d.id_producto ORDER BY fe_movimiento DESC, fe_actualizada DESC LIMIT 1) as tx_accesorios,
  (SELECT tx_observacion FROM mod_movimiento WHERE id_producto=d.id_producto ORDER BY fe_movimiento DESC, fe_actualizada DESC LIMIT 1) as tx_observacion,
  TO_CHAR(fe_vencimiento,'DD/MM/YYYY') AS fe_vencimiento,
  id_proveedor,
  ultimo_movimiento,
 n_stock as existencia FROM mod_producto d, cfg_tipo_marcas h WHERE h.id_marca=d.id_tipo_marca ";
  
  if ($producto!=0) {
	  $sql.=" AND id_producto=".$producto;
  }
  if ($motivo!=0 and $motivo==85) {
	  $sql.=" AND estatus=7";
  }else{
	 $sql.=" AND estatus=5";
  }
  if ($serial!=''){
	  $sql.=" AND upper(tx_serial)='".strtoupper($serial)."'";
  }
   $res=abredatabase(g_BaseDatos,$sql);
  $row2=dregistro($res);
  if (dnumerofilas($res)>0) {
?>	
					<div class='col-xs-12'>
						<div class="col-xs-2">
							<label>Serial</label>
							
							<div class="input-group date">
								<div class="input-group-addon">
									<a href="javascript:codigo_barra(<?php echo $row2['id_producto']; ?>);"><i class="fa fa-barcode" title="Imprimir Código de Barra" ></i></a> 
								</div>
								<input id="serial" class="form-control" type="text" placeholder="serial" value="<?php echo trim($row2['tx_serial']); ?>" required="required">
								<span class="input-group-btn">
									<a href="javascript:editar_equipos_opcional(<?php echo $row2['id_producto']; ?>);">
										<button class="btn btn-default form-control" type="submit"><i class="fa fa-edit"></i></button>
									</a>
								</span>
								
							</div>
						
							<input id="id_producto" class="form-control" type="hidden" placeholder="serial" value="<?php echo $row2['id_producto']; ?>" required="required" >
							
							 
						</div>
						
						<div class="col-xs-<?php  if (isset($_POST['f']) && $_POST['f']==1) { echo '5'; } else { echo '3'; } ?>">
							<label>Descripción</label><br>
	<?php echo $row2['logo']; ?>
							<span><?php echo $row2['tx_descripcion']; ?><br> <b>Tipo:</b> <?php echo $row2['id_tipo_producto']; ?>, <b>Marca:</b> <?php echo $row2['tx_marca']; ?>, <b>Placa:</b> <?php echo $row2['tx_ngr']; ?>, <b>Modelo:</b> <?php echo $row2['tx_modelo']; ?>.</span> 
						</div>
						<div align="center" class="col-xs-1">
							<img src="repositorio/logos/<?php echo $row2['logo']; ?>"  style="width:100%"/>
						</div>
						<?php if (isset($_POST['f']) && $_POST['f']==1) { } else { ?>
						<div align="center" class="col-xs-2">
							<label>Estatus</label>
							<select id="estatus" class="form-control select2" style="width: 100%;" required="required">
							<option value="">---Seleccione---</option>
							  <?php 
							   $sql="SELECT id_tipo_objeto,tx_tipo FROM vie_tbl_estatus_equipo";
							   $res=abredatabase(g_BaseDatos,$sql);
							   WHILE ($row=dregistro($res)){
								   $selecciona=$row2['estatus']==$row['id_tipo_objeto']? 'selected' : '';
							   ?>
							  <option value="<?php echo $row['id_tipo_objeto']; ?>" <?php echo $selecciona; ?>><?php echo $row['tx_tipo']; ?></option>
							  
							   <?php } cierradatabase(); ?>
							</select>
						</div>
						<?php } ?>
						<div align="center" class="col-xs-2">
							<label>Cantidad</label>
							<input id="cantidad" class="form-control" type="text" placeholder="Cantidad" value="0" required="required" autofocus style="text-align:center">
						</div>
						
						<?php if ($row2['existencia']!=0){ ?>
						<div align="center" class="col-xs-2" >
							<div class="col-xs-12"> <label>Stock</label></div>
							<div class="col-xs-12" style="background-color:green; color:#fff; height:35px; vertical-align:middle "> <strong style="font-size:22px;"><?php echo $row2['existencia']; ?></strong></div>
						</div>
						<?php }else { ?>
							<div align="center" class="col-xs-2" >
							<div class="col-xs-12"> <label>Stock</label></div>
							<div class="col-xs-12" style="background-color:red; color:#fff; height:35px; vertical-align:middle "> <strong style="font-size:22px;">0</strong></div>
							</div>
						<?php } ?>
						
						
					</div>

					<div class='col-xs-12'>
						
						<div class="col-xs-6">
							<label>Accesorios</label>
							<textarea id="accesorios" placeholder="Accesorios" class="form-control" rows="4"><?php echo $row2['tx_accesorios']; ?></textarea>
						</div>
						
						<div class="col-xs-6">
							<label>Observaciones</label>
							
							<textarea id="observacion" placeholder="observacion" class="form-control" rows="4"><?php echo $row2['tx_observacion']; ?></textarea>
							<input id="validacion" class="form-control" type="hidden" value=0>
						</div>
						
					</div>	
						<script>
							$("#cantidad").select();
							$("#cantidad").focus();
						</script>

<?php
  } else{
	  
  ?>
				<div class="callout callout-warning">
					<h4>Información Importante!</h4>

					<p>Código de Equipo No Fue Encontrado, si desea continuar, este equipo se guadará como un nuevo equipo y como un nuevo movimiento de entrada al inventario de lo contrario intente de nuevo colocar el codigo correcto del producto.</p>
				</div>
						<div class="col-xs-2">
							<label>Serial</label>
							<input id="id_producto" class="form-control" type="hidden" placeholder="id" value="">
							<input id="serial" class="form-control" type="text" placeholder="serial" value="<?php echo $_POST['id']; ?>" required="required" autofocus>
						</div>
						
						
<?php  }}
?>
<!-- Ventana para codigo de Barra del equipo -->
	<div class="modal fade" tabindex="-1" id="myModal_editar_producto" role="dialog" style="color:#999; ">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content" >
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			 <h2 class="modal-title"><span class="fa fa-edit" style="margin-right:10px"></span>Editar Equipo y/o accesorios</h2>
		  </div>
		  <div class="modal-body" id="codigo_barra_equipo" >
				<iframe src="" name="codigo_producto_editar" id="codigo_producto_editar" width="100%" height="400px" frameborder=0 >
				</iframe>
			
		  </div>
		  <div class="modal-footer"  style="text-align:center">
				  
			
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<script>
	
   
$(function () {
	
	
	 
    $(".select2").select2();
	
	$( "#serial" ).keypress(function( event ) {
	  if ( event.which == 13 ) {
		 $('#form_equipo').load('mod_buscar_equipo.php',{id:$('#serial').val(),id_producto:$('#id_producto').val(),  motivo: $('#motivo').val(), f: <?php  if (isset($_POST['f']) && $_POST['f']==1) { echo '1'; } else { echo '0'; } ?>});
		}
	});
});
</script>