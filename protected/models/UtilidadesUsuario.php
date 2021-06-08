<?php

//clase creada para funciones relacionadas con el modelo de usuario

class UtilidadesUsuario {
   
	public static function adminperfilusuario($id_user, $array) {
		$array_per_selec = array();
		foreach ($array as $clave => $valor) {
		    
		    //se busca el registro para saber si tiene que ser creado 
		    $criteria=new CDbCriteria;
			$criteria->condition='Id_Usuario=:Id_Usuario AND Id_Perfil=:Id_Perfil';
			$criteria->params=array(':Id_Usuario'=>$id_user,':Id_Perfil'=>$valor);
			$modelo_perfil_usuario=PerfilUsuario::model()->find($criteria);

			if(!is_null($modelo_perfil_usuario)){
				//si el estado es inactivo se cambia a activo, si ya esta activo no se realiza ninguna acción
				if($modelo_perfil_usuario->Estado == 0){
					$modelo_perfil_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$modelo_perfil_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$modelo_perfil_usuario->Estado = 1;
					if($modelo_perfil_usuario->save()){
						array_push($array_per_selec, intval($valor));
					}	
				}else{
					array_push($array_per_selec, intval($valor));	
				}
			}else{
				//se debe insertar un nuevo registro en la tabla
				$nuevo_perfil_usuario = new PerfilUsuario;
			    $nuevo_perfil_usuario->Id_Usuario = $id_user;
			    $nuevo_perfil_usuario->Id_Perfil = $valor;
				$nuevo_perfil_usuario->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$nuevo_perfil_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$nuevo_perfil_usuario->Fecha_Creacion = date('Y-m-d H:i:s');
				$nuevo_perfil_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$nuevo_perfil_usuario->Estado = 1;
				if($nuevo_perfil_usuario->save()){
					array_push($array_per_selec, intval($valor));
				}
			}
		}

		//se inactivan los perfiles que no vienen en el array
		$perfiles_excluidos = implode(",",$array_per_selec);
		$pe = str_replace("'", "", $perfiles_excluidos);
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Usuario=:Id_Usuario AND Id_Perfil NOT IN ('.$pe.')';
		$criteria->params=array(':Id_Usuario'=>$id_user);
		$modelo_perfil_usuario_inactivar=PerfilUsuario::model()->findAll($criteria);
		if(!is_null($modelo_perfil_usuario_inactivar)){
			foreach ($modelo_perfil_usuario_inactivar as $perfiles_inactivar) {
				//si el estado es activo se cambia a inactivo, si ya esta inactivo no se realiza ninguna acción
				if($perfiles_inactivar->Estado == 1){
					$perfiles_inactivar->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$perfiles_inactivar->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$perfiles_inactivar->Estado = 0;
					$perfiles_inactivar->save();
				}	
			}
		}
	}

	public static function perfilesactivos($id_user) {
		//opciones activas en el combo perfiles
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Usuario=:Id_Usuario AND Estado=:Estado';
		$criteria->params=array(':Id_Usuario'=>$id_user,':Estado'=> 1);
		$array_per_activos = array();
		$perfiles_activos=PerfilUsuario::model()->findAll($criteria);
		foreach ($perfiles_activos as $perf_act) {
			array_push($array_per_activos, $perf_act->Id_Perfil);
		}

		return json_encode($array_per_activos);
	}

