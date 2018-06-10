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
</head>
<body>
<?php
require_once('common.php'); checkUser();

?>
<div class="wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registro
        <small>Equipos y/o Accesorios</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#">Registro</a></li>
        <li >Equipos y/o Accesorios</li>
        <li class="active">Reemplazo de Datos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="box box-success">
            <div class="box-header">
              <h3 class="box-title">SELECCION DE DATOS A REEMPLAZAR</h3>
            </div>
            <div class="box-body">

	<div class="col-xs-6">
		<label>TIPO DE DATO</label>
		<select id="reemplazo" class="form-control select2" required="required"  onchange="javascript:enviar(this.value);">
		  <option value="">---Seleccione---</option>
		  <option value="tx_marca" onclick="javascript:enviar(0);">MARCA</option>
		  <option value="tx_modelo" onclick="javascript:enviar(0);">MODELO</option>
		  <optgroup  label="TIPO DE PRODUCTIO">
			<?php 
							   $sql="SELECT id_tipo_producto, tx_nombre_tipo FROM
									cfg_tipo_producto ORDER BY tx_nombre_tipo";
							   $res=abredatabase(g_BaseDatos,$sql);
							   WHILE ($row=dregistro($res)){
							   ?>
							  <option value="<?php echo $row['id_tipo_producto']; ?>" onclick="javascript:enviar(<?php echo $row['id_tipo_producto']; ?>);"><?php echo $row['tx_nombre_tipo']; ?></option>
							  
							   <?php } cierradatabase(); ?>
		  </optgroup>
		  
		</select>
	</div>
	<div class="col-xs-6" >
		<label>NUEVO VALOR</label>
		<div id="identificacion_valor"> </div>
		<input id="valor" class="form-control" type="text" placeholder="Escriba el valor" required="required" >
	</div>
	 
	</div>
	<div class="box-footer text-center">
		<button id="accion" type="button" class="btn btn-success" >Reemplazar</button>
		<div id="resultado" class="col-xs-12" style="margin-top:20px">
		</div>
	</div>
	</div>
	</section>

</div>

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script>
$('#valor').hide();
	function enviar(id){
		if (id>0){
			$('#valor').hide();
			$('#identificacion_valor').html('Tipo: '+$('#reemplazo option:selected').text());
			$('#valor').val(id);
		}else{
			$('#identificacion_valor').val('');
			$('#identificacion_valor').hide();
			$('#valor').show();
			$('#valor').val('');
			$('#valor').focus();
		}
	}
	$('#accion').click(function() {
		if ($('#valor').val()>0){
			$('#resultado').html('tipo - '+$('#valor').val()+' <?php echo $_GET['id']; ?>' );
		}else{
			$('#resultado').html($('#reemplazo option:selected').text()+' - '+$('#valor').val() +' <?php echo $_GET['id']; ?>');
		}
	});
</script>


</body>
</html>
