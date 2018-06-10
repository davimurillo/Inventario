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
  
  <?php $formulario=18; include('barra_izquierda.php'); ?> 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  
  
  
  
  
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registro
        <small>Control de Bajas</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Registro</a></li>
        <li class="active">Control de Bajas</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<div class="row">
		  <div class="col-md-12">
			<!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Datos Generales de la Baja</h3>
			  <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                
                
              </div>
			</div>
			
            <!-- /.box-header -->
            <!-- form start -->
            <form id="form1" action="javascript:agregar();" role="form" data-parsley-validate >
              <div class="box-body" id="datos_generales_guia">
                
				
				<div class="form-group">
					<div class="col-xs-6">
					<label>Origen</label>
					<select id="origen" class="form-control select2" required="required">
					<option value="">----Seleccione ---</option>
					  <?php 
					   $sql="SELECT id_tienda,(tx_marca || ' - ' || tx_descripcion) as tx_nombre FROM vie_tbl_tiendas ORDER BY tx_nombre";
					   $res=abredatabase(g_BaseDatos,$sql);
					   WHILE ($row=dregistro($res)){
					   ?>
					  <option value="<?php echo $row['id_tienda']; ?>" ><?php echo $row['tx_nombre']; ?></option>
					  
					   <?php } cierradatabase(); ?>
					</select>
					</div>
					
					<div class="col-xs-6">
					<label>Motivo</label>
					<select id="motivo" class="form-control select2" required="required" Onchange="buscar_numeracion(this.value)">
					<option value="">----Seleccione ---</option>
					  <?php 
					   $sql="SELECT id_tipo_objeto, tx_tipo FROM vie_tbl_tipo_motivos_entrada";
					   $res=abredatabase(g_BaseDatos,$sql);
					   WHILE ($row=dregistro($res)){
					   ?>
					  <option value="<?php echo $row['id_tipo_objeto']; ?>" ><?php echo $row['tx_tipo']; ?></option>
					  
					   <?php } cierradatabase(); ?>
					</select>
					</div>
					
				</div>
				
				<div class="form-group">
					<div class="col-xs-6">
						<label>Responsable</label>
						<input id="responsable" class="form-control" type="text" placeholder="Responsable" required="required">
					</div>
					<div class="col-xs-6">
						<!-- Date -->
						<label>Fecha Ingreso:</label>

						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input id="fe_entrada" type="text" class="form-control pull-right" placeholder="Fecha de Ingreso" required="required" value="<?php echo date('d/m/Y'); ?>" <?php if($_SESSION['rol']<4){ echo "disabled='disabled'"; }?>>
						</div>
					
					</div>
              
				</div>
				<div class="form-group">
					<div class="col-xs-6">
						
						<label>Ticket:</label>

						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-arrow-circle-right"></i>
							</div>
							<input id="ticket" type="text" class="form-control pull-right" placeholder="N° de ticket" required="required" value="0">
						</div>
					
					</div>
					<div id="guia_numeracion" class="col-xs-6">
					
						<!-- Date -->
						<label>N° de Guia de Remisión:</label>

						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-arrow-circle-right"></i>
							</div>
							<input id="guia" type="text" class="form-control pull-right" placeholder="N° de Guia" required="required" >
						</div>
					
					</div>
              
				</div>
				
				
				
              </div>
              <!-- /.box-body -->
				<div align="center" class="box-footer">
						
						

						<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal_buscar_guia"><i class="fa fa-search"></i> BUSCAR GUIA</button>
					
					
				  </div>	
            
          </div>
          <!-- /.box -->
		 </div>
		 </div>
		 <div class="row">
			<div class="col-md-12">
			<div id="eventos_entradas" ></div>
			<div class="box box-warning">
				<div class="box-header with-border">
				  <h3 class="box-title">Ingreso de Equipos y/o Accesorios</h3>
				  <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                
                
              </div>
				</div>
				<!-- /.box-header -->
				<!-- form start -->
				
				  <div id="form_equipo" class="box-body">
					
						<div class="col-xs-2">
							<label>Serial</label>
							<input id="id_producto" class="form-control" type="hidden" placeholder="id" value="" >
							<input id="serial" class="form-control" type="text" placeholder="serial" value="" required="required" autofocus>
						</div>
						
						<div class="col-xs-10">
							<div class="callout callout-info">
								<h4>Información Importante!</h4>

								<p>Presione ENTER para Buscar el serial del Equipo!</p>
							</div>
							
						</div>
						
						
				
				  </div>
				  <!-- /.box-body -->
				 <div align="center" class="box-footer">

						<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal_buscar_equipos" style="width:150px"><i class="fa fa-search"></i> F2-BUSCAR</button>
						
						<button id="cancelar_busqueda" type="button" class="btn btn-danger btn-sm" style="width:150px"><i class="fa fa-ban"></i> ESC - CANCELAR</button>
						
						<button id="agregar" type="submit" class="btn btn-primary btn-sm" style="width:150px"><i class="fa fa-arrow-down"></i> AGREGAR</button>
					
				  </div>	
			</form>	
			</div>
			</div>
				
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title">Listado de Equipos y/o Accesorios</h3> <a href="javascript:refrescar();">Refrescar</a>
					</div>
					<!-- /.box-header -->
					<!-- form start -->
					<div id="movimientos" class="box-body">
					<table id="movimiento_entrada" class="table table-bordered table-hover">
						<thead>
						<tr>
						  <th>DESCRIPCION</th>
						  <th>SERIAL</th>
						  <th>TIPO</th>
						  <th>CANTIDAD</th>
						  <th>COSTO</th>
						  <th><i class="fa fa-trash"></i></th>
						</tr>
						</thead>
					</table>
					</div>
					 <!-- /.box-body -->
					<div align="right" class="box-footer">
						<button id="terminar" type="submit" class="btn btn-success">TERMINAR</button>
					</div>
				</div>
			</div>
		</div>
		
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
   <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2017.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <?php include('barra_derecha.php'); ?>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>

