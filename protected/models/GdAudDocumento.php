<?php

/**
 * This is the model class for table "T_PR_GD_AUD_DOCUMENTO".
 *
 * The followings are the available columns in table 'T_PR_AUD_DOCUMENTO':
 * @property integer $Id_Aud_Documento
 * @property integer $Id_Documento
 * @property integer $Accion
 * @property integer $Id_Usuario
 * @property string $Fecha_Hora
 *
 * The followings are the available model relations:
 * @property THDOCUMENTO $idAudDocumento
 * @property THUSUARIO $idUsuario
 */
class GdAudDocumento extends CActiveRecord
{
	
	public $fecha_inicial;
	public $fecha_final;
	
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
		return 'T_PR_GD_AUD_DOCUMENTO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Documento, Accion, Id_Usuario, Fecha_Hora', 'required'),
			array('Id_Documento, Accion, Id_Usuario', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Documento, Accion, Id_Usuario, fecha_inicial, fecha_final, orderby, num_doc, tit_doc, n_v_doc', 'safe', 'on'=>'search'),
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
			'idusuario' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Aud_Documento' => 'ID',
			'Id_Documento' => 'ID docto',
			'Accion' => 'Acción',
			'Id_Usuario' => 'Usuario',
			'Fecha_Hora' => 'Fecha y hora',
			'fecha_inicial' => 'Fecha inicial',
			'fecha_final' => 'Fecha final',
			'orderby' => 'Orden de resultados',
			'num_doc' => 'N° documento',
			'clasif_doc' => 'Clasificación',
			'tipo_doc' => 'Tipo',
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

		$criteria->together  =  true;
	   	$criteria->with=array('iddocumento','idusuario');

	   	$criteria->compare('t.Id_Documento',$this->Id_Documento);

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

	    if($this->Accion != ""){
			$criteria->AddCondition("t.Accion = '".$this->Accion."'"); 
	    }

		if($this->Id_Usuario != ""){
			$criteria->AddCondition("t.Id_Usuario = ".$this->Id_Usuario); 
	    }

	    if($this->fecha_inicial != "" && $this->fecha_final != ""){
      		$fci = $this->fecha_inicial." 00:00:00";
      		$fcf = $this->fecha_final." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Hora', $fci, $fcf);
    	}else{
    		if($this->fecha_inicial != ""){
	      		$fci = $this->fecha_inicial." 00:00:00";
	      		$fcf = $this->fecha_inicial." 23:59:59";

	      		$criteria->addBetweenCondition('t.Fecha_Hora', $fci, $fcf);
	    	}	
    	}

	    if(empty($this->orderby)){
			$criteria->order = 't.Fecha_Hora DESC';
		}else{
			switch ($this->orderby) {
				case 1:
			        $criteria->order = 't.Id_Documento ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Documento DESC '; 
			        break;
				case 3:
			        $criteria->order = 'iddocumento.Num_Documento ASC'; 
			        break;
			    case 4:
			        $criteria->order = 'iddocumento.Num_Documento DESC '; 
			        break;
			    case 5:
			        $criteria->order = 'iddocumento.Titulo ASC'; 
			        break;
			    case 6:
			        $criteria->order = 'iddocumento.Titulo DESC '; 
			        break;
			    case 7:
			        $criteria->order = 't.Accion ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Accion DESC'; 
			        break;
			    case 9:
			        $criteria->order = 'idusuario.Usuario ASC'; 
			        break;
			    case 10:
			        $criteria->order = 'idusuario.Usuario DESC'; 
			        break;
		        case 11:
			        $criteria->order = 't.Fecha_Hora ASC'; 
			        break;
			    case 12:
			        $criteria->order = 't.Fecha_Hora DESC'; 
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
	 * @return AudDocumento the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
