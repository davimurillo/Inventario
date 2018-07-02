<?php require_once('common.php'); checkUser(); ?>
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
	  <title>APICES|Inventario Ver 1.0.2</title>
	  <link href="../img/logos/apices.png" rel="shortcut icon" type="image/x-icon" />
	  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	  <?php require_once('libreriaCSS.php');  ?>
	  <style>
		.table-striped>tbody>tr:nth-child(odd)>td, 
		.table-striped>tbody>tr:nth-child(odd)>th {
			background-color: #CCC; // Choose your own color here
		}
	  </style>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
		<div class="wrapper">
			<?php require('cabecera.php'); ?> 
			<?php $formulario=1; include('barra_izquierda.php'); ?> 
			<?php if ($_SESSION['rol']==5){ 
				echo "<script>window.location.href='mod_control_salidas.php';</script>";
			} else { ?>
			<div class="content-wrapper">
				<?php if ($_SESSION['rol']!=1) { ?> 
				<section class="content-header">
				  <h1>Dashboard<small>Control panel</small></h1>
				  <ol class="breadcrumb">
					<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Dashboard</li>
				  </ol>
				</section>
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
							<a href="mod_registro_equipos_accesorios.php">
								<div class="col-md-3 ">
								  <div class="info-box">
									<span class="info-box-icon bg-green"><i class="fa fa-desktop"></i></span>
									<div class="info-box-content">
									  <span class="info-box-text"></span>
									  <span class="info-box-number">Maestro</span>
									  Haga Clic Aquí 
									</div>
								  </div>
								</div>
							</a>
							
							<a href="mod_control_entradas.php">
								<div class="col-md-3 ">
								  <div class="info-box">
									<span class="info-box-icon bg-aqua"><i class="fa fa-download"></i></span>
									<div class="info-box-content">
									  <span class="info-box-text"></span>
									  <span class="info-box-number">Entradas</span>
									  Haga Clic Aquí 
									</div>
								  </div>
								</div>
							</a>
							
							<a href="mod_control_salidas.php">
								<div class="col-md-3 ">
								  <div class="info-box">
									<span class="info-box-icon bg-red"><i class="fa fa-upload"></i></span>
									<div class="info-box-content">
									  <span class="info-box-text"></span>
									  <span class="info-box-number">Salidas</span>
										Haga Clic Aquí 
									</div>
								  </div>
								</div>
							</a>
							
							<a href="mod_reportes.php">
								<div class="col-md-3 ">
								  <div class="info-box">
									<span class="info-box-icon bg-yellow"><i class="fa fa-print"></i></span>
									<div class="info-box-content">
									  <span class="info-box-text"></span>
									  <span class="info-box-number">Reportes</span>
									  Haga Clic Aquí 
									</div>
								  </div>
								</div>
							</a>
						</div>
					</div>
					
					<div class="row" style="margin-top:20px">
						<div class="col-md-6">
							<div class="col-xs-12">
									<div class="box box-info">
										<div class="box-header with-border">
										  <h3 class="box-title">Buscar Equipos</h3>
										  <div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
											</button>
										  </div>
										</div>
										
										<div id="estadisticas_stock" class="box-body" style="height:131px">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="font-size:12px" >
													Coloque el N° de serial o Descripción del equipo a buscar.
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
													<form   action="mod_buscar_equipo_rapido.php?f=0" method="GET">
														<div class="input-group "   >
															<input name="buscar" id="buscar" type="text" class="form-control" placeholder="Buscar Equipo" autofocus>
															<span class="input-group-btn">
																<button class="btn btn-default form-control" type="submit"><i class="fa fa-search"></i></button>
															</span>
														</div>
													</form>
												</div>
											</div>				
										</div>
									</div>
							</div>
							
							<div class="col-md-12">
								<div class="box box-success">
									<div class="box-header with-border">
										<h3 class="box-title">Top 20 del Stock</h3>
										<div class="box-tools pull-right">
											<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
										</button>
									</div>
								</div>
									<div id="estadisticas_stock" class="box-body" >
										<table class="table table-bordered table-striped" style=" color:#000; width:100%" > 
											<thead>
												<tr style="background-color:#999">
													<th width="40%" style="text-align:center" >TIPO</th>
													<th width="20%" style="text-align:center" >MIN </th>
													<th width="20%" style="text-align:center" >TOTAL </th>
													<th width="10%" style="text-align:center" >ALERT </th>
												</tr>
											</head>
											<tbody>
										<?php 
										$sql="SELECT b.id_tipo_producto, b.tx_nombre_tipo, (SELECT SUM(n_stock) as n FROM mod_producto a WHERE b.id_tipo_producto=a.id_tipo_producto AND nx_orden!=0 AND  ultimo_movimiento=1  and a.estatus=5) as existencias, (nx_min) as min, nx_orden FROM  cfg_tipo_producto b WHERE nx_orden!=0  ORDER BY nx_orden, b.tx_nombre_tipo ASC LIMIT 20";
										$c=0;
										$res=abredatabase(g_BaseDatos,$sql);
										while ($row=dregistro($res)){
											$c+=1;  
										?>
												<tr>
													<td style="text-align:left" >
														<a href="reportes/07-stock_almacen_detalle.php?motivo=&proveedor=&origen=&fe_entrada=&ticket=&guia=&tipo=<?php echo $row['id_tipo_producto']; ?>" target="_blank" style="color:#000"><?php echo $row['tx_nombre_tipo']; ?>   </a>
													</td>
													<td style="text-align:center"><?php echo $row['min']; ?></td>
													<td style="text-align:center"><?php echo $row['existencias']!=null? $row['existencias'] : 0; ?></td>
													<td style="text-align:center">
													<?php 
														$color="";	
														if ($row['existencias']==null) { $color='color:red'; } 
														if ($row['existencias']<($row['min']*0.50)) { $color='color:red'; } 
														if (($row['existencias']>($row['min']*0.50)) && ($row['existencias']<$row['min'])) { $color='color:orange'; }  									
														if (($row['existencias']>$row['min'])){ $color='color:green';}  
														
														echo "<i class='fa fa-check-circle' style='".$color."'></i>";
													?>	
													</td>
												</tr>
										<?php } ?>
												<tr>
													<td colspan="5">
														<!-- //Importante Preservar el Reporte de almacen 01-stock_almacen_por_tipo.php -->
														<a href="reportes/01-stock_almacen_por_tipo.php" target="_new"> Más Info...</a>
													</td>
												</tr>
											</tbody>
										</table>
									
									</div>
								</div>
							</div>
						</div>
							
						<div class="col-md-6">
							<div class="col-xs-12">
								<div class="box box-primary">
									<div class="box-header with-border">
											<h3 class="box-title">Ubicación del Inventario</h3>
											<div class="box-tools pull-right">
												<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
											</div>
										</div>
										
									<div id="estadisticas_stock" class="box-body" >
										<table class="table table-bordered table-striped" style="font-size:14px; color:#000; width:100%" >
											<thead>
												<tr style="background-color:#999">
													<th width="70%" style="text-align:center" >UBICACION</th>
													<th width="20%" style="text-align:center" >TOTAL</th>
													<th width="10%" style="text-align:center" >%</th>
												</tr>
											</thead>
											<tbody>
											<?php 
											
												$sql="SELECT CASE WHEN id_tienda=1 THEN '1. ALMACEN' WHEN (id_tienda!=1 AND id_tienda!=579) THEN '2. TIENDAS' ELSE '3. DESCONOCIDA' END AS ubicacion , count(id_producto) as existencias, (SELECT count(id_producto) FROM mod_producto) as total FROM mod_producto  group by  ubicacion ORDER BY ubicacion";
												$c=0;
												
												$res=abredatabase(g_BaseDatos,$sql);
												while ($row=dregistro($res)){
													$c+=1; 
													$total=$row['total'];									
											?>
												<tr>
													<td style="text-align:left" ><?php echo $row['ubicacion']; ?> </td>
													<td style="text-align:right"><?php echo number_format($row['existencias'],0); ?></td>
													<td style="text-align:center"><?php  echo number_format(($row['existencias']/$row['total'])*100,2); ?></td>
												</tr>
											<?php } ?>
												  <tr style="font-weight:bold; background-color:#999">
													<td >TOTAL</td>
													<td style="text-align:right"><?php echo number_format($total,0); ?>	</td>
													<td >100.00</td>
												  </tr>
												</tbody>
											</table>
									</div>
								</div>
							</div>
							
							<div class="col-xs-12">
								<div class="box box-primary">
									<div class="box-header with-border">
											<h3 class="box-title">Resumen de Guias de Salida</h3>
											<div class="box-tools pull-right">
												<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
											</div>
										</div>
										
									<div id="estadisticas_stock" class="box-body" >
										<table class="table table-bordered table-striped" style="font-size:14px; color:#000; width:100%" >
											<thead>
												<tr style="background-color:#999">
													<th width="70%" style="text-align:center" >TIPO DE MOTIVO</th>
													<th width="30%" style="text-align:center" >TOTAL</th>
													
												</tr>
											</thead>
											<tbody>
											<?php 
											
												$sql="SELECT tabla.id_motivo, (SELECT tx_tipo FROM cfg_tipo_objeto where id_tipo_objeto=tabla.id_motivo) AS motivo, count(tabla.tx_guia) as n FROM ( SELECT id_motivo, tx_guia, count(id_motivo) as n FROM mod_movimiento WHERE id_tipo_movimiento=2 AND tx_guia like('007%') GROUP BY id_motivo, tx_guia ORDER BY id_motivo ) AS TABLA GROUP BY  tabla.id_motivo ORDER BY tabla.id_motivo ";
  
												$c=0;
												$total=0;
												$res=abredatabase(g_BaseDatos,$sql);
												while ($row=dregistro($res)){
													$c+=1; 
													$total+=$row['n'];									
											?>
												<tr>
													<td style="text-align:left" >
													<a href="reportes/21-resumen_guia_motivo_salida_detalle.php?motivo=<?php echo $row['id_motivo']; ?>&reporte=21 RESUMEN DE GUIA DE SALIDA POR MOTIVO" target="_blank" style="color:#000"><?php echo $c." - ". $row['motivo']; ?> </a></td>
													<td style="text-align:right"><?php echo number_format($row['n'],0); ?></td>
												</tr>
											<?php } ?>
												  <tr style="font-weight:bold; background-color:#999">
													<td >TOTAL</td>
													<td style="text-align:right"><?php echo number_format($total,0); ?>	</td>
												  </tr>
												</tbody>
											</table>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</section>
				<?php } else {?>
					<div >
						<img src="../img/sistema/menu_inicial.png" height="100%" width="100%">
					</div>
				<?php }?>
			</div>
			<?php } ?>
			<?php include('pie.php'); ?>
		</div>
		<?php require_once('libreriaJS.php'); ?>
		<?php require_once('libreriaSCRIPT.php'); ?>
	</body>
</html>
