<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $name
 * @property integer $member_id
 * @property string $provice
 * @property string $city
 * @property string $area
 * @property string $address
 * @property integer $tel
 * @property integer $status
 */
class Address extends \yii\db\ActiveRecord
{
    //设置与三级联动的1对1关系
    public function getLocations(){
        return $this->hasOne(Locations::className(),['id'=>'provice']);
    }
    public function getLocations1(){
        return $this->hasOne(Locations::className(),['id'=>'city']);
    }

    public function getLocations2(){
        return $this->hasOne(Locations::className(),['id'=>'area']);
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'member_id', 'provice', 'city', 'address', 'tel'], 'required'],
            [['member_id', 'tel', 'status'], 'integer'],
            [['tel'], 'string', 'max' => 11],
            [['name'], 'string', 'max' => 50],
            [['provice', 'city', 'area'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '收货人',
            'member_id' => '用户ID',
            'provice' => '省份',
            'city' => '城市',
            'area' => '地区',
            'address' => '详细地址',
            'tel' => '电话号码',
            'status' => '设置默认地址',
        ];
    }
}
