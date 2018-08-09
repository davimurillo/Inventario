<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>APICES|Control de Inventarios</title>
  <link href="../img/logos/apices.png" rel="shortcut icon" type="image/x-icon" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  
<?php 

require_once('common.php'); checkUser(); 

?>
 <!-- Head app -->

  <?php require('cabecera.php'); ?> 
  
  <!-- Left side column. contains the logo and sidebar -->
  
  <?php $formulario=23; include('barra_izquierda.php'); ?> 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registro
        <small> Regularización de Movimientos</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Regularización de Movimientos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div align="left" class="col-xs-6">
		   <form action="mod_ver_movimiento.php" method="GET">			 
			<strong>Buscar Movimientos:</strong>
			<input id="buscar_proveedor" name="buscar_proveedor" class="form-control" type="text" placeholder="Valor de Busqueda" value=""  >
		  </form>
		  
		</div>
		<div class="col-xs-12" id="actualizar" style="margin-top:20px "></div>
      <div class="col_lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
<?php

$mode = (isset($_GET['f_mode'])) ? $_GET['f_mode'] : ""; 
$rid = (isset($_GET['f_rid'])) ? $_GET['f_rid'] : ""; 
################################################################################   
## +---------------------------------------------------------------------------+
## | 1. Creating & Calling:                                                    | 
## +---------------------------------------------------------------------------+
##  *** only relative (virtual) path (to the current document)
  define ("DATAGRID_DIR", g_dir."lib/datagrid/");
  define ("PEAR_DIR", g_dir."lib/datagrid/pear/");
  
  require_once(DATAGRID_DIR.'datagrid.class.php');
  require_once(PEAR_DIR.'PEAR.php');
  require_once(PEAR_DIR.'DB.php');

##  *** creating variables that we need for database connection 
  $DB_BASE=g_TipoBaseDatos;
  $DB_USER=g_User;            
  $DB_PASS=g_Pass;           
  $DB_HOST=g_ServidorBaseDatos;       
  $DB_NAME=g_BaseDatos;  
     

ob_start();
  $db_conn = DB::factory($DB_BASE); 
  $db_conn -> connect(DB::parseDSN($DB_BASE.'://'.$DB_USER.':'.$DB_PASS.'@'.$DB_HOST.'/'.$DB_NAME));


##  *** put a primary key on the first place 

	$sql="SELECT id_movimiento,nx_cantidad, tx_ticket, id_producto, id_tienda, CONCAT('Serial:' || tx_serial || '<br>' || 'Marca:' || tx_marca || '<br>' || 'Guía:' || tx_guia  || '<br>' ||  'Destino:' || tx_destino) as excel  , tx_modelo, fe_movimiento, id_tipo_movimiento, tx_guia,  CASE WHEN id_tipo_movimiento=1 THEN 'ENTRADA' ELSE 'SALIDA' END as movimiento, ('<span class=\"fa fa-retweet\"></span>') as edit, CASE WHEN (id_producto>0 AND id_tienda>0 AND ( (SELECT id_tipo_objeto FROM cfg_tipo_objeto where id_tipo_objeto IN (mod_movimiento.id_motivo) LIMIT 1) > 0 AND id_responsable_destino >0 ) ) THEN 'REGULARIZADO' ELSE '<span style=\" color: red \">SIN REGULARIZAR<span>' END AS casos, (SELECT tx_nombre_apellido FROM cfg_usuario WHERE id_usuario=mod_movimiento.id_usuario) as usuario FROM mod_movimiento ";
	
	if (isset($_GET['buscar_proveedor'])){
		$tienda_b=$_GET['buscar_proveedor']>0? intval($_GET['buscar_proveedor']) : 0 ;
		$id_producto=$_GET['buscar_proveedor']>0? intval($_GET['buscar_proveedor']) : 0 ;
		$sql.=" WHERE id_producto=".$id_producto." OR id_tienda =".$tienda_b." OR upper(tx_serial) LIKE ('".strtoupper($_GET['buscar_proveedor'])."%') OR upper(tx_modelo) LIKE ('%".strtoupper($_GET['buscar_proveedor'])."%') OR upper(tx_marca) LIKE ('%".strtoupper($_GET['buscar_proveedor'])."%') OR upper(tx_tipo) LIKE ('%".strtoupper($_GET['buscar_proveedor'])."%') OR tx_modelo LIKE ('%".strtoupper($_GET['buscar_proveedor'])."%') OR upper(tx_guia) LIKE ('%".strtoupper($_GET['buscar_proveedor'])."%') OR upper(tx_ticket) LIKE ('%".strtoupper($_GET['buscar_proveedor'])."%') ";
	}
	
	
	