<!-- Ventana para BUSCAR EQUIPOS -->
	<div class="modal fade"  id="myModal_buscar_equipos" role="dialog" style="color:#999; ">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content" >
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h2 class="modal-title"><span class="fa fa-search" style="margin-right:10px"></span>Buscardor de Equipos</h2>
		  </div>
		  <div class="modal-body" >
			<div class="row">
				
			  <div class="col-xs-12">
						<label>Valor de Busqueda</label>
							<input id="buscar_equipo" name="buscar_equipo" class="form-control" type="text" placeholder="Valor de Busqueda" value="" required="required"  autofocus>
					
						
						
							
				</div>
				<div id="resultado_busqueda" class="col-xs-12" style="margin-top:20px">
						<div class="callout callout-info">
								<h4>Información Importante!</h4>

								<p>Presione ENTER para Buscar los datos del Equipo!</p>
							</div>
						
							
				</div>
				</div>
			</div>
				
			
		  </div>
		  <div class="modal-footer"  style="text-align:center">
				  
			
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<!-- Ventana para IMPORTAR -->
	<div class="modal fade" tabindex="-1" id="myModal_importar" role="dialog" style="color:#999; ">
	  <div class="modal-dialog">
		<div class="modal-content" >
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h2 class="modal-title"><span class="fa fa-upload" style="margin-right:10px"></span>Importar Equipos</h2>
		  </div>
		  <div class="modal-body" >
				<div class="callout callout-info">
                <h4>Información Importante!</h4>

                <p>Recuerde agregar los accesorios que el mayor detalle posible para poder identificar todas las partes que van en conjunto con el equipo.</p>
              </div>
				
			
		  </div>
		  <div class="modal-footer"  style="text-align:center">
				  
			
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<!-- Ventana para BUSCAR guia -->
	<div class="modal fade" tabindex="-1" id="myModal_buscar_guia" role="dialog" style="color:#999; ">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content" >
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h2 class="modal-title"><span class="fa fa-search" style="margin-right:10px"></span>Buscar Guía de Entrada</h2>
		  </div>
		  <div class="modal-body" >
			<div class="row">
				
			  <div class="col-xs-12">
						<label>Valor de Busqueda</label>
							<input id="buscar_guia" class="form-control" type="text" placeholder="Valor de Busqueda" value="" required="required" >
					
				</div>
				<div id="resultado_busqueda_guia" class="col-xs-12" style="margin-top:20px">
						<div class="callout callout-info">
								<h4>Información Importante!</h4>

								<p>Presione ENTER para Buscar los datos de la Guía!</p>
							</div>
						
							
				</div>
				</div>
			</div>
				
			
		  </div>
		  <div class="modal-footer"  style="text-align:center">
				  
			
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

		<!-- Ventana para codigo de Barra del equipo -->
	<div class="modal fade" tabindex="-1" id="myModal_barra" role="dialog" style="color:#999; ">
	  <div class="modal-dialog">
		<div class="modal-content" >
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h2 class="modal-title"><span class="fa fa-upload" style="margin-right:10px"></span>Codigo de Barra del Equipo</h2>
		  </div>
		  <div class="modal-body" id="codigo_barra_equipo" >
				<iframe src="" name="code_barra" id="id_code_barra" width="100%" height="400px" frameborder=0 >
				</iframe>
			
		  </div>
		  <div class="modal-footer"  style="text-align:center">
				  
				  <button type="button" class="btn btn-success" data-dismiss="modal" aria-label="Close">Cancelar
				  </button>
		  </div>
			
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<!-- ./wrapper -->

