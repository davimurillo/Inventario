<?php
	require_once('common.php');
	checkUser();
	$evento=0;
  if ($_POST['evento']==1){	
	$sql="SELECT id_producto,
  id_tipo_producto,
  (select id_tipo_carga FROM cfg_tipo_producto WHERE id_tipo_producto=a.id_tipo_producto LIMIT 1) as tipo_carga,
  tx_codigo,
  tx_nombre,
  id_unidad_medida,
  tx_marca,
  tx_modelo,
  tx_descripcion,
  costo,
  estatus,
  id_producto_padre,
  tx_serial,
  tx_ngr,
  n_stock,
  ultimo_movimiento,
  id_tienda,
  id_garantia,
  id_condicion FROM mod_producto a WHERE id_producto='".$_POST['id_producto']."'";
  $res=abredatabase(g_BaseDatos,$sql);
  $row2=dregistro($res);
  if (dnumerofilas($res)>0) {
	  $codigo_producto=$row2['id_producto'];
	  $sql_movimiento="SELECT id_tipo_movimiento, fe_movimiento, tx_guia, id_motivo,
		   (SELECT n_stock  FROM mod_producto WHERE id_producto=d.id_producto) as existencia
		   FROM mod_movimiento d WHERE id_producto=".$row2['id_producto']." ORDER BY fe_movimiento DESC, fe_actualizada DESC LIMIT 1"; 
	  $res_movimiento=abredatabase(g_BaseDatos,$sql_movimiento);
	  $row_movimiento=dregistro($res_movimiento);
	  $stock=$row2['n_stock']==null? 0 : $row2['n_stock'];
	  if ($row2['ultimo_movimiento']!=1 ) {
	    $sql_entrada="INSERT INTO  mod_movimiento(id_tipo_movimiento,id_motivo,tx_responsable,id_tienda,tx_ticket,tx_guia,fe_movimiento,id_producto,tx_serial,nx_cantidad,estatus_producto,id_condicion_producto,tx_observacion, id_usuario, id_estatus_movimiento,tx_accesorios) VALUES('1',".$_POST['motivo'].",'".$_POST['responsable']."',".$_POST['origen'].",'".$_POST['ticket']."','".$_POST['guia']."','".$_POST['fe_entrada']."',".$codigo_producto.",'".$_POST['serial']."',".$_POST['cantidad'].",".$row2['estatus'].",".$row2['id_condicion'].",'".$_POST['observacion']."',".$_SESSION['id_usuario'].",1,'".$_POST['accesorios']."')";
		$res_entrada=abredatabase(g_BaseDatos,$sql_entrada);
		$row_entrada=dregistro($res_entrada);
		if ($row2['tipo_carga']!=2){
			$sql="UPDATE mod_producto SET n_stock=".$stock."+".$_POST['cantidad'].", ultimo_movimiento=1, id_tienda=1, estatus=".$_POST['estatus']." WHERE id_producto=".$codigo_producto;
		}else{
			$sql="UPDATE mod_producto SET n_stock=".$stock."+".$_POST['cantidad'].", ultimo_movimiento=1, id_tienda=1 WHERE id_producto=".$codigo_producto;
		}
		$res=abredatabase(g_BaseDatos,$sql);
	  }
	  else{
		//  if ($row2['ultimo_movimiento']==1 && trim($row_movimiento['tx_guia'])!=trim($_POST['guia']) && $row2['tipo_carga']==2) {
		 if ($row2['ultimo_movimiento']==1 && $row2['tipo_carga']==2) {
			    $sql="INSERT INTO  mod_movimiento(id_tipo_movimiento,id_motivo,tx_responsable,id_tienda,tx_ticket,tx_guia,fe_movimiento,id_producto,tx_serial,nx_cantidad,estatus_producto,id_condicion_producto,tx_observacion, id_usuario, id_estatus_movimiento,tx_accesorios) VALUES('1',".$_POST['motivo'].",'".$_POST['responsable']."',".$_POST['origen'].",'".$_POST['ticket']."','".$_POST['guia']."','".$_POST['fe_entrada']."',".$codigo_producto.",'".$_POST['serial']."',".$_POST['cantidad'].",".$row2['estatus'].",".$row2['id_condicion'].",'".$_POST['observacion']."',".$_SESSION['id_usuario'].",1,'".$_POST['accesorios']."')";
				$res=abredatabase(g_BaseDatos,$sql);
				$row2=dregistro($res);  
			   $sql="UPDATE mod_producto SET n_stock=".$stock."+".$_POST['cantidad'].", ultimo_movimiento=1, id_tienda=1 WHERE id_producto=".$codigo_producto;
			   $res=abredatabase(g_BaseDatos,$sql);
		  }else{
			echo '<script> swal("Oops!", "Producto ya aparece registrado con una Entrada!", "error");
			</script>';
		  }
	  }	
  }else{
	 /* $sql="select last_value  FROM mod_producto_id_producto_seq";
	  $res=abredatabase(g_BaseDatos,$sql);
	  $row2=dregistro($res);
	  $codigo_producto=$row2[0]+1;
	  $sql="INSERT INTO mod_producto(id_tipo_producto,tx_descripcion,tx_marca,tx_modelo,tx_observacion,costo,estatus,tx_serial,tx_ngr,id_garantia,id_condicion, id_producto_padre, id_unidad_medida,fe_vencimiento,id_proveedor) VALUES(".$_POST['tipo'].",'".$_POST['descripcion']."','".$_POST['marca']."','".$_POST['modelo']."','".$_POST['observacion']."',".$_POST['costo'].",".$_POST['estatus_equipo'].",'".$_POST['serial']."','".$_POST['ngr']."',".$_POST['garantia'].",".$_POST['condicion_equipo'].",0,".$_POST['unidad_medida'].",'".$_POST['fe_vence']."',".$_POST['proveedor'].")";
	  $res=abredatabase(g_BaseDatos,$sql);
	  $row2=dregistro($res);
	  $sql="INSERT INTO  mod_movimiento(id_tipo_movimiento,id_motivo,tx_codigo_motivo,tx_cotizacion,tx_responsable,id_tienda,tx_ticket,tx_guia,fe_movimiento,id_producto,tx_serial,nx_cantidad,nx_costo,estatus_producto,id_condicion_producto,tx_observacion, id_usuario) VALUES('1',".$_POST['motivo'].",'".$_POST['codigo_motivo']."','".$_POST['cotizacion']."','".$_POST['responsable']."',".$_POST['origen'].",'".$_POST['ticket']."','".$_POST['guia']."','".$_POST['fe_entrada']."',".$codigo_producto.",'".$_POST['serial']."',".$_POST['cantidad'].",'".$_POST['costo']."',".$_POST['estatus_equipo'].",".$_POST['condicion_equipo'].",'".$_POST['observacion']."',".$_SESSION['id_usuario'].")";
	  $res=abredatabase(g_BaseDatos,$sql);
	  $row2=dregistro($res);
	  */
  }
  cierradatabase();
  ?>
   <script>  refrescar();  </script> 
  <?php }
  //mostrar movimientos de entrada
  if ($_POST['evento']==2){	
	 $sql="SELECT id_movimiento, id_producto,  (tx_descripcion || ' ' || tx_marca || ' ' || tx_modelo ) as tx_descripcion,tx_serial,tx_nombre_tipo,nx_cantidad, nx_costo FROM vie_tbl_movimiento WHERE tx_guia='".$_POST['guia']."' and id_tienda='".$_POST['origen']."' and fe_movimiento='".$_POST['fe_entrada']."' and  id_tipo_movimiento=1 ORDER BY id_movimiento DESC";
	$res=abredatabase(g_BaseDatos,$sql);
	?>
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
                <tbody>
				<?php while($row2=dregistro($res)){ ?>
                <tr>
                  <td><?php echo $row2['tx_descripcion']; ?></td>
                  <td><?php echo $row2['tx_serial']; ?>
                  </td>
                  <td><?php echo $row2['tx_nombre_tipo']; ?></td>
                  <td align="center"> <?php echo $row2['nx_cantidad']; ?></td>
                  <td align="right"><?php echo $row2['nx_costo']; ?></td>
				  <td align="center" onclick="javascript:eliminar_elemento(<?php echo $row2['id_movimiento']; ?>,<?php echo $row2['nx_cantidad']; ?>,<?php echo $row2['id_producto']; ?>,<?php echo $row2['id_tienda']; ?>, )"><i class="fa fa-trash"></i></td>
                </tr>
				<?php } cierradatabase(); ?>
				</tbody>
	</table>
	<script>
	$(function () {
    $("#movimiento_entrada").DataTable();
  });					
  </script>	
	<?php 
  }
   if ($_POST['evento']==3){	
		$sql="UPDATE mod_producto SET n_stock=n_stock-".$_POST['cantidad'].", ultimo_movimiento=2, id_tienda=".$_POST['tienda']." WHERE id_producto=".$_POST['id'];
		$res=abredatabase(g_BaseDatos,$sql);
		$sql="DELETE  FROM mod_movimiento WHERE id_movimiento=".$_POST['id_movimiento'];
		$res=abredatabase(g_BaseDatos,$sql);
	?>
	<script>
		$('#movimientos').load('mod_eventos.php',{guia:'<?php echo $_POST['guia']; ?>', origen:<?php echo $_POST['origen']; ?>,fe_entrada:'<?php echo $_POST['fe_entrada']; ?>',accion:1,evento:2});
		 function eliminar_elemento(id, cantidad, id_producto, id_tienda){
			$('#eventos_entradas').load('mod_eventos.php',{guia:$('#guia').val(),id_movimiento:id,origen:$('#origen').val(),fe_entrada:$('#fe_entrada').val(),id:id_producto, 'cantidad':cantidad, tienda:id_tienda, accion:1,evento:3});
		}
	</script>
	<?php 
   }
  if ($_POST['evento']==4){	
	$sql="SELECT id_producto,
  id_tipo_producto,
  tx_codigo,
  tx_nombre,
  id_unidad_medida,
  tx_marca,
  tx_modelo,
  tx_descripcion,
  costo,
  estatus,
  id_producto_padre,
  tx_serial,
  tx_ngr,
  id_garantia,
  id_condicion FROM mod_producto WHERE id_producto='".$_POST['id_producto']."'";
  $res=abredatabase(g_BaseDatos,$sql);
  $row2=dregistro($res);
  if (dnumerofilas($res)>0) {
	  $codigo_producto=$row2['id_producto'];
	/*  $sql="UPDATE mod_producto SET costo=".$_POST['costo'].", estatus=".$_POST['estatus_equipo'].", tx_observacion='".$_POST['observacion']."', id_condicion=".$_POST['condicion_equipo']." WHERE id_producto=".$row2['id_producto'];
	  $res=abredatabase(g_BaseDatos,$sql);
	  $row2=dregistro($res);
	  */
		   $sql_movimiento="SELECT (ultimo_movimiento) as id_tipo_movimiento, 
		   (n_stock) as existencia
		   FROM mod_producto d WHERE id_producto=".$row2['id_producto']; 
	  $res_movimiento=abredatabase(g_BaseDatos,$sql_movimiento);
	  $row_movimiento=dregistro($res_movimiento);
	 // $row_movimiento['id_tipo_movimiento'];
	  $stock=$row_movimiento['existencia']==null? 0 : $row_movimiento['existencia'];
	  if ($row_movimiento['id_tipo_movimiento']!=2 and $row_movimiento['existencia']>1) {
			$sql="INSERT INTO  mod_movimiento(id_tipo_movimiento,id_motivo,tx_proposito,id_tienda,tx_ticket,tx_guia,fe_movimiento,id_producto,tx_serial,nx_cantidad,estatus_producto,id_condicion_producto,tx_accesorios,tx_observacion,id_responsable_destino, id_usuario,id_tipo_preparacion,id_tipo_enviado_a,id_responsable_enviado, id_estatus_movimiento,id_tipo_guia) VALUES('2',".$_POST['motivo'].",'".$_POST['proposito']."',".$_POST['origen'].",'".$_POST['ticket']."','".$_POST['guia']."','".$_POST['fe_entrada']."',".$codigo_producto.",'".$row2['tx_serial']."',".$_POST['cantidad'].",".$row2['estatus'].",".$row2['id_condicion'].",'".$_POST['accesorios']."','".$_POST['observacion']."','".$_POST['responsable']."',".$_SESSION['id_usuario'].",".$_POST['preparacion'].",".$_POST['enviado'].",'".$_POST['responsable_enviado']."',1,".$_POST['tipo_guia'].")";
			$res=abredatabase(g_BaseDatos,$sql);
			$row2=dregistro($res);
			if ($row_movimiento['existencia']==$_POST['cantidad']){
				$sql="UPDATE mod_producto SET n_stock=".$stock."-".$_POST['cantidad'].", ultimo_movimiento=2, id_tienda=".$_POST['origen']." WHERE id_producto=".$codigo_producto;
				$res=abredatabase(g_BaseDatos,$sql);
			}else{
				$sql="UPDATE mod_producto SET n_stock=".$stock."-".$_POST['cantidad'].", ultimo_movimiento=1, id_tienda=".$_POST['origen']." WHERE id_producto=".$codigo_producto;
				$res=abredatabase(g_BaseDatos,$sql);
			}
	  }
	  else{
		  if  ($row_movimiento['id_tipo_movimiento']!=2 AND $row_movimiento['existencia']=1) {
				 $sql="INSERT INTO  mod_movimiento(id_tipo_movimiento,id_motivo,tx_proposito,id_tienda,tx_ticket,tx_guia,fe_movimiento,id_producto,tx_serial,nx_cantidad,estatus_producto,id_condicion_producto,tx_accesorios,tx_observacion,id_responsable_destino, id_usuario,id_tipo_preparacion,id_tipo_enviado_a,id_responsable_enviado, id_estatus_movimiento,id_tipo_guia) VALUES('2',".$_POST['motivo'].",'".$_POST['proposito']."',".$_POST['origen'].",'".$_POST['ticket']."','".$_POST['guia']."','".$_POST['fe_entrada']."',".$codigo_producto.",'".$row2['tx_serial']."',".$_POST['cantidad'].",".$row2['estatus'].",".$row2['id_condicion'].",'".$_POST['accesorios']."','".$_POST['observacion']."','".$_POST['responsable']."',".$_SESSION['id_usuario'].",".$_POST['preparacion'].",".$_POST['enviado'].",'".$_POST['responsable_enviado']."',1,".$_POST['tipo_guia'].")";
				$res=abredatabase(g_BaseDatos,$sql);
				$row2=dregistro($res);
				$sql="UPDATE mod_producto SET n_stock=".$stock."-".$_POST['cantidad'].", ultimo_movimiento=2, id_tienda=".$_POST['origen']." WHERE id_producto=".$codigo_producto;
				$res=abredatabase(g_BaseDatos,$sql);
		  }else{
				echo '<script> swal("Oops!", "Producto ya aparece registrado con una Salida y no posee existencia!", "error");
			</script>';
		  }
	  }	
	  ?>
	 <script> 
		refrescar();
	  </script>
	  <?php
  }
  cierradatabase();
  }
  //mostrar movimientos de salidas
  if ($_POST['evento']==5){	
	$sql="SELECT id_movimiento, id_producto,  (tx_descripcion || ' ' || tx_marca || ' ' || tx_modelo ) as tx_descripcion,tx_serial,tx_nombre_tipo,nx_cantidad, nx_costo FROM vie_tbl_movimiento WHERE tx_guia='".$_POST['guia']."' and id_tienda='".$_POST['origen']."' and fe_movimiento='".$_POST['fe_entrada']."' and  id_tipo_movimiento=2 ORDER BY id_movimiento DESC";
	$res=abredatabase(g_BaseDatos,$sql);
	$c=0;
	?>
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
                <tbody>
				<?php while($row2=dregistro($res)){ $c+=1;?>
                <tr>
                  <td><?php echo $row2['tx_descripcion']; ?></td>
                  <td><?php echo $row2['tx_serial']; ?>
                  </td>
                  <td><?php echo $row2['tx_nombre_tipo']; ?></td>
                  <td align="center"> <?php echo $row2['nx_cantidad']; ?></td>
                  <td align="right"><?php echo $row2['nx_costo']; ?></td>
				  <td align="center" onclick="javascript:eliminar_elemento(<?php echo $row2['id_movimiento']; ?>,<?php echo $row2['nx_cantidad']; ?>, <?php echo $row2['id_producto']; ?> )"><i class="fa fa-trash"></i></td>
                </tr>
				<?php } cierradatabase(); ?>
				</tbody>
	</table>
	<input id=contador type='hidden' value='<?php echo $c; ?>'>
	<script>
	$(function () {
    $("#movimiento_entrada").DataTable();
  });					
  </script>	
	<?php 
  }
  if ($_POST['evento']==6){	
		$sql="UPDATE mod_producto SET n_stock=n_stock+".$_POST['cantidad'].", ultimo_movimiento=1, id_tienda=1 WHERE id_producto=".$_POST['id'];
		$res=abredatabase(g_BaseDatos,$sql);
		$sql="DELETE  FROM mod_movimiento WHERE id_movimiento=".$_POST['id_movimiento'];
		$res=abredatabase(g_BaseDatos,$sql);
	?>
	<script>
		$('#movimientos').load('mod_eventos.php',{guia:'<?php echo $_POST['guia']; ?>', origen:<?php echo $_POST['origen']; ?>,fe_entrada:'<?php echo $_POST['fe_entrada']; ?>',accion:1,evento:5});
		 function eliminar_elemento(id, cantidad, id_producto){
			$('#eventos_entradas').load('mod_eventos.php',{guia:$('#guia').val(),id_movimiento:id,origen:$('#origen').val(),fe_entrada:$('#fe_entrada').val(),id:id_producto, 'cantidad':cantidad, accion:1,evento:6});
		}
	</script>
	<?php 
   }
   //buscar equipo
  if ($_POST['evento']==7){	
    $busqueda=TRIM(strtoupper($_POST['id']));
	 $sql="SELECT id_producto, tx_serial, tx_descripcion, tx_tipo_producto, tx_marca, tx_modelo, (SELECT n_stock FROM mod_producto WHERE id_producto=a.id_producto LIMIT 1) AS n_stock FROM vie_tbl_equipos a WHERE  tx_serial LIKE '%".$busqueda."%' OR  upper(tx_descripcion) LIKE '%".$busqueda."%' OR upper(tx_tipo_producto) LIKE '%".$busqueda."%' OR upper(tx_marca) LIKE '%".$busqueda."%' OR  upper(tx_modelo) LIKE '%".$busqueda."%' ";
	$res=abredatabase(g_BaseDatos,$sql);
	$n_resultado=dnumerofilas($res);
	?>
	<div class="col-xs-6">
		Encontrado (<?php echo $n_resultado; ?>) productos... 
	</div>
	<div class="col-xs-12" style=" overflow: scroll; height:500px; margin-top:20px">
	<table id="resultado_busqueda" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>SERIAL</th>
				  <th>DESCRIPCION</th>
                  <th>TIPO</th>
                  <th>MARCA</th>
                  <th>MODELO</th>
                  <th>STOCK</th>
				  <th>AGREGAR</th>
                </tr>
                </thead>
                <tbody>
				<?php while($row2=dregistro($res)){ ?>
                <tr onclick="javascript:agregar_equipo('<?php echo TRIM($row2['id_producto']); ?>','<?php echo TRIM($row2['tx_serial']); ?>');">
					<td><?php echo $row2['id_producto']; ?></td>
					<td><?php echo $row2['tx_serial']; ?></td>
					<td><?php echo $row2['tx_descripcion']; ?></td>
					<td><?php echo $row2['tx_tipo_producto']; ?></td>
					<td align="center"> <?php echo $row2['tx_marca']; ?></td>
					<td align="right"><?php echo $row2['tx_modelo']; ?></td>
					<td align="right"><?php echo $row2['n_stock']; ?></td>
					<td align="center" ><i class="fa fa-arrow-circle-right"></i></td>
                </tr>
				<?php } cierradatabase(); ?>
				</tbody>
	</table>
	</div>
	<script>
	$(function () {
    $("#movimiento_entrada").DataTable();
  });		
	function agregar_equipo(id,serial){
		$('#id_producto').val(id);
		$('#serial').val(serial);
		buscar_serial();
		 $('#myModal_buscar_equipos').modal('hide');  
	}
  </script>	
	<?php 
  }
  // carga masiva de productos
  if ($_POST['evento']==8){
		date_default_timezone_set($_SESSION['zona_horario']);
		 $garantia=intval($_POST['tiempo_garantia'])*30;
		 $fecha=substr($_POST['fe_entrada'],6,4)."-".substr($_POST['fe_entrada'],3,2)."-".substr($_POST['fe_entrada'],0,2);
		 $fecha = date($fecha);
		$nuevafecha = strtotime ( '+'.$garantia.' day' , strtotime ( $fecha ) ) ;
		 $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
	  for ($i=1;$i<=$_POST['cantidad'];$i++){
	    $sql="INSERT INTO mod_producto(id_tipo_producto,tx_descripcion,id_tipo_marca,tx_modelo,costo,estatus,tx_ngr,id_garantia,id_condicion, id_producto_padre, id_unidad_medida,fe_vencimiento,id_proveedor, tx_accesorios,fe_ingreso,id_tipo_motivo,tx_nu_motivo,tx_nu_cotizacion,tx_guia_remision,id_usuario, n_stock,ultimo_movimiento,id_tienda, id_unidad_negocio) VALUES(".$_POST['tipo'].",'".$_POST['descripcion']."','".$_POST['marca']."','".$_POST['modelo']."',".$_POST['costo'].",5,'".$_POST['ngr']."',".$_POST['garantia'].",".$_POST['condicion_equipo'].",0,8,'".$nuevafecha."',".$_POST['proveedor'].",'".$_POST['accesorios']."','".$_POST['fe_entrada']."',".$_POST['motivo'].",'".$_POST['codigo_motivo']."','".$_POST['cotizacion']."','".$_POST['guia']."','".$_SESSION['id_usuario']."',0,0,1,'".$_POST['un']."')";
	  $res=abredatabase(g_BaseDatos,$sql);
	  $row2=dregistro($res);
	  } 
	  $evento=9;
  }
     //buscar equipo
  if ($evento==9){	
	$sql="SELECT id_producto, tx_serial, tx_descripcion, tx_tipo_producto, tx_marca, tx_modelo FROM vie_tbl_equipos WHERE tx_guia_remision ='".$_POST['guia']."' and fe_ingreso='".$_POST['fe_entrada']."' ORDER BY id_producto";
	$res=abredatabase(g_BaseDatos,$sql);
	?>
	<table id="resultado_busqueda" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th width="20%" >SERIAL</th>
				  <th width="40%" >DESCRIPCION</th>
                  <th width="10%" >TIPO</th>
                  <th width="10%" >MARCA</th>
                  <th width="10%" >MODELO</th>
				  <th width="5%" >CANT.</th>
				  <th colspan="2" width="5%" >ACT</th>
                </tr>
                </thead>
                <tbody>
				<?php $c=0; while($row2=dregistro($res)){ 
				$c+=1;?>
                <tr>
					<td>
						<input name="serial" id="SERIAL" maxlength="50" class="form-control" tabIndex="<?php echo $c; ?>" type="text" placeholder="SERIAL" required="required" onkeypress="javascript:actualizar_serial(event,<?php echo $row2['id_producto']; ?>,this.tabIndex, this.value, $('#cantidad_<?php echo $row2['id_producto']; ?>').val() ); " value="<?php echo $row2['tx_serial']; ?>">
					</td>
					<td><?php echo $row2['tx_descripcion']; ?></td>
					<td><?php echo $row2['tx_tipo_producto']; ?></td>
					<td align="center"> <?php echo $row2['tx_marca']; ?></td>
					<td align="right"><?php echo $row2['tx_modelo']; ?></td>
					<td align="right"><input name="cantidad_<?php echo $row2['id_producto']; ?>" id="cantidad_<?php echo $row2['id_producto']; ?>" maxlength="5" class="form-control"  type="text" placeholder="Cantidad" value="1" ></td>
					<td align="center" ><div id="act<?php echo $row2['id_producto']; ?>"></div></td>
					<td align="center" >
						<a href="javascript:borrar_registro_masivo(<?php echo $row2['id_producto']; ?>);">
							<div id="borrar<?php echo $row2['id_producto']; ?>"  >
								<i class="fa fa-trash"></i>
							</div>
						</a>
					</td>
                </tr>
				<?php } cierradatabase(); ?>
				</tbody>
	</table>
	<script>
    function actualizar_serial(event,id,tab,valor, cantidad){
		if (event.keyCode==13){
			//alert(valor);
			$("#act"+id).load("mod_eventos.php",{'id':id,'serial':valor,'evento':'10', cantidad:cantidad });
			var x=document.getElementsByName("serial")[tab];
			x.focus();
		}	
	}
	function borrar_registro_masivo(id){
			$("#borrar"+id).load("mod_eventos.php",{'id':id,'guia':'<?php echo $_POST['guia']; ?>','fecha':'<?php echo $_POST['fe_entrada']; ?>', 'evento':'101'});
	}
  </script>	
	<?php 
  }
  if ($_POST['evento']==9){
	$sql="SELECT id_producto, tx_serial, tx_descripcion, tx_tipo_producto, tx_marca, tx_modelo FROM vie_tbl_equipos WHERE tx_guia_remision ='".$_POST['guia']."' and fe_ingreso='".$_POST['fe_entrada']."' ORDER BY id_producto";
	$res=abredatabase(g_BaseDatos,$sql);
	?>
	<table id="resultado_busqueda" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th width="20%" >SERIAL</th>
				  <th width="40%" >DESCRIPCION</th>
                  <th width="10%" >TIPO</th>
                  <th width="10%" >MARCA</th>
                  <th width="10%" >MODELO</th>
				  <th width="5%" >CANT.</th>
				  <th colspan="2" width="5%" >ACT</th>
                </tr>
                </thead>
                <tbody>
				<?php $c=0; while($row2=dregistro($res)){ 
					 $sql_consulta="SELECT a.id_producto FROM mod_movimiento a, mod_producto b WHERE a.id_producto=b.id_producto and a.tx_guia=b.tx_guia_remision and a.fe_movimiento=fe_ingreso and a.id_producto=".$row2['id_producto'];
					$res_consulta=abredatabase(g_BaseDatos,$sql_consulta);
					if (dnumerofilas($res_consulta)==0){
							$marcas=0;
					}else{ $marcas=1; }
				$c+=1;?>
                <tr>
					<td>
						<input name="serial" id="SERIAL" maxlength="50" class="form-control" tabIndex="<?php echo $c; ?>" type="text" placeholder="SERIAL" required="required" onkeypress="javascript:actualizar_serial(event,<?php echo $row2['id_producto']; ?>,this.tabIndex, this.value, $('#cantidad_<?php echo $row2['id_producto']; ?>').val() ); " value="<?php echo $row2['tx_serial']; ?>">
					</td>
					<td><?php echo $row2['tx_descripcion']; ?></td>
					<td><?php echo $row2['tx_tipo_producto']; ?></td>
					<td align="center"> <?php echo $row2['tx_marca']; ?></td>
					<td align="right"><?php echo $row2['tx_modelo']; ?></td>
					<td align="right"><input name="cantidad_<?php echo $row2['id_producto']; ?>" id="cantidad_<?php echo $row2['id_producto']; ?>" maxlength="5" class="form-control"  type="text" placeholder="Cantidad" value="1" ></td>
					<td align="center" ><?php if ($marcas==1) {  
						echo "<i class='fa fa-check'></i>";
					echo "<i class='fa fa-check'></i>"; } else {?> 
						<div id="act<?php echo $row2['id_producto']; ?>"></div>
					<?php } ?>
					</td>
					<td align="center" >
						<a href="javascript:borrar_registro_masivo(<?php echo $row2['id_producto']; ?>);">
							<div id="borrar<?php echo $row2['id_producto']; ?>"  >
								<i class="fa fa-trash"></i>
							</div>
						</a>
					</td>
                </tr>
				<?php } cierradatabase(); ?>
				</tbody>
	</table>
		<script>
		 function actualizar_serial(event,id,tab,valor, cantidad){
			if (event.keyCode==13){
				//alert(valor);
				$("#act"+id).load("mod_eventos.php",{'id':id,'serial':valor,'evento':'10', cantidad:cantidad });
				var x=document.getElementsByName("serial")[tab];
				x.focus();
			}	
		}
		function borrar_registro_masivo(id){
				$("#borrar"+id).load("mod_eventos.php",{'id':id,'guia':'<?php echo $_POST['guia']; ?>','fecha':'<?php echo $_POST['fe_entrada']; ?>', 'evento':'101'});
			return false;
		}
	  </script>	
<?php 
  }
  if ($_POST['evento']==10){
	if ($_POST['cantidad'] >0 ){ 
		$sql="UPDATE mod_producto SET tx_serial='".$_POST['serial']."', n_stock=".$_POST['cantidad'].", ultimo_movimiento=1 WHERE id_producto=".$_POST['id'];
		$res=abredatabase(g_BaseDatos,$sql);
		 $sql_consulta="SELECT a.id_producto FROM mod_movimiento a, mod_producto b WHERE a.id_producto=b.id_producto and a.tx_guia=b.tx_guia_remision and a.fe_movimiento=fe_ingreso and a.id_producto=".$_POST['id'];
		 $res_consulta=abredatabase(g_BaseDatos,$sql_consulta);
		 if (dnumerofilas($res_consulta)==0){
			 $sql="INSERT INTO mod_movimiento (id_motivo, id_proveedor, id_tienda, fe_movimiento, tx_guia, id_producto, tx_serial, nx_cantidad, nx_costo, estatus_producto, id_condicion_producto, tx_accesorios, id_tipo_movimiento, id_estatus_movimiento,id_usuario,fe_creacion) (SELECT id_tipo_motivo, id_proveedor, 1, fe_ingreso,tx_guia_remision,id_producto,tx_serial,1,costo,5,id_condicion,tx_accesorios,1,1,id_usuario,fe_creacion FROM mod_producto WHERE id_producto=".$_POST['id'].")";
			$res=abredatabase(g_BaseDatos,$sql);
			echo "<i class='fa fa-check'></i>";
		 }else{
		    $sql_consulta="UPDATE mod_movimiento SET nx_cantidad=".$_POST['cantidad']." WHERE id_producto=".$_POST['id'];
			$res_consulta=abredatabase(g_BaseDatos,$sql_consulta);
			echo "<i class='fa fa-check'></i>";
			echo "<i class='fa fa-check'></i>";
		 }
	}else{
		alert('Por favor introduzca la cantidad del Item');
	}
  }
  //borrar elemento cargago masivamente
	 if ($_POST['evento']==101){
		  $sql_consulta="SELECT a.id_producto FROM mod_movimiento a, mod_producto b WHERE a.id_producto=b.id_producto and a.tx_guia=b.tx_guia_remision and a.fe_movimiento=fe_ingreso and a.id_producto=".$_POST['id'];
		  $res_consulta=abredatabase(g_BaseDatos,$sql_consulta);
			if (dnumerofilas($res_consulta)==0){
				$sql="DELETE FROM mod_producto WHERE tx_guia_remision='".$_POST['guia']."' and fe_ingreso='".$_POST['fecha']."' AND id_producto=".$_POST['id'];
				$res=abredatabase(g_BaseDatos,$sql);
				?>
				<script>
					$("#registro_productos").load("mod_eventos.php",{'guia':'<?php echo $_POST['guia']; ?>','fe_entrada':'<?php echo $_POST['fecha']; ?>', 'evento':'9'});
				</script>
	<?php 		}else{ 
					echo "<i class='fa fa-trash'></i>";
					$sql="DELETE FROM mod_movimiento WHERE  id_producto=".$_POST['id'];
					$res=abredatabase(g_BaseDatos,$sql);
					$sql="DELETE FROM mod_producto WHERE id_producto=".$_POST['id'];
					$res=abredatabase(g_BaseDatos,$sql);
				}
	} 
   //BUSCAR Y/O ANULAR GUIA DE SALIDA 
  if ($_POST['evento']==11){	
	 $sql="SELECT tx_guia, fe_movimiento, (SELECT tx_tipo FROM vie_tbl_tipo_motivos_salidas WHERE a.id_motivo=id_tipo_objeto) AS tx_codigo_motivo, (SELECT tx_descripcion FROM mod_tienda WHERE id_tienda=a.id_tienda LIMIT 1) AS nombre_origen_destino, count(tx_guia) as n_productos, CASE WHEN id_estatus_movimiento=1 THEN 'ACTIVA' WHEN id_estatus_movimiento=2 THEN 'RESERVADA' WHEN id_estatus_movimiento=3 THEN 'PRESTADA' ELSE 'ANULADA' END  AS estatus,id_tipo_guia FROM mod_movimiento a WHERE tx_guia LIKE '".$_POST['id']."%' and id_tipo_movimiento=2 GROUP BY id_tipo_guia, tx_guia, fe_movimiento, a.id_motivo, id_tienda, id_estatus_movimiento ORDER BY fe_movimiento DESC, tx_guia";
	$res=abredatabase(g_BaseDatos,$sql);
	?>
	<table id="resultado_busqueda" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th width="15%">N° GUIA</th>
				  <th width="15%">FECHA</th>
                  <th width="15%">TIPO DE MOTIVO</th>
                  <th width="30%">DESTINO</th>
                  <th width="10%">N° EQUIPOS</th>
				  <th width="10%">ESTATUS</th>
				  <th width="10%">VER GUIA</th>
				  <th width="10%">ANULAR</th>
                </tr>
                </thead>
                <tbody>
				<?php while($row2=dregistro($res)){ ?>
                <tr>
					<td align="center"><?php echo $row2['tx_guia']; ?></td>
					<td align="center"><?php echo $row2['fe_movimiento']; ?></td>
					<td align="center"><?php echo $row2['tx_codigo_motivo']; ?></td>
					<td align="left"> <?php echo $row2['nombre_origen_destino']; ?></td>
					<td align="center"> <?php echo $row2['n_productos']; ?></td>
					<td align="center"> <?php echo $row2['estatus'];  ?></td>
					<td align="center" onclick="javascript:ver_guia('<?php echo TRIM($row2['tx_guia']); ?>','<?php echo $row2['fe_movimiento']; ?>',<?php echo $row2['id_tipo_guia']; ?>);"Ver Guía>
						<i class="fa fa-arrow-circle-right"></i>
					</td>
					<?php if ($row2['estatus']!='ANULADA'){ ?>
					<td align="center" onclick="javascript:anular_guia('<?php echo TRIM($row2['tx_guia']); ?>');" title="Anular Guía"><i class="fa fa-ban"></i></td>
					<?php }else{ ?>
						<td></td>
					<?php } ?>
                </tr>
				<?php } cierradatabase(); ?>
				</tbody>
	</table>
	<script>
	$(function () {
    $("#movimiento_entrada").DataTable();
  });		
	function agregar_guia(id){
		$("#datos_generales_guia").load('mod_eventos.php',{'guia':id,evento:13})
	}
	//function anular_guia(id){
		//$("#resultado_busqueda_guia").load('mod_eventos.php',{'guia':id,evento:12});
	//}
	function ver_guia(guia,fecha,tipo) {
		if (tipo==95){
			window.open('mod_mostrar_guia_salida.php?id=2&guia='+guia+'&fecha='+fecha);
		}else{
			window.open('mod_mostrar_guia_salida_interna.php?id=2&guia='+guia+'&fecha='+fecha);
		}
	}
  </script>	
	<?php 
  }
  //ANULAR GUIA DE SALIDA 
  if ($_POST['evento']==12){	
		date_default_timezone_set($_SESSION['zona_horario']);
		$sql="SELECT id_usuario FROM cfg_usuario WHERE tx_email='".$_POST['usuario']."' and tx_contrasena=MD5('".$_POST['pass']."') and id_perfil=4";
		$res=abredatabase(g_BaseDatos,$sql);
		if (dnumerofilas($res)==1) {
			$sql_movimiento="SELECT id_movimiento FROM mod_movimiento WHERE trim(tx_guia)='".$_POST['guia']."'";
			$res_movimiento=abredatabase(g_BaseDatos,$sql_movimiento);
			if (dnumerofilas($res_movimiento)>=1) {
				$sql="UPDATE mod_movimiento SET id_estatus_movimiento=0 WHERE tx_guia = '".$_POST['guia']."'";
				$res=abredatabase(g_BaseDatos,$sql);
				$sql="UPDATE mod_producto a SET n_stock=n_stock + b.nx_cantidad, ultimo_movimiento=1, id_tienda=1  FROM mod_movimiento b WHERE a.id_producto=b.id_producto and b.tx_guia= '".$_POST['guia']."'";
				$res=abredatabase(g_BaseDatos,$sql);
			}else{
				 $sql="INSERT INTO mod_movimiento (tx_guia, id_motivo, id_tienda, id_usuario, nx_cantidad,  tx_observacion, id_tipo_movimiento, id_estatus_movimiento, fe_movimiento, tx_ticket) VALUES ('".$_POST['guia']."',89, 1,".$_SESSION['id_usuario'].",0,'".$_POST['observacion']."',2,0,'".$_POST['fe_anulada']."', 'ANULACION DE GUIA AUTORIZADA')";
				$res=abredatabase(g_BaseDatos,$sql);
			}
			echo '<script> swal("Proceso Exitoso!", "OPERACION DE ANULACION DE GUIA N° '.$_POST['guia'].' FUE ANULADA CON EXITO!", "success");
			</script>';
			cierradatabase();
			echo "<script>setTimeout(function (){location.reload();},1000);</script>";
		}else {
			echo '<script> swal("Oops!", "OPERACION DE ANULACION NO AUTORIZADO", "error");
			</script>';
			echo "<script>setTimeout(function (){location.reload();},1000);</script>";
		}
  }
  //CARGAR GUIA SALIDA
  if ($_POST['evento']==13){
	  $sql2="SELECT tx_guia, fe_movimiento, id_tienda, tx_ticket, tx_proposito, id_motivo, tx_responsable, id_tipo_enviado_a, id_tipo_preparacion, id_tipo_enviado_a   FROM mod_movimiento WHERE  id_tipo_movimiento=2 and tx_guia = '".$_POST['guia']."' LIMIT 1";
	  date_default_timezone_set($_SESSION['zona_horario']);
	$res2=abredatabase(g_BaseDatos,$sql2);
	 $row2=dregistro($res2) 
 ?>	  
	  <div class="form-group">
					<div class="col-xs-6">
					<label>Destino</label>
					<select id="origen" class="form-control select2" required="required">
					 <option value="">----Seleccione ---</option>
					  <?php 
					   $sql="SELECT id_tienda,(tx_marca || ' - ' || tx_descripcion) as tx_nombre FROM vie_tbl_tiendas";
					   $res=abredatabase(g_BaseDatos,$sql);
					   WHILE ($row=dregistro($res)){
					   ?>
					  <option value="<?php echo $row['id_tienda']; ?>" <?php if ($row['id_tienda']==$row2['id_tienda']){ echo 'selected'; } ?> ><?php echo $row['tx_nombre']; ?></option>
					   <?php } cierradatabase(); ?>
					</select>
					</div>
					<div class="col-xs-3">
					<label>Motivo</label>
					<select id="motivo" class="form-control select2" required="required">
					<option value="">----Seleccione ---</option>
					  <?php 
					   $sql="SELECT id_tipo_objeto, tx_tipo FROM vie_tbl_tipo_motivos_salidas";
					   $res=abredatabase(g_BaseDatos,$sql);
					   WHILE ($row=dregistro($res)){
					   ?>
					  <option value="<?php echo $row['id_tipo_objeto']; ?>" <?php if ($row['id_tipo_objeto']==$row2['id_motivo']){ echo 'selected'; } ?>><?php echo $row['tx_tipo']; ?></option>
					   <?php } cierradatabase(); ?>
					</select>
					</div>
					<div class="col-xs-3">
						<!-- Date -->
						<label>Fecha de Egreso:</label>
						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input id="fe_entrada" type="text" class="form-control pull-right" placeholder="Fecha de Egreso" required="required" value="<?php echo $row2['fe_movimiento']; ?>" <?php if($_SESSION['rol']<4){ echo "disabled='disabled'"; }?>>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6">
						<label>Proposito</label>
						<input id="proposito" class="form-control" type="text" placeholder="Proposito" required="required" value="<?php echo $row2['tx_proposito']; ?>">
					</div>
					<div class="col-xs-6">
						<label>Ticket:</label>
						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-arrow-circle-right"></i>
							</div>
							<input id="ticket" type="text" class="form-control pull-right" placeholder="N° de ticket" required="required" value="<?php echo $row2['tx_ticket']; ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6">
						<label>Responsable</label>
						<input id="responsable" class="form-control" type="text" placeholder="Responsable" required="required" value="<?php echo $row2['tx_responsable']; ?>">
					</div>
					<div class="col-xs-6">
						<!-- Date -->
						<label>N° de Guia:</label>
						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-arrow-circle-right"></i>
							</div>
							<input id="guia" type="text" class="form-control pull-right" placeholder="N° de Guia" required="required" value="<?php echo $row2['tx_guia']; ?>" <?php if($_SESSION['rol']<4){ echo "disabled='disabled'"; }?>	>
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
					  <option value="<?php echo $row['id_tipo_objeto']; ?>" <?php if ($row['id_tipo_objeto']==$row2['id_tipo_preparacion']){ echo 'selected'; } ?>><?php echo $row['tx_tipo']; ?></option>
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
					  <option value="<?php echo $row['id_tipo_objeto']; ?>" <?php if ($row['id_tipo_objeto']==$row2['id_tipo_enviado_a']){ echo 'selected'; } ?>><?php echo $row['tx_tipo']; ?></option>
					   <?php } cierradatabase(); ?>
					</select>
					</div>
					<div class="col-xs-6">
						<label>Responsable (enviado a)</label>
						<select id="responsable_enviado" class="form-control select2" required="required">
							<option value="">----Seleccione ---</option>
							<?php 
								$sql="SELECT id_responsable, (tx_representacion || ' ' || tx_responsable) as responsable FROM mod_responsable";
								$res=abredatabase(g_BaseDatos,$sql);
								WHILE ($row=dregistro($res)){
							?>
									<option value="<?php echo $row['id_responsable']; ?>"  <?php if ($row['id_responsable']==$row2['id_tipo_enviado_a']){ echo 'selected'; } ?>>
										<?php echo $row['responsable']; ?>
									</option>
							<?php } cierradatabase(); ?>
						</select>
					</div>
				</div>
				<script> 
				refrescar();
				 $('#myModal_buscar_guia').modal('hide');  
				</script>
  <?php }
  ///////////////////////////////////////////////////////////////////////
  //BUSCAR Y/O ANULAR GUIA DE ENTRADA 
  if ($_POST['evento']==14){	
	 $sql="SELECT tx_guia, fe_movimiento, (SELECT tx_tipo FROM vie_tbl_tipo_motivos_salidas WHERE a.id_motivo=id_tipo_objeto) AS tx_codigo_motivo, (SELECT tx_descripcion FROM mod_tienda WHERE id_tienda=a.id_tienda LIMIT 1) AS nombre_origen_destino, count(tx_guia) as n_productos, CASE WHEN id_estatus_movimiento=1 THEN 'ACTIVA' WHEN id_estatus_movimiento=2 THEN 'RESERVADA' WHEN id_estatus_movimiento=3 THEN 'PRESTADA' ELSE 'ANULADA' END  AS estatus FROM mod_movimiento a WHERE tx_guia LIKE '".$_POST['id']."%' and id_tipo_movimiento=1 GROUP BY tx_guia, fe_movimiento, a.id_motivo, id_tienda, id_estatus_movimiento ORDER BY fe_movimiento DESC, tx_guia";
	$res=abredatabase(g_BaseDatos,$sql);
	?>
	<table id="resultado_busqueda" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th width="15%">N° GUIA</th>
				  <th width="15%">FECHA</th>
                  <th width="15%">TIPO DE MOTIVO</th>
                  <th width="30%">DESTINO</th>
                  <th width="10%">N° EQUIPOS</th>
				  <th width="10%">ESTATUS</th>
				  <th width="10%">AGREGAR</th>
				  <th width="10%">ANULAR</th>
                </tr>
                </thead>
                <tbody>
				<?php while($row2=dregistro($res)){ ?>
                <tr>
				  <td align="center"><?php echo $row2['tx_guia']; ?></td>
                  <td align="center"><?php echo $row2['fe_movimiento']; ?></td>
                  <td align="center"><?php echo $row2['tx_codigo_motivo']; ?></td>
                  <td align="left"> <?php echo $row2['nombre_origen_destino']; ?></td>
                  <td align="center"> <?php echo $row2['n_productos']; ?></td>
                  <td align="center"> <?php echo $row2['estatus'];  ?></td>
				  <td align="center" onclick="javascript:agregar_guia('<?php echo TRIM($row2['tx_guia']); ?>');"Agregar Guía><i class="fa fa-arrow-circle-right"></i></td>
				  <td align="center" onclick="javascript:anular_guia('<?php echo TRIM($row2['tx_guia']); ?>');" title="Anular Guía"><i class="fa fa-ban"></i></td>
                </tr>
				<?php } cierradatabase(); ?>
				</tbody>
	</table>
	<script>
	$(function () {
    $("#movimiento_entrada").DataTable();
  });		
	function agregar_guia(id){
		$("#datos_generales_guia").load('mod_eventos.php',{'guia':id,evento:16})
	}
	function anular_guia(id){
		$("#resultado_busqueda_guia").load('mod_eventos.php',{'guia':id,evento:15});
	}
  </script>	
	<?php 
  }
  //ANULAR GUIA DE ENTRADA 
  if ($_POST['evento']==15){	
	$sql="UPDATE mod_movimiento SET id_estatus_movimiento=1  WHERE tx_guia = '".$_POST['guia']."'";
	$res=abredatabase(g_BaseDatos,$sql);
	echo "Operación Exitosa Guía N°".$_POST['guia']." Fue Anulada";
	cierradatabase();
  }
  //CARGAR GUIA DE ENTRADA
  if ($_POST['evento']==16){
	  $sql2="SELECT tx_guia, fe_movimiento, id_tienda, tx_ticket, tx_proposito, id_motivo, tx_responsable, id_tipo_preparacion, id_tipo_enviado_a   FROM mod_movimiento WHERE id_tipo_movimiento=1 and tx_guia = '".$_POST['guia']."' LIMIT 1";
	  date_default_timezone_set($_SESSION['zona_horario']);
	$res2=abredatabase(g_BaseDatos,$sql2);
	 $row2=dregistro($res2) 
 ?>	  
 <div class="form-group">
					<div class="col-xs-6">
					<label>Tienda</label>
					<select id="origen" class="form-control select2" required="required">
					<option value="">----Seleccione ---</option>
					  <?php 
					   $sql="SELECT id_tienda,(tx_marca || ' - ' || tx_descripcion) as tx_nombre FROM vie_tbl_tiendas";
					   $res=abredatabase(g_BaseDatos,$sql);
					   WHILE ($row=dregistro($res)){
					   ?>
					  <option value="<?php echo $row['id_tienda']; ?>" <?php if ($row['id_tienda']==$row2['id_tienda']){ echo 'selected'; } ?>><?php echo $row['tx_nombre']; ?></option>
					   <?php } cierradatabase(); ?>
					</select>
					</div>
					<div class="col-xs-6">
					<label>Motivo</label>
					<select id="motivo" class="form-control select2" required="required">
					<option value="">----Seleccione ---</option>
					  <?php 
					   $sql="SELECT id_tipo_objeto, tx_tipo FROM vie_tbl_tipo_motivos_entrada";
					   $res=abredatabase(g_BaseDatos,$sql);
					   WHILE ($row=dregistro($res)){
					   ?>
					  <option value="<?php echo $row['id_tipo_objeto']; ?>" <?php if ($row['id_tipo_objeto']==$row2['id_motivo']){ echo 'selected'; } ?>><?php echo $row['tx_tipo']; ?></option>
					   <?php } cierradatabase(); ?>
					</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6">
						<label>Responsable</label>
						<input id="responsable" class="form-control" type="text" placeholder="Responsable" required="required" value="<?php echo $row2['tx_responsable']; ?>">
					</div>
					<div class="col-xs-6">
						<!-- Date -->
						<label>Fecha Ingreso:</label>
						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input id="fe_entrada" type="text" class="form-control pull-right" placeholder="Fecha de Ingreso" required="required" value="<?php echo $row2['fe_movimiento']; ?>" <?php if($_SESSION['rol']<4){ echo "disabled='disabled'"; }?>>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6">
						<label>Ticket:</label>
						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-arrow-circle-right"></i>
							</div>
							<input id="ticket" type="text" class="form-control pull-right" placeholder="N° de ticket" required="required" value="<?php echo $row2['tx_ticket']; ?>">
						</div>
					</div>
					<div class="col-xs-6">
						<!-- Date -->
						<label>N° de Guia de Remisión:</label>
						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-arrow-circle-right"></i>
							</div>
							<input id="guia" type="text" class="form-control pull-right" placeholder="N° de Guia" required="required" value="<?php echo $row2['tx_guia']; ?>" >
						</div>
					</div>
				</div> 
				<script> 
				refrescar();
				 $('#myModal_buscar_guia').modal('hide');  
				</script>
  <?php }
   if ($_POST['evento']==17){
	  $n_guia_f="000000";
	  $sql2="SELECT tx_guia  FROM mod_movimiento WHERE  id_motivo=".$_POST['id']." ORDER BY id_movimiento DESC LIMIT 1";
	  $res2=abredatabase(g_BaseDatos,$sql2);
	  $row2=dregistro($res2);
	  $n_guia=isset($row2[0])? intval($row2[0])+1 : 1;
	  $n_guia_f=substr($n_guia_f,1,strlen($n_guia_f)-strlen($n_guia)).$n_guia;
	  if ($_POST['id']!=53){
   ?>
		<label>N° de Guia de Remisión:</label>
		<div class="input-group date">
			<div class="input-group-addon">
				<i class="fa fa-arrow-circle-right"></i>
			</div>
			<input id="guia" type="text" class="form-control pull-right" placeholder="N° de Guia" required="required" value="<?php echo $n_guia_f; ?>">
		</div>
	<?php }else{ 
		if ($_SESSION['rol']==4){ $disponible=""; } else { $disponible="disabled";  } 
	?>
		<label>N° de Guia de Remisión:</label>
		<div class="input-group date">
			<div class="input-group-addon">
				<i class="fa fa-arrow-circle-right"></i>
			</div>
			<input id="guia" type="text" class="form-control pull-right" placeholder="N° de Guia" required="required" <?php echo $disponible; ?> value="<?php echo $n_guia_f; ?>">
		</div>
   <?php } } 
   //CAMBIO DE CLAVE
   if ($_POST['evento']==18){
		$sql="UPDATE cfg_usuario SET tx_contrasena=md5('".$_POST['nuevo_pass']."') WHERE id_usuario=".$_POST['id'];
		$res=abredatabase(g_BaseDatos,$sql);
		echo "<script>$('#myModal_clave').modal('hide');</script>";
   }
   //PRESTAMO DE GUIA
   if ($_POST['evento']==19){
		$sql="SELECT id_usuario FROM cfg_usuario WHERE tx_email='".$_POST['usuario']."' and tx_contrasena=MD5('".$_POST['pass']."') and id_perfil=4";
		$res=abredatabase(g_BaseDatos,$sql);
		if (dnumerofilas($res)==1) {
			$sql="INSERT INTO mod_movimiento (tx_guia, id_motivo, id_tienda, id_usuario, nx_cantidad,  id_tipo_movimiento, id_estatus_movimiento, tx_observacion) VALUES ('".$_POST['guia']."',11, 1,".$_SESSION['id_usuario'].",0,2,3,'".$_POST['observacion']."')";
			$res=abredatabase(g_BaseDatos,$sql);
			echo '<script> swal("Proceso Exitoso!", "PRESTAMO AUTORIZADO", "success");
			</script>';
			echo "<script>location.reload();</script>";
		}else {
			echo '<script> swal("Oops!", "PRESTAMO NO AUTORIZADO", "error");
			</script>';
			echo "<script>setTimeout(function (){location.reload();},1000);</script>";
		}
		cierradatabase();
   }
   if ($_POST['evento']==20){	
	$sql="SELECT id_producto,
  id_tipo_producto,
  tx_codigo,
  tx_nombre,
  id_unidad_medida,
  tx_marca,
  tx_modelo,
  tx_descripcion,
  costo,
  estatus,
  id_producto_padre,
  tx_serial,
  tx_ngr,
  id_garantia,
  id_condicion FROM mod_producto WHERE id_producto='".$_POST['id_producto']."'";
  $res=abredatabase(g_BaseDatos,$sql);
  $row2=dregistro($res);
  if (dnumerofilas($res)>0) {
	  $codigo_producto=$row2['id_producto'];
	   $sql_movimiento="SELECT id_tipo_movimiento, fe_movimiento,
		   (SELECT n_stock FROM mod_producto WHERE id_producto=d.id_producto) as existencia
		   FROM mod_movimiento d WHERE id_producto=".$row2['id_producto']." ORDER BY fe_movimiento DESC, fe_actualizada DESC LIMIT 1"; 
	  $res_movimiento=abredatabase(g_BaseDatos,$sql_movimiento);
	  $row_movimiento=dregistro($res_movimiento);
	  if ($row2['estatus']!=7 ) {
	    $sql="INSERT INTO  mod_movimiento(id_tipo_movimiento,id_motivo,tx_responsable,id_tienda,tx_ticket,tx_guia,fe_movimiento,id_producto,tx_serial,nx_cantidad,estatus_producto,id_condicion_producto,tx_observacion, id_usuario,tx_accesorios) VALUES('1',".$_POST['motivo'].",'".$_POST['responsable']."',".$_POST['origen'].",'".$_POST['ticket']."','".$_POST['guia']."','".$_POST['fe_entrada']."',".$codigo_producto.",'".$_POST['serial']."',".$_POST['cantidad'].",5,".$row2['id_condicion'].",'".$_POST['observacion']."',".$_SESSION['id_usuario'].",'".$_POST['accesorios']."')";
		$res=abredatabase(g_BaseDatos,$sql);
		$row2=dregistro($res);
		$sql="UPDATE mod_producto SET n_stock=".$_POST['cantidad'].", ultimo_movimiento=1, id_tienda=1, estatus=7 WHERE id_producto=".$codigo_producto;
		$res=abredatabase(g_BaseDatos,$sql);
	  }
	  else{
		  	echo '<script> swal("Oops!", "Producto ya aparece registrado con una Entrada", "error");
			</script>';
	  }	
  }
  cierradatabase();
  ?>
   <script>  refrescar();  </script> 
  <?php }
  //mostrar movimientos de baja
  if ($_POST['evento']==21){	
	 $sql="SELECT id_movimiento, id_producto, id_tienda,  (SELECT (tx_descripcion || ' ' || tx_marca || ' ' || tx_modelo) FROM mod_producto WHERE id_producto=mod_movimiento.id_producto) as tx_descripcion,tx_serial,(SELECT tx_nombre_tipo FROM mod_producto, cfg_tipo_producto WHERE mod_producto.id_tipo_producto=cfg_tipo_producto.id_tipo_producto and id_producto=mod_movimiento.id_producto) as tx_nombre_tipo,nx_cantidad, nx_costo FROM mod_movimiento WHERE tx_guia='".$_POST['guia']."' and id_tienda='".$_POST['origen']."' and fe_movimiento='".$_POST['fe_entrada']."' and id_tipo_movimiento=1 ORDER BY id_movimiento DESC";
	$res=abredatabase(g_BaseDatos,$sql);
	?>
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
                <tbody>
				<?php while($row2=dregistro($res)){ ?>
                <tr>
                  <td><?php echo $row2['tx_descripcion']; ?></td>
                  <td><?php echo $row2['tx_serial']; ?>
                  </td>
                  <td><?php echo $row2['tx_nombre_tipo']; ?></td>
                  <td align="center"> <?php echo $row2['nx_cantidad']; ?></td>
                  <td align="right"><?php echo $row2['nx_costo']; ?></td>
				  <td align="center" onclick="javascript:eliminar_elemento(<?php echo $row2['id_movimiento']; ?>,<?php echo $row2['nx_cantidad']; ?>,<?php echo $row2['id_producto']; ?>,<?php echo $row2['id_tienda']; ?>, )"><i class="fa fa-times-circle"></i></td>
                </tr>
				<?php } cierradatabase(); ?>
				</tbody>
	</table>
	<script>
	$(function () {
    $("#movimiento_entrada").DataTable();
  });					
  </script>	
	<?php 
  }
   if ($_POST['evento']==22){	
		$sql="UPDATE mod_producto SET n_stock=".$_POST['cantidad'].", ultimo_movimiento=2, id_tienda=".$_POST['tienda'].", estatus=5 WHERE id_producto=".$_POST['id'];
		$res=abredatabase(g_BaseDatos,$sql);
		$sql="DELETE  FROM mod_movimiento WHERE id_movimiento=".$_POST['id_movimiento'];
		$res=abredatabase(g_BaseDatos,$sql);
	?>
	<script>
		$('#movimientos').load('mod_eventos.php',{guia:'<?php echo $_POST['guia']; ?>', origen:<?php echo $_POST['origen']; ?>,fe_entrada:'<?php echo $_POST['fe_entrada']; ?>',accion:1,evento:2});
		 function eliminar_elemento(id, cantidad, id_producto, id_tienda){
			$('#eventos_entradas').load('mod_eventos.php',{guia:$('#guia').val(),id_movimiento:id,origen:$('#origen').val(),fe_entrada:$('#fe_entrada').val(),id:id_producto, 'cantidad':cantidad, tienda:id_tienda, accion:1,evento:22});
		}
	</script>
	<?php 
   }
   if ($_POST['evento']==23){	
		$sql_movimiento="SELECT n_stock  FROM mod_producto WHERE id_producto=".$_POST['id']; 
		$res_movimiento=abredatabase(g_BaseDatos,$sql_movimiento);
		$row_movimiento=dregistro($res_movimiento);
		$stock_actual=$row_movimiento['n_stock']==null? 0 : $row_movimiento['n_stock'];
		if ($_POST['tienda']>0){
		$stock=$_POST['cantidad'];
		if ($_POST['tipo']==1){
			$stock=$stock_actual + $stock;
			 $sql="UPDATE mod_producto SET n_stock=".$stock.", ultimo_movimiento=".$_POST['tipo'].", 	id_tienda=".$_POST['tienda']." WHERE id_producto=".$_POST['id'];
		}else{
			 $stock=$stock_actual - $stock;
			 $sql="UPDATE mod_producto SET n_stock=".$stock.", ultimo_movimiento=".$_POST['tipo'].", id_tienda=".$_POST['tienda']." WHERE id_producto=".$_POST['id'];
		}
		$res=abredatabase(g_BaseDatos,$sql);
			echo "<div class='callout callout-info'>
			<h4><i class='fa fa-check'></i> LISTO - ACTUALIZADO</h4>
			<p>EL PRODUCTO FUE ACTUALIZADO CON EXITOS!</p>
			</div>";
		}else{
			echo "<div class='callout callout-danger'>
				<h4><i class='fa fa-check'></i> NO SE PUDO ACTUALIZAR</h4>
				<p>ERROR DEBE EDITAR EL MOVIMIENTO Y COLOCAR UN ORIGEN O DESTINO!</p>
				</div>";
		}
   }
    if ($_POST['evento']==24){
		$sql_movimiento="SELECT id_movimiento FROM mod_movimiento WHERE trim(tx_guia)='".$_POST['guia']."'";
		$res_movimiento=abredatabase(g_BaseDatos,$sql_movimiento);
		if (dnumerofilas($res_movimiento)>=1) {
				$sql="UPDATE mod_producto a SET n_stock=n_stock + b.nx_cantidad, ultimo_movimiento=1, id_tienda=1  FROM mod_movimiento b WHERE a.id_producto=b.id_producto and b.tx_guia= '".$_POST['guia']."'";
				$res=abredatabase(g_BaseDatos,$sql);
				$sql="DELETE FROM mod_movimiento WHERE tx_guia = '".$_POST['guia']."'";
				$res=abredatabase(g_BaseDatos,$sql);
		}
		echo "<script>location.reload();</script>"; 
	}
   if ($_POST['evento']==25){
	  $n_guia_f="";
	  $GUIA="SELECT serie, guia_desde, guia_hasta FROM cfg_guias WHERE estatus=1";
	  $res_guia=abredatabase(g_BaseDatos,$GUIA);
	  $row_guia=dregistro($res_guia);
	  $serie=trim($row_guia['serie']);
	  $desde=trim($row_guia['guia_desde']);
	  $hasta=trim($row_guia['guia_hasta']);
	  for ($i=0; $i <= strlen($desde)-1; $i++){
		  $n_guia_f.="0";
	  }
	   if ($_POST['tipo']==95){
		   if ($_POST['id']==93){
				//$sql2="SELECT (substring(tx_guia  from 5 for 20)) as actual_guia, (substring(tx_guia  from 0 for 4)) as guia, (substring(tx_guia  from 5 for 20)::integer+1) as siguiente FROM mod_movimiento WHERE id_tipo_movimiento=2 and id_tipo_guia=95 and id_motivo=".$_POST['id']." ORDER BY id_movimiento DESC LIMIT 1";
				$sql2="SELECT (substring(tx_guia  from 5 for 20)) as actual_guia, (substring(tx_guia  from 0 for 4)) as guia, (substring(tx_guia  from 5 for 20)::integer+1) as siguiente FROM mod_movimiento WHERE id_tipo_movimiento=2  and id_motivo=".$_POST['id']." ORDER BY id_movimiento DESC LIMIT 1";
		   }else{
			   $sql2="SELECT (substring(tx_guia  from 5 for 20)) as actual_guia, (substring(tx_guia  from 0 for 4)) as guia, (substring(tx_guia  from 5 for 20)::integer+1) as siguiente FROM mod_movimiento WHERE id_tipo_movimiento=2 and id_tipo_guia=95 and id_motivo!=93 ORDER BY id_movimiento DESC LIMIT 1";
		   }
	   }else{
			$sql2="SELECT (substring(tx_guia  from 4 for 20)::integer+1) as siguiente FROM mod_movimiento WHERE id_tipo_movimiento=2 and id_tipo_guia=96  ORDER BY id_movimiento DESC LIMIT 1";
	   }
	  $res2=abredatabase(g_BaseDatos,$sql2);
	  $row2=dregistro($res2);
	  if (dnumerofilas($res2)==0) {
		$n_guia_f="000001";
		 if ($_POST['tipo']==96){
			  $n_guia_f= 'GI'."-".substr($n_guia_f,1,strlen($n_guia_f)-strlen($n_guia_f)).$n_guia_f;
		  }else{
			  if ($_POST['id']!=93){
				$n_guia_f= $serie."-".substr($n_guia_f,1,strlen($n_guia_f)-strlen($n_guia_f)).$n_guia_f;
			  }else{
				$n_guia=isset($row2[0])? intval($row2[0])+1 : 1;
				$n_guia_f=substr($n_guia_f,1,strlen($n_guia_f)-strlen($n_guia)).$n_guia;
			  }
		  }
	  }else{
		  isset($row2['guia'])? $serie_guia_actual=trim($row2['guia']) : '';
		   if ($_POST['tipo']==96){
				$n_guia=intval($row2['siguiente']);
		   }else{
				$serie_guia_actual==$serie? $n_guia=intval($row2['siguiente']) : $n_guia=intval($desde);
		   }
		  if ($_POST['tipo']==96){
			  $n_guia_f= 'GI'."-".substr($n_guia_f,1,strlen($n_guia_f)-strlen($n_guia)).$n_guia;
		  }else{
			  
			  if (intval($desde) <= intval($n_guia) &&  intval($n_guia) <= intval($hasta) ){
				  $n_guia=$n_guia;
			  }
			  else{
				$n_guia="NO VALIDA";
			  }
			  if ($_POST['id']!=93){
				$n_guia_f= $serie."-".substr($n_guia_f,1,strlen($n_guia_f)-strlen($n_guia)).$n_guia;
			  }else{
				$n_guia=isset($row2[0])? intval($row2[0])+1 : 1;
				$n_guia_f=substr($n_guia_f,1,strlen($n_guia_f)-strlen($n_guia)).$n_guia;
			  }
		  }
	  }
	  if ($_POST['id']!=93){
   ?>
		<label>N° de Guia:</label>
		<div class="input-group date">
			<div class="input-group-addon">
				<i class="fa fa-arrow-circle-right"></i>
			</div>
			<input id="guia" type="text" class="form-control pull-right" placeholder="N° de Guia" required="required" value="<?php echo $n_guia_f; ?>" <?php if($_SESSION['rol']!=4){ echo "disabled='disabled'"; }?>>
		</div>
	<?php }else{ 
		if ($_SESSION['rol']==4){ $disponible=""; } else { $disponible="disabled";  } 
	?>
		<label>N° de Guia:</label>
		<div class="input-group date">
			<div class="input-group-addon">
				<i class="fa fa-arrow-circle-right"></i>
			</div>
			<input id="guia" type="text" class="form-control pull-right" placeholder="N° de Guia" required="required" <?php echo $disponible; ?> value="<?php echo $n_guia_f; ?>">
		</div>
   <?php } } 
    if ($_POST['evento']==26){
 			$sql="SELECT id_tienda,(tx_descripcion) as tx_nombre FROM mod_tienda WHERE estatus=18 and id_empresa=".$_POST['unidad']." ORDER BY tx_nombre";
					   $res=abredatabase(g_BaseDatos,$sql);
   ?>
				<label>UBICACIÓN</label>
					<select id="origen" class="form-control select2" required="required">
					<option value="">Seleccione...</option>
					  <?php 
					   WHILE ($row=dregistro($res)){
					  
					   ?>
					  <option value="<?php echo $row['id_tienda']; ?>" ><?php echo $row['tx_nombre']; ?></option>
					   <?php } cierradatabase(); ?>
					</select>
					
	<?php } 
	// cambia las ubicaciones por selecion de Unidad de Negocio en Control de Entrada, salidas y bajas
	 if ($_POST['evento']==27){
   ?>
				<label>Ubicación(Tda - Sede/Área)</label>
					<select id="origen" class="form-control select2" required="required">
					<option value="">Seleccione...</option>
					  <?php 
					   echo $sql="SELECT id_tienda,(tx_descripcion) as tx_nombre FROM mod_tienda WHERE estatus=18 and id_empresa=".$_POST['unidad']."";
					   $res=abredatabase(g_BaseDatos,$sql);
					   WHILE ($row=dregistro($res)){
					   ?>
					  <option value="<?php echo $row['id_tienda']; ?>" ><?php echo $row['tx_nombre']; ?></option>
					   <?php } cierradatabase(); ?>
					</select>
					<script>$(".select2").select2();</script>
	<?php } 
	// script para regularizar 
	 if ($_POST['evento']==28){
		  $sql="UPDATE mod_producto a SET 
				ultimo_movimiento=(SELECT id_tipo_movimiento FROM mod_movimiento where id_producto=a.id_producto and id_estatus_movimiento=1 ORDER BY fe_movimiento DESC limit 1), 
				n_stock=(SELECT CASE WHEN id_tipo_movimiento=1 THEN 1 ELSE 0 END AS nx_cantidad  FROM mod_movimiento where id_producto=a.id_producto and id_estatus_movimiento=1 ORDER BY fe_movimiento DESC limit 1),
				id_tienda=(SELECT  CASE WHEN id_tipo_movimiento=1 THEN 1 ELSE id_tienda END AS  id_tienda  FROM mod_movimiento where id_producto=a.id_producto and id_estatus_movimiento=1 ORDER BY fe_movimiento DESC limit 1)
				WHERE
				trim(tx_serial)<>'N/T' AND id_tipo_producto NOT IN (
				SELECT a.id_tipo_producto from cfg_tipo_producto a, mod_producto b WHERE a.id_tipo_producto=b.id_tipo_producto and a.id_tipo_carga=2 GROUP BY a.id_tipo_producto)";
		  $res=abredatabase(g_BaseDatos,$sql);
		   $sql="UPDATE mod_producto a SET 
				ultimo_movimiento=(SELECT id_tipo_movimiento FROM mod_movimiento where id_producto=a.id_producto and id_estatus_movimiento=1 ORDER BY fe_movimiento DESC limit 1), 
				id_tienda=(SELECT  CASE WHEN id_tipo_movimiento=1 THEN 1 ELSE id_tienda END AS  id_tienda  FROM mod_movimiento where id_producto=a.id_producto and id_estatus_movimiento=1 ORDER BY fe_movimiento DESC limit 1)
				WHERE
				trim(tx_serial)<>'N/T' AND id_tipo_producto NOT IN (
				SELECT a.id_tipo_producto from cfg_tipo_producto a, mod_producto b WHERE a.id_tipo_producto=b.id_tipo_producto and a.id_tipo_carga=1 GROUP BY a.id_tipo_producto)";
		  $res=abredatabase(g_BaseDatos,$sql);
	?>
	<div class="callout callout-success">
			<h4>Información Importante!</h4>
			<p>Operación se realizo con exitos!</p>
		</div>
	<?php	  
	 }
	  if ($_POST['evento']==29){
	 ?>
			<label>MOTIVO</label>
						<select id="motivo" class="form-control select2"  required="required">
						<option value="">...Seleccione...</option>
						  <?php 
						   if ($_POST['tipo']==1){
								$sql="SELECT id_tipo_objeto,tx_tipo FROM vie_tbl_tipo_motivos_entrada ORDER BY tx_tipo";
								$res=abredatabase(g_BaseDatos,$sql);
						   }else{
								$sql="SELECT id_tipo_objeto,tx_tipo FROM vie_tbl_tipo_motivos_salidas ORDER BY tx_tipo";
								$res=abredatabase(g_BaseDatos,$sql);
						   }
						   WHILE ($row=dregistro($res)){
						   ?>
						  <option value="<?php echo $row['id_tipo_objeto']; ?>" ><?php echo $row['tx_tipo']; ?></option>
						   <?php } cierradatabase(); ?>					 
						</select>	
	<?php 
	  }
	  if ($_POST['evento']==30){
		  $sql2="SELECT (substring(tx_guia  from 5 for 20)) as actual_guia, (substring(tx_guia  from 0 for 4)) as guia, (substring(tx_guia  from 5 for 20)::integer+1) as siguiente FROM mod_movimiento WHERE id_tipo_movimiento=2 and id_motivo=93 ORDER BY id_movimiento DESC LIMIT 1";
		   $res2=abredatabase(g_BaseDatos,$sql2);
		   $row2=dregistro($res2);
			$n_guia_f="0000001";
			$n_guia=isset($row2[0])? intval($row2[0])+1 : 1;
			$n_guia_f=substr($n_guia_f,1,strlen($n_guia_f)-strlen($n_guia)).$n_guia;
		  $sql_inventario="SELECT id_producto, id_tabla_producto,
		  id_tipo_producto,
		  id_tipo_marca,
		  fe_ingreso,
		  tx_modelo,
		  tx_observacion,
		  id_usuario,
		  tx_serial,
		  tx_ngr,
		  id_tienda,
		  estatus, 
		  id_condicion,
		  tx_placati FROM mod_producto_temp";
		  $res_inventario=abredatabase(g_BaseDatos,$sql_inventario);
		  while ($row_inventario=dregistro($res_inventario)){
			    $sql_producto="SELECT * FROM mod_producto WHERE tx_serial='".trim($row_inventario['tx_serial'])."'";
			   $res_producto=abredatabase(g_BaseDatos,$sql_producto);
			   if (dnumerofilas($res_producto)!=0){
				   $sql_producto_update="UPDATE mod_producto SET id_tienda=".$row_inventario['id_tienda'].", tx_placati='".$row_inventario['tx_placati']."', tx_ngr='".$row_inventario['tx_ngr']."', n_stock=0, ultimo_movimiento=2, tx_modelo='".$row_inventario['tx_modelo']."', id_tipo_marca=".$row_inventario['id_tipo_marca'].", estatus=".$row_inventario['estatus'].", id_condicion=".$row_inventario['id_condicion']."  WHERE id_producto=".$row_inventario['id_tabla_producto'];
					$res_producto_update=abredatabase(g_BaseDatos,$sql_producto_update);
					if ($_POST['movimiento']=='false'){
					    $sql_movimiento="INSERT INTO mod_movimiento (id_producto, tx_guia, id_tienda, fe_movimiento, tx_serial, nx_cantidad, estatus_producto, id_condicion_producto, id_usuario, id_tipo_movimiento, tx_proposito, tx_accesorios, id_estatus_movimiento,   id_motivo, tx_ticket) (SELECT a.id_producto, '".$n_guia_f."', a.id_tienda, '".$row_inventario['fe_ingreso']."', a.tx_serial, 1, a.estatus, a.id_condicion, a.id_usuario, 2, 'Ajuste de Inventario por Inventario en Tienda', a.tx_accesorios, 1, 93, 'Ajuste de Inventario'   FROM mod_producto a  WHERE tx_serial='".$row_inventario['tx_serial']."')";
						$res_movimiento=abredatabase(g_BaseDatos,$sql_movimiento);
					}
			   }else{
				    $sql_producto_insert="INSERT INTO mod_producto (
				    id_tipo_producto,
				    id_tipo_marca,
				    fe_ingreso,
					tx_modelo,
					tx_observacion,
					id_usuario,
					tx_serial,
					tx_ngr,
					id_tienda,
					tx_placati,
					id_proveedor,
					id_unidad_medida,
					estatus,
					id_condicion,
					id_tipo_motivo,
					id_garantia,
					id_producto_padre,
					n_stock,
					ultimo_movimiento
				   )(SELECT 
				    id_tipo_producto,
					id_tipo_marca,
					fe_ingreso,
					tx_modelo,
					tx_observacion,
					id_usuario,
					tx_serial,
					tx_ngr,
					id_tienda,
					tx_placati,75,8,estatus,id_condicion,35,33,0, 0, 2 FROM mod_producto_temp WHERE tx_serial='".trim($row_inventario['tx_serial'])."')";
					$res_producto_insert=abredatabase(g_BaseDatos,$sql_producto_insert);
					if ($_POST['movimiento']=='false'){
						$sql_movimiento="INSERT INTO mod_movimiento (id_producto, tx_guia, id_tienda, fe_movimiento, tx_serial, nx_cantidad, estatus_producto, id_condicion_producto, id_usuario, id_tipo_movimiento, tx_proposito, tx_accesorios, id_estatus_movimiento,  id_motivo, tx_ticket) (SELECT a.id_producto, '".$n_guia_f."', a.id_tienda, '".$row_inventario['fe_ingreso']."', a.tx_serial, 1, a.estatus, a.id_condicion, a.id_usuario, 2, 'Ajuste de Inventario por Inventario en Tienda', a.tx_accesorios, 1, 93, 'Ajuste de Inventario'   FROM mod_producto a  WHERE tx_serial='".trim($row_inventario['tx_serial'])."')";
						$res_movimiento=abredatabase(g_BaseDatos,$sql_movimiento);
					}
			   }
		  }
		 $sql_inventario="DELETE FROM mod_producto_temp";
		  $res_inventario=abredatabase(g_BaseDatos,$sql_inventario);
		  echo "<script>setTimeout(function (){location.reload();},1000);</script>";
