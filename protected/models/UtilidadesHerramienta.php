<?php

//clase creada para funciones relacionadas con el modelo de herramienta

class UtilidadesHerramienta {

	public static function getherramientas($id_contrato) {

		$array_herramientas = array();

		$criteria1=new CDbCriteria;
        $criteria1->condition='t.Estado = 1';
        $criteria1->order= 't.Nombre ASC';
        $herramientas=Herramienta::model()->findAll($criteria1);

		if(!empty($herramientas)){

			$id1 = 0;
	        $text1 = 'TODAS LAS HERRAMIENTAS';

	        $array_item = array();

	        $flag = 0; 

	        foreach ($herramientas as $he) {

	        	$id_herramienta = $he->Id_Herramienta;

	        	//se busca esta herramienta para saber si el empleado ya lo tiene ligado a este contrato

	        	$criteria=new CDbCriteria;
				$criteria->condition='Id_Contrato=:Id_Contrato AND Id_Herramienta = :Id_Herramienta AND Estado=:Estado';
				$criteria->params=array(':Id_Contrato'=>$id_contrato,':Id_Herramienta'=>$id_herramienta,':Estado'=> 3);
				$herramienta_emp_asig_pend=HerramientaEmpleado::model()->find($criteria);

				$criteria=new CDbCriteria;
				$criteria->condition='Id_Contrato=:Id_Contrato AND Id_Herramienta = :Id_Herramienta AND Estado=:Estado';
				$criteria->params=array(':Id_Contrato'=>$id_contrato,':Id_Herramienta'=>$id_herramienta,':Estado'=> 1);
				$herramienta_emp_ent=HerramientaEmpleado::model()->find($criteria);

				if(!is_null($herramienta_emp_ent)){	
					$texto_ent = 'ENTREGADO';
					
				}else{
					$texto_ent = '';
				}

				if(!is_null($herramienta_emp_asig_pend)){
					$flag = 1;
				 	$array_item[] = array('id' => $he->Id_Herramienta, 'text' => $he->Nombre, 'text_ent' => $texto_ent, 'check' => 1);	
				}else{
					$array_item[] = array('id' => $he->Id_Herramienta, 'text' => $he->Nombre, 'text_ent' => $texto_ent, 'check' => 0);
				}
     	
	        }

	        $array_herramientas[] = array('id' => $id1, 'text' => $text1, 'check' => $flag, 'children' => $array_item);

		}

		return json_encode($array_herramientas);

	}

	//funcion que desasigna herramientas en estado asignado
	public static function desasigherramientasempleado($id_empleado, $id_contrato, $array_ids_her) {

		foreach ($array_ids_her as $key => $valor) {

			$modelo_herramienta_desasig = HerramientaEmpleado::model()->findByAttributes(array('Id_Contrato' => $id_contrato, 'Id_Empleado' => $id_empleado, 'Id_Herramienta' => $valor, 'Estado' => 3));

			if(!empty($modelo_herramienta_desasig)){
				$modelo_herramienta_desasig->Estado = 0;
				$modelo_herramienta_desasig->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$modelo_herramienta_desasig->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$modelo_herramienta_desasig->save();		
			}
		}
			
	}

	//funcion que inserta y modifica elementos en estado asignado
	public static function asigherramientasempleado($id_empleado, $id_contrato, $array_ids_her) {

		//se arma un array con los elementos asigandos al contrato actual
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Contrato=:Id_Contrato AND Estado=:Estado';
		$criteria->params=array(':Id_Contrato'=>$id_contrato,':Estado'=> 3);
	
		$array_herramientas_act = array();

		$modelo_herramientas_act=HerramientaEmpleado::model()->findAll($criteria);

		if(!is_null($modelo_herramientas_act)){
			foreach ($modelo_herramientas_act as $mha) {
				array_push($array_herramientas_act, $mha->Id_Herramienta);
			}
		}

		if(empty($array_herramientas_act)){
			//primera asignación de elementos

			foreach ($array_ids_her as $key => $valor) {
				$nueva_herramienta_empleado = new HerramientaEmpleado;
				$nueva_herramienta_empleado->Id_Contrato = $id_contrato;
				$nueva_herramienta_empleado->Id_Empleado = $id_empleado;
				$nueva_herramienta_empleado->Id_Herramienta = $valor;
				$nueva_herramienta_empleado->Estado = 3;
				$nueva_herramienta_empleado->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$nueva_herramienta_empleado->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$nueva_herramienta_empleado->Fecha_Creacion = date('Y-m-d H:i:s');
				$nueva_herramienta_empleado->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$nueva_herramienta_empleado->save();
			}
			
		}else{
			//actualización de elementos 
			foreach ($array_ids_her as $key => $valor) {

				$criteria=new CDbCriteria;
				$criteria->condition='Id_Contrato=:Id_Contrato AND Id_Herramienta=:Id_Herramienta AND Estado = 1';
				$criteria->params=array(':Id_Contrato'=>$id_contrato,':Id_Herramienta'=>$valor);
				$modelo_herramienta=HerramientaEmpleado::model()->find($criteria);

				if(is_null($modelo_herramienta)){
					
					$nueva_herramienta_empleado = new HerramientaEmpleado;
					$nueva_herramienta_empleado->Id_Contrato = $id_contrato;
					$nueva_herramienta_empleado->Id_Empleado = $id_empleado;
					$nueva_herramienta_empleado->Id_Herramienta = $valor;
					$nueva_herramienta_empleado->Estado = 3;
					$nueva_herramienta_empleado->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
					$nueva_herramienta_empleado->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$nueva_herramienta_empleado->Fecha_Creacion = date('Y-m-d H:i:s');
					$nueva_herramienta_empleado->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$nueva_herramienta_empleado->save();
				}
			}
		}
	}

