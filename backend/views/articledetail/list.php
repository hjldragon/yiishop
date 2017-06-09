<?=\yii\bootstrap\Html::a('添加文章',['articledetail/add'],['class'=>'btn btn-primary'])?>
    <table class="table table-bordered">
        <tr>
            <th>文章标题</th>
            <th>状态</th>
            <th>内容</th>
            <th>操作</th>
        </tr>
        <?php foreach ($models as $model):?>
            <tr>
                <td><?=$model->article->name?></td>
                <td><?=\backend\models\Article::$sexOption[$model->article->status]?></td>
                <td><?=$model->content?></td>
                <td><?=\yii\bootstrap\Html::a('删除',['articledetail/del','id'=>$model->article_id],['class'=>'btn btn-danger'])?>
                    <?=\yii\bootstrap\Html::a('修改',['articledetail/edit','id'=>$model->article_id],['class'=>'btn btn-primary'])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
])

?>