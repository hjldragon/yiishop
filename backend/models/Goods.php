<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $good_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property string $create_time
 */
class Goods extends \yii\db\ActiveRecord
{
    //设置图片的变量
    public $imgFile;
    //设置商品状态情况
    static public $sexOption=[1=>'正常',2=>'回收'];
    static public $sexOption2=[1=>'在售',2=>'下架'];
    //设置商品1对1商品分类列表
    public function getGoodsCategory(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'good_category_id']);
    }
    //设置商品1对1商品详情列表
    public function getGoodsIntro(){
        return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id']);
    }
//设置商品与商品品牌1对1的关系
public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','good_category_id','is_on_sale', 'status'], 'required'],
            [['good_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn', 'logo', 'create_time'], 'string', 'max' => 255],
            ['imgFile','file','extensions'=>['png','jpg','gif']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'logo图片',
            'good_category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '添加时间',
        ];
    }
}
