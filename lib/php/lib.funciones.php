<?php

function buscar_especial($idespecial)
{
	$sql = "SELECT nombre_especial FROM especial WHERE id_especial=$idespecial";
	$res=abredatabase(g_BaseDatos,$sql);		
	$row=mysql_fetch_array($res);		
	$nombre_especial = $row['nombre_especial'];
	return $nombre_especial;
}
function buscar_opcion_especial($id_opcion)
{
	$sql = "SELECT opcion_nombre FROM opcion_especial WHERE id_opcion=$id_opcion";
	$res=abredatabase(g_BaseDatos,$sql);		
	$row=mysql_fetch_array($res);		
	$opcion_nombre = $row['opcion_nombre'];
	return $opcion_nombre;
}
function buscar_valor_especial($id_valor)
{
	$sql = "SELECT nombre_valor FROM valor_especial WHERE id_valor=$id_valor";
	$res=abredatabase(g_BaseDatos,$sql);		
	$row=mysql_fetch_array($res);		
	$nombre_valor = $row['nombre_valor'];
	return $nombre_valor;
}

function buscar_acronimo($idunidad)
{
	$sql = "SELECT nb_acronimo FROM unidades WHERE id_unidad=$idunidad";
	$res=abredatabase(g_BaseDatos_acceso,$sql);		
	$row=mysql_fetch_array($res);		
	$nb_acronimo = $row['nb_acronimo'];
	return $nb_acronimo;
}
function buscar_direccion_fisica($id_poblacion)
{
	$sql = "SELECT direccion,id_ciudad,ab_estado FROM poblacion WHERE id_poblacion=$id_poblacion";
	$res=abredatabase(g_BaseDatos,$sql);		
	$row=mysql_fetch_array($res);		
	$direccion = trim($row['direccion']);
	$id_ciudad = trim($row['id_ciudad']);
	$ab_estado = trim($row['ab_estado']);	
	if (($direccion == '') || ($id_ciudad == '') || ($ab_estado == ''))
	{
		return false;
	}
	else
	{
		return true;
	}
}
function buscar_correo($id_poblacion)
{
	$sql = "SELECT correo_electronico FROM poblacion WHERE id_poblacion=$id_poblacion";
	$res=abredatabase(g_BaseDatos,$sql);		
	$row=mysql_fetch_array($res);		
	$correo_electronico = trim($row['correo_electronico']);
	if ($correo_electronico == '')
	{
		return false;
	}
	else
	{
		return true;
	}
}
function buscar_email($usuario)
{
	$sql = "SELECT email FROM usuario WHERE userid='".$usuario."'";
	$res=abredatabase(g_BaseDatos_acceso,$sql);		
	$row=mysql_fetch_array($res);		
	$email = $row['email'];
	return $email;
}
function buscar_nombre($usuario)
{
	$sql = "SELECT nombre FROM usuario WHERE userid='".$usuario."'";
	$res=abredatabase(g_BaseDatos_acceso,$sql);		
	$row=mysql_fetch_array($res);		
	$nombre = $row['nombre'];
	return $nombre;
}
function buscar_nombre_personad($persona_destino)
{
	$sql = "SELECT nb_persona,tx_correo_electronico,nu_fax FROM personas_destino WHERE id_persona_destino='".$persona_destino."'";
	$res=abredatabase(g_BaseDatos,$sql);		
	$row=mysql_fetch_array($res);		
	$datos = $row['nb_persona'].",".$row['tx_correo_electronico'].",".$row['nu_fax'];
	return $datos;
}
function buscar_codigo_comunicacion($idcomunicacion)
{
	$sql = "SELECT CONCAT((SELECT nb_acronimo 
							FROM acceso.unidades 
							WHERE comunicaciones.id_unidad_emisora = acceso.unidades.id_unidad),
							'-',
							comunicaciones.id_tipo_documento,
							'-',
							comunicaciones.nu_secuencial,
							'-',
							SUBSTRING(DATE_FORMAT(comunicaciones.fe_comunicacion,'%Y'),3)) AS nu_documento
			FROM comunicaciones
			WHERE id_comunicacion = $idcomunicacion";
	$res=abredatabase(g_BaseDatos,$sql);		
	$row=mysql_fetch_array($res);		
	$nu_documento = $row['nu_documento'];
	return $nu_documento;
}


