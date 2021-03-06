<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "articlecategory".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $sort
 * @property integer $status
 * @property integer $is_help
 */
class Articlecategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    //设置静态的状态字段属性
    static public $sexOption=[-1=>'删除',1=>'正常',0=>'隐藏'];
    public static function tableName()
    {
        return 'articlecategory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['intro'], 'string'],
            [['sort', 'status', 'is_help'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '文章分类名称',
            'intro' => '简介',
            'sort' => '排序',
            'status' => '状态',
            'is_help' => '类型',
        ];
    }
}
