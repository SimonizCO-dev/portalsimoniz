<?php

//clase creada para funciones relacionadas con el modelo de menu para perfiles

class UtilidadesMenu {
   
	public static function setmenu() {
		//opcion que muestra las opciones de menu para un nuevo perfil

		$criteria1=new CDbCriteria;
		$criteria1->condition='Id_Padre=:Id_Padre AND Estado=:Estado AND Id_Menu != 1';
		$criteria1->params=array(':Id_Padre'=>1,':Estado'=> 1);
		$criteria1->order= 'orden';
		$level1=Menu::model()->findAll($criteria1);
		$array_menu = array();
		if (!is_null($level1)) {
		    foreach ($level1 as $l1) {
		        $id1 = $l1->Id_Menu;
		        $text1 = $l1->Descripcion;
		        $criteria2=new CDbCriteria;
		        $criteria2->condition='Id_Padre=:Id_Padre AND Estado=:Estado';
		        $criteria2->params=array(':Id_Padre'=> $id1,':Estado'=> 1);
		        $criteria2->order= 'orden';
		        $level2=Menu::model()->findAll($criteria2);
		        $array_menu2 = array(); 
		        if (!is_null($level2)) {
		            foreach ($level2 as $l2) {
		                $id2 = $l2->Id_Menu;
		                $text2 = $l2->Descripcion;
		                $criteria3=new CDbCriteria;
		                $criteria3->condition='Id_Padre=:Id_Padre AND Estado=:Estado';
		                $criteria3->params=array(':Id_Padre'=> $id2,':Estado'=> 1);
		                $criteria3->order= 'orden';
		                $level3=Menu::model()->findAll($criteria3); 
		                $array_menu3 = array();
		                if (!is_null($level3)) {
				            foreach ($level3 as $l3) {
				                $id3 = $l3->Id_Menu;
				                $text3 = $l3->Descripcion;
				                $criteria4=new CDbCriteria;
				                $criteria4->condition='Id_Padre=:Id_Padre AND Estado=:Estado';
				                $criteria4->params=array(':Id_Padre'=> $id3,':Estado'=> 1);
				                $criteria4->order= 'orden';
				                $level4=Menu::model()->findAll($criteria4); 
				                $array_menu4 = array();
				                if (!is_null($level4)) {
				                    foreach ($level4 as $l4) {
				                        $id4 = $l4->Id_Menu;
				                        $text4 = $l4->Descripcion;
				                        $array_menu4[] = array('id' => $id4, 'text' => $text4, 'children' => array());
				                    }    
				                }

				                $array_menu3[] = array('id' => $id3, 'text' => $text3, 'children' => $array_menu4);
				                reset($array_menu3);
				            }

				        $array_menu2[] = array('id' => $id2, 'text' => $text2, 'children' => $array_menu3);
				        reset($array_menu2);

				        }   

		            }

		        $array_menu[] = array('id' => $id1, 'text' => $text1, 'children' => $array_menu2);
		        reset($array_menu2);

		        }else{
		            $array_menu[] = array('id' => $id1, 'text' => $text1, 'children' => array());
		        }   
		    }
		}
		
		return json_encode($array_menu);
	}

