<?=\yii\bootstrap\Html::a('添加文章分类',['articlecategory/add'],['class'=>'btn btn-primary'])?>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>文章分类名称</th>
            <th>简介</th>
            <th>排序</th>
            <th>状态</th>
            <th>类型</th>
            <th>操作</th>
        </tr>
        <?php foreach ($models as $model):?>
            <tr>
                <td><?=$model->id?></td>
                <td><?=$model->name?></td>
                <td><?=$model->intro?></td>
                <td><?=$model->sort?></td>
                <td><?=\backend\models\Brand::$sexOption[$model->status];?></td>
                <td><?=$model->is_help?></td>
                <td><?=\yii\bootstrap\Html::a('删除',['articlecategory/del','id'=>$model->id],['class'=>'btn btn-danger'])?>
                    <?=\yii\bootstrap\Html::a('修改',['articlecategory/edit','id'=>$model->id],['class'=>'btn btn-primary'])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
])

?>