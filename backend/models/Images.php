<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "images".
 *
 * @property integer $goods_id
 * @property string $path
 * @property integer $id
 */
class Images extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => 'Good ID',
            'path' => 'Path',
            'id' => 'ID',
        ];
    }
}
