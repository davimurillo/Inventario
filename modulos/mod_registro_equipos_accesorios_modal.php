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
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
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
						<div class="col-xs-6">
							<label>Proveedor</label>
							<select id="proveedor_equipo" class="form-control select2" required="required">
							  <option value="">---Seleccione---</option>
							  <?php 
							   $sql="SELECT mod_proveedor.id_proveedor, mod_proveedor.tx_nombre FROM
									public.mod_proveedor WHERE  mod_proveedor.estatus = 16";
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
							<select id="motivo" class="form-control select2"  required="required">
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
							<select id="tipo" class="form-control select2" style="width: 100%;" required="required">
								<option value="">---Seleccione---</option>
								<?php 
									   $sql="SELECT id_tipo_producto,tx_nombre_tipo FROM cfg_tipo_producto";
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
							<input id="costo" class="form-control" type="text" placeholder="costo" required="required">
						</div>
						<div class="col-xs-2">
							<label>Ngr</label>
							<input id="ngr" class="form-control" type="text" placeholder="ngr" required="required">
						</div>
						<div class="col-xs-2">
							<label>Marca</label>
							<select id="marca" class="form-control select2" style="width: 100%;" required="required">
								<option value="">---Seleccione---</option>
								<?php 
									   $sql="SELECT id_marca,tx_marca FROM cfg_tipo_marcas";
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
							<select id="garantia" class="form-control select2" style="width: 100%;" required="required">
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
							<select id="condicion_equipo" class="form-control select2" style="width: 100%;" required="required">
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
</html>







