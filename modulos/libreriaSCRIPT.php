<script>
$( window ).load(function() {
		
		var contenedor = document.getElementById('contenedor_carga');
		contenedor.style.visibility = 'hidden';
		contenedor.style.opacity ='0';
});

jQuery(document).ready(function() {
	
	jQuery('input').keyup(function() {
		$(this).val($(this).val().toUpperCase());
	});

});
</script>