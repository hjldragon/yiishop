<?php
use \yii\web\JsExpression;
$from =\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'name');
echo $from->field($model,'intro')->textarea();
echo $from->field($model,'logo')->hiddenInput();
//echo $from->field($model,'imgFile')->fileInput(['id'=>'test']);
//if($model->logo) echo "<img src='$model->logo' width='200' height='50'></img>";
echo \yii\bootstrap\Html::fileInput('test',null,['id'=>'test']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new \yii\web\JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new \yii\web\JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将上传成功后的图片地址(data.fileUrl)写入img标签
        $("#img_logo").attr("src",data.fileUrl).show();
         //将上传成功后的图片地址(data.fileUrl)写入logon字段
         $("#brand-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
//echo '2';
//echo \yii\helpers\Html::img('@web',$model->logo);
if($model->logo){
    echo \yii\helpers\Html::img($model->logo,['id'=>'img_logo','height'=>50]);
}else{
    echo \yii\helpers\Html::img('',['style'=>'display:none','id'=>'img_logo','height'=>50]);
}
//echo '1';
echo $from->field($model,'sort');
if($model->status!=-1){
    echo $from->field($model,'status',['inline'=>true])->radioList([1=>'正常',0=>'隐藏']);
}else{
    echo $from->field($model,'status',['inline'=>true])->radioList(\backend\models\Brand::$sexOption);
}

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();