##  *** set needed options
  $debug_mode = false;
  $messaging = true;
  $unique_prefix = "f_";  
  $dgrid = new DataGrid($debug_mode, $messaging, $unique_prefix, DATAGRID_DIR);
##  *** set data source with needed options
  $default_order_field = "fe_movimiento, fe_actualizada";
  $default_order_type = "DESC, DESC";
  $dgrid->dataSource($db_conn, $sql, $default_order_field, $default_order_type);	    

## +---------------------------------------------------------------------------+
## | 2. General Settings:                                                      | 
## +---------------------------------------------------------------------------+
##  *** set encoding and collation (default: utf8/utf8_unicode_ci)

$postback_method = "GET";
$dgrid->SetPostBackMethod($postback_method);

$dgrid->firstFieldFocusAllowed = "true";

 $dg_encoding = "utf8";
 $dg_collation = "utf8_unicode_ci";
 $dgrid->setEncoding($dg_encoding, $dg_collation);



$modes = array(
  "add"     =>array("view"=>0, "edit"=>0, "type"=>"image"),
  "edit"    =>array("view"=>true, "edit"=>true, "type"=>"image"),
  "cancel"  =>array("view"=>true, "edit"=>true, "type"=>"image"),
  "details" =>array("view"=>false, "edit"=>false, "type"=>"image"),
  "delete"  =>array("view"=>1, "edit"=>0, "type"=>"image")
);
$dgrid->setModes($modes);



 $multirow_option = true;
 $dgrid->allowMultirowOperations($multirow_option);
 $multirow_operations = array(
    "delete"  => array("view"=>true),
    "details" => array("view"=>false),
	"edit" => array("view"=>true)
 );
 $dgrid->setMultirowOperations($multirow_operations); 

$http_get_vars = array("buscar_proveedor");
$dgrid->SetHttpGetVars($http_get_vars);

##  *** set interface language (default - English)
##  *** (en) - English     (de) - German     (se) Swedish     (hr) - Bosnian/Croatian
##  *** (hu) - Hungarian   (es) - Espanol    (ca) - Catala    (fr) - Francais
##  *** (nl) - Netherlands/"Vlaams"(Flemish) (it) - Italiano  (pl) - Polish
##  *** (ch) - Chinese     (sr) - Serbian
 $dg_language = "es";  
 $dgrid->setInterfaceLang($dg_language);

#
##  *** set layouts: "0" - tabular(horizontal) - default, "1" - columnar(vertical), "2" - customized
#
  $layouts = array("view"=>"0", "edit"=>"1", "details"=>"1", "filter"=>"1");
#
  $dgrid->SetLayouts($layouts);
  
 $css_class = "x-blue";
 if($css_class == "") $css_class = "default"; 
## "embedded" - use embedded classes, "file" - link external css file
 $css_type = "embedded"; 
 $dgrid->setCssClass($css_class, $css_type);
 
## +---------------------------------------------------------------------------+
## | 3. Printing & Exporting Settings:                                         | 
## +---------------------------------------------------------------------------+
##  *** set printing option: true(default) or false 
 $printing_option = false;
 $dgrid->allowPrinting($printing_option);
##  *** set exporting option: true(default) or false 
 $exporting_option = false;
 $dgrid->allowExporting($exporting_option);
##

##
    ## +---------------------------------------------------------------------------+
    ## | 4. Sorting & Paging Settings:                                             | 
    ## +---------------------------------------------------------------------------+
    ##  *** set sorting option: true(default) or false 

$paging_option = true;
$rows_numeration = true;
$numeration_sign = "N #";
$dgrid->allowPaging($paging_option, $rows_numeration, $numeration_sign);
$bottom_paging = array(
         "results"=>true, "results_align"=>"left", 
         "pages"=>true, "pages_align"=>"center", 
         "page_size"=>true, "page_size_align"=>"right");
