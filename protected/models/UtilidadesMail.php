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

			$asunto = "Se ha subido un nuevo documento al repositorio";
			
			$mensaje = "A continuación se listaran los datos correspondientes al documento: <br><br>
			<strong>Áreas asociadas: </strong>".$areas."<br>
			<strong>Clasificación: </strong>".$clasif."<br>
			<strong>Tipo: </strong>".$tipo."<br>
			<strong>N° documento: </strong>".$num_doc."<br>
			<strong>Nombre: </strong>".$nombre."<br>
			<strong>Descripción: </strong>".$descripcion."<br>
			<strong>Elaborado por: </strong>".$elaborado_por."<br>
			<strong>Fecha de elaboración: </strong>".$fecha_elaboracion."<br>
			<strong>Nivel de revisión: </strong>".$n_r."<br>
			<strong>Fecha de revisión: </strong>".$fecha_revision."<br>
			<strong>Emitido por: </strong>".$emitido_por."<br>
			<strong>Fecha de emisión: </strong>".$fecha_emision."<br>
			<strong>Aprobado por: </strong>".$aprobado_por."<br>
			<strong>Permitir descarga: </strong>".$pd."<br>
			<strong>Copia controlada: </strong>".$cc."<br>
			<strong>Usuario que creo: </strong>".$usuario_cre."<br>
			<strong>Fecha de creación: </strong>".$fecha_cre."<br>";

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
		
		$mensaje = UtilidadesMail::horamensaje()."<br><br>
		Se Adjunta documento PDF con detalle de comisión.<br>";

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

		$modelo_wip = Wip::model()->findByPk($id);

		$info = $modelo_wip->WIP;

		$asunto = "Detalle WIP ".$info;
		
		$mensaje = UtilidadesMail::horamensaje()."<br><br>
		Se Adjunta documento PDF con detalle de WIP.<br>";

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

		$url = Yii::app()->getBaseUrl(true).'/index.php?r=fichaItem/redirect&id='.$id;

		$modelo_fi = FichaItem::model()->findByPk($id);
		
		if($step == 10){
			if($modelo_fi->Tipo == 1){
				$asunto = "Se ha creado el ítem ".$modelo_fi->Codigo_Item;
				$mensaje = UtilidadesMail::horamensaje()."<br><br>
				Se ha creado el ítem (".$modelo_fi->DescTipoProducto($modelo_fi->Tipo_Producto)." / ".$modelo_fi->Codigo_Item." - ".$modelo_fi->Descripcion_Corta.").<br><br>";
			}else{
				$asunto = "Se ha actualizado el ítem ".$modelo_fi->Codigo_Item;
				$mensaje = UtilidadesMail::horamensaje()."<br><br>
				Se ha actualizado el ítem con Código ".$modelo_fi->Codigo_Item.".<br><br>";
			}
		}else{
			if($modelo_fi->Tipo == 1){
				if($tipo == 0){
					$asunto = 'Solicitud revisión de datos para creación de ítem';
					$mensaje = UtilidadesMail::horamensaje().'<br><br>
					Se ha solicitado una revisión de los datos registrados para la creación del ítem ('.$modelo_fi->DescTipoProducto($modelo_fi->Tipo_Producto).' / '.$modelo_fi->Descripcion_Corta.').<br><br>
					Observaciones: '.$obs.'<br><br>
					Pulse <a href="'.$url.'"/>aqui</a> para ver el estado de la solicitud.<br><br>
					Usuario que solicita: '.$modelo_fi->idusuarioact->Nombres.'.';
				}else{
					$asunto = 'Solicitud de información para creación de ítem';
					$mensaje = UtilidadesMail::horamensaje().'<br><br>
					Se ha solicitado que registre / revise los datos correpondientes a la creación del ítem ('.$modelo_fi->DescTipoProducto($modelo_fi->Tipo_Producto).' / '.$modelo_fi->Descripcion_Corta.').<br><br>
					Pulse <a href="'.$url.'"/>aqui</a> para ver el estado de la solicitud.<br><br>
					Usuario que solicita: '.$modelo_fi->idusuarioact->Nombres.'.';
				}
			}else{
				if($tipo == 0){
					$asunto = 'Solicitud revisión de datos para actualización de ítem';
					$mensaje = UtilidadesMail::horamensaje().'<br><br>
					Se ha solicitado una revisión de los datos registrados para la actualización del ítem con Código '.$modelo_fi->Codigo_Item.'.<br><br>
					Observaciones: '.$obs.'<br><br>
					Pulse <a href="'.$url.'"/>aqui</a> para ver el estado de la solicitud.<br><br>
					Usuario que solicita: '.$modelo_fi->idusuarioact->Nombres.'.';
				}else{
					$asunto = 'Solicitud revisión de datos para actualización de ítem';
					$mensaje = UtilidadesMail::horamensaje().'<br><br>
					Se ha solicitado que registre / revise los datos correpondientes a la actualización del ítem con Código '.$modelo_fi->Codigo_Item.'.<br><br>
					Pulse <a href="'.$url.'"/>aqui</a> para ver el estado de la solicitud.<br><br>
					Usuario que solicita: '.$modelo_fi->idusuarioact->Nombres.'.';
				}
			}
		}

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

		$url = Yii::app()->getBaseUrl(true).'/index.php?r=pedCom';

		$modelo_pedido = PedCom::model()->findByPk($id);

		
		$asunto = 'Se ha cargado un pedido';
		$mensaje = UtilidadesMail::horamensaje().'<br><br>
		El vendedor '.$modelo_pedido->idusuario->Nombres.' ha solicitado la revisión del pedido '.$id.'.<br><br>
		Pulse <a href="'.$url.'/update2&id='.$id.'"/>aqui</a> para ver la solicitud.';
		
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

		$modelo_sol = SolProm::model()->findByPk($id);

		$url = Yii::app()->getBaseUrl(true).'/index.php?r=solProm/redirect&id='.$id;

	    if($modelo_sol->Estado == 4){
			
			$asunto = "Se ha completado la solicitud";
			$mensaje = UtilidadesMail::horamensaje()."<br><br>
			la promoción número ".$modelo_sol->Num_Sol." ha cambiado a estado: EN ENSAMBLE.<br><br>";
			
		}else{
			if($modelo_sol->Estado == 2 && $modelo_sol->Estado_Rechazo == 3){
				$asunto = 'Tiene una nueva solicitud para revisión de kit';
				$mensaje = UtilidadesMail::horamensaje().'<br><br>
				'.$modelo_sol->idusuarioact->Nombres.' ha solicitado la revisión del número de promoción '.$modelo_sol->Num_Sol.'.<br><br>
				Observaciones: '.$obs.'.<br><br>
				Pulse <a href="'.$url.'"/>aqui</a> para ver la solicitud.';	

				$modelo_sol->Estado_Rechazo = null;
				$modelo_sol->save();
			}else{
				$asunto = 'Tiene una nueva solicitud para revisión de kit';
				$mensaje = UtilidadesMail::horamensaje().'<br><br>
				'.$modelo_sol->idusuarioact->Nombres.' ha solicitado la revisión del número de promoción '.$modelo_sol->Num_Sol.'.<br><br>
				Pulse <a href="'.$url.'"/>aqui</a> para ver la solicitud.';	
			}

		}
		
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

		$modelo_sol = SolPassUsuario::model()->findByPk($id);

		$url = Yii::app()->getBaseUrl(true).'/index.php?r=usuario/resetpassword&token='.base64_encode($id);
			
		$asunto = 'Recuperación de password '.CHtml::encode(Yii::app()->name);
		$mensaje = UtilidadesMail::horamensaje().'<br><br>
		ha solicitado el restablecimiento de password.<br><br>
		Pulse <a href="'.$url.'"/>aqui</a> para continuar con el proceso, este link tiene 15 minutos de validez.';		
		
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

}