	public static function adminempresausuario($id_user, $array) {
		$array_emp_selec = array();
		if(!empty($array)){
			foreach ($array as $clave => $valor) {
			    
			    //se busca el registro para saber si tiene que ser creado 
			    $criteria=new CDbCriteria;
				$criteria->condition='Id_Usuario=:Id_Usuario AND Id_Empresa=:Id_Empresa';
				$criteria->params=array(':Id_Usuario'=>$id_user,':Id_Empresa'=>$valor);
				$modelo_empresa_usuario=EmpresaUsuario::model()->find($criteria);

				if(!is_null($modelo_empresa_usuario)){
					//si el estado es inactivo se cambia a activo, si ya esta activo no se realiza ninguna acción
					if($modelo_empresa_usuario->Estado == 0){
						$modelo_empresa_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$modelo_empresa_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$modelo_empresa_usuario->Estado = 1;
						if($modelo_empresa_usuario->save()){
							array_push($array_emp_selec, intval($valor));
						}	
					}else{
						array_push($array_emp_selec, intval($valor));	
					}
				}else{
					//se debe insertar un nuevo registro en la tabla
					$nuevo_empresa_usuario = new EmpresaUsuario;
				    $nuevo_empresa_usuario->Id_Usuario = $id_user;
				    $nuevo_empresa_usuario->Id_Empresa = $valor;
					$nuevo_empresa_usuario->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
					$nuevo_empresa_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$nuevo_empresa_usuario->Fecha_Creacion = date('Y-m-d H:i:s');
					$nuevo_empresa_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$nuevo_empresa_usuario->Estado = 1;
					if($nuevo_empresa_usuario->save()){
						array_push($array_emp_selec, intval($valor));
					}
				}
			}

			//se inactivan las empresas que no vienen en el array
			$empresas_excluidas = implode(",",$array_emp_selec);
			$ee = str_replace("'", "", $empresas_excluidas);

			$criteria=new CDbCriteria;
			$criteria->condition='Id_Usuario=:Id_Usuario AND Id_Empresa NOT IN ('.$ee.')';
			$criteria->params=array(':Id_Usuario'=>$id_user);
			$modelo_empresa_usuario_inactivar=EmpresaUsuario::model()->findAll($criteria);
			if(!is_null($modelo_empresa_usuario_inactivar)){
				foreach ($modelo_empresa_usuario_inactivar as $empresas_inactivar) {
					//si el estado es activo se cambia a inactivo, si ya esta inactivo no se realiza ninguna acción
					if($empresas_inactivar->Estado == 1){
						$empresas_inactivar->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$empresas_inactivar->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$empresas_inactivar->Estado = 0;
						$empresas_inactivar->save();
					}	
				}
			}
		}else{
			//si el array llega vacio se inactivan todos los registros que esten activos 
		    $criteria=new CDbCriteria;
			$criteria->condition='Id_Usuario=:Id_Usuario AND Estado = 1';
			$criteria->params=array(':Id_Usuario'=>$id_user);
			$modelo_empresa_usuario=EmpresaUsuario::model()->findAll($criteria);
			if(!is_null($modelo_empresa_usuario)){
				foreach ($modelo_empresa_usuario as $empresas_act) {
					$empresas_act->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$empresas_act->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$empresas_act->Estado = 0;
					$empresas_act->save();
		
				}
			}		
		}
	}

	public static function empresasactivas($id_user) {
		//opciones activas en el combo empresas
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Usuario=:Id_Usuario AND Estado=:Estado';
		$criteria->params=array(':Id_Usuario'=>$id_user,':Estado'=> 1);
		$array_emp_activas = array();
		$empresas_activas=EmpresaUsuario::model()->findAll($criteria);
		foreach ($empresas_activas as $empr_act) {
			array_push($array_emp_activas, $empr_act->Id_Empresa);
		}

		return json_encode($array_emp_activas);
	}