	public static function getmenu($id_perfil) {
		//opcion para cargar las opciones de menu por perfil

		//se arma un array con las opciones actuales de el perfil
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Perfil=:Id_Perfil AND Estado=:Estado';
		$criteria->params=array(':Id_Perfil'=>$id_perfil,':Estado'=> 1);
		
		$array_opciones_selec = array();
		$modelo_opciones_selec=MenuPerfil::model()->findAll($criteria);
		if(!is_null($modelo_opciones_selec)){
			foreach ($modelo_opciones_selec as $mos) {
				array_push($array_opciones_selec, $mos->Id_Menu);
			}
		}

		$criteria1=new CDbCriteria;
		$criteria1->condition='Id_Padre=:Id_Padre AND Estado=:Estado AND Id_Menu != 1';
		$criteria1->params=array(':Id_Padre'=>1,':Estado'=> 1);
		$criteria1->order= 'orden';
		$level1=Menu::model()->findAll($criteria1);
		$array_menu = array();
		if (!is_null($level1)) {
		    foreach ($level1 as $l1) {
		        $id1 = $l1->Id_Menu;
		        $text1 = $l1->Descripcion;
		        if (in_array($id1, $array_opciones_selec)) { $checked1 = 1; } else { $checked1 = 0; }
		        $criteria2=new CDbCriteria;
		        $criteria2->condition='Id_Padre=:Id_Padre AND Estado=:Estado';
		        $criteria2->params=array(':Id_Padre'=> $id1,':Estado'=> 1);
		        $criteria2->order= 'orden';
		        $level2=Menu::model()->findAll($criteria2);
		        $array_menu2 = array(); 
		        if (!is_null($level2)) {
		            foreach ($level2 as $l2) {
		                $id2 = $l2->Id_Menu;
		                $text2 = $l2->Descripcion;
		                if (in_array($id2, $array_opciones_selec)) { $checked2 = 1; } else { $checked2 = 0; }
		                $criteria3=new CDbCriteria;
		                $criteria3->condition='Id_Padre=:Id_Padre AND Estado=:Estado';
		                $criteria3->params=array(':Id_Padre'=> $id2,':Estado'=> 1);
		                $criteria3->order= 'orden';
		                $level3=Menu::model()->findAll($criteria3); 
		                $array_menu3 = array();
		                if (!is_null($level3)) {
				            foreach ($level3 as $l3) {
				                $id3 = $l3->Id_Menu;
				                $text3 = $l3->Descripcion;
				                if (in_array($id3, $array_opciones_selec)) { $checked3 = 1; } else { $checked3 = 0; }
				                $criteria4=new CDbCriteria;
				                $criteria4->condition='Id_Padre=:Id_Padre AND Estado=:Estado';
				                $criteria4->params=array(':Id_Padre'=> $id3,':Estado'=> 1);
				                $criteria4->order= 'orden';
				                $level4=Menu::model()->findAll($criteria4); 
				                $array_menu4 = array();
				                if (!is_null($level4)) {
				                    foreach ($level4 as $l4) {
				                        $id4 = $l4->Id_Menu;
				                        $text4 = $l4->Descripcion;
				                        if (in_array($id4, $array_opciones_selec)) { $checked4 = 1; } else { $checked4 = 0; }
				                        $array_menu4[] = array('id' => $id4, 'text' => $text4, 'check' => $checked4, 'children' => array());
				                    }    
				                }

				                $array_menu3[] = array('id' => $id3, 'text' => $text3, 'check' => $checked3, 'children' => $array_menu4);
				                reset($array_menu3);
				            }

					        $array_menu2[] = array('id' => $id2, 'text' => $text2, 'check' => $checked2, 'children' => $array_menu3);
					        reset($array_menu2);

				        }   

		            }

			        $array_menu[] = array('id' => $id1, 'text' => $text1, 'check' => $checked1, 'children' => $array_menu2);
			        reset($array_menu2);

		        }else{
		            $array_menu[] = array('id' => $id1, 'text' => $text1, 'check' => $checked1, 'children' => array());
		        }   
		    }
		}
		
		return json_encode($array_menu);	
	}

