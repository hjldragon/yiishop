<!-- 登录主体部分start -->
<?php
use yii\helpers\Html;
?>
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">
            <?php
            //注册表单
            $form = \yii\widgets\ActiveForm::begin(
                    ['fieldConfig'=>[
                    'options'=>[
                            'tag'=>'li'
                    ],
                        'errorOptions'=>[
                             'tag'=>'p',
                            ]
            ]]
            );
            echo '<ul>';
            echo $form->field($model,'username'
        //这是单独配置样式
//                ,[
//                    'options'=>[
//                            'tag'=>'li',
//                    ]]
            )->textInput(['class'=>'txt']);
            echo $form->field($model,'password')->passwordInput(['class'=>'txt']);
            echo $form->field($model,'password2')->passwordInput(['class'=>'txt']);
            echo $form->field($model,'email')->textInput(['class'=>'txt']);
            echo $form->field($model,'tel')->textInput(['class'=>'txt']);
            $button=Html::button('发送短信验证码',['id'=>'send_sms_button']);
            echo $form->field($model,'smsCode',['options'=>['class'=>'checkcode'],'template'=>
            "{label}\n{input}$button\n{hint}\n{error}"])->textInput(['class'=>'txt']);
            echo $form->field($model,'code',[
                    'options'=>['class'=>'checkcode']
            ])->widget(\yii\captcha\Captcha::className(),['template' => '{input} {image}']);
            echo '		<li>
							<label for="">&nbsp;</label>
							<input type="checkbox" class="chb" checked="checked" /> 我已阅读并同意《用户注册协议》
						</li>';
            echo '     <li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="" class="login_btn" />
                    </li>';
            echo '</ul>';
            \yii\widgets\ActiveForm::end();
            ?>


        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->
<?php
/** @var $this \yii\web\View
 * */
$url =\yii\helpers\Url::to(['user/send-sms']);
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
    $("#send_sms_button").click(function() {
      var tel=$("#member-tel").val()
      //post提交到tel参数到user/send-sms控制器中
      $.post('$url',{tel:tel},function(data) {
        if(data=='success'){
            console.debug('短信发送成功');
            alert('短信发送成功');
        }else{
            console.debug(data);
        }
      })
    })
JS

))
?>