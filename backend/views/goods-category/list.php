<?=\yii\helpers\Html::a('添加分类',['goods-category/add'],['class'=>'btn btn-primary'])?>
<table class="table table-responsive">
    <tr>
        <th>ID</th>
        <th>树ID</th>
        <th>左值</th>
        <th>右值</th>
        <th>层级</th>
        <th>名称</th>
        <th>上级分类ID</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->tree?></td>
        <td><?=$model->lft?></td>
        <td><?=$model->rgt?></td>
        <td><?=$model->depth?></td>
        <td><?=$model->name?></td>
        <td><?=$model->parent_id?></td>
        <td><?=$model->intro?></td>
        <td><?=\yii\bootstrap\Html::a('删除',['goods-category/del','id'=>$model->id],['class'=>'btn btn-danger'])?>
            <?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-primary'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page
]);


