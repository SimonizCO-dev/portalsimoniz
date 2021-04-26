<?php

/**
 * This is the model class for table "T_PR_NETWORK".
 *
 * The followings are the available columns in table 'T_PR_NETWORK':
 * @property integer $Id
 * @property string $Network
 * @property integer $Segment
 * @property integer $Host
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 */
class Network extends CActiveRecord
{
	public $id_red_1;
	public $id_red_2;
	public $ip;
	public $id_equipo;
	public $notas;
	public $ips;
	public $host_inicial;
	public $host_final;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_NETWORK';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_red_1, id_red_2, Segment','required','on'=>'create'),
			array('id_equipo','required','on'=>'asig'),
			array('ip','required','on'=>'asig2'),
			array('ips','required','on'=>'asigipdhcp'),
			//array('Id, Network, Segment, Host, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'required'),
			array('Id, Segment, Host, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Network', 'length', 'max'=>7),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Network, Segment, host_inicial, host_final, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'safe', 'on'=>'search'),
		);
	}

	public function Ip($Id) {

		$model = Network::model()->findByPk($Id);			
		return $model->Network.".".$model->Segment.".".$model->Host;

 	}

 	public function DescEstado($Id){

 		$model_n = Network::model()->findByPk($Id);

       switch ($model_n->Estado) {
		    case 1:
		        return "DISPONIBLE";
		    case 2:
		    	$model = NetworkEquipo::model()->findByAttributes(array('Id_Network'=>$model_n->Id, 'Estado'=>1));
 				$desc_equipo = UtilidadesVarias::descequipo($model->Id_Equipo);
		        return "ASIGNADA (".$desc_equipo.").";
		    case 3:
		    	return "ASIGNADA DHCP";
		}

    }

    public function searchByEquipoAsocNetwork($filtro) {
 		
 		$resp = Yii::app()->db->createCommand("
			SELECT 
			TOP 10
			E.Id_Equipo
		    FROM T_PR_EQUIPO E
		    LEFT JOIN T_PR_DOMINIO TI ON E.Tipo_Equipo = TI.Id_Dominio
			WHERE E.Estado = 1
			AND (TI.Dominio LIKE '%".$filtro."%' OR E.Serial LIKE '%".$filtro."%') AND (SELECT COUNT(*) FROM T_PR_NETWORK_EQUIPO NE WHERE NE.Id_Equipo = E.Id_Equipo AND NE.ESTADO = 1) < 2
		")->queryAll();

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
			'Id' => 'ID',
			'Network' => 'Red',
			'Segment' => 'Segmento',
			'Host' => 'Host',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Fecha_Creacion' => 'Fecha de creación',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'id_red_1' => 'ID de red 1',
			'id_red_2' => 'ID de red 2',
			'ip' => 'IP',
			'id_equipo' => 'Equipo',
			'notas' => 'Notas',
			'ips' => 'IP(s)',
			'host_inicial'=> 'Host inicial',
			'host_final' => 'Host final',

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
		$criteria->compare('Network',$this->Network,true);
		$criteria->compare('Segment',$this->Segment);
		$criteria->compare('Host',$this->Host);
		$criteria->compare('Estado',$this->Estado);

		if($this->host_inicial != "" && $this->host_final != ""){
      		$criteria->addBetweenCondition('t.Host', $this->host_inicial, $this->host_final);
    	}else{
    		if($this->host_inicial != "" && $this->host_final == ""){
    			$criteria->AddCondition("t.Host = ".$this->host_inicial);
    		}
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
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->usuario_creacion); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
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
	 * @return Network the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
