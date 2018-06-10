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


?>
 <!-- Head app -->

  <?php require('cabecera.php'); ?> 
  
  <!-- Left side column. contains the logo and sidebar -->
  
  <?php $formulario=6; include('barra_izquierda.php'); ?> 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registro
        <small>Unidades de Negocios</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Unidades de Negocios</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		 <div align="left" class="col-xs-6">
		   <form action="mod_empresa.php" method="GET">			 
		     <strong>Buscar Empresa:</strong>
			<input id="buscar_empresa" name="buscar_empresa" class="form-control" type="text" placeholder="Valor de Busqueda" value=""  >
		  </form>
		</div>
		<div class="col_lg-12 col-md-12 col-sm-12 col-xs-12">
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

			$sql="SELECT 
		 id_empresa,
		 tx_ruc,
		 tx_empresa,
		 tx_marca,
		 tx_logo,
		 (SELECT tx_tipo FROM cfg_tipo_objeto WHERE id_tipo_objeto=mod_empresa.id_estatus) as estatus
		FROM
		 mod_empresa";
		 
		 if (isset($_GET['buscar_empresa'])){
				$sql.=" WHERE  tx_ruc LIKE '%".strtoupper($_GET['buscar_empresa'])."%' OR tx_empresa LIKE '%".strtoupper($_GET['buscar_empresa'])."%' OR  tx_marca LIKE '%".strtoupper($_GET['buscar_empresa'])."%'";
			}

		##  *** set needed options
		  $debug_mode = false;
		  $messaging = true;
		  $unique_prefix = "f_";  
		  $dgrid = new DataGrid($debug_mode, $messaging, $unique_prefix, DATAGRID_DIR);
		##  *** set data source with needed options
		  $default_order_field = "tx_empresa";
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



		 $multirow_option = false;
		 $dgrid->allowMultirowOperations($multirow_option);
		 $multirow_operations = array(
			"delete"  => array("view"=>false),
			"details" => array("view"=>false),
			"edit" => array("view"=>true)
		 );
		 $dgrid->setMultirowOperations($multirow_operations); 

		//$http_get_vars = array("importancia");
		//$dgrid->SetHttpGetVars($http_get_vars);

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
		$pages_array = array("10"=>"10", "25"=>"25", "50"=>"50", "100"=>"100", "250"=>"250", "500"=>"500", "1000"=>"1000", "2000"=>"2000");
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
			"Empresa "     =>array("table"=>"mod_empresa", "field"=>"tx_empresa", "source"=>"self","operator"=>false, "default_operator"=>"%like%", "type"=>"textbox", "autocomplete"=>false,"case_sensitive"=>FALSE,  "comparison_type"=>"string"),
			"Marca "     =>array("table"=>"mod_empresa", "field"=>"tx_marca", "source"=>"self","operator"=>false, "default_operator"=>"%like%", "type"=>"textbox", "autocomplete"=>false,"case_sensitive"=>FALSE,  "comparison_type"=>"string")
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
			
			"id_empresa"  =>array("header"=>"ID","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
			
			"tx_ruc"  =>array("header"=>"RUC","header_align"=>"center","type"=>"label", "width"=>"10%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
			
			"tx_empresa"  =>array("header"=>"EMPRESA","header_align"=>"center","type"=>"label", "width"=>"55%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
			
			"tx_marca"  =>array("header"=>"MARCA","header_align"=>"center","type"=>"label", "width"=>"15%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
			
			"estatus"  =>array("header"=>"ESTATUS","header_align"=>"center","type"=>"label", "width"=>"5%", "align"=>"left",    "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal"),
			
			"tx_logo"=>array("header"=>"LOGO", "type"=>"image",      "align"=>"center", "width"=>"10%", "wrap"=>"wrap", "text_length"=>"-1", "case"=>"normal", "summarize"=>"false", "sort_type"=>"string", "sort_by"=>"", "visible"=>"true", "on_js_event"=>"", "target_path"=>"repositorio/logos/", "default"=>"", "image_width"=>"100%", "image_height"=>"10%", "linkto"=>"", "magnify"=>"true", "magnify_type"=>"popup", "magnify_power"=>"2")
			
		  );
		  
		  $dgrid->setColumnsInViewMode($vm_colimns);
		## +---------------------------------------------------------------------------+
		## | 7. Add/Edit/Details Mode settings:                                        | 
		## +---------------------------------------------------------------------------+
		##  ***  set settings for edit/details mode
		 
		 //*****ARREGLO PARA ESTATUS ******//
			$tema_array_sql = "SELECT tx_tipo, id_tipo_objeto FROM cfg_tipo_objeto WHERE tx_objeto='estatus_empresa'";
			$especial_array_str = crearArregloDataGrid($tema_array_sql,"estatus_array",g_BaseDatos);
			eval($especial_array_str);	
			//******FIN DE ARREGLO *****///
		 
		  $table_name = "mod_empresa";
		  $primary_key = "id_empresa";
		  $condition = "";
		  $dgrid->setTableEdit($table_name, $primary_key, $condition);
		  $dgrid->setAutoColumnsInEditMode(false);
		   $em_columns = array(
			
			"tx_logo"  =>array("header"=>"Logo", "type"=>"image",      "req_type"=>"st", "width"=>"210px", "title"=>"", "readonly"=>"false", "maxlength"=>"-1", "default"=>"", "unique"=>"false", "unique_condition"=>"", "visible"=>"true", "on_js_event"=>"", "target_path"=>"repositorio/logos/", "allow_image_updating"=>"false", "max_file_size"=>"2M", "image_width"=>"128px", "image_height"=>"128px", "resize_image"=>"FALSE", "resize_width"=>"128px", "resize_height"=>"128px", "magnify"=>"false", "magnify_type"=>"lightbox", "magnify_power"=>"2", "file_name"=>"", "host"=>"local", "allow_downloading"=>"false", "allowed_extensions"=>""),
			
			"tx_ruc" =>array("header"=>"RUC", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
			
			"tx_empresa" =>array("header"=>"NOMBRE", "type"=>"textarea", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
			
			"tx_marca" =>array("header"=>"MARCA", "type"=>"textbox", "width"=>"100%", "req_type"=>"rty", "title"=>"", "unique"=>false),
			
			"tx_abreviatura" =>array("header"=>"ABREVIATURA", "type"=>"textbox", "width"=>"40px", "req_type"=>"rty", "title"=>"", "unique"=>false),
			
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

</body>
</html>
<?php require_once('libreriaSCRIPT.php'); ?>





