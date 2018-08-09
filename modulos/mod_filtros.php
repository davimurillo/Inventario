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
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
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
  
   <script src='https://printjs-4de6.kxcdn.com/print.min.js'></script>
 <link rel="stylesheet" type="text/css" href='	https://printjs-4de6.kxcdn.com/print.min.css'>
 

 
  <style>
	#left-menu .sub-left-menu li ripple:hover {
  background: #ccc;
  -webkit-text-decoration: none;
  -moz-text-decoration: none;
  -ms-text-decoration: none;
  -o-text-decoration: none;
  text-decoration: none;
}
#left-menu .sub-left-menu li {
  line-height: 44px;
  font-size: 12px;
  text-decoration: none;
  list-style:none;
  padding-left:0px;
}
.filtro {
	 border-bottom: 1px solid #ccc;
	 font-weight:bold;
	 font-size:14px;
}
.sub_filtros{
	margin-top:5px;
	margin-bottom:5px;
}
#left-menu .sub-left-menu a {
  color: #918C8C;
  font-size: 12px;
  line-height: 30px;
}
a,
a:hover {
  -webkit-text-decoration: none;
  -moz-text-decoration: none;
  -ms-text-decoration: none;
  -o-text-decoration: none;
  text-decoration: none;
  color: #888;
  font-weight: bold;
}
  </style>
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
        <li class="active">Filtros</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
		<div class="row">
		  <div class="col-md-3">
			<!-- general form elements -->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><strong> <i class="fa fa-filter" ></i> - FILTROS</strong></h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
					</div>
				</div>
				<div class="box-body">
					<div align="center">
						<button id="terminar" type="submit" class="btn btn-success">APLICAR</button>
					</div>
					<div id="left-menu">
						<div class="sub-left-menu" >
							<ul class="nav nav-list scroll ">
								<li class="ripple filtro" data-toggle="collapse" data-target="#tipo_reporte">
									TIPO REPORTE
								</li>
								<ul id="tipo_reporte"  class="collapse" style="padding-left:0px">
									<li>
										<div value=""><input id="tipo_reporte" name="1" type="checkbox" > Equipos y/o Accesorios</div>
									</li>
									<li>
										<div value=""><input id="tipo_reporte" name="2" type="checkbox" > Motivimientos</div>
									</li>
								</ul>

								<li class="ripple filtro" data-toggle="collapse" data-target="#demoGENERALES">
									UBICACION GENERAL
								</li>
								<?php 
									$sql="SELECT ubicacion,ubicacion_condicion,sum(existencias) as existencias, max(total) as total, min(n_orden) as n_orden  FROM (SELECT CASE WHEN id_tienda=1 THEN 'ALMACEN' WHEN (id_tienda!=1 AND id_tienda!=579) THEN 'TIENDAS' ELSE 'DESCONOCIDA' END AS ubicacion, 
									CASE WHEN id_tienda=1 THEN '1' WHEN (id_tienda!=1 AND id_tienda!=579) THEN 'id_tienda!=1 AND id_tienda!=579' ELSE 'id_tienda=579' END AS ubicacion_condicion, count(id_producto) as existencias, (SELECT count(id_producto) FROM mod_producto) as total, min(id_tienda) as n_orden FROM mod_producto  
									group by  id_tienda ORDER BY n_orden) as tabla GROUP BY ubicacion,ubicacion_condicion ORDER BY n_orden";
									$res=abredatabase(g_BaseDatos,$sql);
									$total=0;
								?>
								<ul id="demoGENERALES"  class="collapse" style="padding-left:0px">
										<li>
											<?php while($row=dregistro($res)){ ?>
											
											<div value=""><input id="a.id_tienda" name="<?php echo $row['ubicacion_condicion']; ?>" type="checkbox" > <?php echo $row['ubicacion']." ( ".$row['existencias']." )"; $total+=$row['existencias']; ?></div>
											<?php } ?>
											
											<div value=""><input id="ubicacion_tienda"  name="TODOS" type="checkbox" > TODOS ( <?php echo $total; ?> )</div>
											
													  
										</li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoUN">
									UNIDAD DE NEGOCIO
								</li>
								<ul id="demoUN"  class="collapse" style="padding-left:0px">
									<li>
										
											<div>
												<div > <input id="id_empresa" name=">0" type="checkbox">TODOS ( <?php echo $total; ?> )</div>
											  <?php 
											  $sql="SELECT ubicacion, empresaID, empresa, sum(n_equipos) as n_equipos FROM (SELECT CASE WHEN b.id_tienda=1 THEN 'ALMACEN' WHEN (b.id_tienda!=1 AND b.id_tienda!=579) THEN 'TIENDAS' ELSE 'DESCONOCIDA' END AS ubicacion, b.id_empresa, count(id_empresa) as n_equipos,
														(select tx_abreviatura FROM mod_empresa WHERE id_empresa=b.id_empresa) as empresa,  b.id_empresa as empresaID
														FROM mod_producto a, mod_tienda b WHERE a.id_tienda=b.id_tienda group by  b.id_empresa, b.id_tienda ORDER BY b.id_empresa) tabla group by empresaID, empresa, ubicacion order by ubicacion";
												$res=abredatabase(g_BaseDatos,$sql);
												$total=0;
											   WHILE ($row=dregistro($res)){
												   $tienda_empresa=$row['ubicacion']=="ALMACEN" || $row['ubicacion']=="DESCONOCIDA"? $row['ubicacion'] : "";
											   ?>
											  <div  ><input id="a.id_unidad_negocio" name="<?php echo $row['empresaid']; ?>" type="checkbox"> <?php echo $row['empresa']. " ".$tienda_empresa." ( ".$row['n_equipos']." )"; ?></div>
											   <?php } cierradatabase(); ?>
											</div>
									</li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoUBICACION">
									UBICACIÓN
								</li>
								<ul id="demoUBICACION"  class="collapse" style="padding-left:0px">
									<li>
											<div style="overflow-y:scroll; height:300px">
												<div value="">TODOS</div>
												<?php 
													$sql="SELECT id_tienda,(tx_descripcion) as tx_nombre FROM mod_tienda WHERE estatus=18";
													$res=abredatabase(g_BaseDatos,$sql);
													WHILE ($row=dregistro($res)){
												?>
												<div id="<?php echo $row['id_tienda']; ?>" > <input id="b.id_tienda" name="<?php echo $row['id_tienda']; ?>" type="checkbox"> <?php echo $row['tx_nombre']; ?></div>
												<?php } cierradatabase(); ?>
											</div>
									</li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoPROVEE">
									PROVEEDOR
								</li>
								<ul id="demoPROVEE"  class="collapse" style="padding-left:0px">
									<li>
											<div style="overflow-y:scroll; height:300px">
												<div value="">TODOS</div>
												<?php 
													$sql="SELECT id_proveedor,tx_nombre FROM mod_proveedor WHERE estatus=16";
													$res=abredatabase(g_BaseDatos,$sql);
													WHILE ($row=dregistro($res)){
												?>
												<div ><input id="a.id_proveedor" name="<?php echo $row['id_proveedor']; ?>" type="checkbox"> <?php echo $row['tx_nombre']; ?></div>
												<?php } cierradatabase(); ?>
											</div>
									</li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoMARCA">
									MARCA
								</li>
								<ul id="demoMARCA"  class="collapse" style="padding-left:0px">
									<li>
											<div style="overflow-y:scroll; height:300px">
												<div value="">TODOS</div>
												<?php 
													$sql="SELECT id_marca,tx_marca FROM cfg_tipo_marcas ORDER BY tx_marca";
													$res=abredatabase(g_BaseDatos,$sql);
													WHILE ($row=dregistro($res)){
												?>
												<div ><input id="a.id_tipo_marca" name="<?php echo $row['id_marca']; ?>" type="checkbox"><?php echo $row['tx_marca']; ?></div>
												<?php } cierradatabase(); ?>
											</div>
									</li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoGarantia">
									GARANTIA
								</li>
								<ul id="demoGarantia"  class="collapse" style="padding-left:0px">
									<li>
											<div style="overflow-y:scroll; height:300px">
												<div value="">TODOS</div>
													<?php 
														$sql="SELECT id_tipo_objeto,tx_tipo FROM cfg_tipo_objeto WHERE tx_objeto='garantia_producto' order by nu_orden";
														$res=abredatabase(g_BaseDatos,$sql);
														WHILE ($row=dregistro($res)){
													?>
													<div ><input id="a.id_garantia" name="<?php echo $row['id_tipo_objeto']; ?>" type="checkbox"><?php echo $row['tx_tipo']; ?></div>
													<?php } cierradatabase(); ?>
											</div>
									</li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoTIPO">
									TIPO DE EQUIPO
								</li>
								<ul id="demoTIPO"  class="collapse" style="padding-left:0px">
									<li>
											<div style="overflow-y:scroll; height:300px">
												<div value="">TODOS</div>
													<?php 
														$sql="SELECT id_tipo_producto,tx_nombre_tipo FROM cfg_tipo_producto";
														$res=abredatabase(g_BaseDatos,$sql);
														WHILE ($row=dregistro($res)){
													?>
													<div ><input id="a.id_tipo_producto" name="<?php echo $row['id_tipo_producto']; ?>" type="checkbox"><?php echo $row['tx_nombre_tipo']; ?></div>
													<?php } cierradatabase(); ?>
											</div>
									</li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoMOTIVO">
									MOTIVO
								</li>
								<ul id="demoMOTIVO"  class="collapse" style="padding-left:0px">
									<li>
										<div >
											<label align="left">TIPO DE MOTIVO</label>
											<select id="seleccion_tipo_motivo" class="form-control select2"  required="required" Onchange="javascript:cambiar_tipo_motivo(this.value);" style="width:100%">
												<option value="">Seleccione...</option>
												<option value="1">ENTRADAS</option>
												<option value="2">SALIDAS</option>
											</select>
										</div>
										<div id="tipo_motivo" >
											<label align="left">MOTIVO</label>
												<div value="">...Seleccione...</div>						 
										</div>
									</li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoPRODUCTO">
									EQUIPOS Y ACCESORIOS
								</li>
								<ul id="demoPRODUCTO"  class="collapse" style="padding-left:0px">
									<li>
											<div >
												<div class="input-group date sub_filtros">
													<div class="input-group-addon">
													<i class="fa fa-arrow-circle-right"></i>
													</div>
													<input id="a.tx_nu_motivo" type="text" class="form-control pull-right" placeholder="N° de Motivo" required="required">
												</div>
											</div>
											<div >
												<div class="input-group date sub_filtros">
													<div class="input-group-addon">
													<i class="fa fa-arrow-circle-right"></i>
													</div>
													<input id="a.tx_nu_cotizacion" type="text" class="form-control pull-right" placeholder="N° de Cotización" required="required">
												</div>
											</div>
											<div >
												<div class="input-group date sub_filtros">
													<div class="input-group-addon">
													<i class="fa fa-arrow-circle-right"></i>
													</div>
													<input id="a.tx_guia_remision" type="text" class="form-control pull-right" placeholder="N° de Guía de Remisión" required="required">
												</div>
											</div>
											<div >
												<div class="input-group date sub_filtros">
													<div class="input-group-addon">
													<i class="fa fa-arrow-circle-right"></i>
													</div>
													<input id="a.tx_modelo" type="text" class="form-control pull-right" placeholder="Modelo" required="required">
												</div>
											</div>
										<div >
											<div class="input-group date sub_filtros">
												<div class="input-group-addon">
												<i class="fa fa-arrow-circle-right"></i>
												</div>
												<input id="a.tx_serial" type="text" class="form-control pull-right" placeholder="N° de Serial" required="required">
											</div>
										</div>
										<div >
											<div class="input-group date sub_filtros">
												<div class="input-group-addon">
												<i class="fa fa-arrow-circle-right"></i>
												</div>
												<input id="a.tx_ngr" type="text" class="form-control pull-right" placeholder="N° de Placa Ngr" required="required">
											</div>
										</div>
										<div >
											<div class="input-group date sub_filtros">
												<div class="input-group-addon">
												<i class="fa fa-arrow-circle-right"></i>
												</div>
												<input id="a.tx_placati" type="text" class="form-control pull-right" placeholder="N° de Placa ti" required="required">
											</div>
										</div>
											<div >
												<div class="input-group sub_filtros">
													<div class="input-group-addon">
														<i class="fa fa-calendar"></i>
													</div>
													<input id="a.fe_creacion" type="text" class="form-control pull-right" placeholder="Fecha Ingreso" required="required" value="">
												</div>
											</div>
											<div >
												<div class="input-group sub_filtros">
													<div class="input-group-addon">
														<i class="fa fa-calendar"></i>
													</div>
													<input id="fe_vencimiento" type="text" class="form-control pull-right" placeholder="Fecha Vencimiento" required="required" value="">
												</div>
											</div>
									<li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoTIPOMOVI">
									TIPO DE MOVIMIENTOS
								</li>
								<ul id="demoTIPOMOVI"  class="collapse" style="padding-left:0px">
									<li>
											<div>TODOS</div>
											<div><input id="b.id_tipo_movimiento" name="1" type="checkbox">ENTRADA</div>
											<div><input id="b.id_tipo_movimiento" name="2" type="checkbox">SALIDAS</div>
									</li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoTIPOGUIA">
									TIPO DE GUIA
								</li>
								<ul id="demoTIPOGUIA"  class="collapse" style="padding-left:0px">
									<li>
											<div><input id="b.id_tipo_guia" name="95" type="checkbox">GUIA EXTERNA</div>
											<div><input id="b.id_tipo_guia" name="96" type="checkbox">GUIA INTERNA</div>
									</li>
								</ul>
								
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoMOVI">
									MOVIMIENTOS
								</li>
								<ul id="demoMOVI"  class="collapse" style="padding-left:0px">
									<li>
										<div >
												<div class="input-group sub_filtros">
													<div class="input-group-addon">
														<i class="fa fa-arrow-circle-right"></i>
													</div>
													<input id="tx_ticket" type="text" class="form-control pull-right" placeholder="N° de ticket" required="required">
												</div>
											</div>
											
											<div >
												<div class="input-group sub_filtros">
													<div class="input-group-addon">
														<i class="fa fa-arrow-circle-right"></i>
													</div>
													<input id="tx_guia" type="text" class="form-control pull-right" placeholder="N° de Guia" required="required">
												</div>
											</div>
											<div >
												<div class="input-group sub_filtros">
													<div class="input-group-addon">
														<i class="fa fa-calendar"></i>
													</div>
													<input id="fe_movimiento" type="text" class="form-control pull-right" placeholder="Fecha desde" required="required" value="">
												</div>
											</div>
											<div >
												<div class="input-group sub_filtros">
													<div class="input-group-addon">
														<i class="fa fa-calendar"></i>
													</div>
													<input id="fe_movimiento" type="text" class="form-control pull-right" placeholder="Fecha hasta" required="required" value="">
												</div>
											</div>
										
										
									</li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoRESPOSABLEDESTINO">
									RESPONSABLE DESTINO
								</li>
								<ul id="demoRESPOSABLEDESTINO"  class="collapse" style="padding-left:0px">
									<li>
											<div style="overflow-y:scroll; height:300px">
												<div value="">TODOS</div>
													<?php 
														$sql="SELECT id_responsable, tx_responsable FROM mod_responsable";
														$res=abredatabase(g_BaseDatos,$sql);
														WHILE ($row=dregistro($res)){
													?>
													<div ><input id="b.id_responsable_destino" name="<?php echo $row['id_responsable']; ?>" type="checkbox"><?php echo $row['tx_responsable']; ?></div>
													<?php } cierradatabase(); ?>
											</div>
									</li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoEQUIPOPREPARACION">
									EQUIPO DE PREPARACIÓN
								</li>
								<ul id="demoEQUIPOPREPARACION"  class="collapse" style="padding-left:0px">
									<li>
											<div style="overflow-y:scroll; height:300px">
												<div value="">TODOS</div>
													<?php 
														$sql="SELECT id_tipo_objeto,tx_tipo FROM cfg_tipo_objeto WHERE tx_objeto='tipo_preparacion' order by nu_orden";
														$res=abredatabase(g_BaseDatos,$sql);
														WHILE ($row=dregistro($res)){
													?>
													<div ><input id="b.id_tipo_preparacion" name="<?php echo $row['id_tipo_objeto']; ?>" type="checkbox"><?php echo $row['tx_tipo']; ?></div>
													<?php } cierradatabase(); ?>
											</div>
									</li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoEQUIPOENVIADO">
									ENVIADO A
								</li>
								<ul id="demoEQUIPOENVIADO"  class="collapse" style="padding-left:0px">
									<li>
											<div style="overflow-y:scroll; height:300px">
												<div value="">TODOS</div>
													<?php 
														$sql="SELECT id_tipo_objeto, tx_tipo FROM cfg_tipo_objeto WHERE tx_objeto='tipo_envio_a'";
														$res=abredatabase(g_BaseDatos,$sql);
														WHILE ($row=dregistro($res)){
													?>
													<div ><input id="b.id_tipo_enviado_a" name="<?php echo $row['id_tipo_objeto']; ?>" type="checkbox"><?php echo $row['tx_tipo']; ?></div>
													<?php } cierradatabase(); ?>
											</div>
									</li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoENCARGADOTRASLADO">
									ENCARGADO DE TRASLADO
								</li>
								<ul id="demoENCARGADOTRASLADO"  class="collapse" style="padding-left:0px">
									<li>
											<div style="overflow-y:scroll; height:300px">
												<div value="">TODOS</div>
													<?php 
														$sql="SELECT id_responsable, (tx_representacion || ' ' || tx_responsable) as responsable FROM mod_responsable";
														$res=abredatabase(g_BaseDatos,$sql);
														WHILE ($row=dregistro($res)){
													?>
													<div ><input id="b.id_responsable_enviado" name="<?php echo $row['id_responsable']; ?>" type="checkbox"><?php echo $row['responsable']; ?></div>
													<?php } cierradatabase(); ?>
											</div>
									</li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoESTADOEQUIPO">
									ESTATUS DEL EQUIPO
								</li>
								<ul id="demoESTADOEQUIPO"  class="collapse" style="padding-left:0px">
									<li>
											<div style="overflow-y:scroll; height:300px">
												<div value="">TODOS</div>
													<?php 
														$sql="SELECT tx_tipo, id_tipo_objeto FROM vie_tbl_estatus_equipo ORDER BY tx_tipo";
														$res=abredatabase(g_BaseDatos,$sql);
														WHILE ($row=dregistro($res)){
													?>
													<div ><input id="b.estatus_producto" name="<?php echo $row['id_tipo_objeto']; ?>" type="checkbox"><?php echo $row['tx_tipo']; ?></div>
													<?php } cierradatabase(); ?>
											</div>
									</li>
								</ul>
								<li class="ripple filtro" data-toggle="collapse" data-target="#demoCONDICION">
									CONDICIÓN DEL EQUIPO
								</li>
								<ul id="demoCONDICION"  class="collapse" style="padding-left:0px">
									<li>
											<div style="overflow-y:scroll; height:300px">
												<div value="">TODOS</div>
													<?php 
														$sql="SELECT tx_tipo, id_tipo_objeto FROM vie_tbl_condicion_equipo ORDER BY tx_tipo";
														$res=abredatabase(g_BaseDatos,$sql);
														WHILE ($row=dregistro($res)){
													?>
													<div ><input id="b.id_condicion_producto" name="<?php echo $row['id_tipo_objeto']; ?>" type="checkbox"><?php echo $row['tx_tipo']; ?></div>
													<?php } cierradatabase(); ?>
											</div>
									</li>
								</ul>
							</ul>
						</div>
					</div>
				</div>
			</div>
			</div>
		   <div class="col-md-9">
			<!-- general form elements -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Reporte Dinamicos</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
						</div>
					</div>
					<div class="box-body" style="height:650px; ">
						<div align="right" id="panel_filtro"  style="height:50px; ">
							<div class="col-md-offset-4"> 
								<button type="button" class="btn btn-success" onclick="javascript:imprimir();" >
								<i class="fa fa-print"></i> Imprimir
								</button>
								<button type="button" class="btn btn-success" onclick="javascript:tableToExcel('imprimible', 'W3C Example Table')">
								<i class="fa fa-file-excel"></i> Exportar XLS
								</button>
								<button type="button" class="btn btn-success" onclick="printJS('reporte_filtro', 'html')">
								<i class="fa fa-file-pdf"></i> PDF
								</button>
						</div> 
						</div>
						<div id="reporte_filtro" style="height:600px; overflow:auto;">
							
						</div>
					</div>
				</div>
			</div>
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
	
	var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()
	
	function imprimir(){
		var $this = $(this);
		var originalContent = $('body').html();
		var printArea = $('#reporte_filtro').html();

		$('body').html(printArea);
		window.print();
		$('body').html(originalContent);
	  }
	function checkUncheck() {
	    var elementos=[];
		var c = document.getElementsByTagName('input');
		for (var i = 0; i < c.length; i++) {
			if (c[i].type == 'checkbox') {
				 if (c[i].checked==true) elementos.push({id: c[i].id, elemento: c[i].name})
			}
			if (c[i].type == 'text') {
				 if (c[i].value!="") elementos.push({id: c[i].id, elemento: c[i].value})
			}
		}
		
		console.log(elementos)
		return elementos;
	}
	
    $( "#terminar" ).click(function() {
		
		


		// motivo=$('#motivo').val();
		// un=$('#unidad_negocio').val();
		// proveedor=$('#proveedor').val();
		// origen=$('#origen').val();
		// fe_entrada=$('#fe_entrada').val();
		// fe_hasta=$('#fe_hasta').val();
		// ticket=$('#ticket').val();
		// guia=$('#guia').val();
		 reporte="mod_filtro_movimiento_entrada_salida.php";
		 let variable=[{id:12, name: "luis"}];
		// tipo=$('#tipo_producto').val();
		// url="reportes/"+$('#reporte').val()+"?un="+un+"&motivo="+motivo+"&proveedor="+proveedor+"&origen="+origen+"&fe_entrada="+fe_entrada+"&fe_hasta="+fe_hasta+"&ticket="+ticket+"&guia="+guia+"&tipo="+tipo+"&reporte="+reporte;
		 $("#reporte_filtro").load(reporte, {array: checkUncheck()});
		 
		 // array = checkUncheck();
		 // document.getElementById("reporte_filtros").src="mod_filtro_movimiento_entrada_salida.php?array="+JSON.stringify(array);
	});
	function imprimir_contenido(){
			document.getElementById("reportes").contentWindow.print();
	}
	function cambiar_ubicacion(unidad_negocio){
			$('#ubicacion').load ('mod_eventos.php', { unidad: unidad_negocio, evento:26 });
	}
	function cambiar_tipo_motivo(tipo){
		$('#tipo_motivo').load ('mod_eventos.php', { tipo:tipo,  evento:31 });
	}
</script>
<?php require_once('libreriaSCRIPT.php'); ?>
</body>
</html>