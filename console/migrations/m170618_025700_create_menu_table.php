<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170618_025700_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'label'=>$this->string(20)->notNull()->comment('菜单名'),
            'url'=>$this->string(200)->comment('地址'),
            'parent_id'=>$this->integer(30)->comment('父级id'),
            'sort'=>$this->integer(30)->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
