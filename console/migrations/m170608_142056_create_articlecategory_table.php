<?php

use yii\db\Migration;

/**
 * Handles the creation of table `articlecategory`.
 */
class m170608_142056_create_articlecategory_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('articlecategory', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->comment('简介'),
            'sort'=>$this->smallInteger(11)->comment('排序'),
            'status'=>$this->smallInteger(2)->comment('状态'),
            'is_help'=>$this->smallInteger(1)->comment('类型'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('articlecategory');
    }
}
