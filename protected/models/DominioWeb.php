<?php

/**
 * This is the model class for table "T_PR_DOMINIO_WEB".
 *
 * The followings are the available columns in table 'T_PR_DOMINIO_WEB':
 * @property integer $Id_Dominio_Web
 * @property string $Link
 * @property string $Usuario
 * @property string $Password
 * @property string $Empresa_Administradora
 * @property string $Contacto_Emp_Adm
 * @property string $Contratado_Por
 * @property string $Uso
 * @property string $Fecha_Activacion
 * @property string $Fecha_Vencimiento
 * @property string $Observaciones
 * @property boolean $Estado
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 * @property string $Dominio
 * @property integer $Id_Tipo
 *
 * The followings are the available model relations:
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 * @property THDOMINIO $idTipo
 */
class DominioWeb extends CActiveRecord
{
	public $orderby;
	public $view;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_DOMINIO_WEB';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Link, Usuario, Password, Empresa_Administradora, Contacto_Emp_Adm, Contratado_Por, Uso, Fecha_Activacion, Fecha_Vencimiento, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion, Dominio, Id_Tipo', 'required'),
			array('Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_Tipo', 'numerical', 'integerOnly'=>true),
			array('Link, Usuario, Password, Empresa_Administradora, Contacto_Emp_Adm, Uso, Dominio, Contratado_Por', 'length', 'max'=>100),
			array('Observaciones', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Dominio_Web, Link, Usuario, Password, Empresa_Administradora, Contacto_Emp_Adm, Contratado_Por, Uso, Fecha_Activacion, Fecha_Vencimiento, Observaciones, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion, Dominio, Id_Tipo, orderby, view', 'safe', 'on'=>'search'),
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
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
			'idtipo' => array(self::BELONGS_TO, 'Dominio', 'Id_Tipo'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Dominio_Web' => 'ID',
			'Link' => 'Link',
			'Usuario' => 'Usuario',
			'Password' => 'Password',
			'Empresa_Administradora' => 'Empresa administradora',
			'Contacto_Emp_Adm' => 'Contacto empresa administradora',
			'Contratado_Por' => 'Contratado por',
			'Uso' => 'Uso',
			'Fecha_Activacion' => 'Fecha de activación',
			'Fecha_Vencimiento' => 'Fecha de vencimiento',
			'Observaciones' => 'Observaciones',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'orderby' => 'Orden de resultados',
			'Dominio' => 'Dominio',
			'Id_Tipo' => 'Tipo',
			'view'=>'Ver',
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
		$criteria->with=array('idtipo');

		$criteria->compare('t.Id_Dominio_Web',$this->Id_Dominio_Web);
		$criteria->compare('t.Link',$this->Link,true);
		$criteria->compare('t.Dominio',$this->Dominio,true);
		$criteria->compare('t.Contratado_Por',$this->Contratado_Por, true);
		$criteria->compare('t.Estado',$this->Estado);

		if($this->Fecha_Activacion != ""){
      		$fci = $this->Fecha_Activacion." 00:00:00";
      		$fcf = $this->Fecha_Activacion." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Activacion', $fci, $fcf);
    	}

    	if($this->Fecha_Vencimiento != ""){
      		$fai = $this->Fecha_Vencimiento." 00:00:00";
      		$faf = $this->Fecha_Vencimiento." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Vencimiento', $fai, $faf);
    	}

    	if($this->Id_Tipo != ""){
			$criteria->AddCondition("t.Id_Tipo = '".$this->Id_Tipo."'"); 
	    }

	    if(!empty($this->view)){
			switch ($this->view) {
			    case 1:
			        $criteria->AddCondition("DATEDIFF(day,'".date('Y-m-d')."',t.Fecha_Vencimiento) < 45 AND t.Estado = 1");
			        break;
			    case 2:
			        $criteria->AddCondition("DATEDIFF(day,'".date('Y-m-d')."',t.Fecha_Vencimiento) >= 45 AND t.Estado = 1"); 
			        break;
			    case 3:
			        $criteria->AddCondition("t.Estado = 0");
			        break;
			}			
		}

 		if(empty($this->orderby)){
			$criteria->order = 'idtipo.Dominio ASC, t.Dominio ASC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 'idtipo.Dominio ASC'; 
			        break;
			    case 2:
			        $criteria->order = 'idtipo.Dominio DESC'; 
			        break;
			    case 3:
			        $criteria->order = 't.Dominio ASC'; 
			        break;
			    case 4:
			        $criteria->order = 't.Dominio DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.Link ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Link DESC'; 
			        break;
			    case 7:
			        $criteria->order = 't.Contratado_Por ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Contratado_Por DESC'; 
			        break;
			    case 9:
			        $criteria->order = 't.Fecha_Activacion ASC'; 
			        break;
			    case 10:
			        $criteria->order = 't.Fecha_Activacion DESC'; 
			        break;
			    case 11:
			        $criteria->order = 't.Fecha_Vencimiento ASC'; 
			        break;
			    case 12:
			        $criteria->order = 't.Fecha_Vencimiento DESC'; 
			        break; 
			    case 13:
			        $criteria->order = 't.Estado DESC'; 
			        break;
			    case 14:
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
	 * @return DominioWeb the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
