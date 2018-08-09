<?php
function abredatabase($DataBase,$consultaSQL)
{	
	global $enlace;
	if (g_TipoBaseDatos=='mysql'):
	
		$enlace = mysql_connect(g_ServidorBaseDatos, g_User, g_Pass) or die("No pudo conectarse : " . mysql_error());
		mysql_select_db($DataBase) or die("Error con proveedor de base de datos.");
		$resultado = mysql_query($consultaSQL) or die("La consulta fall&oacute;: " . mysql_error());
		return $resultado;
	elseif (g_TipoBaseDatos=='pgsql'):
		$enlace = pg_connect('dbname='. $DataBase . ' host=' . g_ServidorBaseDatos . ' port=5432 user=' . g_User . ' password=' .g_Pass ) or die ("Error por favor revisar archivo de configuración del sistema");
        $resultado =  pg_query($enlace,$consultaSQL) or die("Error con proveedor de base de datos." ); 
		return $resultado;
	endif;
}

function conectardatabase($DataBase)
{	
	global $enlace;
	if (g_TipoBaseDatos=='mysql'):
		$enlace = mysql_connect(g_ServidorBaseDatos, g_User, g_Pass) or die("No pudo conectarse : " . mysql_error());
		mysql_select_db($DataBase) or die("Error con proveedor de base de datos.");
		return $enlace;
	elseif (g_TipoBaseDatos=='pgsql'):
		$enlace = pg_connect('dbname='. $DataBase . ' host=' . g_ServidorBaseDatos . ' port=5432 user=' . g_User . ' password=' .g_Pass );
		return $enlace;
	endif;
}

function dregistro($resu){
	if (g_TipoBaseDatos=='mysql'):
		return mysql_fetch_array($resu);
	elseif (g_TipoBaseDatos=='pgsql'):
		return pg_fetch_array($resu);
	endif;
}

function dtipodato($campo,$camp){
	if (g_TipoBaseDatos=='mysql'):
		return mysql_field_type($campo,$camp);
	elseif (g_TipoBaseDatos=='pgsql'):
		return pg_field_type($campo,$camp);
	endif;
}

function dnumerocolumna($resu){
	if (g_TipoBaseDatos=='mysql'):
		return mysql_num_fields($resu);
	elseif (g_TipoBaseDatos=='pgsql'):
		return pg_num_fields($resu);
	endif;
}

function dnumerofilas($resu){
	if (g_TipoBaseDatos=='mysql'):
		return mysql_num_rows($resu);
	elseif (g_TipoBaseDatos=='pgsql'):
		return pg_num_rows($resu);
	endif;
}

 
function dnombrecampo($resu,$camp){
	if (g_TipoBaseDatos=='mysql'):
		return mysql_field_name($resu,$camp);
	elseif (g_TipoBaseDatos=='pgsql'):
		return pg_field_name($resu,$camp);
	endif;
}

function cierradatabase(){
	global $enlace;
	if (g_TipoBaseDatos=='mysql'):
		mysql_close($enlace);
	elseif (g_TipoBaseDatos=='pgsql'):
		pg_close($enlace);
	endif;
}

function Cant_Registro($cant){
	if (g_TipoBaseDatos=='mysql'):
		return mysql_num_rows($cant);
	elseif (g_TipoBaseDatos=='pgsql'):
		return pg_num_rows($cant);
	endif;
}

function CompruebaExiste($StrSQL)
{	
	define("Retorno", "");
	$res=abredatabase(g_BaseDatos,$StrSQL);
	$rsdatos=dregistro($res);
	if ($rsdatos!=""): $Retorno = 1; else: $Retorno = 0; endif;
	cierradatabase();
	return $Retorno;
}


?>