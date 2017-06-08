<?php

use yii\db\Migration;

/**
 * Handles the creation of table `articledetail`.
 */
class m170608_161533_create_articledetail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('articledetail', [
            'article_id' => $this->primaryKey()->comment('文章id'),
            'content'=>$this->text()->comment('简介'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('articledetail');
    }
}
