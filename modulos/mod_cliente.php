<!-- 
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  APICES INVENTARIO version 1.0.2					       #
##  Developed by:  	CH TECHNOLOGY (info@ch.net.pe)   (+51) 480 0874        #
##  License:       	GNU LGPL v.3                                           #
##  Site:			chtechnology.com.pe                            #
##  Copyleft:     	CH TECHNOLOGY 2017 - 2018. All rights reserved.	       #
##  Last changed:  	25 DE FEBRER0 DE 2018                                  #
##  AUTOR:  		DAVI MURILLO		                               #
##                                                                             #
################################################################################
-->
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

?>
 <!-- Head app -->

  <?php require('cabecera.php'); ?> 
  
  <!-- Left side column. contains the logo and sidebar -->
  
  <?php $formulario=8; include('barra_izquierda.php'); ?> 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registro
        <small>Ubicación (TDA - Sede / Área)</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Ubicación (TDA - Sede / Área)</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div align="left" class="col-xs-6">
		   <form action="mod_cliente.php" method="GET">			 
		   <strong>Buscar Origen / Destino:</strong>
			<input id="buscar_clientes" name="buscar_clientes" class="form-control" type="text" placeholder="Valor de Busqueda" value=""  >
		  </form>
		</div>
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

	$sql="SELECT id_tienda, (SELECT tx_abreviatura FROM mod_empresa where mod_tienda.id_empresa=id_empresa) as tx_marca, CONCAT(tx_descripcion||' '|| tx_apellido_paterno||' '||tx_apellido_materno||' '||tx_puesto||' '||tx_dni) AS tx_descripcion2, tx_descripcion, tx_direccion, tx_telefono, tx_url, tx_correo, ('<i class=\"fa fa-info\"></i>') as icono_info FROM mod_tienda  ";
	
	if (isset($_GET['buscar_clientes'])){
		$sql.=" WHERE upper(tx_descripcion) LIKE '%".strtoupper($_GET['buscar_clientes'])."%' or   upper(tx_descripcion) LIKE '%".strtoupper($_GET['buscar_clientes'])."%' OR upper(tx_direccion) LIKE '%".strtoupper($_GET['buscar_clientes'])."%' OR upper(tx_telefono) LIKE '%".strtoupper($_GET['buscar_clientes'])."%'  OR upper(tx_correo) LIKE '%".strtoupper($_GET['buscar_clientes'])."%' ";
	}
	

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
  "delete"  =>array("view"=>1, "edit"=>0, "type"=>"image")
);
$dgrid->setModes($modes);



 $multirow_option = true;
 $dgrid->allowMultirowOperations($multirow_option);
 $multirow_operations = array(
    "delete"  => array("view"=>false),
    "details" => array("view"=>false),
	"edit" => array("view"=>true)
 );
 $dgrid->setMultirowOperations($multirow_operations); 

