<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\web\Cookie;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class CartController extends \yii\web\Controller
{
    public $layout='cart';
    public function actionIndex()
    {
        //return $this->render('index');
    }
    //添加显示购物车页面
    public function actionAddCart(){
        //获取添加购货车的商品货号，和商品数量
            $goods_id=\Yii::$app->request->post('goods_id');
            $amount=\Yii::$app->request->post('amount');
            //var_dump($amount);
            //var_dump($goods_id);exit;
        //通过传送过来的id来找到商品ID
        $goods=Goods::findOne(['id'=>$goods_id]);
        //判断下时候有该商品的数据，没有就提示消息
        //var_dump($goods);exit;
        if($goods==null){
            throw New NotFoundHttpException('商品数据有问题,请重新添加');
        }
        //添加购物车的时候判断用户是否进行了登录,基于cookie的保存来判断
        if(\Yii::$app->user->isGuest){
            //这是没有登录的购物车状态
//            var_dump($amount);
//            var_dump($goods_id);exit;
            //获取商品保存到cookies中的数据
            $cookies=\Yii::$app->request->cookies;
            //下面保存的cart所以这里也用cart来找到cart好保存到cookie中
            $cookie=$cookies->get('cart');
            //var_dump($cookies);exit;
            if($cookie==null){
                //cookie中没有购物车数据
                $cart=[];
            }else{
                //实例化cookie得到的数据
                $cart=unserialize($cookie->value);
                //var_dump($cart);exit;
            }
            //重新实例cookie
            $cookies=\Yii::$app->response->cookies;
            //检查购物车中时候有商品，然后在购物车中存在商品的数量添加，所有这里要进行数量累加功能
            //用Key_exists来检查该数据是否存在于数组中
            //检查购物车中是否有该商品,有，数量累加
            if(key_exists($goods->id,$cart)){
                $cart[$goods_id] += $amount;
            }else{
                $cart[$goods_id] = $amount;
            }
            $cookie=new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            //保存在cookie中

            $cookies->add($cookie);
            //var_dump($cookie);exit;
        }else{
            //这是登录的操作  //获取商品保存到cookies中的数据
         //判断在的时候是否有购物车数据,在用户登录的时候已经合并了cookie中的购物车数据，这个方法做再loginform模型里里面的
            $model = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->identity->getId()]);
            if($model){
                $model->amount=$model->amount+$amount;
                $model->save();
            }else{
                $model=new Cart();
                $model->goods_id=$goods_id;
                $model->amount=$amount;
                $model->member_id=\Yii::$app->user->identity->getId();
                if($model->validate()){
                    $model->save();
                }

            }

        }

        return $this->redirect(['cart/cart']);
    }

    //设置显示购物车数据
    public function actionCart(){
        //判断用户是否进行里的登录的购物车
        if(\Yii::$app->user->isGuest){
            //没有登录的购物车
            //获取cook中保存的商品数据，中包含商品的id和数量，获取后好分配到视图中，进行显示
            $cookies = \Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');
            //var_dump($cookie);exit;
            if($cookie == null){
                //购物车中没有数据
                $cart=[];
            }else{
                //购物车中有数据就实例化cookid中的数据
                $cart=unserialize($cookie->value);
            }
            //var_dump($cart);exit;
            //将获取的商品数据用数组变量来保存
            $models=[];
            foreach ($cart as $k=>$amount){
                //var_dump($k);exit;
                $goods=Goods::findOne(['id'=>$k])->attributes;

                $goods['amount']=$amount;
                $models[]=$goods;
            }
            //var_dump($models);exit;
            //var_dump($models);exit;
        }else{
            //登录了的购物车
                 //已登录，从数据库读取所有数据出来
            $carts=Cart::findAll(['member_id'=>\Yii::$app->user->identity->getId()]);
            $models=[];
            $money=0;
            foreach ($carts as $cart){
                $goods=Goods::findOne(['id'=>$cart->goods_id])->attributes;
                $goods['amount']=$cart->amount;
                $money+=$goods['shop_price']*$goods['amount'];
                $models[]=$goods;


            }
            //var_dump($money);exit;
        }
        return $this->render('cart',['models'=>$models,'money'=>$money]);
    }
        public function actionUpdateCart(){
        //获取传送过来的商品id
                $goods_id=\Yii::$app->request->post('goods_id');
                //获取要修改的商品数量
                $amount=\Yii::$app->request->post('amount');
                //找到向对应的商品详情
            //var_dump($goods_id);exit;
                $goods=Goods::findOne(['id'=>$goods_id]);
            if($goods==null){
                throw new NotFoundHttpException('商品不存在');
            }
                //判断是否有用户登录
            if(\Yii::$app->user->isGuest){
                //未登录
                //先获取cookie中的购物车数据
                $cookies=\Yii::$app->request->cookies;
                $cookie=$cookies->get('cart');
                if($cookie==null){
                    $cart=[];//cookie中没有购物车数据
                }else{
                    $cart=unserialize($cookie->value);
                }
//重新数据化cookie
                $cookies=\Yii::$app->response->cookies;
                //检查购物车中是否有该商品,有，数量累加
                if($amount){
                    $cart['goods_id']=$amount;
                    //var_dump(1);exit;
                }else{
                    if(key_exists($goods['id'],$cart)) unset($cart[$goods_id]);
                    //var_dump(2);exit;
                }
                $cookie=new Cookie([
                   'name'=>'cart','value'=>serialize($cart)
                ]);

                $cookies->add($cookie);
            }else{
                //登录状态的购物车
        $cart=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->identity->getId()]);
            if($amount){
                $cart->amount=$amount;
                $cart->save();
            }else{
                $cart->delete();
            }
            }
        }
        //显示提交订单页面
    public function actionOrder(){
            //获取用户地址
            $id=\Yii::$app->user->identity->getId();
            //var_dump($id);exit;
            $addresses =Address::findAll(['member_id'=>$id]);
            //根据用户id来获取购物车数据
            $carts=Cart::findAll(['member_id'=>$id]);
            //通过购物车数据来遍历出每个商品数据
        //因为是多个数据所以先给商品数据定义一个数字来保存
        $models=[];
        $money=0;
        $zj=0;
        foreach ($carts as $cart){
            $goods=Goods::findOne(['id'=>$cart->goods_id])->attributes;
            //给商品数据赋值数量
            $goods['amount']=$cart->amount;
            $models[]=$goods;
            $money+=$goods['shop_price']*$goods['amount'];
            $zj+=$goods['amount'];
        }


            return $this->render('order',['addresses'=>$addresses,'models'=>$models,'money'=>$money,'zj'=>$zj]);
    }
    //显示支付成功地址
    public function actionOrdergoods(){
        $order=new Order();
      //获取传送过来的数据找到地址详情
        $address_id=\Yii::$app->request->post('address_id');
        if($address_id==null){
            throw new NotFoundHttpException();
        }

        //var_dump($address_id);exit;
        $address = Address::findOne(['id'=>$address_id,'member_id'=>\Yii::$app->user->getId()]);
        //var_dump($address->name);exit;
        //找到传送过来的支付方式
        $delivery_id=\Yii::$app->request->post('delivery');
        //var_dump($delivery_id);exit;
        foreach (Order::$deliverys as $delivery1){
            if($delivery1['id']==$delivery_id){
                    $delivery=$delivery1;
            }
        }
        //var_dump($delivery);exit;
        //var_dump($delivery['id']);exit;
        //找到支付方式的数据
        $pay_id=\Yii::$app->request->post('pay');

        foreach (Order::$pays as $pay1){
            if($pay1['id']==$pay_id){
                $pay=$pay1;
            }
        }
        //通过用户登录的id来找到购物车所有数据
        $carts=Cart::find()->where(['member_id'=>\Yii::$app->user->identity->getId()])->all();
        //默认设置总金额为0
        $money=0;
        foreach ($carts as $ct){
            $gd=Goods::findOne(['id'=>$ct->goods_id])->attributes;
            $money+=$gd['shop_price']*$ct->amount;
        }
       // var_dump($money);exit;

//        $money=\Yii::$app->request->post('total_money');
//        var_dump($money);exit;
        //var_dump($pay['pay_name']);exit;
        if($order->validate()){
            $order->member_id=\Yii::$app->user->identity->getId();
            $order->name=$address->name;
            $order->province=$address->provice;
            $order->city=$address->city;
            $order->area=$address->area;
            $order->address=$address->address;
            $order->delivery_id=$delivery['id'];
            $order->delivery_name=$delivery['name'];
            $order->delivery_price=$delivery['price'];
            $order->payment_id=$pay['id'];
            $order->payment_name=$pay['pay_name'];
            $order->create_time=time();
            $order->total=$delivery['price']+$money;
            $order->status=1;

        }else{
            var_dump($order->getErrors());exit;
        }
////        var_dump($order->getErrors());exit;
//        var_dump($order->id);exit;

        //var_dump($carts);exit;
        //回滚--事物--innnodb存储引擎
        //开启事物,命名一个变量用来保存开启事物，用于提交
        $transcation=\Yii::$app->db->beginTransaction();
        try{
            //保存订单详情
            $order->save();
            //获取订单id
            $id=$order->id;
            //找到购物车数据
            foreach ($carts as $cart){
                //根据购物车数据，通过购物车商品的id把商品详情查询出来，逐条保存
                $good=Goods::findOne(['id'=>$cart->goods_id,'status'=>1]);
                //var_dump($cart->amount);
                //var_dump($good);exit;
                if($good==null){
                    //如果商品不存在
                    throw new Exception('商品已下架');
                }
                if($good->stock<$cart->amount){
                    //库存不足
                    //这里是抛出事物回滚错误信息
                    throw  new Exception('商品库存不足');
                }
                //保存商品订单
                //实例化商品模型
                $order_goods=new OrderGoods();
                if ($order_goods->validate()){
                    $order_goods->order_id=$id;
                    $order_goods->goods_id=$cart->goods_id;
                    $order_goods->goods_name=$good->name;
                    $order_goods->logo=$good->logo;
                    $order_goods->price=$good->shop_price;
                    $order_goods->amount=$cart->amount;
                    $order_goods->total=$good->shop_price*$cart->amount;
                    //商品的订单详情保存
                    $order_goods->save();
                    //保存订单信息后要扣除商品库存数量
                    $good->stock-=$cart->amount;
                    $good->save();

                    //清空购物车
                    $cart->delete();
                }else{
                    var_dump($order_goods->getErrors());exit;
                }
            }
            //事物提交
            $transcation->commit();
        }catch (Exception $exception){
            //事物回滚
            $transcation->rollBack();

        }

        //var_dump($order->id);exit;
       //var_dump($order_goods->goods_id);exit;
        return $this->render('ordergoods');
    }

//    //清理超时未支付的订单
//    public function actionClean(){
//        set_time_limit(0);//不限制脚本执行时机
//        //写个死循环让代码一直执行//下面都是脚本代码
//        while (1){
//            //超时未支付的订单 待支付状态超过1小时=》已取消
//            $models =Order::find()->where(['status'=>1])->andWhere(['<','create_time',time()-7*24*3600])->all();
//            foreach ($models as $model){
//                $model->status=2;
//                $model->save();
//                //订单取消后返回库存
//                foreach ($model->goods as $goods){
//                    Goods::updateAllCounters(['stock'=>$goods->amount],'id'.$goods->goods_id);
//                }
//                echo 'ID为'.$model->id.'的订单取消了....';
//            }
//            //1秒执行一次
//            sleep(1);
//        }
//        }

//显示用户订单状态
public function actionMyOrder(){
    $this->layout='goods';
    //实例化order数据模型
    $orders = Order::findAll(['member_id'=>\Yii::$app->user->id]);
    //var_dump($models);exit;

//分配视图
        return $this->render('myorder',['orders'=>$orders]);
}

}

