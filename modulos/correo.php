<!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<?php

require_once('common.php');  //chequeo de usuario entrante 

//Busca los datos del correo activo en la empresa
$sql="SELECT tx_logo, tx_nombre_empresa, tx_correo_electronico,tx_usuario_contrasena,nu_port,tx_smtp  FROM cfg_configuracion_general";
$res=abredatabase(g_BaseDatos,$sql);
$row=dregistro($res);
$correo_smtp=$row['tx_correo_electronico'];
 $clave_correo_smtp=$row['tx_usuario_contrasena'];
$port=$row['nu_port'];
$smtp=$row['tx_smtp'];
$nombre_empresa=$row['tx_nombre_empresa'];
$logo_empresa=$row['tx_logo'];
cierradatabase();



/**
 * This example shows settings to use when sending via Google's Gmail servers.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

require '../lib/mail/PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;

//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';

//Set the hostname of the mail server
$mail->Host = $smtp;
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = $port;

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'TLS';

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = $correo_smtp;

//Password to use for SMTP authentication
$mail->Password = $clave_correo_smtp;

//Set who the message is to be sent from
$mail->setFrom($correo_smtp, $nombre_empresa);

//Set an alternative reply-to address
//mail->addReplyTo('replyto@example.com', 'First Last');

//Set who the message is to be sent to

//envio de correo para equipos

if ($_POST['tipo_correo']==1){

	 $sql="SELECT tx_nombre_tipo,tx_marca,tx_modelo, tx_serial, tx_guia, tx_accesorios, tx_ngr, nx_cantidad, (SELECT tx_ticket FROM mod_movimiento WHERE id_movimiento=vie_tbl_movimiento.id_movimiento) as tx_ticket, (SELECT tx_correo FROM mod_responsable WHERE id_responsable=vie_tbl_movimiento.id_responsable_enviado) as tx_correo, (select tx_placati FROM mod_producto WHERE id_producto=vie_tbl_movimiento.id_producto) as placati,(SELECT (tx_descripcion) from  vie_tbl_tiendas_destino WHERE id_tienda=vie_tbl_movimiento.id_responsable_destino LIMIT 1) as tx_responsable 	FROM vie_tbl_movimiento WHERE tx_guia='".$_POST['guia']."' and fe_movimiento='".$_POST['fecha']."'";
	$res=abredatabase(g_BaseDatos,$sql);
	$contenido="";
	
	while($row=dregistro($res)){
	  $correo=$row['tx_correo'];
           $movimiento="N° de GUIA: ".$row['tx_guia']."<br>"." N° de Ticket: ".$row['tx_ticket']."<br>".$row['tx_responsable'];
	  $contenido.="
		<tr>
			<td class='bg-primary'>".$row['tx_nombre_tipo']."</td>
			<td>".$row['tx_marca']."</td>
			<td>".$row['tx_modelo']."</td>
			<td>".$row['tx_serial']."</td>
			<td>".$row['tx_accesorios']."</td>
			<td>".$row['tx_ngr']."</td>
			<td>".$row['placati']."</td>
			<td>".$row['nx_cantidad']."</td>
		 </tr>
	  ";
	}
	//$contenido.="</table>";
cierradatabase();

	$mail->addAddress($correo);
	$mail->addAddress("almacen.ti@ngr.com.pe");
	$mail->addAddress("andrea.sierra@ngr.com.pe");
	$mail->Subject = "Tu Equipo ya esta listo!";
	$url="modelo_correo/correo_email.php";
	$data=array("mensaje"=>$contenido,"movimiento"=>$movimiento);
	
}

$mail->msgHTML(strtr(file_get_contents($url), $data), dirname(__FILE__));

//Replace the plain text body with one created manually
$mail->AltBody = 'Este es un mensaje del Equipo de '.$nombre_empresa;

//Attach an image file
$mail->AddEmbeddedImage("repositorio/logos_cintillos/marcas.png", "logo1", "some_picture.png", "base64", "application/octet-stream");
//$mail->AddEmbeddedImage('repositorio/logos_cintillos/logo.png', 'logo1');

//send the message, check for errors
if (!$mail->send()) {
    echo "Mensaje No Enviado Vuelva a Intentar!";
} else {
   echo "enviado";
}


?>