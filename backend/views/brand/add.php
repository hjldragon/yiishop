<?php
$from =\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'name');
echo $from->field($model,'intro')->textarea();
echo $from->field($model,'imgFile')->fileInput();
if($model->logo) echo "<img src='$model->logo' width='200' height='50'></img>";
echo $from->field($model,'sort');
if($model->status!=-1){
    echo $from->field($model,'status',['inline'=>true])->radioList([1=>'正常',0=>'隐藏']);
}else{
    echo $from->field($model,'status',['inline'=>true])->radioList(\backend\models\Brand::$sexOption);
}

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();