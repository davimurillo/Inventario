<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>APICES|Control de Inventarios</title>
  <link href="../img/logos/apices.png" rel="shortcut icon" type="image/x-icon" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

</head>
<body >

  
<?php 

require_once('common.php'); checkUser(); 

	date_default_timezone_set($_SESSION['zona_horario']);
?>
<form id="formulario_masivo" action="javascript:agregar();">
		<div class="col-md-12">
			<div class="box box-warning">
				<div class="box-header with-border">
					<h3 class="box-title">Datos Generales</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
					</div>
				</div>
            
				<div class="box-body">
					<div class="form-group">
						<div class="col-xs-2">
							<label>UN</label>
							<select id="id_un" class="form-control select3" required="required">
							  <option value="">---Seleccione---</option>
							  <?php 
							   $sql="SELECT id_empresa,tx_abreviatura FROM
									public.mod_empresa order by tx_abreviatura";
							   $res=abredatabase(g_BaseDatos,$sql);
							   WHILE ($row=dregistro($res)){
							   ?>
							  <option value="<?php echo $row['id_empresa']; ?>" ><?php echo $row['tx_abreviatura']; ?></option>
							  
							   <?php } cierradatabase(); ?>
							</select>
						</div>
						<div class="col-xs-4">
							<label>Proveedor</label>
							<select id="proveedor_equipo" class="form-control select3" required="required">
							  <option value="">---Seleccione---</option>
							  <?php 
							   $sql="SELECT mod_proveedor.id_proveedor, mod_proveedor.tx_nombre FROM
									public.mod_proveedor WHERE  mod_proveedor.estatus = 16 order by mod_proveedor.tx_nombre";
							   $res=abredatabase(g_BaseDatos,$sql);
							   WHILE ($row=dregistro($res)){
							   ?>
							  <option value="<?php echo $row['id_proveedor']; ?>" ><?php echo $row['tx_nombre']; ?></option>
							  
							   <?php } cierradatabase(); ?>
							</select>
						</div>
						
						<div class="col-xs-6">
							<!-- Date -->
							<label>Fecha Ingreso:</label>
							<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
								<input id="fe_entrada_equipo" type="text" class="form-control pull-right" placeholder="Fecha de Ingreso" <?php if($_SESSION['rol']<4){ echo "disabled='disabled'"; }?> required="required" value="<?php echo date('d/m/Y'); ?>">
							</div>
						</div>
					  
					</div>
					
					<div class="form-group">
						<div class="col-xs-6">
							<label>Tipo de Motivo</label>
							<select id="motivo" class="form-control select3"  required="required">
								<option value="">---Seleccione---</option>
								  <?php 
								   $sql="SELECT id_tipo_objeto,tx_tipo FROM vie_tbl_tipo_motivos_entrada";
								   $res=abredatabase(g_BaseDatos,$sql);
								   WHILE ($row=dregistro($res)){
								   ?>
								  <option value="<?php echo $row['id_tipo_objeto']; ?>" ><?php echo $row['tx_tipo']; ?></option>
								  
								   <?php } cierradatabase(); ?>
							</select>
						</div>
						<div class="col-xs-6">
							<label>N° de Motivo</label>
							<input id="codigo_motivo" class="form-control" type="text" placeholder="Código" required="required">
						</div>
							
					</div>
						
					<div class="form-group">
						<div class="col-xs-6">
							<label>Cotización N°</label>
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-arrow-circle-right"></i>
								</div>
								<input id="cotizacion" type="text" class="form-control pull-right" placeholder="N° de Cotización" required="required">
							</div>
						</div>
						<div class="col-xs-6">
							<!-- Date -->
							<label>Guia de Remisión:</label>
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-arrow-circle-right"></i>
								</div>
								<input id="guia" type="text" class="form-control pull-right" placeholder="N° de Guia" required="required">
							</div>
						</div>
							
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<!-- carga masiva -->
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Datos Equipo / Accesorio</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
				</div>
					<div class="box-body">
						<div class="col-xs-4">
							<label>Descripción</label>
							<input id="descripcion" class="form-control" type="text" placeholder="Descripción" required="required">
						</div>									
						<div class="col-xs-2">
							<label>Tipo</label>
							<select id="tipo" class="form-control select3" style="width: 100%;" required="required">
								<option value="">---Seleccione---</option>
								<?php 
									   $sql="SELECT id_tipo_producto,tx_nombre_tipo FROM cfg_tipo_producto order by tx_nombre_tipo";
									   $res=abredatabase(g_BaseDatos,$sql);
									   WHILE ($row=dregistro($res)){
									   ?>
									  <option value="<?php echo $row['id_tipo_producto']; ?>" ><?php echo $row['tx_nombre_tipo']; ?></option>
									  
								<?php } cierradatabase(); ?>
							</select>
						</div>
						<div class="col-xs-2">
							<label>N° de Items</label>
							<input id="cantidad" class="form-control" type="text" placeholder="Cantidad" required="required">
						</div>
						<div class="col-xs-2">
							<label>Costo USD</label>
							<input id="costo" class="form-control" type="number" placeholder="costo" required="required">
						</div>
						<div class="col-xs-2">
							<label>Ngr</label>
							<input id="ngr" class="form-control" type="text" placeholder="ngr" required="required">
						</div>
						<div class="col-xs-2">
							<label>Marca</label>
							<select id="marca" class="form-control select3" style="width: 100%;" required="required">
								<option value="">---Seleccione---</option>
								<?php 
									   $sql="SELECT id_marca,tx_marca FROM cfg_tipo_marcas order by tx_marca";
									   $res=abredatabase(g_BaseDatos,$sql);
									   WHILE ($row=dregistro($res)){
									   ?>
									  <option value="<?php echo $row['id_marca']; ?>"   ><?php echo $row['tx_marca']; ?></option>
									  
								<?php } cierradatabase(); ?>
							</select>
						</div>
						<div class="col-xs-2">
							<label>Modelo</label>
							<input id="modelo" class="form-control" type="text" placeholder="Modelo" required="required">
						</div>
						<div class="col-xs-2">
							<label>Garantía</label>
							<select id="garantia" class="form-control select3" style="width: 100%;" required="required">
								<option value="">---Seleccione---</option>
								<?php 
									   $sql="SELECT id_tipo_objeto,tx_tipo FROM vie_tbl_tipo_garantia";
									   $res=abredatabase(g_BaseDatos,$sql);
									   WHILE ($row=dregistro($res)){
									   ?>
									  <option value="<?php echo $row['id_tipo_objeto']; ?>"   ><?php echo $row['tx_tipo']; ?></option>
									  
								<?php } cierradatabase(); ?>
							</select>
						</div>
						<div class="col-xs-2">
							<label>Condición</label>
							<select id="condicion_equipo" class="form-control select3" style="width: 100%;" required="required">
								<option value="">---Seleccione---</option>
								<?php 
									   $sql="SELECT id_tipo_objeto,tx_tipo FROM vie_tbl_condicion_equipo";
									   $res=abredatabase(g_BaseDatos,$sql);
									   WHILE ($row=dregistro($res)){
									   ?>
									  <option value="<?php echo $row['id_tipo_objeto']; ?>" ><?php echo $row['tx_tipo']; ?></option>
									  
								<?php } cierradatabase(); ?>
							</select>
						</div>
						<div class="col-xs-4">
							<label>Accesorios</label>
							<input id="accesorios" class="form-control" type="text" placeholder="Accesorios" required="required">
						</div>
						<div align="center" class="col-xs-12" style="margin-top:10px">
							<hr>
						</div>
						<div align="center" class="col-xs-12" style="margin-top:10px">
							<button id="agregar" type="submit" class="btn btn-success" >Cargar</button>
						</div>
					</div>
			</div>
		</div>
</form>
				
		<div class="col-md-12">
			<!-- carga masiva -->
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">Registro de Equipos / Accesorios</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>				
					</div>
				</div>
			<div id="registro_productos" class="box-body"></div>            
		</div>
							
</div>

</body>
<script>
$('.select3').select2({
dropdownParent: $('#myModal_carga_masiva')
});
</script>

</html>







