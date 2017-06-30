<?php
namespace frontend\models;

use yii\base\Model;
use yii\db\ActiveQuery;

class GoodsSearchForm extends Model{
    //设置搜索字段
    public $name;
    public  function rules()
    {
        return [
          ['name','string','max'=>50]
        ];
    }
    //封装一个搜索的方法用于GOODS的显示页面
    public  function  search(ActiveQuery $all){
        $this->load(\Yii::$app->request->get());
        if($this->name){
            $all->Where(['like','name',$this->name]);
        }

    }
}