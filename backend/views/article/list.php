<?=\yii\bootstrap\Html::a('添加文章',['article/add'],['class'=>'btn btn-primary'])?>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>文章名称</th>
            <th>文章分类id</th>
            <th>简介</th>
            <th>排序</th>
            <th>状态</th>
            <th>发布时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($models as $model):?>
            <tr>
                <td><?=$model->id?></td>
                <td><?=$model->name?></td>
                <td><?=$model->articlecategory->name?></td>
                <td><?=$model->intro?></td>
                <td><?=$model->sort?></td>
                <td><?=\backend\models\Article::$sexOption[$model->status];?></td>
                <td><?=date('Y/m/d G:i:s',$model->create_time)?></td>
                <td><?=\yii\bootstrap\Html::a('删除',['article/del','id'=>$model->id],['class'=>'btn btn-danger'])?>
                    <?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$model->id],['class'=>'btn btn-primary'])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
])

?>