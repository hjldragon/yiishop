<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'password_hash')->passwordInput();
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction'=>'user/captcha','template'=>'<div class="row">
<div class="col-lg-1">{image}</div><div class="col-lg-2">{input}</div ></div>']);
echo $form->field($model,'remember')->checkbox();
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-primary']);
echo '<br/>';
echo \yii\bootstrap\Html::a('注册账号',['user/add'],['class'=>'btn btn-primary']);
echo \yii\bootstrap\Html::a('忘记密码',['user/forget'],['class'=>'btn btn-danger']);
\yii\bootstrap\ActiveForm::end();