<?php

//clase creada para funciones relacionadas con el modelo de elemento

class UtilidadesElemento {

	//funcion que carga los elementos por sugerido via ajax
	public static function getelementossugerido($id_contrato, $id_area, $id_subarea, $id_cargo) {

		$array_sugerido = array();

		$criteria=new CDbCriteria;
		$criteria->condition='Id_Area=:Id_Area AND Id_Subarea=:Id_Subarea AND Id_Cargo=:Id_Cargo AND Estado=:Estado';
		$criteria->params=array(':Id_Area'=>$id_area,':Id_Subarea'=>$id_subarea,':Id_Cargo'=>$id_cargo,':Estado'=> 1);
		$sugerido=Sugerido::model()->find($criteria);

/*	echo $id_subarea.' '.$id_cargo.' '.$id_area;
		die();*/
		//subareas y areas asignadas a usuario
		$array_subareas =Yii::app()->user->getState('array_subareas');
		$array_areas =Yii::app()->user->getState('array_areas');

		
		if(!empty($sugerido)){

			
			$id_sugerido = $sugerido->Id_Sugerido;

		
	        $area = Area::model()->findByPk($id_area)->Area;
	        $subarea = Subarea::model()->findByPk($id_subarea)->Subarea;
			$cargo = Cargo::model()->findByPk($id_cargo)->Cargo;

			$id1 = 0;
	        $text1 = 'TODOS LOS SUGERIDOS - ELEM. (SUBÁREA / ÁREA) ';

	        //elementos por area para este sugerido
	        $criteria1=new CDbCriteria;
	        $criteria1->join = '
	        LEFT JOIN T_PR_AREA_ELEMENTO ae ON t.Id_A_Elemento = ae.Id_A_Elemento
			LEFT JOIN T_PR_ELEMENTO e ON e.Id_Elemento = ae.Id_Elemento
			LEFT JOIN T_PR_AREA a ON a.Id_Area = ae.Id_Area
			LEFT JOIN T_PR_SUBAREA s ON s.Id_Subarea = ae.Id_Subarea
			';
	        $criteria1->condition='t.Id_Sugerido=:Id_Sugerido AND t.Estado=:Estado';
	        $criteria1->params=array(':Id_Sugerido'=> $id_sugerido,':Estado'=> 1);
	        $criteria1->order= 'e.Elemento ASC, s.Subarea ASC, a.Area ASC';
	        $elementos_sugerido=ElementoSugerido::model()->findAll($criteria1);

	        $array_elementos = array();

	        $flag = 0; 

			
	        foreach ($elementos_sugerido as $es) {

	        	//se determina si el usuario tiene la subarea y area del elemento asignado para ser manipulado, si no se muestra el elemento con check disabled

	        	$id_elemento = $es->Id_A_Elemento;
	        	$id_subarea_elemento = $es->idaelemento->Id_Subarea;
	        	$id_area_elemento = $es->idaelemento->Id_Area;

	        	if(!is_null($id_subarea_elemento) && !is_null($id_area_elemento)){
	        		//el elemento tiene subarea y area asignados, pueden ser manipulados

	        		if (in_array($id_subarea_elemento, $array_subareas) && in_array($id_area_elemento, $array_areas)) {
						//el usuario tiene el area y subarea asignadas del elemento que es recorrido

	        			$modelo_area_elemento = AreaElemento::model()->findByPk($id_elemento);

			        	$texto_area_elemento = $modelo_area_elemento->idelemento->Elemento.' ('.$modelo_area_elemento->idsubarea->Subarea.' / '.$modelo_area_elemento->idarea->Area.')';
        	
						
			        	$criteria=new CDbCriteria;
						$criteria->condition='Id_Contrato=:Id_Contrato AND Id_A_Elemento = :Id_A_Elemento AND Estado=:Estado';
						$criteria->params=array(':Id_Contrato'=>$id_contrato,':Id_A_Elemento'=>$id_elemento,':Estado'=> 3);

						
						$elemento_emp_asig_pend = ElementoEmpleado::model()->find($criteria);
					
						
						
						
						$criteria2=new CDbCriteria;
						$criteria2->condition='Id_Contrato=:Id_Contrato AND Id_A_Elemento = :Id_A_Elemento AND Estado=:Estado';
						$criteria2->params=array(':Id_Contrato'=>$id_contrato,':Id_A_Elemento'=>$id_elemento,':Estado'=> 1);
						
						$elemento_emp_ent=ElementoEmpleado::model()->find($criteria2);

						if(!is_null($elemento_emp_ent)){

							if($elemento_emp_ent->Cantidad == 1){
								$texto_ent = $elemento_emp_ent->Cantidad.' ENTREGADO';
							}else{
								$texto_ent = $elemento_emp_ent->Cantidad.' ENTREGADO(S)';
							}
	
						}else{
							$texto_ent = '';
						}

						if(!is_null($elemento_emp_asig_pend)){
							$flag = 1;
						 	$array_elementos[] = array('id' => $id_elemento, 'text' => $texto_area_elemento, 'text_ent' => $texto_ent, 'check' => 1, 'cantidad' => $elemento_emp_asig_pend->Cantidad);	
						}else{
							$array_elementos[] = array('id' => $id_elemento, 'text' => $texto_area_elemento, 'text_ent' => $texto_ent, 'check' => 0, 'cantidad' => $es->Cantidad);
						}	

					}

	        	}
	        }


	        if(!empty($array_elementos)){
	        	$array_sugerido[] = array('id' => $id1, 'text' => $text1, 'check' => $flag, 'children' => $array_elementos);
	        }

		}

		return json_encode($array_sugerido);

	}