?>
				<div class="callout callout-success">
					<h4>Registro de Equipos Exitosos!</h4>
					<p>Ya puede verificar cada uno de los equipos que han sido cargado en la base maestro de los equipos y accesorios.</p>
				</div>
<?php
	  }
	    if ($_POST['evento']==31){
	 ?>
			<label>MOTIVO</label>
						  <?php 
						   if ($_POST['tipo']==1){
								$sql="SELECT id_tipo_objeto,tx_tipo FROM vie_tbl_tipo_motivos_entrada ORDER BY tx_tipo";
								$res=abredatabase(g_BaseDatos,$sql);
						   }else{
								$sql="SELECT id_tipo_objeto,tx_tipo FROM vie_tbl_tipo_motivos_salidas ORDER BY tx_tipo";
								$res=abredatabase(g_BaseDatos,$sql);
						   }
						   WHILE ($row=dregistro($res)){
						   ?>
						  <div> <input id="b.id_motivo" name="<?php echo $row['id_tipo_objeto']; ?>" type="checkbox"> <?php echo $row['tx_tipo']; ?></div>
						   <?php } cierradatabase(); ?>					 
						
	<?php 
	  }
   	 if ($_POST['evento']==32){
	 ?>
			<label>Tienda/Colaborador</label>
						<select id="responsable" class="form-control select2" required="required">
														<option value="">----Seleccione ---</option>
														  <?php 
															   $sql="SELECT id_tienda, CONCAT(tx_descripcion, ' ' , tx_apellido_paterno , ' ' , tx_apellido_materno , ' ' , tx_puesto , ' ' , tx_dni) as responsable FROM mod_responsable_destino WHERE id_empresa=".$_POST['unidad']." ORDER BY responsable" ;
															   $res=abredatabase(g_BaseDatos,$sql);
															   WHILE ($row=dregistro($res)){
														   ?>
																<option value="<?php echo $row['id_tienda']; ?>" >
																	<?php echo $row['responsable']; ?>
																</option>
															<?php } cierradatabase(); ?>
													</select>
						<script>$(".select2").select2();</script>
	<?php 
	  }
?>

