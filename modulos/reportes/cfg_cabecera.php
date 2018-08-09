<style>
.daterangepicker{z-index:1600;}
</style>
<script src="../../lib/ckeditor/ckeditor.js"></script>
<div id="cabecera" class="row btn-primary animated slideInDown navbar-fixed-top" style="height:45px;">

		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style=" padding:13px 0px 0px 25px">
			APICES|Inventario <span style="font-size:8px;  ">REPORTES</span> 
			
		</div>
		
	
		<div align="right" class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding:13px 25px 0px 0px">
			<button id="quitar_filtro" class="btn btn-default btn-xs" title="Eliminar Filtro" >Quitar Filtro</button>
			<button id="filtro" class="btn btn-default btn-xs" style="margin-right:15px" title="Aplicar Filtro" >Filtro</button>
			<img id="printer" src="../../img/botones/print.png" title="Imprimir">
			<img id="email" src="../../img/botones/email.png" title="Enviar por Correo Electrónico">
			<img id="exportar" src="../../img/botones/excel.png"title="Exportar a Excel">
		</div>
	
</div>
<div class="modal fade"  id="myModal_report" role="dialog" style="color:#999">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3 class="modal-title"><span class="fa fa-filter" style="margin-right:10px"></span> Aplicar Filtro al Reporte</h3>
		  </div>
		  
		  <div class="modal-body" >
		   <div class="row">
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
					Cliente:
					<select class="form-control"  id="cliente">
					<option value="0">Todos</option>
					<?php 
					     if ($_SESSION['rol']==4){
							$sql = "SELECT id_empresa,tx_nombre FROM vie_usuario_empresa ORDER BY tx_nombre";
						 }else{
							 $sql = "SELECT id_empresa,tx_nombre FROM vie_usuario_empresa WHERE id_usuario=".$_SESSION['id_usuario']." ORDER BY tx_nombre";
						 }
						 $res=abredatabase(g_BaseDatos,$sql);
						 while ($row=dregistro($res)){?>
						<option value="<?php echo $row['id_empresa']; ?>"><?php echo $row['tx_nombre']; ?></option>		
					<?php }
					cierradatabase();
					?>
					</select>	
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					Estatus Cliente:
					<select class="form-control"  id="cliente_estatus">
					<option value="0">Todos</option>
					<?php 
					     
						$sql = "SELECT id_tipo_objeto,tx_tipo FROM vie_status_empresa";
						 
						 $res=abredatabase(g_BaseDatos,$sql);
						 while ($row=dregistro($res)){?>
						<option value="<?php echo $row['id_tipo_objeto']; ?>"><?php echo $row['tx_tipo']; ?></option>		
					<?php }
					cierradatabase();
					?>
					</select>	
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="margin-top:10px">
					Tipo de Contacto:
					<select class="form-control"  id="tipo_contacto">
					<option value="0">Todos</option>
					<?php 
					     
						$sql = "SELECT id_tipo_objeto,tx_tipo FROM vie_tipo_condicion_contacto";
						 
						 $res=abredatabase(g_BaseDatos,$sql);
						 while ($row=dregistro($res)){?>
						<option value="<?php echo $row['id_tipo_objeto']; ?>"><?php echo $row['tx_tipo']; ?></option>		
					<?php }
					cierradatabase();
					?>
					</select>	
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="margin-top:10px">
					Tipo de Oportunidad:
					<select class="form-control"  id="tipo_oportunidad">
					<option value="0">Todos</option>
					<?php 
					     
						$sql = "SELECT id_tipo_objeto,tx_tipo FROM vie_tipo_oportunidad";
						 
						 $res=abredatabase(g_BaseDatos,$sql);
						 while ($row=dregistro($res)){?>
						<option value="<?php echo $row['id_tipo_objeto']; ?>"><?php echo $row['tx_tipo']; ?></option>		
					<?php }
					cierradatabase();
					?>
					</select>	
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="margin-top:10px">
					Condición:
					<select class="form-control"  id="tipo_condicion">
					<option value="0">Todos</option>
					<?php 
					     
						$sql = "SELECT id_tipo_objeto,tx_tipo FROM vie_tipo_condicion_oportunidad";
						 
						 $res=abredatabase(g_BaseDatos,$sql);
						 while ($row=dregistro($res)){?>
						<option value="<?php echo $row['id_tipo_objeto']; ?>"><?php echo $row['tx_tipo']; ?></option>		
					<?php }
					cierradatabase();
					?>
					</select>	
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="margin-top:10px">
					 Fecha desde: 
					<input type="textbox" class="form-control"   id="desde" required="required"   placeholder="Fecha Desde" >
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="margin-top:10px">
					Fecha Hasta:
					<input type="textbox" class="form-control"   id="hasta"  placeholder="Fecha Hasta" >
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="margin-top:10px">
					Usuario:
					<select class="form-control"  id="usuario">
					<option value="0">Todos</option>
					<?php 
					     if ($_SESSION['rol']==4){
							$sql = "SELECT id_usuario,tx_nombre_apellido FROM cfg_usuario ORDER BY tx_nombre_apellido";
						 }else{
							 $sql = "SELECT id_usuario,tx_nombre_apellido FROM cfg_usuario WHERE id_perfil=1 ORDER BY tx_nombre_apellido";
						 }
						 $res=abredatabase(g_BaseDatos,$sql);
						 while ($row=dregistro($res)){?>
						<option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['tx_nombre_apellido']; ?></option>		
					<?php }
					cierradatabase();
					?>
					</select>	 
				</div>
				
				
				
			</div>
		  </div>
		  <div class="modal-footer"  style="text-align:center">
		  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
			<button type="button" class="btn btn-primary" id="aplicar_filtro">Aplicar Filtro</button>
		  </div>
		  
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	
	<div class="modal fade"  id="myModal_report_email" role="dialog" style="color:#999">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3 class="modal-title"><span class="fa fa-envelope-o" style="margin-right:10px"></span> Enviar Reporte por Email</h3>
		  </div>
		  <form id="forma1" action="javascript:enviar_correo();" method="POST">
			  <div class="modal-body" >
			   <div class="row" style="font-size:12px">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label>Correo</label>
						<input type="email" class="form-control"   id="correo" required="required"   placeholder="correo" autofocus>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
						<label>Asunto</label>
						<input type="textbox" class="form-control"   id="asunto"  placeholder="Asunto" >
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
						<textarea class="form-control" id="contenido"></textarea>
					</div>
					
				</div>
			  </div>
			  <div class="modal-footer"  style="text-align:center">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
				<button type="submit" class="btn btn-primary" id="enviar">Enviar</button>
				<div id="envio_correo"></div>
			  </div>
		 </form>
		  
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	 
	  <script src="../../lib/js/jquery.min.js"></script>
	   <script src="../../lib/js/bootstrap.min.js" ></script>
	   <script type="text/javascript" src="../../lib/js/moment/moment.min.js"></script>
	   <script type="text/javascript" src="../../lib/js/datepicker/daterangepicker.js"></script>
	<script>
	$('#filtro').on('click', function(){
		
		$('#myModal_report').modal('show');
		

	});
	
	$('#email').on('click', function(){
		$('#contenido').val($('#dvData').html());
		CKEDITOR.replace( 'contenido', {
			// Define the toolbar groups as it is a more accessible solution.
		toolbarGroups: [
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'about', groups: [ 'Image' ] }
	],

	removeButtons: 'Superscript,PasteFromWord,PasteText,Redo,Undo,Link,Unlink,Anchor,WidgetbootstrapAlert,WidgetbootstrapThreeCol,WidgetbootstrapTwoCol,Glyphicons,WidgetbootstrapLeftCol,WidgetbootstrapRightCol,SpecialChar,Youtube,Source,Strike,Subscript,Outdent,Indent,Blockquote,Styles,Format,About,Paste,Copy,Cut,Scayt,AutoCorrect,HorizontalRule,RemoveFormat,NumberedList,BulletedList'
		} );
		
		$('#myModal_report_email').modal('show');
		

	});
	
	//Funcion aplica el filtro para el reporte
	$('#aplicar_filtro').on('click', function(){
		$usuario="";
	   $url=window.location.pathname;
	   if ($('#usuario').val()>0){
		   $usuario="usuario="+$('#usuario').val();
		   $desde="&desde="+$('#desde').val();
	   }else{
		
		$desde="desde="+$('#desde').val();
		
	   }
	   $hasta="&hasta="+$('#hasta').val();
	   $empresa="&empresa="+$('#cliente').val();
	   $tipo_contacto="&tipo_contacto="+$('#tipo_contacto').val();
	 
	  location.href=$url+'?'+$usuario+$desde+$hasta+$empresa+$tipo_contacto;
	 
	});
	
	$('#quitar_filtro').on('click', function(){
		
	   $url=window.location.pathname;
	 
	  location.href=$url;
	 
	});
	
	 $('#desde').daterangepicker({
				
                singleDatePicker: true,
				"locale": {
					"format": "DD/MM/YYYY",
            "separator": " - ",
			"applyLabel": "Aplicar",
			"cancelLabel": "Cerrar",
			"fromLabel": "Desde",
			"toLabel": "Hasta",
			"customRangeLabel": "Custom",
			"weekLabel": "S",
			"daysOfWeek": [
				"Do",
				"Lu",
				"Ma",
				"Mi",
				"Ju",
				"Vi",
				"Sa"
			],
			"monthNames": [
				"Ene",
				"Feb",
				"Mar",
				"Abr",
				"May",
				"Jun",
				"Jul",
				"Ago",
				"Sep",
				"Oct",
				"Nov",
				"Dic"
			]
			}
              });
			  
			 $('#hasta').daterangepicker({
                singleDatePicker: true,
				"locale": {
					"format": "DD/MM/YYYY",
            "separator": " - ",
			"applyLabel": "Aplicar",
			"cancelLabel": "Cerrar",
			"fromLabel": "Desde",
			"toLabel": "Hasta",
			"customRangeLabel": "Custom",
			"weekLabel": "S",
			"daysOfWeek": [
				"Do",
				"Lu",
				"Ma",
				"Mi",
				"Ju",
				"Vi",
				"Sa"
			],
			"monthNames": [
				"Ene",
				"Feb",
				"Mar",
				"Abr",
				"May",
				"Jun",
				"Jul",
				"Ago",
				"Sep",
				"Oct",
				"Nov",
				"Dic"
			]
			}
              });
         
	 
	
	</script>
	<!-- Initialize the editor. -->
  <script>
     function enviar_correo(){
		 $('#envio_correo').load('correo.php',{'sento':$('#correo').val,'asunto':$('#asunto').val,'contenido':$('#contenido').val, 'tipo_correo':4});
	 }
	  
	
	
  </script>