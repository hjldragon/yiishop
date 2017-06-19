<?=\yii\bootstrap\Html::a('添加角色',['rbac/add-role'],['class'=>'btn btn-primary'])?>
<table class="table table-bordered table-responsive">
    <tr>
        <th>角色名</th>
        <th>描述</th>
        <th>拥有权限</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->description?></td>
            <td><?php
                foreach (Yii::$app->authManager->getPermissionsByRole($model->name) as $permission){
                    echo $permission->description;
                    echo ',';
                }
                ?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['rbac/edit-role','name'=>$model->name],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::a('删除',['rbac/del-role','name'=>$model->name],['class'=>'btn btn-danger'])?>
            </td>
        </tr>
<?php endforeach;?>
</table>
