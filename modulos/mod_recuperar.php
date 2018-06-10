
		<?php
		
		if ($_POST['f']==0){
		?>
			<form action="javascript:enviar()" data-parsley-validate>	
			<div style="width:80%; margin-left:40px">
				
				<input type="email" class="form-control"   id="correo_contacto"  placeholder="Correo Electrónica" required="required" >
				<div align="left" style="margin-top:20px; font-size:11px;">Nota: Escriba su correo electrónico registrado en el sistema, y este le enviará un link para recuperar su contraseña</div>
				
				
			</div>
		 
		  <hr>
			<button type="submit" class="btn btn-primary btn-lg" id="enviar" >Enviar</button>
			</form>
		<?php }else{
	require_once('common.php');
	$sql="SELECT id_usuario, tx_contrasena FROM cfg_usuario WHERE tx_email='".$_POST['correo']."'";
	$res=abredatabase(g_BaseDatos,$sql);
	$row=dregistro($res);
	if (dnumerofilas($res)>0){
	
?>
	
		 <div class="row">
			 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">
				 
				  <!-- Start welcome area -->
				  Nota: Recibirá un mensaje en su correo electrónico para que pueda recuperar su contraseña!
				 
					  <h2 class="tittle" style="color:#66afe9" >Mensaje enviado <span> con éxito</span></h2>
					 <div id="email_recuperar"></div>
					 
					 <hr>
				 <button type="button" class="btn btn-primary btn-lg"  data-dismiss="modal" >Aceptar</button>
				  <script>
					$('#email_recuperar').load("modulos/correo.php",{ sento:'<?php echo $_POST['correo']; ?>', tipo_correo:1, pass:'<?php echo $row['tx_contrasena']; ?>', usuario:'<?php echo $row['id_usuario']; ?>' })
				  </script>
			 </div>
		</div>
    <?php } else { ?>    
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center" style="color:red">
			  <!-- Start welcome area -->
			  Error correo electrónico no existe intente de nuevo o corrija el correo!
			 
			 <hr>
			 <button type="button" class="btn btn-danger btn-lg"  data-dismiss="modal" >Aceptar</button>
             
           </div>
         </div>
		<?php } } ?>  
		

