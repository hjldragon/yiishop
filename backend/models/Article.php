<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $articlecategory_id
 * @property integer $sort
 * @property integer $status
 * @property integer $create_time
 */
class Article extends \yii\db\ActiveRecord
{
    static public $sexOption=[-1=>'删除',1=>'正常',0=>'隐藏'];
    //建立1多的查询
    public function getArticlecategory(){
        return $this->hasOne(Articlecategory::className(),['id'=>'articlecategory_id']);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['intro'], 'string'],
            [['articlecategory_id', 'sort', 'status', 'create_time'], 'integer'],
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
            'name' => '文章名称',
            'intro' => '简介',
            'articlecategory_id' => '文章id',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '类型',
        ];
    }
}
