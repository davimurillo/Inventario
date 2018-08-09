<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>APICES|Control de Inventarios</title>
  <link href="../img/logos/apices.png" rel="shortcut icon" type="image/x-icon" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <?php include('libreriaCSS.php');  ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  
<?php 

require_once('common.php'); checkUser(); 

	date_default_timezone_set($_SESSION['zona_horario']);
?>
 <!-- Head app -->

  <?php require('cabecera.php'); ?> 
  
  <!-- Left side column. contains the logo and sidebar -->
  
  <?php  $formulario=4; include('barra_izquierda.php'); ?> 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registro
        <small>Equipos y/o Accesorios</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Registro</a></li>
        <li class="active">Equipos y/o Accesorios</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="box">
            <div class="box-header">
              <h3 class="box-title">Maestro de Inventario</h3>
            </div>
            <div class="box-body">
		<div align="left" class="col-xs-6">
		   <form action="mod_registro_equipos_accesorios.php" method="GET">			 <strong>Buscar Equipo:</strong>
			<input id="buscar_equipo_general" name="buscar_equipo_general" class="form-control" type="text" placeholder="Valor de Busqueda" value=""  >
		  </form>
		</div>
		
		<div align="right" class="col-xs-6">
		<div  class="btn-group">
			 
			<button id="carga_masiva" type="button" class="btn btn-success">F4 - Cargar Masiva</button> 
		</div>
		</div>
		<div class="col-xs-12" style="margin-top:20px">
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

	$sql="SELECT id_producto, tx_serial, tx_descripcion, tx_ngr, tx_tipo_producto, tx_unidad_medida, tx_marca, tx_modelo,  tx_estatus, ('<i class=\"fa fa-list\"></i>') as icono, ('<i class=\"fa fa-info-circle\"></i>') as icono_info, ('<i class=\"fa fa-barcode\"></i>') as icono_code_bar, tx_placati, (SELECT n_stock FROM mod_producto WHERE id_producto=vie_tbl_equipos.id_producto LIMIT 1) AS n_stock, (SELECT tx_abreviatura FROM mod_empresa WHERE id_empresa=vie_tbl_equipos.id_unidad_negocio) AS un FROM vie_tbl_equipos ";
	
	if (isset($_GET['buscar_equipo_general'])){
		$id_producto=$_GET['buscar_equipo_general']>0? intval($_GET['buscar_equipo_general']) : 0 ;
		$sql.=" WHERE id_producto = ".$id_producto." OR upper(tx_serial) LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%' OR upper(tx_ngr) LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%' OR upper(tx_placati) LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%' OR  upper(tx_descripcion) LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%' OR upper(tx_tipo_producto) LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%' OR upper(tx_marca) LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%' OR  upper(tx_modelo) LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%' OR  upper(tx_estatus) LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%'";
	}

##  *** set needed options
  $debug_mode = false;
  $messaging = true;
  $unique_prefix = "f_";  
  $dgrid = new DataGrid($debug_mode, $messaging, $unique_prefix, DATAGRID_DIR);
##  *** set data source with needed options
  $default_order_field = "id_producto";
  $default_order_type = "DESC";
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
  "delete"  =>array("view"=>1, "edit"=>0, "type"=>"image")
);
$dgrid->setModes($modes);



 $multirow_option = true;
 $dgrid->allowMultirowOperations($multirow_option);
 $multirow_operations = array(
    "delete"  => array("view"=>true),
    "details" => array("view"=>false),
	"edit" => array("view"=>true),
	"Reemplazar" => 
     array("view"=>true, 
           "flag_name"=>"my_flag_name", 
           "flag_value"=>"my_flag_value", 
           "tooltip"=>"Reemplazar", 
           "image"=>"image.gif")
 );
 $dgrid->setMultirowOperations($multirow_operations); 