	public static function adminareausuario($id_user, $array) {
		$array_areas_selec = array();
		if(!empty($array)){

			foreach ($array as $clave => $valor) {
			    
			    //se busca el registro para saber si tiene que ser creado 
			    $criteria=new CDbCriteria;
				$criteria->condition='Id_Usuario=:Id_Usuario AND Id_Area=:Id_Area';
				$criteria->params=array(':Id_Usuario'=>$id_user,':Id_Area'=>$valor);
				$modelo_area_usuario=AreaUsuario::model()->find($criteria);

				if(!is_null($modelo_area_usuario)){
					//si el estado es inactivo se cambia a activo, si ya esta activo no se realiza ninguna acción
					if($modelo_area_usuario->Estado == 0){
						$modelo_area_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$modelo_area_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$modelo_area_usuario->Estado = 1;
						if($modelo_area_usuario->save()){
							array_push($array_areas_selec, intval($valor));
						}	
					}else{
						array_push($array_areas_selec, intval($valor));	
					}
				}else{
					//se debe insertar un nuevo registro en la tabla
					$nueva_area_usuario = new AreaUsuario;
				    $nueva_area_usuario->Id_Usuario = $id_user;
				    $nueva_area_usuario->Id_Area = $valor;
					$nueva_area_usuario->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
					$nueva_area_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$nueva_area_usuario->Fecha_Creacion = date('Y-m-d H:i:s');
					$nueva_area_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$nueva_area_usuario->Estado = 1;
					if($nueva_area_usuario->save()){
						array_push($array_areas_selec, intval($valor));
					}
				}
			}

			//se inactivan las areas que no vienen en el array
			$areas_excluidas = implode(",",$array_areas_selec);
			$ae = str_replace("'", "", $areas_excluidas);

			$criteria=new CDbCriteria;
			$criteria->condition='Id_Usuario=:Id_Usuario AND Id_Area NOT IN ('.$ae.')';
			$criteria->params=array(':Id_Usuario'=>$id_user);
			$modelo_area_usuario_inactivar=AreaUsuario::model()->findAll($criteria);
			if(!is_null($modelo_area_usuario_inactivar)){
				foreach ($modelo_area_usuario_inactivar as $areas_inactivar) {
					//si el estado es activo se cambia a inactivo, si ya esta inactivo no se realiza ninguna acción
					if($areas_inactivar->Estado == 1){
						$areas_inactivar->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$areas_inactivar->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$areas_inactivar->Estado = 0;
						$areas_inactivar->save();
					}	
				}
			}
		}else{
			//si el array llega vacio se inactivan todos los registros que esten activos 
		    $criteria=new CDbCriteria;
			$criteria->condition='Id_Usuario=:Id_Usuario AND Estado = 1';
			$criteria->params=array(':Id_Usuario'=>$id_user);
			$modelo_area_usuario=AreaUsuario::model()->findAll($criteria);
			if(!is_null($modelo_area_usuario)){
				foreach ($modelo_area_usuario as $areas_act) {
					$areas_act->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$areas_act->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$areas_act->Estado = 0;
					$areas_act->save();
		
				}
			}
		}
	}

	public static function areasactivas($id_user) {
		//opciones activas en el combo áreas
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Usuario=:Id_Usuario AND Estado=:Estado';
		$criteria->params=array(':Id_Usuario'=>$id_user,':Estado'=> 1);
		$array_ar_activas = array();
		$areas_activas=AreaUsuario::model()->findAll($criteria);
		foreach ($areas_activas as $area_act) {
			array_push($array_ar_activas, $area_act->Id_Area);
		}

		return json_encode($array_ar_activas);
	}

	public static function adminsubareausuario($id_user, $array) {
		$array_subareas_selec = array();
		if(!empty($array)){

			foreach ($array as $clave => $valor) {
			    
			    //se busca el registro para saber si tiene que ser creado 
			    $criteria=new CDbCriteria;
				$criteria->condition='Id_Usuario=:Id_Usuario AND Id_Subarea=:Id_Subarea';
				$criteria->params=array(':Id_Usuario'=>$id_user,':Id_Subarea'=>$valor);
				$modelo_subarea_usuario=SubareaUsuario::model()->find($criteria);

				if(!is_null($modelo_subarea_usuario)){
					//si el estado es inactivo se cambia a activo, si ya esta activo no se realiza ninguna acción
					if($modelo_subarea_usuario->Estado == 0){
						$modelo_subarea_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$modelo_subarea_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$modelo_subarea_usuario->Estado = 1;
						if($modelo_subarea_usuario->save()){
							array_push($array_subareas_selec, intval($valor));
						}	
					}else{
						array_push($array_subareas_selec, intval($valor));	
					}
				}else{
					//se debe insertar un nuevo registro en la tabla
					$nueva_subarea_usuario = new SubareaUsuario;
				    $nueva_subarea_usuario->Id_Usuario = $id_user;
				    $nueva_subarea_usuario->Id_Subarea = $valor;
					$nueva_subarea_usuario->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
					$nueva_subarea_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$nueva_subarea_usuario->Fecha_Creacion = date('Y-m-d H:i:s');
					$nueva_subarea_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$nueva_subarea_usuario->Estado = 1;
					if($nueva_subarea_usuario->save()){
						array_push($array_subareas_selec, intval($valor));
					}
				}
			}

			//se inactivan las areas que no vienen en el array
			$subareas_excluidas = implode(",",$array_subareas_selec);
			$se = str_replace("'", "", $subareas_excluidas);

			$criteria=new CDbCriteria;
			$criteria->condition='Id_Usuario=:Id_Usuario AND Id_Subarea NOT IN ('.$se.')';
			$criteria->params=array(':Id_Usuario'=>$id_user);
			$modelo_subarea_usuario_inactivar=SubareaUsuario::model()->findAll($criteria);
			if(!is_null($modelo_subarea_usuario_inactivar)){
				foreach ($modelo_subarea_usuario_inactivar as $subareas_inactivar) {
					//si el estado es activo se cambia a inactivo, si ya esta inactivo no se realiza ninguna acción
					if($subareas_inactivar->Estado == 1){
						$subareas_inactivar->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$subareas_inactivar->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$subareas_inactivar->Estado = 0;
						$subareas_inactivar->save();
					}	
				}
			}
		}else{
			//si el array llega vacio se inactivan todos los registros que esten activos 
		    $criteria=new CDbCriteria;
			$criteria->condition='Id_Usuario=:Id_Usuario AND Estado = 1';
			$criteria->params=array(':Id_Usuario'=>$id_user);
			$modelo_subarea_usuario=SubareaUsuario::model()->findAll($criteria);
			if(!is_null($modelo_subarea_usuario)){
				foreach ($modelo_subarea_usuario as $subareas_act) {
					$subareas_act->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$subareas_act->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$subareas_act->Estado = 0;
					$subareas_act->save();
		
				}
			}	
		}
	}

