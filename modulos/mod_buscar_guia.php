<div class="col-xs-12">
	<label>Valor de Busqueda</label>
	<input id="buscar_guia" class="form-control" type="text" placeholder="Coloque el Número de la Guia a Buscar" value="" required="required" autofocus>
</div>
<div id="resultado_busqueda_guia" class="col-xs-12" style="margin-top:20px">
	<div class="callout callout-info">
		<h4>Información Importante!</h4>
		<p>Presione ENTER para Buscar los datos de la Guía!</p>
	</div>
</div>
<script>
$( "#buscar_guia" ).keypress(function( event ) {
		if ( event.which == 13 ) {
			$('#resultado_busqueda_guia').load('mod_eventos.php',{id:$('#buscar_guia').val(),accion:1, evento:11});
		}
	});
</script>