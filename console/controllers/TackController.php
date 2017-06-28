<?php
namespace console\controllers;

use backend\models\Goods;
use frontend\models\Order;
use yii\console\Controller;

class TackController extends Controller{

    //清理超时未支付的订单
    public function actionClean(){
        set_time_limit(0);//不限制脚本执行时机
        //写个死循环让代码一直执行//下面都是脚本代码
        while (1){
            //超时未支付的订单 待支付状态超过1小时=》已取消
            $models =Order::find()->where(['status'=>1])->andWhere(['<','create_time',time()-24*3600])->all();
            foreach ($models as $model){
//                $model->status=2;
//                $model->save();
//                //订单取消后返回库存
//                foreach ($model->goods as $goods){
//                    Goods::updateAllCounters(['stock'=>$goods->amount],'id'.$goods->goods_id);
//                }
                echo "ID为".$model->id."has been clean...\n";
            }
            //1秒执行一次
            sleep(1);
        }
    }
}
