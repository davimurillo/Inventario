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
##  MODULO:  		CABECERA DEL MENU PRINCIPAL                                #
##                                                                             #
################################################################################
-->
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
	    
	// input {
		// text-transform: uppercase;
	// }
	
  </style>
	<div id="contenedor_carga">
		<div id="carga"></div>
	</div>
<?php
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
<header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img src="../img/logos/apices.png"></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>APICES</b> <span style="font-size:10px">Inventario</span></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo $foto; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $nombre_usuario; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo $foto; ?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo $nombre_usuario; ?> - <?php echo $perfil; ?>
                  <small><?php echo $fecha_actualizacion; ?></small>
                </p>
              </li>
              <!-- 
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="cfg_cuentas.php?id=<?php echo $_SESSION['id_usuario']; ?>" class="btn btn-default btn-flat">Perfil</a>
                </div>
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">Salir</a>
                </div>
              </li>
            </ul>
          </li>
          
        </ul>
      </div>
    </nav>
  </header>
 