	public static function subareasactivas($id_user) {
		//opciones activas en el combo subáreas
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Usuario=:Id_Usuario AND Estado=:Estado';
		$criteria->params=array(':Id_Usuario'=>$id_user,':Estado'=> 1);
		$array_subareas_activas = array();
		$subareas_activas=SubareaUsuario::model()->findAll($criteria);
		foreach ($subareas_activas as $subarea_act) {
			array_push($array_subareas_activas, $subarea_act->Id_Subarea);
		}

		return json_encode($array_subareas_activas);
	}


	public static function adminbodegausuario($id_user, $array) {
		$array_bodegas_selec = array();
		if(!empty($array)){

			foreach ($array as $clave => $valor) {
			    
			    //se busca el registro para saber si tiene que ser creado 
			    $criteria=new CDbCriteria;
				$criteria->condition='Id_Usuario=:Id_Usuario AND Id_Bodega=:Id_Bodega';
				$criteria->params=array(':Id_Usuario'=>$id_user,':Id_Bodega'=>$valor);
				$modelo_bodega_usuario=BodegaUsuario::model()->find($criteria);

				if(!is_null($modelo_bodega_usuario)){
					//si el estado es inactivo se cambia a activo, si ya esta activo no se realiza ninguna acción
					if($modelo_bodega_usuario->Estado == 0){
						$modelo_bodega_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$modelo_bodega_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$modelo_bodega_usuario->Estado = 1;
						if($modelo_bodega_usuario->save()){
							array_push($array_bodegas_selec, intval($valor));
						}	
					}else{
						array_push($array_bodegas_selec, intval($valor));	
					}
				}else{
					//se debe insertar un nuevo registro en la tabla
					$nueva_bodega_usuario = new BodegaUsuario;
				    $nueva_bodega_usuario->Id_Usuario = $id_user;
				    $nueva_bodega_usuario->Id_Bodega = $valor;
					$nueva_bodega_usuario->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
					$nueva_bodega_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$nueva_bodega_usuario->Fecha_Creacion = date('Y-m-d H:i:s');
					$nueva_bodega_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$nueva_bodega_usuario->Estado = 1;
					if($nueva_bodega_usuario->save()){
						array_push($array_bodegas_selec, intval($valor));
					}
				}
			}

			//se inactivan las bodegas que no vienen en el array
			$bodegas_excluidas = implode(",",$array_bodegas_selec);
			$be = str_replace("'", "", $bodegas_excluidas);

			$criteria=new CDbCriteria;
			$criteria->condition='Id_Usuario=:Id_Usuario AND Id_Bodega NOT IN ('.$be.')';
			$criteria->params=array(':Id_Usuario'=>$id_user);
			$modelo_bodega_usuario_inactivar=BodegaUsuario::model()->findAll($criteria);
			if(!is_null($modelo_bodega_usuario_inactivar)){
				foreach ($modelo_bodega_usuario_inactivar as $bodegas_inactivar) {
					//si el estado es activo se cambia a inactivo, si ya esta inactivo no se realiza ninguna acción
					if($bodegas_inactivar->Estado == 1){
						$bodegas_inactivar->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$bodegas_inactivar->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$bodegas_inactivar->Estado = 0;
						$bodegas_inactivar->save();
					}	
				}
			}
		}else{
			//si el array llega vacio se inactivan todos los registros que esten activos 
		    $criteria=new CDbCriteria;
			$criteria->condition='Id_Usuario=:Id_Usuario AND Estado = 1';
			$criteria->params=array(':Id_Usuario'=>$id_user);
			$modelo_bodega_usuario=BodegaUsuario::model()->findAll($criteria);
			if(!is_null($modelo_bodega_usuario)){
				foreach ($modelo_bodega_usuario as $bodegas_act) {
					$bodegas_act->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$bodegas_act->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$bodegas_act->Estado = 0;
					$bodegas_act->save();
		
				}
			}
		}
	}

