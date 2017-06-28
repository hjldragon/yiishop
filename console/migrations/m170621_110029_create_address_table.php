<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170621_110029_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('收货人'),
            'member_id'=>$this->integer(20)->notNull()->comment('用户ID'),
            'provice'=>$this->string(30)->notNull()->comment('省份'),
            'city'=>$this->string(30)->notNull()->comment('城市'),
            'area'=>$this->string(30)->comment('地区'),
            'address'=>$this->string(100)->notNull()->comment('详细地址'),
            'tel'=>$this->integer(20)->notNull()->comment('电话号码'),
            'status'=>$this->integer()->comment('状态'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
