<?php

/**
 * This is the model class for table "TH_I_DOCTO".
 *
 * The followings are the available columns in table 'TH_I_DOCTO':
 * @property integer $Id
 * @property integer $Id_Tipo_Docto
 * @property integer $Id_Tercero
 * @property string $Fecha
 * @property integer $Consecutivo
 * @property string $Referencia
 * @property string $Vlr_Total
 * @property integer $Id_Estado
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 * @property integer $Id_Emp 
 * @property string $Notas 
 *
 * The followings are the available model relations:
 * @property THIDOCTOMOVTO[] $tHIDOCTOMOVTOs
 * @property THIESTADODOCTO $idEstado
 * @property THITERCERO $idTercero
 * @property THITIPODOCTO $idTipoDocto
 * @property THUSUARIOS $idUsuarioCreacion
 * @property THUSUARIOS $idUsuarioActualizacion
 */
class IDocto extends CActiveRecord
{
	public $orderby;
	public $det_item;
	public $det_bodega_origen;
	public $det_bodega_destino;
	public $det_bodega_tr_origen;
	public $det_bodega_tr_destino;
	public $det_cant;
	public $det_vlr;
	public $cad_item;
	public $cad_bodega_origen;
	public $cad_bodega_destino;
	public $cad_cant;
	public $cad_vlr;
	public $fecha_inicial;
	public $fecha_final;
	public $tipo;
	public $item_inicial;
	public $item_final;
	public $linea;
	public $lin;
	public $opcion_exp;
	public $bodega;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_I_DOCTO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Tipo_Docto, Id_Tercero, Fecha, Consecutivo, Referencia, Vlr_Total, Id_Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'required'),
			array('fecha_inicial, fecha_final', 'required', 'on'=>'kardex'),
			array('opcion_exp', 'required', 'on'=>'movimientos'),
			array('bodega, opcion_exp', 'required', 'on'=>'costo_inv_bod'),
			array('opcion_exp', 'required', 'on'=>'costo_inv_tot'),
			array('lin, opcion_exp', 'required', 'on'=>'ent, sal'),
			array('Id_Tipo_Docto, Id_Tercero, Consecutivo, Id_Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_Emp', 'numerical', 'integerOnly'=>true),
			array('Referencia', 'length', 'max'=>100),
			array('Vlr_Total', 'length', 'max'=>19),
			array('Fecha_Creacion, Fecha_Actualizacion, Notas', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Id_Tipo_Docto, Id_Tercero, Fecha, Consecutivo, Referencia, Vlr_Total, Id_Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Fecha_Creacion, Fecha_Actualizacion, orderby, Id_Emp, Notas', 'safe', 'on'=>'search'),
		);
	}

	public function DescTercero($tercero) {
 
		$q_tercero = Yii::app()->db->createCommand("SELECT CONCAT (Nit, ' - ', Nombre) AS Descr FROM T_PR_I_TERCERO WHERE Id = ".$tercero)->queryRow();
        return $q_tercero['Descr'];

 	}

 	public function DescEmpleado($emp) {
 
		$q_empleado = Yii::app()->db->createCommand("SELECT
			E.Id_Empleado, 
			CONCAT (E.Apellido, ' ', E.Nombre, ' (', TI.Dominio, ' ', E.Identificacion, ')') AS Empleado
			FROM T_PR_EMPLEADO E
			LEFT JOIN T_PR_DOMINIO TI ON E.Id_Tipo_Ident = TI.Id_Dominio
			WHERE Id_Empleado = '".$emp."'")->queryRow();
        return $q_empleado['Empleado'];

 	}

 	public function searchByEmpleado($filtro) {
       
        $resp = Yii::app()->db->createCommand("
        	SELECT
        	TOP 10 
			E.Id_Empleado, 
			CONCAT (E.Apellido, ' ', E.Nombre) AS Nombres_Empleado,
			CONCAT (E.Apellido, ' ', E.Nombre, ' (', TI.Dominio, ' ', E.Identificacion, ')') AS Empleado
			FROM T_PR_EMPLEADO E
			LEFT JOIN T_PR_DOMINIO TI ON E.Id_Tipo_Ident = TI.Id_Dominio
			WHERE (E.Identificacion LIKE '%".$filtro."%' OR E.Nombre LIKE '%".$filtro."%' OR E.Apellido LIKE '%".$filtro."%') 
			ORDER BY Nombres_Empleado
		")->queryAll();
        return $resp;
        
 	}

 	public function searchById($filtro) {
 
        $resp = Yii::app()->db->createCommand("
        	SELECT
			E.Id_Empleado, 
			CONCAT (E.Apellido, ' ', E.Nombre) AS Nombres_Empleado,
			CONCAT (E.Apellido, ' ', E.Nombre, ' (', TI.Dominio, ' ', E.Identificacion, ')') AS Empleado
			FROM T_PR_EMPLEADO E
			LEFT JOIN T_PR_DOMINIO TI ON E.Id_Tipo_Ident = TI.Id_Dominio
			WHERE Id_Empleado = '".$filtro."'")->queryAll();
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
			'idestado' => array(self::BELONGS_TO, 'IEstadoDocto', 'Id_Estado'),
			'idtercero' => array(self::BELONGS_TO, 'ITercero', 'Id_Tercero'),
			'idtipodocto' => array(self::BELONGS_TO, 'ITipoDocto', 'Id_Tipo_Docto'),
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
			'Id_Tipo_Docto' => 'Tipo',
			'Id_Tercero' => 'Tercero',
			'Fecha' => 'Fecha',
			'Consecutivo' => 'Consecutivo',
			'Referencia' => 'Referencia',
			'Vlr_Total' => 'Vlr. total',
			'Id_Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'orderby' => 'Orden de resultados',
			'det_item' => 'Item',
			'det_bodega_origen' => 'Bodega origen',
			'det_bodega_destino' => 'Bodega destino',
			'det_bodega_tr_origen' => 'Bodega origen',
			'det_bodega_tr_destino' => 'Bodega destino',
			'det_cant' => 'Cantidad',
			'det_vlr' => 'Vlr. unitario',
			'fecha_inicial' => 'Fecha inicial',
			'fecha_final' => 'Fecha final',
			'tipo' => 'Tipo',
			'item_inicial' => 'Item inicial',
			'item_final' => 'Item final',
			'linea' => 'Línea(s)',
			'Id_Emp'=> 'Empleado',
			'lin' => 'Línea',
			'opcion_exp'=>'Ver en',
			'Notas'=>'Notas',
			'bodega' => 'Bodega',
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
	   	$criteria->with=array('idestado','idtercero','idtipodocto');

		$criteria->compare('t.Id_Tipo_Docto',$this->Id_Tipo_Docto);
		$criteria->compare('t.Id_Tercero',$this->Id_Tercero);
		$criteria->compare('t.Fecha',$this->Fecha,true);
		$criteria->compare('t.Consecutivo',$this->Consecutivo);
		$criteria->compare('t.Referencia',$this->Referencia,true);
		$criteria->compare('t.Id_Estado',$this->Id_Estado);
		$criteria->compare('t.Id_Emp',$this->Id_Emp);

		$td_hab = Yii::app()->user->getState('array_td');

		if(!empty($td_hab)){
			$tipos_activos = implode(",",$td_hab);
			$criteria->AddCondition("t.Id_Tipo_Docto IN (".$tipos_activos.")"); 	
		}

		if(empty($this->orderby)){
			$criteria->order = 't.Id DESC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 'idtipodocto.Descripcion ASC'; 
			        break;
			    case 2:
			        $criteria->order = 'idtipodocto.Descripcion DESC'; 
			        break;
			    case 3:
			        $criteria->order = 't.Consecutivo ASC'; 
			        break;
			    case 4:
			        $criteria->order = 't.Consecutivo DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.Fecha ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Fecha DESC'; 
			        break;
			    case 7:
			        $criteria->order = 't.Referencia ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Referencia DESC'; 
			        break;
		        case 9:
			        $criteria->order = 'idtercero.Nit ASC'; 
			        break;
			    case 10:
			        $criteria->order = 'idtercero.Nit DESC'; 
			        break;
			    case 11:
			        $criteria->order = 'idestado.Descripcion ASC'; 
			        break;
			    case 12:
			        $criteria->order = 'idestado.Descripcion DESC'; 
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
	 * @return IDocto the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
