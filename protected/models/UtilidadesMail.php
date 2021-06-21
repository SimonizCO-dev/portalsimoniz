<?php

class UtilidadesMail {

	public static function horamensaje() {
		//se define la hora de mensaje
		$hora = date('H');

		if($hora >= 0 && $hora <= 12){
		    $mensaje_hora = "Buenos días,";
		}

		if($hora >= 13 && $hora <= 16){
		    $mensaje_hora = "Buenas tardes,";
		}

		if($hora >= 17 && $hora <= 23){
		    $mensaje_hora = "Buenas noches,";
		}

		return $mensaje_hora;
	}


	public static function enviocreaciondocto($Id_Documento) {

		set_time_limit(0);
		
		// Se inactiva el autoloader de yii
		spl_autoload_unregister(array('YiiBase','autoload'));  

		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\PHPMailer.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\SMTP.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\Exception.php');

		//cuando se termina la accion relacionada con la libreria se activa el autoloader de yii
		spl_autoload_register(array('YiiBase','autoload'));

		$host = Yii::app()->params->env_mail_host;
		$port = Yii::app()->params->env_mail_port;
		$smtpsecure = Yii::app()->params->env_mail_smtpsecure;
		$smtpauth = Yii::app()->params->env_mail_smtpauth;
		$smtpdebug = Yii::app()->params->env_mail_smtpdebug;
		$username = Yii::app()->params->env_mail_cuenta;
		$password = Yii::app()->params->env_mail_password;
		$correo_rem = Yii::app()->params->env_mail_cuenta_rem;
		$desc_correo_rem = Yii::app()->params->env_mail_desc_cuenta_rem;

		$logo = 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::app()->getBaseUrl(true)."/images/login-logo.png", false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))));

		$modelo_documento = GdDocumento::model()->findByPk($Id_Documento);

		$modelo_area_documento = GdAreaDocumento::model()->findAllByAttributes(array('Id_Documento' => $Id_Documento));

		$array_emails = array();

		foreach ($modelo_area_documento as $area_doc) {

		    $id_area = $area_doc->Id_Area;

		    $modelo_notif_area = GdNotificacionArea::model()->findByAttributes(array('Id_Area' => $id_area, 'Estado_Notif' => 1, 'Estado' => 1));

		    if(!is_null($modelo_notif_area)){

		        $emails_notif = $modelo_notif_area->Correos_Notif;
		        $array_emails_area = explode(",", $emails_notif);

		        foreach ($array_emails_area as $key => $email) {
		            array_push($array_emails, trim($email));
		        }
		    }
		}

		//se eliminan valores repetidos para no enviar mas de un email al mismo destinatario
		$lista_depurada_emails = array_values(array_unique($array_emails));

		if($modelo_documento->Estado != 0 && !empty($lista_depurada_emails)){
			//se envia el correo

			$areas = $modelo_documento->Desc_Areas($modelo_documento->Id_Documento);
			$clasif = UtilidadesVarias::descclasif($modelo_documento->Clasificacion);
			$tipo = $modelo_documento->tipo->Descripcion;
			$num_doc = $modelo_documento->Num_Documento;
			$nombre = $modelo_documento->Titulo;
			$descripcion = $modelo_documento->Descripcion;
			$elaborado_por = $modelo_documento->Elaborado_Por;
			$fecha_elaboracion = UtilidadesVarias::textofecha($modelo_documento->Fecha_Elaboracion);
			$n_r = $modelo_documento->Nivel_Revision;
			$fecha_revision = UtilidadesVarias::textofecha($modelo_documento->Fecha_Revision);
			$emitido_por = $modelo_documento->Emitido_Por;
			$fecha_emision = UtilidadesVarias::textofecha($modelo_documento->Fecha_Emision);
			$aprobado_por = $modelo_documento->Desc_Unidad_Gerencia($modelo_documento->Aprobado_Por);
			$pd = UtilidadesVarias::textoestado2($modelo_documento->Permite_Descarga);
			$cc = UtilidadesVarias::textoestado2($modelo_documento->Copia_Controlada);
			$usuario_cre = $modelo_documento->idusuariocre->Usuario;
			$fecha_cre = UtilidadesVarias::textofechahora($modelo_documento->Fecha_Creacion);

			$asunto = "Se ha subido un nuevo documento";

			$mensaje = '
			<!DOCTYPE html>
			<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
			<head>
			    <meta charset="utf-8">
			    <meta name="viewport" content="width=device-width">
			    <meta http-equiv="X-UA-Compatible" content="IE=edge">
			    <meta name="x-apple-disable-message-reformatting">
			</head>
			<body>
			    <center>
			        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; width: 60%;">
			            <tr>
			                <td style="background-color: #ffffff;">
			                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
			                        <tr>
			                            <td style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
			                                <img src="'.$logo.'" alt="'.CHtml::encode(Yii::app()->name).'" title="'.CHtml::encode(Yii::app()->name).'"/>
			                                <h1 style="margin: 0 0 10px; font-size: 25px; line-height: 30px; color: #333333; font-weight: normal;">'.UtilidadesMail::horamensaje().'</h1>
			                                <p>A continuación se listaran los datos correspondientes al documento: </p><br>
			                                <strong>Áreas asociadas: </strong><p>'.$areas.'</p>
											<strong>Clasificación: </strong><p>'.$clasif.'</p>
											<strong>Tipo: </strong><p>'.$tipo.'</p>
											<strong>N° documento: </strong><p>'.$num_doc.'</p>
											<strong>Nombre: </strong><p>'.$nombre.'</p>
											<strong>Descripción: </strong><p>'.$descripcion.'</p>
											<strong>Elaborado por: </strong><p>'.$elaborado_por.'</p>
											<strong>Fecha de elaboración: </strong><p>'.$fecha_elaboracion.'</p>
											<strong>Nivel de revisión: </strong><p>'.$n_r.'</p>
											<strong>Fecha de revisión: </strong><p>'.$fecha_revision.'</p>
											<strong>Emitido por: </strong><p>'.$emitido_por.'</p>
											<strong>Fecha de emisión: </strong><p>'.$fecha_emision.'</p>
											<strong>Aprobado por: </strong><p>'.$aprobado_por.'</p>
											<strong>Permitir descarga: </strong><p>'.$pd.'</p>
											<strong>Copia controlada: </strong><p>'.$cc.'</p>
											<strong>Usuario que creo: </strong><p>'.$usuario_cre.'</p>
											<strong>Fecha de creación: </strong><p>'.$fecha_cre.'</p>

			                                <hr>
			                       
			                            </td>
			                        </tr>
			                    </table>
			                </td>
			            </tr>
			            <tr style="padding: 40px 0; text-align: center">
			                </tr>
			        </table>
			    </center>
			</body>
			</html>
			';

			$mail = new PHPMailer\PHPMailer\PHPMailer;
		
			$mail->IsSMTP();
			$mail->CharSet = 'UTF-8';

			$mail->Host 	  = $host;
			$mail->Port       = $port;
			$mail->SMTPSecure = $smtpsecure;
			$mail->SMTPAuth   = $smtpauth;
			$mail->SMTPDebug  = $smtpdebug;
			$mail->Username   = $username;
			$mail->Password   = $password;
			$mail->From       = $correo_rem;
	 		$mail->FromName   = $desc_correo_rem;
			
			$mail->isHTML(true);
			$mail->Subject = $asunto;
			$mail->Body = $mensaje;
			
			foreach ($lista_depurada_emails as $key => $email) {
				$mail->addAddress($email);
			}

			if(!$mail->send()){
				return 0;
			}else{
			 	return 1;
			}
			
		}else{
			return 2;
		}
	
	}

	public static function envioliq($opc, $id, $id_vendedor, $email, $ruta_archivo) {

		set_time_limit(0);
		
		// Se inactiva el autoloader de yii
		spl_autoload_unregister(array('YiiBase','autoload'));  

		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\PHPMailer.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\SMTP.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\Exception.php');

		//cuando se termina la accion relacionada con la libreria se activa el autoloader de yii
		spl_autoload_register(array('YiiBase','autoload'));

		$host = Yii::app()->params->env_mail_host;
		$port = Yii::app()->params->env_mail_port;
		$smtpsecure = Yii::app()->params->env_mail_smtpsecure;
		$smtpauth = Yii::app()->params->env_mail_smtpauth;
		$smtpdebug = Yii::app()->params->env_mail_smtpdebug;
		$username = Yii::app()->params->env_mail_cuenta;
		$password = Yii::app()->params->env_mail_password;
		$correo_rem = Yii::app()->params->env_mail_cuenta_rem;
		$desc_correo_rem = Yii::app()->params->env_mail_desc_cuenta_rem;

		$logo = 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::app()->getBaseUrl(true)."/images/login-logo.png", false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))));

		$modelo_liq = CControlCms::model()->findByAttributes(array('ID_BASE' => $id));

		$info_id = $modelo_liq->ID_BASE;
		$info_mes = $modelo_liq->Desc_Mes($modelo_liq->MES);
		$info_anio = $modelo_liq->ANIO;
		$info = $info_mes.' - '.$info_anio;

		if($opc == 1){
			//resumen general
			$asunto = "Detalle comisión ".$info;
		}else{
			//resumen por vendedor
			$asunto = "Detalle comisión ".$info.' (Vendedor '.$id_vendedor.')';
		}

		$mensaje = '
		<!DOCTYPE html>
		<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
		<head>
		    <meta charset="utf-8">
		    <meta name="viewport" content="width=device-width">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta name="x-apple-disable-message-reformatting">
		</head>
		<body>
		    <center>
		        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; width: 60%;">
		            <tr>
		                <td style="background-color: #ffffff;">
		                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
		                        <tr>
		                            <td style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
		                            	<img src="'.$logo.'" alt="'.CHtml::encode(Yii::app()->name).'" title="'.CHtml::encode(Yii::app()->name).'"/>
		                            	<h1 style="margin: 0 0 10px; font-size: 25px; line-height: 30px; color: #333333; font-weight: normal;">'.UtilidadesMail::horamensaje().'</h1>
		                                <p>Se Adjunta documento PDF con detalle de comisión.</p>
		                                <hr>
		                            </td>
		                        </tr>
		                    </table>
		                </td>
		            </tr>
		            <tr style="padding: 40px 0; text-align: center">
		                </tr>
		        </table>
		    </center>
		</body>
		</html>
		';

		$mail = new PHPMailer\PHPMailer\PHPMailer;
		
		$mail->IsSMTP();
		$mail->CharSet = 'UTF-8';

		$mail->Host 	  = $host;
		$mail->Port       = $port;
		$mail->SMTPSecure = $smtpsecure;
		$mail->SMTPAuth   = $smtpauth;
		$mail->SMTPDebug  = $smtpdebug;
		$mail->Username   = $username;
		$mail->Password   = $password;
		$mail->From       = $correo_rem;
 		$mail->FromName   = $desc_correo_rem;
		
		$mail->isHTML(true);
		$mail->Subject = $asunto;
		$mail->Body = $mensaje;

		$mail->addAddress($email);

		if($opc == 1){
			//resumen general
			$mail->AddAttachment( $ruta_archivo, "Detalle comisión ".$info.'.pdf');
		}else{
			//resumen por vendedor
			$mail->AddAttachment( $ruta_archivo, "Detalle comisión ".$info.' (Vendedor '.$id_vendedor.').pdf');
		}

		if(!$mail->send()){
			return 0;
		}else{
		 	return 1;
		}
	
	}
  
	public static function enviowip($id, $email, $ruta_archivo) {

		set_time_limit(0);
		
		// Se inactiva el autoloader de yii
		spl_autoload_unregister(array('YiiBase','autoload'));  

		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\PHPMailer.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\SMTP.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\Exception.php');

		//cuando se termina la accion relacionada con la libreria se activa el autoloader de yii
		spl_autoload_register(array('YiiBase','autoload'));

		$host = Yii::app()->params->env_mail_host;
		$port = Yii::app()->params->env_mail_port;
		$smtpsecure = Yii::app()->params->env_mail_smtpsecure;
		$smtpauth = Yii::app()->params->env_mail_smtpauth;
		$smtpdebug = Yii::app()->params->env_mail_smtpdebug;
		$username = Yii::app()->params->env_mail_cuenta;
		$password = Yii::app()->params->env_mail_password;
		$correo_rem = Yii::app()->params->env_mail_cuenta_rem;
		$desc_correo_rem = Yii::app()->params->env_mail_desc_cuenta_rem;

		$logo = 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::app()->getBaseUrl(true)."/images/login-logo.png", false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))));

		$modelo_wip = Wip::model()->findByPk($id);

		$info = $modelo_wip->WIP;

		$asunto = "Detalle WIP ".$info;

		$mensaje = '
		<!DOCTYPE html>
		<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
		<head>
		    <meta charset="utf-8">
		    <meta name="viewport" content="width=device-width">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta name="x-apple-disable-message-reformatting">
		</head>
		<body>
		    <center>
		        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; width: 60%;">
		            <tr>
		                <td style="background-color: #ffffff;">
		                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
		                        <tr>
		                            <td style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
		                            	<img src="'.$logo.'" alt="'.CHtml::encode(Yii::app()->name).'" title="'.CHtml::encode(Yii::app()->name).'"/>
		                            	<h1 style="margin: 0 0 10px; font-size: 25px; line-height: 30px; color: #333333; font-weight: normal;">'.UtilidadesMail::horamensaje().'</h1>
		                                <p>Se Adjunta documento PDF con detalle de WIP.</p>
		                                <hr>
		                            </td>
		                        </tr>
		                    </table>
		                </td>
		            </tr>
		            <tr style="padding: 40px 0; text-align: center">
		                </tr>
		        </table>
		    </center>
		</body>
		</html>
		';

		$mail = new PHPMailer\PHPMailer\PHPMailer;
		
		$mail->IsSMTP();
		$mail->CharSet = 'UTF-8';

		$mail->Host 	  = $host;
		$mail->Port       = $port;
		$mail->SMTPSecure = $smtpsecure;
		$mail->SMTPAuth   = $smtpauth;
		$mail->SMTPDebug  = $smtpdebug;
		$mail->Username   = $username;
		$mail->Password   = $password;
		$mail->From       = $correo_rem;
 		$mail->FromName   = $desc_correo_rem;
		
		$mail->isHTML(true);
		$mail->Subject = $asunto;
		$mail->Body = $mensaje; 

		$mail->addAddress($email);

		$mail->AddAttachment($ruta_archivo, "WIP ".$info.'.pdf');

		if(!$mail->send()){
		 	return 0;
		}else{
		 	return 1;
		}
	}

	public static function emailsfichaitem($step) {
		switch ($step) {
		   	case 2:
		   		$users = FichaItemUsuario::model()->findByPk(1)->Id_Users_Notif;
		        break;
		    case 3:
		   		$users = FichaItemUsuario::model()->findByPk(2)->Id_Users_Notif;
		        break;
		    case 4:
		        $users = FichaItemUsuario::model()->findByPk(2)->Id_Users_Notif;
		        break;
		    case 5:
		        $users = FichaItemUsuario::model()->findByPk(3)->Id_Users_Notif;
		        break;
		    case 6:
				$users = FichaItemUsuario::model()->findByPk(3)->Id_Users_Notif;
		        break;
		    case 7:
				$users = FichaItemUsuario::model()->findByPk(4)->Id_Users_Notif;
		        break;
		    case 8:
		        $users = FichaItemUsuario::model()->findByPk(4)->Id_Users_Notif;
		        break;
		    case 9:
				$users = FichaItemUsuario::model()->findByPk(5)->Id_Users_Notif;
		        break;
		    case 10:
				$users = FichaItemUsuario::model()->findByPk(6)->Id_Users_Notif;
		        break; 
		}

		$q_emails = Yii::app()->db->createCommand("SELECT Correo FROM T_PR_USUARIO WHERE Id_Usuario IN (".$users.")")->queryAll();

		$lista_email = array();
		foreach ($q_emails as $e) {
			$lista_email[] = $e['Correo'];
		}

		return $lista_email;
	
	}

	public static function enviofichaitem($id, $tipo, $step, $array_emails, $obs) {

		//tipo -  0 revision, 1 avance en proceso
		//steps -  al proceso que se va a enviar el correo

		set_time_limit(0);
		
		// Se inactiva el autoloader de yii
		spl_autoload_unregister(array('YiiBase','autoload'));  

		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\PHPMailer.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\SMTP.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\Exception.php');

		//cuando se termina la accion relacionada con la libreria se activa el autoloader de yii
		spl_autoload_register(array('YiiBase','autoload'));

		$host = Yii::app()->params->env_mail_host;
		$port = Yii::app()->params->env_mail_port;
		$smtpsecure = Yii::app()->params->env_mail_smtpsecure;
		$smtpauth = Yii::app()->params->env_mail_smtpauth;
		$smtpdebug = Yii::app()->params->env_mail_smtpdebug;
		$username = Yii::app()->params->env_mail_cuenta;
		$password = Yii::app()->params->env_mail_password;
		$correo_rem = Yii::app()->params->env_mail_cuenta_rem;
		$desc_correo_rem = Yii::app()->params->env_mail_desc_cuenta_rem;

		$logo = 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::app()->getBaseUrl(true)."/images/login-logo.png", false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))));

		$url = Yii::app()->getBaseUrl(true).'/index.php?r=fichaItem/redirect&id='.$id;

		$modelo_fi = FichaItem::model()->findByPk($id);
		
		if($step == 10){
			if($modelo_fi->Tipo == 1){
				$asunto = 'Se ha creado el ítem '.$modelo_fi->Codigo_Item;
				$m = '<p>Se ha creado el ítem ('.$modelo_fi->DescTipoProducto($modelo_fi->Tipo_Producto).' / '.$modelo_fi->Codigo_Item.' - '.$modelo_fi->Descripcion_Corta.').</p>';
			}else{
				$asunto = 'Se ha actualizado el ítem '.$modelo_fi->Codigo_Item;
				$m = '<p>Se ha actualizado el ítem con Código '.$modelo_fi->Codigo_Item.'.</p>';
			}
		}else{
			if($modelo_fi->Tipo == 1){
				if($tipo == 0){
					$asunto = 'Solicitud revisión de datos para creación de ítem';
					$m = '<p>Se ha solicitado una revisión de los datos registrados para la creación del ítem ('.$modelo_fi->DescTipoProducto($modelo_fi->Tipo_Producto).' / '.$modelo_fi->Descripcion_Corta.').</p>
					<strong>Observaciones: </strong><p>'.$obs.'</p>
					<p>Haga click <a href="'.$url.'"/>aqui</a> para ver el estado de la solicitud.</p>
					<strong>Usuario que solicita: </strong><p>'.$modelo_fi->idusuarioact->Nombres.'.</p>';
				}else{
					$asunto = 'Solicitud de información para creación de ítem';
					$m = '<p>Se ha solicitado que registre / revise los datos correpondientes a la creación del ítem ('.$modelo_fi->DescTipoProducto($modelo_fi->Tipo_Producto).' / '.$modelo_fi->Descripcion_Corta.').</p>
					<p>Haga click <a href="'.$url.'"/>aqui</a> para ver el estado de la solicitud.</p>
					<strong>Usuario que solicita: </strong><p>'.$modelo_fi->idusuarioact->Nombres.'.</p>';
				}
			}else{
				if($tipo == 0){
					$asunto = 'Solicitud revisión de datos para actualización de ítem';
					$m = '<p>Se ha solicitado una revisión de los datos registrados para la actualización del ítem con Código '.$modelo_fi->Codigo_Item.'.</p>
					<strong>Observaciones: </strong><p>'.$obs.'</p>
					<p>Haga click <a href="'.$url.'"/>aqui</a> para ver el estado de la solicitud.</p>
					<strong>Usuario que solicita: </strong><p>'.$modelo_fi->idusuarioact->Nombres.'.</p>';
				}else{
					$asunto = 'Solicitud revisión de datos para actualización de ítem';
					$m = '<p>Se ha solicitado que registre / revise los datos correpondientes a la actualización del ítem con Código '.$modelo_fi->Codigo_Item.'.</p>
					<p>Haga click <a href="'.$url.'"/>aqui</a> para ver el estado de la solicitud.</p>
					<strong>Usuario que solicita: </strong><p>'.$modelo_fi->idusuarioact->Nombres.'.</p>';
				}
			}	
		}

		$mensaje = '
		<!DOCTYPE html>
		<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
		<head>
		    <meta charset="utf-8">
		    <meta name="viewport" content="width=device-width">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta name="x-apple-disable-message-reformatting">
		</head>
		<body>
		    <center>
		        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; width: 60%;">
		            <tr>
		                <td style="background-color: #ffffff;">
		                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
		                        <tr>
		                            <td style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
		                            	<img src="'.$logo.'" alt="'.CHtml::encode(Yii::app()->name).'" title="'.CHtml::encode(Yii::app()->name).'"/>
		                            	<h1 style="margin: 0 0 10px; font-size: 25px; line-height: 30px; color: #333333; font-weight: normal;">'.UtilidadesMail::horamensaje().'</h1>
		                                '.$m.'
		                                <hr>
		                            </td>
		                        </tr>
		                    </table>
		                </td>
		            </tr>
		            <tr style="padding: 40px 0; text-align: center">
		                </tr>
		        </table>
		    </center>
		</body>
		</html>
		';

		$mail = new PHPMailer\PHPMailer\PHPMailer;
		
		$mail->IsSMTP();
		$mail->CharSet = 'UTF-8';

		$mail->Host 	  = $host;
		$mail->Port       = $port;
		$mail->SMTPSecure = $smtpsecure;
		$mail->SMTPAuth   = $smtpauth;
		$mail->SMTPDebug  = $smtpdebug;
		$mail->Username   = $username;
		$mail->Password   = $password;
		$mail->From       = $correo_rem;

		$mail->isHTML(true);
		$mail->Subject = $asunto;
		$mail->Body = $mensaje;

		$num_notif = 0;

		foreach ($array_emails as $llave => $email) {
            $mail->addAddress($email);
            $num_notif++;
        }

		if(!$mail->send()){
			return 0;
		}else{
		 	return $num_notif;
		}
	
	}

	public static function enviopedidocom($id, $array_emails) {

		set_time_limit(0);
		
		// Se inactiva el autoloader de yii
		spl_autoload_unregister(array('YiiBase','autoload'));  

		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\PHPMailer.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\SMTP.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\Exception.php');

		//cuando se termina la accion relacionada con la libreria se activa el autoloader de yii
		spl_autoload_register(array('YiiBase','autoload'));

		$host = Yii::app()->params->env_mail_host;
		$port = Yii::app()->params->env_mail_port;
		$smtpsecure = Yii::app()->params->env_mail_smtpsecure;
		$smtpauth = Yii::app()->params->env_mail_smtpauth;
		$smtpdebug = Yii::app()->params->env_mail_smtpdebug;
		$username = Yii::app()->params->env_mail_cuenta;
		$password = Yii::app()->params->env_mail_password;
		$correo_rem = Yii::app()->params->env_mail_cuenta_rem;
		$desc_correo_rem = Yii::app()->params->env_mail_desc_cuenta_rem;

		$logo = 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::app()->getBaseUrl(true)."/images/login-logo.png", false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))));

		$url = Yii::app()->getBaseUrl(true).'/index.php?r=pedCom';

		$modelo_pedido = PedCom::model()->findByPk($id);

		
		$asunto = 'Se ha cargado un pedido';

		$mensaje = '
		<!DOCTYPE html>
		<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
		<head>
		    <meta charset="utf-8">
		    <meta name="viewport" content="width=device-width">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta name="x-apple-disable-message-reformatting">
		</head>
		<body>
		    <center>
		        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; width: 60%;">
		            <tr>
		                <td style="background-color: #ffffff;">
		                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
		                        <tr>
		                            <td style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
		                            	<img src="'.$logo.'" alt="'.CHtml::encode(Yii::app()->name).'" title="'.CHtml::encode(Yii::app()->name).'"/>
		                            	<h1 style="margin: 0 0 10px; font-size: 25px; line-height: 30px; color: #333333; font-weight: normal;">'.UtilidadesMail::horamensaje().'</h1>
		                                <h1 style="margin: 0 0 10px; font-size: 25px; line-height: 30px; color: #333333; font-weight: normal;">Ha solicitado el restablecimiento de password.</h1>
		                                <p>El vendedor '.$modelo_pedido->idusuario->Nombres.' ha solicitado la revisión del pedido '.$id.'.</p>
		                                <p>Haga click <a href="'.$url.'/update2&id='.$id.'"/>aqui</a> para ver la solicitud.</p>
		                                <hr>
		                            </td>
		                        </tr>
		                    </table>
		                </td>
		            </tr>
		            <tr style="padding: 40px 0; text-align: center">
		                </tr>
		        </table>
		    </center>
		</body>
		</html>
		';
		
		$mail = new PHPMailer\PHPMailer\PHPMailer;
		
		$mail->IsSMTP();
		$mail->CharSet = 'UTF-8';

		$mail->Host 	  = $host;
		$mail->Port       = $port;
		$mail->SMTPSecure = $smtpsecure;
		$mail->SMTPAuth   = $smtpauth;
		$mail->SMTPDebug  = $smtpdebug;
		$mail->Username   = $username;
		$mail->Password   = $password;
		$mail->From       = $correo_rem;

		$mail->isHTML(true);
		$mail->Subject = $asunto;
		$mail->Body = $mensaje;

		$num_notif = 0;

		foreach ($array_emails as $llave => $email) {
            $mail->addAddress($email);
            $num_notif++;
        }

		if(!$mail->send()){
			return 0;
		}else{
		 	return $num_notif;
		}
	
	}

	public static function emailssolprom($step) {
		switch ($step) {
		   	case 1:
		   		$users = SolPromUsuario::model()->findByPk(1)->Id_Users_Notif;
		        break;
		    case 2:
		   		$users = SolPromUsuario::model()->findByPk(2)->Id_Users_Notif;
		        break;
		    case 3:
		        $users = SolPromUsuario::model()->findByPk(3)->Id_Users_Notif;
		        break;
		    case 4:
		        $users = SolPromUsuario::model()->findByPk(4)->Id_Users_Notif;
		        break;
		    case 5:
				$users = SolPromUsuario::model()->findByPk(5)->Id_Users_Notif;
		        break;
		}

		$q_emails = Yii::app()->db->createCommand("SELECT Correo FROM T_PR_USUARIO WHERE Id_Usuario IN (".$users.")")->queryAll();

		$lista_email = array();
		foreach ($q_emails as $e) {
			$lista_email[] = $e['Correo'];
		}

		return $lista_email;
	
	}

	public static function enviosolprom($id, $array_emails, $obs) {

		set_time_limit(0);
		
		// Se inactiva el autoloader de yii
		spl_autoload_unregister(array('YiiBase','autoload'));  

		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\PHPMailer.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\SMTP.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\Exception.php');

		//cuando se termina la accion relacionada con la libreria se activa el autoloader de yii
		spl_autoload_register(array('YiiBase','autoload'));

		$host = Yii::app()->params->env_mail_host;
		$port = Yii::app()->params->env_mail_port;
		$smtpsecure = Yii::app()->params->env_mail_smtpsecure;
		$smtpauth = Yii::app()->params->env_mail_smtpauth;
		$smtpdebug = Yii::app()->params->env_mail_smtpdebug;
		$username = Yii::app()->params->env_mail_cuenta;
		$password = Yii::app()->params->env_mail_password;
		$correo_rem = Yii::app()->params->env_mail_cuenta_rem;
		$desc_correo_rem = Yii::app()->params->env_mail_desc_cuenta_rem;

		$logo = 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::app()->getBaseUrl(true)."/images/login-logo.png", false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))));

		$modelo_sol = SolProm::model()->findByPk($id);

		$url = Yii::app()->getBaseUrl(true).'/index.php?r=solProm/redirect&id='.$id;

	    if($modelo_sol->Estado == 4){
			
			$asunto = 'Se ha completado la solicitud';
			$m = '<p>la promoción número '.$modelo_sol->Num_Sol.' ha cambiado a estado: EN ENSAMBLE.</p>';
			
		}else{
			if($modelo_sol->Estado == 2 && $modelo_sol->Estado_Rechazo == 3){
				$asunto = 'Tiene una nueva solicitud para revisión de kit';
				$m = '<p>'.$modelo_sol->idusuarioact->Nombres.' ha solicitado la revisión del número de promoción '.$modelo_sol->Num_Sol.'.</p>
				<strong>Observaciones: </strong><p>'.$obs.'.</p>
				<p>Haga click <a href="'.$url.'"/>aqui</a> para ver la solicitud.</p>';	

				$modelo_sol->Estado_Rechazo = null;
				$modelo_sol->save();
			}else{
				$asunto = 'Tiene una nueva solicitud para revisión de kit';
				$m = '<p>'.$modelo_sol->idusuarioact->Nombres.' ha solicitado la revisión del número de promoción '.$modelo_sol->Num_Sol.'.</p>
				<p>Haga click <a href="'.$url.'"/>aqui</a> para ver la solicitud.</p>';
			}

		}

		$mensaje = '
		<!DOCTYPE html>
		<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
		<head>
		    <meta charset="utf-8">
		    <meta name="viewport" content="width=device-width">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta name="x-apple-disable-message-reformatting">
		</head>
		<body>
		    <center>
		        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; width: 60%;">
		            <tr>
		                <td style="background-color: #ffffff;">
		                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
		                        <tr>
		                            <td style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
		                            	<img src="'.$logo.'" alt="'.CHtml::encode(Yii::app()->name).'" title="'.CHtml::encode(Yii::app()->name).'"/>
		                            	<h1 style="margin: 0 0 10px; font-size: 25px; line-height: 30px; color: #333333; font-weight: normal;">'.UtilidadesMail::horamensaje().'</h1>
		                                '.$m.'
		                                <hr>
		                            </td>
		                        </tr>
		                    </table>
		                </td>
		            </tr>
		            <tr style="padding: 40px 0; text-align: center">
		                </tr>
		        </table>
		    </center>
		</body>
		</html>
		';
		
		$mail = new PHPMailer\PHPMailer\PHPMailer;
		
		$mail->IsSMTP();
		$mail->CharSet = 'UTF-8';

		$mail->Host 	  = $host;
		$mail->Port       = $port;
		$mail->SMTPSecure = $smtpsecure;
		$mail->SMTPAuth   = $smtpauth;
		$mail->SMTPDebug  = $smtpdebug;
		$mail->Username   = $username;
		$mail->Password   = $password;
		$mail->From       = $correo_rem;
		
		$mail->isHTML(true);
		$mail->Subject = $asunto;
		$mail->Body = $mensaje;

		$num_notif = 0;

		foreach ($array_emails as $llave => $email) {
            $mail->addAddress($email);
            $num_notif++;
        }

		if(!$mail->send()){
			return 0;
		}else{
		 	return $num_notif;
		}
	
	}

	public static function envioresetpassword($id, $correo) {

		set_time_limit(0);
		
		// Se inactiva el autoloader de yii
		spl_autoload_unregister(array('YiiBase','autoload'));  

		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\PHPMailer.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\SMTP.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\Exception.php');

		//cuando se termina la accion relacionada con la libreria se activa el autoloader de yii
		spl_autoload_register(array('YiiBase','autoload'));

		$host = Yii::app()->params->env_mail_host;
		$port = Yii::app()->params->env_mail_port;
		$smtpsecure = Yii::app()->params->env_mail_smtpsecure;
		$smtpauth = Yii::app()->params->env_mail_smtpauth;
		$smtpdebug = Yii::app()->params->env_mail_smtpdebug;
		$username = Yii::app()->params->env_mail_cuenta;
		$password = Yii::app()->params->env_mail_password;
		$correo_rem = Yii::app()->params->env_mail_cuenta_rem;
		$desc_correo_rem = Yii::app()->params->env_mail_desc_cuenta_rem;

		$logo = 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::app()->getBaseUrl(true)."/images/login-logo.png", false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))));

		$modelo_sol = SolPassUsuario::model()->findByPk($id);

		$url = Yii::app()->getBaseUrl(true).'/index.php?r=usuario/resetpassword&token='.base64_encode($id);
			
		$asunto = 'Recuperación de password '.CHtml::encode(Yii::app()->name);

		$mensaje = '
		<!DOCTYPE html>
		<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
		<head>
		    <meta charset="utf-8">
		    <meta name="viewport" content="width=device-width">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta name="x-apple-disable-message-reformatting">
		</head>
		<body>
		    <center>
		        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; width: 60%;">
		            <tr>
		                <td style="background-color: #ffffff;">
		                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
		                        <tr>
		                            <td style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
		                            	<img src="'.$logo.'" alt="'.CHtml::encode(Yii::app()->name).'" title="'.CHtml::encode(Yii::app()->name).'"/>
		                            	<h1 style="margin: 0 0 10px; font-size: 25px; line-height: 30px; color: #333333; font-weight: normal;">'.UtilidadesMail::horamensaje().'</h1>
		                                <h1 style="margin: 0 0 10px; font-size: 25px; line-height: 30px; color: #333333; font-weight: normal;">Ha solicitado el restablecimiento de password.</h1>
		                                <p>Haga click <a href="'.$url.'"/>aqui</a> para continuar con el proceso, este link tiene 15 minutos de validez.</p>
		                                <hr>
		                            </td>
		                        </tr>
		                    </table>
		                </td>
		            </tr>
		            <tr style="padding: 40px 0; text-align: center">
		                </tr>
		        </table>
		    </center>
		</body>
		</html>
		';				
		
		$mail = new PHPMailer\PHPMailer\PHPMailer;
		
		$mail->IsSMTP();
		$mail->CharSet = 'UTF-8';

		$mail->Host 	  = $host;
		$mail->Port       = $port;
		$mail->SMTPSecure = $smtpsecure;
		$mail->SMTPAuth   = $smtpauth;
		$mail->SMTPDebug  = $smtpdebug;
		$mail->Username   = $username;
		$mail->Password   = $password;
		$mail->From       = $correo_rem;
		
		$mail->isHTML(true);
		$mail->Subject = $asunto;
		$mail->Body = $mensaje;
		
        $mail->addAddress($correo);

        if(!$mail->send()){
			return 0;
		}else{
		 	return 1;
		}
        
	}

	public static function enviocalificacionticketcerrado($id, $correo) {

		set_time_limit(0);
		
		// Se inactiva el autoloader de yii
		spl_autoload_unregister(array('YiiBase','autoload'));  

		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\PHPMailer.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\SMTP.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\Exception.php');

		//cuando se termina la accion relacionada con la libreria se activa el autoloader de yii
		spl_autoload_register(array('YiiBase','autoload'));

		$host = Yii::app()->params->env_mail_host;
		$port = Yii::app()->params->env_mail_port;
		$smtpsecure = Yii::app()->params->env_mail_smtpsecure;
		$smtpauth = Yii::app()->params->env_mail_smtpauth;
		$smtpdebug = Yii::app()->params->env_mail_smtpdebug;
		$username = Yii::app()->params->env_mail_cuenta;
		$password = Yii::app()->params->env_mail_password;
		$correo_rem = Yii::app()->params->env_mail_cuenta_rem;
		$desc_correo_rem = Yii::app()->params->env_mail_desc_cuenta_rem;

		$logo = 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::app()->getBaseUrl(true)."/images/login-logo.png", false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))));

		$ticket = Ticket::model()->findByPk($id);

		$desc_caso = $ticket->Solicitud;

		$por_mejorar = 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::app()->getBaseUrl(true)."/images/1.png", false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))));
		$url_por_mejorar = Yii::app()->getBaseUrl(true).'/index.php?r=ticket/fticket&id='.$id.'&c=1';

		$neutro = 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::app()->getBaseUrl(true)."/images/2.png", false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))));
		$url_neutro = Yii::app()->getBaseUrl(true).'/index.php?r=ticket/fticket&id='.$id.'&c=2';


		$bueno = 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::app()->getBaseUrl(true)."/images/3.png", false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))));
		$url_bueno = Yii::app()->getBaseUrl(true).'/index.php?r=ticket/fticket&id='.$id.'&c=3';


		if($ticket->Notas != ""){ $notas = $ticket->Notas; }else{ $notas="-"; }

		$url = Yii::app()->getBaseUrl(true).'/index.php?r=ticket/cticket&token='.base64_encode($id);
			
		$asunto = 'El ticket ( ID '.$id.' ) ha sido cerrado, por favor califica el servicio';
		$mensaje = '
		<!DOCTYPE html>
		<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
		<head>
		    <meta charset="utf-8">
		    <meta name="viewport" content="width=device-width">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta name="x-apple-disable-message-reformatting">
		</head>
		<body>
		    <center>
		        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; width: 60%;">
		            <tr>
		                <td style="background-color: #ffffff;">
		                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
		                        <tr>
		                            <td style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
		                            	<img src="'.$logo.'" alt="'.CHtml::encode(Yii::app()->name).'" title="'.CHtml::encode(Yii::app()->name).'"/>
		                                <h1 style="margin: 0 0 10px; font-size: 25px; line-height: 30px; color: #333333; font-weight: normal;">'.UtilidadesMail::horamensaje().'</h1>
		                                <h1 style="margin: 0 0 10px; font-size: 25px; line-height: 30px; color: #333333; font-weight: normal;">Califica nuestro servicio.</h1>
		                                <p>El ticket ( ID '.$id.' ) ha sido cerrado:</p>
		                                <p>A continuación encontrara la descripción del caso, notas adicionales y una encuesta para calificar el servicio: </p><br>
		                                <strong>Descripción del caso: </strong><p>'.$desc_caso.'</p>
		                                <strong>Notas: </strong><p>'.$notas.'</p>
		                                <hr>
		                                <p>Seleccione o haga click en el emoji para calificar este servicio:</p>
		                                <a href="'.$url_por_mejorar.'"/><img src="'.$por_mejorar.'" alt="POR MEJORAR" title="POR MEJORAR"/></a>
									    <a href="'.$url_neutro.'"/><img src="'.$neutro.'" alt="NEUTRO" title="NEUTRO"/></a>
									    <a href="'.$url_bueno.'"/><img src="'.$bueno.'" alt="BUENO" title="BUENO"/></a>
		                            </td>
		                        </tr>
		                    </table>
		                </td>
		            </tr>
		            <tr style="padding: 40px 0; text-align: center">
		                </tr>
		        </table>
		    </center>
		</body>
		</html>
		';		
		
		$mail = new PHPMailer\PHPMailer\PHPMailer;
		
		$mail->IsSMTP();
		$mail->CharSet = 'UTF-8';

		$mail->Host 	  = $host;
		$mail->Port       = $port;
		$mail->SMTPSecure = $smtpsecure;
		$mail->SMTPAuth   = $smtpauth;
		$mail->SMTPDebug  = $smtpdebug;
		$mail->Username   = $username;
		$mail->Password   = $password;
		$mail->From       = $correo_rem;
		
		$mail->isHTML(true);
		$mail->Subject = $asunto;

		$mail->Body = $mensaje;
		$mail->IsHTML(true);
		
        $mail->addAddress($correo);

        if(!$mail->send()){
			return 0;
		}else{
		 	return 1;
		}
        
	}

	public static function envionotifemisionproducto($id, $id_user, $correo) {

		set_time_limit(0);
		
		// Se inactiva el autoloader de yii
		spl_autoload_unregister(array('YiiBase','autoload'));  

		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\PHPMailer.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\SMTP.php');
		require_once(Yii::app()->basePath . '\extensions\PHPMailer\src\Exception.php');

		//cuando se termina la accion relacionada con la libreria se activa el autoloader de yii
		spl_autoload_register(array('YiiBase','autoload'));

		$host = Yii::app()->params->env_mail_host;
		$port = Yii::app()->params->env_mail_port;
		$smtpsecure = Yii::app()->params->env_mail_smtpsecure;
		$smtpauth = Yii::app()->params->env_mail_smtpauth;
		$smtpdebug = Yii::app()->params->env_mail_smtpdebug;
		$username = Yii::app()->params->env_mail_cuenta;
		$password = Yii::app()->params->env_mail_password;
		$correo_rem = Yii::app()->params->env_mail_cuenta_rem;
		$desc_correo_rem = Yii::app()->params->env_mail_desc_cuenta_rem;

		$logo = 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::app()->getBaseUrl(true)."/images/login-logo.png", false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false)))));

		$modelo_emision = EmProd::model()->findByPk($id);

		$ruta_doc = Yii::app()->basePath.'/../files/portal_reportes/emision_prod/'.$modelo_emision->Documento;

		$url = Yii::app()->getBaseUrl(true).'/index.php?r=emprod/viewdoc&id='.$id.'&u='.$id_user;
			
		$asunto = 'Se ha cargado una nueva emisión de producto '.CHtml::encode(Yii::app()->name);

		$mensaje = '
		<!DOCTYPE html>
		<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
		<head>
		    <meta charset="utf-8">
		    <meta name="viewport" content="width=device-width">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta name="x-apple-disable-message-reformatting">
		</head>
		<body>
		    <center>
		        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; width: 60%;">
		            <tr>
		                <td style="background-color: #ffffff;">
		                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
		                        <tr>
		                            <td style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
		                            	<img src="'.$logo.'" alt="'.CHtml::encode(Yii::app()->name).'" title="'.CHtml::encode(Yii::app()->name).'"/>
		                            	<h1 style="margin: 0 0 10px; font-size: 25px; line-height: 30px; color: #333333; font-weight: normal;">'.UtilidadesMail::horamensaje().'</h1>
		                                <p>Se Adjunta documento PDF con detalle de emisión de producto (ID '.$id.').</p>
		                                <p>Haga click <a href="'.$url.'"/>aqui</a> para marcar el documento como visto.</p>
		                                <hr>
		                            </td>
		                        </tr>
		                    </table>
		                </td>
		            </tr>
		            <tr style="padding: 40px 0; text-align: center">
		                </tr>
		        </table>
		    </center>
		</body>
		</html>
		';				
		
		$mail = new PHPMailer\PHPMailer\PHPMailer;
		
		$mail->IsSMTP();
		$mail->CharSet = 'UTF-8';

		$mail->Host 	  = $host;
		$mail->Port       = $port;
		$mail->SMTPSecure = $smtpsecure;
		$mail->SMTPAuth   = $smtpauth;
		$mail->SMTPDebug  = $smtpdebug;
		$mail->Username   = $username;
		$mail->Password   = $password;
		$mail->From       = $correo_rem;
		
		$mail->isHTML(true);
		$mail->Subject = $asunto;
		$mail->Body = $mensaje;
		
        $mail->addAddress($correo);

        $mail->AddAttachment($ruta_doc, "Emision Producto ".$id.'.pdf');

        if(!$mail->send()){
			return 0;
		}else{
		 	return 1;
		}
        
	}

}