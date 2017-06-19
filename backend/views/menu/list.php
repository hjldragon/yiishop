<?=\yii\bootstrap\Html::a('添加菜单',['menu/add'],['class'=>'btn btn-primary'])?>
<table class="table table-responsive table-bordered">
    <tr>
        <th>ID</th>
        <th>菜单名</th>
        <th>跳转地址</th>
        <th>父级名</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->label?></td>
        <td><?=$model->url?></td>
        <td><?php
            if($model->parent_id==0){
                echo '顶级菜单';
            }else{
                $id=$model->parent_id;
                $menu=\backend\models\Menu::findOne(['id'=>$id]);
                echo $menu->label;
            }

            ?></td>
        <td><?=$model->sort?></td>
        <td>
            <?=\yii\bootstrap\Html::a('删除',['menu/del','id'=>$model->id],['class'=>'btn btn-danger'])?>
            <?=\yii\bootstrap\Html::a('修改',['menu/edit','id'=>$model->id],['class'=>'btn btn-warning'])?>
        </td>
    </tr>
<?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
])

?>