 <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<?php 
require_once('common.php'); checkUser(); 
require_once '../lib/Excel/reader.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('UTF-8');
$data->read($_POST['archivo']);
$nequipos=0;
$correctos=0;
$incorrectos=0;
$total=0;
$datos_nuevos=0;
$datos_existentes=0;
$id_producto=0;
$fecha="";
$tienda="";
$total_filas=$data->sheets[0]['numRows']-3;
for ($i = 4; $i <= $data->sheets[0]['numRows']; $i++) {
		$ok=0;
		$fecha=utf8_encode($data->sheets[0]['cells'][$i][2]);
		$tienda=intval(utf8_encode($data->sheets[0]['cells'][$i][4]));
		$tipo=intval(utf8_encode($data->sheets[0]['cells'][$i][5]));
		$marca=intval(utf8_encode($data->sheets[0]['cells'][$i][8]));
		$modelo=utf8_encode($data->sheets[0]['cells'][$i][9]);
		$serial=strtoupper(utf8_encode($data->sheets[0]['cells'][$i][10]));
		$placa=utf8_encode($data->sheets[0]['cells'][$i][11]);
		$placati=utf8_encode($data->sheets[0]['cells'][$i][12]);
		if (strtoupper (intval(utf8_encode($data->sheets[0]['cells'][$i][13])))==1){
			$estado=5;
		}else{
			$estado=7;
		}
		if (strtoupper (intval(utf8_encode($data->sheets[0]['cells'][$i][13])))==1) {
			$condicion= 20;
		}else{
			$condicion= 81;
		}
		if ($fecha!='' && $tienda!='' && $marca!='' && $modelo!='' && $serial!='' && $placa!='' && $placati!='' && $estado!=''   ){
			$ok=1;
		}else{
			$ok=0;
		}
		 $sql_inventario="SELECT id_producto FROM mod_producto WHERE tx_serial='".$serial."'";
		$res_inventario=abredatabase(g_BaseDatos,$sql_inventario);
		if (dnumerofilas($res_inventario)!=0){
			$row_inventario=dregistro($res_inventario);
			$datos_existentes+=1;
			$id_producto=$row_inventario[0];
		}else{
			$datos_nuevos+=1;
			$id_producto=0;
		}
		$sql_inventario="SELECT tx_descripcion, id_tienda FROM mod_tienda WHERE id_tienda='".$tienda."'";
		$res_inventario=abredatabase(g_BaseDatos,$sql_inventario);
		if (dnumerofilas($res_inventario)!=0){
			$ok=1;
			$row_inventario=dregistro($res_inventario);
			$tienda=$row_inventario[0];
		}else{
			$ok=0;
		}
		$ok==1? $correctos+=1 : $incorrectos+=1;
}
$total=$correctos + $incorrectos;
echo "<strong>".$tienda."</strong>";
echo "<br>";
echo "Total Registros Correctos:".$correctos;
echo "<br>";
echo "Total Registros Incorrectos:".$incorrectos;
echo "<br>";
echo "Total Registros:".$total;
echo "<br>";
$total=$datos_nuevos + $datos_existentes;
echo "<br>";
echo "Total Filas del Archivo:".$total_filas;
echo "<br>";
echo "Total Registros Nuevos:".$datos_nuevos;
echo "<br>";
echo "Total Registros Existentes:".$datos_existentes;
echo "<p>";
echo "Resultado <br>";
if ($total==$correctos){
	echo '<button class="btn btn-lg btn-success" onclick="javascript:incluir(\''.$_POST['archivo'].'\');">Procesar</button>';
}else{
	echo "<h3><span class='label label-danger'>Error, Revise su archivo excel, y vuelva a cargar</span></h3>";
}
?>
<script> 
function incluir(archivo){
	$("#resultados").load("mod_carga_inventario_leer.php",{ 'archivo': archivo});
}
</script>