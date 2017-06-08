<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170608_151328_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('文章名称'),
            'intro'=>$this->text()->comment('简介'),
            'articlecategory_id'=>$this->integer()->comment('文章id'),
            'sort'=>$this->smallInteger(11)->comment('排序'),
            'status'=>$this->smallInteger(2)->comment('状态'),
            'create_time'=>$this->integer(11)->comment('类型'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
