<?php
/* @var $this EmpleadoController */
/* @var $model Empleado */

//para combos de tipos de identificación
$lista_tipos_ident = CHtml::listData($tipos_ident, 'Id_Dominio', 'Dominio'); 

//para combos de ciudades
$lista_ciudades = CHtml::listData($ciudades, 'Id_Ciudad', 'Ciudad'); 

//para combos de grados de escolaridad
$lista_grados_escolaridad = CHtml::listData($grados_escolaridad, 'Id_Dominio', 'Dominio'); 

//para combos de estados civiles
$lista_estados_c = CHtml::listData($estado_c, 'Id_Dominio', 'Dominio'); 

//para combos de razas
$lista_razas = CHtml::listData($razas, 'Id_Dominio', 'Dominio'); 

//para combos de composición familiar
$lista_composicion_fam = CHtml::listData($composicion_familiar, 'Id_Dominio', 'Dominio');

//para combos de ocupaciones
$lista_ocupaciones = CHtml::listData($ocupaciones, 'Id_Dominio', 'Dominio'); 

//para combos de localidades
$lista_localidades = CHtml::listData($localidades, 'Id_Dominio', 'Dominio'); 

//para combos de rh
$lista_rh = CHtml::listData($rh, 'Id_Dominio', 'Dominio');

//para combos de géneros
$lista_generos = CHtml::listData($generos, 'Id_Dominio', 'Dominio');

//para combos de estratos
$lista_estratos = CHtml::listData($estratos, 'Id_Dominio', 'Dominio'); 

//para combos de parentesco de contacto
$lista_parentesco_contacto = CHtml::listData($parentescos_contacto, 'Id_Dominio', 'Dominio'); 

//para combos de eps
$lista_eps = CHtml::listData($eps, 'Id_Dominio', 'Dominio'); 

//para combos de cajas de compensacion
$lista_cajas = CHtml::listData($caja_c, 'Id_Dominio', 'Dominio'); 

//para combos de fondos de pensiones
$lista_pensiones = CHtml::listData($pensiones, 'Id_Dominio', 'Dominio'); 

//para combos de fondos de cesantias
$lista_cesantias = CHtml::listData($cesantias, 'Id_Dominio', 'Dominio'); 

//para combos de arls
$lista_arl = CHtml::listData($arl, 'Id_Dominio', 'Dominio'); 

//para combos de bancos
$lista_bancos = CHtml::listData($bancos, 'Id_Dominio', 'Dominio'); 

//para combos de tipos de cuenta
$lista_tipos_cuenta = CHtml::listData($tipos_cuenta, 'Id_Dominio', 'Dominio'); 

//para combos de tipos de regionales
$lista_regionales = CHtml::listData($regionales, 'Id_Regional', 'Regional'); 

?>



<h4>Creación de empleado</h4>

<?php $this->renderPartial('_form', array(
	'model'=>$model,
	'lista_tipos_ident' => $lista_tipos_ident,
	'lista_ciudades' => $lista_ciudades,
	'lista_grados_escolaridad' => $lista_grados_escolaridad,
	'lista_estados_c' => $lista_estados_c,
	'lista_razas' => $lista_razas,
	'lista_composicion_fam' => $lista_composicion_fam,
	'lista_ocupaciones' => $lista_ocupaciones,
	'lista_localidades' => $lista_localidades,
	'lista_rh' => $lista_rh,
	'lista_generos' => $lista_generos,
	'lista_estratos' => $lista_estratos,
	'lista_parentesco_contacto'=>$lista_parentesco_contacto,
	'lista_eps' => $lista_eps,
	'lista_cajas' => $lista_cajas,
	'lista_pensiones' => $lista_pensiones,
	'lista_cesantias' => $lista_cesantias,
	'lista_arl' => $lista_arl,
	'lista_bancos' => $lista_bancos,
	'lista_tipos_cuenta' => $lista_tipos_cuenta,
	'lista_regionales' => $lista_regionales,
)); ?>