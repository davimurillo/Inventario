<link href="../lib/fonts/css/font-awesome.css" rel="stylesheet">
<?php require_once('common.php'); 

$error = '0';

if (isset($_POST['envio'])){
	// Get user input
	$username = isset($_POST['correo']) ? $_POST['correo'] : '';
	$password = isset($_POST['clave']) ? $_POST['clave'] : '';
	
        
	// Try to login the user

	$error = loginUser($username,$password);
}

?>

	<?php 
	
	
	
	
	
	if ($error != '') {
			}   
   			 if (isset($_POST['envio'])){ ?>
                <div style="float:left; margin:0px auto 10px auto; width:100%; text-align:center">
               
                                  <?php
								  
                                if ($error == '') {
                                    echo "<i class='fa fa-spinner fa-spin'></i> Bienvenido al sistema";
                                    echo "<script>window.location.href='modulos/index.php?filtro=1';</script>";
									
                                }
                                else { 
									echo "<label style='color:#FF0000; font-size:10px'>".$error."</label>"; 
									
								}
                    
                                ?>
                             
                            
                </div>
     	 <?php    }else{
				 echo "<script>window.location.href='../index.php';</script>";
				
		 } ?>
			
	</div>
