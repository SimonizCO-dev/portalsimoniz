<?php

/**
 * This is the model class for table "T_PR_GD_DOCUMENTO".
 *
 * The followings are the available columns in table 'T_PR_GD_DOCUMENTO':
 * @property integer $Id_Documento
 * @property integer $Clasificacion
 * @property integer $Tipo
 * @property string $Num_Documento
 * @property string $Titulo
 * @property string $Descripcion
 * @property integer $Nivel_Revision
 * @property string $Fecha_Revision
 * @property string $Elaborado_Por
 * @property string $Fecha_Elaboracion
 * @property string $Emitido_Por
 * @property string $Fecha_Emision
 * @property integer $Aprobado_Por
 * @property integer $Permite_Descarga
 * @property integer $Copia_Controlada
 * @property string $Url_Consulta
 * @property string $Url_Descarga
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THTIPO $tipo
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 */
class GdDocumento extends CActiveRecord
{
    public $orderby;
    public $doc;
    public $doc_consulta;
    public $doc_descarga;
    public $ext_doc_consulta;
    public $ext_doc_descarga;
    public $areas;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_GD_DOCUMENTO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Clasificacion, Tipo, Num_Documento, Titulo, Descripcion, Nivel_Revision, Fecha_Revision, Elaborado_Por, Fecha_Elaboracion, Emitido_Por, Fecha_Emision, Aprobado_Por, Permite_Descarga, Copia_Controlada, Url_Consulta, Url_Descarga, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Fecha_Creacion, Fecha_Actualizacion, areas', 'required'),
			array('Clasificacion, Tipo, Nivel_Revision, Aprobado_Por, Permite_Descarga, Copia_Controlada, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Num_Documento', 'length', 'max'=>100),
			array('Titulo, Elaborado_Por, Emitido_Por, Url_Consulta, Url_Descarga', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Documento, Clasificacion, Tipo, Num_Documento, Titulo, Descripcion, Nivel_Revision, Fecha_Revision, Elaborado_Por, Fecha_Elaboracion, Emitido_Por, Fecha_Emision, Aprobado_Por, Permite_Descarga, Copia_Controlada, Url_Consulta, Url_Descarga, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Fecha_Creacion, Fecha_Actualizacion', 'safe', 'on'=>'search'),
		);
	}

	public function Desc_Areas($id_documento){

        $q_areas = Yii::app()->db->createCommand("SELECT A.Area FROM T_PR_GD_AREA_DOCUMENTO AD INNER JOIN T_PR_AREA A ON A.Id_Area = AD.Id_Area WHERE AD.Id_Documento = ".$id_documento." AND AD.Estado = 1 ORDER BY A.Area")->queryAll();

		  $texto_areas = '';

		  foreach ($q_areas as $a) {
		    $texto_areas .= $a['Area'].', ';
		  }

        return substr ($texto_areas, 0, -2);
    }

    public function Desc_Unidad_Gerencia($gerencia){

        $modelo_gerencia = Yii::app()->db->createCommand("SELECT Unidad_Gerencia FROM T_PR_UNIDAD_GERENCIA WHERE Id_Unidad_Gerencia = ".$gerencia)->queryRow();
        return $modelo_gerencia['Unidad_Gerencia'];
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tipo' => array(self::BELONGS_TO, 'GdTipo', 'Tipo'),
			'idusuariocre' => array(self::BELONGS_TO, 'USuario', 'Id_Usuario_Creacion'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
			'aprobadopor' => array(self::BELONGS_TO, 'UnidadGerencia', 'Aprobado_Por'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Documento' => 'ID',
			'Clasificacion' => 'Clasificación',
			'Tipo' => 'Tipo',
			'Num_Documento' => 'N° docto',
			'Titulo' => 'Nombre',
			'Descripcion' => 'Descripción',
			'Nivel_Revision' => 'Nivel de revisión',
			'Fecha_Revision' => 'Fecha de revisión',
			'Elaborado_Por' => 'Elaborado por',
			'Fecha_Elaboracion' => 'Fecha de elaboración',
			'Emitido_Por' => 'Emitido por',
			'Fecha_Emision' => 'Fecha de emisión',
			'Aprobado_Por' => 'Aprobado por',
			'Permite_Descarga' => 'Permitir descarga',
			'Copia_Controlada' => 'Copia controlada',
			'Url_Consulta' => 'Url consulta',
			'Url_Descarga' => 'Url descarga',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'orderby' => 'Orden de resultados',
			'doc_consulta' => 'Documento para consulta',
			'doc_descarga' => 'Documento para descarga',
			'areas' => 'Áreas asociadas',
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
	   	$criteria->with=array('idusuariocre','idusuarioact','tipo');

	    if($this->Clasificacion != ""){
			$criteria->AddCondition("t.Clasificacion = ".$this->Clasificacion); 
	    }

	   	if($this->Tipo != ""){
			$criteria->AddCondition("t.Tipo = ".$this->Tipo); 
	    }

		$criteria->compare('t.Num_Documento',$this->Num_Documento,true);
		$criteria->compare('t.Titulo',$this->Titulo,true);
		$criteria->compare('t.Nivel_Revision',$this->Nivel_Revision,true);

		if($this->Id_Usuario_Creacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Creacion); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
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

		$criteria->compare('t.Estado',$this->Estado);

	    if(empty($this->orderby)){
			$criteria->order = 't.Id_Documento DESC'; 	
		}else{
			switch ($this->orderby) {
				case 1:
			        $criteria->order = 't.Id_Documento ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Documento DESC'; 
			        break;
		        case 3:
			        $criteria->order = 't.Clasificacion ASC'; 
			        break;
			    case 4:
			        $criteria->order = 't.Clasificacion DESC'; 
			        break;
			    case 5:
			        $criteria->order = 'tipo.Descripcion ASC'; 
			        break;
			    case 6:
			        $criteria->order = 'tipo.Descripcion DESC'; 
			        break;
			    case 7:
			        $criteria->order = 't.Num_Documento ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Num_Documento DESC'; 
			        break;
			    case 9:
			        $criteria->order = 't.Titulo ASC'; 
			        break;
			    case 10:
			        $criteria->order = 't.Titulo DESC'; 
			        break;
			    case 11:
			        $criteria->order = 't.Nivel_Revision ASC'; 
			        break;
			    case 12:
			        $criteria->order = 't.Nivel_Revision DESC'; 
			        break;
		        case 13:
			        $criteria->order = 'idusuariocre.Usuario ASC'; 
			        break;
			    case 14:
			        $criteria->order = 'idusuariocre.Usuario DESC'; 
			        break;
			    case 15:
			        $criteria->order = 't.Fecha_Creacion ASC'; 
			        break;
			    case 16:
			        $criteria->order = 't.Fecha_Creacion DESC'; 
			        break;
			    case 17:
			        $criteria->order = 'idusuarioact.Usuario ASC'; 
			        break;
			    case 18:
			        $criteria->order = 'idusuarioact.Usuario DESC'; 
			        break;
				case 19:
			        $criteria->order = 't.Fecha_Actualizacion ASC'; 
			        break;
			    case 20:
			        $criteria->order = 't.Fecha_Actualizacion DESC'; 
			        break;
			    case 21:
			        $criteria->order = 't.Estado DESC'; 
			        break;
			    case 22:
			        $criteria->order = 't.Estado ASC'; 
			        break;
			}
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));

		
	}

	public function searchdocse()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->together  =  true;
	   	$criteria->with=array('tipo');
	   	$criteria->join = "INNER JOIN T_PR_GD_AREA_DOCUMENTO a ON t.Id_Documento = a.Id_Documento AND a.Estado = 1";

	   	$areas = implode(",", Yii::app()->user->getState('array_areas')); 
		
		if($areas != ""){
			$criteria->AddCondition("a.Id_Area IN (".$areas.")");
		}else{
			$criteria->AddCondition("t.Id_Documento = 0");
		}
		
		$criteria->AddCondition("t.Estado = 1");
		$criteria->AddCondition("t.Clasificacion = 1"); 

	   	if($this->Tipo != ""){
			$criteria->AddCondition("t.Tipo = ".$this->Tipo); 
	    }

		$criteria->compare('t.Num_Documento',$this->Num_Documento,true);
		$criteria->compare('t.Titulo',$this->Titulo,true);

	    if(empty($this->orderby)){
			$criteria->order = 'tipo.Descripcion ASC, t.Titulo ASC , t.Num_Documento ASC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 'tipo.Descripcion ASC'; 
			        break;
			    case 2:
			        $criteria->order = 'tipo.Descripcion DESC'; 
			        break;
			    case 3:
			        $criteria->order = 't.Num_Documento ASC'; 
			        break;
			    case 4:
			        $criteria->order = 't.Num_Documento DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.Titulo ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Titulo DESC'; 
			        break;   
			}
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));	
	}

	public function searchdocsi()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->together  =  true;
	   	$criteria->with=array('tipo');
	   	$criteria->join = "INNER JOIN T_PR_GD_AREA_DOCUMENTO a ON t.Id_Documento = a.Id_Documento AND a.Estado = 1";

	   	$areas = implode(",", Yii::app()->user->getState('array_areas')); 
		
		if($areas != ""){
			$criteria->AddCondition("a.Id_Area IN (".$areas.")");
		}else{
			$criteria->AddCondition("t.Id_Documento = 0");
		}

		$criteria->AddCondition("t.Estado = 1");
		$criteria->AddCondition("t.Clasificacion = 2");

	   	if($this->Tipo != ""){
			$criteria->AddCondition("t.Tipo = ".$this->Tipo); 
	    }

		$criteria->compare('t.Num_Documento',$this->Num_Documento,true);
		$criteria->compare('t.Titulo',$this->Titulo,true);

	    if(empty($this->orderby)){
			$criteria->order = 'tipo.Descripcion ASC, t.Titulo ASC , t.Num_Documento ASC'; 	  	
		}else{
			switch ($this->orderby) {
				case 1:
			        $criteria->order = 'tipo.Descripcion ASC'; 
			        break;
			    case 2:
			        $criteria->order = 'tipo.Descripcion DESC'; 
			        break;
			    case 3:
			        $criteria->order = 't.Num_Documento ASC'; 
			        break;
			    case 4:
			        $criteria->order = 't.Num_Documento DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.Titulo ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Titulo DESC'; 
			        break;
			}
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));	
	}

	public function searchdocst()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->together  =  true;
	   	$criteria->with=array('tipo');

		$criteria->AddCondition("t.Estado = 1");
		$criteria->AddCondition("t.Clasificacion = 3");

	   	if($this->Tipo != ""){
			$criteria->AddCondition("t.Tipo = ".$this->Tipo); 
	    }

		$criteria->compare('t.Num_Documento',$this->Num_Documento,true);
		$criteria->compare('t.Titulo',$this->Titulo,true);

	    if(empty($this->orderby)){
			$criteria->order = 'tipo.Descripcion ASC, t.Titulo ASC , t.Num_Documento ASC';  	
		}else{
			switch ($this->orderby) {
				case 1:
			        $criteria->order = 'tipo.Descripcion ASC'; 
			        break;
			    case 2:
			        $criteria->order = 'tipo.Descripcion DESC'; 
			        break;
			    case 3:
			        $criteria->order = 't.Num_Documento ASC'; 
			        break;
			    case 4:
			        $criteria->order = 't.Num_Documento DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.Titulo ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Titulo DESC'; 
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
	 * @return Documento the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
