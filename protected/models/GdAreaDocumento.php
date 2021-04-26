<?php

/**
 * This is the model class for table "T_PR_GD_AREA_DOCUMENTO".
 *
 * The followings are the available columns in table 'T_PR_AREA_DOCUMENTO':
 * @property integer $Id_A_Documento
 * @property integer $Id_Documento
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property integer $Id_Area
 * @property integer $Estado
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THDOCUMENTO $idDocumento
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 */
class GdAreaDocumento extends CActiveRecord
{
	public $clasif_doc;
	public $tipo_doc;
	public $num_doc;
	public $tit_doc;
	public $n_v_doc;
	public $orderby;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_GD_AREA_DOCUMENTO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Documento, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_Area, Estado, Fecha_Creacion, Fecha_Actualizacion', 'required'),
			array('Id_Documento, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_Area, Estado', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_A_Documento, Id_Documento, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_Area, Estado, Fecha_Creacion, Fecha_Actualizacion, clasif_doc, tipo_doc, num_doc, tit_doc, n_v_doc, orderby', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'iddocumento' => array(self::BELONGS_TO, 'GdDocumento', 'Id_Documento'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
			'idarea' => array(self::BELONGS_TO, 'Area', 'Id_Area'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_A_Documento' => 'ID',
			'Id_Documento' => 'ID docto',
			'Id_Area' => 'Área',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Estado' => 'Estado',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'orderby' => 'Orden de resultados',
			'clasif_doc' => 'Clasificación',
			'tipo_doc' => 'Tipo',
			'num_doc' => 'N° documento',
			'tit_doc' => 'Nombre',
			'n_v_doc' => 'Nivel de revisión',
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

		$criteria->with=array('iddocumento','idusuariocre','idusuarioact','idarea');

		$criteria->compare('t.Id_A_Documento',$this->Id_A_Documento);
		$criteria->compare('Id_Documento',$this->Id_Documento);
		

		if($this->clasif_doc != ""){
	    	$criteria->compare('iddocumento.Clasificacion',$this->clasif_doc,true);
		}

		if($this->tipo_doc != ""){
	    	$criteria->compare('iddocumento.Tipo',$this->tipo_doc,true);
		}

	   	if($this->num_doc != ""){
			$criteria->AddCondition("iddocumento.Num_Documento = '".$this->num_doc."'"); 
	    }

	    if($this->tit_doc != ""){
	    	$criteria->compare('iddocumento.Titulo',$this->tit_doc,true);
		}

		if($this->n_v_doc != ""){
	    	$criteria->compare('iddocumento.Nivel_Revision',$this->n_v_doc,true);
		}

		$criteria->compare('t.Id_Area',$this->Id_Area);

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
			$criteria->AddCondition("t.Usuario = ".$this->Id_Usuario_Creacion); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

	    $criteria->compare('t.Estado',$this->Estado);

		if(empty($this->orderby)){
			$criteria->order = 't.Id_A_Documento DESC';
		}else{
			switch ($this->orderby) {
				case 1:
			        $criteria->order = 't.Id_A_Documento ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_A_Documento DESC '; 
			        break;
			    case 3:
			        $criteria->order = 't.Id_Documento ASC'; 
			        break;
			    case 4:
			        $criteria->order = 't.Id_Documento DESC '; 
			        break;
				case 5:
			        $criteria->order = 'iddocumento.Num_Documento ASC'; 
			        break;
			    case 6:
			        $criteria->order = 'iddocumento.Num_Documento DESC '; 
			        break;
			    case 7:
			        $criteria->order = 'iddocumento.Titulo ASC'; 
			        break;
			    case 8:
			        $criteria->order = 'iddocumento.Titulo DESC '; 
			        break;
		        case 9:
			        $criteria->order = 'idarea.Area ASC'; 
			        break;
			    case 10:
			        $criteria->order = 'idarea.Area DESC'; 
			        break;
		        case 11:
			        $criteria->order = 'idusuariocre.Usuario ASC'; 
			        break;
			    case 12:
			        $criteria->order = 'idusuariocre.Usuario DESC'; 
			        break;
			    case 13:
			        $criteria->order = 't.Fecha_Creacion ASC'; 
			        break;
			    case 14:
			        $criteria->order = 't.Fecha_Creacion DESC'; 
			        break;
			    case 15:
			        $criteria->order = 'idusuarioact.Usuario ASC'; 
			        break;
			    case 16:
			        $criteria->order = 'idusuarioact.Usuario DESC'; 
			        break;
				case 17:
			        $criteria->order = 't.Fecha_Actualizacion ASC'; 
			        break;
			    case 18:
			        $criteria->order = 't.Fecha_Actualizacion DESC'; 
			        break;
			    case 19:
			        $criteria->order = 't.Estado DESC'; 
			        break;
			    case 20:
			        $criteria->order = 't.Estado ASC'; 
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
	 * @return AreaDocumento the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
