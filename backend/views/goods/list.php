<?=\yii\bootstrap\Html::a('添加商品',['goods/add'],['class'=>'btn btn-primary'])?>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>排序</th>
            <th>name</th>
            <th>货号</th>
            <th>LOGO</th>
            <th>商品分类名</th>
            <th>品牌分类名</th>
            <th>商品价格</th>
            <th>库存</th>
            <th>出售状态</th>
            <th>商品状态</th>
            <th>商品添加时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($models as $model):?>
            <tr>
                <td><?=$model->id?></td>
                <td><?=$model->sort?></td>
                <td><?=$model->name?></td>
                <td><?=$model->sn?></td>
                <td><?=\yii\bootstrap\Html::img($model->logo,['width'=>60,'height'=>40])?></td>
                <td><?=$model->goodsCategory->name?></td>
                <td><?=$model->brand->name?></td>
                <td><?=$model->shop_price?></td>
                <td><?=$model->stock?></td>
                <td><?=\backend\models\Goods::$sexOption2[$model->is_on_sale];?></td>
                <td><?=\backend\models\Goods::$sexOption[$model->status];?></td>
                <td><?=$model->create_time?></td>


                <td>
                    <?=\yii\bootstrap\Html::a('详情',['goods/content','id'=>$model->id],['class'=>'btn btn-primary'])?>
                    <?=\yii\bootstrap\Html::a('删除',['goods/del','id'=>$model->id],['class'=>'btn btn-danger'])?>
                    <?=\yii\bootstrap\Html::a('修改',['goods/edit','id'=>$model->id],['class'=>'btn btn-primary'])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
])

?>