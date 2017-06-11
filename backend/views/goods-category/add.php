<?php
/**
 * @var $this \yii\web\View
*/
//写上面这段注释是为了方便$this的使用有代码提示！
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'parent_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($model,'intro')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();
//使用ztree来表示drop，所以要先来加载3个静态资源ztree.php那三个路径资源
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',
    ['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes=\yii\helpers\Json::encode($category);
$js=new \yii\web\JsExpression(
    <<<JS
    var zTreeObj;
    // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
        },
        	callback: {
		onClick:function(event, treeId, treeNode) {
		    //测试拿到分类的id
		   //console.log(treeNode.id);
		   //将选中的节点id赋值给表单中的parent_id
		   $("#goodscategory-parent_id").val(treeNode.id);
		}
	}
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes ={$zNodes};
   
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    //设置展开所有节点
    zTreeObj.expandAll(true);
    //修改的时候要先找到当前的父节点(根据id找到）//添加的时候也就默认选等级分类
    var node = zTreeObj.getNodesByParam("id",$("#goodscategory-parent_id").val(), null);
    zTreeObj.selectNode(node);//要修改的时候选中的父节点
JS

);
$this->registerJs($js);
?>

