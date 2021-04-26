<?php

/**
 * This is the model class for table "TH_FICHA_ITEM".
 *
 * The followings are the available columns in table 'TH_FICHA_ITEM':
 * @property integer $Id
 * @property string $Pais 
 * @property integer $Tipo
 * @property integer $Tipo_Producto
 * @property string $Codigo_Item
 * @property string $Referencia
 * @property string $Descripcion_Corta
 * @property string $Nombre_Funcional
 * @property string $Marca_Producto
 * @property string $Caracteristicas
 * @property string $Presentacion
 * @property string $Unidad_Medida_Inv
 * @property string $Unidad_Medida_Compra
 * @property string $Tipo_Inventario
 * @property string $Grupo_Impositivo
 * @property integer $Ind_Compra
 * @property integer $Ind_Manufactura
 * @property integer $Ind_Venta
 * @property integer $Maneja_Lote
 * @property integer $Exento_Impuesto
 * @property integer $Tiempo_Reposicion
 * @property integer $Cant_Moq
 * @property integer $Stock_Minimo
 * @property string $Un_Medida
 * @property integer $Un_Cant
 * @property string $Un_Peso
 * @property string $Un_Largo
 * @property string $Un_Ancho
 * @property string $Un_Alto
 * @property string $Un_Volumen
 * @property string $Un_Gtin
 * @property string $Ep_Medida
 * @property integer $Ep_Cant
 * @property string $Ep_Peso
 * @property string $Ep_Largo
 * @property string $Ep_Ancho
 * @property string $Ep_Alto
 * @property string $Ep_Volumen
 * @property string $Ep_Gtin
 * @property string $Cad_Medida
 * @property integer $Cad_Cant
 * @property string $Cad_Peso
 * @property string $Cad_Largo
 * @property string $Cad_Ancho
 * @property string $Cad_Alto
 * @property string $Cad_Volumen
 * @property string $Cad_Gtin
 * @property string $Crit_Origen
 * @property string $Crit_Tipo
 * @property string $Crit_Clasificacion
 * @property string $Crit_Clase
 * @property string $Crit_Marca
 * @property string $Crit_Submarca
 * @property string $Crit_Segmento
 * @property string $Crit_Familia
 * @property string $Crit_Linea
 * @property string $Crit_Subfamilia
 * @property string $Crit_Sublinea
 * @property string $Crit_Grupo
 * @property string $Crit_UN
 * @property string $Crit_Fabrica
 * @property string $Crit_Cat_Oracle
 * @property string $Descripcion_Larga
 * @property string $Instalaciones
 * @property string $Bodegas
 * @property integer $Id_Usuario_Solicitud
 * @property string $Fecha_Hora_Solicitud
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Hora_Actualizacion
 * @property integer $Estado_Solicitud
 * @property string $Observaciones
 * @property integer $Estado_Solicitud
 * @property integer $Step
 * @property integer $Step_Rev
 * @property string $Posicion_Arancelar
 * @property integer $Origen
 * @property string $Envase
 *
 * The followings are the available model relations:
 * @property THUSUARIOS $idUsuarioSolicitud
 * @property THUSUARIOS $idUsuarioCreacion
 */
