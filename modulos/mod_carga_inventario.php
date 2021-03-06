﻿<?php require_once('common.php'); checkUser(); ?>
<!-- 
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  APICES INVENTARIO version 1.0.2					                           #
##  Developed by:  	CH TECHNOLOGY (info@ch.net.pe)   (+51) 480 0874            #
##  License:       	GNU LGPL v.3                                               #
##  Site:			chtechnology.com.pe                         			   #
##  Copyleft:     	CH TECHNOLOGY 2017 - 2018. All rights reserved.		       #
##  Last changed:  	0 DE 2018                                      #
##  AUTOR:  		DAVI MURILLO		                                       #
##                                                                             #
################################################################################
-->
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
<?php
if (isset($_FILES["archivo"]["name"])){

	$nombre_archivo=$_FILES["archivo"]["name"];
	$tamano_archivo=$_FILES["archivo"]["size"];
	$mensaje="";
	$target_dir = "repositorio/importes/";
	$target_file = $target_dir . basename($_FILES["archivo"]["name"]);
	$uploadOk = 1;
	if (file_exists($target_file)) {
		unlink($target_file);
	}
	
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = filesize($_FILES["archivo"]["tmp_name"]);
		if($check !== false) {
			$mensaje.= "Archivo es un Excel - " . $check["mime"] . ".<br>";
			$uploadOk = 1;
		} else {
			$mensaje.=  "El acrhivo no cumple con las condiciones de carga.<br>";
			$uploadOk = 1;
		}
	}
	// Check if file already exists
	if (file_exists($target_file)) {
		$mensaje.= "Lo siento el archivo ya existe, cambie el nombre o vuelva a intentar.<br>";
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["archivo"]["size"] > 5000000) {
		$mensaje.= "Sorry, your file is too large. <br>";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "xls" && $imageFileType != "xlsx") {
		$mensaje.= "Sorry, only XLS, XLXS files are allowed.<br>";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$mensaje.= "No se pudo cargar su archivo <br>";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $target_file)) {
			$mensaje.= "El Archivo ". basename( $_FILES["archivo"]["name"]). " fue Cargador con exito.";
		} else {
			$mensaje.= "Sorry, there was an error uploading your file.";
		}
	}
}
?>
<div class="wrapper">
 <!-- Head app -->
  <?php require('cabecera.php'); ?> 
  <!-- Left side column. contains the logo and sidebar -->
  <?php $formulario=21;  include('barra_izquierda.php'); ?> 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registro
        <small>Carga de Inventario</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Carga de Inventario</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
		<div class="col-md-12">
		<div class="box box-info">
		<div class="box-header with-border">
		<h3 class="box-title">Archivo de Carga</h3>
		<div class="box-tools pull-right">
		<button type="button" class="btn btn-box-tool" data-widget="collapse">
			<i class="fa fa-minus"></i>
		</button>
		</div>
		<div id="form_equipo" class="box-body">
			<div class="col-xs-12">
				<div class="callout callout-info">
					<h4>Información Importante!</h4>
					<p>Seleccione el archivo que desea buscar, recuerde que este debe estar en el formato prestablecido por el personal de NGR, y luego pulse cargar</p>
				</div>
			</div>
			<div class="col-xs-12">
				<form action="mod_carga_inventario.php" method="post" enctype="multipart/form-data">
					<div class="col-xs-10">
						Seleccione el Archivo:
						<input class="form-control" type="file" name="archivo" id="archivo">
					</div>
					<div class="col-xs-2">
						<button type="submit" class="btn btn-primary btn-sm"  style="margin-top:18px">
							<i class="fa fa-search"></i> 
								CARGAR
						</button>
					</div>
				</form>
			</div>
		</div>
		</div>
		</div>
		</div>
		<div class="col-xs-6">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Resultado del Análisis</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse">
								<i class="fa fa-minus"></i>
							</button>
						</div>
						<div  class="box-body">
							<?php	
								if (isset($_FILES["archivo"]["name"])){
										if ($uploadOk==1) echo "OK <br>"; else echo "Error <br>"; 
										echo $mensaje;
										echo "<hr>";
								}
							?>
							<div id="analisis" class="col-md-12">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title">Resultado de la Carga</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse">
								<i class="fa fa-minus"></i>
							</button>
						</div>
						<div id="resultados" class="box-body">
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
      <b>Version</b> 2.3.8
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
</body>
</html>
<?php require_once('libreriaSCRIPT.php'); ?>
<?php if ($uploadOk==1){
	echo '<script>$("#analisis").load("mod_carga_inventario_analisis.php",{ archivo: "'.$target_file.'"});</script>';
	}
?>
