<?php
use yii\helpers\Html;
foreach ($models as $k=>$model):?>
    <div class="cat <?=$k=0?"item1":""?>">
        <h3><a href=""><?=Html::a($model->name,['goods/list','cate_id'=>$model->id])?></a><b></b></h3>
        <div class="cat_detail">
            <?php foreach ($model->children as $k2=>$child):?>
                <dl <?=$k2==0?'class="dl_1st"':''?>>
                    <dt><?=Html::a($child->name,['goods/list','cate_id'=>$child->id])?></dt>
                    <dd>
                        <?php foreach ($child->children as $cate):?>
                            <?=Html::a($cate->name,['goods/list','cate_id'=>$cate->id])?>
                        <?php endforeach;?>
                    </dd>
                </dl>
            <?php endforeach;?>

        </div>
    </div>
<?php endforeach;?>