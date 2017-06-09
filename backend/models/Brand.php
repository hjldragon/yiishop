<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    //给LOGO图片定义一个属性
    public  $imgFile;
    //设置静态的状态字段属性
    static public $sexOption=[-1=>'删除',1=>'正常',0=>'隐藏'];
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','logo'], 'required'],
            ['intro', 'string'],
            [['sort', 'status'], 'integer'],
            ['name', 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 255],
            //['imgFile','file','extensions'=>['jpg','png','gif']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'logo' => 'LOGO图片',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}
