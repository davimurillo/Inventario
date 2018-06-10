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
  <!-- daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
   <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="plugins/colorpicker/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  
<?php 

require_once('common.php'); checkUser(); 
$sql="SELECT (tx_nombre_apellido) as nombre, tx_foto_usuario, (SELECT tx_telefono FROM cfg_usuario_telefono WHERE id_usuario=a.id_usuario LIMIT 1) AS telefono, CASE WHEN id_estatu=1 THEN 'Activo' ELSE 'Inactivo' END AS estatus, to_char(fe_ultima_actualizacion, 'DD/MM/YYYY a las HH:MI am') as fecha_actualizacion, (SELECT tx_perfil FROM cfg_perfil WHERE id_perfil=a.id_perfil) AS perfil FROM cfg_usuario a WHERE id_usuario=".$_SESSION['id_usuario'];
	$res=abredatabase(g_BaseDatos,$sql);
	$row=dregistro($res);
	$nombre_usuario=$row['nombre'];
	$telefono_usuario=$row['telefono'];
	$estatus_usuario=$row['estatus'];
	$perfil=$row['perfil'];
	$fecha_actualizacion=$row['fecha_actualizacion'];
	$foto=$row['tx_foto_usuario'];
	cierradatabase();
	
	if ($foto==""){
		$foto="../img/fotos/img.jpg";	
	}else{
		$foto="repositorio/fotos_usuario/".$foto;
	}
	date_default_timezone_set($_SESSION['zona_horario']);
?>
 <!-- Head app -->

  <?php require('cabecera.php'); ?> 
  
  <!-- Left side column. contains the logo and sidebar -->
  
  <?php $formulario=9;  include('barra_izquierda.php'); ?> 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Reportes
        <small>Generales</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Reportes</a></li>
        <li class="active">Generales</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<div class="row">
		  <div class="col-md-12">
			<!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Datos Generales del Reporte</h3>
			  <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                
                
              </div>
			</div>
			
            <!-- /.box-header -->
            <!-- form start -->
            
              <div class="box-body">
                <div class="form-group">
					<div class="col-xs-12">
						<label>Reporte</label>
						<select id="reporte" class="form-control select2"  required="required">
						  <?php 
						   $sql="SELECT id_reporte,tx_reporte, tx_archivo FROM cfg_reportes ORDER BY tx_reporte";
						   $res=abredatabase(g_BaseDatos,$sql);
						   WHILE ($row=dregistro($res)){
						   ?>
						  <option value="<?php echo $row['tx_archivo']; ?>" ><?php echo $row['tx_reporte']; ?></option>
						  
						   <?php } cierradatabase(); ?>
						</select>
					</div>
					
					
					
				</div>
				<div class="form-group">
					<div class='col-xs-12' style="margin-top:20px">
						 <h5><strong> <i class="fa fa-filter" ></i> - FILTRO PARA LOS REPORTES</strong></h5>
						 <HR>
					</div>
					<div class="col-xs-2">
						<label>UNIDAD DE NEGOCIO</label>
						
						<select id="unidad_negocio" class="form-control select2"  required="required" Onchange="javascript:cambiar_ubicacion(this.value);">
							<option value="">Seleccione...</option>
						  <?php 
						   $sql="SELECT id_empresa,tx_marca FROM mod_empresa";
						   $res=abredatabase(g_BaseDatos,$sql);
						   WHILE ($row=dregistro($res)){
						   ?>
						  <option value="<?php echo $row['id_empresa']; ?>" ><?php echo $row['tx_marca']; ?></option>
						  
						   <?php } cierradatabase(); ?>
						</select>
					</div>
					
					<div id=ubicacion class="col-xs-4">
					<label>UBICACIÓN</label>
					<select id="origen" class="form-control ubicacion" required="required">
					<option value="">Seleccione...</option>
					  <?php 
					   $sql="SELECT id_tienda,(tx_descripcion) as tx_nombre FROM mod_tienda WHERE estatus=18";
					   $res=abredatabase(g_BaseDatos,$sql);
					   WHILE ($row=dregistro($res)){
					   ?>
					  <option value="<?php echo $row['id_tienda']; ?>" ><?php echo $row['tx_nombre']; ?></option>
					  
					   <?php } cierradatabase(); ?>
					</select>
					</div>
					
					<div class="col-xs-6">
						<label>PROVEEDOR</label>
						<select id="proveedor" class="form-control select2"  required="required" >
							<option value="">Seleccione...</option>
							<?php 
								$sql="SELECT id_proveedor,tx_nombre FROM mod_proveedor WHERE estatus=16";
								$res=abredatabase(g_BaseDatos,$sql);
								WHILE ($row=dregistro($res)){
							?>
							<option value="<?php echo $row['id_proveedor']; ?>" ><?php echo $row['tx_nombre']; ?></option>
							<?php } cierradatabase(); ?>
						</select>
					</div>
					
					
					
					<div class="col-xs-3">
						<label>N° DE TICKET:</label>
						<div class="input-group date">
							<div class="input-group-addon">
							<i class="fa fa-arrow-circle-right"></i>
							</div>
							<input id="ticket" type="text" class="form-control pull-right" placeholder="N° de ticket" required="required">
						</div>
					</div>
					
					<div class="col-xs-3">
						<!-- Date -->
						<label>N° DE GUÍA:</label>

						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-arrow-circle-right"></i>
							</div>
							<input id="guia" type="text" class="form-control pull-right" placeholder="N° de Guia" required="required">
						</div>
					
					</div>
					
					<div class="col-xs-3">
						<!-- Date -->
						<label>FECHA DESDE:</label>

						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input id="fe_entrada" type="text" class="form-control pull-right" placeholder="Fecha desde" required="required" value="">
						</div>
					
					</div>
					
					<div class="col-xs-3">
						<!-- Date -->
						<label>FECHA HASTA:</label>

						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input id="fe_hasta" type="text" class="form-control pull-right" placeholder="Fecha hasta" required="required" value="">
						</div>
					
					</div>
					
					<div class="col-xs-2">
						<label>TIPO DE MOTIVO</label>
						
						<select id="seleccion_tipo_motivo" class="form-control select2"  required="required" Onchange="javascript:cambiar_tipo_motivo(this.value);">
							<option value="">Seleccione...</option>
							<option value="1">ENTRADAS</option>
							<option value="2">SALIDAS</option>
						</select>
					</div>
					
					<div id="tipo_motivo" class="col-xs-4">
						<label>MOTIVO</label>
						<select id="motivo" class="form-control select2"  required="required">
							<option value="">...Seleccione...</option>						 
						</select>
					</div>
					
					<div class="col-xs-6">
						<label>Tipo de Producto</label>
						<select id="tipo_producto" class="form-control select2"  required="required">
							<option value="">Seleccione...</option>
							<?php 
								$sql="SELECT id_tipo_producto,tx_nombre_tipo FROM cfg_tipo_producto";
								$res=abredatabase(g_BaseDatos,$sql);
								WHILE ($row=dregistro($res)){
							?>
							<option value="<?php echo $row['id_tipo_producto']; ?>" ><?php echo $row['tx_nombre_tipo']; ?></option>
							<?php } cierradatabase(); ?>
						</select>
					</div>
					<div class="col-xs-12">
						<hr>
					</div>
					<div class="col-xs-12" align="CENTER" class="box-footer">
						<button id="terminar" type="submit" class="btn btn-success">IMPRIMIR</button>
					</div>
					
					</div>
					
					
					
					
				</div>
				
			
              </div>
              <!-- /.box-body -->
			  
            
          </div>
          <!-- /.box -->
		  
		
		
		
    </section>
    <!-- /.content -->
  </div>
  <?php include('pie.php'); ?>