	public static function bodegasactivas($id_user) {
		//opciones activas en el combo bodegas
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Usuario=:Id_Usuario AND Estado=:Estado';
		$criteria->params=array(':Id_Usuario'=>$id_user,':Estado'=> 1);
		$array_bod_activas = array();
		$bodegas_activas=BodegaUsuario::model()->findAll($criteria);
		foreach ($bodegas_activas as $bod_act) {
			array_push($array_bod_activas, $bod_act->Id_Bodega);
		}

		return json_encode($array_bod_activas);
	}

	public static function admintipodoctousuario($id_user, $array) {
		$array_td_selec = array();
		if(!empty($array)){

			foreach ($array as $clave => $valor) {
			    
			    //se busca el registro para saber si tiene que ser creado 
			    $criteria=new CDbCriteria;
				$criteria->condition='Id_Usuario=:Id_Usuario AND Id_Tipo_Docto=:Id_Tipo_Docto';
				$criteria->params=array(':Id_Usuario'=>$id_user,':Id_Tipo_Docto'=>$valor);
				$modelo_td_usuario=TipoDoctoUsuario::model()->find($criteria);

				if(!is_null($modelo_td_usuario)){
					//si el estado es inactivo se cambia a activo, si ya esta activo no se realiza ninguna acción
					if($modelo_td_usuario->Estado == 0){
						$modelo_td_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$modelo_td_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$modelo_td_usuario->Estado = 1;
						if($modelo_td_usuario->save()){
							array_push($array_td_selec, intval($valor));
						}	
					}else{
						array_push($array_td_selec, intval($valor));	
					}
				}else{
					//se debe insertar un nuevo registro en la tabla
					$nuevo_td_usuario = new TipoDoctoUsuario;
				    $nuevo_td_usuario->Id_Usuario = $id_user;
				    $nuevo_td_usuario->Id_Tipo_Docto = $valor;
					$nuevo_td_usuario->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
					$nuevo_td_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$nuevo_td_usuario->Fecha_Creacion = date('Y-m-d H:i:s');
					$nuevo_td_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$nuevo_td_usuario->Estado = 1;
					if($nuevo_td_usuario->save()){
						array_push($array_td_selec, intval($valor));
					}
				}
			}

			//se inactivan los tipos de docto que no vienen en el array
			$td_excluidos = implode(",",$array_td_selec);
			$tde = str_replace("'", "", $td_excluidos);

			$criteria=new CDbCriteria;
			$criteria->condition='Id_Usuario=:Id_Usuario AND Id_Tipo_Docto NOT IN ('.$tde.')';
			$criteria->params=array(':Id_Usuario'=>$id_user);
			$modelo_td_usuario_inactivar=TipoDoctoUsuario::model()->findAll($criteria);
			if(!is_null($modelo_td_usuario_inactivar)){
				foreach ($modelo_td_usuario_inactivar as $td_inactivar) {
					//si el estado es activo se cambia a inactivo, si ya esta inactivo no se realiza ninguna acción
					if($td_inactivar->Estado == 1){
						$td_inactivar->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$td_inactivar->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$td_inactivar->Estado = 0;
						$td_inactivar->save();
					}	
				}
			}
		}else{
			//si el array llega vacio se inactivan todos los registros que esten activos 
		    $criteria=new CDbCriteria;
			$criteria->condition='Id_Usuario=:Id_Usuario AND Estado = 1';
			$criteria->params=array(':Id_Usuario'=>$id_user);
			$modelo_td_usuario=TipoDoctoUsuario::model()->findAll($criteria);
			if(!is_null($modelo_td_usuario)){
				foreach ($modelo_td_usuario as $td_act) {
					$td_act->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$td_act->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$td_act->Estado = 0;
					$td_act->save();
		
				}
			}
		}
	}