	//funcion que carga los elementos (no sugeridos) via ajax
	public static function getelementos($id_contrato, $id_area, $id_subarea, $id_cargo) {

		$modelo_sugerido = Sugerido::model()->findByAttributes(array('Id_Area' => $id_area, 'Id_Subarea' => $id_subarea, 'Id_Cargo' => $id_cargo, 'Estado' => 1));
		if(!is_null($modelo_sugerido)){
			$modelo_elementos_sugerido = ElementoSugerido::model()->findAllByAttributes(array('Id_Sugerido' => $modelo_sugerido->Id_Sugerido, 'Estado' => 1));
			
			$cond_exc = ' AND t.Id_A_Elemento NOT IN (';

			foreach ($modelo_elementos_sugerido as $esug) {
				$cond_exc .= $esug->Id_A_Elemento.',';
			}

			$cond_exc = substr ($cond_exc, 0, -1);
			$cond_exc .= ')';
			$texto = 'OTROS ELEMENTOS (ÁREA)';
		}else{
			$cond_exc = '';
			$texto = 'TODOS LOS ELEMENTOS (ÁREA)';
		}

		//subareas y areas asignadas a usuario
		$array_subareas =Yii::app()->user->getState('array_subareas');
		$string_subareas_usuario = implode(",", $array_subareas);
		$array_areas =Yii::app()->user->getState('array_areas');
		$string_areas_usuario = implode(",", $array_areas);

		$m_areas=Area::model()->findAll(array('order'=>'Area', 'condition'=>'Id_Area IN ('.$string_areas_usuario.') AND Estado = 1'));

		$array = array();

		$array_areas = array();

		foreach ($m_areas as $area) {
		
			$criteria1=new CDbCriteria;
			$criteria1->join = 'LEFT JOIN T_PR_ELEMENTO e ON e.Id_Elemento = t.Id_Elemento LEFT JOIN T_PR_SUBAREA s ON s.Id_Subarea = t.Id_Subarea';
	        $criteria1->condition='t.Id_Area = '.$area->Id_Area.' AND t.Estado = 1 AND t.Id_Subarea IN ('.$string_subareas_usuario.') AND s.Estado = 1 '.$cond_exc;
	        $criteria1->order= 'e.Elemento ASC, s.Subarea ASC';
	        $elementos_a=AreaElemento::model()->findAll($criteria1);

	        if(!empty($elementos_a)){
	        
	        	$id1 = 'a_'.$area->Id_Area;

	        	$text1 = $area->Area. ' (ELEM. - SUBÁREA)';

	        	$flag = 0;

	        	$array_elementos = array();

	        	foreach ($elementos_a as $e_a) {

		        	$id_area_elemento = $e_a->Id_A_elemento;

		        	//se busca este elemento para saber si el empleado ya lo tiene ligado a este contrato

		        	$modelo_area_elemento = AreaElemento::model()->findByPk($id_area_elemento);

		        	$texto_area_elemento = $modelo_area_elemento->idelemento->Elemento.' ('.$modelo_area_elemento->idsubarea->Subarea.')';

		        	$criteria=new CDbCriteria;
					$criteria->condition='Id_Contrato=:Id_Contrato AND Id_A_Elemento = :Id_A_Elemento AND Estado=:Estado';
					$criteria->params=array(':Id_Contrato'=>$id_contrato,':Id_A_Elemento'=>$id_area_elemento,':Estado'=> 3);
					

					$elemento_emp_asig_pend = ElementoEmpleado::model()->find($criteria);

					$criteria2=new CDbCriteria;
					$criteria2->condition='Id_Contrato=:Id_Contrato AND Id_A_Elemento = :Id_A_Elemento AND Estado=:Estado';
					$criteria2->params=array(':Id_Contrato'=>$id_contrato,':Id_A_Elemento'=>$id_area_elemento,':Estado'=> 1);
					
					$elemento_emp_ent=ElementoEmpleado::model()->find($criteria2);

					if(!is_null($elemento_emp_ent)){
						
						if($elemento_emp_ent->Cantidad == 1){
							$texto_ent = $elemento_emp_ent->Cantidad.' ENTREGADO';
						}else{
							$texto_ent = $elemento_emp_ent->Cantidad.' ENTREGADO(S)';
						}

					}else{
						$texto_ent = '';
					}

					if(!is_null($elemento_emp_asig_pend)){
						$flag = 1;
					 	$array_elementos[] = array('id' => $id_area_elemento, 'text' => $texto_area_elemento, 'text_ent' => $texto_ent, 'check' => 1, 'cantidad' => $elemento_emp_asig_pend->Cantidad);	
					}else{
						$array_elementos[] = array('id' => $id_area_elemento, 'text' => $texto_area_elemento, 'text_ent' => $texto_ent, 'check' => 0, 'cantidad' => 1);
					}
	     	
		        }

		        $array_areas[] = array('id' => $id1, 'text' => $text1, 'check' => $flag, 'children' => $array_elementos);
		        reset($array_elementos);
	        }

		}

		if(!empty($array_areas)){
			$array[] = array('id' => 0, 'text' => $texto, 'check' => $flag, 'children' => $array_areas);	
		}

		

		return json_encode($array);

	}