$http_get_vars = array("buscar_clientes");
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
	"Descripción "     =>array("table"=>"mod_tienda", "field"=>"tx_descripcion", "source"=>"self","operator"=>false, "default_operator"=>"%like%", "type"=>"textbox", "autocomplete"=>false,"case_sensitive"=>true,  "comparison_type"=>"string")
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
	
	"id_tienda"  =>array("header"=>"ID","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_marca"  =>array("header"=>"UN","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_descripcion2"  =>array("header"=>"Descripción","header_align"=>"center","type"=>"label", "width"=>"35%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_direccion"  =>array("header"=>"Dirección","header_align"=>"center","type"=>"label", "width"=>"35%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_telefono"  =>array("header"=>"Telefonos","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"tx_correo"  =>array("header"=>"Dirección","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"estatus"  =>array("header"=>"Estatus","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	
	"icono_info"=>array("header"=>"Info","header_align"=>"center", "type"=>"link",    "align"=>"center", "width"=>"5%", "wrap"=>"wrap", "text_length"=>"-1", "tooltip"=>"true", "tooltip_type"=>"floating", "case"=>"normal", "summarize"=>"false", "sort_type"=>"numeric", "sort_by"=>"", "visible"=>"true", "on_js_event"=>"", "field_key"=>"id_tienda","field_data"=>"icono_info", "rel"=>"", "title"=>"Historial", "target"=>"", "href"=>"javascript:abrir_historial({0});")
	
  );
  
  $dgrid->setColumnsInViewMode($vm_colimns);
## +---------------------------------------------------------------------------+
## | 7. Add/Edit/Details Mode settings:                                        | 
## +---------------------------------------------------------------------------+
##  ***  set settings for edit/details mode
 
	//*****ARREGLO PARA ESTATUS ******//
	$tema_array_sql = "SELECT tx_tipo, id_tipo_objeto FROM vie_tbl_estatus_tienda";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"estatus_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
	//*****ARREGLO PARA TIPO ******//
	$tema_array_sql = "SELECT tx_tipo, id_tipo_objeto FROM cfg_tipo_objeto WHERE tx_objeto='tipo_origen_destino'";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"tipo_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
	//*****ARREGLO PARA EMPRESA ******//
	$tema_array_sql = "SELECT tx_empresa, id_empresa FROM mod_empresa ";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"empresa_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
	
	//*****ARREGLO PARA SUPERVISOR ******//
	//$tema_array_sql = "SELECT tx_nombres_apellidos, id_personal FROM mod_personal";
	//$especial_array_str = crearArregloDataGrid($tema_array_sql,"personal_array",g_BaseDatos);
	//eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	
  $table_name = "mod_tienda";
  $primary_key = "id_tienda";
  $condition = "";
  $dgrid->setTableEdit($table_name, $primary_key, $condition);
  $dgrid->setAutoColumnsInEditMode(false);
   $em_columns = array(
 	
	"id_empresa" =>array("header"=>"Empresa",  "type"=>"enum",  "default"=>"1",  "source"=>$empresa_array, "view_type"=>"dropdownlist",  "width"=>"80px", "req_type"=>"st", "title"=>"", "default"=>"0"),
	
	
	"id_tipo_cliente" =>array("header"=>"Tipo",  "type"=>"enum",  "default"=>"1",  "source"=>$tipo_array, "view_type"=>"dropdownlist",  "width"=>"80px", "req_type"=>"rt", "title"=>""),

	"tx_descripcion" =>array("header"=>"TDA - Sede", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	"tx_apellido_paterno" =>array("header"=>"Apellidos Paterno", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false, "default"=>' '),
	"tx_apellido_materno" =>array("header"=>"Apellido Materno", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false, "default"=>' '),
	"tx_puesto" =>array("header"=>"Puesto", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false, "default"=>' '),
	"tx_dni" =>array("header"=>"DNI", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false, "default"=>' '),
	
	
	"tx_direccion" =>array("header"=>"Dirección", "type"=>"textbox", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	"tx_ubicacion" =>array("header"=>"Ubicación", "type"=>"textbox", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	
	"tx_distrito_provincia" =>array("header"=>"Distrito/Provincia", "type"=>"textbox", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	"tx_url" =>array("header"=>"Dirección WEB", "type"=>"textbox", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	"tx_telefono" =>array("header"=>"Telefonos", "type"=>"textbox", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	"tx_correo" =>array("header"=>"Correo", "type"=>"email", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	"tx_hora_cierre" =>array("header"=>"Hora de Cierre", "type"=>"textbox", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	"tx_formato" =>array("header"=>"Formato", "type"=>"textbox", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	"tx_rpc" =>array("header"=>"RPC", "type"=>"textbox", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
		
	"tx_observacion" =>array("header"=>"Observación", "type"=>"textbox", "width"=>"100%", "req_type"=>"sty", "title"=>"", "unique"=>false),
	
	//"id_supervisor" =>array("header"=>"Supervisor",  "type"=>"enum",  "default"=>"0",  "source"=>$personal_array, "view_type"=>"dropdownlist",  "width"=>"80px", "req_type"=>"st", "title"=>""),
	
	"estatus" =>array("header"=>"Estatus",  "type"=>"enum",  "default"=>"1",  "source"=>$estatus_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	
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

  <!-- Control Sidebar -->
  <?php include('barra_derecha.php'); ?>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
 <!-- Ventana para historial del equipo -->
	<div class="modal fade" tabindex="-1" id="myModal_importar" role="dialog" style="color:#999; ">
	  <div class="modal-dialog modal-lg">
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

<?php include('libreriaJS.php'); ?>

</body>
</html>
<script>
function abrir_historial(id){
	$('#historial').load('mod_ver_movimiento_info.php',{'id_tienda':id});
	$('#myModal_importar').modal('show'); 
}	

$("#rtyid_proveedor").select2();

</script>

<?php require_once('libreriaSCRIPT.php'); ?>






