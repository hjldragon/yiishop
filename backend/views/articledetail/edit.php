<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'articlecategory_id')->dropDownList(\yii\helpers\ArrayHelper::map($article,'id','name'));
echo $form->field($model,'sort');
if($model->status!=-1){
    echo $form->field($model,'status',['inline'=>true])->radioList([1=>'正常',0=>'隐藏']);
}else{
    echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\Articlecategory::$sexOption);
}
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();