	//funcion que desasigna elementos en estado asignado
	public static function desasigelementosempleado($id_empleado, $id_contrato, $array_ids_ele) {

		foreach ($array_ids_ele as $key => $valor) {

			$modelo_elemento_desasig = ElementoEmpleado::model()->findByAttributes(array('Id_Contrato' => $id_contrato, 'Id_Empleado' => $id_empleado, 'Id_A_Elemento' => $valor, 'Estado' => 3));

			if(!empty($modelo_elemento_desasig)){
				$modelo_elemento_desasig->Estado = 0;
				$modelo_elemento_desasig->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$modelo_elemento_desasig->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$modelo_elemento_desasig->save();		
			}
		}
			
	}

	//funcion que inserta y modifica elementos en estado asignado
	public static function asigelementosempleado($id_empleado, $id_contrato, $array_ids_ele, $array_cant_ele) {

		//se arma un array con los elementos asignados al contrato actual
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Contrato=:Id_Contrato AND Estado=:Estado';
		$criteria->params=array(':Id_Contrato'=>$id_contrato,':Estado'=> 3);
	
		$array_elementos_actuales = array();

		$modelo_elementos_act=ElementoEmpleado::model()->findAll($criteria);

		if(!is_null($modelo_elementos_act)){
			foreach ($modelo_elementos_act as $mea) {
				array_push($array_elementos_actuales, $mea->Id_A_Elemento);
			}
		}

		if(empty($array_elementos_actuales)){
			//primera asignación de elementos

			foreach ($array_ids_ele as $key => $valor) {
				$nuevo_elemento_empleado = new ElementoEmpleado;
				$nuevo_elemento_empleado->Id_Contrato = $id_contrato;
				$nuevo_elemento_empleado->Id_Empleado = $id_empleado;
				$nuevo_elemento_empleado->Id_A_Elemento = $valor;
				$nuevo_elemento_empleado->Cantidad = $array_cant_ele[$key];
				$nuevo_elemento_empleado->Estado = 3;
				$nuevo_elemento_empleado->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$nuevo_elemento_empleado->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$nuevo_elemento_empleado->Fecha_Creacion = date('Y-m-d H:i:s');
				$nuevo_elemento_empleado->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$nuevo_elemento_empleado->save();
			}
			

		}else{
			//actualización de elementos 
			foreach ($array_ids_ele as $key => $valor) {

				$criteria=new CDbCriteria;
				$criteria->condition='Id_Contrato=:Id_Contrato AND Id_A_Elemento=:Id_A_Elemento AND Estado = 3';
				$criteria->params=array(':Id_Contrato'=>$id_contrato,':Id_A_Elemento'=>$valor);
				$modelo_elemento=ElementoEmpleado::model()->find($criteria);

				if(!is_null($modelo_elemento)){
					if($modelo_elemento->Cantidad != $array_cant_ele[$key]){
						$modelo_elemento->Cantidad = $array_cant_ele[$key];
						$modelo_elemento->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$modelo_elemento->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$modelo_elemento->save();	
					}
				}else{
					$nuevo_elemento_empleado = new ElementoEmpleado;
					$nuevo_elemento_empleado->Id_Contrato = $id_contrato;
					$nuevo_elemento_empleado->Id_Empleado = $id_empleado;
					$nuevo_elemento_empleado->Id_A_Elemento = $valor;
					$nuevo_elemento_empleado->Cantidad = $array_cant_ele[$key];
					$nuevo_elemento_empleado->Estado = 3;
					$nuevo_elemento_empleado->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
					$nuevo_elemento_empleado->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$nuevo_elemento_empleado->Fecha_Creacion = date('Y-m-d H:i:s');
					$nuevo_elemento_empleado->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$nuevo_elemento_empleado->save();
				}
			}
		}
	}

