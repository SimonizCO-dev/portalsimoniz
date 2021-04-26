<?php

/**
 * This is the model class for table "TH_SOL_PROM".
 *
 * The followings are the available columns in table 'TH_SOL_PROM':
 * @property integer $Id_Sol_Prom
 * @property string $Num_Sol
 * @property string $Tipo
 * @property string $Cliente
 * @property integer $Kit
 * @property integer $Cant
 * @property string $Fecha
 * @property string $Fecha_T_Entrega
 * @property string $Fecha_Entrega
 * @property string $Responsable
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 * @property integer $Estado
 * @property integer $Val_Compra
 * @property integer $Val_Prod
 * @property integer $Val_MT
 * @property integer $Comp_Disp
 * @property integer $Estado_Rechazo
 * @property string $Observaciones
 * @property string $Observaciones_Ger
 * @property string $Observaciones_Pla
 * @property string $Observaciones_Log
 *
 * The followings are the available model relations:
 * @property THUSUARIOS $idUsuarioCreacion
 * @property THUSUARIOS $idUsuarioActualizacion
 */
class SolProm extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_SOL_PROM';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('Fecha_Entrega', 'required','on'=>'rev_log'),
			array('Responsable, Tipo, Observaciones, Kit, Cant', 'required','on'=>'registro'),
			array('Fecha_Entrega', 'required','on'=>'reg_logistica'),
			array('Id_Usuario_Creacion, Id_Usuario_Actualizacion, Estado, Val_Compra, Val_Prod, Val_MT', 'numerical', 'integerOnly'=>true),
			array('Num_Sol, Cliente', 'length', 'max'=>50),
			array('Tipo', 'length', 'max'=>10),
			array('Responsable', 'length', 'max'=>200),
			array('Observaciones_Log', 'required','on'=>'rev_plan'),
			array('Observaciones, Observaciones_Ger, Observaciones_Pla, Val_Compra, Val_Prod, Val_MT, Comp_Disp', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Sol_Prom, Num_Sol, Tipo, Cliente, Fecha, Fecha_T_Entrega, Fecha_Entrega, Responsable, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion, Estado, Observaciones', 'safe', 'on'=>'search'),
		);
	}

	public function ValidProceso($proceso, $tipo, $usuario) {

		
		if($tipo == null){
			$q = Yii::app()->db->createCommand("SELECT Id_Users FROM TH_SOL_PROM_USUARIO WHERE Proceso = ".$proceso)->queryAll();
		}else{
			$q = Yii::app()->db->createCommand("SELECT Id_Users FROM TH_SOL_PROM_USUARIO WHERE Proceso = ".$proceso." AND Tipo = ".$tipo)->queryAll();
		}

		if(!empty($q)){

			$array_users = array();

			foreach ($q as $reg) {
				$user_x_proc = $reg['Id_Users'];
				$users = explode(",", $user_x_proc);
				foreach ($users as $key => $value) {
					if (!in_array($value, $array_users)) {
					    array_push($array_users, $value);
					}
				}		
			}

			if (in_array($usuario, $array_users)) {
			    return 1;
			}else{
				return 0;
			}	

		}else{
			return 0;
		}
        
 	}

 	public function TiposXUser($proceso, $usuario) {

		$q_t = Yii::app()->db->createCommand("SELECT Tipo, Id_Users FROM TH_SOL_PROM_USUARIO WHERE Proceso = ".$proceso)->queryAll();

		$array_tipos = array();
		
		if(!empty($q_t)){

			foreach ($q_t as $reg) {

				$user_x_proc = $reg['Id_Users'];
				$users = explode(",", $user_x_proc);

				if (in_array($usuario, $users)) {
			    	$tipo  = new SolProm;
			    	$array_tipos[$reg['Tipo']] = $tipo->DescTipo($reg['Tipo']);
				}

			}

		}

		return $array_tipos;
        
 	}

 	public function DescEstado($Estado) {

		switch ($Estado) {
		    case 0:
		        return "RECHAZADO";
		    case 1:
		        return "REV. GERENCIA";
		    case 2:
		        return "REV. PLANEACIÓN";
		    case 3:
		        return "REV. LOGÍSTICA";
		    case 4:
		        return "EN ENSAMBLE";
		}

 	}

 	public function DescCheck($Check) {

		switch ($Check) {
		    case null:
		        return "-";
		    case 0:
		        return "NO";
		    case 1:
		        return "SI";
		}

 	}

	public function DescTipo($Tipo) {

		$resp = Yii::app()->db->createCommand("
			SELECT Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 300 AND Id_Criterio  = '".$Tipo."'
		")->queryRow();

	    return $resp['Criterio_Descripcion'];

 	}

 	public function DescCliente($Cliente){

		$resp = Yii::app()->db->createCommand("
			SELECT TOP 10 C_NIT_CLIENTE, C_NOMBRE_CLIENTE FROM T_CF_CLIENTES WHERE C_CIA = 2 AND C_NIT_CLIENTE = '".$Cliente."' 
		")->queryRow();


	    return $resp['C_NIT_CLIENTE']." - ".$resp['C_NOMBRE_CLIENTE'];

 	}

 	public function DescKit($Kit){

        $desc= Yii::app()->db->createCommand("
		    SELECT CONCAT (RTRIM(LTRIM(I_ID_ITEM)), ' - ', RTRIM(LTRIM(I_REFERENCIA)), ' - ', RTRIM(LTRIM(I_DESCRIPCION))) AS DESCR FROM T_CF_ITEMS WHERE I_ID_ITEM = ".$Kit." AND I_CIA = 2")->queryRow();

		return $desc['DESCR'];

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
			'Id_Sol_Prom' => 'ID',
			'Num_Sol' => 'Consecutivo',
			'Tipo' => 'Tipo',
			'Cliente' => 'Cliente',
			'Kit' => 'Kit',
			'Cant' => 'Cantidad',
			'Fecha' => 'Fecha',
			'Fecha_T_Entrega' => 'Fecha tentativa de entrega',
			'Fecha_Entrega' => 'Fecha de entrega',
			'Responsable' => 'Responsable',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Fecha_Creacion' => 'Fecha de creación',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizo',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'Estado' => 'Estado',
			'Val_Compra' => 'Validación compra',
			'Val_Prod' => 'Validación producción',
			'Val_MT' => 'Validación materia prima',
			'Comp_Disp' => 'Componentes disponibles',
			'Observaciones'=>'Observaciones',
			'Observaciones_Ger'=>'Observaciones',
			'Observaciones_Pla'=>'Observaciones',
			'Observaciones_Log'=>'Observaciones',
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



		$criteria->compare('t.Id_Sol_Prom',$this->Id_Sol_Prom);
		$criteria->compare('t.Num_Sol',$this->Num_Sol,true);
		$criteria->compare('t.Tipo',$this->Tipo);
		$criteria->compare('t.Cliente',$this->Cliente,true);
		$criteria->compare('t.Responsable',$this->Responsable,true);
		$criteria->compare('t.Estado',$this->Estado);


	    if($this->Id_Usuario_Creacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Creacion); 
	    }

		if($this->Fecha_Creacion != ""){
      		$fci = $this->Fecha_Creacion." 00:00:00";
      		$fcf = $this->Fecha_Creacion." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Creacion', $fci, $fcf);
    	}


	    if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

		if($this->Fecha_Actualizacion != ""){
      		$fci = $this->Fecha_Actualizacion." 00:00:00";
      		$fcf = $this->Fecha_Actualizacion." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Actualizacion', $fci, $fcf);
    	}

		$criteria->order = 't.Num_Sol DESC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SolProm the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
