<?php
use yii\helpers\Html;
?>
<!-- 页面主体 start -->

<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10">
        <div class="address_hd">
            <h3>收货地址薄</h3>
            <?php foreach ($models as $model1):?>
            <dl>
                <dt><?=$model1->name?>&nbsp;<?=$model1->locations->name?>&nbsp;<?=$model1->locations1->name?>&nbsp;<?=$model1->locations2->name?>&nbsp;<?=$model1->address?>&nbsp;<?=$model1->tel?></dt>
                <dd>
                    <a href="addedit.html?id=<?=$model1->id?>">修改</a>
                    <a href="adddel.html?id=<?=$model1->id?>">删除</a>
                    <a href="status.html?id=<?=$model1->id?>">设为默认地址</a>
                </dd>
            </dl>
        <?php endforeach;?>
            </div>
        <div class="address_bd mt10">
            <h4>新增收货地址</h4>
            <?php
            $form = \yii\widgets\ActiveForm::begin(
                ['fieldConfig'=>[
                    'options'=>[
                        'tag'=>'li'
                    ],
                ]]
            );
            echo '<ul>';
            echo $form->field($model,'name')->textInput(['class'=>'txt']);
            echo $form->field($model,'provice')->dropDownList([],['prompt'=>'请选择','id'=>'provice'])->label('所在地：');
            echo $form->field($model,'city')->dropDownList([],['prompt'=>'请选择','id'=>'city'])->label(false);
            echo $form->field($model,'area')->dropDownList([],['prompt'=>'请选择','id'=>'area'])->label(false);
            $js=new \yii\web\JsExpression(
               <<<JS
               var parent_id;
            var data={'parent_id':0,'level':1};
	//因为页面加载完毕就要展示所以省份的内容，所以建立一个匿名函数并用ajax请求数据库数据
			$(function(){
				//因为要获取数据库内容所以使用ajax的getjson来请求php获取省份内容
				$.getJSON('locations.html',data,function(ele){
					//因为要获取所以省份信息所以要对其进行遍历，通过参数来进行遍历
					$(ele).each(function(i,v){
						//所以将遍历出来的数据都保存到id位box1的下拉框中所以新建一个html代码来保存
						//console.debug(v);
						var html ='<option value='+v.id+'>'+v.name+'</option>';
						//因为要将取得的省份内容保存在页面上，所以要找到要显示省份的节点并添加到该节点内
						$(html).appendTo('#provice');
						
					})
				})
				//因为要通过省份的id来找到城市的id，所以建立一个失去省份失去焦点的事件来获取身份id
				$('#provice').on('change',function(){
				//因为多次选择省份的时候，通过id来找城市，新选择的省份的城市信息依然保存到二级联动里的
			//所以重新选择城市的时候要将其上次的城市选择区县信息删除所以找到节点并删除获取的信息
					$('#city option:not(:first)').remove();
					$('#area option:not(:first)').remove();
					parent_id = $(this).find('option:selected').val();
                    data = {'parent_id':parent_id,'level':2};
					//因为要取得数据库里的城市信息所以用ajax来请教php
					$.getJSON('locations.html',data,function(ele){
						//因为要获取所以城市信息，所以要用参数来进行遍历
						$(ele).each(function(i,v){
							//console.debug(v);
							//因为要将所以遍历的数据显示到页面上所以新建html代码来保存城市信息数据
							var html='<option value='+v.id+'>'+v.name+'</option>';
							//将获取的所有结果保存到要显示的节点里
							$(html).appendTo('#city');
						})
					})
				})
				//同理要因为通过城市的id来查找区县的id所以还是绑定一个二级城市联动失去焦点的世界
				$('#city').on('change',function(){
					//因为重新选择城市的时候要将其上次的城市选择区县信息删除所以找到节点并删除获取的信息
					$('#area option:not(:first)').remove();
						//因为当我重新选择的时候要做一个判断，是否清空
					 parent_id = $(this).find('option:selected').val();
  data = {'parent_id':parent_id,'level':3};
					//因为通过相关联的城市id来获取区县信息，所以要要用ajax来请求php数据
					$.getJSON('locations.html',data,function(ele){
						//因为要获取所有区县信息所以要用参数对所有数据进行遍历
						$(ele).each(function(i,v){
							// console.debug(v);
							//获取所有遍历的结果集
							var html='<option value='+v.id+'>'+v.name+'</option>';
							//将所有结果集保存到节点为area的下拉框里
							$(html).appendTo('#area');
							
						})
					})
				})
			})

JS
            );
            $this->registerJs($js);
            echo $form->field($model,'address')->textInput(['class'=>'txt address']);
            echo $form->field($model,'tel')->textInput(['class'=>'txt']);
            echo $form->field($model,'status')->checkbox(['class'=>'check']);
            echo ' <li>
                        <label for="">&nbsp;</label>
                        <input type="submit" name="" class="btn" value="保存" />
                    </li>';
            echo '</ul>';
            \yii\widgets\ActiveForm::end();
            ?>

        </div>

    </div>
    <!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->

<div style="clear:both;"></div>