	public static function getherramientaspendentempleado($id_contrato) {

		$id1 = 0;
        $text1 = 'TODAS LAS HERRAMIENTAS';

        //elementos asignados
        $criteria1=new CDbCriteria;
        $criteria1->join = '
		LEFT JOIN T_PR_HERRAMIENTA h ON t.Id_Herramienta = h.Id_Herramienta
		';
        $criteria1->condition='t.Id_Contrato=:Id_Contrato AND t.Estado=:Estado';
        $criteria1->params=array(':Id_Contrato'=> $id_contrato,':Estado'=> 3);
        $criteria1->order= 'h.Nombre ASC';
        $herramientas_pend=HerramientaEmpleado::model()->findAll($criteria1);

        $array_herramientas_pend_ent = array();
        $array_herramientas = array();

        if(!empty($herramientas_pend)){

	        foreach ($herramientas_pend as $ha) {

	        	$id_herramienta = $ha->Id_Herramienta;

	        	$texto_herramienta = $ha->idherramienta->Nombre;

				$array_herramientas[] = array('id' => $id_herramienta, 'text' => $texto_herramienta);
			
	        }

	        $array_herramientas_pend_ent[] = array('id' => $id1, 'text' => $text1,'children' => $array_herramientas);

       	}

		return json_encode($array_herramientas_pend_ent);

	}

	//funcion que cambia el estado de las herramientas a ASIGNADO
	public static function entregaherramientasempleado($id_empleado, $id_contrato, $array_ids_her) {

		foreach ($array_ids_her as $key => $valor) {

			$modelo_herramienta_entrega = HerramientaEmpleado::model()->findByAttributes(array('Id_Contrato' => $id_contrato, 'Id_Empleado' => $id_empleado, 'Id_Herramienta' => $valor, 'Estado' => 3));

			if(!empty($modelo_herramienta_entrega)){
				$modelo_herramienta_entrega->Estado = 1;
				$modelo_herramienta_entrega->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$modelo_herramienta_entrega->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$modelo_herramienta_entrega->save();		
			}
		}
			
	}

	public static function getherramientasentempleado($id_contrato) {

		$id1 = 0;
        $text1 = 'TODAS LAS HERRAMIENTAS';

        //elementos asignados
        $criteria1=new CDbCriteria;
        $criteria1->join = '
		LEFT JOIN T_PR_HERRAMIENTA h ON t.Id_Herramienta = h.Id_Herramienta
		';
        $criteria1->condition='t.Id_Contrato=:Id_Contrato AND t.Estado=:Estado';
        $criteria1->params=array(':Id_Contrato'=> $id_contrato,':Estado'=> 1);
        $criteria1->order= 'h.Nombre ASC';
        $herramientas_entregadas=HerramientaEmpleado::model()->findAll($criteria1);

        $array_herramientas_entregadas = array();
        $array_herramientas = array();

        if(!empty($herramientas_entregadas)){

	        foreach ($herramientas_entregadas as $ha) {

	        	$id_herramienta = $ha->Id_Herramienta;

	        	$texto_herramienta = $ha->idherramienta->Nombre;

				$array_herramientas[] = array('id' => $id_herramienta, 'text' => $texto_herramienta);
			
	        }

	        $array_herramientas_entregadas[] = array('id' => $id1, 'text' => $text1,'children' => $array_herramientas);

       	}

		return json_encode($array_herramientas_entregadas);

	}

	//funcion que cambia el estado de las herramientas a DEVUELTO
	public static function devolucionherramientasempleado($id_empleado, $id_contrato, $array_ids_her) {
		

		foreach ($array_ids_her as $key => $valor) {

			$modelo_herramienta_dev = HerramientaEmpleado::model()->findByAttributes(array('Id_Contrato' => $id_contrato, 'Id_Empleado' => $id_empleado, 'Id_Herramienta' => $valor, 'Estado' => 1));

			if(!empty($modelo_herramienta_dev)){
				$modelo_herramienta_dev->Estado = 2;
				$modelo_herramienta_dev->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$modelo_herramienta_dev->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$modelo_herramienta_dev->save();		
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
