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
<body >

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
  </style>
	<div id="contenedor_carga">
		<div id="carga"></div>
	</div>
<div >
  
<?php 

require_once('common.php'); checkUser(); 

	date_default_timezone_set($_SESSION['zona_horario']);
?>
 <!-- Head app -->

 

  <!-- Content Wrapper. Contains page content -->
  <div >
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Editar
        <small>Equipos y/o Accesorios</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Editar</a></li>
        <li class="active">Equipos y/o Accesorios</li>
      </ol>
    </section>

    
		<div class="col-xs-12" style="margin-top:20px">
<?php

$mode = (isset($_GET['f_mode'])) ? $_GET['f_mode'] : ""; 
$rid = (isset($_GET['f_rid'])) ? $_GET['f_rid'] : ""; 

if($mode == "update" && $rid > "1" ){
			echo "<script>window.parent.$('#myModal_editar_producto').modal('hide');</script>";
		}
if($mode == "cancel" && $rid > "1" ){
			echo "<script>window.parent.$('#myModal_editar_producto').modal('hide');</script>";
}
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

	$sql="SELECT id_producto, tx_serial, tx_descripcion, tx_ngr, tx_tipo_producto, tx_unidad_medida, tx_marca, tx_modelo,  tx_estatus, ('<i class=\"fa fa-list\"></i>') as icono, ('<i class=\"fa fa-info-circle\"></i>') as icono_info, ('<i class=\"fa fa-barcode\"></i>') as icono_code_bar FROM vie_tbl_equipos ";
	
	if (isset($_GET['buscar_equipo_general'])){
		$sql.=" WHERE  tx_ngr LIKE '%".$_GET['buscar_equipo_general']."%' OR tx_serial LIKE '%".$_GET['buscar_equipo_general']."%' OR  tx_descripcion LIKE '%".$_GET['buscar_equipo_general']."%' OR tx_tipo_producto LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%' OR tx_marca LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%' OR  tx_modelo LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%'";
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
	"edit" => array("view"=>true)
 );
 $dgrid->setMultirowOperations($multirow_operations); 

$http_get_vars = array("buscar_equipo_general");
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
	
	"tx_serial"  =>array("header"=>"N° SERIAL","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_ngr"  =>array("header"=>"N° PLACA","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	//"tx_descripcion"  =>array("header"=>"EQUIPO","header_align"=>"center","type"=>"label", "width"=>"30%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_tipo_producto"  =>array("header"=>"TIPO","header_align"=>"center","type"=>"label", "width"=>"25%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_marca"  =>array("header"=>"MARCA","header_align"=>"center","type"=>"label", "width"=>"23%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_modelo"  =>array("header"=>"MODELO","header_align"=>"center","type"=>"label", "width"=>"23%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_estatus"  =>array("header"=>"ESTATUS","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
		
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
	$tema_array_sql = "SELECT tx_nombre_tipo, id_tipo_producto FROM cfg_tipo_producto";
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
	$tema_array_sql = "SELECT tx_tipo, id_tipo_objeto FROM vie_tbl_tipo_motivos_entrada";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"tipo_motivo_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
	//*****ARREGLO ******//
	$tema_array_sql = "SELECT tx_nombre, id_proveedor FROM mod_proveedor ORDER BY tx_nombre";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"proveedor_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
 
  
  $table_name = "mod_producto";
  $primary_key = "id_producto";
  $condition = "";
  $dgrid->setTableEdit($table_name, $primary_key, $condition);
  $dgrid->setAutoColumnsInEditMode(false);
   $em_columns = array(
 	"id_proveedor" =>array("header"=>"Proveedor",  "type"=>"enum",  "default"=>"1",  "source"=>$proveedor_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"id_tipo_motivo" =>array("header"=>"Tipo de Compra",  "type"=>"enum",  "default"=>"1",  "source"=>$tipo_motivo_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"tx_nu_motivo" =>array("header"=>"N° de Compra", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_nu_cotizacion" =>array("header"=>"N° de Cotización", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_guia_remision" =>array("header"=>"N° de Guía de Remisión", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"id_tipo_producto" =>array("header"=>"Tipo de Equipo",  "type"=>"enum",  "default"=>"1",  "source"=>$tipoequipos_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
	"tx_serial" =>array("header"=>"N° Serial", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	
	"tx_descripcion" =>array("header"=>"Descripción", "type"=>"textarea", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_marca" =>array("header"=>"Marca", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_modelo" =>array("header"=>"Modelo", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	"tx_ngr" =>array("header"=>"Código NGR", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	
	
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
</div>
		</div>


	
	
	<script>
	
	
		
		function sumarDias(dias){
			dias=parseInt($("#rtyid_garantia option[value='"+dias+"']").text());
			dias=dias*30;
			var fecha='';
			fecha=$('#rtyfe_ingreso').val();
			
			var d=new Date(fecha);
			d.setDate(dias);
			
			if (parseInt(d.getFullYear())>0){
				$('#rtyfe_vencimiento').val(d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDay());
			}else{
				alert('Seleccione una fecha de Ingreso');
			}
			return false;
		}
	</script>

    </section>
    <!-- /.content -->
  </div>

</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

<!-- Sparkline -->
<script src="plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>


<?php require_once('libreriaSCRIPT.php'); ?>

</body>
</html>







