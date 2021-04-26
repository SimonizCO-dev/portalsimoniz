<?php

/**
 * This is the model class for table "TH_DIN_COM".
 *
 * The followings are the available columns in table 'TH_DIN_COM':
 * @property integer $Id_Dic_Com
 * @property string $Pais
 * @property integer $Tipo
 * @property string $Fecha_Inicio
 * @property string $Fecha_Fin
 * @property string $Item
 * @property string $Cliente
 * @property string $Lista_Precios
 * @property string $CO
 * @property string $Vlr_Min
 * @property string $Vlr_Max
 * @property integer $Cant_Min
 * @property integer $Cant_Max
 * @property integer $Cant_Ped
 * @property integer $Cant_Obs
 * @property string $Descuento
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THDINCOMITEM[] $tHDINCOMITEMs
 * @property THDINCOMCLIENTE[] $tHDINCOMCLIENTEs
 * @property THUSUARIOS $idUsuarioCreacion
 * @property THUSUARIOS $idUsuarioActualizacion
 */
class DinCom extends CActiveRecord
{
	
	public $Id_Plan_Cliente;
	public $Id_Criterio_Cliente;
	public $Id_Plan_Item;
	public $Id_Criterio_Item;
	
	public $Cad_Plan_Cliente;
	public $Cad_Criterio_Cliente;
	public $Cad_Plan_Item;
	public $Cad_Criterio_Item;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_DIN_COM';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Pais, Tipo', 'required', 'on' => 'create'),
			array('Estado', 'required', 'on' => 'update'),
			array('Tipo, Cant_Min, Cant_Max, Cant_Ped, Cant_Obs, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Item, Cliente', 'length', 'max'=>20),
			array('Lista_Precios, CO', 'length', 'max'=>10),
			array('Vlr_Min, Vlr_Max, Descuento', 'length', 'max'=>18),
			//array('Fecha_Inicio, Fecha_Fin', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Dic_Com, Pais, Tipo, Fecha_Inicio, Fecha_Fin, Item, Cliente, Lista_Precios, CO, Vlr_Min, Vlr_Max, Cant_Min, Cant_Max, Cant_Ped, Cant_Obs, Descuento, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'safe', 'on'=>'search'),
		);
	}

	public static function desctipo($tipo){

		switch ($tipo) {
		    case 1:
		        $texto_tipo = 'ITEM';
		        break;
		    case 2:
		        $texto_tipo = 'CLIENTE';
		        break;
		    case 3:
		        $texto_tipo = 'CRITERIO CLIENTE';
		        break;
		    case 4:
		        $texto_tipo = 'CRITERIO ITEM';
		        break;
		    case 5:
		        $texto_tipo = 'OBSEQUIO';
		        break;
		    case 6:
		        $texto_tipo = 'LISTA PRECIOS';
		        break;
		    case 7:
		        $texto_tipo = 'CO';
		        break;
		    case 8:
		        $texto_tipo = 'ITEM / CLIENTE';
		        break;
		    case 9:
		        $texto_tipo = 'ITEM / CRITERIO CLIENTE';
		        break;
		    case 10:
		        $texto_tipo = 'ITEM / LISTA DE PRECIOS';
		        break;
		    case 11:
		        $texto_tipo = 'ITEM / CO';
		        break;
		    case 12:
		        $texto_tipo = 'CRITERIO ITEM / CRITERIO CLIENTE';
		        break;
		    case 13:
		        $texto_tipo = 'CRITERIO ITEM / CLIENTE';
		        break;
		    case 14:
		        $texto_tipo = 'CRITERIO ITEM / LISTA DE PRECIOS';
		        break;
		    case 15:
		        $texto_tipo = 'CRITERIO ITEM / CO';
		        break;
		   	case 16:
		        $texto_tipo = 'CRITERIO CLIENTE / LISTA DE PRECIOS';
		        break;
		    case 17:
		        $texto_tipo = 'CRITERIO CLIENTE / CO';
		        break;
		    case 18:
		        $texto_tipo = 'CLIENTE / LISTA DE PRECIOS';
		        break;
		   	case 19:
		        $texto_tipo = 'CLIENTE / CO';
		        break;
		    case 20:
		        $texto_tipo = 'LISTA DE PRECIOS / CO';
		        break;
		}

		return $texto_tipo;

	}

	public static function desclistaprecios($id_lista){

		$q = Yii::app()->db->createCommand("SELECT DISTINCT f112_descripcion FROM UnoEE1..t112_mc_listas_precios WHERE f112_id = '".$id_lista."'")->queryRow();
		return $q['f112_descripcion'];
	}

	public static function descco($id_co){
		$q = Yii::app()->db->createCommand("SELECT DISTINCT f285_descripcion FROM UnoEE1..t285_co_centro_op WHERE f285_id = '".$id_co."'")->queryRow();
		return $q['f285_descripcion'];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Dic_Com' => 'ID',
			'Pais' => 'País',
			'Tipo' => 'Tipo',
			'Fecha_Inicio' => 'Fecha inicio',
			'Fecha_Fin' => 'Fecha fin',
			'Item' => 'Item',
			'Cliente' => 'Cliente',
			'Lista_Precios' => 'Lista de precios',
			'CO' => 'CO',
			'Vlr_Min' => 'Vlr. Min.',
			'Vlr_Max' => 'Vlr. Max.',
			'Cant_Min' => 'Cant. Min.',
			'Cant_Max' => 'Cant. Max.',
			'Cant_Ped' => 'Cant. Ped.',
			'Cant_Obs' => 'Cant. Obsequio',
			'Descuento' => '% Descuento',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',

			'Id_Plan_Cliente' => 'Plan cliente',
			'Id_Criterio_Cliente' => 'Criterio cliente',
			'Id_Plan_Item' => 'Plan item',
			'Id_Criterio_Item' => 'Criterio item',
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
	   	$criteria->with=array('idusuariocre','idusuarioact');

		$criteria->compare('Id_Dic_Com',$this->Id_Dic_Com);
		$criteria->compare('Tipo',$this->Tipo);
		$criteria->compare('Estado',$this->Estado);

		if($this->Pais != ""){

			$array_paises = $this->Pais;

			$cond_pais_t = "";

			foreach ($array_paises as $key => $value) {
				$cond_pais_t .= "t.Pais LIKE ('%".$value."%') OR ";
			}

			$cond = substr($cond_pais_t, 0, -3);
			$criteria->AddCondition($cond);
	    }

		if($this->Fecha_Creacion != ""){
      		$fci = $this->Fecha_Creacion." 00:00:00";
      		$fcf = $this->Fecha_Creacion." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Creacion', $fci, $fcf);
    	}

    	if($this->Fecha_Actualizacion != ""){
      		$fai = $this->Fecha_Actualizacion." 00:00:00";
      		$faf = $this->Fecha_Actualizacion." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Actualizacion', $fai, $faf);
    	}

		if($this->Id_Usuario_Creacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Creacion); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

		$criteria->order = 't.Id_Dic_Com DESC'; 	

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),		
		));

	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DinCom the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
