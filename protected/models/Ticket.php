<?php

/**
 * This is the model class for table "T_PR_TICKET".
 *
 * The followings are the available columns in table 'T_PR_TICKET':
 * @property integer $Id_Ticket
 * @property integer $Id_Tipo
 * @property integer $Prioridad
 * @property integer $Id_Grupo
 * @property integer $Id_Novedad
 * @property integer $Id_Novedad_Det
 * @property string $Solicitud
 * @property string $Fecha_Asig
 * @property integer $Id_Usuario_Asig
 * @property string $Fecha_Cierre
 * @property integer $Calificacion
 * @property string $Fecha_Calificacion
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 * @property string $Soporte
 * @property string $Notas
 *
 * @property string $Solicitud
 }*/
class Ticket extends CActiveRecord
{
	public $orderby;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_TICKET';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Tipo, Prioridad, Id_Grupo, Id_Novedad, Solicitud', 'required','on'=>'create'),
			array('Id_Usuario_Asig, Estado', 'required','on'=>'update'),
			array('Id_Tipo, Prioridad, Id_Grupo, Id_Novedad, Id_Novedad_Det, Id_Usuario_Asig, Calificacion, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Fecha_Asig, Fecha_Cierre, Fecha_Calificacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Grupo, Id_Tipo, Id_Novedad, Id_Novedad_Det, Solicitud, Fecha_Asig, Id_Usuario_Asig, Fecha_Cierre, Calificacion, Fecha_Calificacion, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion, Estado, orderby', 'safe', 'on'=>'search'),
		);
	}

	public function DescTipo($tipo){

		switch ($tipo) {
		    case 1:
		        $texto_tipo = 'INCIDENCIA';
		        break;
		    case 2:
		        $texto_tipo = 'REQUERIMIENTO';
		        break;
		}

		return $texto_tipo;

	}

	public function DescEstado($estado){

		switch ($estado) {
		    case 1:
		        $texto_estado = 'SIN ASIGNAR';
		        break;
		    case 2:
		        $texto_estado = 'ASIGNADO';
		        break;
		    case 3:
		        $texto_estado = 'EN PROCESO';
		        break;
		    case 4:
		        $texto_estado = 'CERRADO';
		        break;
		    case 5:
		        $texto_estado = 'FINALIZADO / CALIFICADO';
		        break;
		}

		return $texto_estado;

	}

	public function DescPrioridad($prioridad){

		switch ($prioridad) {
		    case 1:
		        $texto_estado = 'ALTA';
		        break;
		    case 2:
		        $texto_estado = 'MEDIA';
		        break;
		    case 3:
		        $texto_estado = 'BAJA';
		        break;
		}

		return $texto_estado;

	}

	public function DescCalif($calificacion){

		switch ($calificacion) {
		    case "":
		        $texto_calif = '<h4 class="fas fa-meh-blank text-muted" title="SIN CALIFICAR"></h4>';
		        break;
		    case 1:
		        $texto_calif = '<h4 class="fas fa-frown text-warning" title="POR MEJORAR"></h4>';
		        break;
		    case 2:
		        $texto_calif = '<h4 class="fas fa-meh text-primary" title="NEUTRO"></h4>';
		        break;
		    case 3:
		        $texto_calif = '<h4 class="fas fa-smile text-success" title="BUENO"></h4>';
		        break;
		}

		return $texto_calif;

	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idusuarioasig' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Asig'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
			'idgrupo' => array(self::BELONGS_TO, 'Dominio', 'Id_Grupo'),
			'idnovedad' => array(self::BELONGS_TO, 'NovedadTicket', 'Id_Novedad'),
			'idnovedaddet' => array(self::BELONGS_TO, 'NovedadTicket', 'Id_Novedad_Det'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Ticket' => 'ID',
			'Id_Tipo' => 'Tipo',
			'Prioridad' => 'Prioridad',
			'Id_Grupo' => 'Área / Grupo',
			'Id_Novedad' => 'Novedad',
			'Id_Novedad_Det' => 'Detalle',
			'Solicitud' => 'Descripción de Caso',
			'Fecha_Asig' => 'Fecha de asignación',
			'Id_Usuario_Asig' => 'Responsable',
			'Fecha_Cierre' => 'Fecha de cierre',
			'Calificacion' => 'Calificación',
			'Fecha_Calificacion' => 'Fecha de Calificación',
			'Id_Usuario_Creacion' => 'Usuario que solicita',
			'Fecha_Creacion' => 'Fecha de creación',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizo',
			'Fecha_Actualizacion' => 'Ultima Fecha de actualización',
			'Soporte' => 'Soporte',
			'Estado' => 'Estado',
			'Notas' => 'Notas',
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

		$user = Yii::app()->user->getState('id_user');

		$q_grupos = Yii::app()->db->createCommand("SELECT DISTINCT NT.Id_Grupo FROM T_PR_NOVEDAD_TICKET NT WHERE Id_Novedad IN (
		SELECT DISTINCT NTU.Id_Novedad FROM T_PR_NOVEDAD_TICKET_USUARIO NTU 
		INNER JOIN T_PR_NOVEDAD_TICKET NT ON NTU.Id_Novedad = NT.Id_Novedad AND NT.Estado = 1
		WHERE NTU.Estado = 1 AND NTU.Id_Usuario = ".$user.")")->queryAll();

	   	$q_novedades = Yii::app()->db->createCommand("SELECT DISTINCT NTU.Id_Novedad FROM T_PR_NOVEDAD_TICKET_USUARIO NTU 
		INNER JOIN T_PR_NOVEDAD_TICKET NT ON NTU.Id_Novedad = NT.Id_Novedad AND NT.Estado = 1
		WHERE NTU.Estado = 1 AND NTU.Id_Usuario = ".$user)->queryAll();

		if(!empty($q_grupos)){

			//el usuario cuenta con grupos y tipos

			$criteria->compare('t.Id_Ticket',$this->Id_Ticket);
			$criteria->compare('t.Id_Tipo',$this->Id_Tipo);
			$criteria->compare('t.Prioridad',$this->Prioridad);
			$criteria->compare('t.Estado',$this->Estado);
			
			if($this->Fecha_Creacion != ""){
	      		$fci = $this->Fecha_Creacion." 00:00:00";
	      		$fcf = $this->Fecha_Creacion." 23:59:59";

	      		$criteria->addBetweenCondition('t.Fecha_Creacion', $fci, $fcf);
	    	}

	    	if($this->Id_Usuario_Creacion != ""){
				$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Creacion); 
		    }

			//condicion grupos
			$cond_grupos_t = "t.Id_Grupo IN (";

			foreach ($q_grupos as $reg) {
				$cond_grupos_t .= $reg['Id_Grupo'].",";
			}

			$cond_g = substr($cond_grupos_t, 0, -1);

			$cond_g = $cond_g.")";


			if($this->Id_Grupo == ""){
				$criteria->AddCondition($cond_g);
		    }else{
		    	$criteria->compare('t.Id_Grupo',$this->Id_Grupo);
		    }

		    //condicion novedadades
			$cond_novedades_t = "t.Id_Novedad IN (";

			foreach ($q_novedades as $reg) {
				$cond_novedades_t .= $reg['Id_Novedad'].",";
			}

			$cond_n = substr($cond_novedades_t, 0, -1);

			$cond_n = $cond_n.")";


			if($this->Id_Novedad == ""){
				$criteria->AddCondition($cond_n);
		    }else{
				$novedades = implode(",", $this->Id_Novedad);
				$criteria->AddCondition("t.Id_Novedad IN (".$novedades.")"); 
		    }

		    if($this->Id_Novedad_Det != ""){
				$novedades_det = implode(",", $this->Id_Novedad_Det);
				$criteria->AddCondition("t.Id_Novedad_Det IN (".$novedades_det.")"); 
		    }

		    if($this->Id_Usuario_Asig != ""){
				$usuarios_asig = implode(",", $this->Id_Usuario_Asig);
				$criteria->AddCondition("t.Id_Usuario_Asig IN (".$usuarios_asig.")"); 
		    }

		    if($this->Estado == ""){
				$criteria->AddCondition("t.Estado IN (1,2,3)"); 
		    }else{
		    	$criteria->compare('t.Estado',$this->Estado);
		    }

		    if(empty($this->orderby)){
				$criteria->order = 't.Id_Ticket DESC'; 	
			}else{
				switch ($this->orderby) {
				    case 1:
				        $criteria->order = 't.Id_Ticket ASC'; 
				        break;
				    case 2:
				        $criteria->order = 't.Id_Ticket DESC'; 
				        break;
				    case 3:
				        $criteria->order = 't.Fecha_Creacion ASC'; 
				        break;
				    case 4:
				        $criteria->order = 't.Fecha_Creacion DESC'; 
				        break;
			        case 5:
				        $criteria->order = 't.Prioridad ASC'; 
				        break;
				    case 6:
				        $criteria->order = 't.Prioridad DESC'; 
				        break;
				}
			}


		}else{
			//no se muestran actividades
			$criteria->AddCondition("t.Id_Ticket = 0"); 
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));
	}

	public function hist()
	{
		$criteria=new CDbCriteria;

		if($this->Id_Usuario_Creacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Creacion); 
	    }

		$criteria->order = 't.Id_Ticket DESC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ticket the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
