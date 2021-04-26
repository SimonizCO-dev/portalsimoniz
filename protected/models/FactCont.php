<?php

/**
 * This is the model class for table "TH_FACT_CONT".
 *
 * The followings are the available columns in table 'TH_FACT_CONT':
 * @property integer $Id_Fact
 * @property string $Num_Factura
 * @property string $Fecha_Radicado
 * @property integer $Proveedor
 * @property string $Fecha_Factura
 * @property string $Valor
 * @property integer $Moneda
 * @property integer $Empresa
 * @property integer $Area
 * @property string $Doc_Soporte
 * @property string $Observaciones
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property integer $Id_Usuario_Revision
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 * @property string $Fecha_Revision
 *
 * The followings are the available model relations:
 * @property THUSUARIOS $idUsuarioCreacion
 * @property THUSUARIOS $idUsuarioActualizacion
 * @property THUSUARIOS $idUsuarioRevision
 * @property THPROVEEDORCONT $proveedor
 */
class FactCont extends CActiveRecord
{
	public $usuario_creacion;
	public $usuario_actualizacion;
	public $usuario_revision;
	public $orderby;
	public $periodo_radicado;
	public $sop;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_FACT_CONT';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Num_Factura, Fecha_Radicado, Proveedor, Fecha_Factura, Valor, Moneda, Empresa, Area', 'required'),
			array('Num_Factura, Proveedor', 'ECompositeUniqueValidator', 'attributesToAddError'=>'Num_Factura','message'=>'# Factura - prov. ya existe en el sistema.'),
			array('Proveedor, Moneda, Empresa, Area, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Num_Factura', 'length', 'max'=>20),
			array('Valor', 'length', 'max'=>18),
			array('Doc_Soporte', 'length', 'max'=>200),
			array('Fecha_Creacion, Fecha_Actualizacion, Observaciones', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Fact, Num_Factura, Fecha_Radicado, Proveedor, Fecha_Factura, Valor, Moneda, Empresa, Area, Observaciones, Estado, usuario_creacion, usuario_actualizacion, usuario_revision, Fecha_Creacion, Fecha_Actualizacion, Fecha_Revision, orderby, periodo_factura', 'safe', 'on'=>'search'),
		);
	}

	public function DescEmpresa($Empresa){

       switch ($Empresa) {
		    case 1:
		        return "COMSTAR";
		    case 2:
		        return "PANSELL";
		    case 3:
		        return "SIMONIZ";
		    case 4:
		        return "TITAN";
		}

    }

    public function DescProveedor($Proveedor){

        $desc= Yii::app()->db->createCommand("
		    SELECT CONCAT (Nit, ' - ', Razon_Social) AS DESCR FROM T_PR_PROVEEDOR_CONT WHERE Id = ".$Proveedor)->queryRow();

		return $desc['DESCR'];

    }

    public function DescMoneda($Moneda){

       switch ($Moneda) {
		    case 1:
		        return "COP";
		    case 2:
		        return "USD";
		}

    }

    public function DescEstado($Estado){

       switch ($Estado) {
		    case 0:
		        return "ANULADA";
		    case 1:
		        return "CARGADA";
		    case 2:
		        return "RECIBIDA";
		    case 3:
		        return "RECHAZADA";
		}

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
			'idusuariorev' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Revision'),
			'proveedor' => array(self::BELONGS_TO, 'ProveedorCont', 'Proveedor'),
			'area' => array(self::BELONGS_TO, 'Area', 'Area'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Fact' => 'ID',
			'Num_Factura' => '# Factura',
			'Fecha_Radicado' => 'Fecha de radicado',
			'Proveedor' => 'Proveedor',
			'Fecha_Factura' => 'Fecha de factura',
			'Valor' => 'Valor',
			'Moneda' => 'Moneda',
			'Empresa' => 'Empresa',
			'Area' => 'Área',
			'Observaciones' => 'Observaciones',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Id_Usuario_Revision' => 'Usuario que revisó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Fecha de actualización',
			'Fecha_Revision' => 'Fecha de Revisión',
			'usuario_creacion' => 'Usuario que creo',
			'usuario_actualizacion' => 'Usuario que actualizó',
			'usuario_revision' => 'Usuario que revisó',
			'orderby' => 'Orden de resultados',
			'periodo_radicado' => 'Periodo de radicado',
			'sop' => 'Soporte',
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

	   	$criteria->with=array('proveedor','idusuariocre','idusuarioact','idusuariorev','area');
	   	//*$criteria->join = "INNER JOIN T_PR_AREA a ON t.Area = a.Id_Area";

		$criteria->compare('t.Id_Fact',$this->Id_Fact);
		$criteria->compare('t.Num_Factura',$this->Num_Factura,true);
		$criteria->compare('t.Proveedor',$this->Proveedor);
		$criteria->compare('t.Valor',$this->Valor,true);
		$criteria->compare('t.Moneda',$this->Moneda);
		$criteria->compare('t.Empresa',$this->Empresa);
		$criteria->compare('t.Area',$this->Area);
		$criteria->compare('t.Observaciones',$this->Observaciones,true);
		$criteria->compare('t.Estado',$this->Estado);

		if($this->Fecha_Radicado != ""){
      		$fci = $this->Fecha_Radicado." 00:00:00";
      		$fcf = $this->Fecha_Radicado." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Radicado', $fci, $fcf);
    	}

    	if($this->periodo_radicado != ""){
      		$ffi = $this->periodo_radicado."-01";
      	
			$smff = strtotime ( '+1 month' , strtotime($ffi)) ;
			$nff = strtotime ( '-1 day' , $smff);
			
			$fff = date ( 'Y-m-d' , $nff );

      		$criteria->addBetweenCondition('t.Fecha_Radicado', $ffi, $fff);
    	}

    	if($this->Fecha_Factura != ""){
      		$fci = $this->Fecha_Factura." 00:00:00";
      		$fcf = $this->Fecha_Factura." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Factura', $fci, $fcf);
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

    	if($this->Fecha_Revision != ""){
      		$fri = $this->Fecha_Revision." 00:00:00";
      		$frf = $this->Fecha_Revision." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Revision', $fri, $frf);
    	}

		if($this->Id_Usuario_Creacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Creacion); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

	    if($this->Id_Usuario_Revision != ""){
			$criteria->AddCondition("t.Id_Usuario_Revision = ".$this->Id_Usuario_Revision); 
	    }

	    if(empty($this->orderby)){
			$criteria->order = 't.Id_Fact DESC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_Fact ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Fact DESC'; 
			        break;
			    case 3:
			        $criteria->order = 't.Empresa ASC'; 
			        break;
			    case 4:
			        $criteria->order = 't.Empresa DESC'; 
			        break;
			    case 5:
			        $criteria->order = 'area.Area ASC'; 
			        break;
			    case 6:
			        $criteria->order = 'area.Area DESC'; 
			        break;
			    case 7:
			        $criteria->order = 't.Num_Factura ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Num_Factura DESC'; 
			        break;
		        case 9:
			        $criteria->order = 't.Fecha_Factura ASC'; 
			        break;
			    case 10:
			        $criteria->order = 't.Fecha_Factura DESC'; 
			        break;
			    case 11:
			        $criteria->order = 't.Fecha_Radicado ASC'; 
			        break;
			    case 12:
			        $criteria->order = 't.Fecha_Radicado DESC'; 
			        break;
			    case 13:
			        $criteria->order = 'proveedor.Razon_Social ASC'; 
			        break;
			    case 14:
			        $criteria->order = 'proveedor.Razon_Social DESC'; 
			        break;
		        case 15:
			        $criteria->order = 'idusuariocre.Usuario ASC'; 
			        break;
			    case 16:
			        $criteria->order = 'idusuariocre.Usuario DESC'; 
			        break;
			    case 17:
			        $criteria->order = 't.Fecha_Creacion ASC'; 
			        break;
			    case 18:
			        $criteria->order = 't.Fecha_Creacion DESC'; 
			        break;
			    case 19:
			        $criteria->order = 'idusuarioact.Usuario ASC'; 
			        break;
			    case 20:
			        $criteria->order = 'idusuarioact.Usuario DESC'; 
			        break;
				case 21:
			        $criteria->order = 't.Fecha_Actualizacion ASC'; 
			        break;
			    case 22:
			        $criteria->order = 't.Fecha_Actualizacion DESC'; 
			        break;
			    case 23:
			        $criteria->order = 'idusuariorev.Usuario ASC'; 
			        break;
			    case 24:
			        $criteria->order = 'idusuariorev.Usuario DESC'; 
			        break;
				case 25:
			        $criteria->order = 't.Fecha_Revision ASC'; 
			        break;
			    case 26:
			        $criteria->order = 't.Fecha_Revision DESC'; 
			        break;
			    case 27:
			        $criteria->order = 't.Estado DESC'; 
			        break;
			    case 28:
			        $criteria->order = 't.Estado ASC'; 
			        break;
			}
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),		
		));
	}

	public function search2()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$array_areas_usuario = Yii::app()->user->getState('array_areas');

		$criteria=new CDbCriteria;

		$criteria->together  =  true;

	   	$criteria->with=array('proveedor','idusuariocre','idusuarioact','idusuariorev','area');
	   	//$criteria->join = "INNER JOIN T_PR_AREA a ON t.Area = a.Id_Area";

	   	if(!empty($array_areas_usuario)){
			$areas_usuario = implode(",", $array_areas_usuario);
			$criteria->AddCondition("t.Area IN (".$areas_usuario.")"); 
	    }else{
	    	$criteria->AddCondition("t.Id_Fact = 0");
	    }

		$criteria->compare('t.Id_Fact',$this->Id_Fact);
		$criteria->compare('t.Num_Factura',$this->Num_Factura,true);
		$criteria->compare('t.Proveedor',$this->Proveedor);
		$criteria->compare('t.Valor',$this->Valor,true);
		$criteria->compare('t.Moneda',$this->Moneda);
		$criteria->compare('t.Empresa',$this->Empresa);
		$criteria->compare('t.Area',$this->Area);
		$criteria->compare('t.Observaciones',$this->Observaciones,true);
		$criteria->compare('t.Estado',$this->Estado);

		if($this->Fecha_Radicado != ""){
      		$fci = $this->Fecha_Radicado." 00:00:00";
      		$fcf = $this->Fecha_Radicado." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Radicado', $fci, $fcf);
    	}

    	if($this->periodo_radicado != ""){
      		$ffi = $this->periodo_radicado."-01";
      	
			$smff = strtotime ( '+1 month' , strtotime($ffi)) ;
			$nff = strtotime ( '-1 day' , $smff);
			
			$fff = date ( 'Y-m-d' , $nff );

      		$criteria->addBetweenCondition('t.Fecha_Radicado', $ffi, $fff);
    	}

    	if($this->Fecha_Factura != ""){
      		$fci = $this->Fecha_Factura." 00:00:00";
      		$fcf = $this->Fecha_Factura." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Factura', $fci, $fcf);
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

    	if($this->Fecha_Revision != ""){
      		$fri = $this->Fecha_Revision." 00:00:00";
      		$frf = $this->Fecha_Revision." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Revision', $fri, $frf);
    	}

		if($this->Id_Usuario_Creacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Creacion); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

	    if($this->Id_Usuario_Revision != ""){
			$criteria->AddCondition("t.Id_Usuario_Revision = ".$this->Id_Usuario_Revision); 
	    }

	    if(empty($this->orderby)){
			$criteria->order = 't.Id_Fact DESC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_Fact ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Fact DESC'; 
			        break;
			    case 3:
			        $criteria->order = 't.Empresa ASC'; 
			        break;
			    case 4:
			        $criteria->order = 't.Empresa DESC'; 
			        break;
			    case 5:
			        $criteria->order = 'area.Area ASC'; 
			        break;
			    case 6:
			        $criteria->order = 'area.Area DESC'; 
			        break;
			    case 7:
			        $criteria->order = 't.Num_Factura ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Num_Factura DESC'; 
			        break;
		        case 9:
			        $criteria->order = 't.Fecha_Factura ASC'; 
			        break;
			    case 10:
			        $criteria->order = 't.Fecha_Factura DESC'; 
			        break;
			    case 11:
			        $criteria->order = 't.Fecha_Radicado ASC'; 
			        break;
			    case 12:
			        $criteria->order = 't.Fecha_Radicado DESC'; 
			        break;
			    case 13:
			        $criteria->order = 'proveedor.Razon_Social ASC'; 
			        break;
			    case 14:
			        $criteria->order = 'proveedor.Razon_Social DESC'; 
			        break;
		        case 15:
			        $criteria->order = 'idusuariocre.Usuario ASC'; 
			        break;
			    case 16:
			        $criteria->order = 'idusuariocre.Usuario DESC'; 
			        break;
			    case 17:
			        $criteria->order = 't.Fecha_Creacion ASC'; 
			        break;
			    case 18:
			        $criteria->order = 't.Fecha_Creacion DESC'; 
			        break;
			    case 19:
			        $criteria->order = 'idusuarioact.Usuario ASC'; 
			        break;
			    case 20:
			        $criteria->order = 'idusuarioact.Usuario DESC'; 
			        break;
				case 21:
			        $criteria->order = 't.Fecha_Actualizacion ASC'; 
			        break;
			    case 22:
			        $criteria->order = 't.Fecha_Actualizacion DESC'; 
			        break;
			    case 23:
			        $criteria->order = 'idusuariorev.Usuario ASC'; 
			        break;
			    case 24:
			        $criteria->order = 'idusuariorev.Usuario DESC'; 
			        break;
				case 25:
			        $criteria->order = 't.Fecha_Revision ASC'; 
			        break;
			    case 26:
			        $criteria->order = 't.Fecha_Revision DESC'; 
			        break;
			    case 27:
			        $criteria->order = 't.Estado DESC'; 
			        break;
			    case 28:
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
	 * @return FactPend the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function search3()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->together  =  true;

	   	$criteria->with=array('proveedor','idusuariocre','idusuarioact','idusuariorev','area');
	   	//$criteria->join = "INNER JOIN T_PR_AREA a ON t.Area = a.Id_Area";

	   	$criteria->AddCondition("t.Estado != 1");

		$criteria->compare('t.Id_Fact',$this->Id_Fact);
		$criteria->compare('t.Num_Factura',$this->Num_Factura,true);
		$criteria->compare('t.Proveedor',$this->Proveedor);
		$criteria->compare('t.Valor',$this->Valor,true);
		$criteria->compare('t.Moneda',$this->Moneda);
		$criteria->compare('t.Empresa',$this->Empresa);
		$criteria->compare('t.Area',$this->Area);
		$criteria->compare('t.Observaciones',$this->Observaciones,true);
		$criteria->compare('t.Estado',$this->Estado);

		if($this->Fecha_Radicado != ""){
      		$fci = $this->Fecha_Radicado." 00:00:00";
      		$fcf = $this->Fecha_Radicado." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Radicado', $fci, $fcf);
    	}

    	if($this->periodo_radicado != ""){
      		$ffi = $this->periodo_radicado."-01";
      	
			$smff = strtotime ( '+1 month' , strtotime($ffi)) ;
			$nff = strtotime ( '-1 day' , $smff);
			
			$fff = date ( 'Y-m-d' , $nff );

      		$criteria->addBetweenCondition('t.Fecha_Radicado', $ffi, $fff);
    	}

    	if($this->Fecha_Factura != ""){
      		$fci = $this->Fecha_Factura." 00:00:00";
      		$fcf = $this->Fecha_Factura." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Factura', $fci, $fcf);
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

    	if($this->Fecha_Revision != ""){
      		$fri = $this->Fecha_Revision." 00:00:00";
      		$frf = $this->Fecha_Revision." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Revision', $fri, $frf);
    	}

		if($this->Id_Usuario_Creacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Creacion); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

	    if($this->Id_Usuario_Revision != ""){
			$criteria->AddCondition("t.Id_Usuario_Revision = ".$this->Id_Usuario_Revision); 
	    }

	    if(empty($this->orderby)){
			$criteria->order = 't.Id_Fact DESC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_Fact ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Fact DESC'; 
			        break;
			    case 3:
			        $criteria->order = 't.Empresa ASC'; 
			        break;
			    case 4:
			        $criteria->order = 't.Empresa DESC'; 
			        break;
			    case 5:
			        $criteria->order = 'area.Area ASC'; 
			        break;
			    case 6:
			        $criteria->order = 'area.Area DESC'; 
			        break;
			    case 7:
			        $criteria->order = 't.Num_Factura ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Num_Factura DESC'; 
			        break;
		        case 9:
			        $criteria->order = 't.Fecha_Factura ASC'; 
			        break;
			    case 10:
			        $criteria->order = 't.Fecha_Factura DESC'; 
			        break;
			    case 11:
			        $criteria->order = 't.Fecha_Radicado ASC'; 
			        break;
			    case 12:
			        $criteria->order = 't.Fecha_Radicado DESC'; 
			        break;
			    case 13:
			        $criteria->order = 'proveedor.Razon_Social ASC'; 
			        break;
			    case 14:
			        $criteria->order = 'proveedor.Razon_Social DESC'; 
			        break;
		        case 15:
			        $criteria->order = 'idusuariocre.Usuario ASC'; 
			        break;
			    case 16:
			        $criteria->order = 'idusuariocre.Usuario DESC'; 
			        break;
			    case 17:
			        $criteria->order = 't.Fecha_Creacion ASC'; 
			        break;
			    case 18:
			        $criteria->order = 't.Fecha_Creacion DESC'; 
			        break;
			    case 19:
			        $criteria->order = 'idusuarioact.Usuario ASC'; 
			        break;
			    case 20:
			        $criteria->order = 'idusuarioact.Usuario DESC'; 
			        break;
				case 21:
			        $criteria->order = 't.Fecha_Actualizacion ASC'; 
			        break;
			    case 22:
			        $criteria->order = 't.Fecha_Actualizacion DESC'; 
			        break;
			    case 23:
			        $criteria->order = 'idusuariorev.Usuario ASC'; 
			        break;
			    case 24:
			        $criteria->order = 'idusuariorev.Usuario DESC'; 
			        break;
				case 25:
			        $criteria->order = 't.Fecha_Revision ASC'; 
			        break;
			    case 26:
			        $criteria->order = 't.Fecha_Revision DESC'; 
			        break;
			    case 27:
			        $criteria->order = 't.Estado DESC'; 
			        break;
			    case 28:
			        $criteria->order = 't.Estado ASC'; 
			        break;
			}
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),		
		));
	}
}
