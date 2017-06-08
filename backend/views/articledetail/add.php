<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'article_id')->dropDownList(\yii\helpers\ArrayHelper::map($article,'id','name'));
echo $form->field($model,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('发布',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();