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
<aside class="main-sidebar">
    <section class="sidebar">
	   <div class="user-panel">
			<img src="../img/sistema/menu_inicial.png" height="100%" width="100%">
		</div>
	    <div class="user-panel">
			<div class="pull-left image">
			  <img src="<?php echo $foto; ?>" class="img-circle" alt="User Image">
			</div>
        <div class="pull-left info">
          <p><?php echo $nombre_usuario; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
		<?php if ($_SESSION['rol']==5){ ?>
			<li class="<?php if($formulario==2 or $formulario==3 ){ echo "active"; } ?> treeview">
			  <a href="#">
				<i class="fa fa-chevron-circle-right"></i> <span>Control</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			  </a>
			  <ul class="treeview-menu">
				<li class="<?php if($formulario==3){ echo "active"; } ?>"><a href="mod_control_salidas.php"><i class="fa fa-chevron-left"></i> Salidas</a></li>
			  </ul>
			</li>
		<?php }else { ?>
			<form   action="javascript:buscar_rapido_equipos()" method="GET" class="sidebar-form">
				<div class="input-group " >
				<input name="buscar" id="buscar_rapido" type="text" class="form-control" placeholder="Buscar Equipo" autofocus>
				<span class="input-group-btn">
					<button class="btn btn-flat" type="submit"><i class="fa fa-search"></i></button>
				</span>
				</div>
			</form>
			<li class="header">MENU DE NAVEGACIÓN</li>
				<li class="<?php if($formulario==1){ echo "active"; } ?> treeview">
				  <a href="index.php">
					<i class="fa fa-dashboard"></i> <span>Dashboard</span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
				  </a>
          		</li>
			<?php if ($_SESSION['rol']>=2){ ?>
			<li class="<?php if($formulario==2 or $formulario==3 ){ echo "active"; } ?> treeview">
			  <a href="#">
				<i class="fa fa-chevron-circle-right"></i> <span>Control</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			  </a>
			  <ul class="treeview-menu">
				<li class="<?php if($formulario==2){ echo "active"; } ?>"><a href="mod_control_entradas.php"><i class="fa fa-chevron-right"></i> Entradas</a></li>
				<li class="<?php if($formulario==3){ echo "active"; } ?>"><a href="mod_control_salidas.php"><i class="fa fa-chevron-left"></i> Salidas</a></li>
			  </ul>
			</li>
			<li class="<?php if($formulario>=4 && $formulario<=8 ){ echo "active"; } ?> treeview">
			  <a href="#">
				<i class="fa fa-plus-square"></i> <span>Registro</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			  </a>
			  <ul class="treeview-menu">
				<li class="<?php if($formulario==4){ echo "active"; } ?>"><a href="mod_registro_equipos_accesorios.php"><i class="fa fa-desktop"></i> Equipos y/o Accesorios</a></li>
				 <li class="<?php if($formulario==5){ echo "active"; } ?>"><a href="mod_personal.php"><i class="fa fa-street-view "></i> Supervisores</a></li>
				  <li class="<?php if($formulario==21){ echo "active"; } ?>"><a href="mod_responsable_destino.php"><i class="fa fa-users"></i> Reponsable de Destino</a></li>
				  <li class="<?php if($formulario==21){ echo "active"; } ?>"><a href="mod_responsable.php"><i class="fa fa-users"></i> Reponsable de Traslado</a></li>
				<li class="<?php if($formulario==6){ echo "active"; } ?>"><a href="mod_empresa.php"><i class="fa fa-sitemap "></i> Unidades de Negocios</a></li>
				<li class="<?php if($formulario==7){ echo "active"; } ?>"><a href="mod_proveedor.php"><i class="fa fa-briefcase"></i> Proveedor</a></li>
				<li class="<?php if($formulario==8){ echo "active"; } ?>"><a href="mod_cliente.php"><i class="fa fa-list-ul"></i> Origen/Destino</a></li>
				<?php if ($_SESSION['rol']==4) { ?>
				<li class="<?php if($formulario==23){ echo "active"; } ?>"><a href="mod_ver_movimiento.php"><i class="fa fa-history"></i>Regularizar de Movimientos</a></li>
				<li class="<?php if($formulario==21){ echo "active"; } ?>"><a href="mod_carga_inventario.php"><i class="fa fa-archive"></i> Carga de Inventario</a></li>
				<li class="<?php if($formulario==40){ echo "active"; } ?>"><a href="mod_registro_equipos_temporal.php"><i class="fa fa-archive"></i> Registro de Equipos Temporal</a></li>
				<?php } ?>
			  </ul>
			</li>
			<?php } ?>
			<li class="class="<?php if($formulario==9 ){ echo "active"; } ?> treeview">
			  <a href="#">
				<i class="fa fa-print"></i> <span>Reportes</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			  </a>
			  <ul class="treeview-menu">
				<li class="<?php if($formulario==9){ echo "active"; } ?>"><a href="mod_reportes.php"><i class="fa fa-print"></i> Generales</a></li>
			  </ul>
			</li>
		<?php } ?>
		<?php if ($_SESSION['rol']==4){ ?>
		<li class="<?php if($formulario>=10 && $formulario<=15 ){ echo "active"; } ?> treeview">
          <a href="#">
            <i class="fa fa-gears"></i> <span>Configuración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
			<li class="<?php if($formulario==10){ echo "active"; } ?>"><a href="cfg_cuentas.php"><i class="fa fa-user"></i> Usuario</a></li>
			<li class="<?php if($formulario==24){ echo "active"; } ?>"><a href="cfg_guias.php"><i class="fa fa-chevron-circle-right"></i> Guías </a></li>
			<!--
			<li class="<?php if($formulario==11){ echo "active"; } ?>"><a href="mod_almacen.php"><i class="fa fa-building"></i> Almacen</a></li>
            <li class="<?php if($formulario==12){ echo "active"; } ?>"><a href="cfg_perfil.php"><i class="fa fa-chevron-circle-right"></i> Perfil</a></li>
			-->
			<!--
			<li ><a href="cfg_modulos.php"><i class="fa fa-sitemap"></i> Modulos</a></li>
			-->
			<li class="<?php if($formulario==13){ echo "active"; } ?>"><a href="cfg_tipo_objetos.php"><i class="fa fa-chevron-circle-right"></i> Elementos del Sistema</a></li>
			<li class="<?php if($formulario==14){ echo "active"; } ?>"><a href="cfg_tipo_equipo.php"><i class="fa fa-chevron-circle-right"></i> Tipos de Equipos</a></li>
			<li class="<?php if($formulario==28){ echo "active"; } ?>"><a href="cfg_tipo_marcas.php"><i class="fa fa-chevron-circle-right"></i> Tipos de Marcas</a></li>
			<li class="<?php if($formulario==15){ echo "active"; } ?>"><a href="cfg_reportes.php"><i class="fa fa-chevron-circle-right"></i> Reportes</a></li>
			<li class="<?php if($formulario==16){ echo "active"; } ?>"><a href="cfg_configuracion.php"><i class="fa fa-chevron-circle-right"></i> Configuración General</a></li>
			<li class="<?php if($formulario==24){ echo "active"; } ?>"><a href="cfg_funciones.php"><i class="fa fa-chevron-circle-right"></i>Funciones</a></li>
		<?php } ?>
          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  <!-- Ventana para BUSCAR EQUIPO -->
	<div class="modal fade" tabindex="-1" id="myModal_buscar_rapido" role="dialog" style="color:#999; ">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h2 class="modal-title">
					<i class="fa fa-search" style="margin-right:10px"></i>Busqueda de Equipos
				</h2>
			</div>
			<div class="modal-body" >
				<div  class="row">
				   <iframe id="buscar_rapido_muestra" width="100%" height="500px" src="" style="border:none"></iframe>
				</div>
			</div>
			<div class="modal-footer"  style="text-align:center">
			</div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<script>
		function buscar_rapido_equipos(){
			$('#buscar_rapido_muestra').attr('src','mod_buscar_equipo_rapido2.php?buscar='+$('#buscar_rapido').val()+'&f=1');
			$('#myModal_buscar_rapido').modal('show'); 
		}
	</script>