function crearArregloDataGrid($sql,$nb_arreglo,$baseDatos)
{
	$res=abredatabase($baseDatos,$sql);
	$array_str = "";
	$i="";
	$array_str.= "$".$nb_arreglo." = array(";	
	$array_str.= "\"\"=>\"-Seleccione-\","; 
	$cant = dnumerofilas($res);
	while ($row=$rs=dregistro($res))
	{
		if ($i!=$cant) 
		{	
			$array_str.= "\"$row[1]\"=>\"$row[0]\","; 
		}
		else
		{	
			$array_str.= "\"$row[1]\"=>\"$row[0]\","; 
		}
	}
	$array_str.= ");";	
	return $array_str;
}


function crearArregloDataGrid2($sql,$nb_arreglo,$baseDatos)
{
	$res=abredatabase($baseDatos,$sql);
	$array_str = "";
	$i="";
	$array_str .= "$".$nb_arreglo." = array(";	
	//$array_str .= "\"\"=>\"-Seleccione-\","; 
	$cant = dnumerofilas($res);
	while ($row=$rs=dregistro($res))
	{
		if ($i!=$cant) 
		{	
			$array_str .= "\"$row[1]\"=>\"$row[0]\","; 
		}
		else
		{	
			$array_str .= "\"$row[1]\"=>\"$row[0]\","; 
		}
	}
	$array_str .= ");";	
	return $array_str;
}

function crearArregloDataGrid3($sql,$nb_arreglo,$baseDatos)
{
	$res=abredatabase($baseDatos,$sql);
	$array_str = "";
	$i="";
	$array_str.= "$".$nb_arreglo." = array(";	
	$array_str.= "\"0\"=>\"-Seleccione-\","; 
	$cant = dnumerofilas($res);
	while ($row=$rs=dregistro($res))
	{
		if ($i!=$cant) 
		{	
			$array_str.= "\"$row[1]\"=>\"$row[0]\","; 
		}
		else
		{	
			$array_str.= "\"$row[1]\"=>\"$row[0]\","; 
		}
	}
	$array_str.= ");";	
	return $array_str;
}

