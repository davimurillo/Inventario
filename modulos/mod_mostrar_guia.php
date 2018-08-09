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
    

    <!-- Main content -->
    <section class="content" style="margin-top:30px">
		<div align="center" ><button id="printer" class="btn btn-success"><i class="fa fa-print"></i> IMPRIMIR</button></div>
		<div id="area_imprimir" class="row">
			
			<div align="left" class="col-lg-12" style="margin-top:30px; padding-left:180px">
			<?php 
			$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
			$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 
			//echo $dias[date('w')].", ".date('d')." de ".$meses[date('n')-1]. " de ".date('Y') ;

			?>
			</div>
			
			<?php 
				$sql="SELECT tx_direccion,tx_descripcion, tx_responsable, tx_ticket, tx_guia, (SELECT tx_tipo FROM vie_tbl_tipo_motivos_entrada WHERE id_tipo_objeto=a.id_motivo) AS motivo FROM mod_movimiento a, mod_tienda b WHERE a.tx_guia='".$_GET['guia']."' and a.fe_movimiento='".$_GET['fecha']."' and a.id_tienda=b.id_tienda";
				$res=abredatabase(g_BaseDatos,$sql);
				$row=dregistro($res);
				
			?>
				
			<div class="col-lg-12">
				
				<div class="col-lg-12" style="margin-top:20px">
					<table class="table" width="100%">
					<thead>
					<tr>
						<th colspan="7" >
							<div  align="left" class="col-lg-12" >
								<div><img src="repositorio/logos_cintillos/logo.jpg"  ></div>
								<h3>GUÍA DE RECEPCIÓN EN ALMACEN</h3><hr>
					
							</div> 
							<div   class="col-lg-12" >
								<table width="100%"  class="table" style="margin-top:20px;  ">
									<tr>
										<td><label>FECHA:</label> <?php echo $_GET['fecha']; ?></td>
										<td>MOTIVO: <?php echo $row['motivo']; ?></td>
									<tr>
								
									<tr>
										<td><label>ORIGEN:</label> <?php echo $row['tx_descripcion']; ?>-<?php echo $row['tx_direccion']; ?></td>
										<td><label>RECIBIDO POR:</label> <?php echo $row['tx_responsable']; ?> </td>
									<tr>
									<tr>
										<td><label>N° DE GUIA:</label> <?php echo $row['tx_guia']; ?></td>
										<td><label>TICKET:</label> <?php echo $row['tx_ticket']; ?></td>
									<tr>
									<tr>
										<td></td>
										<td></td>
									<tr>
								</table>
							</div>
			
							<div class="col-lg-12" align="center">
									<H1>DETALLE DE LA RECEPCIÓN</H1>
							</div>
						</th>
					</tr>
					<tr style="background-color:#ccc">
						<th> # </th>
						<th> Cantidad </th>
						<th> Tipo </th>
						<th> Marca </th>
						<th> Modelo </th>
						<th> Serial </th>
						<th> Accesorios </th>
					</tr>
					</thead>
					<tbody>
					<?php 
					$c=0;
				$sql="SELECT nx_cantidad,tx_nombre_tipo, tx_marca, tx_modelo, tx_serial, tx_accesorios FROM vie_tbl_movimiento a WHERE a.tx_guia='".$_GET['guia']."'  and a.fe_movimiento='".$_GET['fecha']."'";
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
				</tbody>
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







