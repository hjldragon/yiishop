<?php
namespace backend\models;


use yii\base\Model;
use yii\db\ActiveQuery;

class GoodsSearchForm extends Model{
    //命名搜索的相关遍历
    public $name;
    public $sn;
    public $minPrice;
    public $maxPrice;
    //设置搜索的验证方法
    public function rules()
    {
        return [
            ['name','string','max'=>50],
            ['sn','string'],
            ['minPrice','double'],
            ['maxPrice','double'],
        ];
    }
    //封装一个搜索的方法用于GOODS的显示页面
    public  function  search(ActiveQuery $all){
        $this->load(\Yii::$app->request->get());
        if($this->name){
            $all->andWhere(['like','name',$this->name]);
        }if($this->sn){
            $all->andWhere(['like','sn',$this->sn]);
        }if($this->minPrice){
            $all->andWhere(['<=','shop_price',$this->minPrice]);
        }if($this->maxPrice){
            $all->andWhere(['>=','shop_price',$this->maxPrice]);
        }

    }
}