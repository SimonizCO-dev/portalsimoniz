<?php

//clase creada para funciones relacionadas con el modelo de sugerido

class UtilidadesSugerido {
   
	public static function sugerido($id_sugerido){

		//Sugerido (Área / Subárea - Cargo)

		$modelo_sugerido = Sugerido::model()->findByPk($id_sugerido);

		$id_area = $modelo_sugerido->Id_Area;

		$id_subarea = $modelo_sugerido->Id_Subarea;

		$id_cargo = $modelo_sugerido->Id_Cargo;

		if(is_null($id_area)){
			$area = 'NO ASIGNADO';
		}else{
			$area = $modelo_sugerido->idarea->Area;
		}

		if(is_null($id_area)){
			$subarea = 'NO ASIGNADO';
		}else{
			$subarea = $modelo_sugerido->idsubarea->Subarea;
		}

		if(is_null($id_area)){
			$cargo = 'NO ASIGNADO';
		}else{
			$cargo = $modelo_sugerido->idcargo->Cargo;
		}


		return $cargo.' - '.$subarea.' / '.$area;

	}

	public static function areasubareaelemento($id_area_elemento){

		$modelo_area_sub_elemento = AreaElemento::model()->findByPk($id_area_elemento);

		$id_elemento = $modelo_area_sub_elemento->Id_Elemento;

		$id_area = $modelo_area_sub_elemento->Id_Area;

		$id_subarea = $modelo_area_sub_elemento->Id_Subarea;

		if(is_null($id_elemento)){
			$elemento = 'NO ASIGNADO';
		}else{
			$elemento = $modelo_area_sub_elemento->idelemento->Elemento;
		}

		if(is_null($id_area)){
			$area = 'NO ASIGNADO';
		}else{
			$area = $modelo_area_sub_elemento->idarea->Area;
		}

		if(is_null($id_subarea)){
			$subarea = 'NO ASIGNADO';
		}else{
			$subarea = $modelo_area_sub_elemento->idsubarea->Subarea;
		}


		return $elemento.' ('.$subarea.' / '.$area.')';

	}

}