$http_get_vars = array("reemplazar","buscar_equipo_general");
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
	"N° de Serial"     =>array("table"=>"vie_tbl_equipos", "field"=>"tx_serial", "source"=>"self","operator"=>false, "default_operator"=>"%like%", "type"=>"textbox", "autocomplete"=>false,"case_sensitive"=>true,  "comparison_type"=>"string"),
	"N° de Placa"     =>array("table"=>"vie_tbl_equipos", "field"=>"tx_ngr", "source"=>"self","operator"=>false, "default_operator"=>"%like%", "type"=>"textbox", "autocomplete"=>false,"case_sensitive"=>true,  "comparison_type"=>"string"),
	"Descripción"     =>array("table"=>"vie_tbl_equipos", "field"=>"tx_descripcion", "source"=>"self","operator"=>true, "default_operator"=>"%like%", "type"=>"textbox", "autocomplete"=>true, "case_sensitive"=>false,  "comparison_type"=>"string"),
	"Tipo de Equipo"     =>array("table"=>"vie_tbl_equipos", "field"=>"tx_tipo_producto", "source"=>"self","operator"=>true, "default_operator"=>"%like%", "type"=>"textbox", "autocomplete"=>true, "case_sensitive"=>false,  "comparison_type"=>"string")
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
	
	"id_producto"  =>array("header"=>"ID","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	"un"  =>array("header"=>"UN","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_serial"  =>array("header"=>"N° SERIAL","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_ngr"  =>array("header"=>"N° PLACA","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_placati"  =>array("header"=>"N° PLACA TI","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	//"tx_descripcion"  =>array("header"=>"EQUIPO","header_align"=>"center","type"=>"label", "width"=>"25%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_tipo_producto"  =>array("header"=>"TIPO","header_align"=>"center","type"=>"label", "width"=>"20%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_marca"  =>array("header"=>"MARCA","header_align"=>"center","type"=>"label", "width"=>"21%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_modelo"  =>array("header"=>"MODELO","header_align"=>"center","type"=>"label", "width"=>"15%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_estatus"  =>array("header"=>"ESTATUS","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"n_stock"  =>array("header"=>"STOCK","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"center",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
		
	//"icono"=>array("header"=>"Acces.","header_align"=>"center", "type"=>"link",    "align"=>"center", "width"=>"5%", "wrap"=>"wrap", "text_length"=>"-1", "tooltip"=>"true", "tooltip_type"=>"floating", "case"=>"normal", "summarize"=>"false", "sort_type"=>"numeric", "sort_by"=>"", "visible"=>"true", "on_js_event"=>"", "field_key"=>"id_producto","field_data"=>"icono", "rel"=>"", "title"=>"Agregar Accesorios", "target"=>"", "href"=>"javascript:accesorios({0});"),
	
	"icono_info"=>array("header"=>"Info","header_align"=>"center", "type"=>"link",    "align"=>"center", "width"=>"5%", "wrap"=>"wrap", "text_length"=>"-1", "tooltip"=>"true", "tooltip_type"=>"floating", "case"=>"normal", "summarize"=>"false", "sort_type"=>"numeric", "sort_by"=>"", "visible"=>"true", "on_js_event"=>"", "field_key"=>"id_producto","field_data"=>"icono_info", "rel"=>"", "title"=>"Agregar Accesorios", "target"=>"", "href"=>"javascript:abrir_historial({0});"),
	
	"icono_code_bar"=>array("header"=>"Code Bar","header_align"=>"center", "type"=>"link",    "align"=>"center", "width"=>"5%", "wrap"=>"wrap", "text_length"=>"-1", "tooltip"=>"true", "tooltip_type"=>"floating", "case"=>"normal", "summarize"=>"false", "sort_type"=>"numeric", "sort_by"=>"", "visible"=>"true", "on_js_event"=>"", "field_key"=>"id_producto","field_data"=>"icono_code_bar", "rel"=>"", "title"=>"Agregar Accesorios", "target"=>"", "href"=>"javascript:codigo_barra({0});")
	
	
  );
  
  $dgrid->setColumnsInViewMode($vm_colimns);
## +---------------------------------------------------------------------------+
## | 7. Add/Edit/Details Mode settings:                                        | 
## +---------------------------------------------------------------------------+
##  ***  set settings for edit/details mode

	//*****ARREGLO PARA TIPO DE EQUIPOS******//
	$tema_array_sql = "SELECT tx_nombre_tipo, id_tipo_producto FROM cfg_tipo_producto ORDER BY tx_nombre_tipo";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"tipoequipos_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///

