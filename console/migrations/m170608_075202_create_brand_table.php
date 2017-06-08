<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m170608_075202_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey()->comment('ID'),
            //名称
            'name'=>$this->string(50)->notNull()->comment('名称'),
            //简介
            'intro'=>$this->text()->comment('简介'),
            //LOGO
            'logo'=>$this->string(255)->notNull()->comment('LOGO图片'),
            //排序
            'sort'=>$this->integer()->comment('简介'),
            //状态
            'status'=>$this->smallInteger(2)->comment('状态'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
