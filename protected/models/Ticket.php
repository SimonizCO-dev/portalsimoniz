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
 * @property integer $Estado
 */
class Ticket extends CActiveRecord
{
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
			array('Id_Tipo, Prioridad, Id_Grupo, Id_Novedad, Id_Novedad_Det, Id_Usuario_Asig, Calificacion, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Fecha_Asig, Fecha_Cierre, Fecha_Calificacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Grupo, Id_Tipo, Solicitud, Fecha_Asig, Id_Usuario_Asig, Fecha_Cierre, Calificacion, Fecha_Calificacion, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion, Estado', 'safe', 'on'=>'search, searchasig'),
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
		        $texto_estado = 'ANULADO /RECHAZADO';
		        break;
		    case 4:
		        $texto_estado = 'EN PROCESO';
		        break;
		    case 5:
		        $texto_estado = 'CERRADO';
		        break;
		    case 6:
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
		        $texto_calif = '<br><h2 class="fas fa-meh-blank text-muted" title="SIN CALIFICAR"></h2>';
		        break;
		    case 1:
		        $texto_calif = '<h1 class="fas fa-frown text-warning title="MALO"></h1>';
		        break;
		    case 2:
		        $texto_calif = '<h1 class="fas fa-meh text-primary" title="NEUTRO"></h1>';
		        break;
		    case 3:
		        $texto_calif = '<h1 class="fas fa-smile text-success" title="BUENO"></h1>';
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
			'Solicitud' => 'Solicitud',
			'Fecha_Asig' => 'Fecha de asignación',
			'Id_Usuario_Asig' => 'Responsable',
			'Fecha_Cierre' => 'Fecha de cierre',
			'Calificacion' => 'Calificación',
			'Fecha_Calificacion' => 'Fecha de Calificación',
			'Id_Usuario_Creacion' => 'Usuario que solicita',
			'Fecha_Creacion' => 'Fecha de creación',
			'Id_Usuario_Actualizacion' => 'ultimo usuario que actualizo',
			'Fecha_Actualizacion' => 'Ultima Fecha de actualización',
			'Soporte' => 'Soporte',
			'Estado' => 'Estado',
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
	
	public function asig()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$user = Yii::app()->user->getState('id_user');

		//$criteria->together  =  true;
	   	//$criteria->with=array('idusuariocre','idusuarioact','idgrupo','idtipo','idusuariodeleg');

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
		    	$criteria->compare('t.Id_Novedad',$this->Id_Novedad);
		    }

		    if($this->Estado == ""){
				$criteria->AddCondition("t.Estado IN (1,4)"); 
		    }else{
		    	$criteria->compare('t.Estado',$this->Estado);
		    }


		    /*if($this->Id_Tipo == ""){
				$criteria->AddCondition("t.Id_Tipo IN (1,4,5)"); 
		    }else{
		    	$criteria->compare('t.Id_Tipo',$this->Id_Tipo);
		    }*/

			
			
			//$criteria->compare('t.Id_Ticket',$this->Id_Ticket);
			/*$criteria->compare('t.Fecha',$this->Fecha,true);
			$criteria->compare('t.Actividad',$this->Actividad,true);
			$criteria->compare('t.Id_Grupo',$this->Id_Grupo);
			$criteria->compare('t.Id_Tipo',$this->Id_Tipo);
			$criteria->compare('t.Prioridad',$this->Prioridad);*/

			

			/*if($this->Id_Grupo == ""){
		    	$criteria->AddCondition("t.Id_Usuario = ".$user." OR t.Id_Usuario_Deleg = ".$user);  
		    }else{
		  		if($this->user_enc != ""){
		    		$criteria->AddCondition("t.Id_Usuario = ".$this->user_enc." OR t.Id_Usuario_Deleg = ".$this->user_enc); 
		    	}	
		    }

			

		    

			if($this->Fecha_Creacion != ""){
	      		$fci = $this->Fecha_Creacion." 00:00:00";
	      		$fcf = $this->Fecha_Creacion." 23:59:59";

	      		$criteria->addBetweenCondition('t.Fecha_Creacion', $fci, $fcf);
	    	}*/

		}else{
			//no se muestran actividades
			$criteria->AddCondition("t.Id_Ticket = 0"); 
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('Id_Ticket',$this->Id_Ticket);
		$criteria->compare('Id_Grupo',$this->Id_Grupo);
		$criteria->compare('Id_Tipo',$this->Id_Tipo);
		$criteria->compare('Solicitud',$this->Solicitud,true);
		$criteria->compare('Fecha_Asig',$this->Fecha_Asig,true);
		$criteria->compare('Id_Usuario_Asig',$this->Id_Usuario_Asig);
		$criteria->compare('Fecha_Cierre',$this->Fecha_Cierre,true);
		$criteria->compare('Calificacion',$this->Calificacion);
		$criteria->compare('Fecha_Calificacion',$this->Fecha_Calificacion,true);
		$criteria->compare('Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->compare('Estado',$this->Estado);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