	public static function getelementospendentempleado($id_contrato) {

		$id1 = 0;
        $text1 = 'TODOS LOS ELEMENTOS';

        //subareas y areas asignadas a usuario
		$array_subareas =Yii::app()->user->getState('array_subareas');
		$string_subareas_usuario = implode(",", $array_subareas);
		$array_areas =Yii::app()->user->getState('array_areas');
		$string_areas_usuario = implode(",", $array_areas);

        //elementos asignados
        $criteria1=new CDbCriteria;
        $criteria1->join = '
        LEFT JOIN T_PR_AREA_ELEMENTO ae ON t.Id_A_Elemento = ae.Id_A_Elemento
		LEFT JOIN T_PR_ELEMENTO e ON e.Id_Elemento = ae.Id_Elemento
		LEFT JOIN T_PR_SUBAREA s ON s.Id_Subarea = ae.Id_Subarea
		LEFT JOIN T_PR_AREA a ON a.Id_Area = ae.Id_Area
		';
        $criteria1->condition='t.Id_Contrato=:Id_Contrato AND t.Estado=:Estado AND ae.Id_Subarea IN ('.$string_subareas_usuario.') AND ae.Id_Area IN ('.$string_areas_usuario.')';
        $criteria1->params=array(':Id_Contrato'=> $id_contrato,':Estado'=> 3);
        $criteria1->order= 'e.Elemento ASC, s.Subarea ASC, a.Area ASC';
        $elementos_pend_ent=ElementoEmpleado::model()->findAll($criteria1);

        $array_elementos_pend_ent = array();
        $array_elementos = array();

        if(!empty($elementos_pend_ent)){

	        foreach ($elementos_pend_ent as $ea) {

	        	$id_area_elemento = $ea->Id_A_Elemento;

	        	$modelo_area_elemento = AreaElemento::model()->findByPk($id_area_elemento);

	        	if(is_null($ea->idaelemento->Id_Elemento)){
	        		$elemento = 'SIN ASIGNAR';
	        	}else{
	        		$elemento = $modelo_area_elemento->idelemento->Elemento;
	        	}

	        	if(is_null($ea->idaelemento->Id_Subarea)){
					$subarea = 'SIN ASIGNAR';
	        	}else{
	        		$subarea = $modelo_area_elemento->idsubarea->Subarea;
	        	}

	        	if(is_null($ea->idaelemento->Id_Area)){
					$area = 'SIN ASIGNAR';
	        	}else{
	        		$area = $modelo_area_elemento->idarea->Area;
	        	}

	        	$texto_area_elemento = $ea->Cantidad.' '.$elemento.' ('.$subarea.' / '.$area.')';

				$array_elementos[] = array('id' => $id_area_elemento, 'text' => $texto_area_elemento);
			
	        }

	        $array_elementos_pend_ent[] = array('id' => $id1, 'text' => $text1,'children' => $array_elementos);
	   	}

		return json_encode($array_elementos_pend_ent);

	}

