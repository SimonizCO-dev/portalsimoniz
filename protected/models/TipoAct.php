<?php

/**
 * This is the model class for table "TH_TIPO_ACT".
 *
 * The followings are the available columns in table 'TH_TIPO_ACT':
 * @property integer $Id_Tipo
 * @property integer $Clasificacion
 * @property integer $Padre
 * @property integer $Id_Grupo
 * @property string $Tipo
 * @property integer $Cantidad
 * @property string $Fecha_Inicio
 * @property string $Fecha_Fin
 * @property string $Ind_Alto
 * @property string $Ind_Medio
 * @property string $Ind_Bajo
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THDOMINIO $idGrupo
 * @property THUSUARIOS $idUsuarioCreacion
 * @property THUSUARIOS $idUsuarioActualizacion
 */
class TipoAct extends CActiveRecord
{
	public $Usuarios;
	public $orderby;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_TIPO_ACT';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Clasificacion, Id_Grupo, Tipo, Usuarios, Cantidad, Ind_Alto, Ind_Medio, Ind_Bajo, Id_Usuario_Creacion', 'required','on'=>'create'),
			array('Clasificacion, Id_Grupo, Tipo, Usuarios, Estado, Cantidad, Ind_Alto, Ind_Medio, Ind_Bajo, Id_Usuario_Creacion', 'required','on'=>'update'),
			array('Id_Tipo, Id_Grupo, Cantidad, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Tipo', 'length', 'max'=>100),
			array('Padre', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Tipo, Clasificacion, Id_Grupo, Padre, Tipo, Cantidad, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion, orderby', 'safe', 'on'=>'search'),
		);
	}

	public function DescEstado($estado){

		switch ($estado) {
		    case 0:
		        $texto_estado = 'INACTIVO';
		        break;
		    case 1:
		        $texto_estado = 'EN CURSO';
		        break;
		    case 2:
		        $texto_estado = 'FINALIZADO';
		        break;
		    case 3:
		        $texto_estado = 'POSPUESTO';
		        break;		    
		}

		return $texto_estado;

	}

	public function DescClasif($clasificacion){

		switch ($clasificacion) {
		    case 1:
		        $texto_clasif = 'GENERAL';
		        break;
		    case 2:
		        $texto_clasif = 'DISPONIBILIDAD';
		        break;		    
		}

		return $texto_clasif;

	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idgrupo' => array(self::BELONGS_TO, 'Dominio', 'Id_Grupo'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
			'idpadre' => array(self::BELONGS_TO, 'TipoAct', 'Padre'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Tipo' => 'ID',
			'Clasificacion' => 'Clasificación',
			'Padre' => 'Padre',
			'Id_Grupo' => 'Grupo',
			'Tipo' => 'Tipo',
			'Cantidad' => 'Cantidad',
			'Fecha_Inicio' => 'Fecha inicial',
			'Fecha_Fin' => 'Fecha final',
			'Ind_Alto' => 'Ind. alto',
			'Ind_Medio' => 'Ind. medio',
			'Ind_Bajo' => 'Ind. bajo',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización ',
			'Usuarios' => 'Usuarios',
			'orderby' => 'Orden de resultados',
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
	   	$criteria->with=array('idusuariocre','idusuarioact', 'idgrupo');

		$criteria->compare('t.Id_Tipo',$this->Id_Tipo);
		$criteria->compare('t.Clasificacion',$this->Clasificacion);
		$criteria->compare('t.Id_Grupo',$this->Id_Grupo);
		$criteria->compare('t.Padre',$this->Padre);
		$criteria->compare('t.Ind_Alto',$this->Ind_Alto);
		$criteria->compare('t.Ind_Medio',$this->Ind_Medio);
		$criteria->compare('t.Ind_Bajo',$this->Ind_Bajo);
		$criteria->compare('t.Tipo',$this->Tipo,true);
		$criteria->compare('t.Estado',$this->Estado);
		
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

		if(empty($this->orderby)){
			$criteria->order = 't.Id_Tipo DESC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_Tipo ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Tipo DESC'; 
			        break;
			    case 3:
			        $criteria->order = 'idgrupo.Dominio ASC'; 
			        break;
			    case 4:
			        $criteria->order = 'idgrupo.Dominio DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.Tipo ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Tipo DESC'; 
			        break; 
		        case 7:
			        $criteria->order = 'idusuariocre.Usuario ASC'; 
			        break;
			    case 8:
			        $criteria->order = 'idusuariocre.Usuario DESC'; 
			        break;
			    case 9:
			        $criteria->order = 't.Fecha_Creacion ASC'; 
			        break;
			    case 10:
			        $criteria->order = 't.Fecha_Creacion DESC'; 
			        break;
			    case 11:
			        $criteria->order = 'idusuarioact.Usuario ASC'; 
			        break;
			    case 12:
			        $criteria->order = 'idusuarioact.Usuario DESC'; 
			        break;
				case 13:
			        $criteria->order = 't.Fecha_Actualizacion ASC'; 
			        break;
			    case 14:
			        $criteria->order = 't.Fecha_Actualizacion DESC'; 
			        break;
			}
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),		
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TipoAct the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