<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="../lib/js/jquery.min1.11.2.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/select2.full.min.js"></script>
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page script -->
<script>

$("BODY").keydown(function(event) {
        
		
		if(event.which == 113) { //F2
         
		  $('#myModal_buscar_equipos').modal('show'); 
		    $('#buscar_equipo').select();	
            $('#buscar_equipo').focus();	 
		  
        } 
		//if(event.which == 114) { //F3
        //  $('#myModal_importar').modal('show');  
        //} 
		
		if(event.which == 27) { //ESC
          limpiar();
        } 
       
 });
 
 function limpiar (){
	  $('#form_equipo').load('mod_buscar_equipo.php', {id:'',id_producto:''});
	 
 }
 
 $( "#buscar_equipo" ).keypress(function( event ) {
	  if ( event.which == 13 ) {
 
	$('#resultado_busqueda').load('mod_eventos.php',{id:$('#buscar_equipo').val(),accion:1, evento:7});
	  }
 });

  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
  
  
   //Date picker
    $('#fe_entrada').datepicker({
      autoclose: true,
	  format: 'dd/mm/yyyy'
    });
	});
	
	
	function buscar_serial(){
		
		 $('#form_equipo').load('mod_buscar_equipo.php',{id:$('#serial').val(),'id_producto':$('#id_producto').val()});
	}
	
	$( "#serial" ).keypress(function( event ) {
	  if ( event.which == 13 ) {
		  
		 $('#form_equipo').load('mod_buscar_equipo.php',{id:$('#serial').val(),id_producto:$('#id_producto').val()});
	  }
	});
	
	//$( "#agregar" ).click(function() {
	function agregar(){	
		 if (typeof $('#observacion').val() != "undefined"){
			if ($('#cantidad').val()>0) { 
				$('#eventos_entradas').load('mod_eventos.php',{id_producto:$('#id_producto').val(),serial:$('#serial').val(),cantidad:$('#cantidad').val(), observacion:$('#observacion').val(), accesorios:$('#accesorios').val(),responsable:$('#responsable').val(),origen:$('#origen').val(),fe_entrada:$('#fe_entrada').val(),ticket:$('#ticket').val(),guia:$('#guia').val(),motivo:$('#motivo').val(),estatus:$('#estatus').val(),accion:1, evento:20});
				
				limpiar();
			}else {
				alert("Debe Colocar una cantidad mayor que CERO en la entrada del producto!");
				 $('#cantidad').select();	
				 $('#cantidad').focus();
			}	
		}
		 
	}
	
	function refrescar(){
		$('#movimientos').load('mod_eventos.php',{origen:$('#origen').val(),fe_entrada:$('#fe_entrada').val(),ticket:$('#ticket').val(),guia:$('#guia').val(),accion:1, evento:21});
	}
	
	$('#form1').keypress(function(e){   
    if(e == 13){
      return false;
    }
  });
  
 
  $(function () {
    $("#movimiento_entrada").DataTable();
    
  });
  
  function eliminar_elemento(id, cantidad, id_producto, id_tienda){
			$('#eventos_entradas').load('mod_eventos.php',{guia:$('#guia').val(),id_movimiento:id,origen:$('#origen').val(),fe_entrada:$('#fe_entrada').val(),id:id_producto, 'cantidad':cantidad, tienda:id_tienda, accion:1,evento:22});
  }
  
  //boton terminar
  $( "#terminar" ).click(function() {
	  window.open("mod_mostrar_guia_baja.php?id=1&guia="+$('#guia').val()+"&fecha="+$('#fe_entrada').val(),"_blank");
	  location.reload();
  });
  
   $( "#buscar_guia" ).keypress(function( event ) {
	  if ( event.which == 13 ) {
 
	$('#resultado_busqueda_guia').load('mod_eventos.php',{id:$('#buscar_guia').val(),accion:1, evento:14});
	  }
 });
 
 $( "#cancelar_busqueda" ).click(function() {
		limpiar();
 });

 // busca la ultima numeriacion del motivo
 function buscar_numeracion(id){
	$('#guia_numeracion').load('mod_eventos.php',{'id':id, 'evento': 17});
 }
</script>
</body>
</html>
<script>

function codigo_barra(id){
		$('#id_code_barra').attr('src','mod_mostrar_code_barra_equipo.php?id_producto='+id);  
		$('#myModal_barra').modal('show'); 
	}
 function editar_equipos_opcional(id) { 
          
		   $('#codigo_producto_editar').attr('src','blank.html');  
		  $('#codigo_producto_editar').attr('src','mod_registro_equipos_accesorios_editar.php?buscar_equipo_general=&f_mode=edit&f_rid='+id+'&f_page_size=100&f_p=1');  
		  $('#myModal_editar_producto').modal('show');  
		  
 }
</script>
<?php require_once('libreriaSCRIPT.php'); ?>