	public static function adminmenuperfil($opcion, $id_perfil, $array) {
		//opcion para guardar o modificar las opciones de menu por perfil
		//opcion: 1. crear, 2. modificar
		if($opcion == 1){
			//se recorre el arreglo y por cada opcion se crea un registro en menu perfil
			foreach ($array as $key => $value) {
				$nueva_opcion_perfil = new MenuPerfil;
				$nueva_opcion_perfil->Id_Perfil = $id_perfil;
				$nueva_opcion_perfil->Id_Menu = $value;
				$nueva_opcion_perfil->Estado = 1;
				$nueva_opcion_perfil->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$nueva_opcion_perfil->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$nueva_opcion_perfil->Fecha_Creacion = date('Y-m-d H:i:s');
				$nueva_opcion_perfil->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$nueva_opcion_perfil->save();
			}
		} else {

			//se arma un array con las opciones actuales de el perfil
			$criteria=new CDbCriteria;
			$criteria->condition='Id_Perfil=:Id_Perfil AND Estado=:Estado';
			$criteria->params=array(':Id_Perfil'=>$id_perfil,':Estado'=> 1);
		
			$array_opciones_actuales = array();

			$modelo_opciones_act=MenuPerfil::model()->findAll($criteria);
			if(!is_null($modelo_opciones_act)){
				foreach ($modelo_opciones_act as $moa) {
					array_push($array_opciones_actuales, $moa->Id_Menu);
				}
			}

			$opciones_add = array_diff($array, $array_opciones_actuales);
			$opciones_inac = array_diff($array_opciones_actuales, $array);

			//se recorren las opciones a añadir: primero se buscan para saber si no existen
			foreach ($opciones_add as $key => $value) {
				$criteria=new CDbCriteria;
				$criteria->condition='Id_Menu=:Id_Menu AND Id_Perfil=:Id_Perfil AND Estado=:Estado';
				$criteria->params=array(':Id_Menu'=>$value,':Id_Perfil'=>$id_perfil,':Estado'=> 0);
				$modelo_opcion=MenuPerfil::model()->find($criteria);
				if(!is_null($modelo_opcion)){
					$modelo_opcion->Estado = 1;
					$modelo_opcion->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$modelo_opcion->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$modelo_opcion->save();
				}else{
					$nueva_opcion_perfil = new MenuPerfil;
					$nueva_opcion_perfil->Id_Perfil = $id_perfil;
					$nueva_opcion_perfil->Id_Menu = $value;
					$nueva_opcion_perfil->Estado = 1;
					$nueva_opcion_perfil->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
					$nueva_opcion_perfil->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$nueva_opcion_perfil->Fecha_Creacion = date('Y-m-d H:i:s');
					$nueva_opcion_perfil->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$nueva_opcion_perfil->save();
				}
			}

			//se recorren las opciones a inactivar
			foreach ($opciones_inac as $key => $value) {
				$criteria=new CDbCriteria;
				$criteria->condition='Id_Menu=:Id_Menu AND Id_Perfil=:Id_Perfil';
				$criteria->params=array(':Id_Menu'=>$value,':Id_Perfil'=>$id_perfil);
				$modelo_opcion=MenuPerfil::model()->find($criteria);
				$modelo_opcion->Estado = 0;
				$modelo_opcion->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$modelo_opcion->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$modelo_opcion->save();
			}
		}

	}

