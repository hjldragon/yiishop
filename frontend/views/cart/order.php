<?php
use yii\helpers\Html;
?>
<div style="clear:both;"></div>
<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="/goodscategory/index.html"><?=Html::img('@web/images/logo.png');?></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
    <form id="form" action="<?=\yii\helpers\Url::to(['cart/ordergoods'])?>" method="post">
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">

        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($addresses as $address):?>
                <p>
                    <input type="radio" value="<?=$address->id?>" name="address_id" <?=$address->status==1?"checked":""?>/><?=$address->name?>  <?=$address->tel?>  <?=$address->provice?> <?=$address->city?> <?=$address->area?> <?=$address->address?> </p>

                <?php endforeach;?>
            </div>


        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach (\frontend\models\Order::$deliverys as $k=>$delivery):?>
                    <tr <?=$k==0?'class="cur"':''?>>
                        <td>
                            <input type="radio" name="delivery"  <?=$k==0?'checked="checked"':''?> value="<?=$delivery['id']?>" /><?=$delivery['name']?>
                        </td>
                        <td id="delivery">￥<?=$delivery['price']?></td>
                        <td><?=$delivery['info']?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php foreach (\frontend\models\Order::$pays as $i=>$pay):?>
                    <tr <?=$i==0?'class="cur"':''?>>
                        <td class="col1"><input type="radio" name="pay" value="<?=$pay['id']?>" /><?=$pay['pay_name']?></td>
                        <td class="col2"><?=$pay['pay_info']?></td>
                    </tr>
                    <?php endforeach;?>

                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($models as $model):?>
                <tr class="count" data-count="<?=$model['shop_price']*$model['amount']?>">
                    <td class="col1"><a href=""><?=Html::img(Yii::$app->params['imageDomain'].$model['logo'])?></a>  <strong><a href=""><?=$model['name']?></a></strong></td>
                    <td class="col3"><?=$model['shop_price']?></td>
                    <td class="col4"><?=$model['amount']?></td>
                    <td class="col5"><span><?=$model['shop_price']*$model['amount']?></span></td>
                </tr>
                <?php endforeach;?>

                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span id="count"><?=$zj?> 件商品，总商品金额：</span>
                                <em id="money">￥<?=$money?></em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em id="delivery">10.00</em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em class="total_money"></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
<!--        <span><input type="submit" value="提交订单"></span>-->
        <a href="javascript:;" id="button"><span>提交订单</span></a>
        <input name="_csrf-frontend" type="hidden" id="_csrf-frontend"
               value="<?= Yii::$app->request->csrfToken ?>">
<!--        <input type="hidden" name="total_money" value='<em class="total_money"></em>'>-->
        <p>应付总额：<strong class="total_money"></strong></p>

    </div>
</div>
    </form>
<!-- 主体部分 end -->

<div style="clear:both;"></div>
<?php
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
        $(function() {
           var count=0;
           var money=0;
           $('.count').each(function(i,v) {
               count+=1;
               money+=parseInt($(v).attr('data-count'));
           })
            $('#count').find('span').text(count);
           $('#money').text(money);
            //获取应付总额
           $('.total_money').text(money+parseInt($('.cur').find('td:eq(1)').text().substring(1,$('.cur').find('td:eq(1)').text().length)));
       //送货方式的改变，改变运费
       $('input[name=delivery]').change(function() {
        var b= $('#delivery').text('￥'+parseInt($('cur').find('td:eq(1)').text().substring(1,$('.cur').find('td:eq(1)').text().length)));
        
         //获取应付总额
         var a =$('.total_money').text(parseInt($('#money').text())+parseInt($('.cur').find('td:eq(1)').text().substring(1,$('.cur').find('td:eq(1)').text().length)));
         // console.debug(a);
         // console.debug(b);exit;    
         })
            $('#button').click(function() {
                if($('.pay_select input:checked').length==0){
                    alert('未选择支付方式,请选择支付方式');
                }else{
                    $('#form').submit();
                }
                
            })
       })
JS

));