$top_paging = array(
         "results"=>true, "results_align"=>"left",
         "pages"=>true, "pages_align"=>"center",
         "page_size"=>true, "page_size_align"=>"right");
$pages_array = array("5"=>"5","10"=>"10", "25"=>"25", "50"=>"50", "100"=>"100", "250"=>"250", "500"=>"500", "1000"=>"1000");
$default_page_size = 100;
$paging_arrows = array("first"=>"|<<", "previous"=>"<<", "next"=>">>", "last"=>">>|");
$dgrid->setPagingSettings($bottom_paging, $top_paging, $pages_array, $default_page_size);



##
## +---------------------------------------------------------------------------+
## | 5. Filter Settings:                                                       | 
## +---------------------------------------------------------------------------+
##  *** set filtering option: true or false(default)
 $filtering_option = false;
 $dgrid->allowFiltering($filtering_option);
##  *** set aditional filtering settings
 
  $filtering_fields = array(
	"Nombre "     =>array("table"=>"mod_proveedor", "field"=>"tx_nombre", "source"=>"self","operator"=>false, "default_operator"=>"%like%", "type"=>"textbox", "autocomplete"=>false,"case_sensitive"=>FALSE,  "comparison_type"=>"string")
  );
  $dgrid->setFieldsFiltering($filtering_fields);
##
## 