function comunicacion_bandeja($submenu)
{
	switch($submenu){
   	case "Pendientes":
      	$SQL = "SELECT COUNT(*) AS total FROM comunicaciones WHERE tx_estatus <> 'CERRADA' and id_emisor ='".$_SESSION['userName']."'";
		$res=abredatabase(g_BaseDatos,$SQL);		
		$row=mysql_fetch_array($res);		
		$total = $row['total'];
      	break;
   	case "Borradores":
      	$SQL = "SELECT COUNT(*) AS total FROM comunicaciones WHERE tx_estatus = 'BORRADOR' and id_emisor ='".$_SESSION['userName']."'";
		$res=abredatabase(g_BaseDatos,$SQL);		
		$row=mysql_fetch_array($res);		
		$total = $row['total'];
      	break;
   	case "Por Revisar":
      	$SQL = "SELECT COUNT(*) AS total FROM comunicaciones WHERE tx_estatus = 'POR REVISAR' and id_revisor ='".$_SESSION['userName']."'";
		$res=abredatabase(g_BaseDatos,$SQL);		
		$row=mysql_fetch_array($res);		
		$total = $row['total'];
      	break;
   	case "Revisadas":
      	$SQL = "SELECT COUNT(*) AS total FROM comunicaciones WHERE tx_estatus = 'REVISADA' and id_emisor ='".$_SESSION['userName']."'";
		$res=abredatabase(g_BaseDatos,$SQL);		
		$row=mysql_fetch_array($res);		
		$total = $row['total'];
      	break;
   	case "Por Firmar":
      	$SQL = "SELECT COUNT(*) AS total FROM comunicaciones WHERE tx_estatus = 'POR FIRMAR' and id_firmante ='".$_SESSION['userName']."'";
		$res=abredatabase(g_BaseDatos,$SQL);		
		$row=mysql_fetch_array($res);		
		$total = $row['total'];
      	break;
   	case "Cerradas":
      	$SQL = "SELECT COUNT(*) AS total FROM comunicaciones WHERE tx_estatus = 'CERRADA' and id_emisor ='".$_SESSION['userName']."'";
		$res=abredatabase(g_BaseDatos,$SQL);		
		$row=mysql_fetch_array($res);		
		$total = $row['total'];
      	break;
   	case "Todas":
      	$SQL = "SELECT COUNT(*) AS total FROM comunicaciones WHERE id_emisor ='".$_SESSION['userName']."' OR id_firmante ='".$_SESSION['userName']."' OR id_revisor ='".$_SESSION['userName']."'";
		$res=abredatabase(g_BaseDatos,$SQL);		
		$row=mysql_fetch_array($res);		
		$total = $row['total'];
      	break;		
	}
	return $total;	
	cierradatabase();
}


	function buscar_registro_poblacion($id) {
		$sql = "SELECT poblacion.*,ciudades.ab_estados FROM poblacion,ciudades WHERE id_poblacion='".$id."' and ciudades.id_ciudad = poblacion.id_ciudad";
		$res=abredatabase(g_BaseDatos,$sql);		
		$row=mysql_fetch_array($res);		
		$arreglo_poblacion = $row['primer_nombre'].",".$row['segundo_nombre'].",".$row['primer_apellido'].",".$row['segundo_apellido'].",".$row['correo_electronico'].",".$row['telefono'].",".$row['direccion'].",".$row['id_ciudad'].",".$row['codigo_zip'].",".$row['ab_estados'].",".$row['id_poblacion'];
		cierradatabase();
		return $arreglo_poblacion;
	}



	function registrar_carga_masiva_poblacion_2($primer_nombre,$segundo_nombre,$primer_apellido,$segundo_apellido,$cedula,$sexo,$email,$fecha_nac,$ciudad,$estado,$zip,$telefono,$direccion,$titulo,$lugar_trabajo,$departamento,$titulo_trabajo,$conyugue,$id_unidad,$user_name,$fax)
	{
		$cant_registro = 0;
		if (empty($primer_nombre) or empty($primer_apellido))// or empty($direccion)) 		
		{
			$mensaje = "<li>Nombre, Apellido y Direccion son datos imprescindibles</li>";
		}
		else
		{
			if (!empty($email)) 
			{
				$sql = "SELECT correo_electronico FROM poblacion WHERE correo_electronico='".$email."'";	
				$res=abredatabase(g_BaseDatos,$sql);		
				$row=mysql_fetch_array($res);		
				$correo = $row['correo_electronico'];
			}		
			$sql = "SELECT primer_nombre,primer_apellido,id_unidad FROM poblacion WHERE primer_nombre='".str_replace(" ","",$primer_nombre)."' and primer_apellido='".str_replace(" ","",$primer_apellido)."'";
			$res=abredatabase(g_BaseDatos,$sql);		
			$row=mysql_fetch_array($res);					
			$nombre = $row['primer_nombre'];
			$apellido = $row['primer_apellido'];
			$nombres_unidad = $row['id_unidad'];

				//$cant_reg = $resultado;//Cant_Registro($res);
			if (!empty($correo)) 
			{
				//$mensaje = "<li>Else ".$cant_reg."</li>";
				$mensaje = "<li>Correo electronico ya existe</li>";
				//$mensaje = "<li>".$cant_reg."</li>";
			}
			elseif (!empty($nombre))
			{
				$mensaje = $nombre." ".$apellido." ya existe Registrado en la Unidad: ".$nombres_unidad;			
			}
			else
			{
			
			
				if ((buscar_estado($estado) == "0") and (!empty($direccion)))
				{
					$mensaje = "<li>Estado no existe.</li>";
				}
				elseif (buscar_ciudad($estado,$ciudad) == "0" and (!empty($direccion)))
				{
					$mensaje = "<li>Ciudad no existe.</li>";
				}
				else
				{
					$ciudad = buscar_ciudad($estado,$ciudad);
		      		$SQL = "INSERT INTO poblacion (primer_nombre,segundo_nombre,primer_apellido,segundo_apellido,cedula,sexo,correo_electronico,fecha_nac,telefono,direccion,id_ciudad,codigo_zip,titulo,lugar_trabajo,departamento,titulo_trabajo,conyugue,ab_estado,id_unidad,user_name,fax) 
									VALUES ('$primer_nombre','$segundo_nombre','$primer_apellido','$segundo_apellido','$cedula','$sexo','$email','$fecha_nac','$telefono','$direccion','$ciudad','$zip','$titulo','$lugar_trabajo','$departamento','$titulo_trabajo','$conyugue','$estado','$id_unidad','$user_name','$fax')";
					abredatabase(g_BaseDatos,$SQL);
					cierradatabase();		
					//$mensaje = "Cedula: ".$cedula." registrada con exito";
				}
			}
		}
		//$mensaje .= str_replace(" ","",$primer_nombre.$primer_apellido);
		return $mensaje;	
	}

	function buscar_margen_avery($avery)
	{
		$sql = "SELECT * FROM avery WHERE id_avery='".$avery."'";
		$res=abredatabase(g_BaseDatos,$sql);		
		$row=mysql_fetch_array($res);		
		$margen_top = $row['margen_top'];
		cierradatabase();
		if (!isset($margen_top)) {
			$margen_top = 10;
		}
		return intval($margen_top);
	}
	
	function buscar_estado($estado)
	{
		$sql = "SELECT * FROM estados WHERE ab_estados='".$estado."'";
		$res=abredatabase(g_BaseDatos,$sql);		
		$row=mysql_fetch_array($res);		
		$id_estado = $row['ab_estados'];
		cierradatabase();
		if (!isset($id_estado)) {
			$id_estado = "0";
		}
		return $id_estado;
	}
	
	function buscar_ciudad($id_estado,$ciudad)
	{
		$sql = "SELECT * FROM ciudades WHERE ab_estados='".$id_estado."' and nb_ciudades = '".$ciudad."'";
		$res=abredatabase(g_BaseDatos,$sql);		
		$row=mysql_fetch_array($res);		
		$id_ciudad = $row['id_ciudad'];
		cierradatabase();
		if (!isset($id_ciudad)) {
			$id_ciudad = "0";
		}
		return $id_ciudad;
	}

