<?php require_once('common.php'); checkUser(); ?>
 

  
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

	$sql="SELECT id_producto, tx_codigo, tx_descripcion, tx_tipo_producto, tx_unidad_medida, tx_marca, tx_modelo, nx_max, nx_min, tx_estatus FROM vie_tbl_accesorios WHERE id_producto_padre=".$_GET['equipo'];

##  *** set needed options
  $debug_mode = false;
  $messaging = true;
  $unique_prefix = "f_";  
  $dgrid = new DataGrid($debug_mode, $messaging, $unique_prefix, DATAGRID_DIR);
##  *** set data source with needed options
  $default_order_field = "tx_descripcion";
  $default_order_type = "ASC";
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
  "add"     =>array("view"=>1, "edit"=>0, "type"=>"image"),
  "edit"    =>array("view"=>true, "edit"=>true, "type"=>"image"),
  "cancel"  =>array("view"=>true, "edit"=>true, "type"=>"image"),
  "details" =>array("view"=>false, "edit"=>false, "type"=>"image"),
  "delete"  =>array("view"=>0, "edit"=>0, "type"=>"image")
);
$dgrid->setModes($modes);



 $multirow_option = false;
 $dgrid->allowMultirowOperations($multirow_option);
 $multirow_operations = array(
    "delete"  => array("view"=>false),
    "details" => array("view"=>false),
	"edit" => array("view"=>true)
 );
 $dgrid->setMultirowOperations($multirow_operations); 

$http_get_vars = array("equipo");
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
$rows_numeration = false;
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
$pages_array = array("5"=>"5","10"=>"10", "25"=>"25", "50"=>"50", "100"=>"100", "250"=>"250", "500"=>"500", "1000"=>"1000", "2000"=>"2000");
$default_page_size = 10;
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
 
  $filtering_fields = array( );
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
	
	"tx_codigo"  =>array("header"=>"Código","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_descripcion"  =>array("header"=>"Equipo","header_align"=>"center","type"=>"label", "width"=>"70%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_tipo_producto"  =>array("header"=>"Tipo","header_align"=>"center","type"=>"label", "width"=>"15%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	

	"tx_estatus"  =>array("header"=>"Estatus","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	
	
  );
  
  $dgrid->setColumnsInViewMode($vm_colimns);
## +---------------------------------------------------------------------------+
## | 7. Add/Edit/Details Mode settings:                                        | 
## +---------------------------------------------------------------------------+
##  ***  set settings for edit/details mode

	//*****ARREGLO PARA TIPO DE EQUIPOS******//
	$tema_array_sql = "SELECT tx_tipo, id_tipo_objeto FROM vie_tbl_tipo_equipo";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"tipoequipos_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
	//*****ARREGLO PARA UNIDAD DE MEDIDA******//
	$tema_array_sql = "SELECT tx_tipo, id_tipo_objeto FROM vie_tbl_unidad_medida";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"unidad_medida_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
	//*****ARREGLO PARA ESTATUS EQUIPO******//
	$tema_array_sql = "SELECT tx_tipo, id_tipo_objeto FROM vie_tbl_estatus_equipo";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"estatus_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
 
  
  $table_name = "mod_producto";
  $primary_key = "id_producto";
  $condition = "";
  $dgrid->setTableEdit($table_name, $primary_key, $condition);
  $dgrid->setAutoColumnsInEditMode(false);
   $em_columns = array(
 	
	"id_tipo_producto" =>array("header"=>"Tipo de Accesorio",  "type"=>"enum",  "default"=>"1",  "source"=>$tipoequipos_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"tx_codigo" =>array("header"=>"N° Código", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_descripcion" =>array("header"=>"Descripción", "type"=>"textarea", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_marca" =>array("header"=>"Marca", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_modelo" =>array("header"=>"Modelo", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_ngr" =>array("header"=>"Código NGR", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"id_unidad_medida" =>array("header"=>"Unidad de Medida",  "type"=>"enum",  "default"=>"1",  "source"=>$unidad_medida_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"nx_min" =>array("header"=>"Minimo de Existencia", "type"=>"textbox", "width"=>"50px", "req_type"=>"rny", "title"=>"", "unique"=>false),
	
	"nx_max" =>array("header"=>"Maximo de Existencia", "type"=>"textbox", "width"=>"50px", "req_type"=>"rny", "title"=>"", "unique"=>false),
	
	"costo" =>array("header"=>"Costo", "type"=>"textbox", "width"=>"80px", "req_type"=>"rny", "title"=>"", "unique"=>false),
	
	"estatus" =>array("header"=>"Estatus",  "type"=>"enum",  "default"=>"1",  "source"=>$estatus_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"tx_observacion" =>array("header"=>"Observaciones", "type"=>"textarea", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	"id_usuario" =>array("header"=>"",       "type"=>"hidden",    "req_type"=>"st", "default"=>$_SESSION['id_usuario'], "visible"=>"false", "unique"=>false),
	
	"id_producto_padre" =>array("header"=>"",       "type"=>"hidden",    "req_type"=>"st", "default"=>$_GET['equipo'], "visible"=>"false", "unique"=>false)
	
												
												
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

</body>
</html>







