<?php

//clase creada para funciones relacionadas con el modelo de cuenta

class UtilidadesCuenta {
   
	public static function novedadcuenta($id, $password_act, $password_nue, $observaciones_act, $observaciones_nue, $tipo_cuenta_act, $tipo_cuenta_nue, $tipo_acceso_act, $tipo_acceso_nue, $estado_act, $estado_nue, $ext_act, $ext_nue){

		$texto_novedad = "";
		$flag = 0;

		if($password_act != $password_nue){
			$flag = 1;

			$texto_novedad .= "Password: ".$password_act." / ".$password_nue.", ";
		}

		if($observaciones_act != $observaciones_nue){
			$flag = 1;

			if($observaciones_act == ''){
				$observaciones_act = 'No asignado';
			}

			if($observaciones_nue == ''){
				$observaciones_nue = 'No asignado';
			}

			$texto_novedad .= "Observaciones: ".$observaciones_act." / ".$observaciones_nue.", ";
		}

		if($tipo_cuenta_act != $tipo_cuenta_nue){
			$flag = 1;	

			$n_tipo_cuenta_act = Dominio::model()->findByPk($tipo_cuenta_act);
			$n_tipo_cuenta_nue = Dominio::model()->findByPk($tipo_cuenta_nue);

			$texto_novedad .= "Tipo de cuenta: ".$n_tipo_cuenta_act->Dominio." / ".$n_tipo_cuenta_nue->Dominio.", ";
		}

		if($tipo_acceso_act != $tipo_acceso_nue){
			$flag = 1;

			if($tipo_acceso_act == 1){
				$n_tipo_acceso_act = 'GENÉRICO';	
			}

			if($tipo_acceso_act == 2){
				$n_tipo_acceso_act = 'PERSONAL';
			}

			if($tipo_acceso_nue == 1){
				$n_tipo_acceso_nue = 'GENÉRICO';
			}

			if($tipo_acceso_nue == 2){
				$n_tipo_acceso_nue = 'PERSONAL';
			}


			$texto_novedad .= "Tipo de acceso: ".$n_tipo_acceso_act." / ".$n_tipo_acceso_nue.", ";
		}

		if($estado_act != $estado_nue){
			$flag = 1;	

			$n_estado_act = Dominio::model()->findByPk($estado_act);
			$n_estado_nue = Dominio::model()->findByPk($estado_nue);

			$texto_novedad .= "Estado: ".$n_estado_act->Dominio." / ".$n_estado_nue->Dominio.", ";
		}
		
		if($ext_act != $ext_nue){
			$flag = 1;

			if($ext_act == ''){
				$ext_act = 'No asignado';
			}

			if($ext_nue == ''){
				$ext_nue = 'No asignado';
			}

			$texto_novedad .= "Extensión: ".$ext_act." / ".$ext_nue.", ";
		}

		

		//alguno de los criterios cambio
		if($flag == 1){
			$texto_novedad = substr ($texto_novedad, 0, -2);
			$nueva_novedad = new NovedadCuenta;
			$nueva_novedad->Id_Cuenta = $id;
			$nueva_novedad->Novedades = $texto_novedad;
			$nueva_novedad->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$nueva_novedad->Fecha_Creacion = date('Y-m-d H:i:s');
			$nueva_novedad->save();
		}
	}

	public static function generateRandomString() {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i <= 4; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

}
