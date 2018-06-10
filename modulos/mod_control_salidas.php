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
##  Last changed:  	25 DE FEBRER0 DE 2018                                      #
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
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		 <?php include('libreriaCSS.php');  ?>
	</head>
<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
		
		<?php require('cabecera.php'); ?> 
		<?php $formulario=3; include('barra_izquierda.php'); ?> 
		<div class="content-wrapper">
				<section class="content-header">
					<h1>Registro<small>Control de Salidas</small></h1>
					<ol class="breadcrumb">
						<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#">Registro</a></li>
						<li class="active">Control de Salidas</li>
					</ol>
				</section>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title">Datos Generales de la Salida</h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse">
											<i class="fa fa-minus"></i>
										</button>
									</div>
								</div>
									<form id="form1" action="javascript:agregar();" role="form" data-parsley-validate >
										<div class="box-body" id="datos_generales_guia">
											<div class="form-group" >
												<div class="col-xs-2">
													<label>UN</label>
													
													<select id="unidad_negocio" class="form-control select2"  required="required" Onchange="javascript:cambiar_ubicacion(this.value);">
														<option value="">Seleccione...</option>
													  <?php 
													   $sql="SELECT id_empresa,tx_abreviatura FROM mod_empresa";
													   $res=abredatabase(g_BaseDatos,$sql);
													   WHILE ($row=dregistro($res)){
													   ?>
													  <option value="<?php echo $row['id_empresa']; ?>" ><?php echo $row['tx_abreviatura']; ?></option>
													  
													   <?php } cierradatabase(); ?>
													</select>
												</div>
												<div id=ubicacion class="col-xs-4">
													<label>Ubicación</label>
													<select id="origen" class="select2 form-control " required="required">
														<option value="">----Seleccione ---</option>
													</select>
												</div>
												<div class="col-xs-3">
													<label>Motivo</label>
													<select id="motivo" class="form-control select2" required="required" >
														<option value="">----Seleccione ---</option>
														<?php 
															if ($_SESSION['rol']==5){ 
																$sql="SELECT id_tipo_objeto, tx_tipo FROM vie_tbl_tipo_motivos_salidas WHERE id_tipo_objeto IN (61,62)";
															}else {
																$sql="SELECT id_tipo_objeto, tx_tipo FROM vie_tbl_tipo_motivos_salidas";
															}
															$res=abredatabase(g_BaseDatos,$sql);
															WHILE ($row=dregistro($res)){
														?>
															<option value="<?php echo $row['id_tipo_objeto']; ?>" >
																<?php echo $row['tx_tipo']; ?>
															</option>
														<?php } cierradatabase(); ?>
													</select>
												</div>
												<div class="col-xs-3">
													<label>Fecha de Egreso:</label>
													<div class="input-group date">
														<div class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</div>
														<input id="fe_entrada" type="text" class="form-control pull-right" placeholder="Fecha de Egreso" required="required" value="<?php echo date('d/m/Y'); ?>" <?php if($_SESSION['rol']!=4){ echo "disabled='disabled'"; }?>>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="col-xs-6">
													<label>Proposito</label>
													<input id="proposito" class="form-control" type="text" placeholder="Proposito" required="required" >
												</div>
												<div class="col-xs-6">
													<label>Ticket:</label>
													<div class="input-group date">
														<div class="input-group-addon">
															<i class="fa fa-arrow-circle-right"></i>
														</div>
														<input id="ticket" type="text" class="form-control pull-right" placeholder="N° de ticket" required="required" value="0">
														</div>
													</div>
											</div>
											<div class="form-group">
												<div class="col-xs-6">
													<label>Responsable Destino</label>								
													<select id="responsable" class="form-control select2" required="required">
														<option value="">----Seleccione ---</option>
														  <?php 
															   $sql="SELECT id_tienda, (tx_descripcion || ' ' || tx_url || ' ' || tx_observacion) as responsable FROM mod_responsable_destino";
															   $res=abredatabase(g_BaseDatos,$sql);
															   WHILE ($row=dregistro($res)){
														   ?>
																<option value="<?php echo $row['id_tienda']; ?>" >
																	<?php echo $row['responsable']; ?>
																</option>
															<?php } cierradatabase(); ?>
													</select>
												
												</div>
												<div class="col-xs-2">
													<label>Tipo Guía</label>
													<select id="tipo_guia" class="form-control select2" required="required" Onchange="buscar_numeracion(this.value)">
														<option value="">----Seleccione ---</option>
														<?php if ($_SESSION['rol']==5){ 
															$sql="SELECT id_tipo_objeto, tx_tipo FROM vie_tbl_tipo_guia WHERE id_tipo_objeto=95";
															$res=abredatabase(g_BaseDatos,$sql);
															WHILE ($row=dregistro($res)){
														?>
															<option value="<?php echo $row['id_tipo_objeto']; ?>" >
																<?php echo $row['tx_tipo']; ?>
															</option>
																										
															<?php }
														}else { 
															$sql="SELECT id_tipo_objeto, tx_tipo FROM vie_tbl_tipo_guia";
															$res=abredatabase(g_BaseDatos,$sql);
															WHILE ($row=dregistro($res)){
														?>
															<option value="<?php echo $row['id_tipo_objeto']; ?>" >
																<?php echo $row['tx_tipo']; ?>
															</option>
														<?php } } cierradatabase(); ?>
													</select>
												</div>
												<div id="guia_numeracion" class="col-xs-4">
													<label>N° de Guia de Remisión:</label>
													<div class="input-group date">
														<div class="input-group-addon">
															<i class="fa fa-arrow-circle-right"></i>
														</div>
														<input id="guia" type="text" class="form-control pull-right" placeholder="N° de Guia" required="required" disabled >
													</div>
					
												</div>
											</div>
											<div class="form-group">
												<div class="col-xs-3">
													<label>Preparación de Equipo</label>
													<select id="preparacion" class="form-control select2" required="required">
														<option value="">----Seleccione ---</option>
														<?php 
															$sql="SELECT id_tipo_objeto, tx_tipo FROM cfg_tipo_objeto WHERE tx_objeto='tipo_preparacion'";
															$res=abredatabase(g_BaseDatos,$sql);
															WHILE ($row=dregistro($res)){
														?>
															<option value="<?php echo $row['id_tipo_objeto']; ?>" ><?php echo $row['tx_tipo']; ?></option>
							  
														<?php } cierradatabase(); ?>
													</select>	
												</div>
												<div class="col-xs-3">
													<label>Enviado a</label>
													<select id="enviado_a" class="form-control select2" required="required">
														<option value="">----Seleccione ---</option>
														<?php 
															$sql="SELECT id_tipo_objeto, tx_tipo FROM cfg_tipo_objeto WHERE tx_objeto='tipo_envio_a'";
															$res=abredatabase(g_BaseDatos,$sql);
															WHILE ($row=dregistro($res)){
														?>
															<option value="<?php echo $row['id_tipo_objeto']; ?>" >
																<?php echo $row['tx_tipo']; ?>
															</option>
														<?php } cierradatabase(); ?>
													</select>
												</div>
												<div class="col-xs-6">
													<label>Encargado de Traslado</label>
													<select id="responsable_enviado" class="form-control select2" required="required">
														<option value="">----Seleccione ---</option>
														  <?php 
															   $sql="SELECT id_responsable, (tx_representacion || ' ' || tx_responsable) as responsable FROM mod_responsable";
															   $res=abredatabase(g_BaseDatos,$sql);
															   WHILE ($row=dregistro($res)){
														   ?>
																<option value="<?php echo $row['id_responsable']; ?>" >
																	<?php echo $row['responsable']; ?>
																</option>
															<?php } cierradatabase(); ?>
													</select>
												</div>
											</div>
										</div>
										<?php if ($_SESSION['rol']!=5){ ?>
											<div align="center" class="box-footer">
												<a href="javascript:buscar_guia_boton();">
													<button  type="button" class="btn btn-primary btn-sm">
														<i class="fa fa-search"></i> 
														BUSCAR GUIA
													</button>
												</a>
												<?php if ($_SESSION['rol']==4) { ?>
													<a href="javascript:prestamo_guia_boton();">
														<button type="button" class="btn btn-warning btn-sm" >
															<i class="fa fa-exclamation-triangle"></i> 
															PRESTAR GUIA
														</button>
													</a>
												<?php } ?>
												<?php if ($_SESSION['rol']>=3) { ?>
													<a href="javascript:anular_guia_boton();">
														<button type="button" class="btn btn-danger btn-sm" ><i class="fa fa-exclamation-triangle"></i> ANULAR GUIA</button>
													</a>
												<?php }  ?>
											</div>	
										<?php } ?>
							</div>	
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div id="eventos_entradas" ></div>
							<div class="box box-warning">
								<div class="box-header with-border">
									<h3 class="box-title">Equipos y/o Accesorios</h3>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="collapse">
											<i class="fa fa-minus"></i>
										</button>
									</div>
									<div id="form_equipo" class="box-body">
										<div class="col-xs-2">
											<label>Serial</label>
											<input id="id_producto" class="form-control" type="hidden" placeholder="id" value="0" >
											<input id="serial" class="form-control" type="text" placeholder="serial" value="" required="required" autofocus>
										</div>
										<div class="col-xs-10">
											<div class="callout callout-info">
												<h4>Información Importante!</h4>
												<p>Presione ENTER para Buscar el serial del Equipo!</p>
											</div>
										</div>
									</div>
									<div align="center" class="box-footer">
									<?php if ($_SESSION['rol']!=5){ ?>
										<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal_buscar_equipos">
											<i class="fa fa-search"></i> 
											F2-BUSCAR
										</button>
									<?php } ?>
										<button id="cancelar_busqueda" type="button" class="btn btn-danger btn-sm" style="width:150px"><i class="fa fa-ban"></i> ESC - CANCELAR</button>
										<button id="agregar" type="submit" class="btn btn-success btn-sm">
											<i class="fa fa-arrow-down"></i> 
											AGREGAR
									</div>	
								</form>	
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="box box-success">
									<div class="box-header with-border">
										<h3 class="box-title">Listado de Equipos y/o Accesorios</h3> 
											<a href="javascript:refrescar();"><button type="button" class="btn btn-success btn-sm" ><i class="fa fa-retweet"></i> REFRESCAR</button></a>
									</div>
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
										<input id=contador type='hidden' value='0'>
									</div>
									<div align="right" class="box-footer">
										<button id="cancelar_guia" type="button" class="btn btn-danger btn-sm" onclick="">CANCELAR</button>
										<button id="terminar" type="submit" class="btn btn-success btn-sm" onclick="">TERMINAR</button>
									</div>
									<div id="correo_enviado"></div>
							</div>
						</div>
					</div>
				</section>
			</div>
			<?php include('pie.php'); ?>
		</div>
	</div>
	<!-- MODAL BUSCAR EQUIPOS -->
	<div class="modal fade" tabindex="-1" id="myModal_buscar_equipos" role="dialog" style="color:#999; ">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" >
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h2 class="modal-title"><span class="fa fa-search" style="margin-right:10px"></span>Buscardor de Equipos</h2>
				</div>
				<div class="modal-body" >
					<div class="row" >
					 	<div class="col-xs-12">
							<label>Valor de Busqueda</label>
							<input id="buscar_equipo" class="form-control" type="text" placeholder="Valor de Busqueda" value="" required="required" autofocus>
						</div>
						<div id="resultado_busqueda" class="col-xs-12" style="margin-top:20px">
							<div class="callout callout-info">
								<h4>Información Importante!</h4>
								<p>Presione ENTER para Buscar los datos del Equipo!</p>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer"  style="text-align:center">
					<button type="button" class="btn btn-success" data-dismiss="modal" aria-label="Close">Cancelar</button>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div>
	
	<!-- MODAL BUSCAR GUIA -->
	<div class="modal fade" tabindex="-1" id="myModal_buscar_guia" role="dialog" style="color:#999; ">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" >
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h2 class="modal-title"><span class="fa fa-search" style="margin-right:10px"></span>Buscar Guía de Salida</h2>
				</div>
				<div class="modal-body" >
					<div class="row" id="buscar_guia_modal">
						
					</div>
					<div class="modal-footer"  style="text-align:center">
						<button type="button" class="btn btn-success" data-dismiss="modal" aria-label="Close">
							Cancelar
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- MODAL CODIGO DE BARRA -->
	<div class="modal fade" tabindex="-1" id="myModal_barra" role="dialog" style="color:#999; ">
		<div class="modal-dialog">
			<div class="modal-content" >
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h2 class="modal-title"><span class="fa fa-upload" style="margin-right:10px">
						</span>Codigo de Barra del Equipo
					</h2>
				</div>
				<div class="modal-body" id="codigo_barra_equipo" >
					<iframe src="" name="code_barra" id="id_code_barra" width="100%" height="400px" frameborder=0 ></iframe>
				</div>
				<div class="modal-footer"  style="text-align:center">
					<button type="button" class="btn btn-success" data-dismiss="modal" aria-label="Close">
						Cancelar
					</button>
				</div>
			</div>
		</div>
	 </div>
	
	<!-- MODAL PRESTAR GUIA -->
	<div class="modal fade" tabindex="-1" id="myModal_prestar_guia" role="dialog" style="color:#999; ">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" >
				<form id=form2 action="javascript:prestamo_guia();" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h2 class="modal-title">
						<span class="fa fa-fa-exclamation-triangle" style="margin-right:10px"></span>
						Prestamo de Guia
					</h2>
				</div>
				<div class="modal-body" id="prestamo_guia" >
					
						
					</div>
					<div class="modal-footer"  style="text-align:center">
					
						<button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancelar</button>
						<button type="submit" class="btn btn-warning" >Aceptar</button>
					</div>
				</form>
			</div>
		</div>
	 </div>
	  
	<!-- MODAL ANULAR GUIA -->
	<div class="modal fade" tabindex="-1" id="myModal_anular_guia" role="dialog" style="color:#999; ">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" id="anulacion_guia_contenido" >
				
			</div>
		</div>
	</div>

	<?php include('libreriaJS.php'); ?>
	<script>
	$("BODY").keydown(function(event) {
		if(event.which == 113) { //F2
			$('#myModal_buscar_equipos').modal('show');  
			setTimeout(function (){
				$('#buscar_equipo').focus();
			}, 1000);
		}

		if(event.which == 27) { //ESC
          limpiar();
        } 
	 });
	 // BOTON DE BUSCAR GUIA
	 function buscar_guia_boton(){
			$('#buscar_guia_modal').load('mod_buscar_guia.php');
			$('#myModal_buscar_guia').modal('show');
			setTimeout(function (){
				$('#buscar_guia').val('');			
				$('#buscar_guia').focus();
			},1000);
	 }

	// BOTON DE PRESTAMO DE GUIA
	  function prestamo_guia_boton(){
			$('#prestamo_guia').load('mod_prestamo_guia.php');
			$('#myModal_prestar_guia').modal('show');
			setTimeout(function (){			
				$('#guia_prestamo').focus();
			},1000);
	 }	 

	 // BOTON DE ANULAR GUIA
	  function anular_guia_boton(){
			$('#anulacion_guia_contenido').load('mod_anular_guia.php',{f:0});
			$('#myModal_buscar_guia').modal('hide');
			$('#myModal_anular_guia').modal('show');
			setTimeout(function (){			
				$('#usuario_aprovacion_anular').focus();
			},1000);
	 }
	 
	  // BOTON DE DE EDITAR EQUIPO EN BUSQUEDA DE EQUIPOS
	function editar_equipos_opcional() { 
		$('#myModal_editar_producto').modal('show');  
	} 
	
	$( "#buscar_equipo" ).keypress(function( event ) {
		if ( event.which == 13 ) {
			$('#resultado_busqueda').load('mod_eventos.php',{id:$('#buscar_equipo').val(),accion:1, evento:7});
		}
	});
	$( "#buscar_guia" ).keypress(function( event ) {
		if ( event.which == 13 ) {
			$('#resultado_busqueda_guia').load('mod_eventos.php',{id:$('#buscar_guia').val(),accion:1, evento:11});
		}
	});
	$(function () {
		$(".select2").select2();
		$('#fe_entrada').datepicker({
			autoclose: true,
			format: 'dd/mm/yyyy'
		});
	});
	function buscar_serial(){
		
		 $('#form_equipo').load('mod_buscar_equipo.php',{id:$('#serial').val(),'id_producto':$('#id_producto').val(), motivo: $('#motivo').val(), f: 1});
	}
	$( "#serial" ).keypress(function( event ) {
	  if ( event.which == 13 ) {
		 
		$('#form_equipo').load('mod_buscar_equipo.php',{id:$('#serial').val(),id_producto:$('#id_producto').val(), motivo: $('#motivo').val(),f: 1});
	  }
	});
	function limpiar (){
	  $('#form_equipo').load('mod_buscar_equipo.php', {id:'',id_producto:''});
     } 
	function agregar(){	
		$limite=0;
		if ($('#tipo_guia').val()==95){ $limite=1000 } else { $limite=1000 }
		if  ($('#contador').val() < $limite){
			 if ($('#cantidad').val() >0 && $('#id_producto').val()>0){
				$('#eventos_entradas').load('mod_eventos.php',{id_producto:$('#id_producto').val(),serial:$('#serial').val(),cantidad:$('#cantidad').val(), accesorios:$('#accesorios').val(), observacion:$('#observacion').val(),origen:$('#origen').val(),fe_entrada:$('#fe_entrada').val(),ticket:$('#ticket').val(),guia:$('#guia').val(),proposito:$('#proposito').val(),responsable:$('#responsable').val(),motivo:$('#motivo').val(),preparacion:$('#preparacion').val(),enviado:$('#enviado_a').val(),responsable_enviado:$('#responsable_enviado').val(), tipo_guia:$('#tipo_guia').val(), accion:1, evento:4});
				limpiar();
			}else{
				if ($('#id_producto').val()!=0){
					swal("Oops!", "Debe Colocar una cantidad mayor que CERO en la entrada del producto!", "error");
				}
			} 
		}else{
			swal("Oops!", "Ha llegado al limite de Items por Guía, debe generar una nueva Guía!", "error");
		}
	}
	function ver_guia(guia,fecha,tipo) {
		if (tipo==95){
			window.open('mod_mostrar_guia_salida.php?id=2&guia='+guia+'&fecha='+fecha);
		}else{
			window.open('mod_mostrar_guia_salida_interna.php?id=2&guia='+guia+'&fecha='+fecha);
		}
	}
	function refrescar(){
		if ($('#origen').val()>0){
			$('#movimientos').load('mod_eventos.php',{origen:$('#origen').val(),fe_entrada:$('#fe_entrada').val(),ticket:$('#ticket').val(),guia:$('#guia').val(),accion:1, evento:5});
		}
	}
	$('#form1').keypress(function(e){   
		if(e == 13){
			return false;
		}
	});
	$(function () {
		$("#movimiento_entrada").DataTable();
	});
	
	function eliminar_elemento(id, cantidad, id_producto){
		$('#eventos_entradas').load('mod_eventos.php',{guia:$('#guia').val(),id_movimiento:id,origen:$('#origen').val(),fe_entrada:$('#fe_entrada').val(),id:id_producto, 'cantidad':cantidad, accion:1,evento:6});
	}
	
	$( "#terminar" ).click(function() {
		if ($('#tipo_guia').val()>0){
			if ($('#tipo_guia').val()==95){
				location.href="mod_ver_guia_salida.php?id=2&guia="+$('#guia').val()+"&fecha="+$('#fe_entrada').val(); 
			}else{
				location.href="mod_ver_guia_salida_interna.php?id=2&guia="+$('#guia').val()+"&fecha="+$('#fe_entrada').val(); 
			}
		}
	});
	
	$( "#cancelar_guia" ).click(function() {
		$('#eventos_entradas').load('mod_eventos.php',{guia:$('#guia').val(),fecha:$('#fe_entrada').val(), evento:24});
	});
	
	function terminar(guia,fecha) {
		if ($('#tipo_guia').val()>0){
			if ($('#tipo_guia').val()==95){
				window.open('mod_mostrar_guia_salida.php?id=2&guia='+guia+'&fecha='+fecha);
			}else{
				window.open('mod_mostrar_guia_salida_interna.php?id=2&guia='+guia+'&fecha='+fecha);
			}
		}
	}
	function codigo_barra(id){
		$('#id_code_barra').attr('src','mod_mostrar_code_barra_equipo.php?id_producto='+id);  
		$('#myModal_barra').modal('show'); 
	}	
	function editar_equipos_opcional(id) { 
        $('#codigo_producto_editar').attr('src','blank.html');  
		$('#codigo_producto_editar').attr('src','mod_registro_equipos_accesorios_editar.php?buscar_equipo_general=&f_mode=edit&f_rid='+id+'&f_page_size=100&f_p=1');  
		$('#myModal_editar_producto').modal('show');  
	}
	function prestamo_guia(){
		$('#prestamo_guia').load('mod_eventos.php',{guia:$('#guia_prestamo').val(), usuario:$('#usuario_aprovacion').val(), pass:$('#clave_aprovacion').val(), observacion:$('#observaciones_prestamo').val(), evento:19});
	}
	function anular_guia(guia){
		$('#anulacion_guia_contenido').load('mod_anular_guia.php',{f:1,guia:guia});
			$('#myModal_buscar_guia').modal('hide');
			$('#myModal_anular_guia').modal('show');
			setTimeout(function (){			
				$('#usuario_aprovacion_anular').focus();
			},1000);
		
		//$('#anulacion_guia').load('mod_eventos.php',{guia:$('#guia_anular').val(), evento:24});
	} 
	function anular_guia_definitivo(f){
		$('#anulacion_guia_contenido').load('mod_eventos.php',{guia:$('#guia_anular_modal').val(), usuario:$('#usuario_aprovacion_anular').val(), pass:$('#clave_aprovacion_anular').val(), observacion:$('#observaciones_anular').val(), fe_anulada:$('#fe_anular').val(), f:f, evento:12});
	}
	 $( "#cancelar_busqueda" ).click(function() {
		limpiar();
    });
	
	 function limpiar (){
	  $('#form_equipo').load('mod_buscar_equipo.php', {id:'',id_producto:''});
	 }
	  // busca la ultima numeriacion del motivo
	function buscar_numeracion(id){
		if ($('#motivo').val()>0 || $('#tipo_guia').val()>0){
			$('#guia_numeracion').load('mod_eventos.php',{'id':$('#motivo').val(), 'evento': 25, tipo:$('#tipo_guia').val()});
		}else{
			alert("Selecione un tipo de motivo");			
		}
	}
	function cambiar_ubicacion(unidad_negocio){
			$('#ubicacion').load ('mod_eventos.php', { unidad: unidad_negocio, evento:27, control:'Destino' });
	}
	
</script>
</body>
	<?php include('libreriaSCRIPT.php'); ?>
</html>






