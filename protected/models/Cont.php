<?php

/**
 * This is the model class for table "T_PR_CONT".
 *
 * The followings are the available columns in table 'T_PR_CONT':
 * @property integer $Id_Contrato
 * @property integer $Empresa
 * @property string $Proveedor
 * @property string $Concepto_Contrato
 * @property string $Fecha_Inicial
 * @property string $Fecha_Final
 * @property string $Fecha_Ren_Can
 * @property string $Area
 * @property string $Observaciones
 * @property integer $Periodicidad
 * @property integer $Dias_Alerta
 * @property string $Contacto
 * @property string $Telefono_Contacto
 * @property string $Email_Contacto
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THEMPRESA $empresa
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 * @property THDOMINIO $periodicidad
 */
class Cont extends CActiveRecord
{

	public $orderby;
	public $view;
	public $vlr_cont;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_CONT';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Empresa, Proveedor, Concepto_Contrato, Fecha_Inicial, Fecha_Final, Fecha_Ren_Can, Area, Observaciones, Periodicidad, Dias_Alerta, Estado', 'required'),
			array('Empresa, Periodicidad, Dias_Alerta, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Proveedor, Area, Contacto, Telefono_Contacto, Email_Contacto', 'length', 'max'=>100),
			array('Concepto_Contrato', 'length', 'max'=>200),
			array('Email_Contacto','email', 'message'=>'E-mail no valido'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Contrato, Empresa, Proveedor, Concepto_Contrato, Fecha_Inicial, Fecha_Final, Fecha_Ren_Can, Area, Observaciones, Periodicidad, Dias_Alerta, Contacto, Telefono_Contacto, Email_Contacto, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion, orderby, view', 'safe', 'on'=>'search'),
		);
	}

	public function Desccontrato($Id_Contrato) {

		$modelo_cont = Cont::model()->findByPk($Id_Contrato);

		$desc_contrato = $modelo_cont->Id_Contrato.' / '.$modelo_cont->Proveedor.' - '.$modelo_cont->Concepto_Contrato;
		
		return $desc_contrato;

 	}

 	public function VlrCont($Id_Contrato) {

		$modelo_items_cont= Yii::app()->db->createCommand("SELECT Cant, Vlr_Unit, Iva, Moneda FROM T_PR_ITEM_CONT WHERE Id_Contrato = ".$Id_Contrato." AND Estado = 1 ORDER BY Moneda")->queryAll();

		$array_total_x_moneda = array();

		if(!empty($modelo_items_cont)){

			foreach ($modelo_items_cont as $reg) {
				
				$Moneda =$reg['Moneda'];
				$array_total_x_moneda[$Moneda] = 0; 	
			}
			
			foreach ($modelo_items_cont as $reg) {

				$Cant =$reg['Cant'];
				$Vlr_Unit =$reg['Vlr_Unit'];
				
				$Iva =$reg['Iva'];

				if($Iva == 0){

					$Vlr_Total = $Vlr_Unit * $Cant;

				}else{

					$vlr_base = $Vlr_Unit * $Cant;
					$vlr_iva = (($vlr_base * $Iva) / 100);
					$Vlr_Total = $vlr_base + $vlr_iva;

				}

				$Moneda =$reg['Moneda'];

				$array_total_x_moneda[$Moneda] = $array_total_x_moneda[$Moneda] + $Vlr_Total; 
				
			}

			$cadena_total = "";

			foreach ($array_total_x_moneda as $id_moneda => $valor) {
				$desc_moneda = Dominio::model()->findByPk($id_moneda)->Dominio;
				$cadena_total .= $desc_moneda.": ".number_format($valor, 0)." / ";
			}

			$resp = substr ($cadena_total, 0, -2);
		}else{
			$resp = "-";
		}

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
			'empresa' => array(self::BELONGS_TO, 'Empresa', 'Empresa'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
			'periodicidad' => array(self::BELONGS_TO, 'Dominio', 'Periodicidad'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Contrato' => 'ID',
			'Empresa' => 'Empresa',
			'Proveedor' => 'Proveedor',
			'Concepto_Contrato' => 'Concepto',
			'Fecha_Inicial' => 'Fecha de inicio',
			'Fecha_Final' => 'Fecha de fin.',
			'Fecha_Ren_Can' => 'Fecha de ren. / canc.',
			'Area' => 'Área',
			'Observaciones' => 'Observaciones',
			'Periodicidad' => 'Periodicidad de pago',
			'Dias_Alerta'=>'Días de alerta (antelación)',
			'Contacto' => 'Contacto',
			'Telefono_Contacto' => 'Teléfono contacto',
			'Email_Contacto' => 'E-mail contacto',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'orderby' => 'Orden de resultados',
			'view'=>'Ver',
			'vlr_cont'=>'Valor',
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
		$criteria->with=array('periodicidad','empresa');

		$criteria->compare('t.Id_Contrato',$this->Id_Contrato);

		if($this->Empresa != ""){
			$criteria->AddCondition("t.Empresa = '".$this->Empresa."'"); 
	    }

		$criteria->compare('t.Proveedor',$this->Proveedor,true);
		$criteria->compare('t.Concepto_Contrato',$this->Concepto_Contrato,true);

		if($this->Periodicidad != ""){
			$criteria->AddCondition("t.Periodicidad = '".$this->Periodicidad."'"); 
	    }

	    $criteria->compare('t.Area',$this->Area);

		if($this->Fecha_Inicial != ""){
      		$fci = $this->Fecha_Inicial." 00:00:00";
      		$fcf = $this->Fecha_Inicial." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Inicial', $fci, $fcf);
    	}

    	if($this->Fecha_Final != ""){
      		$fai = $this->Fecha_Final." 00:00:00";
      		$faf = $this->Fecha_Final." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Final', $fai, $faf);
    	}

    	if($this->Fecha_Ren_Can != ""){
      		$fai = $this->Fecha_Ren_Can." 00:00:00";
      		$faf = $this->Fecha_Ren_Can." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Ren_Can', $fai, $faf);
    	}

	    if(!empty($this->view)){
			switch ($this->view) {
			    case 1:
			        $criteria->AddCondition("DATEDIFF(day,'".date('Y-m-d')."',t.Fecha_Ren_Can) < t.Dias_Alerta AND t.Estado = 1");
			        break;
			    case 2:
			        $criteria->AddCondition("DATEDIFF(day,'".date('Y-m-d')."',t.Fecha_Ren_Can) >= t.Dias_Alerta AND t.Estado = 1"); 
			        break;
			    case 3:
			        $criteria->AddCondition("t.Estado = 0");
			        break;
			}			
		}

	    if(empty($this->orderby)){
			$criteria->order = 't.Proveedor ASC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_Contrato ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Contrato DESC'; 
			        break;
			    case 3:
			        $criteria->order = 'empresa.Descripcion ASC'; 
			        break;
			    case 4:
			        $criteria->order = 'empresa.Descripcion DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.Proveedor ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Proveedor DESC'; 
			        break;
			    case 7:
			        $criteria->order = 't.Concepto_Contrato ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Concepto_Contrato DESC'; 
			        break;
			    case 9:
			        $criteria->order = 'periodicidad.Dominio ASC'; 
			        break;
			    case 10:
			        $criteria->order = 'periodicidad.Dominio DESC'; 
			        break;
			    case 11:
			        $criteria->order = 't.Area ASC'; 
			        break;
			    case 12:
			        $criteria->order = 't.Area DESC'; 
			        break;
			    case 13:
			        $criteria->order = 't.Fecha_Inicial ASC'; 
			        break;
			    case 14:
			        $criteria->order = 't.Fecha_Inicial DESC'; 
			        break; 
			    case 15:
			        $criteria->order = 't.Fecha_Final DESC'; 
			        break;
			    case 16:
			        $criteria->order = 't.Fecha_Final ASC'; 
			        break;
			    case 17:
			        $criteria->order = 't.Fecha_Ren_Can DESC'; 
			        break;
			    case 18:
			        $criteria->order = 't.Fecha_Ren_Can ASC'; 
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
	 * @return Cont the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