function crear_guia_navegacion($hijo){
  $select_hijo="SELECT id_maestro,id_nivel_maestro,nombre,tipo_arbol FROM maestro a,tipo_arbol b WHERE a.id_tipo_arbol=b.id_tipo_arbol and a.id_maestro=".$hijo."  ORDER BY id_maestro ASC";
  $resultado_hijo=abredatabase(g_BaseDatos,$select_hijo);
  $row2=dregistro($resultado_hijo);
  $id_padre=$row2[0];
  $hijo_padre=$row2[1];
  $padre_nombre=$row2[2];
  $guia="'0' => 'Inicio'";
  if ($row2[1]!=0) 
  {
	  $select_hijo_padre="SELECT id_maestro,id_nivel_maestro,nombre,tipo_arbol FROM maestro a,tipo_arbol b WHERE a.id_tipo_arbol=b.id_tipo_arbol and a.id_maestro<=".$id_padre."  ORDER BY id_maestro ASC";
	  $resultado_hijo_padre=abredatabase(g_BaseDatos,$select_hijo_padre);
	  while($registros=dregistro($resultado_hijo_padre))
	  {
		  if ($hijo_padre!=0){
			$select_hijos="SELECT id_maestro,id_nivel_maestro,nombre,tipo_arbol FROM maestro a,tipo_arbol b WHERE a.id_tipo_arbol=b.id_tipo_arbol and a.id_maestro=".$hijo_padre." ORDER BY id_maestro";
			$resultados_hijos=abredatabase(g_BaseDatos,$select_hijos);
			$row3=dregistro($resultados_hijos);
			$guia.=",'".$row3['0']."' => '".$row3['0']."'";
			$hijo_padre=$row3['1'];
		  }
	  }
		  
  }
					  
  $g="$"."navegacion"."=array(".$guia.");";
  eval($g);
  ksort($navegacion);
  foreach ($navegacion as $key => $val) {
	  echo "$val >\n ";
  }
  echo $padre_nombre;
}

?>