</div>


<?php include('libreriaJS.php'); ?>
<!-- Page script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
	 $(".ubicacion").select2();
  
  
   //Date picker
    $('#fe_entrada').datepicker({
      autoclose: true,
	  format: 'dd/mm/yyyy'
    });
	
	//Date picker
    $('#fe_hasta').datepicker({
      autoclose: true,
	  format: 'dd/mm/yyyy'
    });
	
	});
	
	
	
    $( "#terminar" ).click(function() {
		motivo=$('#motivo').val();
		un=$('#unidad_negocio').val();
		proveedor=$('#proveedor').val();
		origen=$('#origen').val();
		fe_entrada=$('#fe_entrada').val();
		fe_hasta=$('#fe_hasta').val();
		ticket=$('#ticket').val();
		guia=$('#guia').val();
		reporte=$("#reporte option:selected").text();
		tipo=$('#tipo_producto').val();
		url="reportes/"+$('#reporte').val()+"?un="+un+"&motivo="+motivo+"&proveedor="+proveedor+"&origen="+origen+"&fe_entrada="+fe_entrada+"&fe_hasta="+fe_hasta+"&ticket="+ticket+"&guia="+guia+"&tipo="+tipo+"&reporte="+reporte;
		v=window.open(url,reporte);
		
	});
  
	function imprimir_contenido(){
			document.getElementById("reportes").contentWindow.print();
	}
	
	function cambiar_ubicacion(unidad_negocio){
			$('#ubicacion').load ('mod_eventos.php', { unidad: unidad_negocio, evento:26 });
	}
	
	function cambiar_tipo_motivo(tipo){
		$('#tipo_motivo').load ('mod_eventos.php', { tipo:tipo,  evento:29 });
	}

</script>
<?php require_once('libreriaSCRIPT.php'); ?>
</body>
</html>







