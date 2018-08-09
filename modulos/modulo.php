<?php
	require_once('common.php');
	checkUser();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MANTENIMIENTO</title>

<script src="jquery.js" type="text/javascript"></script>
<script src="jquery.MultiFile.js" type="text/javascript"></script>

</head>
<?php
$directorio = '';

if(isset($_FILES['archivo'])){
        foreach ($_FILES['archivo']['error'] as $key => $error) {
           if ($error == UPLOAD_ERR_OK) {
                  // echo "$error_codes[$error]";
                   move_uploaded_file($_FILES["archivo"]["tmp_name"][$key],$directorio.$_FILES["archivo"]["name"][$key]) or die("Ocurrio un problema al intentar subir el archivo.");
           }
        }
}

?>
<body>
<form enctype="multipart/form-data" name="subir" action="modulo.php" method="post" >
        <p>
          <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
        Seleccionar archivo:</p>
        <p> 
          <input id="archivo" name="archivo[]" class="multi" type="file" size="16" /> 
          <input type="submit" value="Subir Imagen" />
        </p>
</form>
</body>
</html>