//*****ARREGLO PARA UNIDAD DE NEGOCIO******//
	$tema_array_sql = "SELECT tx_abreviatura, id_empresa FROM mod_empresa ORDER BY tx_abreviatura";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"un_array",g_BaseDatos);
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
	
	//*****ARREGLO PARA ESTATUS EQUIPO******//
	$tema_array_sql = "SELECT tx_tipo, id_tipo_objeto FROM vie_tbl_tipo_garantia";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"garantia_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
	//*****ARREGLO ******//
	$tema_array_sql = "SELECT tx_tipo, id_tipo_objeto FROM vie_tbl_condicion_equipo";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"condicio_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
	//*****ARREGLO ******//
	$tema_array_sql = "SELECT tx_tipo, id_tipo_objeto FROM vie_tbl_tipo_motivos_entrada ORDER BY tx_tipo";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"tipo_motivo_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
	//*****ARREGLO ******//
	$tema_array_sql = "SELECT tx_nombre, id_proveedor FROM mod_proveedor ORDER BY tx_nombre";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"proveedor_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
	//*****ARREGLO ******//
	$tema_array_sql = "SELECT tx_marca, id_marca FROM cfg_tipo_marcas ORDER BY tx_marca";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"marca_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
 
  
  $table_name = "mod_producto";
  $primary_key = "id_producto";
  $condition = "";
  $dgrid->setTableEdit($table_name, $primary_key, $condition);
  $dgrid->setAutoColumnsInEditMode(false);
  if ($_SESSION['rol']!=4){
   $em_columns = array(
	"id_unidad_negocio" =>array("header"=>"UN",  "type"=>"enum",   "source"=>$un_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),

 	"id_proveedor" =>array("header"=>"Proveedor",  "type"=>"enum",  "default"=>"1",  "source"=>$proveedor_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"id_tipo_motivo" =>array("header"=>"Tipo de Motivo",  "type"=>"enum",  "default"=>"1",  "source"=>$tipo_motivo_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"tx_nu_motivo" =>array("header"=>"N° de Motivo", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_nu_cotizacion" =>array("header"=>"N° de Cotización", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_guia_remision" =>array("header"=>"N° de Guía de Remisión", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"id_tipo_producto" =>array("header"=>"Tipo de Equipo",  "type"=>"enum",  "default"=>"1",  "source"=>$tipoequipos_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"tx_serial" =>array("header"=>"N° Serial", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	
	"tx_descripcion" =>array("header"=>"Descripción", "type"=>"textarea", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	//"tx_marca" =>array("header"=>"Marca", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"id_tipo_marca" =>array("header"=>"Marca",  "type"=>"enum",  "default"=>"1",  "source"=>$marca_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"tx_modelo" =>array("header"=>"Modelo", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_ngr" =>array("header"=>"Código NGR", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_placati" =>array("header"=>"Código PLACA TI", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	
	"id_unidad_medida" =>array("header"=>"Unidad de Medida",  "type"=>"enum",  "default"=>"1",  "source"=>$unidad_medida_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"fe_ingreso"  =>array("header"=>"Fecha de Ingreso", "type"=>"date",       "req_type"=>"rt", "width"=>"187px", "title"=>"", "readonly"=>"false", "maxlength"=>"-1", "default"=>"", "unique"=>"false", "unique_condition"=>"", "visible"=>"true", "on_js_event"=>"", "calendar_type"=>"floating"),
	
	
	"id_garantia" =>array("header"=>"Tiempo de Garantia",  "type"=>"enum",  "default"=>"1",  "source"=>$garantia_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>"", "on_js_event"=>"Onchange=\"javascript:sumarDias(this.value);\""),
	
	"fe_vencimiento"  =>array("header"=>"Fecha de Vencimiento", "type"=>"date",       "req_type"=>"rt", "width"=>"187px", "title"=>"", "readonly"=>"false", "maxlength"=>"-1", "default"=>"", "unique"=>"false", "unique_condition"=>"", "visible"=>"true", "on_js_event"=>"", "calendar_type"=>"floating"),
	
	"costo" =>array("header"=>"Costo USD.", "type"=>"textbox", "width"=>"80px", "req_type"=>"rny", "title"=>"", "unique"=>false),
	
	"id_condicion" =>array("header"=>"Condición",  "type"=>"enum",  "default"=>"1",  "source"=>$condicio_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"estatus" =>array("header"=>"Estatus",  "type"=>"enum",  "default"=>"1",  "source"=>$estatus_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"tx_proposito" =>array("header"=>"Proposito", "type"=>"textbox", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	"tx_accesorios" =>array("header"=>"Accesorios", "type"=>"textarea", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	"tx_observacion" =>array("header"=>"Observaciones", "type"=>"textarea", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	"id_usuario" =>array("header"=>"",       "type"=>"hidden",    "req_type"=>"st", "default"=>$_SESSION['id_usuario'], "visible"=>"false", "unique"=>false),
	
	"id_producto_padre" =>array("header"=>"",       "type"=>"hidden",    "req_type"=>"st", "default"=>"0", "visible"=>"false", "unique"=>false)
  								
												
  );
  }else{
	  $em_columns = array(
"id_unidad_negocio" =>array("header"=>"UN",  "type"=>"enum",   "source"=>$un_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
 	"id_proveedor" =>array("header"=>"Proveedor",  "type"=>"enum",  "default"=>"1",  "source"=>$proveedor_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"id_tipo_motivo" =>array("header"=>"Tipo de Motivo",  "type"=>"enum",  "default"=>"1",  "source"=>$tipo_motivo_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"tx_nu_motivo" =>array("header"=>"N° de Motivo", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_nu_cotizacion" =>array("header"=>"N° de Cotización", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_guia_remision" =>array("header"=>"N° de Guía de Remisión", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"id_tipo_producto" =>array("header"=>"Tipo de Equipo",  "type"=>"enum",  "default"=>"1",  "source"=>$tipoequipos_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"tx_serial" =>array("header"=>"N° Serial", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	
	"tx_descripcion" =>array("header"=>"Descripción", "type"=>"textarea", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	//"tx_marca" =>array("header"=>"Marca", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"id_tipo_marca" =>array("header"=>"Marca",  "type"=>"enum",  "default"=>"1",  "source"=>$marca_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"tx_modelo" =>array("header"=>"Modelo", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_ngr" =>array("header"=>"Código NGR", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_placati" =>array("header"=>"Código PLACA TI", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"id_unidad_medida" =>array("header"=>"Unidad de Medida",  "type"=>"enum",  "default"=>"1",  "source"=>$unidad_medida_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"fe_ingreso"  =>array("header"=>"Fecha de Ingreso", "type"=>"date",       "req_type"=>"rt", "width"=>"187px", "title"=>"", "readonly"=>"false", "maxlength"=>"-1", "default"=>"", "unique"=>"false", "unique_condition"=>"", "visible"=>"true", "on_js_event"=>"", "calendar_type"=>"floating"),
	
	
	"id_garantia" =>array("header"=>"Tiempo de Garantia",  "type"=>"enum",  "default"=>"1",  "source"=>$garantia_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>"", "on_js_event"=>"Onchange=\"javascript:sumarDias(this.value);\""),
	
	"fe_vencimiento"  =>array("header"=>"Fecha de Vencimiento", "type"=>"date",       "req_type"=>"rt", "width"=>"187px", "title"=>"", "readonly"=>"false", "maxlength"=>"-1", "default"=>"", "unique"=>"false", "unique_condition"=>"", "visible"=>"true", "on_js_event"=>"", "calendar_type"=>"floating"),
	
	"costo" =>array("header"=>"Costo USD.", "type"=>"textbox", "width"=>"80px", "req_type"=>"rny", "title"=>"", "unique"=>false),
	
	"id_condicion" =>array("header"=>"Condición",  "type"=>"enum",  "default"=>"1",  "source"=>$condicio_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"estatus" =>array("header"=>"Estatus",  "type"=>"enum",  "default"=>"1",  "source"=>$estatus_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"tx_proposito" =>array("header"=>"Proposito", "type"=>"textbox", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	"tx_accesorios" =>array("header"=>"Accesorios", "type"=>"textarea", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	"tx_observacion" =>array("header"=>"Observaciones", "type"=>"textarea", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	"n_stock" =>array("header"=>"Stock", "type"=>"textbox", "width"=>"100%", "req_type"=>"rny", "title"=>"", "unique"=>false),
	

	"id_tienda" =>array("header"=>"Tienda", "type"=>"textbox", "width"=>"100%", "req_type"=>"rny", "title"=>"", "unique"=>false),

	"ultimo_movimiento" =>array("header"=>"Ultimo Movimiento", "type"=>"textbox", "width"=>"100%", "req_type"=>"rny", "title"=>"", "unique"=>false),
	
	"id_usuario" =>array("header"=>"",       "type"=>"hidden",    "req_type"=>"st", "default"=>$_SESSION['id_usuario'], "visible"=>"false", "unique"=>false),
	
	"id_producto_padre" =>array("header"=>"",       "type"=>"hidden",    "req_type"=>"st", "default"=>"0", "visible"=>"false", "unique"=>false)
	);
  }
$dgrid->setColumnsInEditMode($em_columns);
  

  
##  *** set auto-genereted eName_1.FieldName > 'a' AND TableName_1.FieldName < 'c'"
##  *** Ex.: "on_js_event"=>"onclick='alert(\"Yes!!!\");'"

  
## +---------------------------------------------------------------------------+
## | 8. Bind the DataGrid:                                                     | 
## +---------------------------------------------------------------------------+
##  *** set debug mode & messaging options
	
	
    $dgrid->bind();        
    ob_end_flush();
 
    if(isset($_GET['f_mode']) && 
		($_GET['f_mode'] == "Reemplazar") ) 
		{ 
			// PREPARE THE IDs selected FOR A QUERY 
			$ids = explode("-", $_GET['f_rid']); 
				$i_ids = count($ids); 
				$idstatement = array(); 
				for ($i=0; $i < $i_ids; $i++) 
				{ 
				$idstatement[] = $ids[$i]; 
				} 
				$ids = implode("," , $idstatement); 
			
			echo "<script>window.open('mod_reemplazar_datos_equipos.php?id=".$ids."','Modulos','height=340,width=620');</script>";
		} 
	if($mode == "update" && $rid == "-1" && $dgrid->IsOperationCompleted()){
			$last_insert_id = $dgrid->GetCurrentId();
			 
			//$sql="INSERT INTO mod_movimiento (id_motivo, id_proveedor, id_tienda, fe_movimiento, tx_guia, id_producto, tx_serial, nx_cantidad, nx_costo, estatus_producto, id_condicion_producto, tx_accesorios, id_tipo_movimiento, id_estatus_movimiento,id_usuario,fe_creacion,tx_responsable) (SELECT id_tipo_motivo, id_proveedor, 1, fe_ingreso,tx_guia_remision,id_producto,tx_serial,1,costo,5,id_condicion,tx_accesorios,1,1,id_usuario,fe_creacion, upper('".$_SESSION['nombre']."') as usuario  FROM mod_producto WHERE id_producto=".$last_insert_id.")";
			//$res=abredatabase(g_BaseDatos,$sql);
			
			$sql="UPDATE mod_producto SET n_stock=0, ultimo_movimiento=0, id_tienda=1 WHERE id_producto=".$last_insert_id;
			$res=abredatabase(g_BaseDatos,$sql);
		}
?>
</div>
</div>
		</div>
<!-- Ventana para incluir accesorios -->
	<div class="modal fade" tabindex="-1" id="myModal_accesorios" role="dialog" style="color:#999; ">
	  <div class="modal-dialog">
		<div class="modal-content" >
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h2 class="modal-title"><span class="fa fa-list" style="margin-right:10px"></span>Accesorios del Equipo</h2>
		  </div>
		  <div class="modal-body" >
				<div class="callout callout-info">
                <h4>Información Importante!</h4>

                <p>Recuerde agregar los accesorios que el mayor detalle posible para poder identificar todas las partes que van en conjunto con el equipo.</p>
              </div>
				<iframe id="accesorios_producto" src="" height="450px" width="100%" allowtransparency="1" frameborder="0"></iframe>
			
		  </div>
		  <div class="modal-footer"  style="text-align:center">
				  
			
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	 <!-- Ventana para historial del equipo -->
	<div class="modal fade" tabindex="-1" id="myModal_importar" role="dialog" style="color:#999; ">
	  <div class="modal-dialog" style="width:80%">
		<div class="modal-content" >
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h2 class="modal-title"><span class="fa fa-upload" style="margin-right:10px"></span>Historial de Movimientos del Equipo</h2>
		  </div>
		  <div class="modal-body" id="historial" >
				
				
			
		  </div>
		  <div class="modal-footer"  style="text-align:center">
				  
			
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	
	<!-- Ventana para codigo de Barra del equipo -->
	<div class="modal fade" tabindex="-1" id="myModal_barra" role="dialog" style="color:#999; ">
	  <div class="modal-dialog">
		<div class="modal-content" >
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h2 class="modal-title"><span class="fa fa-upload" style="margin-right:10px"></span>Codigo de Barra del Equipo</h2>
		  </div>
		  <div class="modal-body" id="codigo_barra_equipo" >
				<iframe src="" name="code_barra" id="id_code_barra" width="100%" height="400px" frameborder=0 >
				</iframe>
			
		  </div>
		  <div class="modal-footer"  style="text-align:center">
				  
			
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<!-- Ventana para carga masiva -->
	<div class="modal fade" tabindex="-1" id="myModal_carga_masiva" role="dialog" style="color:#999; ">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" >
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h2 class="modal-title">Carga Masiva </h2>
				</div>
				<div class="modal-body" >
					<div id="carga_masiva_registro" class="row">
						
					</div>
				</div>
				<div class="modal-footer"  style="text-align:center">
					<div align="center" class="col-xs-12" style="margin-top:10px">
						<button id=boton_aceptar type="button" class="btn btn-success" data-dismiss="modal" >Salir</button>
					</div>
				</div>
			</div>
			
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
	
	
	<!-- Ventana para incluir reemplazar -->
	<div class="modal fade" tabindex="-1" id="myModal_reemplazar" role="dialog" style="color:#999; ">
	  <div class="modal-dialog">
		<div class="modal-content" >
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h2 class="modal-title"><span class="fa fa-list" style="margin-right:10px"></span>Reemplazar del Equipo</h2>
		  </div>
		  <div class="modal-body" >
				
				
			
		  </div>
		  <div class="modal-footer"  style="text-align:center">
				  
			
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<script>

	function accesorios(id) {
			$('#myModal_accesorios').modal('show');
			url="mod_accesorios.php?equipo="+id;
			$('#accesorios_producto').attr('src',url);
		}
		function reemplazar(id) {
			$('#myModal_reemplazar').modal('show');
			
		}
	
		function sumarDias(dias){
			dias=parseInt($("#rtyid_garantia option[value='"+dias+"']").text());
			dias=dias*30;
			var fecha='';
			var fecha=$('#rtyfe_ingreso').val();
			let d=new Date(fecha);
			let dia=d.getDate();
			d.setDate(dias);
			if (parseInt(d.getFullYear())>0){
				$('#rtyfe_vencimiento').val(d.getFullYear()+'-'+(d.getMonth()+2)+'-'+dia);
			}else{
				$('#rtyfe_vencimiento').val($('#rtyfe_ingreso').val());
			}
			return false;
		}
	</script>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2017.</strong> All rights
    reserved.
  </footer>

  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<?php include('libreriaJS.php'); ?>

<script>
$("#rtyid_proveedor").select2();

$("#rtyid_tipo_motivo").select2();

$("#rtyid_tipo_producto").select2();

$("#rtyid_tipo_marca").select2();

$("#rtyid_unidad_medida").select2();

$("#rtyid_garantia").select2();

$("#rtyid_condicion").select2();

$("#rtyestatus").select2();


$("BODY").keydown(function(event) {
        
		if(event.which == 113) { //F2
          $('#myModal_importar').modal('show');  
        } 
       
		if(event.which == 115) { //F4
         
			$('#carga_masiva_registro').load('mod_registro_equipos_accesorios_modal.php');
			$('#myModal_carga_masiva').modal('show'); 
	  
        } 
		
		
		
 });
 $("#carga_masiva").click(function() {
	 $('#carga_masiva_registro').load('mod_registro_equipos_accesorios_modal.php');
	 $('#myModal_carga_masiva').modal('show'); 
 });
 

 //Date picker
    $('#fe_entrada_equipo').datepicker({
      autoclose: true,
	  format: 'dd/mm/yyyy'
    });
	
 
 //$( "#agregar" ).click(function() {

function agregar(){ 
		 if ($('#cantidad').val() >0){
			
		  
			$('#registro_productos').load('mod_eventos.php',{cantidad:$('#cantidad').val(),descripcion:$('#descripcion').val(), tipo:$('#tipo').val(),costo:$('#costo').val(), ngr:$('#ngr').val(),  marca:$('#marca').val(), modelo:$('#modelo').val(),  garantia:$('#garantia').val(), tiempo_garantia:$("#garantia option:selected").text(), condicion_equipo:$('#condicion_equipo').val(),accesorios:$('#accesorios').val(), proveedor:$('#proveedor_equipo').val(), motivo:$('#motivo').val(), codigo_motivo:$('#codigo_motivo').val(),cotizacion:$('#cotizacion').val(),fe_entrada:$('#fe_entrada_equipo').val(),guia:$('#guia').val(), un:$('#id_un').val(), evento:8});
			
			
		}else{
			alert("Debe Colocar una cantidad mayor que CERO en la entrada del producto!");
		}
		 
	}

</script>
<script>
function abrir_historial(id){
	$('#historial').load('mod_historial_producto.php',{'id_producto':id});
	$('#myModal_importar').modal('show'); 
}	

function codigo_barra(id){
	$('#id_code_barra').attr('src','mod_mostrar_code_barra_equipo.php?id_producto='+id);  
	
	$('#myModal_barra').modal('show'); 
}
	
</script>
<style>

</style>
<?php require_once('libreriaSCRIPT.php'); ?>
</body>
</html>