## +---------------------------------------------------------------------------+
## | 6. View Mode Settings:                                                    | 
## +---------------------------------------------------------------------------+
##  *** set columns in view mode
   //$dgrid->setAutoColumnsInViewMode(true);  
  $vm_table_properties = array("width"=>"100%","sortable"=>false);
  $dgrid->SetViewModeTableProperties($vm_table_properties); 
		
 
 	$vm_colimns = array(
	
	"id_movimiento"  =>array("header"=>"ID","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"id_producto"  =>array("header"=>"id_producto","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"id_tienda"  =>array("header"=>"Destino","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_ticket"  =>array("header"=>"Ticket","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_guia"  =>array("header"=>"Guía","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"fe_movimiento"  =>array("header"=>"Fecha","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"movimiento"  =>array("header"=>"Tipo Movimiento","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	"nx_cantidad"  =>array("header"=>"Cantidad","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"casos"  =>array("header"=>"Estado del Movimiento","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),

	"usuario"  =>array("header"=>"Usuario","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"edit"=>array("header"=>"Act. stock","header_align"=>"center", "type"=>"link",	"align"=>"center", "width"=>"5%", "wrap"=>"wrap", "text_length"=>"-1", "tooltip"=>"true", 	"tooltip_type"=>"floating", "case"=>"normal", "summarize"=>"false", "sort_type"=>"numeric", "sort_by"=>"", 				"visible"=>"true", "on_js_event"=>"", "field_key"=>"id_producto", "field_key_1"=>"id_tipo_movimiento", "field_key_2"=>"id_tienda", "field_key_3"=>"nx_cantidad",  "field_data"=>"contacto", "rel"=>"", "title"=>"Actualizar stock", "target"=>"", "href"=>"javascript:actualizar({0},{1},{2},{3});"),
	
  );
  
  $dgrid->setColumnsInViewMode($vm_colimns);
## +---------------------------------------------------------------------------+
## | 7. Add/Edit/Details Mode settings:                                        | 
## +---------------------------------------------------------------------------+
##  ***  set settings for edit/details mode
 
 	//*****ARREGLO ******//
	$tema_array_sql = "SELECT tx_tipo, id_tipo_objeto FROM cfg_tipo_objeto WHERE tx_objeto IN ('tipo_motivo_salidas','tipo_motivo_entrada')";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"motivo_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
	//*****ARREGLO ******//
	$tema_array_sql = "SELECT tx_descripcion, id_tienda FROM mod_tienda ORDER BY tx_descripcion";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"tienda_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
	//*****ARREGLO ******//
	$tema_array_sql = "SELECT  (tx_representacion || ' ' || tx_responsable) as responsable, id_responsable FROM mod_responsable";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"traslado_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
	//*****ARREGLO ******//
	$tema_array_sql = "SELECT CONCAT(tx_descripcion || ' ' || tx_apellido_paterno || ' ' || tx_apellido_materno || ' ' || tx_puesto || ' ' || tx_dni ) as tx_descripcion, id_tienda FROM mod_responsable_destino ORDER BY tx_descripcion";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"responsable_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
	//*****ARREGLO ******//
	$tema_array_sql = "SELECT replace(CONCAT(tx_descripcion || ' - ' || (select tx_marca FROM cfg_tipo_marcas where id_marca=a.id_tipo_marca) || ' - ' || tx_modelo || ' - ' || tx_serial), '\"','')  AS descripcion, id_producto FROM mod_producto a ORDER BY descripcion";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"producto_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
	$estatus=array(""=>"--Seleccione--","0"=>"Inactiva","1"=>"Activa","3"=>"Prestamo");
	
  $table_name = "mod_movimiento";
  $primary_key = "id_movimiento";
  $condition = "";
  $dgrid->setTableEdit($table_name, $primary_key, $condition);
  $dgrid->setAutoColumnsInEditMode(false);
   $em_columns = array(
 	
	"id_motivo" =>array("header"=>"Motivo",  "type"=>"enum",  "default"=>"1",  "source"=>$motivo_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"id_tienda" =>array("header"=>"Ubicación",  "type"=>"enum",  "default"=>"1",  "source"=>$tienda_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"id_responsable_destino" =>array("header"=>"Responsable Destino",  "type"=>"enum",  "default"=>"1",  "source"=>$responsable_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"id_producto" =>array("header"=>"Producto",  "type"=>"enum",  "default"=>"1",  "source"=>$producto_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"tx_ticket" =>array("header"=>"Ticket",  "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	// "tx_responsable" =>array("header"=>"Reponsable",  "type"=>"textbox", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	"id_responsable_enviado" =>array("header"=>"Responsable Traslado",  "type"=>"enum",  "default"=>"1",  "source"=>$traslado_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"nx_cantidad" =>array("header"=>"Stock del Movimiento",  "type"=>"textbox", "width"=>"100%", "req_type"=>"rny", "title"=>"", "unique"=>false),
	
	"fe_movimiento"  =>array("header"=>"Fecha de Movimiento", "type"=>"date",       "req_type"=>"rt", "width"=>"187px", "title"=>"", "readonly"=>"false", "maxlength"=>"-1", "default"=>"", "unique"=>"false", "unique_condition"=>"", "visible"=>"true", "on_js_event"=>"", "calendar_type"=>"floating"),
	
	"id_estatus_movimiento" =>array("header"=>"Estatus",  "type"=>"enum",  "default"=>"1",  "source"=>$estatus, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	
	"id_usuario" =>array("header"=>"",       "type"=>"hidden",    "req_type"=>"st", "default"=>$_SESSION['id_usuario'], "visible"=>"false", "unique"=>false)
	
												
												
  );
$dgrid->setColumnsInEditMode($em_columns);
  

  
##  *** set auto-genereted eName_1.FieldName > 'a' AND TableName_1.FieldName < 'c'"
##  *** Ex.: "on_js_event"=>"onclick='alert(\"Yes!!!\");'"

  
## +---------------------------------------------------------------------------+
## | 8. Bind the DataGrid:                                                     | 
## +---------------------------------------------------------------------------+
##  *** set debug mode & messaging options
	
	
    $dgrid->bind();        
    ob_end_flush();
 

?>
</div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.3.8
    </div>
    <strong>Copyright &copy; 2017.</strong> All rights
    reserved.
  </footer>

</div>
<!-- ./wrapper -->

<?php include('libreriaJS.php'); ?>
</body>
</html>
<script>
 $("[name*='rtyid_tienda']").select2();
 
 $("[name*='rtyid_producto']").select2();
 
 $("[name*='rtyid_responsable_destino']").select2();
 

function actualizar(id_producto, movimiento, tienda, cantidad){
		$('#actualizar').load("mod_eventos.php", {id:id_producto, tipo:movimiento, tienda:tienda, cantidad:cantidad,  evento:23});
}
</script>
<?php require_once('libreriaSCRIPT.php'); ?>





