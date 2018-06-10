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
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  
<?php 

require_once('common.php'); checkUser(); 

	

?>
 <!-- Head app -->

  <?php require('cabecera.php'); ?> 
  
  <!-- Left side column. contains the logo and sidebar -->
  
  <?php $formulario=1; include('barra_izquierda.php'); ?> 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px" >
					 Coloque el N° de serial o Descripción del equipo a buscar.
					 </div>
					 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
						<form   action="mod_buscar_equipo_rapido.php" method="GET">
							<div class="input-group "   >
				
								<input name="buscar" id="buscar" type="text" class="form-control" placeholder="Buscar Equipo" autofocus>
							<span class="input-group-btn">
								<button class="btn btn-default form-control" type="submit"><i class="fa fa-search"></i></button>
							</span>
					
							</div>
						</form>
					</div>
<?php
	
if ($_GET['buscar']==""){	
?>
		<script>alert('Debe colocar un serial, descripcion ó n° de placa del equipo a buscar');</script>
<?php 
}else{


$producto = isset($_GET['buscar']) ? strtoupper ($_GET['buscar']) : " "; 

 

  $sql="SELECT id_producto, tx_serial, tx_descripcion, tx_tipo_producto, tx_marca, tx_modelo, CASE WHEN (SELECT ultimo_movimiento FROM mod_producto WHERE id_producto=d.id_producto)=1 THEN  (SELECT cantidad FROM stock WHERE id_producto=d.id_producto LIMIT 1)  ELSE '0' END  as existencia  FROM vie_tbl_equipos d WHERE tx_serial LIKE '%".$producto."%' OR  tx_descripcion LIKE '%".$producto."%' OR tx_tipo_producto LIKE '%".$producto."%' OR tx_marca LIKE '%".$producto."%' OR  tx_modelo LIKE '%".$producto."%'  ";
		  
		 
	$res=abredatabase(g_BaseDatos,$sql);
	

	?>
	<div class="col-xs-12" style="margin-top:20px">
	  <?php echo "Número de Elementos encontrados: (".dnumerofilas($res).")";
	?>
	</div>
	<div class="col-xs-12" style="margin-top:20px">
	<table id="resultado_busqueda" class="table table-bordered table-hover">
                <thead>
                <tr>
                  
                  <th>SERIAL</th>
				  <th>DESCRIPCION</th>
                  <th>TIPO</th>
                  <th>MARCA</th>
                  <th>MODELO</th>
				  <th>EXISTENCIA</th>
                </tr>
                </thead>
                <tbody>
				<?php while($row2=dregistro($res)){ ?>
                <tr onclick="javascript:abrir_historial(<?php echo $row2['id_producto']; ?>);">
				  <td><?php echo $row2['tx_serial']; ?></td>
                  <td><?php echo $row2['tx_descripcion']; ?></td>
                  <td><?php echo $row2['tx_tipo_producto']; ?></td>
                  <td align="center"> <?php echo $row2['tx_marca']; ?></td>
                  <td align="right"><?php echo $row2['tx_modelo']; ?></td>
				  <td align="center" ><?php echo $row2['existencia']; ?></td>
                </tr>
				<?php } cierradatabase(); ?>
				</tbody>
	</table>
	</div>
	<?php }
	?>					
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

  <!-- Ventana para historial del equipo -->
	<div class="modal fade" tabindex="-1" id="myModal_importar" role="dialog" style="color:#999; ">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content" >
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h2 class="modal-title"><span class="fa fa-upload" style="margin-right:10px"></span>Historial de Movimientos del Equipo</h2>
		  </div>
		  <div class="modal-body" id="historial" >
				
				
			
		  </div>
		  <div class="modal-footer"  style="text-align:center">
				  
			
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
  
  <!-- Control 
  <?php //include('barra_derecha.php'); ?>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control 
  <div class="control-sidebar-bg"></div>  Sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="plugins/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<script>
function abrir_historial(id){
	$('#historial').load('mod_historial_producto.php',{'id_producto':id});
	$('#myModal_importar').modal('show'); 
}	
</script>
<script>
$( window ).load(function() {
		
		var contenedor = document.getElementById('contenedor_carga');
		contenedor.style.visibility = 'hidden';
		contenedor.style.opacity ='0';
});
</script>
</body>
</html>
						

