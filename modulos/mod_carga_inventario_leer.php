<?php require_once('common.php'); checkUser(); 
require_once '../lib/Excel/reader.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('UTF-8');
$data->read($_POST['archivo']);
$datos_nuevos=0;
$datos_existentes=0;
$id_producto=0;
$total_filas=$data->sheets[0]['numRows'];
$i=0;
for ($i = 4; $i <= $data->sheets[0]['numRows']; $i++) {
		$sql_inventario='';
		$fecha=$data->sheets[0]['cells'][$i][2];
		$tienda=intval(utf8_encode($data->sheets[0]['cells'][$i][4]));
		$tipo=intval(utf8_encode($data->sheets[0]['cells'][$i][5]));
		$marca=intval(utf8_encode($data->sheets[0]['cells'][$i][8]));
		$modelo=strtoupper (utf8_encode($data->sheets[0]['cells'][$i][9]));
		$serial=strtoupper (utf8_encode($data->sheets[0]['cells'][$i][10]));
		$placa=strtoupper (utf8_encode($data->sheets[0]['cells'][$i][11]));
		$placati=strtoupper (utf8_encode($data->sheets[0]['cells'][$i][12]));
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
		
		 $sql_inventario="SELECT id_producto FROM mod_producto WHERE tx_serial='".$serial."'";
		$res_inventario=abredatabase(g_BaseDatos,$sql_inventario);
		 dnumerofilas($res_inventario).'<br>';
		if (dnumerofilas($res_inventario)!=0){
			$row_inventario=dregistro($res_inventario);
			$datos_existentes+=1;
			$id_producto=$row_inventario[0];
		}else{
			$datos_nuevos+=1;
			$id_producto=0;
		}
		 $sql_inventario="INSERT INTO mod_producto_temp(
		  id_tabla_producto,
		  id_tipo_producto,
		  id_tipo_marca,
		  fe_ingreso,
		  tx_modelo,
		  tx_observacion,
		  id_usuario,
		  tx_serial,
		  tx_ngr,
		  n_stock,
		  id_tienda,
		  tx_placati,
		  tx_archivo, estatus, id_condicion) VALUES(".$id_producto.",".$tipo.",".$marca.",'".$fecha."','".$modelo."','',".$_SESSION['id_usuario'].",'".$serial."','".$placa."',1,".$tienda.",'".$placati."','".$_POST['archivo']."',".$estado.",".$condicion.")";
		$res_inventario=abredatabase(g_BaseDatos,$sql_inventario);
}
$total=$datos_nuevos + $datos_existentes;
echo "<hr>";
echo "Total Registros Nuevos:".$datos_nuevos;
echo "<br>";
echo "Total Registros Existentes:".$datos_existentes;
echo "<br>";
echo "Total Registros:".$total;

?>