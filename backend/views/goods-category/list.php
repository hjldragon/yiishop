<?=\yii\helpers\Html::a('添加分类',['goods-category/add'],['class'=>'btn btn-primary'])?>
<table class="cate table table-responsive table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
    <tr data-tree="<?=$model->tree?>" data-lft="<?=$model->lft?>" data-rgt="<?=$model->rgt?>">
        <td><?=$model->id?></td>
        <td><?=str_repeat('-',$model->depth).$model->name?>
        <span class="but glyphicon glyphicon-chevron-down" style="float: right;"></span>
        </td>
        <td><?=\yii\bootstrap\Html::a('删除',['goods-category/del','id'=>$model->id],['class'=>'btn btn-danger'])?>
            <?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-primary'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
$js=<<<JS
     $(".but").click(function() {
       var tr=$(this).closest('tr');
       var tree=parseInt(tr.attr('data-tree'));
       var lft=parseInt(tr.attr('data-lft'));
       var rgt=parseInt(tr.attr('data-rgt'));
       //console.debug(lft);
       //显示还是隐藏，是否是显示
       var show=$(this).hasClass('glyphicon-chevron-up');
       //切换图标
       $(this).toggleClass('glyphicon-chevron-up');
       $(this).toggleClass('glyphicon-chevron-down');
       $(".cate tr").each(function() {
                  //
           if(parseInt($(this).attr('data-tree'))==tree && parseInt($(this).attr('data-lft'))>lft && parseInt($(this).attr('data-rgt'))<rgt ){
                 show?$(this).show():$(this).hide();
                    console.debug(this);
           } 
       });
     });
JS;
$this->registerJs($js);