	public static function tiposdoctoactivos($id_user) {
		//opciones activas en el combo tipos de documento
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Usuario=:Id_Usuario AND Estado=:Estado';
		$criteria->params=array(':Id_Usuario'=>$id_user,':Estado'=> 1);
		$array_td_activos = array();
		$tipos_docto_activos=TipoDoctoUsuario::model()->findAll($criteria);
		foreach ($tipos_docto_activos as $td_act) {
			array_push($array_td_activos, $td_act->Id_Tipo_Docto);
		}

		return json_encode($array_td_activos);
	}

	public static function adminusuarioupdth($id, $array) {
		$array_users_selec = array();
		foreach ($array as $clave => $valor) {
		    
		    //se busca el registro para saber si tiene que ser creado 
		    $criteria=new CDbCriteria;
			$criteria->condition='Id_Upd_Th=:Id_Upd_Th AND Id_Usuario=:Id_Usuario';
			$criteria->params=array(':Id_Upd_Th'=>$id,':Id_Usuario'=>$valor);
			$modelo_usuario_updth=UsuarioUpdTh::model()->find($criteria);

			if(!is_null($modelo_usuario_updth)){
				//si el estado es inactivo se cambia a activo, si ya esta activo no se realiza ninguna acción
				if($modelo_usuario_updth->Estado == 0){
					$modelo_usuario_updth->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$modelo_usuario_updth->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$modelo_usuario_updth->Estado = 1;
					if($modelo_usuario_updth->save()){
						array_push($array_users_selec, intval($valor));
					}	
				}else{
					array_push($array_users_selec, intval($valor));	
				}
			}else{
				//se debe insertar un nuevo registro en la tabla
				$nuevo_usuario_updth_usuario = new UsuarioUpdTh;
			    $nuevo_usuario_updth_usuario->Id_Upd_Th = $id;
			    $nuevo_usuario_updth_usuario->Id_Usuario = $valor;
				$nuevo_usuario_updth_usuario->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$nuevo_usuario_updth_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$nuevo_usuario_updth_usuario->Fecha_Creacion = date('Y-m-d H:i:s');
				$nuevo_usuario_updth_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$nuevo_usuario_updth_usuario->Estado = 1;
				if($nuevo_usuario_updth_usuario->save()){
					array_push($array_users_selec, intval($valor));
				}
			}
		}

		//se inactivan los usuarios que no vienen en el array
		$usuarios_excluidos = implode(",",$array_users_selec);
		$ue = str_replace("'", "", $usuarios_excluidos);
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Upd_Th=:Id_Upd_Th AND Id_Usuario NOT IN ('.$ue.')';
		$criteria->params=array(':Id_Upd_Th'=>$id);
		$modelo_usuarios_updth_inactivar=UsuarioUpdTh::model()->findAll($criteria);
		if(!is_null($modelo_usuarios_updth_inactivar)){
			foreach ($modelo_usuarios_updth_inactivar as $usuarios_inactivar) {
				//si el estado es activo se cambia a inactivo, si ya esta inactivo no se realiza ninguna acción
				if($usuarios_inactivar->Estado == 1){
					$usuarios_inactivar->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$usuarios_inactivar->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$usuarios_inactivar->Estado = 0;
					$usuarios_inactivar->save();
				}	
			}
		}
	}

	public static function usuariosactivosupdth($id_upd) {
		//opciones activas en el combo de usuarios -> permisos
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Upd_Th=:Id_Upd_Th AND Estado=:Estado';
		$criteria->params=array(':Id_Upd_Th'=>$id_upd,':Estado'=> 1);
		$array_usuarios_activos = array();
		$usuarios_activos=UsuarioUpdTh::model()->findAll($criteria);
		foreach ($usuarios_activos as $user_act) {
			array_push($array_usuarios_activos, $user_act->Id_Usuario);
		}

		return json_encode($array_usuarios_activos);
	}

