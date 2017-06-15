<?php
echo \yii\bootstrap\Html::fileInput('test',null,['id'=>'test']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['goods_id'=>$goods->id],//上传文件的同时传参goods_id
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
//        //将上传成功后的图片地址(data.fileUrl)写入img标签
//        $("#img_logo").attr("src",data.fileUrl).show();
//         //将上传成功后的图片地址(data.fileUrl)写入logon字段
//         $("#brand-logo").val(data.fileUrl);
//设置显示到当前页面的HTML代码
    var html='<tr data-id="'+data.id+'" id="images_'+data.id+'">';
        html+='<td><img src="'+data.fileUrl+'"/></td>';
        html+='<td><button type="button" class="btn btn-danger del_btn">删除</button></td>';
        html+='<tr>';
        $("table").append(html);
    }
}
EOF
        ),
    ]
]);
//echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
?>
<!--设置一个table表-->
<table class="table">
    <tr>
        <th>相册集</th>
        <th>操作</th>
    </tr>
<?php foreach ($images as $img):?>
    <tr id="images_<?=$img->id?>" data-id="<?=$img->id?>">
        <td><?=\yii\bootstrap\Html::img($img->path)?></td>
        <td><?=\yii\bootstrap\Html::button('删除',['class'=>'btn btn-danger del_btn'])?></td>
    </tr>
<?php endforeach;?>
</table>
<?php
$url = \yii\helpers\Url::to(['del-gallery']);
$this->registerJs(new \yii\web\JsExpression(
    <<<EOT
    $("table").on('click',".del_btn",function(){
        if(confirm("确定删除该图片吗?")){
        var id = $(this).closest("tr").attr("data-id");
            $.post("{$url}",{id:id},function(data){
                if(data=="success"){
                    alert("删除成功");
                    $("#images_"+id).remove();
                }
            });
        }
    });
EOT
));
//$url = \yii\helpers\Url::to(['index']);
//$jsnew = <<<EOF
//$.get('{$url}','',function(data){
//    console.log(111);
//});
//EOF;

