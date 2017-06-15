<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'email');
echo $form->field($model,'oldpassword')->passwordInput();
echo $form->field($model,'newpassword')->passwordInput();
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),['captchaAction'=>'user/captcha',
    'template'=>'<div class="row"><div class="col-lg-1">{image}</div><div class="col-lg-2">{input}</div ></div>']);
echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();