<?php

//clase creada para funciones relacionadas con el modelo de personal

class UtilidadesEmpleado {
   
	public static function cambioempresaempleado($id_empleado, $id_empresa) {
		
		$comando = Yii::app()->db->createCommand();
		$comando->update('T_PR_EMPLEADO', array(
		    'Id_Empresa'=> $id_empresa,
		    'Estado'=> 1,
		), 'Id_Empleado=:id_empleado', array(':id_empleado'=>$id_empleado));

	}

	public static function evaluarcambioempresaempleado($id_empleado, $id_contrato, $id_empresa) {
		//logica para saber si se esta modificando la empresa del contrato vigente del empleado
		$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id_empleado.' AND Id_M_Retiro IS NULL ORDER BY 1 DESC')->queryRow();

		$id_ult_contrato = $query_contrato['Id_Contrato'];

		if($id_contrato == $id_ult_contrato){
			$comando = Yii::app()->db->createCommand();
			$comando->update('T_PR_EMPLEADO', array(
			    'Id_Empresa'=> $id_empresa,
			), 'Id_Empleado=:id_empleado', array(':id_empleado'=>$id_empleado));
		}
	}


	public static function contratoactualempleado($id_empleado){

		$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id_empleado.' AND Id_M_Retiro IS NULL ORDER BY 1 DESC')->queryRow();

		$id_ult_contrato = $query_contrato['Id_Contrato'];

		return $id_ult_contrato;

	}

	public static function nombreempleado($id_empleado){

		$Empleado= Yii::app()->db->createCommand("
		    SELECT CONCAT (E.Apellido, ' ', E.Nombre, ' (', TI.Dominio, ' ', E.Identificacion, ')') AS Nombre_Apellido FROM T_PR_EMPLEADO E INNER JOIN T_PR_DOMINIO TI ON E.Id_Tipo_Ident = TI.Id_Dominio WHERE E.Id_Empleado = ".$id_empleado."
		")->queryRow();

		return $Empleado['Nombre_Apellido'];

	}

	public static function novedadcontratoempleado($id_contrato, $id_empleado, $empresa_act, $empresa_nueva, $unidad_gerencia_act, $unidad_gerencia_nueva, $area_act, $area_nueva, $subarea_act, $subarea_nueva, $cargo_act, $cargo_nuevo, $turno_act, $turno_nuevo, $fecha_ingreso_act, $fecha_ingreso_nueva, $salario_act, $salario_nuevo, $concepto_act, $concepto_nuevo, $restricciones_act, $restricciones_nuevas, $grupo_act, $grupo_nuevo, $string_trab_esp_act, $string_trab_esp_nuevos){

		$texto_novedad = "";
		$flag = 0;

		//empresa
		if($empresa_act != $empresa_nueva){
			$flag = 1;

			if(is_null($empresa_act)){
				$texto_empresa_act = 'SIN ASIGNAR';
			}else{
				$m_empresa_act = Empresa::model()->findByPk($empresa_act);
				$texto_empresa_act = $m_empresa_act->Descripcion;
			}

			$m_empresa_nueva = Empresa::model()->findByPk($empresa_nueva);
			$texto_empresa_nueva = $m_empresa_nueva->Descripcion;

			$texto_novedad .= "Empresa: ".$texto_empresa_act." / ".$texto_empresa_nueva.", ";
		}

		//unidad de gerencia
		if($unidad_gerencia_act != $unidad_gerencia_nueva){
			$flag = 1;

			if(is_null($unidad_gerencia_act)){
				$texto_ug_act = 'SIN ASIGNAR';
			}else{
				$m_unidad_gerencia_act = UnidadGerencia::model()->findByPk($unidad_gerencia_act);
				$texto_ug_act = $m_unidad_gerencia_act->Unidad_Gerencia;
			}

			$m_unidad_gerencia_nueva = UnidadGerencia::model()->findByPk($unidad_gerencia_nueva);
			$texto_ug_nueva = $m_unidad_gerencia_nueva->Unidad_Gerencia;

			$texto_novedad .= "Unidad de gerencia: ".$texto_ug_act." / ".$texto_ug_nueva.", ";
		}

		//área
		if($area_act != $area_nueva){
			$flag = 1;

			if(is_null($area_act)){
				$texto_area_act = 'SIN ASIGNAR';
			}else{
				$m_area_act = Area::model()->findByPk($area_act);
				$texto_area_act = $m_area_act->Area;
			}

			$m_area_nueva = Area::model()->findByPk($area_nueva);
			$texto_area_nueva = $m_area_nueva->Area;

			$texto_novedad .= "Área: ".$texto_area_act." / ".$texto_area_nueva.", ";
		}

		//subárea
		if($subarea_act != $subarea_nueva){
			$flag = 1;

			if(is_null($subarea_act)){
				$texto_subarea_act = 'SIN ASIGNAR';
			}else{
				$m_subarea_act = Subarea::model()->findByPk($subarea_act);
				$texto_subarea_act = $m_subarea_act->Subarea;
			}

			$m_subarea_nueva = Subarea::model()->findByPk($subarea_nueva);
			$texto_subarea_nueva = $m_subarea_nueva->Subarea;

			$texto_novedad .= "Subárea: ".$texto_subarea_act." / ".$texto_subarea_nueva.", ";
		}

		//cargo
		if($cargo_act != $cargo_nuevo){
			$flag = 1;

			if(is_null($cargo_act)){
				$texto_cargo_act = 'SIN ASIGNAR';
			}else{
				$m_cargo_act = Cargo::model()->findByPk($cargo_act);
				$texto_cargo_act = $m_cargo_act->Cargo;
			}

			$m_cargo_nuevo = Cargo::model()->findByPk($cargo_nuevo);
			$texto_cargo_nuevo = $m_cargo_nuevo->Cargo;

			$texto_novedad .= "Cargo: ".$texto_cargo_act." / ".$texto_cargo_nuevo.", ";
		}

		//turno
		if($turno_act != $turno_nuevo){
			$flag = 1;

			if(is_null($turno_act)){
				$texto_turno_act = 'SIN ASIGNAR';
			}else{
				$m_turno_act = Dominio::model()->findByPk($turno_act);
				$texto_turno_act = $m_turno_act->Dominio;
			}

			if(empty($turno_nuevo)){
				$texto_turno_nuevo = 'SIN ASIGNAR';
			}else{
				$m_turno_nuevo = Dominio::model()->findByPk($turno_nuevo);
				$texto_turno_nuevo = $m_turno_nuevo->Dominio;
			}

			$texto_novedad .= "Turno de trabajo: ".$texto_turno_act." / ".$texto_turno_nuevo.", ";
		}

		//fecha de ingreso
		if($fecha_ingreso_act != $fecha_ingreso_nueva){
			$flag = 1;

			$texto_novedad .= "Fecha de ingreso: ".UtilidadesVarias::textofecha($fecha_ingreso_act)." / ".UtilidadesVarias::textofecha($fecha_ingreso_nueva).", ";
		}

		if($salario_act != $salario_nuevo){
			$flag = 1;

			$texto_novedad .= "Salario: ".number_format($salario_act, 0)." / ".number_format($salario_nuevo, 0).", ";
		}

		//concepto examen
		if($concepto_act != $concepto_nuevo){
			$flag = 1;

			if(is_null($concepto_act)){
				$texto_concepto_act = 'SIN ASIGNAR';
			}else{
				$m_concepto_act = Dominio::model()->findByPk($concepto_act);
				$texto_concepto_act = $m_concepto_act->Dominio;
			}

			if(empty($concepto_nuevo)){
				$texto_concepto_nuevo = 'SIN ASIGNAR';
			}else{
				$m_concepto_nuevo = Dominio::model()->findByPk($concepto_nuevo);
				$texto_concepto_nuevo = $m_concepto_nuevo->Dominio;
			}

			$texto_novedad .= "Concepto de examen ocupacional: ".$texto_concepto_act." / ".$texto_concepto_nuevo.", ";
		}

		//restricciones
		if($restricciones_act != $restricciones_nuevas){
			$flag = 1;

			if(is_null($restricciones_act)){
				$texto_rest_act = 'SIN ASIGNAR';
			}else{
				$texto_rest_act = $restricciones_act;
			}

			if(empty($restricciones_nuevas)){
				$texto_rest_nuevas = 'SIN ASIGNAR';
			}else{
				$texto_rest_nuevas = $restricciones_nuevas;
			}			

			$texto_novedad .= "Restricciones: ".$texto_rest_act." / ".$texto_rest_nuevas.", ";
		}

		//grupo
		if($grupo_act != $grupo_nuevo){
			$flag = 1;

			if(is_null($grupo_act)){
				$texto_grupo_act = 'SIN ASIGNAR';
			}else{
				$m_grupo_act = Dominio::model()->findByPk($grupo_act);
				$texto_grupo_act = $m_grupo_act->Dominio;
			}

			if(empty($grupo_nuevo)){
				$texto_grupo_nuevo = 'SIN ASIGNAR';
			}else{
				$m_grupo_nuevo = Dominio::model()->findByPk($grupo_nuevo);
				$texto_grupo_nuevo = $m_grupo_nuevo->Dominio;
			}

			$texto_novedad .= "Grupo: ".$texto_grupo_act." / ".$texto_grupo_nuevo.", ";
		}

		//trabajos esp.

		if($string_trab_esp_act == ""){
			$array_te_act = array();
		}else{
			$array_te_act = explode(",", $string_trab_esp_act);
		}

		if($string_trab_esp_nuevos == ""){
			$array_te_nuevos = array();
		}else{
			$array_te_nuevos = explode(",", $string_trab_esp_nuevos);	
		}

		$diff_a = array_diff($array_te_act, $array_te_nuevos);

		$diff_b = array_diff($array_te_nuevos, $array_te_act);

		if(!empty($diff_a) || !empty($diff_b)){
			
			$flag = 1;

			$texto_te_act = "";

			if(empty($array_te_act)){
				$texto_te_act = 'SIN ASIGNAR';
			}else{

				foreach ($array_te_act as $key => $val) {
					$i = Dominio::model()->findByPk($val)->Dominio;
				    $texto_te_act .= "".$i.", ";
			  	}
				
				$texto_te_act = substr ($texto_te_act, 0, -2);
			}


			$texto_te_nuevo = "";

			if(empty($array_te_nuevos)){
				$texto_te_nuevo = 'SIN ASIGNAR';
			}else{

				foreach ($array_te_nuevos as $key => $val) {

					$i = Dominio::model()->findByPk($val)->Dominio;
				    $texto_te_nuevo .= "".$i.", ";
			  	}
				
				$texto_te_nuevo = substr ($texto_te_nuevo, 0, -2);
			}


			$texto_novedad .= "Trabajo(s) específico(s): ".$texto_te_act." / ".$texto_te_nuevo.", ";
		}

		//alguno de los criterios cambio
		if($flag == 1){
			$texto_novedad = substr ($texto_novedad, 0, -2);
			$nueva_novedad = new NovedadContrato;
			$nueva_novedad->Id_Contrato = $id_contrato;
			$nueva_novedad->Id_Empleado = $id_empleado;
			$nueva_novedad->Novedad = $texto_novedad;
			$nueva_novedad->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$nueva_novedad->Fecha_Creacion = date('Y-m-d H:i:s');
			$nueva_novedad->Id_Usuario_Actulizacion = Yii::app()->user->getState('id_user');
			$nueva_novedad->Fecha_Actualizacion = date('Y-m-d H:i:s');
			$nueva_novedad->save();
		}
	}

	public static function desasignarempresaempleado($id_empleado) {
		
			$comando = Yii::app()->db->createCommand();
			$comando->update('T_PR_EMPLEADO', array(
			    'Id_Empresa'=> 0,
			    'Estado'=> 0,
			), 'Id_Empleado=:id_empleado', array(':id_empleado'=>$id_empleado));
	}

	public static function unidadgerenciaactualempleado($id_empleado){

		$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id_empleado.' ORDER BY 1 DESC')->queryRow();

		$id_ult_contrato = $query_contrato['Id_Contrato'];

		if($id_ult_contrato != ''){
			$modelocontrato = ContratoEmpleado::model()->findByPk($id_ult_contrato);
			if(is_null($modelocontrato->Id_Unidad_Gerencia)){
				return 'SIN ASIGNAR';
			}else{
				return $modelocontrato->idunidadgerencia->Unidad_Gerencia;
			}
		}else{
			return 'SIN ASIGNAR';
		}

	}

	public static function areaactualempleado($id_empleado){

		$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id_empleado.' ORDER BY 1 DESC')->queryRow();

		$id_ult_contrato = $query_contrato['Id_Contrato'];

		if($id_ult_contrato != ''){
			$modelocontrato = ContratoEmpleado::model()->findByPk($id_ult_contrato);
			if(is_null($modelocontrato->Id_Area)){
				return 'SIN ASIGNAR';
			}else{
				return $modelocontrato->idarea->Area;
			}	
		}else{
			return 'SIN ASIGNAR';
		}

	}

	public static function subareaactualempleado($id_empleado){

		$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id_empleado.' ORDER BY 1 DESC')->queryRow();

		$id_ult_contrato = $query_contrato['Id_Contrato'];

		if($id_ult_contrato != ''){
			$modelocontrato = ContratoEmpleado::model()->findByPk($id_ult_contrato);
			if(is_null($modelocontrato->Id_Subarea)){
				return 'SIN ASIGNAR';
			}else{
				return $modelocontrato->idsubarea->Subarea;
			}
		}else{
			return 'SIN ASIGNAR';
		}

	}

	public static function cargoactualempleado($id_empleado){

		$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id_empleado.' ORDER BY 1 DESC')->queryRow();

		$id_ult_contrato = $query_contrato['Id_Contrato'];

		if($id_ult_contrato != ''){
			$modelocontrato = ContratoEmpleado::model()->findByPk($id_ult_contrato);
			if(is_null($modelocontrato->Id_Cargo)){
				return 'SIN ASIGNAR';
			}else{
				return $modelocontrato->idcargo->Cargo;
			}
		}else{
			return 'SIN ASIGNAR';
		}

	}

	public static function centrocostoactualempleado($id_empleado){

		$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id_empleado.' ORDER BY 1 DESC')->queryRow();

		$id_ult_contrato = $query_contrato['Id_Contrato'];

		if($id_ult_contrato != ''){
			$modelocontrato = ContratoEmpleado::model()->findByPk($id_ult_contrato);
			if(is_null($modelocontrato->Id_Centro_Costo)){
				return 'SIN ASIGNAR';
			}else{
				return $modelocontrato->idcentrocosto->Codigo.' - '.$modelocontrato->idcentrocosto->Centro_Costo;
			}
		}else{
			return 'SIN ASIGNAR';
		}

	}

	public static function estadoactualempleado($id_empleado){

		$modeloempleado = Empleado::model()->findByPk($id_empleado);

		if($modeloempleado->Estado == 1){
			return 'ACTIVO';
		}else{
			return 'INACTIVO';
		}

	}

	public static function tipoidentificacionempleado($id_empleado){

		$modeloempleado = Empleado::model()->findByPk($id_empleado);

		if($modeloempleado->Id_Tipo_Ident == ''){
			return 'SIN ASIGNAR';
		}else{
			return $modeloempleado->idtipoident->Dominio;
		}

	}

	public static function identificacionempleado($id_empleado){

		$modeloempleado = Empleado::model()->findByPk($id_empleado);

		if($modeloempleado->Identificacion == ''){
			return 'SIN ASIGNAR';
		}else{
			return $modeloempleado->Identificacion;
		}

	}

	public static function fechanacimientoempleado($id_empleado){

		$modeloempleado = Empleado::model()->findByPk($id_empleado);

		$fecha_nacimiento = $modeloempleado->Fecha_Nacimiento;

		if($fecha_nacimiento == ''){
			return 'SIN ASIGNAR';
		}else{

			$fecha = date_create($fecha_nacimiento);

			$diatxt = date_format($fecha, 'l');
			$dianro = date_format($fecha, 'd');
			$mestxt = date_format($fecha, 'F');
			$anionro = date_format($fecha, 'Y');
			
			// *********** traducciones y modificaciones de fechas a letras y a español ***********
			$ding=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
			$ming=array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
			$mesp=array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
			$desp=array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
			$mesn=array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
			$diaesp=str_replace($ding, $desp, $diatxt);
			$mesesp=str_replace($ming, $mesp, $mestxt);

			return $diaesp.", ".$dianro." de ".$mesesp." de ".$anionro;		
		}

	}

	public static function edadempleado($id_empleado){

		$modeloempleado = Empleado::model()->findByPk($id_empleado);

		$fecha_nacimiento = $modeloempleado->Fecha_Nacimiento;

		if($fecha_nacimiento == ''){
			return 'SIN ASIGNAR';
		}else{

			$dia=date("d");
			$mes=date("m");
			$ano=date("Y");

			$dianaz=date("d",strtotime($fecha_nacimiento));
			$mesnaz=date("m",strtotime($fecha_nacimiento));
			$anonaz=date("Y",strtotime($fecha_nacimiento));


			//si el mes es el mismo pero el día inferior aun no ha cumplido años, le quitaremos un año al actual

			if (($mesnaz == $mes) && ($dianaz > $dia)) {
			$ano=($ano-1); }

			//si el mes es superior al actual tampoco habrá cumplido años, por eso le quitamos un año al actual

			if ($mesnaz > $mes) {
			$ano=($ano-1);}

			 //ya no habría mas condiciones, ahora simplemente restamos los años y mostramos el resultado como su edad

			$edad=($ano-$anonaz);


			return $edad.' AÑOS';
		}

	}

	public static function fechaingresoempleado($id_empleado){

		$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id_empleado.' ORDER BY 1 DESC')->queryRow();

		$id_ult_contrato = $query_contrato['Id_Contrato'];

		if($id_ult_contrato != ''){
			$modelocontrato = ContratoEmpleado::model()->findByPk($id_ult_contrato);

			$fecha = date_create($modelocontrato->Fecha_Ingreso);

			$diatxt = date_format($fecha, 'l');
			$dianro = date_format($fecha, 'd');
			$mestxt = date_format($fecha, 'F');
			$anionro = date_format($fecha, 'Y');
			
			// *********** traducciones y modificaciones de fechas a letras y a español ***********
			$ding=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
			$ming=array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
			$mesp=array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
			$desp=array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
			$mesn=array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
			$diaesp=str_replace($ding, $desp, $diatxt);
			$mesesp=str_replace($ming, $mesp, $mestxt);

			return $diaesp.", ".$dianro." de ".$mesesp." de ".$anionro;	

		}else{
			return 'SIN ASIGNAR';
		}

	}

	public static function empresaactualempleado($id_empleado){

		$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Empresa FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id_empleado.' AND Id_M_Retiro IS NULL ORDER BY 1 DESC')->queryRow();

		$id_ult_empresa = $query_contrato['Id_Empresa'];

		if($id_ult_empresa != ''){
			$modelo_empresa = Empresa::model()->findByPk($id_ult_empresa);
			return $modelo_empresa->Descripcion;
		}else{
			return 'SIN ASIGNAR';
		}

	}


	public static function compfamempleado($array){

		$a_cf = explode(",", $array);

		$comp_fam = "";

		if(!empty($a_cf)){
			foreach ($a_cf as $key => $val) {

				$i = Dominio::model()->findByPk($val)->Dominio;

			    $comp_fam .= "".$i.", ";
		  	}
			
			$comp_fam = substr ($comp_fam, 0, -2);
			return $comp_fam;

		}else{
			return 'SIN ASIGNAR';
		}

	}

	public static function ocupmempleado($array){

		$a_ocup = explode(",", $array);

		$ocup = "";

		if(!empty($a_ocup)){
			foreach ($a_ocup as $key => $val) {

				$i = Dominio::model()->findByPk($val)->Dominio;

			    $ocup .= "".$i.", ";
		  	}
			
			$ocup = substr ($ocup, 0, -2);
			return $ocup;

		}else{
			return 'SIN ASIGNAR';
		}

	}



}
