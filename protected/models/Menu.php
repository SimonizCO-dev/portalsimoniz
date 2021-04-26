<?php

/**
 * This is the model class for table "T_PR_MENU".
 *
 * The followings are the available columns in table 'T_PR_MENU':
 * @property integer $Id_Menu
 * @property integer $Id_Padre
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Descripcion
 * @property string $Link
 * @property integer $Orden
 * @property int $Descarga_Directa
 * @property string $Descripcion_Larga
 * @property boolean $Estado
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property Menu $idPadre
 * @property Usuario $idUsuarioActualizacion
 * @property Usuario $idUsuarioCreacion

 */
class Menu extends CActiveRecord
{
	public $padre;
	public $orderby;

	/**
	 * @return string the associated database table name
	 */

	public function tableName()
	{
		return 'T_PR_MENU';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Padre, Descripcion, Link, Orden, Descarga_Directa, Estado', 'required','on'=>'create , update'),
			array('Id_Padre, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Orden, Descarga_Directa', 'numerical', 'integerOnly'=>true),
			array('Descripcion, Link', 'length', 'max'=>50),
			array('Descripcion_Larga', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Menu, padre, Descripcion, Link, Orden, Descarga_Directa, Estado, Fecha_Creacion, Fecha_Actualizacion, Id_Usuario_Creacion, Id_Usuario_Actualizacion, orderby', 'safe', 'on'=>'search'),
		);
	}

	public function ParentMenu($Id_Padre){

        $opc = Menu::model()->findByPk($Id_Padre);
        $Parent1 = Menu::model()->findByPk($opc->Id_Menu);
        $Parent2 = Menu::model()->findByPk($Parent1->Id_Padre);

        if($Parent1->Id_Padre == $Parent2->Id_Padre){
        	return $Parent1->idpadre->Descripcion;
        }else{
        	return $Parent2->idpadre->Descripcion.' -> '.$Parent1->idpadre->Descripcion;	
        }	

    }

    public function DescOpcPadre($Id_Menu){

    	$Parent1 = Menu::model()->findByPk($Id_Menu);
	    $Parent2 = Menu::model()->findByPk($Parent1->Id_Padre);

    	if(intval($Id_Menu) == 1){
    		return $Parent1->Descripcion;
    	}else{

	        $Parent1 = Menu::model()->findByPk($Id_Menu);
	        $Parent2 = Menu::model()->findByPk($Parent1->Id_Padre);

	        return $Parent2->Descripcion.' -> '.$Parent1->Descripcion;	
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
			'idpadre' => array(self::BELONGS_TO, 'Menu', 'Id_Padre'),
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
			'Id_Menu' => 'ID',
			'Id_Padre' => 'Opción padre',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Descripcion' => 'Descripción',
			'Link' => 'Link',
			'Orden' => 'Orden',
			'Descarga_Directa' => 'Descarga directa',
			'Descripcion_Larga' => 'Descripción larga',
			'Estado' => 'Estado',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'padre' => 'Opción padre',
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

		$criteria->together  =  true;
	   	$criteria->with=array('idusuariocre','idusuarioact', 'idpadre');

		$criteria->compare('t.Id_Menu',$this->Id_Menu);
		$criteria->compare('t.Descripcion',$this->Descripcion,true);
		$criteria->compare('t.Link',$this->Link,true);
		$criteria->compare('t.Orden',$this->Orden);
		$criteria->compare('t.Descarga_Directa',$this->Descarga_Directa);
		$criteria->compare('t.Estado',$this->Estado);
		$criteria->AddCondition("t.Id_Menu != 1"); 

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
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Creacion); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

	    if($this->padre != ""){
			$criteria->AddCondition("idpadre.Id_Menu = ".$this->padre); 
	    }

	    if(empty($this->orderby)){
			$criteria->order = 't.Id_Menu DESC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_Menu ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Menu DESC'; 
			        break;
			    case 3:
			        $criteria->order = 'idpadre.Descripcion ASC'; 
			        break;
			    case 4:
			        $criteria->order = 'idpadre.Descripcion DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.Descripcion ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Descripcion DESC'; 
			        break;
			    case 7:
			        $criteria->order = 't.Orden ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Orden DESC'; 
			        break;
			    case 9:
			        $criteria->order = 't.Link ASC'; 
			        break;
			    case 10:
			        $criteria->order = 't.Link DESC'; 
			        break; 
		        case 11:
			        $criteria->order = 'idusuariocre.Usuario ASC'; 
			        break;
			    case 12:
			        $criteria->order = 'idusuariocre.Usuario DESC'; 
			        break;
			    case 13:
			        $criteria->order = 't.Fecha_Creacion ASC'; 
			        break;
			    case 14:
			        $criteria->order = 't.Fecha_Creacion DESC'; 
			        break;
			    case 15:
			        $criteria->order = 'idusuarioact.Usuario ASC'; 
			        break;
			    case 16:
			        $criteria->order = 'idusuarioact.Usuario DESC'; 
			        break;
				case 17:
			        $criteria->order = 't.Fecha_Actualizacion ASC'; 
			        break;
			    case 18:
			        $criteria->order = 't.Fecha_Actualizacion DESC'; 
			        break;
			    case 19:
			        $criteria->order = 't.Estado DESC'; 
			        break;
			    case 20:
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
	 * @return Menu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
