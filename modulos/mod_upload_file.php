<?php
$target_dir = "repositorio/importes/";
echo $_POST['archivo'];
$target_file = $target_dir . basename($_FILES["archivo"]["name"]);
echo $target_file;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
echo $imageFileType;
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = filesize($_FILES["archivo"]["tmp_name"]);
	echo $check;
    if($check !== false) {
        echo "Archivo es un Excel - " . $check["mime"] . ".<br>";
        $uploadOk = 1;
    } else {
        echo "El acrhivo no cumple con las condiciones de carga.<br>";
        $uploadOk = 1;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Lo siento el archivo ya existe.<br>";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["archivo"]["size"] > 5000000) {
    echo "Lo siento, el archivo no se pudo cargar el archivo es mayor a los 5 MB.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "xls" && $imageFileType != "xlsx") {
    echo "Lo siento, solo se permiten archivo con la extensión XLS, XLXS.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "No se pudo cargar su archivo";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $target_file)) {
        echo "El Archivo ". basename( $_FILES["archivo"]["name"]). " fue Cargador con exito.";
    } else {
        echo "Lo siento, no se pudo cargar el archivo intente de nuevo";
    }
}
?>
