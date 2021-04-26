<?php

//clase creada para funciones relacionadas con el modelo de contrato de empleado

class UtilidadesContrato {
   
	public static function fechaingresomin($id_empleado) {
		
		//se consulta el ultimo contrato
		$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato, Fecha_Retiro FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id_empleado.' AND Id_M_Retiro IS NOT NULL ORDER BY 1 DESC')->queryRow();

		if(empty($query_contrato)){
			$aa = date("Y") - 1;

			$fechaingresomin =	$aa.'-01-01';
		}else{
			
			$nuevafecha = strtotime ( '+1 day' , strtotime($query_contrato['Fecha_Retiro'])) ;
			$nuevafecha = date ('Y-m-d' , $nuevafecha);

			$fechaingresomin = $nuevafecha;
		}

		return $fechaingresomin;

	}

	public static function fechaminausdis($id_empleado) {
		
		//se consulta el ultimo contrato
		$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato, Fecha_Ingreso FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id_empleado.' AND Id_M_Retiro IS NULL ORDER BY 1 DESC')->queryRow();
		
		$nuevafecha = $query_contrato['Fecha_Ingreso'];

		$fechamin = $nuevafecha;

		return $fechamin;

	}

	public static function fechamintur($id_empleado) {
		
		//se consulta el ultimo contrato
		$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato, Fecha_Ingreso FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id_empleado.' AND Id_M_Retiro IS NULL ORDER BY 1 DESC')->queryRow();
		
		$nuevafecha = $query_contrato['Fecha_Ingreso'];

		$fechamin = $nuevafecha;

		return $fechamin;

	}
}
