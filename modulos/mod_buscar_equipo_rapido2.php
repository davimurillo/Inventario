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
  <style>
	"," :after, *:before{
		margin: 0;
		padding: 0;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;		
	}
	#contenedor_carga{
			background-color: rgba(255,255,255, 0.95);
			height: 100%;
			width: 100%;
			position: fixed;
			-webkit-transition: all 1s ease;
			-o-transition: all 1s ease;
			transition: all 1s ease;
			z-index: 10000; 
	}
	#carga{
		border: 15px solid #ccc;
		border-top-color: #006699;
		border-top-style: groove;
		height: 100px;
		width: 100px;
		border-radius: 100%;
		
		position: absolute;
		top:0;
		left:0;
		right:0;
		bottom:0;
		margin:auto;
		-webkit-animation: girar 1.5s linear infinite;
		-o-animation: girar 1.5s linear infinite;
		animation: girar 1.5s linear infinite;
		
	}
	
	@keyframes girar{
			from { transform: rotate(0deg); }
			to { transform: rotate(360deg); }
	}
  </style>
</head>
<body >
	<div id="contenedor">
		<div id="carga"></div>
	</div>
<div >
  
<?php 

require_once('common.php'); checkUser(); 


?>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px" >
					 Coloque el N° de serial o Descripción del equipo a buscar.
					 </div>
					 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
						<form   action="mod_buscar_equipo_rapido2.php" method="GET">
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
$id_producto=$producto>0? intval($producto) : 0 ;

 

 $sql="SELECT id_producto, tx_serial, tx_ngr, tx_placati, tx_descripcion, tx_tipo_producto, tx_marca, tx_modelo, CASE WHEN (SELECT ultimo_movimiento FROM mod_producto WHERE id_producto=d.id_producto)=1 THEN  (SELECT cantidad FROM stock WHERE id_producto=d.id_producto LIMIT 1)  ELSE '0' END  as existencia  FROM vie_tbl_equipos d WHERE id_producto = ".$id_producto." OR upper(tx_serial) LIKE '%".strtoupper($producto)."%' OR upper(tx_ngr) LIKE '%".strtoupper($producto)."%' OR upper(tx_placati) LIKE '%".strtoupper($producto)."%' OR  upper(tx_descripcion) LIKE '%".strtoupper($producto)."%' OR upper(tx_tipo_producto) LIKE '%".strtoupper($producto)."%' OR upper(tx_marca) LIKE '%".strtoupper($producto)."%' OR  upper(tx_modelo) LIKE '%".strtoupper($producto)."%' OR  upper(tx_estatus) LIKE '%".strtoupper($producto)."%'";
		  
		 
	$res=abredatabase(g_BaseDatos,$sql);
	

	?>
	<div class="col-xs-12" style="margin-top:20px">
	  <?php echo "Número de Elementos encontrados: (".dnumerofilas($res).")";
	?>
	</div>
	<div class="col-xs-12" style="margin-top:20px">
	<table id="resultado_busqueda" class="table table-bordered table-hover" >
                <thead>
                <tr  align="center">
                  
                  <th align="center" style="border: 1px #000 solid;">SERIAL</th>
				  <th align="center" style="border: 1px #000 solid;">DESCRIPCION</th>
                  <th align="center" style="border: 1px #000 solid;">TIPO</th>
                  <th align="center" style="border: 1px #000 solid;">MARCA</th>
                  <th align="center" style="border: 1px #000 solid;">MODELO</th>
		<th align="center" style="border: 1px #000 solid;">N° PLACA NGR</th>
		<th align="center" style="border: 1px #000 solid;">N° PLACA TI</th>
		  <th align="center" style="border: 1px #000 solid;">EXISTENCIA</th>

                </tr>
                </thead>
                <tbody>
				<?php while($row2=dregistro($res)){ ?>
                <tr style="border: 1px #000 solid;" onclick="javascript:abrir_historial(<?php echo $row2['id_producto']; ?>);">
				  <td style="border: 1px #000 solid;"><?php echo $row2['tx_serial']; ?></td>
                  <td style="border: 1px #000 solid;"><?php echo $row2['tx_descripcion']; ?></td>
                  <td style="border: 1px #000 solid;"><?php echo $row2['tx_tipo_producto']; ?></td>
                  <td align="center" style="border: 1px #000 solid;"> <?php echo $row2['tx_marca']; ?></td>
                  <td align="right" style="border: 1px #000 solid;"><?php echo $row2['tx_modelo']; ?></td>
		  <td align="center" style="border: 1px #000 solid;"><?php echo $row2['tx_ngr']; ?></td>
		  <td align="center" style="border: 1px #000 solid;"><?php echo $row2['tx_placati']; ?></td>
		  <td align="center" style="border: 1px #000 solid;"><?php echo $row2['existencia']; ?></td>
                </tr>
				<?php } cierradatabase(); ?>
				</tbody>
	</table>
	</div>
	<?php }
	?>					
 </section>
    <!-- /.content -->
 

  <!-- Ventana para historial del equipo -->
	<div class="modal fade" tabindex="-1" id="myModal_importar" role="dialog" style="color:#999; ">
	  <div class="modal-dialog" style="width:90%">
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


<script>
$( window ).load(function() {
		
		var contenedor = document.getElementById('contenedor');
		contenedor.style.visibility = 'hidden';
		contenedor.style.opacity ='0';
});

function abrir_historial(id){
	$('#historial').load('mod_historial_producto.php',{'id_producto':id});
	$('#myModal_importar').modal('show'); 
}	
</script>
</body>
</html>
						

