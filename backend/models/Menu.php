<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property integer $parent_id
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    //设置一个自己表中的1多多的关系，ID对父类id的关系
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label'], 'required'],
            [['parent_id', 'sort'], 'integer'],
            [['label'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '菜单名',
            'url' => '地址',
            'parent_id' => '父级id',
            'sort' => '排序',
        ];
    }
    static public function getParentId(){
        $menu1 = self::find()->where('parent_id=0')->asArray()->all();
        $menu=ArrayHelper::merge([['label'=>'顶级菜单','id'=>0]],$menu1);
        return ArrayHelper::map($menu,'id','label');
    }
    //设置一个二级分类显示
    public function sort(){
        $models=[];
        $fathers = self::findAll(['parent_id'=>0]);
        foreach ($fathers as $father){
            $models[]=$father;
            //$childrens =self::findAll([''])
        }
    }
}