class FichaItem extends CActiveRecord
{
	public $comp;
	public $cant;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_FICHA_ITEM';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Pais, Tipo_Producto, Origen, Nombre_Funcional, Marca_Producto, Caracteristicas, Descripcion_Larga, Unidad_Medida_Inv, Unidad_Medida_Compra, Ind_Compra, Ind_Manufactura, Ind_Venta, Un_Medida', 'required','on'=>'desarrollo, v_desarrollo'),

			array('Tipo_Inventario, Grupo_Impositivo, Exento_Impuesto', 'required','on'=>'finanzas, v_finanzas'),

			array('Crit_Origen, Crit_Tipo, Crit_Clasificacion, Crit_Clase, Crit_Marca, Crit_Submarca, Crit_Segmento, Crit_Familia, Crit_Subfamilia, Crit_Linea, Crit_Sublinea, Crit_Grupo, Crit_UN, Crit_Fabrica, Crit_Cat_Oracle', 'required','on'=>'comercial, v_comercial'),

			array('Un_Medida, Un_Cant, Un_Peso, Un_Largo, Un_Ancho, Un_Alto, Ep_Medida, Ep_Cant, Ep_Peso, Ep_Largo, Ep_Ancho, Ep_Alto, Cad_Medida, Cad_Cant, Cad_Peso, Cad_Largo, Cad_Ancho, Cad_Alto', 'required','on'=>'ingenieria_pt_prom, v_ingenieria_pt_prom'),

			array('Un_Medida, Un_Cant, Un_Peso, Un_Largo, Un_Ancho, Un_Alto', 'required','on'=>'ingenieria, v_ingenieria'),			

			array('Codigo_Item, Referencia, Maneja_Lote, Tiempo_Reposicion, Cant_Moq, Stock_Minimo, Posicion_Arancelar, Instalaciones, Bodegas, Un_Gtin, Ep_Gtin, Cad_Gtin', 'required','on'=>'dat_maestros_pt_prom, v_dat_maestros_pt_prom'),
			
			array('Codigo_Item, Referencia, Maneja_Lote, Tiempo_Reposicion, Cant_Moq, Stock_Minimo, Posicion_Arancelar, Instalaciones, Bodegas', 'required','on'=>'dat_maestros, v_dat_maestros'),

			array('Codigo_Item, Tiempo_Reposicion, Cant_Moq, Stock_Minimo, Crit_Origen, Crit_Tipo, Crit_Clasificacion, Crit_Clase, Crit_Marca, Crit_Submarca, Crit_Segmento, Crit_Familia, Crit_Subfamilia, Crit_Linea, Crit_Sublinea, Crit_Grupo, Crit_UN, Crit_Fabrica, Crit_Cat_Oracle', 'required','on'=>'create2'),

			array('Crit_Origen, Crit_Tipo, Crit_Clasificacion, Crit_Clase, Crit_Marca, Crit_Submarca, Crit_Segmento, Crit_Familia, Crit_Subfamilia, Crit_Linea, Crit_Sublinea, Crit_Grupo, Crit_UN, Crit_Fabrica, Crit_Cat_Oracle', 'required','on'=>'v_comercial_act'),


			array('Tiempo_Reposicion, Cant_Moq, Stock_Minimo', 'required','on'=>'dat_maestros_act'),

			array('Codigo_Item, Tiempo_Reposicion, Cant_Moq, Stock_Minimo, Crit_Origen, Crit_Tipo, Crit_Clasificacion, Crit_Clase, Crit_Marca, Crit_Submarca, Crit_Segmento, Crit_Familia, Crit_Subfamilia, Crit_Linea, Crit_Sublinea, Crit_Grupo, Crit_UN, Crit_Fabrica, Crit_Cat_Oracle', 'required','on'=>'create2'),

			array('Estado_Solicitud', 'required','on'=>'aprobacion'),

			array('Step, Observaciones', 'required','on'=>'notas'),

			array('Tipo, Tipo_Producto, Ind_Compra, Ind_Manufactura, Ind_Venta, Maneja_Lote, Exento_Impuesto, Tiempo_Reposicion, Cant_Moq, Stock_Minimo, Un_Cant, Ep_Cant, Cad_Cant, Id_Usuario_Solicitud, Estado_Solicitud, Step', 'numerical', 'integerOnly'=>true),
			array('Codigo_Item, Referencia', 'length', 'max'=>20),
			array('Descripcion_Corta', 'length', 'max'=>75),
			array('Nombre_Funcional, Marca_Producto, Caracteristicas', 'length', 'max'=>20),
			array('Unidad_Medida_Inv, Unidad_Medida_Compra, Grupo_Impositivo, Un_Medida, Ep_Medida, Cad_Medida, Crit_Origen, Crit_Tipo, Crit_Clasificacion, Crit_Clase, Crit_Marca, Crit_Submarca, Crit_Segmento, Crit_Familia, Crit_Linea, Crit_Subfamilia, Crit_Sublinea, Crit_Grupo, Crit_UN, Crit_Fabrica, Crit_Cat_Oracle', 'length', 'max'=>4),
			array('Tipo_Inventario', 'length', 'max'=>12),
			array('Presentacion', 'length', 'max'=>10),
			array('Un_Peso, Un_Largo, Un_Ancho, Un_Alto, Un_Volumen, Ep_Peso, Ep_Largo, Ep_Ancho, Ep_Alto, Ep_Volumen, Cad_Peso, Cad_Largo, Cad_Ancho, Cad_Alto, Cad_Volumen', 'length', 'max'=>20),
			array('Un_Gtin, Ep_Gtin, Cad_Gtin', 'length', 'max'=>14),
			array('Descripcion_Larga', 'length', 'max'=>140),
			array('Envase', 'length', 'max'=>100),
			array('Instalaciones, Bodegas, Fecha_Hora_Solicitud', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Pais, Tipo, Tipo_Producto, Origen, Codigo_Item, Referencia, Descripcion_Corta, Nombre_Funcional, Marca_Producto, Caracteristicas, Unidad_Medida_Inv, Unidad_Medida_Compra, Tipo_Inventario, Grupo_Impositivo, Ind_Compra, Ind_Manufactura, Ind_Venta, Maneja_Lote, Exento_Impuesto, Tiempo_Reposicion, Cant_Moq, Stock_Minimo, Un_Medida, Un_Cant, Un_Peso, Un_Largo, Un_Ancho, Un_Alto, Un_Volumen, Un_Gtin, Ep_Medida, Ep_Cant, Ep_Peso, Ep_Largo, Ep_Ancho, Ep_Alto, Ep_Volumen, Ep_Gtin, Cad_Medida, Cad_Cant, Cad_Peso, Cad_Largo, Cad_Ancho, Cad_Alto, Cad_Volumen, Cad_Gtin, Crit_Origen, Crit_Tipo, Crit_Clasificacion, Crit_Clase, Crit_Marca, Crit_Submarca, Crit_Segmento, Crit_Familia, Crit_Linea, Crit_Subfamilia, Crit_Sublinea, Crit_Grupo, Crit_UN, Crit_Fabrica, Crit_Cat_Oracle, Descripcion_Larga, Instalaciones, Bodegas, Id_Usuario_Solicitud, Fecha_Hora_Solicitud, Estado_Solicitud', 'safe', 'on'=>'search'),
		);
	}

	public function DescTipo($tipo){

		switch ($tipo) {
		    case 1:
		        $texto_tipo = 'CREACIÓN';
		        break;
		    case 2:
		        $texto_tipo = 'ACTUALIZACIÓN';
		        break;    
		}

		return $texto_tipo;

	}

	public function DescTipoProducto($tipo_producto){

		switch ($tipo_producto) {
		    case 1:
		        $texto_tipo_producto = 'PRODUCTO TERMINADO';
		        break;
		    case 2:
		        $texto_tipo_producto = 'PRODUCTO EN PROCESO';
		        break;
		    case 3:
		        $texto_tipo_producto = 'POP';
		        break;
		    case 4:
		        $texto_tipo_producto = 'MATERIA PRIMA';
		        break;
		    case 5:
		        $texto_tipo_producto = 'PROMOCIÓN';
		        break;
		    case null:
		        $texto_tipo_producto = '-';
		        break;      
		}

		return $texto_tipo_producto;

	}

	public function DescPais($paises){

		$array_paises = explode(",", $paises);

		$texto_pais = "";

		foreach ($array_paises as $key => $value) {
			
			switch ($value) {
			    case 1:
			        $pais = 'COLOMBIA';
			        break;
			    case 2:
			        $pais = 'ECUADOR';
			        break;
			    case 3:
			        $pais = 'PERÚ';
			        break;
			    case 4:
			        $pais = 'CHILE';
			        break;
			}

			$texto_pais .= $pais.", ";
		}

		$texto = substr ($texto_pais, 0, -2);
		return $texto;

	}

	public function DescOrigen($origen){

		switch ($origen) {
		    case 1:
		        $texto_origen = 'NACIONAL';
		        break;
		    case 2:
		        $texto_origen = 'IMPORTADO';
		        break;    
		}

		return $texto_origen;

	}

	public function DescEstado($estado){

		switch ($estado) {
		    case 0:
		        $texto_estado = 'PEND. REVISIÓN';
		        break;
		    case 1:
		        $texto_estado = 'EN PROCESO';
		        break;
		    case 2:
		        $texto_estado = 'APROBADO';
		        break;
		    
		}

		return $texto_estado;

	}

	public function DescStep($step){

		switch ($step) {
		    case 2:
		        $texto_step = 'Verificación Desarrollo - Innovación / Mercadeo';
		        break;
		    case 3:
		        $texto_step = 'Finanzas / Contabilidad';
		        break;
		    case 4:
		        $texto_step = 'Verificación Finanzas / Contabilidad';
		        break;
		    case 5:
		        $texto_step = 'Comercial / Mercadeo';
		        break;
		    case 6:
		        $texto_step = 'Verificación Comercial / Mercadeo';
		        break;
		    case 7:
		        $texto_step = 'Ingeniería';
		        break;
		    case 8:
		        $texto_step = 'Verificación Ingeniería';
		        break;
		    case 9:
		        $texto_step = 'Datos Maestros';
		        break;
		    case 10:
		        $texto_step = 'Finalizado';
		        break;	    
		}

		return $texto_step;

	}

	public function DescUnidad($un){

		$unidad = Yii::app()->db->createCommand("SELECT f101_id, f101_descripcion FROM UnoEE1..t101_mc_unidades_medida WHERE f101_id_cia = 2 AND f101_id = '".$un."'")->queryRow();

		$desc = $unidad['f101_id'].' - '.$unidad['f101_descripcion'];

		return $desc;

	}

	public function searchByItem($filtro) {
        
        $resp = Yii::app()->db->createCommand("
		    SELECT TOP 10 I_ID_ITEM, CONCAT (I_ID_ITEM, ' - ', I_DESCRIPCION) AS DESCR FROM T_CF_ITEMS WHERE I_CIA = 2 AND I_ESTADO='ACTIVO' AND (I_ID_ITEM LIKE '%".$filtro."%' OR I_DESCRIPCION  LIKE '%".$filtro."%') ORDER BY DESCR 
		")->queryAll();
        return $resp;
        
 	}

 	public function searchById($filtro) {
 
        $resp = Yii::app()->db->createCommand("
		    SELECT I_ID_ITEM , CONCAT (I_ID_ITEM, ' - ', I_DESCRIPCION) AS DESCR FROM T_CF_ITEMS WHERE I_CIA = 2 AND I_ID_ITEM = '".$filtro."'")->queryAll();
        return $resp;

 	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idusuariosol' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Solicitud'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'Tipo' => 'Solicitud',
			'Tipo_Producto' => 'Tipo de ítem',
			'Codigo_Item' => 'Código',
			'Referencia' => 'Referencia',
			'Descripcion_Corta' => 'Descripción corta',
			'Nombre_Funcional' => 'Nombre funcional',
			'Marca_Producto' => 'Marca',
			'Caracteristicas' => 'Caracteristicas',
			'Presentacion' => 'Presentación',
			'Unidad_Medida_Inv' => 'Und. medida inventario',
			'Unidad_Medida_Compra' => 'Und. medida compra',
			'Tipo_Inventario' => 'Tipo inventario',
			'Grupo_Impositivo' => 'Grupo impositivo',
			'Ind_Compra' => 'Ind. compra',
			'Ind_Manufactura' => 'Ind. manufactura',
			'Ind_Venta' => 'Ind. venta',
			'Maneja_Lote' => 'Maneja lote',
			'Exento_Impuesto' => 'Exento impuesto',
			'Tiempo_Reposicion' => 'Tiempo reposición',
			'Cant_Moq' => 'Cant. moq',
			'Stock_Minimo' => 'Stock minimo',
			'Un_Medida' => 'Und. medida log.',
			'Un_Cant' => 'Cant.',
			'Un_Peso' => 'Peso',
			'Un_Largo' => 'Largo',
			'Un_Ancho' => 'Ancho',
			'Un_Alto' => 'Alto',
			'Un_Volumen' => 'Volumen',
			'Un_Gtin' => 'EAN 13',
			'Ep_Medida' => 'Und. medida emp. principal',
			'Ep_Cant' => 'Cant.',
			'Ep_Peso' => 'Peso',
			'Ep_Largo' => 'Largo',
			'Ep_Ancho' => 'Ancho',
			'Ep_Alto' => 'Alto',
			'Ep_Volumen' => 'Volumen',
			'Ep_Gtin' => 'EAN 14',
			'Cad_Medida' => 'Und. medida subemp. cadena',
			'Cad_Cant' => 'Cant.',
			'Cad_Peso' => 'Peso',
			'Cad_Largo' => 'Largo',
			'Cad_Ancho' => 'Ancho',
			'Cad_Alto' => 'Alto',
			'Cad_Volumen' => 'Volumen',
			'Cad_Gtin' => 'EAN 14 - 1',
			'Crit_Origen' => 'Origen',
			'Crit_Tipo' => 'Tipo',
			'Crit_Clase' => 'Clase',
			'Crit_Clasificacion' => 'Clasificación',
			'Crit_Marca' => 'Marca',
			'Crit_Submarca' => 'Sub-marca',
			'Crit_Segmento' => 'Segmento',
			'Crit_Familia' => 'Familia',
			'Crit_Linea' => 'Línea',
			'Crit_Subfamilia' => 'Sub-familia',
			'Crit_Sublinea' => 'Sub-línea',
			'Crit_Grupo' => 'Grupo',
			'Crit_UN' => 'Unidad de negocio',
			'Crit_Fabrica' => 'Fabrica',
			'Crit_Cat_Oracle' => 'Cat. oracle',
			'Descripcion_Larga' => 'Descripción larga / Componentes',
			'Instalaciones' => 'Instalaciones',
			'Bodegas' => 'Bodegas',
			'Id_Usuario_Solicitud' => 'Usuario que Solicito',
			'Fecha_Hora_Solicitud' => 'Fecha de solicitud',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizo',
			'Fecha_Hora_Actualizacion' => 'Ultima fecha de actualización',
			'Estado_Solicitud' => 'Estado',
			'Observaciones' => 'Observaciones',
			'Step' => 'Proceso',
			'Step_Rev' => 'Proceso que solicita reversión',
			'Pais' => 'País',
			'comp' => 'Componente',
			'cant' => 'Cant.',
			'Posicion_Arancelar' => 'Posición arancelaria',
			'Origen' => 'Origen',
			'Envase' => 'Envase',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->together  =  true;
	   	$criteria->with=array('idusuariosol');

		$criteria->compare('t.Id',$this->Id);

		if($this->Pais != ""){

			$array_paises = $this->Pais;

			$cond_pais_t = "";

			foreach ($array_paises as $key => $value) {
				$cond_pais_t .= "t.Pais LIKE ('%".$value."%') OR ";
			}

			$cond = substr($cond_pais_t, 0, -3);
			$criteria->AddCondition($cond);
	    }

		$criteria->compare('t.Tipo',$this->Tipo);
		$criteria->compare('t.Tipo_Producto',$this->Tipo_Producto);
		$criteria->compare('t.Codigo_Item',$this->Codigo_Item,true);
		$criteria->compare('t.Referencia',$this->Referencia,true);
		$criteria->compare('t.Descripcion_Corta',$this->Descripcion_Corta,true);
		$criteria->compare('t.Estado_Solicitud',$this->Estado_Solicitud);
		$criteria->compare('t.Step',$this->Step);

		if($this->Fecha_Hora_Solicitud != ""){
      		$fci = $this->Fecha_Hora_Solicitud." 00:00:00";
      		$fcf = $this->Fecha_Hora_Solicitud." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Hora_Solicitud', $fci, $fcf);
    	}

		if($this->Id_Usuario_Solicitud != ""){
			$criteria->AddCondition("t.Id_Usuario_Solicitud = ".$this->Id_Usuario_Solicitud); 
	    }

	    if($this->Fecha_Hora_Actualizacion != ""){
      		$fci = $this->Fecha_Hora_Actualizacion." 00:00:00";
      		$fcf = $this->Fecha_Hora_Actualizacion." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Hora_Actualizacion', $fci, $fcf);
    	}

		if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

	    $criteria->order = 't.Id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FichaItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