	//funcion que cambia el estado de los elementos a ASIGNADO
	public static function entregaelementosempleado($id_empleado, $id_contrato, $array_ids_ele) {

		foreach ($array_ids_ele as $key => $valor) {

			$modelo_elemento_entrega = ElementoEmpleado::model()->findByAttributes(array('Id_Contrato' => $id_contrato, 'Id_Empleado' => $id_empleado, 'Id_A_Elemento' => $valor, 'Estado' => 3));

			if(!empty($modelo_elemento_entrega)){
				$modelo_elemento_entrega->Estado = 1;
				$modelo_elemento_entrega->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$modelo_elemento_entrega->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$modelo_elemento_entrega->save();		
			}
		}
			
	}

	public static function getelementosentempleado($id_contrato) {

		$id1 = 0;
        $text1 = 'TODOS LOS ELEMENTOS';

        //subareas y areas asignadas a usuario
		$array_subareas =Yii::app()->user->getState('array_subareas');
		$string_subareas_usuario = implode(",", $array_subareas);
		$array_areas =Yii::app()->user->getState('array_areas');
		$string_areas_usuario = implode(",", $array_areas);

        //elementos entregados
        $criteria1=new CDbCriteria;
        $criteria1->join = '
        LEFT JOIN T_PR_AREA_ELEMENTO ae ON t.Id_A_Elemento = ae.Id_A_Elemento
		LEFT JOIN T_PR_ELEMENTO e ON e.Id_Elemento = ae.Id_Elemento
		LEFT JOIN T_PR_SUBAREA s ON s.Id_Subarea = ae.Id_Subarea
		LEFT JOIN T_PR_AREA a ON a.Id_Area = ae.Id_Area
		';
        $criteria1->condition='t.Id_Contrato=:Id_Contrato AND t.Estado=:Estado AND ae.Id_Subarea IN ('.$string_subareas_usuario.') AND ae.Id_Area IN ('.$string_areas_usuario.')';
        $criteria1->params=array(':Id_Contrato'=> $id_contrato,':Estado'=> 1);
        $criteria1->order= 'e.Elemento ASC, s.Subarea ASC, a.Area ASC';
        $elementos_entregados=ElementoEmpleado::model()->findAll($criteria1);

        $array_elementos_ent = array();
        $array_elementos = array();

        if(!empty($elementos_entregados)){

	        foreach ($elementos_entregados as $ea) {

	        	$id_area_elemento = $ea->Id_A_Elemento;

	        	$modelo_area_elemento = AreaElemento::model()->findByPk($id_area_elemento);

	        	if(is_null($ea->idaelemento->Id_Elemento)){
	        		$elemento = 'SIN ASIGNAR';
	        	}else{
	        		$elemento = $modelo_area_elemento->idelemento->Elemento;
	        	}

	        	if(is_null($ea->idaelemento->Id_Subarea)){
					$subarea = 'SIN ASIGNAR';
	        	}else{
	        		$subarea = $modelo_area_elemento->idsubarea->Subarea;
	        	}

	        	if(is_null($ea->idaelemento->Id_Area)){
					$area = 'SIN ASIGNAR';
	        	}else{
	        		$area = $modelo_area_elemento->idarea->Area;
	        	}

	        	$texto_area_elemento = $ea->Cantidad.' '.$elemento.' ('.$subarea.' / '.$area.')';

				$array_elementos[] = array('id' => $id_area_elemento, 'text' => $texto_area_elemento);
			
	        }

	        $array_elementos_ent[] = array('id' => $id1, 'text' => $text1,'children' => $array_elementos);
	   	}

		return json_encode($array_elementos_ent);

	}

	public static function devolucionelementosempleado($id_empleado, $id_contrato, $array_ids_ele) {
			
		foreach ($array_ids_ele as $key => $valor) {

			$modelo_elemento_dev = ElementoEmpleado::model()->findByAttributes(array('Id_Contrato' => $id_contrato, 'Id_Empleado' => $id_empleado, 'Id_A_Elemento' => $valor, 'Estado' => 1));

			if(!empty($modelo_elemento_dev)){
				$modelo_elemento_dev->Estado = 2;
				$modelo_elemento_dev->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$modelo_elemento_dev->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$modelo_elemento_dev->save();		
			}
		}

	}

	public static function textoestado($estado){

		switch ($estado) {
		    case 0:
		        $texto_estado = 'DESASIGNADO';
		        break;
		    case 1:
		        $texto_estado = 'ASIGNADO';
		        break;
		    case 2:
		        $texto_estado = 'DEVUELTO';
		        break;
		    case 3:
		        $texto_estado = 'PEND. POR ENTREGA';
		        break;
		}

		return $texto_estado;

	}
}
