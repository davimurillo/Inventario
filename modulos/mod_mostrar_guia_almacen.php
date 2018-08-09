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

</head>
<body c>

  
<?php 

require_once('common.php'); checkUser(); 

date_default_timezone_set($_SESSION['zona_horario']);
	
?>
 <!-- Head app -->

  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registro
        <small>Control de Guias</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Guía</a></li>
        <li class="active"><?php if ($_POST['id']=1) { echo "Control de Entrada"; $movimiento=1; }else{ echo "Control de Salida"; $movimiento=2; } ?> </li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<div align="center" ><button id="printer" class="btn btn-success"><i class="fa fa-print"></i> IMPRIMIR</button></div>
		<div id="area_imprimir" class="row">
			
			<div align="left" class="col-lg-12" style="margin-top:30px; padding-left:180px">
			<?php 
			$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
			$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 
			echo $dias[date('w')].", ".date('d')." de ".$meses[date('n')-1]. " de ".date('Y') ;

			?>
			</div>
			
			<?php 
				$sql="SELECT tx_direccion,tx_descripcion, tx_descripcion FROM mod_movimiento a, mod_tienda b WHERE a.tx_guia='".$_GET['guia']."' and a.id_tienda=b.id_tienda";
				$res=abredatabase(g_BaseDatos,$sql);
				$row=dregistro($res);
				
			?>
			<div class="col-lg-12" style="margin-top:20px">
			CAMINO REAL 1801 MZ.B LT.17 - SURCO - LIMA - LIMA
			</div>
			<div class="col-lg-12">
			<?php echo $row['tx_descripcion']; ?>
			</div>
			<div class="col-lg-12">
			<?php echo $_GET['guia']; ?>
			</div>
			<div class="col-lg-12" style="margin-top:20px">
			<?php echo $row['tx_descripcion']; ?>
			</div>
			<div class="col-lg-12">
			<?php echo $row['tx_direccion']; ?>
			</div>
			
			<div class="col-lg-12" style="margin-top:60px">
				<table class="table">
					<tr>
						<th> # </th>
						<th> Cantidad </th>
						<th> Tipo </th>
						<th> Marca </th>
						<th> Modelo </th>
						<th> Serial </th>
						<th> Accesorios </th>
					</tr>
					<?php 
					$c=0;
				$sql="SELECT nx_cantidad,tx_nombre_tipo, tx_marca, tx_modelo, tx_serial, tx_accesorios FROM vie_tbl_movimiento a WHERE a.tx_guia='".$_GET['guia']."'";
				$res=abredatabase(g_BaseDatos,$sql);
				while($row=dregistro($res)){
				
			?>
					<tr>
						<td> <?php echo $c+=1; ?> </td>
						<td> <?php echo $row['nx_cantidad']; ?> </td>
						<td> <?php echo $row['tx_nombre_tipo']; ?> </td>
						<td> <?php echo $row['tx_marca']; ?> </td>
						<td> <?php echo $row['tx_modelo']; ?> </td>
						<td> <?php echo $row['tx_serial']; ?> </td>
						<td> <?php echo $row['tx_accesorios']; ?> </td>
					</tr>
				<?php } cierradatabase();?>
				</table>
			</div>
			
			
		</div>
		
    </section>
    <!-- /.content -->
  </div>
 




<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>

<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page script -->
<script>
$(document).ready(function()
{
	$("#printer").click(function ()
	{
		
	 var ficha = document.getElementById("area_imprimir");
	  var ventimp = window.open(' ', 'popimpr');
	  ventimp.document.write( ficha.innerHTML );
	  ventimp.document.close();
	  ventimp.print( );
	  ventimp.close();
		
	});
});
</script>
</body>
</html>







