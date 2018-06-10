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
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php 
require_once('common.php'); checkUser(); 
	date_default_timezone_set($_SESSION['zona_horario']);
?>
 <!-- Head app -->
  <?php require('cabecera.php'); ?> 
  <!-- Left side column. contains the logo and sidebar -->
  <?php  $formulario=40; include('barra_izquierda.php'); ?> 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registro
        <small>Equipos y/o Accesorios Temporal</small>
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
              <h3 class="box-title">Maestro de Inventario Temporal</h3>
            </div>
            <div class="box-body">
			<div id="registro" class="col-xs-12">
				<div class="callout callout-danger">
					<h4>Información Importante!</h4>
					<p>Al Presionar el Boton de Carga de Inventario Temporal debe estar seguro, de que ha chequeado la información y que toda la data es la definitiva a subir, para no generar problemas de duplicidad de información.</p>
				</div>
	</div>
		<div align="left" class="col-xs-6">
		   <form action="mod_registro_equipos_temporal.php" method="GET">			 <strong>Buscar Equipo:</strong>
			<input id="buscar_equipo_general" name="buscar_equipo_general" class="form-control" type="text" placeholder="Valor de Busqueda" value=""  >
		  </form>
		</div>
		<div align="right" class="col-xs-6">
		   <input type="checkbox" id="movimiento"> Excluir Movimientos 
		   <button class="btn btn-lg btn-success" onclick="javascript:registrar_equipos();"><i class="fa fa-arrow-circle-o-up"></i> Subir Data</button>
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
	$sql="SELECT id_producto, tx_serial, fe_ingreso, tx_descripcion, tx_ngr, (SELECT tx_nombre_tipo FROM cfg_tipo_producto WHERE id_tipo_producto=a.id_tipo_producto) as tx_tipo_equipo, (SELECT tx_marca FROM cfg_tipo_marcas WHERE id_marca=a.id_tipo_marca) as tx_marca, tx_modelo, tx_placati, (SELECT tx_descripcion FROM mod_tienda WHERE id_tienda=a.id_tienda) as tienda, tx_archivo, CASE WHEN id_tabla_producto=0 THEN 'Nuevo' ELSE 'Existe' END AS producto  FROM mod_producto_temp a ";
	if (isset($_GET['buscar_equipo_general'])){
		$id_producto=$_GET['buscar_equipo_general']>0? intval($_GET['buscar_equipo_general']) : 0 ;
		$sql.=" WHERE id_producto = ".$id_producto." OR upper(tx_serial) LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%' OR upper(tx_ngr) LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%' OR upper(tx_placati) LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%'  OR upper((SELECT tx_nombre FROM cfg_tipo_producto WHERE id_tipo_producto=a.id_tipo_producto)) LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%' OR upper((SELECT tx_marca FROM cfg_tipo_marcas WHERE id_marca=a.id_tipo_marca)) LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%' OR  upper(tx_modelo) LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%' OR  upper((SELECT tx_descripcion FROM mod_tienda WHERE id_tienda=a.id_tienda)) LIKE '%".strtoupper($_GET['buscar_equipo_general'])."%'";
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
	"fe_ingreso"  =>array("header"=>"FECHA","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	"tx_serial"  =>array("header"=>"N° SERIAL","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	"tx_ngr"  =>array("header"=>"N° PLACA","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	"tx_placati"  =>array("header"=>"N° PLACA TI","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	"tx_tipo_equipo"  =>array("header"=>"TIPO","header_align"=>"center","type"=>"label", "width"=>"20%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	"tx_marca"  =>array("header"=>"MARCA","header_align"=>"center","type"=>"label", "width"=>"21%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	"tx_modelo"  =>array("header"=>"MODELO","header_align"=>"center","type"=>"label", "width"=>"15%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	"tienda"  =>array("header"=>"TIENDA","header_align"=>"center","type"=>"label", "width"=>"15%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	"tx_archivo"  =>array("header"=>"ARCHIVO","header_align"=>"center","type"=>"label", "width"=>"15%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
	"producto"  =>array("header"=>"ESTATUS","header_align"=>"center","type"=>"label", "width"=>"15%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal")
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
	//*****ARREGLO PARA UNIDAD DE MEDIDA******//
	$tema_array_sql = "SELECT tx_tipo, id_tipo_objeto FROM vie_tbl_unidad_medida ORDER BY tx_tipo";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"unidad_medida_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	//*****ARREGLO PARA ESTATUS EQUIPO******//
	$tema_array_sql = "SELECT tx_tipo, id_tipo_objeto FROM vie_tbl_estatus_equipo ORDER BY tx_tipo";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"estatus_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	//*****ARREGLO PARA ESTATUS EQUIPO******//
	$tema_array_sql = "SELECT tx_tipo, id_tipo_objeto FROM vie_tbl_tipo_garantia ORDER BY tx_tipo";
	$especial_array_str = crearArregloDataGrid($tema_array_sql,"garantia_array",g_BaseDatos);
	eval($especial_array_str);	
	//******FIN DE ARREGLO *****///
	//*****ARREGLO ******//
	$tema_array_sql = "SELECT tx_tipo, id_tipo_objeto FROM vie_tbl_condicion_equipo ORDER BY tx_tipo";
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
  $table_name = "mod_producto_temp";
  $primary_key = "id_producto";
  $condition = "";
  $dgrid->setTableEdit($table_name, $primary_key, $condition);
  $dgrid->setAutoColumnsInEditMode(false);
   $em_columns = array(
	"id_tipo_producto" =>array("header"=>"Tipo de Equipo",  "type"=>"enum",  "default"=>"1",  "source"=>$tipoequipos_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	"tx_serial" =>array("header"=>"N° Serial", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	"id_tipo_marca" =>array("header"=>"Marca",  "type"=>"enum",  "default"=>"1",  "source"=>$marca_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	"tx_modelo" =>array("header"=>"Modelo", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	"tx_ngr" =>array("header"=>"Código NGR", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	"tx_placati" =>array("header"=>"Código PLACA TI", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
	"id_unidad_medida" =>array("header"=>"Unidad de Medida",  "type"=>"enum",  "default"=>"1",  "source"=>$unidad_medida_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"st", "title"=>""),
	"id_condicion" =>array("header"=>"Condición",  "type"=>"enum",  "default"=>"1",  "source"=>$condicio_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
	"estatus" =>array("header"=>"Estatus",  "type"=>"enum",  "default"=>"1",  "source"=>$estatus_array, "view_type"=>"dropdownlist",  "width"=>"20px", "req_type"=>"rt", "title"=>""),
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
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script>
 function registrar_equipos() {
	 $('#registro').load('mod_eventos.php', { evento : 30, movimiento : $('#movimiento').is(':checked')  });
 }
 //Date picker
    $('#fe_entrada_equipo').datepicker({
      autoclose: true,
	  format: 'dd/mm/yyyy'
    });
</script>
<?php require_once('libreriaSCRIPT.php'); ?>
</body>
</html>