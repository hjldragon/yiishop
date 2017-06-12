<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use function foo\func;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    //测试嵌套组件
    public function actionTest()
    {
//     $molde = new GoodsCategory();
//     $molde->name='家用电器';
//     $molde->parent_id=0;
//     $molde->makeRoot();
//     var_dump($molde);
        //测试创建二级分类
        //找到家用电器的父类节点
//        $parent=GoodsCategory::findOne(['id'=>7]);
//        $xmodel = new GoodsCategory();
//        $xmodel->name='小家电';
//        $xmodel->parent_id=$parent->id;
//        $xmodel->prependTo($parent);
//        echo '添加成功！';
        //获取所有一级分类
//        $roots = GoodsCategory::find()->roots()->all();
//        var_dump($roots);

    }

    //测试树桩
    public function actionZtree()
    {
        //把所有分类都找出来
        $category = GoodsCategory::find()->asArray()->all();
        return $this->renderPartial('ztree', ['category' => $category]);
    }

    //设置商品分类添加类
    public function actionAdd()
    {
        //获取数据模型
        $model = new GoodsCategory();
        // var_dump($model);exit;
        //加载数据并判断传送方式
        if ($model->load(\Yii::$app->request->post())) {
            //如果是，判断是否符合模型的验证规则
            if ($model->validate()) {
                //判断是否是添加一级分类(parent_id是否为0)
                if ($model->parent_id) {
                    //添加非一级分类
                    //要添加非一节分类就要找到一节分类的上一级分类
                    $parent = GoodsCategory::findOne(['id' => $model->parent_id]);
                    $model->prependTo($parent);//添加到上级分类的下面
                } else {
                    //添加一级分类
                    $model->makeRoot();
                }
                //如果都符合验证要求就保存数据
//                var_dump($model);exit;
                $model->save();
                //弹出提示框，显示添加成功
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转页页面
                return $this->redirect(['goods-category/list']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        //建立树桩型分类选项,这里获取所有分类选项的id和名称
        // $options = ArrayHelper::map(GoodsCategory::find()->asArray()->all(),'id','name');
        $category = ArrayHelper::merge([['id' => 0, 'name' => '顶级分类', 'parent_id' => 0]],
            GoodsCategory::find()->asArray()->all());
        //分配视图显示
        return $this->render('add', ['model' => $model, 'category' => $category]);
    }

    //建立显示商品分类的的视图,并设置分页
    public function actionList()
    {
        //找到数据模型的所有数据
        $models = GoodsCategory::find()->orderBy('tree,lft')->all();


        return $this->render('list', ['models' => $models]);
    }

    //设置分类的删除方法类
    public function actionDel($id)
    {
        //找到删除id对的数据
        $model = GoodsCategory::findOne(['id' => $id]);
        //var_dump($model);exit;
        //不能删除根节点
        //进行删除保存
        $model->delete();
        //提示删除成功
        \Yii::$app->session->setFlash('danger', '删除成功');
        //跳转页面
        return $this->redirect(['goods-category/list']);

    }

    //设置修改数据的方法
    public function actionEdit($id)
    {
        //找到要修改的数据
        $model = GoodsCategory::findOne(['id' => $id]);
        //如果传的分类不存在的id所以进行个判断
        if ($model == null) {
            throw new NotFoundHttpException('要修改的分类不存在');
        }

        //var_dump($model);exit;
        //加载数据并判断传送方式
        if ($model->load(\Yii::$app->request->post())) {
            //如果是，判断是否符合模型的验证规则
            if ($model->validate()) {
                //判断是否是修改一级分类(parent_id是否为0)
                if ($model->parent_id) {
                    //修改非一级分类
                    //要修改非一节分类就要找到一节分类的上一级分类
                    $parent = GoodsCategory::findOne(['id' => $model->parent_id]);
                    $model->prependTo($parent);//添加到上级分类的下面
                } else {
                    if($model->getOldAttribute('parent_id'==0)){
                        //如果是一级分类就用
                        $model->save();
                    }else{
                        //就修改一级分类
                        $model->makeRoot();
                    }


                }
                //如果都符合验证要求就保存数据
//                var_dump($model);exit;
                $model->save();
                //弹出提示框，显示添加成功
                \Yii::$app->session->setFlash('warning', '修改成功');
                //跳转页页面
                return $this->redirect(['goods-category/list']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        //建立树桩型分类选项,这里获取所有分类选项的id和名称
        // $options = ArrayHelper::map(GoodsCategory::find()->asArray()->all(),'id','name');
        $category = ArrayHelper::merge([['id' => 0, 'name' => '顶级分类', 'parent_id' => 0]],
            GoodsCategory::find()->asArray()->all());
        //分配视图显示
        return $this->render('add', ['model' => $model, 'category' => $category]);
    }
}