	public static function admintipactusuario($id_tipo, $array) {
		$array_tip_selec = array();
		foreach ($array as $clave => $valor) {
		    
		    //se busca el registro para saber si tiene que ser creado 
		    $criteria=new CDbCriteria;
			$criteria->condition='Id_Tipo=:Id_Tipo AND Id_Usuario=:Id_Usuario';
			$criteria->params=array(':Id_Tipo'=>$id_tipo,':Id_Usuario'=>$valor);
			$modelo_tipo_usuario=TipoActUsuario::model()->find($criteria);

			if(!is_null($modelo_tipo_usuario)){
				//si el estado es inactivo se cambia a activo, si ya esta activo no se realiza ninguna acción
				if($modelo_tipo_usuario->Estado == 0){
					$modelo_tipo_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$modelo_tipo_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$modelo_tipo_usuario->Estado = 1;
					if($modelo_tipo_usuario->save()){
						array_push($array_tip_selec, intval($valor));
					}	
				}else{
					array_push($array_tip_selec, intval($valor));	
				}
			}else{
				//se debe insertar un nuevo registro en la tabla
				$nuevo_tipo_usuario = new TipoActUsuario;
			    $nuevo_tipo_usuario->Id_Tipo = $id_tipo;
			    $nuevo_tipo_usuario->Id_Usuario = $valor;
				$nuevo_tipo_usuario->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$nuevo_tipo_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$nuevo_tipo_usuario->Fecha_Creacion = date('Y-m-d H:i:s');
				$nuevo_tipo_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$nuevo_tipo_usuario->Estado = 1;
				if($nuevo_tipo_usuario->save()){
					array_push($array_tip_selec, intval($valor));
				}
			}
		}

		//se inactivan los usuarios que no vienen en el array
		$usuarios_excluidos = implode(",",$array_tip_selec);
		$ue = str_replace("'", "", $usuarios_excluidos);
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Tipo=:Id_Tipo AND Id_Usuario NOT IN ('.$ue.')';
		$criteria->params=array(':Id_Tipo'=>$id_tipo);
		$modelo_tipo_usuario_inactivar=TipoActUsuario::model()->findAll($criteria);
		if(!is_null($modelo_tipo_usuario_inactivar)){
			foreach ($modelo_tipo_usuario_inactivar as $usuarios_inactivar) {
				//si el estado es activo se cambia a inactivo, si ya esta inactivo no se realiza ninguna acción
				if($usuarios_inactivar->Estado == 1){
					$usuarios_inactivar->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$usuarios_inactivar->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$usuarios_inactivar->Estado = 0;
					$usuarios_inactivar->save();
				}	
			}
		}
	}