	public static function loadmenu() {
		//se obtienen los perfiles asociados al usuario
		$perfiles = implode(",", Yii::app()->user->getState('array_perfiles'));

		$array_opciones_menu = array();
		//se traen los id de menu asociados al perfil o perfiles del usuario
		$criteria = new CDbCriteria;
		$criteria->select = 'DISTINCT Id_Menu';
		$criteria->condition='Id_Perfil in ('.$perfiles.') AND Estado=:Estado';
		$criteria->params=array(':Estado'=> 1);
		$opciones_x_perfiles=MenuPerfil::model()->findAll($criteria);
		foreach ($opciones_x_perfiles as $oxp) {
		    array_push($array_opciones_menu, $oxp->Id_Menu);   
		}

		$criteria1=new CDbCriteria;
		$criteria1->condition='Id_Padre=:Id_Padre AND Estado=:Estado AND Id_Menu != 1';
		$criteria1->params=array(':Id_Padre'=>1,':Estado'=> 1);
		$criteria1->order= 'orden';
		$level1=Menu::model()->findAll($criteria1);
		$array_menu = array();
		if (!is_null($level1)) {
		    foreach ($level1 as $l1) {
		        $id1 = $l1->Id_Menu;
		        $text1 = $l1->Descripcion;
		        $long_text1 = $l1->Descripcion_Larga;
		        $dd1 = $l1->Descarga_Directa;
		        $link1 = $l1->Link;
		        if (in_array($id1, $array_opciones_menu)) { $visible1 = 1; } else { $visible1 = 0; }
		        $criteria2=new CDbCriteria;
		        $criteria2->condition='Id_Padre=:Id_Padre AND Estado=:Estado';
		        $criteria2->params=array(':Id_Padre'=> $id1,':Estado'=> 1);
		        $criteria2->order= 'orden';
		        $level2=Menu::model()->findAll($criteria2);
		        $array_menu2 = array(); 
		        if (!is_null($level2)) {
		            foreach ($level2 as $l2) {
		                $id2 = $l2->Id_Menu;
		                $text2 = $l2->Descripcion;
		                $long_text2 = $l2->Descripcion_Larga;
				        $dd2 = $l2->Descarga_Directa;
				        $link2 = $l2->Link;
				        if (in_array($id2, $array_opciones_menu)) { $visible2 = 1; } else { $visible2 = 0; }
		                $criteria3=new CDbCriteria;
		                $criteria3->condition='Id_Padre=:Id_Padre AND Estado=:Estado';
		                $criteria3->params=array(':Id_Padre'=> $id2,':Estado'=> 1);
		                $criteria3->order= 'orden';
		                $level3=Menu::model()->findAll($criteria3); 
		                $array_menu3 = array();
		                if (!is_null($level3)) {
				            foreach ($level3 as $l3) {
				                $id3 = $l3->Id_Menu;
				                $text3 = $l3->Descripcion;
				                $long_text3 = $l3->Descripcion_Larga;
						        $dd3 = $l3->Descarga_Directa;
						        $link3 = $l3->Link;
						        if (in_array($id3, $array_opciones_menu)) { $visible3 = 1; } else { $visible3 = 0; }
				                $criteria4=new CDbCriteria;
				                $criteria4->condition='Id_Padre=:Id_Padre AND Estado=:Estado';
				                $criteria4->params=array(':Id_Padre'=> $id3,':Estado'=> 1);
				                $criteria4->order= 'orden';
				                $level4=Menu::model()->findAll($criteria4); 
				                $array_menu4 = array();
				                if (!is_null($level4)) {
				                    foreach ($level4 as $l4) {
				                        $id4 = $l4->Id_Menu;
				                        $text4 = $l4->Descripcion;
				                        $long_text4 = $l4->Descripcion_Larga;
								        $dd4 = $l4->Descarga_Directa;
								        $link4 = $l4->Link;
								        if (in_array($id4, $array_opciones_menu)) { $visible4 = 1; } else { $visible4 = 0; }
								        if($visible4 == 1){
								        	$array_menu4[] = array('id' => $id4, 'text' => $text4, 'long_text' => $long_text4, 'dd' => $dd4, 'link' => $link4, 'children' => array());
								        }
				                        
				                    }    
				                }
				                if($visible3 == 1){
				                	$array_menu3[] = array('id' => $id3, 'text' => $text3, 'long_text' => $long_text3, 'dd' => $dd3, 'link' => $link3, 'children' => $array_menu4);
				                	reset($array_menu3);
				            	}
				            }

				        	if($visible2 == 1){
				        		$array_menu2[] = array('id' => $id2, 'text' => $text2, 'long_text' => $long_text2, 'dd' => $dd2, 'link' => $link2, 'children' => $array_menu3);
				        		reset($array_menu2);
			        		}

				        }   

		            }

		            if($visible1 == 1){
		        		$array_menu[] = array('id' => $id1, 'text' => $text1, 'long_text' => $long_text1, 'dd' => $dd2, 'link' => $link1, 'children' => $array_menu2);
		        		reset($array_menu2);
	        		}
		        }else{
	        		if($visible1 == 1){
		            	$array_menu[] = array('id' => $id1, 'text' => $text1, 'long_text' => $long_text1, 'dd' => $dd2, 'link' => $link1, 'children' => array());
		        	}
		        }   
		    }
		}

	return json_encode($array_menu);

	}

	public static function searchopcion($filtro) {
		//se obtienen los perfiles asociados al usuario
		$perfiles = implode(",", Yii::app()->user->getState('array_perfiles'));

		$array_opciones_menu = array();
		//se traen los id de menu asociados al perfil o perfiles del usuario
		$criteria = new CDbCriteria;
		$criteria->select = 'DISTINCT Id_Menu';
		$criteria->condition='Id_Perfil in ('.$perfiles.') AND Estado=:Estado';
		$criteria->params=array(':Estado'=> 1);
		$opciones_x_perfiles=MenuPerfil::model()->findAll($criteria);
		foreach ($opciones_x_perfiles as $oxp) {
		    array_push($array_opciones_menu, $oxp->Id_Menu);   
		}

		$e = implode(",", $array_opciones_menu);

		$resp = Yii::app()->db->createCommand("
			SELECT TOP 5 M.Id_Menu 
			FROM T_PR_MENU M
			WHERE M.Estado = 1 AND (M.Descripcion LIKE '%".$filtro."%' OR M.Descripcion_Larga LIKE '%".$filtro."%') AND M.Link != '#'
			AND (SELECT COUNT(*) FROM T_PR_MENU WHERE Id_Padre = M.Id_Menu) < = 0 
			 AND M.Id_Menu In (".$e.")
		")->queryAll();

        return $resp;


	}

}
