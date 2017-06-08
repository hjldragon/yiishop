<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "articledetail".
 *
 * @property integer $article_id
 * @property string $content
 */
class Articledetail extends \yii\db\ActiveRecord
{
    //建立一对多的文章
    public function getArticle(){
       return $this->hasOne(Article::className(),['id'=>'article_id']);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'articledetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['article_id', 'required'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => '文章id',
            'content' => '简介',
        ];
    }
}
