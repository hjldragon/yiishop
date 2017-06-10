<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'tree');
echo $form->field($model,'lft');
echo $form->field($model,'rgt');
echo $form->field($model,'depth');
echo $form->field($model,'name');
echo $form->field($model,'parent_id');
echo $form->field($model,'intro');

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();