	public static function adminnovedadticketusuario($id_novedad, $array) {
		$array_tip_selec = array();
		foreach ($array as $clave => $valor) {
		    
		    //se busca el registro para saber si tiene que ser creado 
		    $criteria=new CDbCriteria;
			$criteria->condition='Id_Novedad=:Id_Novedad AND Id_Usuario=:Id_Usuario';
			$criteria->params=array(':Id_Novedad'=>$id_novedad,':Id_Usuario'=>$valor);
			$modelo_tipo_usuario=NovedadTicketUsuario::model()->find($criteria);

			if(!is_null($modelo_tipo_usuario)){
				//si el estado es inactivo se cambia a activo, si ya esta activo no se realiza ninguna acción
				if($modelo_tipo_usuario->Estado == 0){
					$modelo_tipo_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$modelo_tipo_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$modelo_tipo_usuario->Estado = 1;
					if($modelo_tipo_usuario->save()){
						array_push($array_tip_selec, intval($valor));
					}	
				}else{
					array_push($array_tip_selec, intval($valor));	
				}
			}else{
				//se debe insertar un nuevo registro en la tabla
				$nuevo_tipo_usuario = new NovedadTicketUsuario;
			    $nuevo_tipo_usuario->Id_Novedad = $id_novedad;
			    $nuevo_tipo_usuario->Id_Usuario = $valor;
				$nuevo_tipo_usuario->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$nuevo_tipo_usuario->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$nuevo_tipo_usuario->Fecha_Creacion = date('Y-m-d H:i:s');
				$nuevo_tipo_usuario->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$nuevo_tipo_usuario->Estado = 1;
				if($nuevo_tipo_usuario->save()){
					array_push($array_tip_selec, intval($valor));
				}
			}
		}

		//se inactivan los usuarios que no vienen en el array
		$usuarios_excluidos = implode(",",$array_tip_selec);
		$ue = str_replace("'", "", $usuarios_excluidos);
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Novedad=:Id_Novedad AND Id_Usuario NOT IN ('.$ue.')';
		$criteria->params=array(':Id_Novedad'=>$id_novedad);
		$modelo_tipo_usuario_inactivar=NovedadTicketUsuario::model()->findAll($criteria);
		if(!is_null($modelo_tipo_usuario_inactivar)){
			foreach ($modelo_tipo_usuario_inactivar as $usuarios_inactivar) {
				//si el estado es activo se cambia a inactivo, si ya esta inactivo no se realiza ninguna acción
				if($usuarios_inactivar->Estado == 1){
					$usuarios_inactivar->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$usuarios_inactivar->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$usuarios_inactivar->Estado = 0;
					$usuarios_inactivar->save();
				}	
			}
		}
	}

	public static function listaareasusuario() {

		$array_areas_usuario = Yii::app()->user->getState('array_areas');
		$lista_areas = array();

		if(!empty($array_areas_usuario)){

			$areas_usuario = implode(",", $array_areas_usuario);
			$areas = Yii::app()->db->createCommand("SELECT Id_Area, Area FROM T_PR_AREA WHERE Estado = 1 AND Id_Area IN (".$areas_usuario.") ORDER BY Area")->queryAll();
			foreach ($areas as $ar) {
				$lista_areas[$ar['Id_Area']] = $ar['Area'];
			}

		}

		return $lista_areas;
	}

	public static function usuariostipactactivos($id_tipo) {
		//opciones activas en el combo usuarios
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Tipo=:Id_Tipo AND Estado=:Estado';
		$criteria->params=array(':Id_Tipo'=>$id_tipo,':Estado'=> 1);
		$array_u_activos = array();
		$usuarios_act=TipoActUsuario::model()->findAll($criteria);
		foreach ($usuarios_act as $u_act) {
			array_push($array_u_activos, $u_act->Id_Usuario);
		}

		return json_encode($array_u_activos);
	}

	public static function usuariosnovedadticketactivos($id_novedad) {
		//opciones activas en el combo usuarios
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Novedad=:Id_Novedad AND Estado=:Estado';
		$criteria->params=array(':Id_Novedad'=>$id_novedad,':Estado'=> 1);
		$array_u_activos = array();
		$usuarios_act=NovedadTicketUsuario::model()->findAll($criteria);
		foreach ($usuarios_act as $u_act) {
			array_push($array_u_activos, $u_act->Id_Usuario);
		}

		return json_encode($array_u_activos);
	}

	public static function factareapend() {
		
		$array_areas_usuario = Yii::app()->user->getState('array_areas');
		if(!empty($array_areas_usuario)){
			
			$areas_usuario = implode(",", $array_areas_usuario);

			$criteria=new CDbCriteria;
			$criteria->condition='Area in ('.$areas_usuario.') AND Estado=:Estado';
			$criteria->params=array(':Estado'=> 1);
			$factareapendrev=FactCont::model()->findAll($criteria);
			$resp = count($factareapendrev);

		}else{
			$resp = -1;
		}

		return $resp;
	}

	public static function correspareapend() {
		
		$array_areas_usuario = Yii::app()->user->getState('array_areas');
		if(!empty($array_areas_usuario)){
			
			$areas_usuario = implode(",", $array_areas_usuario);

			$criteria=new CDbCriteria;
			$criteria->condition='Area in ('.$areas_usuario.') AND Estado=:Estado';
			$criteria->params=array(':Estado'=> 1);
			$correspareapendrev=FactCont::model()->findAll($criteria);
			$resp = count($correspareapendrev);

		}else{
			$resp = -1;
		}

		return $resp;
	}

}
