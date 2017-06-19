<?=\yii\bootstrap\Html::a('添加账号',['user/add'],['class'=>'btn btn-primary'])?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>密码</th>
        <th>邮箱</th>
        <th>所有拥有的角色</th>
        <th>状态</th>
        <th>最后登录时间</th>
        <th>最后登录IP地址</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>

        <td><?=$model->id?></td>
        <td><?=$model->username?></td>
        <td><?=$model->password_hash?></td>
        <td><?=$model->email?></td>
        <td><?php
            foreach (Yii::$app->authManager->getRolesByUser($model->id) as $role){
                echo $role->name;
                echo '|';
            }
            ?></td>
        <td><?=\backend\models\User::$sexOption[$model->status]?></td>
        <td><?=$model->last_time?></td>
        <td><?=$model->last_ip?></td>
        <td>
            <?=\yii\bootstrap\Html::a('删除',['user/del','id'=>$model->id],['class'=>'btn btn-danger'])?>
            <?=\yii\bootstrap\Html::a('修改',['user/edit','id'=>$model->id],['class'=>'btn btn-primary'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
]);