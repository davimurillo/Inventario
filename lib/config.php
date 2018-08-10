<?PHP
	
	// definición de variables globales -----//
	
	define("g_dir", $dir."../");
	
	//-------------------------------------------------------------------------
	
	
	// definición de variables para el servidor de Base de Datos -----//
	
	define("g_TipoBaseDatos", "pgsql");
	define("g_User", "postgres");
	define("g_Pass", "usbw");
	define("g_Port", "5432");
	define("g_ServidorBaseDatos","localhost");
	define("g_BaseDatos", "INVENTARIO");
	
	//-------------------------------------------------------------------------
	
	
	
	// --- FUNCIONES/
	
		/*include_once('func_php/lib.string.php'); 
		include_once('func_php/lib.numeros.php'); 
		include_once('func_php/lib.fechas.php'); 
		include_once('func_php/lib.objetos.php'); 
		include_once('func_php/lib.debug.php'); 
		include_once('func_php/lib.request.php'); 		
		include_once('func_php/lib.secuenciadores.php'); 	
		include_once('func_php/lib.paginacion.php'); 	*/		
		include_once(g_dir.'lib/php/lib.bd.php'); 	
	/*	include_once('func_php/lib.validaciones.php'); 
		include_once('func_php/lib.ficheros.php'); 
		include_once('func_php/lib.ftp.php'); 
		require_once('ldap/adLDAP.php');*/

	//-------------------------------------------------------------------------

	//-------------------------------------------------------------------------
	// --- WEB SERVICES
		

	//-------------------------------------------------------------------------
	
	//-------------------------------------------------------------------------
	// --- GRAFICOS



	//-------------------------------------------------------------------------

	//-------------------------------------------------------------------------
	// --- RULES

	     include_once(g_dir.'lib/php/lib.funciones.php'); 		
		/*include_once('reglas/lib.accesos.php'); 	*/
	//-------------------------------------------------------------------------

?>
